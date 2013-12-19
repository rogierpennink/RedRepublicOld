<?
$nav = "localcity";
$AUTH_LEVEL = 0;
$REDIRECT = true;
$CHARNEEDED = true;
$_SESSION["ingame"] = true;

include_once "../includes/utility.inc.php";

/* Check for tavern */
$q = $db->query( "SELECT * FROM localcity INNER JOIN businesses ON localcity.business_id = businesses.id WHERE business_type=11 AND location_id=". $char->location_nr );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This city doesn't have a Hotel.";
	header( "Location: index.php" );
	exit;
}

if ( $char->hasHotelRoom( $char->location_nr ) )
	$nav = "hotel";

$bankq = $db->query( "SELECT * FROM bank_accounts WHERE owner_id=". $char->character_id ." AND account_type=0 AND account_status <> 0" );
$bankacc = $db->fetch_array( $bankq );	

/* Purchase Room */
if ( isset( $_POST["getroom"] ) && isset( $_POST["type"] ) && isset( $_POST["bank_id"] ) && !$char->hasHotelRoom( $char->location_nr ) )
{
	if ( !is_numeric( $_POST["bank_id"] ) )
		$_SESSION["error"] = "Invalid Bank Account #.";
	else
	{
		if ( $db->getRowCount( $bankq ) == 0 )
			$_SESSION["error"] = "You need a checkings account to get a hotel room!";
		else
		{
			if ( $bankacc["account_number"] != $_POST["bank_id"] )
				$_SESSION["error"] = "That isn't your bank account number.";
			else
			{
				$cost = 75;
				if ( $_POST["type"] == 1 ) $cost = $cost+30;

				if ( $bankacc["balance"] < $cost )
					$_SESSION["error"] = "You do not have enough money in your bank account to rent a hotel room!";
				else
				{
					/* Success */
					$time = time()+(24*60*60);
					$space = 6;

					if ( $_POST["type"] == 1 ) $space = 9;

					$db->query( "INSERT INTO char_hotelrooms SET `char_id`=" . $char->getCharacterID() . ", `location_id`=" . $char->location_nr . ", `pay_timer`=" . $time . ", `nights_stayed`=0, `hotel_type`=" . $db->prepval( $_POST["type"] ) . ", `closet_slots`=" . $space );
					$_SESSION["notify"] = "You now own a hotel room in this city.";
				}
			}
		}
	}
}

include_once "../includes/header.php";
?>
<h1><?=$char->location;?> Hotel</h1>
<p>If you need a safe haven for a little while, a hotel is the way to go. You can book a room for days on end and have the comfort of knowing that you have a safe place to sleep at night.</p>

<table align="center" style="width: 100%; vertical-align: top;"><tr><td style="width: 100%;" align="center">
<div style="width: 80%;" align="left">
	<? if ( !$char->hasHotelRoom( $char->location_nr ) ) { ?>
		<div class="title"><div style="margin-left: 10px;">Purchase Hotel Room</div></div>
		<div class="content">
			Currently the hotel is housing <b><?=$db->getRowCount( $db->query( "SELECT id FROM char_hotelrooms WHERE location_id=" . $char->location_nr ) );?></b> residents.<br />
			The price for renting a room here is <b>$75</b> per night,<br /><span style="color: #530000;">which is taken automatically from your checking
			account</span>.<br />
			<br />
			<img src="<?=$rootdir;?>/images/charstats.png" style="margin-top: 2px; margin-bottom: -2px;" /> <b>Renting a Room?</b>:<br />
			<form method="post" action="<?=$rootdir;?>/localcity/hotel.php">
			<table align="center" style="width: 100%; vertical-align: top;"><tr><td style="width: 100%;" align="center">
				<div style="width: 80%;" align="left">
					<table style="width: 100%;">
					<tr>
						<td style="width: 30%;"><b>Checkings Account #:</b></td>
						<td style="width: 70%;"><input type="text" name="bank_id" class="std_input" value="<?=( $db->getRowCount( $bankq ) == 0 ) ? "None" : $bankacc['account_number'];?>" /></td>
					</tr>
					</table>
					<table style="width: 100%;">
					<tr>
						<td style="width: 100%;"><input name="type" value="0" type="radio" /> Standard Room <input type="radio" name="type" value="1" /> Luxury Room (+<span style="color: green;">$30</span> per night)</td>
					</tr>
					</table>
					<table style="width: 100%;">
					<tr>
						<td style="width: 100%;"><input type="submit" class="std_input" name="getroom" value="Purchase Room" /></td>
					</tr>
					</table>
					<table style="width: 100%;">
					<tr>
						<td style="width: 90%;"><span style="font-size: 9px;">You may keep your room indefinatly, as long as your Checkings Account retains the required ammount to pay the fee. On purchase, your fee will be charged in the following 24 hours, then again every other 24 hours, for the duration of your stay. No refunds are available after the nightly fee is paid.</span></td><td style="width: 10%;">&nbsp;</td>
					</tr>
					</table>
				</div>
			</td></tr></table>
			</form>
		</div>
	<? 
	} 
	else
	{
		$hotel_query = $db->query( "SELECT * FROM char_hotelrooms WHERE char_id=" . $char->getCharacterID() );
		$hotel = $db->fetch_array( $hotel_query );
		?>
		<div class="title"><div style="margin-left: 10px;"><?=$char->location;?> Hotel - Room <?=$hotel["id"]*$char->getCharacterID();?></div></div>
		<div class="content">
			<?
			if ( isset( $_SESSION["hotel_event_title"] ) && isset( $_SESSION["hotel_event_text"] ) )
			{
				?>
				<table cellspacing="0" cellpadding="0" align="center" style="width: 100%; vertical-align: top;"><tr><td style="width: 100%; border: solid 1px; background-color: #CC0000;"><div style="margin-left: 10px; margin-right: 5px; margin-top: 2px; margin-bottom: 2px;"><b>Event Title</b></div></td></tr>
				<tr><td style="width: 100%; border-right: solid 1px; border-left: solid 1px; border-bottom: solid 1px; background-color: #F3E681;"><div style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 10px;">Test</div></td></tr>
				</table>
				<?
			}
			?>
			<table style="width: 100%;">
			<tr>
				<td style="width: 45%; vertical-align: top;">
					<div class="title" style="background: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');"><div style="margin-left: 10px;">Hotel Information</div></div>
					<div class="content" style="background-color: #EBE6BC;">
						<b>Room Information</b><br />
						Room <?=$hotel["id"]*$char->getCharacterID();?><br />
						<?=$char->location;?> Hotel<br />
						<?=getHotelTypeString( $hotel["type"] );?> <? if ( $hotel["type"] == 0 ): print( "<a href='" . $rootdir . "/localcity/hotel.php?act=upgrade' style='text-decoration: none;' alt='U' title='Upgrade hotel room to " . getHotelTypeString( 1 ) . ".'><span style='vertical-align: top; font-size: 9px; color: green;'>^</span></a>" ); endif; ?><br />
						<b>$<? $cost = 75; if ( $hotel["type"] == 1 ): $cost = $cost+30; endif; print( $cost ); ?></b> per night<br /><br />

						<b>Bank Account</b>
						<table style="width: 100%;">
						<tr>
							<td style="width: 20%;">Acct. #: </td>
							<td style="width: 80%;"><input type="text" name="bank_id" class='std_input' value="<?=$bankacc["account_number"];?>" /></td>
						</tr>
						</table>
						<table style="width: 100%;"><tr><td style="width: 100%;"><input type="submit" class="std_input" name="changeaccount" value="Change Checking Account" /></td></tr></table>
					</div>
				</td>

				<td style="width: 5%; vertical-align: top;">&nbsp;</td>

				<td style="width: 45%; vertical-align: top;">
					<div class="title" style="background: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');"><div style="margin-left: 10px;">Room Closet</div></div>
					<div class="content" style="background-color: #EBE6BC;">

						<!-- Money Safe -->
						<table style="width: 100%;" cellspacing="0"><tr>
							<td colspan="2" style="text-align: center; border: 1px solid #383838; border-bottom: none; color: #fff; height: 20px; background-color: #606060; width: 100%; padding: 0px;">
								<strong>Money Safe</strong>
							</td>
						</tr><tr>
							<td style="width: 45%; border: solid 1px #383838; border-top: none; border-bottom: none; padding: 2px;"><strong>Money:</strong></td><td  style="border-right: solid 1px #383838; padding: 2px;" id="money_clean">$<?=$hotel["money_clean"];?> <a style="text-decoration: none; cursor: pointer;" onclick="#" alt="" title="Add money to safe..."><span style='vertical-align: top; font-size: 9px; color: green;'>+</span></a> <a style="text-decoration: none; cursor: pointer;" onclick="#" alt="" title="Take money from safe..."><span style='vertical-align: top; font-size: 9px; color: red;'>-</span></a></td>
						</tr><tr>
							<td style="width: 45%; border: solid 1px #383838; padding: 2px;"><strong>Dirty:</strong></td><td style="border: solid 1px #383838; border-left: none; padding: 2px;">$<?=$hotel["money_dirty"];?> <a style="text-decoration: none; cursor: pointer;" onclick="#" alt="" title="Add money to safe..."><span style='vertical-align: top; font-size: 9px; color: green;'>+</span></a> <a style="text-decoration: none; cursor: pointer;" onclick="#" alt="" title="Take money from safe..."><span style='vertical-align: top; font-size: 9px; color: red;'>-</span></a></td>
						</tr><tr>
							<td colspan="2" style="height: 15px;">&nbsp;</td>
						</tr></table>
					</div>
				</td>
			</tr>
			</table>
		</div>
	<?
	} 
	?>
</div>
</td></tr></table>

<? include_once "../includes/footer.php"; ?>
