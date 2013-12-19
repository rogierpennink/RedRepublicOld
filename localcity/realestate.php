<?
/**
 * The real estate agency sells property.
 */
$nav = "localcity";
$CHARNEEDED = true;
$AUTH_LEVEL = 0;
$REDIRECT = true;
$_SESSION['ingame'] = true;
include "../includes/utility.inc.php";
$char = new Player( getUserID() );

/** 
 * Check if there is a real estate agency in this city.
 */
$q = $db->query( "SELECT * FROM localcity INNER JOIN businesses ON localcity.business_id = businesses.id WHERE business_type=9 AND location_id=". $char->location_nr );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This city doesn't even have a real estate agency!";
	header( "Location: index.php" );
	exit;
}
$business = $db->fetch_array( $q );
$continue = true;

/**
 * If uncultivated land is to be bought.
 */

include_once "../includes/header.php";
?>

<h1><?=$char->location;?>'s Real Estate Agency</h1>
<p>As you wander through <?=$char->location;?> you notice a medium-sized modern looking building that has large photos printed all over it's windows. As you come closer, to investigate, you can see these are photos of property; beautiful villas, smaller houses and locations where land can be bought. Perhaps, your dream of investing in real estate can still come true!</p>

<?
if ( isset( $_SESSION['message'] ) )
{
	echo "<p><strong>". $_SESSION['message'] ."</strong></p>";
	unset( $_SESSION['message'] );
}
?>

<div class="title" style="padding-left: 10px;">
	What would you like to do?
</div>
<div class="content">
	<form action="<?=$PHP_SELF;?>" method="post">
	I would like to
	<select class="std_input" name="action" style="margin-left: 5px;">
		<option value="buy_land">Buy uncultivated land</option>
		<option value="buy_land2" <?=($_POST['action']=='buy_land2')?"selected='true'":"";?>>Buy cultivated property</option>
		<option value="sell_land" <?=($_POST['action']=='sell_land')?"selected='true'":"";?>>Sell property</option>
	</select>
	<input type="submit" name="do_action" class="std_input" style="margin-left: 10px;" value="Go!" />
	</form>	
</div>

<?
/**
 * The amount of land available (measured in square meters) depends on the population size of the city. Assume an average of 100 square meters
 * per person. This amount of land should, if all used for crops, yield the raw resources for 50 drugs a day. 50 being an average here: if the weather is * bad it'll be less, and if it's good, it'll be more.
 */
$numpeople = $db->getRowCount( $db->query( "SELECT * FROM char_characters WHERE homecity=". $char->location_nr ." AND health > 0" ) );
$maxland = $numpeople * 100;
$land = $db->getRowsFor( "SELECT SUM(amount) AS amount_taken FROM char_land WHERE location_id=". $char->location_nr );

/**
 * Notes:
 * There will be a char_land table that contains a link to the business, an amount of land, and a type of stuff that grows on that land 
 * (this could be just a number). As people buy land, more entries are made, splitting the land over multiple records in that table. When land goes
 * back to the real estate agency, the character id of the table simply becomes 0. And if nothing has been built on the land, the record is deleted and
 * the amount of land is added to the main record for the agency again.
 */

switch ( $_POST['action'] )
{
	case "buy_land":
		include_once "realestate.buyland.php";
		break;
	case "buy_land2":
		include_once "realestate.buyland2.php";
		break;
	case "sell_land":
		include_once "realestate.sellland.php";
		break;
	default:
		include_once "realestate.buyland.php";
}

include_once "../includes/footer.php";
?>