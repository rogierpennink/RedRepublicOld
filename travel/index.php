<?
/**
 * The traveling page.
 */
$nav = "travel";
$AUTH_LEVEL = 0;
$CHARNEEDED = true;

include_once "../includes/utility.inc.php";

/* Flight Fixer
 * This section of code takes care of deleting
 * flights that have made it, and inserting new
 * flights based on how many are left, and at
 * what locations */
$ffquery = array(
	"TotalFlights" => $db->query( "SELECT * FROM travel_flights WHERE `from`=" . $char->location_nr ),
	"EnRoute" => $db->query( "SELECT * FROM travel_flights WHERE `from`=" . $char->location_nr . " AND `flightstart`<" . time() . " AND `flightend`>" . time() ),
	"Ended" => $db->query( "SELECT * FROM travel_flights WHERE `from`=" . $char->location_nr . " AND `flightend`<" . time() ),
	"Current" => $db->query( "SELECT * FROM travel_flights WHERE `from`=" . $char->location_nr . " AND `flightstart`>" . time() )
);

/* Delete flights that have ended. */
while ( $row = $db->fetch_array( $ffquery["Ended"] ) )
{
	//$db->query( "DELETE FROM travel_flights WHERE `id`=" . $row["id"] . " AND `flightend`<" . time() );
	$db->query( "DELETE FROM travel_flights_tickets WHERE flight_id=" . $row["id"] );
}

/* Insert new flights when needed */
if ( $db->getRowCount( $ffquery["Current"] ) <= 5 )
{
	$count = $db->getRowCount( $ffquery["Current"] );
	for ( $i = $count-1; $i < 5; $i++ )
	{
		$loc = $db->getRowsFor( "SELECT id FROM locations WHERE id!=" . $char->location_nr . " ORDER BY RAND() LIMIT 1" );
		
		if ( $loc["id"] > $char->location_nr ) $diff = $loc["id"]-$char->location_nr;
		if ( $char->location_nr > $loc["id"] ) $diff = $char->location_nr-$loc["id"]; 

		$startrand = time()+mt_rand($diff*mt_rand(100, 500), $diff*mt_rand(500, 1000));
		$endrand = (time()+$startrand)+mt_rand($diff*mt_rand(100, 500), $diff*mt_rand(500, 1000));
		$diffoeta = $endrand-$startrand;
		$costrand = $diff*mt_rand(1000, 1500);
		
		$db->query( "INSERT INTO travel_flights SET `to`=" . $loc["id"] . ", `from`=" . $char->location_nr . ", `flightstart`=" . $startrand . ", `flightend`=" . $endrand . ", `cost`=" . $costrand . ", `tickets`=20" );
	}
}

/* Purchasing Ticket */
if ( isset( $_GET["act"] ) && $_GET["act"] == "purchase" && isset( $_GET["id"] ) )
{
	if ( !is_numeric( $_GET["id"] ) )
		$_SESSION["airtravel_error"] = "Invalid ID parameter.";
	else
	{
		$ticketchk = $db->query( "SELECT * FROM travel_flights_tickets WHERE char_id=" . $char->getCharacterID() . " AND done='false'" );
		
		/* Player Ticket Check */
		if ( $db->getRowCount( $ticketchk ) > 0 )
			$_SESSION["airtravel_error"] = "You already have a ticket to fly, you cannot purchase another one from this airline.";
		else
		{
			$flight_query = $db->query( "SELECT * FROM travel_flights WHERE `id`=" . $db->prepval( $_GET["id"] ) . " AND `flightstart`>" . time() . " AND `flightend`>" . time() . " AND `from`=" . $char->location_nr );
			
			/* Flight Check */
			if ( $db->getRowCount( $flight_query ) == 0 )
				$_SESSION["airtravel_error"] = "Sorry, but this flight has recently taken off, looks like you just missed it.";
			else
			{
				$flight = $db->fetch_array( $flight_query );
				
				/* Tickets Check */
				if ( $flight["tickets"] == 0 )
					$_SESSION["airtravel_error"] = "Sorry, but this flight has sold out of tickets.";
				else
				{
					/* Money Check... */
					if ( $char->getCleanMoney( true ) < $flight["cost"] )
						$_SESSION["airtravel_error"] = "Sorry, but you must pay for this flight in cash.";
					else
					{
						/* Success Purchase */
						$char->setCleanMoney( $char->getCleanMoney( true )-$flight["cost"] );
						$db->query( "INSERT INTO travel_flights_tickets SET char_id=" . $char->getCharacterID() . ", flight_id=" . $flight["id"] . ", cost=" . $flight["cost"] . ", `to`=" . $flight["to"] );
						$db->query( "UPDATE travel_flights SET `tickets`=`tickets`-1 WHERE `id`=" . $flight["id"] );
						$_SESSION["airtravel_error"] = "Thanks for choosing " . $char->location . " Air!";
					}
				}
			}
		}
	}
}

/* Private Jet */
if ( isset( $_POST["privatejet"] ) )
{
	$ticketchk = $db->query( "SELECT * FROM travel_flights_tickets WHERE char_id=" . $char->getCharacterID() . " AND done='false'" );
	if ( $db->getRowCount( $ticketchk ) == 0 )
	{
		$time = time()+60*60*2;
		$db->query( "INSERT INTO travel_flights SET `to`=" . $db->prepval( $_POST["location"] ) . ", `from`=" . $char->location_nr . ", `flightstart`=" . time() . ", `flightend`=" . $time . ", `cost`=0, `tickets`=0" );
		$db->query( "INSERT INTO travel_flights_tickets SET `flight_id`=" . mysql_insert_id() . ", `char_id`=" . $char->getCharacterID() . ", `cost`=0, `to`=" . $_POST["location"] . ", `done`='false'" );
		$_SESSION["airtravel_error"] = "The Bank of " . $char->homecity . " has paid for your ticket to " . getLocationInfo( $_POST["location"], "location_name" ) . ".";
		
		$bank_query = $db->query( "SELECT * FROM localcity INNER JOIN businesses ON localcity.business_id = businesses.id WHERE business_type=6 AND location_id=". $char->homecity_nr );

		if ( $db->getRowCount( $bank_query ) > 0 )
		{
			$bank = $db->fetch_array( $bank_query );
			$db->query( "UPDATE bank_accounts SET balance=balance-5000 WHERE account_number=" . $bank["bank_id"] );
		}
	}
	else
		$_SESSION["airtravel_error"] = "You already have a ticket to fly, you can only have one ticket at a time.";
}

/* Refund Ticket */
if ( isset( $_GET["act"] ) && $_GET["act"] == "refund" && isset( $_GET["id"] ) )
{
	if ( !is_numeric( $_GET["id"] ) )
		$_SESSION["airtravel_error"] = "Invalid ID parameter.";
	else
	{
		$ticketchk = $db->query( "SELECT * FROM travel_flights_tickets WHERE char_id=" . $char->getCharacterID() . " AND flight_id=" . $db->prepval( $_GET["id"] ) );

		/* Ticket Check */
		if ( $db->getRowCount( $ticketchk ) == 0 )
			$_SESSION["airtravel_error"] = "You don't have a ticket for this flight.";
		else
		{
			/* Refund ticket price by 1/2. */
			$ticket = $db->fetch_array( $ticketchk );

			$refund = floor( $ticket["cost"]/2 );

			$char->setCleanMoney( $char->getCleanMoney( true ) + $refund );

			$_SESSION["airtravel_error"] = "Half the cost of your ticket was refunded. Have a nice day.";

			$db->query( "UPDATE travel_flights SET `tickets`=`tickets`+1 WHERE `id`=" . $ticket["flight_id"] );
			$db->query( "DELETE FROM travel_flights_tickets WHERE id=" . $ticket["id"] );
		}
	}
}

/* Admin Flight and Ticket Resets */
if ( isset( $_GET["act"] ) && isset( $_GET["mode"] ) && $_GET["act"] == "reset" )
{
	if ( getUserRights() >= USER_SUPERADMIN )
	{
		/* Reset Flights */
		if ( $_GET["mode"] == "flights" )
			$db->query( "DELETE FROM travel_flights" );
		
		if ( $_GET["mode"] == "tickets" )
			$db->query( "DELETE FROM travel_flights_tickets" );

		header( "location: " . $rootdir . "/travel/index.php" );
	}
}

/* Travel by car */
if ( isset( $_POST["cartravel"] ) )
{
	if ( !isset( $_SESSION["travelcost"] ) || !isset( $_SESSION["start"] ) || !isset( $_SESSION["end"] ) )
		$_SESSION["cartravel_error"] = "Invalid setup.";
	else
	{
		/* Ticket Check */
		$ticketchk = $db->query( "SELECT * FROM travel_flights_tickets WHERE char_id=" . $char->getCharacterID() . " AND done='false'" );

		if ( $db->getRowCount( $ticketchk ) > 0 )
			$_SESSION["cartravel_error"] = "You already have a ticket for a flight, you cannot set off by car now.";
		else
		{
			if ( !$char->hasVehicle() )
				$_SESSION["cartravel_error"] = "You need a vehicle in order to travel by car.";
			else
			{
				if ( $char->getCleanMoney( true ) < $_SESSION["travelcost"] )
					$_SESSION["cartravel_error"] = "You don't have the funds to travel by car.";
				else
				{
					/* Success */
					$db->query( "UPDATE char_vehicles SET `traveling`='true', `to`=" . $_POST["location"] . ", `from`=" . $char->location_nr . ", `end`=" . $_SESSION["end"] . " WHERE `id`=" . $char->getCharacterID() );
					$rowquery = $db->query( "SELECT * FROM char_vehicles WHERE `traveling`='true' AND `id`=" . $char->getCharacterID() );
					$row = $db->fetch_array( $rowquery );

					$char->setCleanMoney( $char->getCleanMoney( true ) - $_SESSION["travelcost"] );
					$char->addLargeEvent( "Traveling", "You are currently traveling to " . getLocationInfo( $row["to"], "location_name" ) . " from " . getLocationInfo( $row["from"], "location_name" ) . ". You are expected to arrive at " . date( "g:i A", $row["end"] ) . ". Currently the time is " . date( "g:i A", time() ) . "." );

					$_SESSION["cartravel_error"] = "You have packed your bags and set out for " . getLocationInfo( $row["to"], "location_name" ) . ".";
				}
			}
		}
	}
}

/* Start Output */
include_once "../includes/header.php";
?>
<h1>Traveling</h1>
<p><?=$char->location;?> has many great travel agencies ready to get you were you need to go, but the location, the method of travel, and who you are will effect your time of arrival and your personal security while traveling.</p>

<table align="center" style="width: 100%; vertical-align: top;"><tr><td style="width: 100%;" align="center">
<div style="width: 80%;" align="left">
    <div class="title">
		<div style="margin-left: 10px;"><?=$char->location;?> Air Travel</div>
    </div>
	<div class="content">
		<?
		/* Error for Air Travel */
		if ( isset( $_SESSION["airtravel_error"] ) )
		{
			?>
			<b><?=$_SESSION["airtravel_error"];?></b><br />
			<?
			unset( $_SESSION["airtravel_error"] );
		}
		?>
		<div class="content" style="background-color: #fff686; border-top: solid 1px; padding: 0px;">			
			<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
				<table class="row">
					<tr>		
						<td class="field" style="width: 11%;"><strong>Time</strong></td>
						<td class="field" style="width: 30%;"><strong>Destination</strong></td>
						<td class="field" style="width: 15%;"><strong>Ticket Price</strong></td>
						<td class="field" style="width: 11%;"><strong>ETA</strong></td>
						<td class="field" style="width: 23%;"><strong>Options</strong></td>			
					</tr>
				</table>
			</div>
			<?
			$flightquery = $db->query( "SELECT * FROM travel_flights WHERE `flightstart`>" . time() . " AND `from`=" . $char->location_nr . " ORDER BY `flightstart`" );
			$flightsquery_started = $db->query( "SELECT * FROM travel_flights WHERE `flightstart`<" . time() . " AND `flightend`>" . time() . " AND `from`=" . $char->location_nr . " LIMIT 3" );
			
			/* Flights En Route Listing */
			while ( $row = $db->fetch_assoc( $flightsquery_started ) )
			{
				?>
				<div class="row">
					<table class="row">
						<tr>		
							<td class="field" style="width: 11%;"><span style="color: #CC0000;">En Route</span></td>
							<td class="field" style="width: 30%;"><?=getLocationInfo( $row["to"], "location_name" );?></td>
							<td class="field" style="width: 15%;"><span style="color: #575757;">$<?=$row["cost"];?></span></td>
							<td class="field" style="width: 11%;"><?=date( "g:i A", $row["flightend"] ); ?></td>
							<td class="field" style="width: 23%;">&nbsp;</td>			
						</tr>
					</table>
				</div>
				<?
			}
		

			/* Available Flights Listing */
			while ( $row = $db->fetch_array( $flightquery ) )
			{
				$charticketquery = $db->query( "SELECT * FROM travel_flights_tickets WHERE char_id=" . $char->getCharacterID() . " AND flight_id=" . $row["id"] );
				$charticketarray = $db->fetch_array( $charticketquery );
				?>
				<div class="row">
					<table class="row">
						<tr>		
							<td class="field" style="width: 11%;"><?=date( "g:i A", $row["flightstart"] ); ?></td>
							<td class="field" style="width: 30%;"><?=getLocationInfo( $row["to"], "location_name" );?></td>
							</td>
							<td class="field" style="width: 15%;">
								<? if ( $charticketarray["flight_id"] == $row["id"] ) { ?>
									<span style="color: blue;">(purchased)</span>
								<? } elseif ( $row["tickets"] == 0 ) { ?>
									<span style="color: #653636;">(sold out)</span>
								<? } else { ?>
									<span style="color: green;">$<?=$row["cost"];?></span>
								<? } ?>
							</td>
							<td class="field" style="width: 11%;"><?=date( "g:i A", $row["flightend"] ); ?></td>
							<td class="field" style="width: 23%;">
								<form method="post" action="<?=$rootdir;?>/travel/index.php">
									<select name="flightoption" class="std_input">
										<!-- Flight Options -->
										<option value="null">--- Select Option ---</option>
										<? if ( $row["tickets"] > 0 && $db->getRowCount( $charticketquery ) == 0 ) { ?>
											<option value="purchase" onclick="window.location='<?=$rootdir;?>/travel/index.php?act=purchase&id=<?=$row["id"];?>'">Purchase Ticket</option>
										<? } ?>
										
										<? if ( $db->getRowCount( $charticketquery ) > 0 ) { ?>
											<option value="refund" onclick="window.location='<?=$rootdir;?>/travel/index.php?act=refund&id=<?=$row["id"];?>'">Refund Ticket</option>
										<? } ?>
									</select>
								</form>
							</td>			
						</tr>
					</table>
				</div>
				<?
			}

			$ranks_query = $db->query( "SELECT * FROM char_ranks WHERE occupation_id=3 AND id=" . $char->rank_nr );
			$rank = $db->fetch_array( $ranks_query );

			if ( $rank["order_index"] > 1 ) $bankflight = true;
			
			if ( isset( $bankflight ) ) {
			?>
				<div class="row">
					<form method="post" action="<?=$rootdir;?>/travel/index.php">
					<table class="row">
						<tr>		
							<td class="field" style="width: 11%;">&nbsp;</td>
							<td class="field" style="width: 30%;">
								<!-- List Locations -->
								<select name="location" class="std_input" style="width: 150px;">
								<?
								$locresult = $db->query( "SELECT * FROM locations WHERE id!=" . $char->location_nr );
								while ( $loc = $db->fetch_array( $locresult ) )
								{
									?><option value="<?=$loc["id"];?>"><?=$loc["location_name"];?></option><?
								}
								?>
								</select>
							</td>
							<td class="field" style="width: 15%;">Free</td>
							<td class="field" style="width: 11%;"><?=date( "g:i A", time()+60*60*2 ); ?></td>
							<td class="field" style="width: 23%;">
								<input type="submit" class="std_input" value="        Company Jet        " name="privatejet" />
							</td>			
						</tr>
					</table>
					</form>
				</div>
				<?
			}

			if ( getUserRights() >= USER_SUPERADMIN )
			{
				?>
				<div class="row">
					<table class="row">
						<tr>		
							<td class="field" style="width: 100%;"><span style="font-size: 12px;"><a style="text-decoration: none;" href="<?=$rootdir;?>/travel/index.php?act=reset&mode=flights"><img src="<?=$rootdir;?>/images/icon_delete.gif" style="border: none; margin-bottom: -2px; margin-top: 2px;" /> Reset Flights</a> | <a href="<?=$rootdir;?>/travel/index.php?act=reset&mode=tickets" style="text-decoration: none;"><img src="<?=$rootdir;?>/images/icon_users.png" style="border: none; margin-bottom: -2px; margin-top: 2px;" /> Reset Tickets</a></span></td>	
						</tr>
					</table>
				</div>
				<?
			}
			?>
		</div>
	</div>
</div>
<div style="width: 80%;" align="left">
	<table style="width: 100%;"><tr>
		<td style="width: 55%;">
			<? 
			if ( $char->hasVehicle() )
			{
				?>
				<div class="title">
					<div style="margin-left: 10px;">Car Travel</div>
				</div>
				<div class="content">
					<? if ( isset( $_SESSION["cartravel_error"] ) ) { ?><?="<b>" . $_SESSION["cartravel_error"] . "</b><br />";?><? unset( $_SESSION["cartravel_error"] ); } ?>
					<form method="post" action="<?=$rootdir;?>/travel/index.php">
					<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
						<table class="row">
						<tr>
							<td class="field" style="width: 50%;"><strong>Destination</strong></td>
							<td class="field" style="width: 25%;"><strong>Travel Cost</strong></td>
							<td class="field" style="width: 25%;"><strong>ETA</strong></td>		
						</tr>
						</table>
					</div>
					<?
					$locresult = $db->query( "SELECT * FROM locations WHERE id!=" . $char->location_nr );
					while ( $loc = $db->fetch_array( $locresult ) )
					{
						if ( $char->location_nr > $loc["id"] ) $diff = $char->location_nr-$loc["id"];
						if ( $char->location_nr < $loc["id"] ) $diff = $loc["id"]-$char->location_nr;
						$travelcost = floor( $diff*196 );
						$start = time();
						$end = (time()+$start)+(60*60*($diff+$diff));
						$eta = date( "g:i A", $end );
						
						$_SESSION["travelcost"] = $travelcost;
						$_SESSION["start"] = $start;
						$_SESSION["end"] = $end;
						?>
						<div class="row">
							<table class="row">
							<tr>		
								<td style="width: 50%;"><input type="radio" name="location" value="<?=$loc["id"];?>" /> <?=$loc["location_name"];?></td>
								<td style="width: 25%;">$<?=$travelcost;?></td>
								<td class="field" style="width: 25%;"><?=$eta;?></td>			
							</tr>
							</table>
						</div>
						<?
					}
					?>
					<div class="row">
						<table class="row">
						<tr>
							<td style="width: 100%;"><input type="submit" name="cartravel" value="Depart" class="std_input" style="width: 100%;" /></td>
						</tr>
						</table>
					</div>
					</form>
				</div>
			<? } else { ?>
				&nbsp;
			<? } ?>
		</td>
		<td style="5%;">&nbsp;</td>
		<td style="width: 35%;">&nbsp;</td>
	</tr></table>
</div>
</td></tr></table>
<?
include_once "../includes/footer.php"; 
?>