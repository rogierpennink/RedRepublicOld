<?
$CHARNEEDED = true;
$AUTH_LEVEL = 0;
$REDIRECT = false;
include "../includes/utility.inc.php";

$char = new Player( getUserID() );

/* Check if there is an auction house in this city at all. */
$q = $db->query( "SELECT * FROM localcity INNER JOIN businesses ON localcity.business_id = businesses.id WHERE business_type=6 AND location_id=". $char->location_nr );
if ( $db->getRowCount( $q ) == 0 )
{
	echo "<div style='padding: 10px;'>You are trying to view an auction house in a city that has no auction house!</div>";
	exit;
}
$business = $db->fetch_array( $q );

if ( $_GET['reqtype'] == "getitemlist" )
{
	/* Check for a global auction house or not. */
	$setting = $db->fetch_array( $db->query( "SELECT value FROM settings_general WHERE setting='global_ah'" ) );

	/* Some paging variables. */
	$numperpage = 15;
	$page = ( isset( $_GET['page'] ) && $_GET['page'] != "" ) ? $_GET['page'] : 1;
	$offset = ( $page - 1 ) * $numperpage;

	$keywords = $db->prepval( "%" . $_GET['keywords'] ."%" );	
	$quality = $db->prepval( $_GET['quality'] );
	$category = $db->prepval( $_GET['category'] );

	/* The query. */
	$sql = "SELECT * FROM auctions ";
	$sql .= "INNER JOIN items ON auctions.item_id=items.item_id ";
	$sql .= "INNER JOIN char_characters ON auctions.seller=char_characters.id ";
	$sql .= "INNER JOIN icons ON items.icon = icons.icon_id ";
	$sql .= "WHERE items.name LIKE $keywords ";
	$sql .= "AND close_time > ". time() ." ";
	if ( $quality >= 0 ) $sql .= "AND items.quality >= $quality ";
	if ( $category >= 0 ) $sql .= "AND items.category = $category ";
	$sql .= ( $setting['value'] == "true" ) ? "" : "AND business_id = ". $business['business_id'] ." ";
	$sql .= "ORDER BY items.name, items.quality ASC ";
	$sql .= "LIMIT $offset,$numperpage";

	/* Execute it. */
	$result = $db->query( $sql );

	$numresults = $db->getRowCount( $result );

	/* Put together the html... */
	?>
	<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
		<table class="row">
			<tr>		
				<td class="field" style="width: 75px;">&nbsp;</td>
				<td class="field" style="width: 25%;"><strong>Item Name</strong></td>
				<td class="field" style="width: 25%;"><strong>Seller</strong></td>
				<td class="field" style="width: 20%;"><strong>Time Left</strong></td>
				<td class="field"><strong>Bid/Buyout</strong></td>
			</tr>
		</table>
	</div>	
	<?	
	if ( $numresults == 0 )
	{
		echo "<div style='padding: 10px;'>There are no items in the auction house that match your search criteria!</div>";
	}
	else
	{
		$i = 0;
		while ( $auction = $db->fetch_array( $result ) )
		{		
		$timeleft = $auction['close_time'] - time();
		$timestring = ceil( $timeleft / 3600 ) . " hours left";
		if ( $timeleft <= 3600 ) $timestring = "Less than an hour";
		if ( $timeleft <= 1800 ) $timestring = "Less than half an hour";		
		?>
		<div class="row" <?=( $i++ % 2 == 0 ) ? "style='background-color: #fff686;'" : "";?>>
			<table class="row">
				<tr>
					<td class="field" style="width: 75px;">
						<a href="auctionhouse.php?act=purchase&amp;id=<?=$auction['auction_id'];?>" style="text-decoration: none;">
						<img id="item<?=$auction['item_id'];?>" src="<?=$rootdir;?>/images/<?=$auction['url'];?>" alt="" style="border: none;" />
						</a>
					</td>
					<td class="field" style="width: 25%;">
						<a href="auctionhouse.php?act=purchase&amp;id=<?=$auction['auction_id'];?>">
						<?=$auction['name'];?>
						</a>
					</td>
					<td class="field" style="width: 25%;"><?=$auction['firstname'] . " \"" . $auction['nickname'] ."\" ". $auction['lastname'];?></td>
					<td class="field" style="width: 20%;"><?=$timestring;?></td>
					<td class="field"><?="Bid: $" . $auction['current_bid'] . "<br />Buyout: $" . $auction['buyout'];?></td>
				</tr>
			</table>
		</div>
		<?
		}
	}

	
	if ( $numresults > 0 )
	{
	?>	
	<!-- Paging -->
	<div class="row">		
		<div style="padding: 5px; font-family: Verdana; font-size: 11px;">
		<? 
		if ( $page > 1 ) echo "<a href=\"javascript: getItems( ". ( $page - 1 ) .", document.getElementById('category').value, document.getElementById('quality').value, document.getElementById('name').value );\">";
		echo "<<<";
		if ( $page > 1 ) echo "</a>";
		for ( $i = 0; $i < ceil( $numresults / $numperpage ); $i++ ) 
		{
			echo " ";
			if ( $i + 1 != $page ) echo "<a href=\"javascript: getItems( ". ( $i + 1 ) .", document.getElementById('category').value, document.getElementById('quality').value, document.getElementById('name').value );\">";
			echo ( $i + 1 );
			if ( $i + 1 != $page ) echo "</a>";
		}
		echo " ";
		if ( $page < ceil( $numresults / $numperpage ) ) echo "<a href=\"javascript: getItems( ". ( $page + 1 ) .", document.getElementById('category').value, document.getElementById('quality').value, document.getElementById('name').value );\">";
		echo ">>>";
		if ( $page < ceil( $numresults / $numperpage ) ) echo "</a>";
		?>
		</div>
	</div>
	<?
	}
}