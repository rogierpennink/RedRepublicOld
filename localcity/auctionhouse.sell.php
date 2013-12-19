<?
$showbrowser = false;

if ( isset( $_POST['sell'] ) )
{
	/* Check if the player has the item in his inventory and whether the item is tradable. */
	$itresult = $db->query( "SELECT * FROM char_inventory INNER JOIN items ON char_inventory.item_id = items.item_id WHERE char_id=". $char->character_id );
	$it = $db->fetch_array( $itresult );
	if ( $db->getRowCount( $itresult ) == 0 )
	{
		?><div style="width: 100%; margin-left: auto; margin-right: auto;">
			<p><strong>Either the item doesn't exist or you don't have it in your backpack! No exploiting please!</strong></p>
		</div><?
	}
	elseif ( $it['tradable'] == 0 )
	{
		?><div style="width: 100%; margin-left: auto; margin-right: auto;">
			<p><strong>The item you tried selling can't be traded! No exploiting please!</strong></p>
		</div><?
	}
	elseif ( !is_numeric( $_POST['min_bid'] ) || !is_numeric( $_POST['buyout'] ) )
	{
		?><div style="width: 100%; margin-left: auto; margin-right: auto;">
			<p><strong>Please enter numeric values into the bid and buyout fields!</strong></p>
		</div><?
	}
	elseif ( $_POST['min_bid'] >= $_POST['buyout'] )
	{
		?><div style="width: 100%; margin-left: auto; margin-right: auto;">
			<p><strong>Your buyout price should always be higher than the minimum bid price!</strong></p>
		</div><?
	}
	else
	{		
		$b_id = $business['business_id'];

		/* Create a new auction. Close time being 8 hours. */
		if ( $db->query( "INSERT INTO auctions SET item_id=". $it['item_id'] .", seller=". $char->character_id .", current_bid=". $db->prepval( $_POST['min_bid'] ) .", buyout=". $_POST['buyout'] .", close_time=". ( time() + 28800 ) .", business_id=$b_id, location_id=". $char->location_nr ) === false )
		{
			?><div style="width: 100%; margin-left: auto; margin-right: auto;">
				<p><strong>An error occurred on adding the auction! Please contact an administrator!</strong></p>
			</div><?
		}
		else
		{ 
			$auction_id = mysql_insert_id();

			/* Take from inventory of seller. */
			if ( $db->query( "DELETE FROM char_inventory WHERE char_id=". $char->character_id ." AND item_id=". $it['item_id'] ) === false )
			{
				$db->query( "DELETE FROM auctions WHERE auction_id=$auction_id" );
				?><div style="width: 100%; margin-left: auto; margin-right: auto;">
					<p><strong>An error occurred on adding the auction! Please contact an administrator!</strong></p>
				</div><?
			}
			else
			{
				?>
				<p><strong>Your item, <?=$it['name'];?>, has been put up for sale successfully!</strong></p>
				<?
				$showbrowser = true;
			}
		}
	}
}

/* Show the html for the item sell window. */
if ( !$showbrowser )
{	
?>
<div style="width: 100%; margin-left: auto; margin-right: auto;">		
	<div class="title" style="padding-left: 10px;">
		Sell Items
	</div>
	<div class="content">
		<p>From this page you can sell any items you have in your inventory through the auctionhouse interface. Simply check the items you wish to sell and provide a minimum bid and buyout price. Please keep in mind that this auction house charges a fee over any sales made. This will affect the amount of money you earn by selling your items through this auction house.</p>
		<p>Other Options: <a href="auctionhouse.php">Item Browser</a></p>
	</div>

	<div class="title" style="padding-left: 10px;">
		Sellable items in your inventory
	</div>
	<div class="content" style="padding: 0px;">		
	
		

		<div  class="row" style="text-align: center; background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
			<table class="row">
				<tr>
					<td class="field" style="width: 75px;">&nbsp;</td>
					<td class="field" style="width: 25%;"><strong>Information</strong></td>
					<td class="field" style="width: 25%;"><strong>Min. Bid</strong></td>
					<td class="field" style="width: 25%;"><strong>Buyout</strong></td>
					<td class="field"><strong>Options</strong></td>
				</tr>
			</table>
		</div>
		
		<?
		$result = $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id );
		if ( $db->getRowCount( $result ) == 0 )
		{
			?>
			<div class="row">
				<div style="margin: 10px;">You do not have any items in your inventory that you can sell through this auction house!</div>
			</div>
			<?
		}
		else
		{
		while ( $inventory = $db->fetch_array( $result ) )
		{
		$item = new Item( $inventory['item_id'] );
		if ( $item->tradable == 0 ) continue;
		?>
		<form action="auctionhouse.php?act=sell" method="post">
			<div class="row">
				<table class="row">
					<tr>
						<td class="field" style="width: 75px;">
							<img id="item<?=$item->item_id;?>" src="../images/<?=$item->icon;?>" alt="<?=$item->name;?>" />
						</td>
						<td class="field" style="width: 25%; font-size: 11px;">
						<?
						$num_in_ah = $db->getRowCount( $db->query( "SELECT * FROM auctions WHERE item_id=". $item->item_id ) );
						$avg = $db->query( "SELECT AVG(current_bid), AVG(buyout) FROM auctions WHERE item_id=". $item->item_id );
						if ( $db->getRowCount( $avg ) == 0 )
						{
							$avg = array();
							$avg[0] = 0;
							$avg[1] = 0;
						}
						else
						{
							$avg = $db->fetch_array( $avg );
						}
						echo "<strong>$num_in_ah</strong> of these items currently for sale.<br />Average bid: <strong>$". round( $avg[0] ) ."</strong><br />Average buyout: <strong>$". round( $avg[1] ) ."</strong>";
						?>
						</td>
						<td class="field" style="width: 25%;"><input type="text" value="0" class="std_input" style="min-width: 100px;" name="min_bid" /></td>
						<td class="field" style="width: 25%;"><input type="text" value="0" class="std_input" style="min-width: 100px;" name="buyout" /></td>
						<td class="field"><input type="submit" class="std_input" name="sell" value=" Sell  " /><input type="hidden" value="<?=$item->item_id;?>" name="item_id" /></td>
					</tr>
				</table>
			</div>
		</form>
		<?		
		}
		}
		?>	
		
	</div>
</div>
<?
}