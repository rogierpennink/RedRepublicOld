<?
/* The local bank - allows you to open new bank accounts and operate upon
 * those accounts.
 * Apart from business accounts you can only have one account per type
 * per character.
 * Account types:
 * - Main Account
 * - Savings Account
 * - Investment Account
 * - Business Account
 */

$ext_style = "forums_style.css";
$nav = "localcity";
$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = true;
$_SESSION['ingame'] = true;
include "../includes/utility.inc.php";
$char = new Player( getUserID() );

/* Check if there is a bank in this city at all. */
$q = $db->query( "SELECT * FROM localcity INNER JOIN businesses ON localcity.business_id = businesses.id WHERE business_type=6 AND location_id=". $char->location_nr );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This city doesn't even have a bank!";
	header( "Location: index.php" );
	exit;
}
$business = $db->fetch_array( $q );

/* Retrieve the bank's settings. */
$dquery = $db->query( "SELECT * FROM bank_settings WHERE business_id=". $business['id'] );
$banksettings = $db->fetch_array( $dquery );

/* Check if player has accounts at all... */
if ( $_GET['act'] == "deposit" || $_GET['act'] == "withdraw" || $_GET['act'] == "transfer" )
{
	if ( $db->getRowCount( $db->query( "SELECT * FROM bank_accounts WHERE owner_id=". $char->character_id ) ) == 0 )
	{
		$_SESSION['message'] = "You don't have any bank accounts to perform that action with!";
		unset( $_GET['act'] );
	}
}

/* In case of a new loan request..... */
if ( isset( $_POST['request_loan'] ) )
{
	/* Check if there is a pledge and if it's indeed in the player's inventory... */
	$checkq = $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id ." AND item_id=". $db->prepval( $_POST['pledge'] ) );
	/* Check if this player doesn't already have a loan... */
	$checkq2 = $db->query( "SELECT * FROM bank_loans WHERE char_id=". $char->character_id ." AND loan_status<>10" );
	if ( $char->homecity_nr != $char->location_nr )
	{
		$_SESSION['message'] = "You have to be in your homecity to request a loan!";
	}
	elseif ( $char->agg_timer2 > time() )
	{
		$_SESSION['message'] = "You cannot request a new loan yet! Try again later!";
	}
	elseif ( $db->getRowCount( $checkq2 ) > 0 )
	{
		$_SESSION['message'] = "You still have an unarchived loan running! You can't request any new loans yet!";
	}
	elseif ( $_POST['pledge'] != 0 && $db->getRowCount( $checkq ) == 0 )
	{
		$_SESSION['message'] = "You don't have the requested pledge in your inventory! No cheating please!";
	}
	else
	{
		$pledge = new Item( $_POST['pledge'] );
		if ( getMaxLoanAmount( $char->bank_trust ) + $pledge->worth < $_POST['loan_amount'] )
		{
			$_SESSION['message'] = "The amount you requested for this loan exceeds your loan limit". ( $_POST['pledge'] == 0 ? "!" : ", even with your current pledge!" );
		}
		else
		{
			/* Insert new loan request... */
			$db->query( "INSERT INTO bank_loans SET char_id=". $char->character_id .", loan_amount=". $db->prepval( $_POST['loan_amount'] ) .", pledge=". $db->prepval( $_POST['pledge'] ) .", case_notes='". date( TIME_FORMAT, time() ) . " - Loan requested by ". $char->nickname ."', business_id=". $business['business_id'] );

			/* Delete pledge from inventory. */
			$db->query( "DELETE FROM char_inventory WHERE char_id=". $char->character_id ." AND item_id=". $pledge->item_id );

			$_SESSION['message'] = "Your loan has been added to the list of loan requests. You will now have to wait for a bank employee to accept the loan!";
		}
	}
}

/* In case of a loan repay request... */
if ( isset( $_POST['repay_loan'] ) && $_POST['repay_amount'] > 0 )
{
	/* Check the loan's ID. */
	$checkq = $db->query( "SELECT * FROM bank_loans WHERE loan_id=". $db->prepval( $_POST['loan_id'] ) ." AND char_id=". $char->character_id ." AND loan_status<>10" );
	if ( $char->homecity_nr != $char->location_nr )
	{
		$_SESSION['message'] = "You have to be in your homecity to repay a loan!";
	}
	elseif ( $db->getRowCount( $checkq ) == 0 )
	{
		$_SESSION['message'] = "An invalid loan ID was specified... No cheating please!";
	}
	elseif ( $_POST['repay_amount'] > $char->getCleanMoney() && $_POST['source'] == 0 )
	{
		$_SESSION['message'] = "You don't have that much money on hand!";
	}
	elseif ( $_POST['repay_amount'] > $char->getDirtyMoney() && $_POST['source'] == 1 )
	{
		$_SESSION['message'] = "You don't have that much dirty money!";
	}
	else
	{
		$loan = $db->fetch_array( $checkq );
		$baquery = $db->query ( "SELECT * FROM bank_accounts WHERE owner_id=". $char->character_id ." AND account_type=0 AND account_number=". $db->prepval( $_POST['source'] ) );
		$ba = $db->fetch_array( $baquery );

		/* If the money is more than the current debt, deny.. */
		if ( $loan['loan_status'] == 0 )
		{
			$_SESSION['message'] = "This loan has not yet been accepted! You can't repay that which you have not yet received!";
		}
		elseif ( $loan['loan_status'] == 10 )
		{
			$_SESSION['message'] = "This loan has already been archived! You don't need to pay anything anymore!";
		}
		elseif ( $db->getRowCount( $baquery ) == 0 && $_POST['source'] != 0 && $_POST['source'] != 1 )
		{
			$_SESSION['message'] = "You specified an invalid source for repaying!";
		}
		elseif ( $_POST['repay_amount'] > $ba['balance'] && $_POST['source'] != 0 && $_POST['source'] != 1 )
		{
			$_SESSION['message'] = "You don't have that much money on your ". getBankAccountTypeString( $ba['account_type'] ) ."!";
		}
		elseif ( ceil( $loan['loan_amount'] * ( 1 + $loan['rent'] / 100 ) ) - $loan['loan_repaid'] < $_POST['repay_amount'] )
		{
			$_SESSION['message'] = "Fool! Why would you pay back more than you owe the bank?";
		}		
		else
		{
			/* Update the loan... */
			$newnotes = $loan['case_notes'] . "\n" . date( TIME_FORMAT, time()  ) . " - An amount of $". $_POST['repay_amount'] ." was repaid by ". $char->nickname ."!";
			$db->query( "UPDATE bank_loans SET loan_repaid=loan_repaid+". $db->prepval( $_POST['repay_amount'] ) .", case_notes=". $db->prepval( $newnotes ) ." WHERE loan_id=". $loan['loan_id'] );

			/* Take the money from the specified source. */
			if ( $_POST['source'] == 0 )
			{
				$char->setCleanMoney( $char->getCleanMoney() - $_POST['repay_amount'] );
			}
			elseif ( $_POST['source'] == 1 )
			{
				$char->setDirtyMoney( $char->getDirtyMoney() - $_POST['repay_amount'] );
			}
			else
			{
				/* Insert transaction log... */
				$db->query( "INSERT INTO bank_transactions SET account_number=". $ba['account_number'] .", transaction_type=2, amount=". $db->prepval( $_POST['repay_amount'] ) .", balance=". $db->prepval( $ba['balance'] - $_POST['repay_amount'] ) .", to_player=-". $business['bank_id'] .", `datetime`='". date( "Y-m-d H:i:s" ) ."', fee=0" );

				/* Take from bank... */
				$db->query( "UPDATE bank_accounts SET balance=balance-". $db->prepval( $_POST['repay_amount'] ) ." WHERE account_number=". $ba['account_number'] );
			}

			$_SESSION['message'] = "You repaid $". $_POST['repay_amount'] ." of your debts!";			
		}
	}
}

/* In case of a new transfer request..... */
if ( isset( $_POST['transfer'] ) )
{
	/* Check if the selected account number is indeed the player's account. */
	$accq = $db->query( "SELECT * FROM bank_accounts WHERE owner_id=".$char->character_id." AND account_number=".$db->prepval( $_POST['from_account'] ) );
	$acc = $db->fetch_array( $accq );

	/* Get information about the target account number. */
	$targetq = $db->query( "SELECT * FROM bank_accounts INNER JOIN char_characters ON bank_accounts.owner_id=char_characters.id WHERE account_number=".$db->prepval( $_POST['to_account'] ) );
	$targetacc = $db->fetch_array( $targetq );

	if ( !ctype_digit( $_POST['amount'] ) || $_POST['amount'] < 1 )
	{
		$_SESSION['message'] = "Please enter a numeric value greater than 0 into the amount field.";
		$_GET['act'] = "transfer";
	}	
	elseif ( $db->getRowCount( $accq ) == 0 )
	{
		$_SESSION['message'] = "Please make sure you transfer funds from an account of your own.";
		$_GET['act'] = "withdraw";
	}
	elseif ( $db->getRowCount( $targetq ) == 0 )
	{
		$_SESSION['message'] = "The account you wish to transfer funds to doesn't exist!";
		$_GET['act'] = "transfer";
	}
	elseif ( $targetacc['account_number'] == $acc['account_number'] )
	{
		$_SESSION['message'] = "You cannot transfer funds to the account you're also transferring funds!";
		$_GET['act'] = "transfer";
	}
	elseif ( $targetacc['account_type'] != 0 && $targetacc['owner_id'] != $acc['owner_id'] )
	{
		$_SESSION['message'] = "You can only transfer funds to the checking account of another player!";
		$_GET['act'] = "transfer";
	}
	elseif ( $_POST['amount'] > $acc['balance'] )
	{
		$_SESSION['message'] = "You don't have the amount of money to make this transfer!";
		$_GET['act'] = "transfer";
	}
	else
	{
		$fee = 0; if ( $targetacc['owner_id'] != $acc['owner_id'] ) $fee = $banksettings['transfer_fee'] / 1000;
		$result = false;

		/* Transfer funds from the first account to the second account. */
		if ( $acc['balance'] >= $_POST['amount'] * ( 1 + $fee ) )
		{
			$result = $db->query( "UPDATE bank_accounts SET balance=balance-". ( $db->prepval( $_POST['amount'] ) * ( 1 + $fee ) ) ." WHERE account_number=". $db->prepval( $acc['account_number'] ) );
			if ( $result !== false )
			{
				if ( ( $result = $db->query( "UPDATE bank_accounts SET balance=balance+". $db->prepval( $_POST['amount'] ) ." WHERE account_number=". $db->prepval( $targetacc['account_number'] ) ) ) === false )
				{
					$db->query( "UPDATE bank_accounts SET balance=balance+". ( $db->prepval( $_POST['amount'] ) * ( 1 + $fee ) ) ." WHERE account_number=". $db->prepval( $acc['account_number'] ) );
					$_SESSION['message'] = "A database error occurred on transferring your funds. Please contact an administrator.";					
				}
				else
				{
					$_SESSION['message'] = "You successfully transferred $". $_POST['amount'] ." to account number ". $targetacc['account_number'] .", paying a fee of $".( $fee * $_POST['amount'] );

					/* The transaction. */
					$db->query( "INSERT INTO bank_transactions SET account_number=". $acc['account_number'] .", transaction_type=2, amount=". $_POST['amount'] .", balance=". ( $acc['balance'] - $_POST['amount'] * ( 1 + $fee ) ) .", to_player=". $targetacc['account_number'] .", `datetime`='". date( "Y-m-d H:i:s" ) ."', fee=". ( $fee * $_POST['amount'] ) );
					$db->query( "INSERT INTO bank_transactions SET account_number=". $targetacc['account_number'] .", transaction_type=3, amount=". $_POST['amount'] .", balance=". ( $targetacc['balance'] + $_POST['amount'] ) .", from_player=". $acc['account_number'] .", `datetime`='". date( "Y-m-d H:i:s" ) ."'" );

					/* Post an event. */
					if ( $targetacc['owner_id'] != $acc['owner_id'] )
					{						
						$msg = "You have received money from bank account number ". $acc['account_number'] .". This bank account belongs to a person informally known as ". $char->nickname .". ". ( $char->gender == "m" ? "He" : "She" ) ." transferred an amount of $". $_POST['amount'] ." into bank account ". $targetacc['account_number'] .". This is your ". getBankAccountTypeString( $targetacc['account_type'] ) .".";
						$target = new Player( $targetacc['account_id'] );
						$target->addEvent( 'Transfer from '.$acc['account_number'], $msg );
					}
				}
			}
		}
		else
		{
			$result = $db->query( "UPDATE bank_accounts SET balance=balance-". ( $db->prepval( $_POST['amount'] ) ) ." WHERE account_number=". $db->prepval( $acc['account_number'] ) );
			if ( $result !== false )
			{
				if ( ( $result = $db->query( "UPDATE bank_accounts SET balance=balance+". ( $db->prepval( $_POST['amount'] ) * ( 1 - $fee ) ) ." WHERE account_number=". $db->prepval( $targetacc['account_number'] ) ) ) === false )
				{
					$db->query( "UPDATE bank_accounts SET balance=balance+". $db->prepval( $_POST['amount'] ) ." WHERE account_number=". $db->prepval( $acc['account_number'] ) );
					$_SESSION['message'] = "A database error occurred on transferring your funds. Please contact an administrator.";					
				}
				else
				{
					$_SESSION['message'] = "You successfully transferred $". ( $_POST['amount'] * ( 1 - $fee ) ) ." to account number ". $targetacc['account_number'] .", paying a fee of $".( $fee * $_POST['amount'] );	

					/* The transaction. */
					$db->query( "INSERT INTO bank_transactions SET account_number=". $acc['account_number'] .", transaction_type=2, amount=". ( $_POST['amount'] * ( 1 - $fee ) ) .", balance=". ( $acc['balance'] - $_POST['amount'] ) .", to_player=". $targetacc['account_number'] .", `datetime`='". date( "Y-m-d H:i:s" ) ."', fee=". ( $fee * $_POST['amount'] ) );
					$db->query( "INSERT INTO bank_transactions SET account_number=". $targetacc['account_number'] .", transaction_type=3, amount=". ( $_POST['amount'] * ( 1 - $fee ) ) .", balance=". ( $targetacc['balance'] + $_POST['amount'] * ( 1 - $fee ) ) .", from_player=". $acc['account_number'] .", `datetime`='". date( "Y-m-d H:i:s" ) ."'" );

					/* Post an event. */
					if ( $targetacc['owner_id'] != $acc['owner_id'] )
					{
						$msg = "You have received money from bank account number ". $acc['account_number'] .". This bank account belongs to a person informally known as ". $char->nickname .". ". ( $char->gender == "m" ? "He" : "She" ) ." transferred an amount of $". ( $_POST['amount'] * ( 1 - $fee ) ) ." into bank account ". $targetacc['account_number'] .". This is your ". getBankAccountTypeString( $targetacc['account_type'] ) .".";
						$target = new Player( $targetacc['account_id'] );
						$target->addEvent( 'Transfer from '.$acc['account_number'], $msg );
					}
				}
			}
		}

		if ( $result !== false )
		{
			$db->query( "UPDATE bank_accounts SET balance=balance+". ( $db->prepval( $_POST['amount'] ) * $fee ) ." WHERE account_number=". $business['bank_id'] );
		}

		$_GET['act'] = "overview";
	}
}

/* In case of a new withdrawal request... */
if ( isset( $_POST['withdraw'] ) )
{
	/* Check if the selected account number is indeed the player's account. */
	$accq = $db->query( "SELECT * FROM bank_accounts WHERE owner_id=".$char->character_id." AND account_type=0 AND account_number=".$db->prepval( $_POST['from_account'] ) );
	$acc = $db->fetch_array( $accq );
	if ( !ctype_digit( $_POST['amount'] ) || $_POST['amount'] < 1 )
	{
		$_SESSION['message'] = "Please enter a numeric value greater than 0 into the amount field.";
		$_GET['act'] = "withdraw";
	}	
	elseif ( $db->getRowCount( $accq ) == 0 )
	{
		$_SESSION['message'] = "Please make sure you withdraw funds from a checking account of your own.";
		$_GET['act'] = "withdraw";
	}
	elseif ( $acc['account_status'] != 2 )
	{
		$_SESSION['message'] = "You cannot withdraw funds from a frozen or unapproved bank account.";		
	}
	elseif ( $_POST['amount'] > $acc['balance'] )
	{
		$_SESSION['message'] = "You do not have $". $_POST['amount'] ." on the selected bank account!";
		$_GET['act'] = "withdraw";
	}
	else
	{
		$withdrawal = $_POST['amount'];
		$fee = $banksettings['withdrawal_fee'] / 100;

		/* Decide the actual withdrawal amount. */
		if ( $acc['balance'] >= $withdrawal * ( 1 + $fee ) )		
			$withdrawal *= ( 1 + $fee );

		/* Take the money from the bank account. */
		if ( $db->query( "UPDATE `bank_accounts` SET `balance`=`balance`-". $db->prepval( $withdrawal ) ." WHERE `account_number`=". $db->prepval( $_POST['from_account'] ) ) === false )
		{
			$_SESSION['message'] = "A database error occurred on withdrawing your funds. Please contact an administrator.";
			$_GET['act'] = "withdraw";
		}
		else
		{
			if ( $acc['balance'] >= $withdrawal * ( 1 + $fee ) )		
				$withdrawal *= ( 1 + $fee );
			else
				$withdrawal *= ( 1 - $fee );

			/* Add funds to the character's clean money. */
			if ( $acc['balance'] < $withdrawal * ( 1 + $fee ) )
			{
				$char->setCleanMoney( $char->getCleanMoney() + $withdrawal );
				$db->query( "INSERT INTO bank_transactions SET account_number=". $acc['account_number'] .", transaction_type=1, amount=$withdrawal, fee=". ( $fee * $_POST['amount'] ) .", balance=". ( $acc['balance'] - $_POST['amount'] ) .", `datetime`='". date( "Y-m-d H:i:s" ) ."'" );
			}
			else
			{
				$char->setCleanMoney( $char->getCleanMoney() + $_POST['amount'] );
				$db->query( "INSERT INTO bank_transactions SET account_number=". $acc['account_number'] .", transaction_type=1, amount=". $_POST['amount'] .", fee=". ( $fee * $_POST['amount'] ) .", balance=". ( $acc['balance'] - $_POST['amount'] ) .", `datetime`='". date( "Y-m-d H:i:s" ) ."'" );
			}

			/* Add the fee to the bank. */
			$db->query( "UPDATE bank_accounts SET balance=balance+". ( $db->prepval( $_POST['amount'] ) * $fee ) ." WHERE account_number=". $business['bank_id'] );

			$_SESSION['message'] = "Your funds have been withdrawn from the following bank account: ". $_POST['from_account'];		
			$_GET['act'] = "overview";
		}
	}
}

/* In case a new deposit request should be processed. */
if ( isset( $_POST['deposit'] ) )
{
	/* Check if the selected account number is indeed the player's account. */
	$accq = $db->query( "SELECT * FROM bank_accounts WHERE owner_id=".$char->character_id." AND account_number=".$db->prepval( $_POST['to_account'] ) );
	$acc = $db->fetch_array( $accq );
	if ( !ctype_digit( $_POST['amount'] ) || $_POST['amount'] < 1 )
	{
		$_SESSION['message'] = "Please enter a numeric value greater than 0 into the amount field.";
		$_GET['act'] = "deposit";
	}
	elseif ( $_POST['amount'] > $char->getCleanMoney() )
	{
		$_SESSION['message'] = "You do not have $". $_POST['amount'] ." in cash!";
		$_GET['act'] = "deposit";
	}
	elseif ( $db->getRowCount( $accq ) == 0 )
	{
		$_SESSION['message'] = "Please make sure you deposit funds into one of your own bank accounts.";
		$_GET['act'] = "deposit";
	}
	elseif ( $acc['account_status'] != 2 )
	{
		$_SESSION['message'] = "You cannot deposit funds into a frozen or unapproved bank account.";		
	}
	else
	{
		$deposit = $_POST['amount'];
		$fee = $banksettings['deposit_fee'] / 100;

		/* Check if the char has enough money to pay the fee. */
		if ( $char->getCleanMoney() < $deposit * ( $fee + 1 ) )
			$deposit *= ( 1 - $fee );

		/* Insert into the selected bank account. */
		if ( $db->query( "UPDATE `bank_accounts` SET `balance`=`balance`+". $db->prepval( $deposit ) ." WHERE `account_number`=". $db->prepval( $_POST['to_account'] ) ) === false )
		{
			$_SESSION['message'] = "A database error occurred on depositing your funds. Please contact an administrator.";
			$_GET['act'] = "deposit";
		}
		else
		{
			/* Take funds from character. */
			if ( $char->getCleanMoney() < $_POST['amount'] * ( $fee + 1 ) )
			{
				$char->setCleanMoney( $char->getCleanMoney() - $_POST['amount'] );
				$db->query( "INSERT INTO bank_transactions SET account_number=". $acc['account_number'] .", transaction_type=0, amount=". ( $_POST['amount'] * ( 1 - $fee ) ) .", fee=". ( $fee * $_POST['amount'] ) .", balance=". ( $acc['balance'] + $_POST['amount'] * ( 1 - $fee ) ) .", `datetime`='". date( "Y-m-d H:i:s" ) ."'" );
			}
			else
			{
				$char->setCleanMoney( $char->getCleanMoney() - ( $_POST['amount'] * ( $fee + 1 ) ) );
				$db->query( "INSERT INTO bank_transactions SET account_number=". $acc['account_number'] .", transaction_type=0, amount=". $_POST['amount'] .", fee=". ( $fee * $_POST['amount'] ) .", balance=". ( $acc['balance'] + $_POST['amount'] ) .", `datetime`='". date( "Y-m-d H:i:s" ) ."'" );
			}

			/* Add the fee to the bank. */
			$db->query( "UPDATE bank_accounts SET balance=balance+". ( $db->prepval( $_POST['amount'] ) * $fee ) ." WHERE account_number=". $business['bank_id'] );

			$_SESSION['message'] = "Your funds have been deposited into the following bank account: ". $_POST['to_account'];
			$_GET['act'] = "overview";
		}
	}
}

/* In case a new bank-account request should be processed. */
if ( isset( $_POST['newacc'] ) )
{
	if ( strlen( $_POST['fname'] ) < 3 || !ctype_alpha( $_POST['fname'] ) )
	{
		$_SESSION['message'] = "You should enter a first name consisting of more than three alphabetic characters.";
		$_GET['act'] = "newacc";
	}
	elseif ( strlen( $_POST['lname'] ) < 3 || !ctype_alpha( $_POST['lname'] ) )
	{
		$_SESSION['message'] = "You should enter a last name consisting of more than three alphabetic characters.";
		$_GET['act'] = "newacc";
	}
	elseif ( !ctype_digit( $_POST['deposit'] ) || $_POST['deposit'] < 5000 )
	{
		$_SESSION['message'] = "Your initial deposit should be equal to or higher than $5000!";
		$_GET['act'] = "newacc";
	}
	elseif ( $_POST['deposit'] > $char->getCleanMoney() )
	{
		$_SESSION['message'] = "You unfortunately do not have enough money to make that deposit!";
		$_GET['act'] = "newacc";
	}
	else
	{
		/* Check number of accounts and account types (Only one account per type is allowed).
		 * Since you can have only one of each type, checking if that type has been taken suffices.
		 */
		$q = $db->query( "SELECT * FROM bank_accounts WHERE owner_id=". $char->character_id ." AND account_type=". $db->prepval( $_POST['acc_type'] ) );
		if ( $db->getRowCount( $q ) > 0 )
		{
			$_SESSION['message'] = "You already have a ". getBankAccountTypeString( $_POST['acc_type'] ) ."!";
			$_GET['act'] = "newacc";
		}
		else
		{
			/* Generate a new account number and check if it exists or not. */
			$num = mt_rand( 1000000, 9999999 );
			while ( $db->getRowCount( $db->query( "SELECT * FROM bank_accounts WHERE account_number=$num" ) ) > 0 )
				$num = mt_rand( 1000000, 9999999 );

			/* Insert a new, unapproved, bank account. */
			$db->query( "INSERT INTO bank_accounts SET account_number=$num, owner_id=".$char->character_id.", account_type=". $db->prepval( $_POST['acc_type'] ) .", account_status=0, balance=". $db->prepval( $_POST['deposit'] ) .", firstname=". $db->prepval( $_POST['fname'] ) .", lastname=". $db->prepval( $_POST['lname'] ) .", business_id=". $business['id'] );

			/* Take the money from the player. */
			$char->setCleanMoney( $char->getCleanMoney() - $_POST['deposit'] );

			$_SESSION['message'] = "You successfully requested the creation of a new bank account. All you can do now is wait!";

			$_GET['act'] = "services";
		}
	}
}

include_once "../includes/header.php";
?>

<h1>The Bank of <?=$char->location;?></h1>
<p>Welcome to the Bank of <?=$char->location;?>, our employees stand ready to serve you. If you don't have an account yet make sure you open one with us! You'll be able to access your funds anywhere in the world at virtually no cost! Ask our helpdesk ladies for more information!</p>

<?
if ( isset( $_SESSION['message'] ) )
{
	echo "<div style='width: 90%; margin-left: auto; margin-right: auto;'>";
	echo "<p><strong>". $_SESSION['message'] ."</strong></p>";
	echo "</div>";
	unset( $_SESSION['message'] );
}

/* Check if there are accounts... */
$checkquery = $db->query( "SELECT * FROM bank_accounts WHERE owner_id=".$char->character_id." AND account_status=2" );
$nobank = ( $db->getRowCount( $checkquery ) == 0 ) ? true : false;

/* Show the content depending on our current 'action'. */
if ( !$nobank || $_GET['act'] == "helpdesk" || $_GET['act'] == "newacc" || $_GET['act'] == "loans" )
	switch ( $_GET['act'] )
	{
		case "newacc": include_once "bank.newacc.php"; break;
		case "deposit": include_once "bank.deposit.php"; break;
		case "withdraw": include_once "bank.withdraw.php"; break;
		case "transfer": include_once "bank.transfer.php"; break;
		case "helpdesk": include_once "bank.helpdesk.php"; break;
		case "overview": include_once "bank.overview.php"; break;
		case "loans": include_once "bank.loans.php"; break;
		case "services": include_once "bank.default.php"; break;
		default: include_once "bank.overview.php";
	}
else
{
	if ( $_GET['act'] == "deposit" || $_GET['act'] == "withdraw" || $_GET['act'] == "transfer" )
	{
		echo "<div style='width: 90%; margin-left: auto; margin-right: auto;'>";
		echo "<p><strong>You do not have any bank accounts to do that with!</strong></p>";
		echo "</div>";
	}

	include_once "bank.default.php";
}

include_once "../includes/footer.php"

?>