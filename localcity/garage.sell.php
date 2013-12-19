<?
/**
 * garage.sell.php - Sell your car
 */
$nav = "localcity";
$_SESSION['ingame'] = true;

include "../includes/utility.inc.php";

if ( !user_auth() )
{
	$_SESSION['error'] = "You need to be logged in to access those pages.";
	header( "Location: " . $rootdir . "/login.php" );
	exit;
}

if ( !hasCharacter( getUserID() ) )
{
	$_SESSION['error'] = "You need to have a character to access those pages.";
	header( "Location: " . $rootdir . "/account.php" );
	exit;
}


if ( !$char ) $char = new Player( getUserID() );

/* Is a garage in the current city? */
$q = $db->query( "SELECT * FROM localcity AS l INNER JOIN businesses AS b ON l.business_id = b.id WHERE b.url='garage.php' AND l.location_id=". $db->prepval( $char->location_nr ) );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This city doesn't have a garage that you can visit!";
	header( "Location: index.php" );
	exit;
}

/* Constructor */
$success = false;

/* Check for Vehicle */
if ( !$char->hasVehicle() )
{
	header( "location: " . $rootdir . "/localcity/garage.php" );
	exit();
}

if ( $char->isTradingVehicles() )
	$garageError = "You are currently requesting a trade for this vehicle. You may not sell it...";
else
{
	$vehicleq = $db->query( "SELECT * FROM char_vehicles WHERE id=" . $db->prepval( getCharacterID() ) );
	$vehicle = $db->fetch_array( $vehicleq );
	$car = $db->getRowsFor( "SELECT * FROM vehicles WHERE id=" . $vehicle['vehicle'] );

	/* Get worth tunups */
	$tuneupq = $db->query( "SELECT * FROM char_tuneups WHERE char_id=" . getCharacterID() );
	$worth = $car['worth']-VEHICLE_WORTH_DEDUCTABLE;

	while ( $tuneup = $db->fetch_array( $tuneupq ) )
	{
		$tuneuprow = $db->getRowsFor( "SELECT * FROM vehicles_tuneups WHERE id=" . $tuneup['tuneup_id'] );
		$worth = $worth+$tuneuprow['worth_increase'];
	}

	/* Delete vehicle and tuneups */
	$db->query( "DELETE FROM char_vehicles WHERE id=" . getCharacterID() );
	$db->query( "DELETE FROM char_tuneups WHERE char_id=" . getCharacterID() );

	/* Give player cash */
	$char->setCleanMoney( $char->getCleanMoney() + $worth );

	/* End Deal */
	$success = true;
	$garageMessage = "You have sold your vehicle and its tuneups for a total of, <font color='#486C37'>$" . $worth . "</font>.";
}

// Start the real main page
include_once "../includes/header.php";
?>

<h1><?=$char->location;?> Garage</h1>
<p>Welcome to the smelly, dirty, garage of <?=$char->location;?>. From here you'll be able to fix your vehicle up, buy a new one, or trade for other vehicle.
<?
if ( isset( $garageError ) && strlen( $garageError ) > 0 )
{
	?>
	<br><br><b><?=$garageError;?></b>
	<?
}
?></p>
<?
if ( $success )
{
	?>
	<div style="width: 400px;">
		<div class="title">
			<div style="margin-left: 10px; width: 300px;">Vehicle Sold</div>
		</div>
		<div class="content">
			<?=$garageMessage;?><br /><br /><a href='<?=$rootdir;?>/localcity/garage.php' style='text-decoration: none;'><img alt="Back to Garage" title="Back to garage..." border='0' src="<?=$rootdir;?>/images/square_arrow_left_red_32.png" /></a>
		</div>
	</div>
	<?
}
include_once "../includes/footer.php";
?>