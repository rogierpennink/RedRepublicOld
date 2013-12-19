<?
/* The local auction house. Players can buy and sell items here. */

$ext_style = "forums_style.css";
$nav = "localcity";
$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = true;
include "../includes/utility.inc.php";
$char = new Player( getUserID() );

/* Check if there is an auction house in this city at all. */
$q = $db->query( "SELECT * FROM localcity INNER JOIN businesses ON localcity.business_id = businesses.id INNER JOIN auctionhouse ON auctionhouse.business_id=businesses.id WHERE business_type=3 AND location_id=". $char->location_nr );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This city doesn't even have an auction house!";
	header( "Location: index.php" );
	exit;
}
$business = $db->fetch_array( $q );

$_SESSION['ingame'] = true;

/* Include the header (start output). */
include_once "../includes/header.php";
?>
<script type="text/javascript" src="../javascript/localcity/auctionhouse_js.js"></script>

<h1>The <?=$char->location;?> Auction House</h1>
<p>As you cross one of <?=$char->location;?>'s market squares, you notice a gathering of many people near a mid-sized building across the square. As you approach the building you can make out letters over its doors and you learn it's <?=$char->location;?>'s auction house! You excitedly walk inside: this is the place where you feel a lot of money can be made by trading stuff!</p>

<?
$showbrowser = true;
if ( $_GET['act'] == "purchase" )
{
	include "auctionhouse.purchase.php";
}
if ( $_GET['act'] == "sell" )
{
	include "auctionhouse.sell.php";
}

/**
 * Show the item browser only if $showbrowser is true! This variable is only true if no other windows
 * are supposed to be displayed!
 */
if ( $showbrowser )
{
?>
<div class="title" style="padding-left: 10px;">
	Item Search Criteria
</div>
<div class="content">
	<p>Use this form to refine your search criteria and find that item you so desperately need!</p>
	<p>
		<select class="std_input" id="category" style="margin-right: 20px;">
			<option value="-1">Any Category</option>
			<?
			for ( $i = 0; $i < 11; $i++ )
				echo "<option value='$i'>". categoryString( $i ) ."</option>";
			?>
		</select>

		<select class="std_input" id="quality" style="margin-right: 20px;">
			<option value="-1">Any Quality</option>
			<?
			for ( $i = 0; $i < 5; $i++ )
				echo "<option value='$i'>". qualityString( $i ) ."</option>";
			?>
		</select>

		<input type="text" class="std_input" value="Item Name..." onfocus="this.value='';" id="name" style="width: 150px; margin-right: 20px;" />

		<input type="button" class="std_input" value="   Search    " onclick="getItems( 1, document.getElementById('category').value, document.getElementById('quality').value, document.getElementById('name').value );" />
	</p>
	<p>Other options: <a href="auctionhouse.php?act=sell">Sell Items</a></p>
</div>

<div class="title" style="padding-left: 10px;">
	Results
</div>
<div class="content" style="padding: 0px;" id="auctionhouse_itemlist">
	<div style="padding: 10px;">Loading... <img src="../images/loading.gif" alt="" /></div>
</div>

<script type="text/javascript">getItems( 1, document.getElementById('category').value, document.getElementById('quality').value, '' );</script>
<?
}

include_once "../includes/footer.php";
?>