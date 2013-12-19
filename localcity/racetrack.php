<?
/* The Racing track.
 * This is the localcity racetrack where players with vehicles can compete.
 **
 * Notes:
 * The racetrack is bugged. It does not hand out money to winning betters, and possibly driver.
 */


$ext_style = "forums_style.css";
$nav = "localcity";
$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = true;
$_SESSION['ingame'] = true;
include_once "../includes/utility.inc.php";
$char = new Player( getUserID() );

/* Check for race track */
$q = $db->query( "SELECT * FROM localcity INNER JOIN businesses ON localcity.business_id = businesses.id WHERE business_type=8 AND location_id=". $char->location_nr );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This city doesn't even have a race track!";
	header( "Location: index.php" );
	exit;
}

$business = $db->fetch_array( $q );

/* Constructors */
$israce = false;
$needdrivers = false;
$canbet = false;
$applied = false;
$inrace = false;

/* Get Race Information */
$racequery = $db->query( "SELECT * FROM racetrack_race WHERE race_location=" . $db->prepval( $char->location_nr ) . " AND race_done='false'" );
if ( $db->getRowCount( $racequery ) != 0 )
{
	$israce = true;
	$race = $db->fetch_array( $racequery );
} else $race = false;

/* Is a Race */
if ( $israce )
{
	/* Driver Information */
	if ( $race['driver_id_1'] == 0 || $race['driver_id_2'] == 0 || $race['driver_id_3'] == 0 ) $needdrivers = true;

	if ( $race['driver_id_1'] != 0 )
	{
		$chkq = $db->getRowsFor( "SELECT nickname FROM char_characters WHERE id=" . $race['driver_id_1'] );
		$driver_1 = new Player( $chkq['nickname']  );
	} else $driver_1 = false;

	if ( $race['driver_id_2'] != 0 )
	{
		$chkq = $db->getRowsFor( "SELECT nickname FROM char_characters WHERE id=" . $race['driver_id_2'] );
		$driver_2 = new Player( $chkq['nickname']  );
	} else $driver_2 = false;

	if ( $race['driver_id_3'] != 0 )
	{
		$chkq = $db->getRowsFor( "SELECT nickname FROM char_characters WHERE id=" . $race['driver_id_3'] );
		$driver_3 = new Player( $chkq['nickname']  );
	} else $driver_3 = false;

	if ( $driver_1 && !$driver_1->hasVehicle() ) $driver_1 = false;
	if ( $driver_2 && !$driver_2->hasVehicle() ) $driver_2 = false;
	if ( $driver_3 && !$driver_3->hasVehicle() ) $driver_3 = false;

	if ( $driver_1 || $driver_2 || $driver_3 ) $canbet = true;

	/* Check if the player has applied to join already */
	if ( $char->hasVehicle() )
	{
		$driverresult = $db->query( "SELECT id FROM racetrack_applications WHERE driver_id=" . getCharacterID() );
		if ( $db->getRowCount( $driverresult ) > 0 ) $applied = true;
	}

	if ( $driver_1 && getCharacterID() == $driver_1->getCharacterID() )
		$inrace = true;
	elseif ( $driver_2 && getCharacterID() == $driver_2->getCharacterID() )
		$inrace = true;
	elseif ( $driver_3 && getCharacterID() == $driver_3->getCharacterID() )
		$inrace = true;

	/* Place Bet */
	if ( isset( $_POST['bet'] ) )
	{
		if ( $_POST['driver'] == 'noselect' )
		{
			header( "location: " . $rootdir . "/localcity/racetrack.php" );
			exit();
		}

		if ( $inrace )
		{
			$_SESSION['message'] = "You are racing, you may not place a bet.";
		}
		elseif ( $_POST['betsize'] < 1 )
		{
			$_SESSION["message"] = "What...? Why would you even try that?";
		}
		elseif ( $char->getCleanMoney() < $_POST['betsize'] )
		{
			$_SESSION['message'] = "You don't have that kind of money to bet! Place a smaller ammount.";
		}
		elseif ( $db->getRowCount( $db->query( "SELECT * FROM racetrack_bets WHERE race_id=" . $race['race_id'] . " AND char_id=" . getCharacterID() ) ) > 0 )
		{
			$_SESSION['message'] = "You've already placed your bet, you cannot bet again!";
		}
		else
		{
			/* Player can make bet */
			$char->setCleanMoney( $char->getCleanMoney() - $_POST['betsize'] );
			$db->query( "INSERT INTO racetrack_bets (char_id, race_id, driver_id, betsize) VALUES (" . getCharacterID() . ", " . $race['race_id'] . ", " . $db->prepval( $_POST['driver'] ) . ", " . $db->prepval( $_POST['betsize'] ) . ")" );

			/* Caculations */
			$total = $_POST['betsize'];
			$betbudget = round( $total / 2 ); 
			$total = $betbudget;
			$winnerbudget = round( $total / 2 );
			$ownerbudget = round( $total / 2 );

			$db->query( "UPDATE racetrack_race SET bet_budget=bet_budget+" . $betbudget . ", winner_budget=winner_budget+" . $winnerbudget . ", owner_budget=owner_budget+" . $ownerbudget . " WHERE race_id=" . $race['race_id'] );

			$_SESSION['message'] = "Your bet has been placed, you will be notified when the race starts.";
		}
	}
	elseif ( isset( $_POST['application'] ) )
	{
		if ( $inrace )
		{
			$_SESSION['message'] = "You are already racing, you can't apply again.";
		} 
		else
		{
			$_SESSION['message'] = "Your application has been sent to the owner for review.";
			$db->query( "INSERT INTO racetrack_applications (id, race_id, driver_id) VALUES ('', '" . $race['race_id'] . "', '" . getCharacterID() . "')" );
			$applied = true;
		}
	}
	elseif ( isset( $_POST['selectdriver'] ) )
	{
		// Form information...
		// selectdriver - submit button to fill a position
		// driverapp - select box, lists driver id's. (noselect default)
		// driverposition - select box, lists driver position to be filled (1, 2, or 3) (noselect default)
		if ( $_POST['driverapp'] == "noselect" || $_POST['driverposition'] == "noselect" )
		{
			$_SESSION['message'] = "You must select a driver and position in order to enter them into the race.";
		}
		else
		{
			/* Form info entered correctly, check if position is filled or not */
			if ( $_POST['driverposition'] == '1' && $driver_1 )
			{
				$_SESSION["message"] = "This position has already been filled.";
			}
			elseif ( $_POST['driverposition'] == '2' && $driver_2 )
			{
				$_SESSION["message"] = "This position has already been filled.";
			}
			elseif ( $_POST['driverposition'] == '3' && $driver_3 )
			{
				$_SESSION["message"] = "This position has already been filled.";
			}
			else
			{
				/* Start entering info */
				$nickq = $db->getRowsFor( "SELECT nickname FROM char_characters WHERE id=" . $_POST['driverapp'] );
				$newdriver = new Player( $nickq['nickname'] );

				$db->query( "UPDATE racetrack_race SET driver_id_" . $_POST['driverposition'] . "=" . $newdriver->getCharacterID() . " WHERE race_id=" . $_POST['race_id'] );

				$db->query( "DELETE FROM racetrack_applications WHERE driver_id=" . $newdriver->getCharacterID() );

				$newdriver->addEvent( "Racetrack Application", "Your application at the Race Track has been accepted! You will be notified when the owner starts the race." );

				$_SESSION['message'] = $newdriver->nickname . " has been notified, and added to the race.";

				/* Just for synchronization, create the new driver to eliminate page refresh */
				if ( $_POST['driverposition'] == '1' ) $driver_1 = $newdriver;
				if ( $_POST['driverposition'] == '2' ) $driver_2 = $newdriver;
				if ( $_POST['driverposition'] == '3' ) $driver_3 = $newdriver;
			}
		}
	}
	elseif ( isset( $_POST['race'] ) )
	{
		/* START THE RACES!!!! */
		/* Remember To
		 * Delete the Race, and Insert a New one.
		 * Caculate the Budgets and deliever money to gamblers, driver (winner), and owner.
		 * Send win/lose event to drivers.
		 * Unset driver_1, driver_2, and driver_3.
		 */
		
		// Cache
		$driver_1_speed = $driver_1->getVehicleSpeed();
		$driver_2_speed = $driver_2->getVehicleSpeed();
		$driver_3_speed = $driver_3->getVehicleSpeed();

		// Calculate Statistics - if someone has a better forumula, use it.
		$driver_1_outcome = mt_rand( 0, 100 ) * $driver_1_speed;
		$driver_2_outcome = mt_rand( 0, 100 ) * $driver_2_speed;
		$driver_3_outcome = mt_rand( 0, 100 ) * $driver_3_speed;

		if ( $driver_1_outcome > $driver_2_outcome && $driver_1_outcome > $driver_3_outcome )
		{
			$winner = $driver_1;
			unset( $driver_1 );
		}
		elseif ( $driver_2_outcome > $driver_1_outcome && $driver_2_outcome > $driver_3_outcome )
		{
			$winner = $driver_2;
			unset( $driver_2 );
		}
		elseif ( $driver_3_outcome > $driver_1_outcome && $driver_3_outcome > $driver_2_outcome )
		{
			$winner = $driver_3;
			unset( $driver_3 );
		} else $winner = false; // Who cares at this point.

		// Tiebreaker
		if ( !$winner )
		{
			$winner = mt_rand( 1, 3 );
			if ( $winner == 1 ) { $winner = $driver_1; unset( $driver_1 ); }
			if ( $winner == 2 ) { $winner = $driver_2; unset( $driver_2 ); }
			if ( $winner == 3 ) { $winner = $driver_3; unset( $driver_3 ); }
		}

		// Deliever Money, and Add Event to Betters.
		$betters = $db->query( "SELECT * FROM racetrack_bets WHERE race_id=" . $_POST['race_id'] );
		if ( $db->getRowCount( $betters ) != 0 )
		{
			while ( $bet = $db->fetch_array( $betters ) )
			{
				if ( $bet['driver_id'] == $winner->getCharacterID() )
				{
					/* The bet has been won. */
					$betplayer = new Player( $bet['char_id'], false );
					$betplayer->addEvent( "Race Track Outcome", "The outcome of the race is in, and the driver you bet on (" . $winner->nickname . ") has won! You were given $" . $bet['betsize'] * 3 . " as your reward." );
					$betplayer->setCleanMoney( $betplayer->getCleanMoney( true ) + ( $bet['betsize'] * 3 ) );
				}
				else
				{
					$betplayer = new Player( $bet['char_id'], false );
					$betplayer->addEvent( "Race Track Outcome", "The outcome of the race is in, and the driver you bet on has lost to " . $winner->nickname . ". You lost your bet of $" . $bet['betsize'] . " to the racetrack." );

					/* Give money to owner */
					$db->query( "UPDATE racetrack_race SET owner_budget=owner_budget+" . $bet['betsize'] . " WHERE race_id=" . $_POST['race_id'] );
				}
			}
		}

		// Give owner money
		$ownerbudget = $db->getRowsFor( "SELECT owner_budget FROM racetrack_race WHERE race_id=" . $_POST['race_id'] );
		$char->setCleanMoney( $char->getCleanMoney( true ) + $ownerbudget['owner_budget'] );
		$_SESSION["message"] = "The race ended, and " . $winner->nickname . " has won! You collect all lost bets ($" . $ownerbudget['owner_budget'] . "). A new race has been setup and is ready for driver applications.";

		// Give winner money and add event
		$winnerbudget = $db->getRowsFor( "SELECT winner_budget FROM racetrack_race WHERE race_id=" . $_POST['race_id'] );
		$winner->setCleanMoney( $winner->getCleanMoney( true ) + $winnerbudget['winner_budget'] );
		$winner->addEvent( "Race Track Outcome", "You raced down the track in your " . $winner->getVehicleInfo( 'name' ) . " and completely left the other drivers in the dust! You win $" . $winnerbudget['winner_budget'] . "!" );

		// Give losers event
		if ( isset( $driver_1 ) ) 
			$driver_1->addEvent( "Race Track Outcome", "As the race started, you raced down the track in your " . $driver_1->getVehicleInfo( 'name' ) . " only to be blown away by " . $winner->nickname . " and their " . $winner->getVehicleInfo( 'name' ) . ", you lost the race." );

		if ( isset( $driver_2 ) ) 
			$driver_2->addEvent( "Race Track Outcome", "As the race started, you raced down the track in your " . $driver_2->getVehicleInfo( 'name' ) . " only to be blown away by " . $winner->nickname . " and their " . $winner->getVehicleInfo( 'name' ) . ", you lost the race." );

		if ( isset( $driver_3 ) ) 
			$driver_3->addEvent( "Race Track Outcome", "As the race started, you raced down the track in your " . $driver_3->getVehicleInfo( 'name' ) . " only to be blown away by " . $winner->nickname . " and their " . $winner->getVehicleInfo( 'name' ) . ", you lost the race." );

		/* Developer Notes:
		 * If you are attempting to test the race track,
		 * it will make the job a lot easier if you comment
		 * out the following two queries. It will then keep
		 * the old race, and allow you to simply race it again
		 * after page refresh.
		 */
		// Delete Race & Add New
		$db->query( "DELETE FROM racetrack_race WHERE race_id=" . $_POST['race_id'] );
		$db->query( "INSERT INTO racetrack_race (race_id, race_location, race_done, driver_id_1, driver_id_2, driver_id_3, bet_budget, winner_budget, owner_budget) VALUES ('', " . $char->location_nr . ", 'false', 0, 0, 0, 0, 0, 0)" );
		
		// Unset Players
		unset( $driver_1 );
		unset( $driver_2 );
		unset( $driver_3 );

		// Reconstruct Variables
		$needdrivers = true;
	}
}

include_once "../includes/header.php";
?>

<h1><?=$business['name'];?></h1>
<p>Welcome to the races! If you feel like luck is on your side tonight then this is defiantly the place to put it. Just place a bet on the car who you think is likely to win and wait for the race to start! If on the other hand you have a fast car, the track managers are always looking for some new talent, and you might just concider signing up for a race or two.</p>

<?
/* Message */
if ( isset( $_SESSION['message'] ) )
{
	echo "<div style='width: 90%; margin-left: auto; margin-right: auto;'>";
	echo "<p><strong>". $_SESSION['message'] ."</strong></p>";
	echo "</div>";
	unset( $_SESSION['message'] );
}

/* Content */
if ( !isset( $_GET['act'] ) )
{
	/* Default Content */
	?>
	<table style='width: 100%;'>
		<tr>
			<!-- First Col. -->
			<td style="width: 48%; vertical-align: top;">
				<div class="titlerace">Betting Booth</div></div>
				<div class="contentrace">
					<? if ( !$israce ) { ?>There is no race scheduled for this city.<? } else { ?>
						<? if ( !$canbet ) { ?><font color='#2C4949'>There is a race today</font>, however, there are currently no drivers selected to race yet.<? } else { ?>
							<!-- Actual Betting Content Starts Here -->
							<form method='post' action='<?=$rootdir;?>/localcity/racetrack.php'>
							<select name="driver" class="std_input">
								<option value='noselect'>---- Select Driver ----</option>
								<? if ( $driver_1 ): ?><option value='<?=$race['driver_id_1'];?>'><?=$driver_1->nickname;?></option><? endif; ?>
								<? if ( $driver_2 ): ?><option value='<?=$race['driver_id_2'];?>'><?=$driver_2->nickname;?></option><? endif; ?>
								<? if ( $driver_3 ): ?><option value='<?=$race['driver_id_3'];?>'><?=$driver_3->nickname;?></option><? endif; ?>
							</select>
							&nbsp;$<input type='text' size='5' name='betsize' class="std_input" />
							&nbsp;<input type='submit' class='std_input' value='    Place Bet    ' name='bet' />
							</form>
							<!-- Betting Content Ends Here -->
						<? } ?>
					<? } ?>
				</div>
			</td>

			<!-- End First Col. Start Spacer -->
			<td style="width: 4%;">&nbsp;</td>
			<!-- End Spacer Start Second Col. -->

			<td style="width: 48%; vertical-align: top;">
				<div class="titlerace">Driver Information</div></div>
				<div class="contentrace">
					<? if ( !$israce ) { ?>No race, no drivers. Sorry folks.<? } else { ?>
						<font color='#823428'>Its strongly advised</font> that you wait until all three drivers have been chosen so you get a clear picture of what the race is going to turn out like. The current selected drivers are listed below.<br /><br />
						
						<!-- Driver 1 -->
						<? if ( $driver_1 ) { ?>
							<b><a href='<?=$rootdir;?>/profile.php?id=<?=$race['driver_id_1'];?>' style='text-decoration: none;' title='View Profile'><?=$driver_1->nickname;?></a></b> will be racing a <? if ( $driver_1->hasTuneups() ) { ?>modified <? } ?><?=$driver_1->getVehicleInfo( 'name' );?>. The shape vehicle itself is <?=getVehicleHealthString( $driver_1->getVehicleHealth(), false );?>.<br /><br />
						<? } ?>
						<? if ( $driver_2 ) { ?>
							<!-- Driver 2 -->
							<b><a href='<?=$rootdir;?>/profile.php?id=<?=$race['driver_id_2'];?>' style='text-decoration: none;' title='View Profile'><?=$driver_2->nickname;?></a></b> will be racing a <? if ( $driver_2->hasTuneups() ) { ?>modified <? } ?><?=$driver_2->getVehicleInfo( 'name' );?>. The shape vehicle itself is <?=getVehicleHealthString( $driver_2->getVehicleHealth(), false );?>.<br /><br />
						<? } ?>
						<? if ( $driver_3 ) { ?>
							<!-- Driver 3 -->
							<b><a href='<?=$rootdir;?>/profile.php?id=<?=$race['driver_id_3'];?>' style='text-decoration: none;' title='View Profile'><?=$driver_3->nickname;?></a></b> will be racing a <? if ( $driver_3->hasTuneups() ) { ?>modified <? } ?><?=$driver_3->getVehicleInfo( 'name' );?>. The shape vehicle itself is <?=getVehicleHealthString( $driver_3->getVehicleHealth(), false );?>.<br /><br />
						<? } ?>
						<? if ( !$driver_1 && !$driver_2 && !$driver_3 ) { ?>No drivers listed to race.<? } ?>
					<? } ?>
				</div>
			</td>
		</tr>
	</table>
	<? 
	if ( $char->hasVehicle() )
	{
		?>
		<div style="width: 70%; margin-left: auto; margin-right: auto;">
			<div class="titlerace">Driver Signup</div>
			<div class="contentrace">
				<? 
				if ( !$israce ) 
				{
					?>Sorry, there isn't a need for any drivers since there is no race here!<?
				} 
				elseif ( !$needdrivers )
				{
					?>Sorry, there isn't a need for any more drivers in the comming race as the owner has already reviewed all applications and filled the driver positions. <font color='#611D22'>Try again next race!</font><?
				}
				elseif ( $applied )
				{
					?>You've already applied to join this race, your application is still in que.<?
				}
				elseif ( $inrace )
				{
					?>You are already in this race.<?
				}
				else
				{
					?>
					Currently there are positions to be filled for the next race! Just submit the information below and the owner of the race track will review your application. You will be notified if you are chosen to race or not.<br /><br />
					<form method='post' action='<?=$rootdir;?>/localcity/racetrack.php'>
					<table style="width: 50%;">
						<tr>
							<td style="width: 40%;">Driver Name:</td>
							<td style="width: 60%;"><input type='text' readonly='true' class='std_input' value='<?=$char->nickname;?>' /></td>
						</tr>
						<tr>
							<td style="width: 40%;">Vehicle:</td>
							<td style="width: 60%;"><input type='text' readonly='true' class='std_input' value='<?=$char->getVehicleInfo( 'name' );?>' /></td>
						</tr>
						<tr>
							<td style="width: 40%;"></td>
							<td style="width: 60%;"><input type='submit' name='application' value='Submit Application' class='std_input' /></td>
						</tr>
					</table>
					</form>
					<?
				}
				?>
			</div>
		</div>
		<?
		if ( $business['owner_id'] == getCharacterID() )
		{
			?>
			<div style="width: 70%; margin-left: auto; margin-right: auto;">
				<div class="titleraceex">Manager's Panel</div>
				<div class="contentraceex">
					From here you can select drivers to fill the next race position, or you can <font color='#612B29'>start the race when all drivers have been chosen</font>.<br /><br />
					<form method='post' action='<?=$rootdir;?>/localcity/racetrack.php'>
					<b>Driver Applications</b><br />
					These drivers are currently waiting to be selected for the next race.<br />
					<input type='hidden' name='race_id' value='<?=$race['race_id'];?>' />
					<select name="driverapp" class="std_input">
						<?
						$dappq = $db->query( "SELECT * FROM racetrack_applications WHERE race_id=" . $race['race_id'] );
						if ( $db->getRowCount( $dappq ) == 0 )
						{
							?><option value="noselect">No Applications Found</option><?
						}
						else
						{
							?><option value="noselect">---- Select Driver ----</option><?
							while ( $application = $db->fetch_array( $dappq ) ) 
							{
								$nickq = $db->getRowsFor( "SELECT nickname FROM char_characters WHERE id=" . $application['driver_id'] );
								?><option value="<?=$application['driver_id'];?>"><?=$nickq['nickname'];?></option><?
							}
						}
						?>
					</select>&nbsp;
					<select name="driverposition" class="std_input">
						<option value="noselect">---- Select Position ----</option>
						<?
						if ( !$driver_1 ): ?><option value="1">Position 1</option><? endif;
						if ( !$driver_2 ): ?><option value="2">Position 2</option><? endif;
						if ( !$driver_3 ): ?><option value="3">Position 3</option><? endif;
						if ( $driver_1 && $driver_2 && $driver_3 ): ?><option value="noselect">All Positions Filled</option><? endif;
						?>
					</select>&nbsp;
					<input type='submit' class='std_input' name='selectdriver' value='  Select Driver  ' />
					</form>
					<br /><br />
					<?
					/* Ready to race? */
					if ( $driver_1 && $driver_2 && $driver_3 )
					{
						?>
						<b>Ready to Race?</b><br />
						All drivers have been chosen, now all you need to do is tell them your ready to race!<br />
						<form method="post" action="<?=$rootdir;?>/localcity/racetrack.php">
						<input type='hidden' name='race_id' value='<?=$race['race_id'];?>' />
						<input type='submit' class='std_input' value='                Start Race                ' name='race' />
						</form><br />
						<?
					}
					?>
				</div>
			</div>
			<?
		}
	}
}
else
{
	/* Special Content */

}

include_once "../includes/footer.php";
?>