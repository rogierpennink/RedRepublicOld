<?
/**
 * garage.trade.php - Trade your car to another person, via event request.
 */
$nav = "localcity";
$AUTHLEVEL = 0;
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

/* Is a garage in the current city? */
$q = $db->query( "SELECT * FROM localcity AS l INNER JOIN businesses AS b ON l.business_id = b.id WHERE b.url='garage.php' AND l.location_id=". $db->prepval( $char->location_nr ) );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This city doesn't have a garage that you can visit!";
	header( "Location: index.php" );
	exit;
}

/* Vehicle Information Lookup */
if ( isset( $_POST["lookup"] ) )
{
	/* General problems */
	if ( !isset( $_POST["nickname"] ) || $_POST["nickname"] == "nickname..." || strlen( $_POST["nickname"] ) == 0 )
		$garageErrorEx = "You must provide a nickname of the person for the mechanic to lookup.";
	else
	{
		/* Pay Check */
		if ( $char->getCleanMoney( true ) < 100 )
			$garageErrorEx = "You do not have enough money to pay for the lookup service charge ($100).";
		else
		{
			/* Check for existance of nickname. */
			if ( charExists( $_POST["nickname"], "nickname" ) == 0 )
				$garageErrorEx = "The nickname you provided was not found.";
			else
			{
				/* Character found, check for vehicle. */
				$char->setCleanMoney( $char->getCleanMoney( true ) - 100 );
				$infochar = new Player( $_POST["nickname"], false );
				if ( !$infochar->hasVehicle() )
				{
					$garageErrorEx = $_POST["nickname"] . " does not have a vehicle.";
					unset( $infochar );
					/* If infochar is set, then we assume
					 * they have a vehcile, and we display
					 * information about it.
					 */
				}
			}
		}
	}
}

/* Trade Request */
if ( isset( $_POST["sendrequest"] ) )
{
	/* Error Catching... */
	if ( !isset( $_POST["nickname"] ) || $_POST["nickname"] == "nickname..." || strlen( $_POST["nickname"] ) == 0 )
		$garageError = "You must provide a nickname to trade your vehicle with.";
	else
	{
		/* Check Nick */
		if ( charExists( $_POST["nickname"], "nickname" ) == 0 )
			$garageError = "The nickname you provided was not found.";
		else
		{
			/* Developers can only trade with themselves */
			if ( $_POST["nickname"] == getCharNickname( $char->getCharacterID() ) && getUserRights() != USER_SUPERADMIN )
				$garageError = "You may not vehicles with yourself!";
			else
			{
				/* Check for vehicle. */
				$charex = new Player( $_POST["nickname"], false );

				if ( !$charex->hasVehicle() )
				{
					unset( $charex );
					$garageError = $_POST["nickname"] . " does not have a vehicle to trade you.";
				}
				else
				{
					$nick = getCharNickname( $char->getCharacterID() );
					$msg = "<input type='hidden' name='chrid' value='" . $char->getCharacterID() . "' /><a href='" . $rootdir . "/profile.php?id=" . $char->getCharacterID() . "' style='text-decoration: none;'>" . $nick . "</a> is currently interested in trading vehicles with you. " . $nick . " currently has a " . $char->getVehicleInfo( "name" ) . " that is " . getVehicleHealthString( $char->getVehicleHealth(), false ). ". You can find out more about " . $nick . "'s vehicle at any garage, in the <a href=\"" . $rootdir . "/localcity/garage.trade.php\" style=\"text-decoration: none;\">trade menu</a>.<br /><br /><input type=\"radio\" name=\"tradeoption\" value=\"accept\" /> Accept Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"decline\" /> Decline Trade<br /><input type=\"radio\" name=\"tradeoption\" value=\"wait\" /> Not Ready to Answer<br /><br /><input type=\"submit\" class=\"std_input\" value=\"Continue\" />";

					$db->query( "UPDATE char_vehicles SET trading='true' WHERE id=" . $char->getCharacterID() );

					$charex->addEventRequest( "Vehicle Trade Offer", $msg, "trade_vehicle.php" );
					$garageError = $_POST["nickname"] . " has been notified, and will soon accept or decline your trade offer.";
				}
			}
		}
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
?></p>
<!-- Ending Garage Trade Header -->
<table style="width: 100%;">
	<tr>
		<td style="width: 45%; vertical-align: top;">
			<div class="title"><div style="margin-left: 10px;">Trade Vehicle</div></div>
			<div class="content">
				This is where car owners can request a trade of vehicles.<br /><br />
				<b>Request a trade from</b>: <form method="post" action="<?=$PHP_SELF;?>"><input class="std_input" name="nickname" type="text" value="nickname..." onclick="this.value = ''; this.onclick = null;" /> <input type="submit" class="std_input" name="sendrequest" value="Send" /></form><br />
				<b>I am trading a</b>:<br />
				<span style="color: #004123;"><?=$char->getVehicleInfo( "name" );?></span> <span style="font-size: 10px;">(<?=getVehicleHealthString( $char->getVehicleHealth(), false );?>)</span><br />
				<?
				/* List Tuneups */
				$tuneups = $db->query( "SELECT * FROM char_tuneups WHERE char_id=" . getCharacterID() );

				if ( $db->getRowCount( $tuneups ) != 0 )
				{
					?>
					<span style="font-size: 10px;">
					<?
					while ( $tuneup = $db->fetch_array( $tuneups ) )
					{
						$trow = $db->getRowsFor( "SELECT * FROM vehicles_tuneups WHERE id=" . $tuneup['tuneup_id'] );
						?>
						<span style="color: #3C6C84;"><?=$trow["name"];?></span><br />
						<?
					}
					?>
					</span>
					<br />
					<?
				}
				else
				{
					?><span style="font-size: 10px;">(no modifications)</span><?
				}
				?>
			</div>
		</td>
		<td style="width: 10%;">&nbsp;</td>
		<td style="width: 45%; vertical-align: top;">
			<div class="title"><div style="margin-left: 10px;">Get Vehicle Information</div></div>
			<div class="content">
				<b>Vehicle Lookup</b><br />
				The local mechanic here will gather information on a specific car for a small service charge of $100.<br /><br />
				<form method='post' action='<?=$PHP_SELF;?>'>
				I would like information on <input class="std_input" style="font-size: 10px;" name="nickname" value="nickname..." onclick="this.value = ''; this.onclick = null;" />'s vehicle. <input type="submit" class="std_input" name="lookup" value="Lookup" style="font-size: 11px;" />
				</form>
				<?
				/* Vehicle Lookup Error Display */
				if ( isset( $garageErrorEx ) && strlen( $garageErrorEx ) > 0 )
				{
					?>
					<center><hr style="width: 80%;" /></center><br />
					<center><b><?=$garageErrorEx;?></b></center><br />
					<?
				}
				
				/* Vehicle Lookup Success */
				if ( isset( $infochar ) )
				{
					?>
					<center><hr style="width: 80%;" /></center><br />
					<b><?=$_POST["nickname"];?> owns a</b>:<br />
					<span style="color: #004123;"><?=$infochar->getVehicleInfo( "name" );?></span> <span style="font-size: 10px;">(<?=getVehicleHealthString( $infochar->getVehicleHealth(), false );?>)</span><br />
					<?
					/* List Tuneups */
					$tuneups = $db->query( "SELECT * FROM char_tuneups WHERE char_id=" . $infochar->character_id );

					if ( $db->getRowCount( $tuneups ) != 0 )
					{
						?>
						<span style="font-size: 10px;">
						<?
						while ( $tuneup = $db->fetch_array( $tuneups ) )
						{
							$trow = $db->getRowsFor( "SELECT * FROM vehicles_tuneups WHERE id=" . $tuneup['tuneup_id'] );
							?>
							<span style="color: #3C6C84;"><?=$trow["name"];?></span><br />
							<?
						}
						?>
						</span>
						<br />
						<?
					}
					else
					{
						?><span style="font-size: 10px;">(no modifications)</span><?
					}
				}
				?>
			</div>
		</td>
	</tr>
</table>
<?
include_once "../includes/footer.php";
?>