<?
if ( !is_numeric( $_GET['id'] ) )
{
	echo "<p><strong>Please do not try to use exploits. We really don't let our URL parameters go unchecked...</strong></p>";	
}
else
{
	/* Check for a global auction house or not. */
	$setting = $db->fetch_array( $db->query( "SELECT value FROM settings_general WHERE setting='global_ah'" ) );

	/* Check if the auction exists and if it is purchasable at this particular AH. */
	$sql = "SELECT * FROM auctions INNER JOIN char_characters ON auctions.seller = char_characters.id WHERE auction_id=". $db->prepval( $_GET['id'] );
	$sql .= ( $setting['value'] == 'true' ) ? "" :  "AND business_id=". $business['business_id'];
	$result = $db->query( $sql );

	if ( $db->getRowCount( $result ) == 0 )
	{
		echo "<p><strong>You cannot purchase that item at this auction house!</strong></p>";
	}
	else
	{
		/* Don't show the item browser anymore, and fetch the result into an array. */
		$showbrowser = false;
		$result = $db->fetch_array( $result );

		/* Instantiate an item object. */
		$item = new Item( $result['item_id'] );
		$seller = new Player( $result['account_id'] );

		if ( isset( $_POST['buyout'] ) )
		{
			if ( $result['close_time'] < time() )
			{
				?><div style="width: 70%; margin-left: auto; margin-right: auto;">
					<p><strong>This auction has closed! Bidding on it is no longer possible!</strong></p>
				</div><?
			}
			elseif ( $char->getCleanMoney() < $result['buyout'] )
			{
				?><div style="width: 70%; margin-left: auto; margin-right: auto;">
					<p><strong>You do not carry enough money to buyout this item!</strong></p>
				</div><?
			}
			elseif ( $char->character_id == $result['seller'] )
			{
				?><div style="width: 70%; margin-left: auto; margin-right: auto;">
					<p><strong>If you wish to buy your own item, why sell it in the first place?!?</strong></p>
				</div><?
			}
			elseif ( $char->character_id == $result['current_bidder'] )
			{
				?><div style="width: 70%; margin-left: auto; margin-right: auto;">
					<p><strong>Why outbid yourself? You better spend the money elsewhere...</strong></p>
				</div><?
			}
			else
			{
				/* First, notify the previous bidder that he has been outbid and restore his money. */
				$prev_bidder = new Player( $result['current_bidder'], false );
				if ( $result['current_bidder'] > 0 && $prev_bidder->isAlive() )
				{				
					$prev_bidder->addEvent( "You have been outbid!", "This is an automated message from the ". $char->location ." auction house to inform you that you have been outbid on the auction of '". $item->name ."'. The new buyer decided to play it safe and buyout the item! You have no chance of competing in this particular auction anymore. The $". $result['current_bid'] ." you bid on this item has been returned to you." );
					$prev_bidder->setCleanMoney( $prev_bidder->getCleanMoney() + $result['current_bid'] );
				}

				/* Then, take the money from this character. */
				$char->setCleanMoney( $char->getCleanMoney( true ) - $result['buyout'] );

				/* Delete the auction after transferring the fee to the auction house and the profit - fee to the seller */
				$ah_profit = ( $seller->isAlive() ) ? $result['buyout'] * ( $business['auction_fee'] / 100 ) : $result['buyout'];
				$db->query( "UPDATE bank_accounts SET balance=balance+". $ah_profit ." WHERE account_number=". $business['bank_id'] );
				$db->query( "DELETE FROM auctions WHERE auction_id=". $db->prepval( $_GET['id'] ) );

				if ( $seller->isAlive() )
				{
					$seller->addEvent( "Auction Successful!", "This is an automated message from the ". $char->location ." auction house to inform you that your item, '". $item->name ."', has been successfully sold! The auction house took a fee of ". ( $business['auction_fee'] / 100 ) ."% ($$ah_profit) from your profits. The remaining profits, an amount of $". ( $result['buyout'] - $ah_profit ) ." has been sent to you in cash!" );

					$msg = "Within this mail we have included the sum of money that the auction of your item, ". $item->name .", has yielded you.";
					$db->query( "INSERT INTO char_mailbox SET char_id=". $seller->character_id .", money=". ( $result['buyuout'] - $ah_profit ) .", sender=". ( -1 * $business['business_id'] ) .", message='$msg'" );
				}

				/* Put the item into the buyer's mailbox. */
				$msg = "We hereby include the item you have bought from our Auction House. Enjoy it, and remember to always come back to us for sales and bargains!";
				$db->query( "INSERT INTO char_mailbox SET char_id=". $char->character_id .", item_id=". $item->item_id .", sender=". ( -1 * $business['business_id'] ) .", message='$msg'" );				

				?><p><strong>You successfully bought '<?=$item->name;?>'! It has been sent to your mailbox where it should arrive soon!</strong></p><?

				$showbrowser = true;
			}
		}
		
		if ( isset( $_POST['bid'] ) )
		{
			if ( $result['close_time'] < time() )
			{
				?><div style="width: 70%; margin-left: auto; margin-right: auto;">
					<p><strong>This auction has closed! Bidding on it is no longer possible!</strong></p>
				</div><?
			}
			elseif ( !is_numeric( $_POST['amount'] ) )
			{
				?><div style="width: 70%; margin-left: auto; margin-right: auto;">
					<p><strong>Please enter a numeric amount into the bid-field.</strong></p>
				</div><?
			}
			elseif ( $_POST['amount'] < ( $result['current_bid'] * 1.03 ) )
			{
				?><div style="width: 70%; margin-left: auto; margin-right: auto;">
					<p><strong>When upping the bid, your new bid should at least be 3% higher than the previous bid!</strong></p>
				</div><?
			}
			elseif ( $char->getCleanMoney() < $_POST['amount'] )
			{
				?><div style="width: 70%; margin-left: auto; margin-right: auto;">
					<p><strong>You do not carry enough money to make that bid!</strong></p>
				</div><?
			}
			elseif ( $char->character_id == $result['seller'] )
			{
				?><div style="width: 70%; margin-left: auto; margin-right: auto;">
					<p><strong>If you wish to buy your own item, why sell it in the first place?!?</strong></p>
				</div><?
			}
			elseif ( $char->character_id == $result['current_bidder'] )
			{
				?><div style="width: 70%; margin-left: auto; margin-right: auto;">
					<p><strong>Why outbid yourself? You better spend the money elsewhere...</strong></p>
				</div><?
			}
			else
			{
				/* First, notify the previous bidder that he has been outbid and restore his money. */
				$prev_bidder = new Player( $result['current_bidder'], false );
				if ( $result['current_bidder'] > 0 && $prev_bidder->isAlive() )
				{	
					$prev_bidder->addEvent( "You have been outbid!", "This is an automated message from the ". $char->location ." auction house to inform you that you have been outbid on the auction of '". $item->name ."'. The bid has been upped to $". $_POST['amount'] .". If you still wish to compete for this item you will have to raise the bid with at least 3%. The $". $result['current_bid'] ." you bid on this item has been returned to you." );
					$prev_bidder->setCleanMoney( $prev_bidder->getCleanMoney() + $result['current_bid'] );
				}

				/* Then, take the money from this character. */
				$char->setCleanMoney( $char->getCleanMoney( true ) - $_POST['amount'] );

				/* And update the auction itself. */
				$db->query( "UPDATE auctions SET current_bidder=". $char->character_id .", current_bid=". $db->prepval( $_POST['amount'] ) ." WHERE auction_id=". $db->prepval( $_GET['id'] ) );

				?><p><strong>You successfully placed a bid on '<?=$item->name;?>'! You will be notified if you have been outbid or won the auction!</strong></p><?

				$showbrowser = true;
			}
		}
		
		/* Show the html for the purchase options. */
		if ( !$showbrowser )
		{
		?>
		<div style="width: 70%; margin-left: auto; margin-right: auto;">			
			<div class="title" style="padding-left: 10px;">
				Purchase <?=$item->name;?>
			</div>
			<div class="content" style="padding: 0px;">				
				<div  class="row" style="text-align: center; background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
					<strong>Basic Auction Information</strong>
				</div>
				<div class="row" style="background-color: #fff686;">
					<table class="row" cellpadding="0" cellspacing="0">
						<tr>
							<td rowspan="4" class="field" style="padding: 10px; text-align: center; border-right: solid 1px #bb6;">
								<img id="item<?=$item->item_id;?>" src="<?=$rootdir."/images/".$item->icon;?>" style="margin-top: 10px;" alt="<?=$item->name;?>" />
							</td>
						</tr>
						<tr>
							<td class="field" style="width: 20%;"><strong>Seller:</strong></td>
							<td class="field" style="width: 30%; border-right: solid 1px #bb6;"><?=$seller->firstname." ".$seller->lastname;?></td>
							<td class="field" style="width: 25%;"><strong>Current Bid:</strong></td>
							<td class="field" style="width: 25%;">$<?=$result['current_bid'];?></td>
						</tr>
						<tr>
							<td class="field" style="width: 20%;"><strong>Time Left:</strong></td>
							<td class="field" style="width: 30%; border-right: solid 1px #bb6;">Less than half an hour</td>
							<td class="field" style="width: 25%;"><strong>Buyout Price:</strong></td>
							<td class="field" style="width: 25%;">$<?=$result['buyout'];?></td>
						</tr>
					</table>
				</div>
				<div  class="row" style="text-align: center; background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
					<strong>Take Action</strong>
				</div>
				<div class="notifydisplay" style="visibility: visible; display: block; color: #000; font-size: 11.5px; padding: 5px;">You are about to purchase a <?=$item->name;?> from a player known as <?=$seller->firstname." ".$seller->lastname." (".$seller->nickname.")";?>. You can choose to bid on this item (in which case you will be required to fill in a higher bid than the current highest bid), or you can choose to buyout the item for its buyout price. This means that the auction will end directly and you will receive the item.</div>
				<form action="auctionhouse.php?act=purchase&amp;id=<?=$result['auction_id'];?>" method="post">
					<p style="text-align: center; margin-top: 10px;">		
						<strong>Enter an amount:</strong><br /><br />
						<input type="text" class="std_input" style="width: 100px;" name="amount" /><br /><br />
						<input type="submit" name="bid" class="std_input" value="Place Bid" /> <input type="submit" name="buyout" class="std_input" value="Buyout" />
					</p>
				</form>				
			</div>
		</div>
		<?
		}
	}
}
?>