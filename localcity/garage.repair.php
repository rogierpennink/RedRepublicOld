<?
$nav = "localcity";
/**
 * garage.sell.php - Repair your car
 */
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

$_SESSION['ingame'] = true;

/* Get total cost to repair vehicle */
$health = $char->getVehicleHealth();
$rhealth = 100 - $health;
$costperpercent = round( $char->getVehicleInfo( 'worth' ) / 2 );
$repaircost = $rhealth * $costperpercent;

/* Repair Function */
if ( isset( $_GET['act'] ) && $_GET['act'] == 'true' )
{
	if ( $char->getVehicleHealth() == 100 )
	{
		$garageError = "Your vehicle is perfectly fine, you can't repair it.";
	}
	elseif ( $char->getCleanMoney() < $repaircost )
	{
		$garageError = "The mechanic shakes his head, \"You need more money, pal\".";
	}
	else
	{
		/* Can repair */
		$char->setVehicleHealth( 100 );
		$char->setCleanMoney( $char->getCleanMoney() - $repaircost );
		$garageError = "<font color='#47B44D'>After a while, the mechanic comes out from under your vehicle and informs you that she is running like new.</font>";
	}
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
?>
</p>

<div style="width: 400px;">
	<div class="title">
		<div style="margin-left: 10px; width: 300px;">Repair Vehicle</div>
	</div>
	<div class="content">
		<?
		if ( $char->getVehicleHealth() != 100 ) { ?>
			The mechanic informs you that it will cost you <font color='#37762E'>$<?=$repaircost;?></font> up front in order to repair your <font color='#2E5969'><?=$char->getVehicleInfo( 'name' ); ?></font>, would you like to continue?
			<br /><br /><a href='<?=$rootdir;?>/localcity/garage.repair.php?act=true' style='text-decoration: none;' title='Repair Vehicle'>Continue</a> | 
		<? } else { ?>
			The mechanic informs you that your vehicle is perfectly fine, and there is nothing to be repaired.<br /><br />
		<? } ?>
		<a href='<?=$rootdir;?>/localcity/garage.php' style='text-decoration: none;'><img alt="Back to Garage" title="Back to garage..." border='0' src="<?=$rootdir;?>/images/square_arrow_left_red_32.png" /></a>
	</div>
</div>

<? include_once "../includes/footer.php"; ?>