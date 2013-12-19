<?
$cron = true;
include_once "/home/roger/public_html/includes/utility.inc.php";

/**
 * The following code checks for new bank account requests and approves/disproves them
 * if there are no bank employees who can do that.
 */
function updateBankAccounts()
{
	global $db;

	$q = $db->query( "SELECT * FROM locations" );
	while ( $l = $db->fetch_array( $q ) )
	{		
		if ( $db->getRowCount( $db->query( "SELECT * FROM char_characters AS c INNER JOIN locations AS l ON c.homecity = l.id INNER JOIN char_ranks AS r ON c.rank_id = r.id INNER JOIN char_occupations AS o ON r.occupation_id = o.id WHERE o.career_type=2 AND l.id=".$l['id'] ) ) == 0 )
		{
			/* Approve or disapprove bank account requests. */
			$q2 = $db->query( "SELECT * FROM bank_accounts INNER JOIN localcity ON bank_accounts.business_id = localcity.business_id WHERE account_status=0 AND location_id=". $l['id'] );
			while ( $r = $db->fetch_array( $q2 ) )
			{
				/* Fetch the player in question. */
				$c = $db->fetch_array( $db->query( "SELECT * FROM char_characters WHERE id=". $r['owner_id'] ) );
				$char = new Player( $c['account_id'] );

				/* Check if the account in question should be approved or not... */
				if ( trim( strtolower( $r['firstname'] ) ) != strtolower( $char->firstname ) || 
					 trim( strtolower( $r['lastname'] ) ) != strtolower( $char->lastname ) )
				{
					/* Return the initial deposit. */
					$char->setCleanMoney( $char->getCleanMoney() + $r['balance'] );

					/* Delete the bank account request. */
					$db->query( "DELETE FROM bank_accounts WHERE account_number=". $r['account_number'] );

					/* Tell the player he should be more thruthful... */
					$msg = "After careful investigation of your request to open a new ". getBankAccountTypeString( $r['account_type'] ) ." with the Bank of ". $l['location_name'] ." it was decided to have it declined. Our investigation into your credentials has shown that the bank account subscription form has not correctly been filled. If you require assistance in opening a new bank account, feel free to ask our staff. Your initial deposit has been returned to you in cash.";
					$char->addEvent( getBankAccountTypeString( $r['account_type'] ) . " request decline.", $msg );
				}
				else
				{
					/* Update that bank account request. */
					$db->query( "UPDATE bank_accounts SET account_status=2 WHERE account_number=". $r['account_number'] );

					/* Tell the player about his/her new bank account. */
					$msg = "After careful investigation of your request to open a new ". getBankAccountTypeString( $r['account_type'] ) ." with the Bank of ". $l['location_name'] ." no objections could be raised to processing your request further. You are now in the possession of a new ". getBankAccountTypeString( $r['account_type'] ) ." containing a balance of $". $r['balance'] .". The bank account number of your new account is ". $r['account_number'] .". You can always find this number in your character statistics screen.";
					$char->addEvent( getBankAccountTypeString( $r['account_type'] ) . " request approval.", $msg );
				}				
			}
		}
	}	
}

/**
 * The following function will deal with all new study requests.
 */
function updateUniRequests()
{
	global $db;

	$q = $db->query( "SELECT * FROM businesses WHERE owner_id=0 AND business_type=5" );
	while ( $r = $db->fetch_array( $q ) )
	{
		$degq = $db->query( "SELECT * FROM char_degrees WHERE degree_status=0 AND business_id=". $r['id'] );
		while ( $deg = $db->fetch_array( $degq ) )
		{
			$char = new Player( $deg['character_id'], false );

			$accept = true;

			if ( trim( strtolower( $deg['fname'] ) ) != strtolower( $char->firstname ) ) $accept = false;
			if ( trim( strtolower( $deg['lname'] ) ) != strtolower( $char->lastname ) ) $accept = false;
			if ( $db->getRowCount( $db->query( "SELECT * FROM bank_accounts WHERE owner_id=".$char->character_id." AND account_number=". $deg['bank_account'] ) ) == 0 ) $accept = false;

			if ( $deg['authed'] == 1 )
			{
				$authq = $db->query( "SELECT * FROM char_characters WHERE firstname=". $db->prepval( $deg['auth_fname'] ) ." AND lastname=". $db->prepval( $deg['auth_lastname'] ) );
				if ( $db->getRowCount( $authq ) == 0 ) $accept = false;
				$auth = $db->fetch_array( $authq );
				if ( $db->getRowCount( $db->query( "SELECT * FROM bank_accounts WHERE owner_id=".$auth['id']." AND account_number=". $deg['bank_account'] ) ) == 0 ) $accept = false;
			}

			if ( $accept == false )
			{
				/* Update degree and add event. */
				$db->query( "DELETE FROM char_degrees WHERE degree_id=". $deg['degree_id'] );

				/* Tell the player he should be more thruthful... */
				$msg = "After careful investigation of your request to start studying at ". $r['name'] ." it was decided to have it declined. Our investigation into your credentials has shown that the faculty subscription form has not correctly been filled. If you require assistance in subscribing for a course, you may want to reconsider studying at academic level.";
				$char->addEvent( "Academic subscription request declined", $msg );
			}
			else
			{
				/* Update degree and add event. */
				$db->query( "UPDATE char_degrees SET degree_status=1 WHERE degree_id=". $deg['degree_id'] );

				/* Take from bank account. */
				$accnum = $deg['authed'] == 0 ? $deg['bank_account'] : $deg['auth_bank_account'];
				$db->query( "UPDATE bank_accounts SET balance=balance-5000 WHERE account_number=$accnum" );
				$bank = $db->getRowsFor( "SELECT balance FROM bank_accounts WHERE account_number=$accnum" );
				$db->query( "INSERT INTO bank_transactions SET account_number=$accnum, transaction_type=4, amount=5000, balance=". $bank['balance'] .", fee=0, to_player=". $r['bank_id'] .", from_player=$accnum, `datetime`=". date( "'Y-m-d H:i:s'" ) );

				/* Tell the player he should be more thruthful... */
				$msg = "After careful investigation of your request to start studying at ". $r['name']." no objections could be raised to processing your request further. You have now officially started studying at our university, and you may visit it regularly to continue your studies. The college fee that applies is $5000. This amount has been taken from the specified bank account.";
				$char->addEvent( "Academic subscription request approved", $msg );
			}
		}
	}
}

/**
 * The following function will deal with all closed auctions. It will see if an auction has closed and send the item to the 
 * highest bidder or the seller if there is no bidder.
 */
function updateAuctions()
{
	global $db;

	$q = $db->query( "SELECT * FROM auctions INNER JOIN locations ON auctions.location_id = locations.id WHERE close_time <= ". time() );
	while ( $r = $db->fetch_array( $q ) )
	{
		$business = $db->query( "SELECT * FROM auctionhouse INNER JOIN businesses ON auctionhouse.business_id=businesses.id WHERE business_id=". $r['business_id'] );
		$business = $db->fetch_array( $business );

		$seller = new Player( $r['seller'], false );
		$item = new Item( $r['item_id'] );
		$buyer = new Player( $r['current_bidder'], false );

		/* If the item hasn't been sold... */
		if ( $r['current_bidder'] == 0 )
		{
			if ( $seller->isAlive() )
				$seller->addEvent( "Auction Unsuccessfull", "This is an automated message from the ". $r['location_name'] ." Auction House to inform you that your item, '". $item->name ."', has not been bought by anyone. Your item has been sent to your mailbox where it will probably arrive shortly. In order to sell an other item, or perhaps purchase one, please remember you are always welcome back at the ". $r['location_name'] ." Auction House!" );

			/* Insert auction item into mailbox of seller. */
			$msg = "We hereby include the item you did not manage to sell aut our Auction House! For another try, please remember to visit us again!";
			$db->query( "INSERT INTO char_mailbox SET char_id=". $seller->character_id .", item_id=". $item->item_id .", sender=". ( -1 * $business['business_id'] ) .", message='$msg'" );		

			/* Delete the auction. */
			$db->query( "DELETE FROM auctions WHERE auction_id=". $r['auction_id'] );
		}
		else
		{
			/* Delete the auction after transferring the fee to the auction house and the profit - fee to the seller */
			$ah_profit = ( $seller->isAlive() ) ? $result['current_bid'] * ( $business['auction_fee'] / 100 ) : $result['current_bid'];
			$db->query( "UPDATE bank_accounts SET balance=balance+". $ah_profit ." WHERE account_number=". $business['bank_id'] );
			
			if ( $seller->isAlive() )
			{
				$seller->addEvent( "Auction Successful!", "This is an automated message from the ". $r['location_name'] ." Auction House to inform you that your item, '". $item->name ."', has been successfully sold! The auction house took a fee of ". ( $r['auction_fee'] / 100 ) ."% ($$ah_profit) from your profits. The remaining profits, an amount of $". ( $r['current_bid'] - $ah_profit ) ." has been sent to you in cash!" );

				$msg = "Within this mail we have included the sum of money that the auction of your item, ". $item->name .", has yielded you.";
				$db->query( "INSERT INTO char_mailbox SET char_id=". $seller->character_id .", money=". ( $r['current_bid'] - $ah_profit ) .", sender=". ( -1 * $business['business_id'] ) .", message='". stripslashes( $msg ) ."'" );
			}

			if ( $buyer->isAlive() )
			{
				/* Put the item into the buyer's mailbox. */
				$msg = "We hereby include the item you have bought from our Auction House. Enjoy it, and remember to always come back to us for sales and bargains!";
				$db->query( "INSERT INTO char_mailbox SET char_id=". $buyer->character_id .", item_id=". $item->item_id .", sender=". ( -1 * $business['business_id'] ) .", message='$msg'" );		

				/* Let the buyer know he won an auction. */
				$buyer->addEvent( "Auction Won", "This is an automated message from the ". $r['location_name'] ." Auction House to inform you that you have won an auction for '". $item->name ."'. The item has been sent to your mailbox where it should arrive shortly. For future purchases please remember to visit us again in ". $r['location_name'] ."!" );
			}

			$db->query( "DELETE FROM auctions WHERE auction_id=". $r['auction_id'] );
		}				
	}
}

/**
 * The deliverMail function will basically set all has_arrived fields in the current mailboxes to true.
 */
function deliverMail()
{
	global $db;
	$db->query( "UPDATE char_mailbox SET has_arrived=1" );
}

updateBankAccounts();
updateUniRequests();
updateAuctions();
deliverMail();
?>