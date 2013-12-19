<?
if ( !isset( $validfile ) || $validfile == false ) exit;

/**
 * This is the codeblock that will deal with a TAKE request from a bank employee. These are the rules
 * for taking: You can take a request if
 * - curr_employee = 0
 * - curr_employee = dead
 * else, you can't take it.
 */
if ( isset( $_POST['take'] ) )
{
	$q = $db->query( "SELECT * FROM bank_accounts WHERE account_number=". $db->prepval( $_POST['req_id'] ) );
	$q2 = $db->query( "SELECT * FROM bank_accounts WHERE curr_employee=". $char->character_id );
	if ( $db->getRowCount( $q ) == 0 )
	{
		$_SESSION['msg'] = "<p><strong>That account request does not exist! Please refrain from trying to use exploits!</strong></p>";
	}
	elseif ( $db->getRowCount( $q2 ) > 0 )
	{
		$_SESSION['msg'] = "<p><strong>You are already working on another bank account request!</strong></p>";
	}
	else
	{
		$r = $db->fetch_array( $q );
		if ( $r['owner_id'] == $char->character_id )
		{
			$_SESSION['msg'] = "<p><strong>You cannot start working on requests of your own! You are supposed to be unbiased!</strong></p>";
		}
		elseif ( $r['curr_employee'] == $char->character_id )
		{
			$_SESSION['msg'] = "<p><strong>You are already working on this request!</strong></p>";
		}
		elseif ( $r['curr_employee'] != 0 )
		{
			$q = $db->query( "SELECT * FROM char_characters INNER JOIN char_ranks ON char_ranks.id=char_characters.rank_id WHERE id=". $r['curr_employee'] );
			if ( $db->getRowCount( $q ) == 0 )
			{
				$r['curr_employee'] = 0;
			}
			else
			{
				$c = $db->fetch_array( $q );
				if ( $c['health'] <= 0 || $c['occupation_id'] != $OCCNEEDED )
					$r['curr_employee'] = 0;
				else
					$_SESSION['msg'] = "<p><strong>A colleague of yours has already taken this request for further processing!</strong></p>";
			}
		}
		else
		{
			$db->query( "UPDATE bank_accounts SET curr_employee=". $char->character_id ." WHERE account_number=". $r['account_number'] );
			$_SESSION['msg'] = "<p><strong>You are now working on ". $r['firstname'] ." ". $r['lastname'] ."'". ( substr( $r['lastname'], strlen( $r['lastname'] ) -1 ) == "s" ? "" : "s" ) ." request for a new ". getBankAccountTypeString( $r['account_type'] ) ."!</strong></p>";
		}
	}
}

/**
 * This is the codeblock that will deal with a DROP request from a bank employee. An employee
 * can only drop the request he's working on if he's the current employee working on it.
 */
if ( isset( $_POST['drop'] ) )
{
	$q = $db->query( "SELECT * FROM bank_accounts WHERE account_number=". $db->prepval( $_POST['req_id'] ) );
	if ( $db->getRowCount( $q ) == 0 )
	{
		$_SESSION['msg'] = "<p><strong>That account request does not exist! Please refrain from trying to use exploits!</strong></p>";
	}
	else
	{
		$r = $db->fetch_array( $q );
		if ( $r['curr_employee'] != $char->character_id )
		{
			$_SESSION['msg'] = "<p><strong>You can't drop a bank account request that you weren't working on in the first place!</strong></p>";
		}
		else
		{
			$db->query( "UPDATE bank_accounts SET curr_employee=0 WHERE account_number=". $r['account_number'] );
			$_SESSION['msg'] = "<p><strong>You stopped working on ". $r['firstname'] ." ". $r['lastname'] ."'". ( substr( $r['lastname'], -1 ) == "s" ? "" : "s" ) ." request for a new ". getBankAccountTypeString( $r['account_type'] ) ."!</strong></p>";
		}
	}
}

/**
 * The CREDENTIALS request allows a bank employee to check whether the credentials as provided by the 
 * person that requests the bank account are correct or not.
 * If correct, status will be 11
 * If not correct, status will be 10
 */
if ( isset( $_POST['credentials'] ) )
{
	$q = $db->query( "SELECT * FROM bank_accounts WHERE account_number=". $db->prepval( $_POST['req_id'] ) );
	if ( $db->getRowCount( $q ) == 0 )
	{
		$_SESSION['msg'] = "<p><strong>That account request does not exist! Please refrain from trying to use exploits!</strong></p>";
	}
	elseif ( time() < $char->earn_timer2 )
	{
		$_SESSION['msg'] = "<p><strong>You cannot yet investigate into this person's credentials. Wait a little longer!</strong></p>";
	}
	else
	{
		$r = $db->fetch_array( $q );

		if ( $r['curr_employee'] != $char->character_id )
		{
			$_SESSION['msg'] = "<p><strong>You are not working on this request! Leave the execution of tasks related to this request to those responsible!</strong></p>";
		}
		else
		{
			/* Fetch the player in question. */
			$c = $db->fetch_array( $db->query( "SELECT * FROM char_characters WHERE id=". $r['owner_id'] ) );
			$requester = new Player( $c['account_id'] );

			/* Check if the account in question should be approved or not... */
			if ( trim( strtolower( $r['firstname'] ) ) != strtolower( $requester->firstname ) || 
				 trim( strtolower( $r['lastname'] ) ) != strtolower( $requester->lastname ) )
			{
				$db->query( "UPDATE bank_accounts SET account_status=10 WHERE account_number=". $r['account_number'] );
				$_SESSION['msg'] = "<p><strong>After a thorough investigation in cooperation with the local police department you are suspecting the request form to be incorrectly filled out. This may be an attempt at fraud!</strong></p>";
			}
			else
			{
				$db->query( "UPDATE bank_accounts SET account_status=11 WHERE account_number=". $r['account_number'] );
				$_SESSION['msg'] = "<p><strong>You thoroughly investigated the credentials of ". $r['firstname'] ." ". $r['lastname'] ." but there was no evidence to suggest an attempt at fraud!</strong></p>";
			}

			/* Set the earntimer2. */
			$char->setEarnTimer2();
		}
	}
}

/**
 * The ACTION request comes along with another POST variable. Based on that variable the actual
 * course of action is decided.
 * Possible actions:
 * - Accept request.
 * - Decline request.
 * - Report fraud.
 */
if ( isset( $_POST['action'] ) )
{
	$q = $db->query( "SELECT * FROM bank_accounts WHERE account_number=". $db->prepval( $_POST['req_id'] ) );
	if ( $db->getRowCount( $q ) == 0 )
	{
		$_SESSION['msg'] = "<p><strong>That account request does not exist! Please refrain from trying to use exploits!</strong></p>";
	}	
	elseif ( $_POST['option'] == "accept" ) /* In case of accept. */
	{
		$r = $db->fetch_array( $q );
		if ( $r['curr_employee'] != $char->character_id )
		{
			$_SESSION['msg'] = "<p><strong>You are not working on this request! Leave the execution of tasks related to this request to those responsible!</strong></p>";
		}
		else
		{
			$requester = new Player( $r['owner_id'], false );

			$goodchoice = ( $r['firstname'] == $requester->firstname && $r['lastname'] == $requester->lastname );
			$money = $goodchoice ? 1500 : 500;
			$bankexp = $goodchoice ? 15 : 5;
			$char->setCleanMoney( $char->getCleanMoney() + $money );
			$char->setBankExp( $char->getBankExp() + $bankexp );

			/* Update that bank account request. */
			$db->query( "UPDATE bank_accounts SET account_status=2 WHERE account_number=". $r['account_number'] );

			/* Tell the player about his/her new bank account. */
			$msg = "After careful investigation of your request to open a new ". getBankAccountTypeString( $r['account_type'] ) ." with the Bank of ". $business['location_name'] ." no objections could be raised to processing your request further. You are now in the possession of a new ". getBankAccountTypeString( $r['account_type'] ) ." containing a balance of $". $r['balance'] .". The bank account number of your new account is ". $r['account_number'] .". You can always find this number in your character statistics screen.";
			$requester->addEvent( getBankAccountTypeString( $r['account_type'] ) . " request approval.", $msg );

			$_SESSION['msg'] = "<p><strong>You accepted ". $r['firstname'] ." ". $r['lastname'] ."'". ( substr( $r['lastname'], -1 ) == "s" ? "" : "s" ) ." request for a new ". getBankAccountTypeString( $r['account_type'] ) .". You got paid for your efforts.</strong></p>";
		}
	}
	elseif ( $_POST['option'] == "decline" ) /* In case of decline. */
	{
		$r = $db->fetch_array( $q );
		if ( $r['curr_employee'] != $char->character_id )
		{
			$_SESSION['msg'] = "<p><strong>You are not working on this request! Leave the execution of tasks related to this request to those responsible!</strong></p>";
		}
		else
		{
			$requester = new Player( $r['owner_id'], false );

			$goodchoice = ( $r['firstname'] != $requester->firstname || $r['lastname'] != $requester->lastname );
			$money = $goodchoice ? 1500 : 500;
			$bankexp = $goodchoice ? 15 : 5;
			$char->setCleanMoney( $char->getCleanMoney() + $money );
			$char->setBankExp( $char->getBankExp() + $bankexp );

			/* Return the initial deposit. */
			$requester->setCleanMoney( $requester->getCleanMoney() + $r['balance'] );

			/* Delete the bank account request. */
			$db->query( "DELETE FROM bank_accounts WHERE account_number=". $r['account_number'] );

			/* Tell the player he should be more thruthful... */
			$msg = "After careful investigation of your request to open a new ". getBankAccountTypeString( $r['account_type'] ) ." with the Bank of ". $business['location_name'] ." it was decided to have it declined. ". ( $r['account_status'] == 11 ? "This is strange because you're sure you've filled the forms correctly! Perhaps something fishy's going on?" : "If you require assistance in opening a new bank account, feel free to ask our staff." ) ." Your initial deposit has been returned to you in cash.";
			$requester->addEvent( getBankAccountTypeString( $r['account_type'] ) . " request decline.", $msg );

			$_SESSION['msg'] = "<p><strong>You declined ". $r['firstname'] ." ". $r['lastname'] ."'". ( substr( $r['lastname'], -1 ) == "s" ? "" : "s" ) ." request for a new ". getBankAccountTypeString( $r['account_type'] ) .". You got paid for your efforts.</strong></p>";
		}
	}	
	elseif ( $_POST['option'] == "report" ) /* In case of report, we report AND decline. */
	{
		$r = $db->fetch_array( $q );
		if ( $r['curr_employee'] != $char->character_id )
		{
			$_SESSION['msg'] = "<p><strong>You are not working on this request! Leave the execution of tasks related to this request to those responsible!</strong></p>";
		}
		else
		{
			$requester = new Player( $r['owner_id'], false );

			$goodchoice = ( $r['firstname'] != $requester->firstname || $r['lastname'] != $requester->lastname );
			$money = $goodchoice ? 2500 : 100;
			$bankexp = $goodchoice ? 25 : 1;
			$char->setCleanMoney( $char->getCleanMoney() + $money );
			$char->setBankExp( $char->getBankExp() + $bankexp );

			/* Return the initial deposit. */
			$requester->setCleanMoney( $requester->getCleanMoney() + $r['balance'] );

			/* Delete the bank account request. */
			$db->query( "DELETE FROM bank_accounts WHERE account_number=". $r['account_number'] );

			/* Tell the player he should be more thruthful... */
			$msg = "After careful investigation of your request to open a new ". getBankAccountTypeString( $r['account_type'] ) ." with the Bank of ". $l['location_name'] ." it was decided to have it declined. The bank employee who handled your request also reported you to the police for having committed fraud! ". ( $r['account_status'] == 11 ? "This is strange because you're sure you've filled the forms correctly! Perhaps something fishy's going on?" : "" ) ." Your initial deposit has been returned to you in cash.";
			$requester->addEvent( getBankAccountTypeString( $r['account_type'] ) . " request decline.", $msg );

			/* TODO: report the crime. */

			$_SESSION['msg'] = "<p><strong>You reported your suspicions to the police and declined the bank account request from ". $r['firstname'] ." ". $r['lastname'] ."! You got paid for your efforts.</strong></p>";
		}
	}
	else
	{
		$_SESSION['msg'] = "<p><strong>An unknown action was requested.</strong></p>";
	}
}
?>