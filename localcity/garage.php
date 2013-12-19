<?
$nav = "localcity";
/**
 * garage.php = Garage in localcity.
 * TODO: Object orientate this mess.
 */
$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = false;
include_once "../includes/utility.inc.php";
$char = new Player( getUserID() );

/* Is a garage in the current city? */
$q = $db->query( "SELECT * FROM localcity AS l INNER JOIN businesses AS b ON l.business_id = b.id WHERE b.url='garage.php' AND l.location_id=". $db->prepval( $char->location_nr ) );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This city doesn't have a garage that you can visit!";
	header( "Location: index.php" );
	exit;
}


$char = new Player( getUserID() );

// purchase_vehicle
if ( isset( $_POST['purchase_vehicle'] ) )
{
	$chk = $db->query( "SELECT * FROM char_vehicles WHERE id=" . $db->prepval( getCharacterID() ) );
	if ( $db->getRowCount( $chk ) > 0 )
	{
		$garageError = "You need to sell or trade your vehicle before you can buy another!";
	} 
	else 
	{
		$getPurchasable = $db->query( "SELECT * FROM vehicles WHERE id=" . $db->prepval( $_POST['vehicle'] ) );
		if ( $db->getRowCount( $getPurchasable ) == 0 )
		{
			$garageError = "No such vehicle (" . $_POST['vehicle'] . ").";
		} 
		else
		{
			$vehassoc = $db->fetch_assoc( $getPurchasable );
			if ( $char->getCleanMoney() < $vehassoc['worth'] )
			{
				$garageError = "Not enough cash, get lost deadbeat!";
			}
			else
			{
				if ( $vehassoc["tier_required"] > $char->getTier() )
				{
					$garageError = "You need a little more experience before you can use this vehicle.";
				}
				else
				{
					// Insert vehicle
					$db->query( "INSERT INTO char_vehicles (id, vehicle, modified, health) VALUES (" . $db->prepval( getCharacterID() ) . ", " . $db->prepval( $_POST['vehicle'] ) . ", 'false', '100')" );
					$char->setCleanMoney( $char->getCleanMoney() - $vehassoc['worth'] );
					$garageError = "<font color='#47B44D'>Congratulations, and enjoy your new vehicle!</font>";
				}
			}
		}
	}
}

// purchase_tuneup
if ( isset( $_POST['purchase_tuneup'] ) && isset( $_POST['tuneup'] ) )
{
	/* Check for existance */
	$chk = $db->query( "SELECT * FROM vehicles_tuneups WHERE id=" . $db->prepval( $_POST['tuneup'] ) );
	if ( $db->getRowCount( $chk ) == 0 )
	{
		$garageError = "What? That tuneup doesn't exist.";
	}
	else
	{
		$tuneup = $db->fetch_array( $chk );

		/* Check if player already has it */
		$chk = $db->query( "SELECT * FROM char_tuneups WHERE char_id=" . getCharacterID() . " AND tuneup_id=" . $db->prepval( $_POST['tuneup'] ) );
		if ( $db->getRowCount( $chk ) != 0 )
		{
			$garageError = "The mechanic looks at you curiously, \"...? But you already have that tuneup, it would be sensless to purchase it again.\".";
		}
		else
		{
			/* Check if player has enough cash to make the purcahse */
			if ( $char->getCleanMoney() < $tuneup['worth'] )
			{
				$garageError = "The mechanic throws a large wrench at you in fury, \"<font color='red'>Get some cash or get out!</font>\".";
			} 
			else 
			{
				if ( !$char->hasVehicle() )
				{
					$garageError = "You don't even have a vehicle to tuneup!";
				}
				else
				{
					if ( $tuneup["tier_required"] > $char->getTier() )
					{
						$garageError = "You need a little more experience to get this tuneup.";
					}
					else
					{
						/* The purchase can be made */
						$db->query( "INSERT INTO char_tuneups (char_id, tuneup_id) VALUES (" . getCharacterID() . ", " . $_POST['tuneup'] . ")" );
						$char->setCleanMoney( $char->getCleanMoney() - $tuneup['worth'] );
						$db->query( "UPDATE char_vehicles SET modified='true' WHERE id=" . getCharacterID() );
						$garageError = "<font color='#47B44D'>After a while, the mechanic comes out from under your vehicle, \"Your good to go, enjoy your new tuneup.\"</font>";
					}
				}
			}
		}
	}
}

$_SESSION['ingame'] = true;

// Start the real main page
include_once "../includes/header.php";
?>

<h1><?=$char->location;?> Garage</h1>
<p>Welcome to the smelly, dirty, garage of <?=$char->location;?>. From here you'll be able to fix your vehicle up, buy a new one, or trade for another vehicle.
<?
if ( isset( $garageError ) && strlen( $garageError ) > 0 )
{
	?>
	<br><br><b><?=$garageError;?></b>
	<?
}
?></p>

<table style="width: 100%;"><tr><td style="width: 100%;" align="center">
<div style="width: 70%;" align="left">
<?
// Query for a vehicle
$veh = $db->query( "SELECT * FROM char_vehicles WHERE id=" . getCharacterID() );
if ( $db->getRowCount( $veh ) > 0 )
{
	/* General Vehicle Queries */
	$veha = $db->fetch_array( $veh );
	$vehicle = $db->getRowsFor( "SELECT * FROM vehicles WHERE id=" . $veha['vehicle'] );

	/* Check for tuneups */
	$tuneups = $db->query( "SELECT * FROM char_tuneups WHERE char_id=" . getCharacterID() );
	if ( $db->getRowCount( $tuneups ) != 0 )
	{
		/* Constructors */
		$worth = $vehicle['worth']-VEHICLE_WORTH_DEDUCTABLE;
		$speed = $vehicle['speed'];
		$items = $vehicle['item_slots'];
		$drugs = $vehicle['drug_slots'];
		while ( $row = $db->fetch_array( $tuneups ) )
		{
			$tune = $db->getRowsFor( "SELECT * FROM vehicles_tuneups WHERE id=" . $row['tuneup_id'] );
			$worth = $worth+$tune['worth_increase'];
			$speed = $speed+$tune['speed_increase'];
			$items = $items+$tune['item_slot_increase'];
			$drugs = $drugs+$tune['drug_slot_increase'];
		}
	} 
	else 
	{
		/* Default Constructors */
		$worth = $vehicle['worth']-VEHICLE_WORTH_DEDUCTABLE;
		$speed = $vehicle['speed'];
		$items = $vehicle['item_slots'];
		$drugs = $vehicle['drug_slots'];
	}
	?>
	<div class="title">
		<div style="margin-left: 10px;">Your Vehicle</div>
	</div>
	<div class="content">
	<b><?=$vehicle['name'];?></b> (<font color='#23565F'>worth $<?=$worth;?></font>)<br />
	Equipped to hold <font color='#452E21'><?=$drugs;?> drug slots</font>, and <font color='#452E21'><?=$items;?> item slots</font>.<br /><br />
	<?
	/* Another Tuneup Finder */
	$tuneups = $db->query( "SELECT * FROM char_tuneups WHERE char_id=" . getCharacterID() );
	if ( $db->getRowCount( $tuneups ) != 0 )
	{
		while ( $row = $db->fetch_array( $tuneups ) )
		{
			/* List Tuneups */
			$trow = $db->getRowsFor( "SELECT * FROM vehicles_tuneups WHERE id=" . $row['tuneup_id'] );
			print( "<b><font color='#3C6C84'>" . $trow['name'] . "</font></b>: " . $trow['description'] . "<br />" );
		}

		print( "<br />" );

	}
	else
	{
		?>
		No tuneups have been purchased.<br /><br />
		<?
	}
	?>

	<a style='text-decoration: none;' href='<?=$rootdir;?>/localcity/garage.sell.php' title='Sell vehicle and tuneups for total worth.'>Sell</a> | <a style='text-decoration: none;' href='<?=$rootdir;?>/localcity/garage.trade.php' title='Trade vehicle and tuneups to another player'>Trade</a> | <a style='text-decoration: none;' href='<?=$rootdir;?>/localcity/garage.repair.php' title='Repair your current vehicle.'>Repair</a>
	</div> 
	<?
}

if ( $db->getRowCount( $veh ) == 0 )
{
	/* No purchase if you already own one. */
	?>
	<div class="title">
		<div style="margin-left: 10px;">Purchase Vehicles</div>
	</div>
	<div class="content">
		<form method="post" action="garage.php">
		<?
		$getVehicles = $db->query( "SELECT * FROM vehicles WHERE tier_required<=" . $char->getTier() );
		if ( $db->getRowCount( $getVehicles ) == 0 )
		{
			?>No Vehicles Available<?
		} 
		else 
		{
			while ( $row = $db->fetch_assoc( $getVehicles ) )
			{
				$locations = explode( ":", $row["locations"] );
				for ( $i = 0; $i < count( $locations ); $i++ )
				{
					if ( $locations[$i] == $char->location_nr )
					{
						?><input class="std_input" type="radio" name="vehicle" value="<?=$row['id'];?>" /><?=$row['name'];?> ($<?=$row['worth'];?>)<br /><?
					}
				}
			}
		}
		?><br />
		<input class="std_input" type="submit" name="purchase_vehicle" value="Purchase" />
		</form>
	</div>
	<?
}

if ( $char->hasVehicle() )
{
	?>
	<div class="title">
		<div style="margin-left: 10px;">Purchase Tune-ups</div>
	</div>
	<div class="content">
		<form method="post" action="garage.php">
		<?
		$getTuneups = $db->query( "SELECT * FROM vehicles_tuneups WHERE tier_required<=" . $char->getTier() );
		$getTuneups2 = $db->query( "SELECT * FROM char_tuneups WHERE char_id=" . getCharacterID() );
		if ( $db->getRowCount( $getTuneups ) == 0 || $db->getRowCount ( $getTuneups2 ) == $db->getRowCount( $getTuneups ) )
		{
			?>No Tune-ups Available<br /><?
		}
		else
		{
			while ( $row = $db->fetch_assoc( $getTuneups ) )
			{
				if ( $db->getRowCount( $db->query( "SELECT * FROM char_tuneups WHERE char_id=" . getCharacterID() . " AND tuneup_id=" . $row['id'] ) ) == 0 )
				{
					$locations = explode( ":", $row["locations"] );
					for ( $i = 0; $i < count( $locations ); $i++ )
					{
						if ( $locations[$i] == $char->location_nr )
						{
							?><input class="std_input" title="<?=$row['description'];?>" type="radio" name="tuneup" value="<?=$row['id'];?>" /><?=$row['name'];?> ($<?=$row['worth'];?>)<br /><font size='1' color='#54466C'><?=$row['description'];?></font><br /><br />
							<?
						}
					}
				}
			}
		}
		?>
		<br />
		<input class="std_input" type="submit" name="purchase_tuneup" value="Purchase" />
		</form>
	</div>
	<?
}
?>
</div></td></tr></table>
<?
include_once "../includes/footer.php";
?>