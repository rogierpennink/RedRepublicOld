<?
include_once "utility.inc.php";

// INCLUDE FILES FROM DATABASE
$includeQuery = $db->query( "SELECT * FROM settings_include" );
while ( $includeAssoc = $db->fetch_assoc( $includeQuery ) )
{
	if ( file_exists( "/includes/" . $includeAssoc['filename'] ) )
		include_once "/includes/" . $includeAssoc['filename'];
	else
		include_once "../includes/" . $includeAssoc['filename'];
}

if ( $_GET['ajaxrequest'] == "show_debug" )
{
	$_SESSION['show_debug'] = ( $_SESSION['show_debug'] ) ? false : true;
}

if ( $_GET['ajaxrequest'] == "show_gov" )
{
	$_SESSION['show_gov'] = ( $_SESSION['show_gov'] ) ? false : true;
}

if ( $_GET['ajaxrequest'] == "show_news" )
{
	$_SESSION['show_news'] = ( $_SESSION['show_news'] ) ? false : true;
}

if ( $_GET['ajaxrequest'] == "show_parties" )
{
	$_SESSION["show_parties"] = ( $_SESSION["show_parties"] ) ? false : true;
}

$adm = strpos( $_SERVER['SCRIPT_NAME'], "/admin" );
$errpag = strpos( $_SERVER['SCRIPT_NAME'], "/errorpages" );
if ( $adm !== false || ( $errpag !== false && $_SESSION['inadmin'] ) ) $_SESSION['inadmin'] = true; else unset( $_SESSION['inadmin'] );

if ( user_auth() )
{
	// ADMINISTRATION DEBUG
	if ( getUserRights() == USER_SUPERADMIN && isset( $_POST["adminedit"] ) )
	{
		/* Character Debug */
		$db->query( "UPDATE char_characters SET money_clean=" . $db->prepval( $_POST["money_clean"] ) . ", money_dirty=" . $db->prepval( $_POST["money_dirty"] ) . ", location=" . $db->prepval( $_POST["location"] ) . " WHERE id=" . $db->prepval( $_POST["char_id"] ) );

		/* Debug Text Box */
		$db->query( "UPDATE settings_text SET value=" . $db->prepval( $_POST["debugbox"] ) . " WHERE setting='debugbox'" );
		
		/* Query */
		if ( strlen( $_POST["query"] ) > 0 ) $db->query( $_POST["query"] );

		/* Clear */
		unset( $_POST["adminedit"] );
	}

	/* If non-existant, create char_deeds entry for character */
	$deedr = $db->query( "SELECT * FROM char_deeds WHERE char_id=" . getCharacterID() );
	if ( $db->getRowCount( $deedr ) == 0 )
	{
		$db->query( "INSERT INTO char_deeds (char_id, slot_a, slot_b, slot_c, slot_d, slot_e, slot_f, slot_g, slot_h, slot_i, slot_j, slot_k, slot_l, slot_m, slot_n, slot_o, slot_p) VALUES (" . $db->prepval( getCharacterID() ) . ", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
	}

	/* Admin switching their rights to view different displays. */
	if ( ( getUserRights() == USER_SUPERADMIN || isset( $_SESSION["adminplayer"] ) ) && ( isset( $_GET['display'] ) && is_numeric( $_GET['display'] ) ) )
	{
		if ( $_GET['display'] == USER_SUPERADMIN )
		{
			setUserRights( "", USER_SUPERADMIN );
			unset( $_SESSION["adminplayer"] );
			$_SESSION["notify"] = "You're display has been updated to USER_SUPERADMIN.";
		}
		elseif ( $_GET["display"] == USER_DEFAULTMEMBER )
		{
			setUserRights( "", USER_DEFAULTMEMBER );
			$_SESSION["adminplayer"] = true;
			$_SESSION["notify"] = "You're display has been updated to USER_DEFAULTMEMBER.";
		}
	}

	/* Answering an event request */
	if ( isset( $_SESSION["ingame"] ) && isset( $_POST["eventrequest"] ) && $char->hasEventRequest() )
	{
		/* Process Event ... */
		$_SESSION["event"] = true;
		include_once "handlers/" . $_POST["handler"];
		unset( $_SESSION["event"] );
		header( "location: " . $rootdir . "/events/index.php" ); // Keep refresh from answering.
		exit;
	}

	/* Switching to Debug Mode */
	if ( isset( $_GET["debugmode"] ) && $_GET["debugmode"] == "true" )
		$_SESSION["debugmode"] = true;
	elseif ( isset( $_GET["debugmode"] ) && $_GET["debugmode"] == "false" )
		unset( $_SESSION["debugmode"] );

	/* Traveling */
	if ( isset( $_SESSION["ingame"] ) )
	{
		$ticketchk = $db->query( "SELECT * FROM travel_flights_tickets WHERE char_id=" . $char->getCharacterID() );
		
		if ( $db->getRowCount( $ticketchk ) > 0 )
		{
			$ticket = $db->fetch_array( $ticketchk );
			$flight_query = $db->query( "SELECT * FROM travel_flights WHERE `id`=" . $ticket["flight_id"] );
			
			if ( $db->getRowCount( $flight_query ) == 0 )
			{
				/* Flight Ended & Missed */
				$char->addEvent( "Missed Flight", "You missed your flight to " . getLocationInfo( $ticket["to"], "location_name" ) . ", refunds are not possible at this point." );
				$db->query( "DELETE FROM travel_flights_tickets WHERE char_id=" . $char->getCharacterID() );
			}
			else
			{
				$flight = $db->fetch_array( $flight_query );
				if ( $flight["flightend"] < time() && $ticket["done"] != "true" )
				{
					/* Flight Ended */
					$char->setLocation( $flight["to"] );
					$db->query( "UPDATE travel_flights_tickets SET done='true' WHERE char_id=" . $char->getCharacterID() );
					header( "location: " . $rootdir . "/localcity/index.php" );
				}
				elseif ( $flight["flightstart"] < time() && $flight["flightend"] > time() )
				{
					$char->addLargeEvent( "Traveling", "You are currently traveling to " . getLocationInfo( $flight["to"], "location_name" ) . " from " . getLocationInfo( $flight["from"], "location_name" ) . ". You are expected to arrive at " . date( "g:i A", $flight["flightend"] ) . ". Currently the time is " . date( "g:i A", time() ) . "." );
				}
			}
		}

		$travelchk = $db->query( "SELECT * FROM char_vehicles WHERE `traveling`='true' AND `id`=" . $char->getCharacterID() );

		if ( $db->getRowCount( $travelchk ) > 0 )
		{
			$row = $db->fetch_array( $travelchk );

			if ( time() < $row["end"] )
			{
				$char->addLargeEvent( "Traveling", "You are currently traveling to " . getLocationInfo( $row["to"], "location_name" ) . " from " . getLocationInfo( $row["from"], "location_name" ) . ". You are expected to arrive at " . date( "g:i A", $row["end"] ) . ". Currently the time is " . date( "g:i A", time() ) . "." );
			}
			else
			{
				$db->query( "UPDATE char_vehicles SET `traveling`='false' WHERE `id`=" . getCharacterID() );
				$char->setLocation( $row["to"] );
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	
	<title>Red Republic</title>

	<link rel="stylesheet" type="text/css" href="<?=$rootdir;?>/stylesheets/general_style.css" />
	<?
	if ( isset( $ext_style ) && $ext_style != "" )
	{
		$file = explode( "::", $ext_style );
		foreach ( $file as $value )
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $rootdir . "/stylesheets/" . trim( $value ) ."\" />\n";
	}
	?>
	<script type="text/javascript" src="<?=$rootdir;?>/javascript/general_js.js"></script>
	<script type="text/javascript" src="<?=$rootdir;?>/javascript/xmlhttprequest.js"></script>

</head>

<body id="body" style="margin-left: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px;"
<? 
if ( isset( $err ) && $err->is_error() ) { ?>onload="openErrorDisplay();"<? } 
elseif ( isset( $nav ) && $nav == "events" && $char->hasEventRequest() ) { ?>onload="openEventRequest();"<? } ?>
>
<div style="position: relative; min-height: 100%;">
	
	<div id="progressIndicator" class="progressIndicator" style="visibility: hidden;">
		<strong>Loading Content</strong>&nbsp;&nbsp;&nbsp;<img src="images/loading.gif" alt="Loading..." />
	</div>

	<?
	if ( isset( $_SESSION['ingame'] ) ) 
	{ 
		if ( !isset( $char ) ) $char = new Player( getUserID() );

		// Check for large events
		$l_event_q = $db->query( "SELECT * FROM events_large WHERE char_id=". $char->character_id ." AND ( event_new=1 OR death_message=1 ) ORDER BY death_message ASC" );
		$num = $db->getRowCount( $l_event_q );

		/* Check for promotions or crossroads... A crossroad occurs when there are two promotions in the database. */
		$promoquery = $db->query( "SELECT * FROM char_promos WHERE status=2 AND char_id=". $char->character_id );
		$numpromos = $db->getRowCount( $promoquery );
		?>
		<div id="glasspane" class="glasspane" <?=($num > 0 || $numpromos > 0)?"style='visibility: visible;'":"";?>></div>

		<?
		if ( $num > 0 )
		{
			$levent = $db->fetch_array( $l_event_q );
			$db->query( "UPDATE events_large SET event_new=0 WHERE levent_id=". $levent['levent_id'] );
			?>
			<div class="usercontainer" style="visibility: visible;">
				<div class="title" style="padding-left: 10px;">
					<?=$levent['subject'];?>			
				</div>
				<div class="content" style="text-align: center; overflow: auto;">
					<?=$levent['content'];?><br /><br />
					<a href="<?=$PHP_SELF;?>">Continue</a>
				</div>
			</div>
			<?
		}
		?>
		
		<!-- Users Online Container -->
		<div id="usercontainer" class="usercontainer">
			<div class="title">
				<img src="<?=$rootdir;?>/images/icon_close.gif" alt="close" title="Close User List" style="float: right; margin: 2px; cursor: pointer;" onclick="closeUserList();" />
				<img src="<?=$rootdir;?>/images/icon_users.png" alt="" style="margin-left: 2px; margin-right: 5px;" />Users Online
			</div>
			<div class="content" id="userlist" style="text-align: center; overflow: auto;">
			</div>
		</div>
		<!-- End Users Online Container -->

		<div id="inventory" class="usercontainer"></div>
		<div id="charinfo" class="usercontainer"></div>

		<?		
		if ( $numpromos > 0 )
		{			
			?><div id="promoscreen" class="usercontainer" style="z-index: 4567; visibility: visible;">
			<div class="title" style="padding-left: 5px;">
				Promotion
			</div>
			<div class="content"><?
			if ( $numpromos > 1 )
				include_once "../promotions/crossroad.php";
			else
			{
				$promo = $db->fetch_array( $promoquery );
				$rank = $db->getRowsFor( "SELECT * FROM char_ranks WHERE id=". $promo['next_rank'] );
				if ( !file_exists( "../promotions/". $rank['promo_page'] ) )
					include_once "promotions/". $rank['promo_page'];
				else
					include_once "../promotions/". $rank['promo_page'];
			}
			?></div></div><?
		}
		?>

		<? if ( isset( $_GET["act"] ) && $_GET["act"] == "bar" ) { ?>
			<!-- Beer Info Container -->
			<div id="beerinfo" class="usercontainer">
				<div style="width: 400px;">
					<div class="title">
						<img src="<?=$rootdir;?>/images/icon_close.gif" alt="close" title="Cancel Order" style="float: right; margin: 2px; cursor: pointer;" onclick="closeBeer();" />
						<img src="<?=$rootdir;?>/images/icon_read.png" alt="" style="margin-left: 2px; margin-right: 5px;" />Purchase Beer
					</div>
					<div class="content" style="text-align: center; overflow: auto;">
						The barkeep informs you that it'll cost $5 a beer. You should also note that drinking is a vice, and will have effects on your health and well being, still want to get that beer?<br /><br />
						<center><b><a href='<?=$rootdir;?>/localcity/tavern.php?act=bar&baract=beer' style='text-decoration: none;' onclick='closeBeer();'>Yes</a></b> | <b><a onclick="closeBeer();" alt='' style='text-decoration: none; cursor: pointer;'>Nevermind</a></b></center>
					</div>
				</div>
			</div>
			<!-- End Beer Info Container -->
		<? } ?>

		<?
		if ( $char->hasEventRequest() )
		{
			$event = $char->getEventRequest( $char->getLastRequest() );
			?>
			<div id="eventrequest" class="usercontainer">
				<div style="width: 500px;">
					<div class="title">
						<img src="<?=$rootdir;?>/images/icon_close.gif" alt="close" title="Close" style="margin: 2px; float: right; cursor: pointer;" onclick="closeEventRequest();" />
						<img src="<?=$rootdir;?>/images/icon_read.png" alt="" style="margin-left: 2px; margin-right: 5px;" />Event Request
					</div>
					<div class="content" style="overflow: auto;">
						<!-- Create Pre-Form -->
						<form method='post' action='<?=$PHP_SELF;?>'>
							<input type='hidden' name='eventrequest' value='true' />
							<input type='hidden' name='id' value='<?=$event["ID"];?>' />
							<input type='hidden' name='handler' value='<?=$event["Handler"];?>' />
							<table style="width: 100%;">
								<tr>
									<td>&nbsp;</td>
									<td align="center"><b><?=$event["Name"];?></b></td>
								</tr>
								<tr>
									<td style="width: 20px;">&nbsp;</td>
									<td><?=$event["Message"];?></td>
								</tr>
							</table>
						</form>
						<!-- End Form -->
					</div>
				</div>
			</div>
			<?
		}
		
		if ( getUserRights() >= USER_SUPERADMIN ) { ?>
			<!-- Display Mode Warning (Admin) -->
			<div id="warndisplay" class="usercontainer">
				<div style="width: 400px;">
					<div class="title">
						<img src="<?=$rootdir;?>/images/icon_close.gif" alt="close" title="Cancel" style="float: right; margin: 2px; cursor: pointer;" onclick="closeWarnDisplay();" />
						<img src="<?=$rootdir;?>/images/icon_read_red.png" alt="" style="margin-left: 2px; margin-right: 5px;" />Warning
					</div>
					<div class="content" style="text-align: center; overflow: auto;">
						<table>
							<tr>
								<td style="vertical-align: top;"><img src="<?=$rootdir;?>/images/warning.png" alt="!WARNING!" title="Warning" /></td>
								<td align="left">
									If you close your browser, or end your RR sessions while in the Player display, you're account will retain the user rights of 0 (default member), and you will be required to set your user rights back to 10 manually. However, when you are done with the Player display, and wish to switch back to administrative mode, simply look for the <i>Admin Display</i> link in the footer bar.<br /><br />
									<center>
										<a href='<?=$PHP_SELF;?>?display=0' onclick='closeWarnDisplay();' style='text-decoration: none;'>Understood</a> | 
										<a style='text-decoration: none; cursor: pointer;' onclick='closeWarnDisplay();'>Nevermind</a>
									</center>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<!-- End Display Mode Warning (Admin) -->

			<!-- Error Display -->
			<? if ( isset( $err ) && $err->is_error() ) { ?>
				<div id="errordisplayex" class="usercontainer">
					<div style="width: 400px;">
						<div class="title">
							<img src="<?=$rootdir;?>/images/icon_close.gif" alt="close" title="Cancel" style="float: right; margin: 2px; cursor: pointer;" onclick="closeErrorDisplay();" />
							<img src="<?=$rootdir;?>/images/icon_read_red.png" alt="" style="margin-left: 2px; margin-right: 5px;" />Error
						</div>
						<div class="content" style="text-align: center; overflow: auto;">
							<table>
								<tr>
									<td style="vertical-align: top;"><img src="<?=$rootdir;?>/images/warning.png" alt="!ERROR!" title="Error" /></td>
									<td align="left">
										<? if ( $err->is_error() ) { ?>
											An error has occurred in Red Republic. The following information was added:<br /><br /><font style="font-size: 11px;"><?=$err->getError();?></font>
										<? } ?>
										<br /><br />
										<center>
											<a style='text-decoration: none; cursor: pointer;' onclick='closeErrorDisplay();'><img border='0' src='<?=$rootdir;?>/images/icon_delete.gif' /> Close Dialog</a>
										</center>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			<? } ?>
			<!-- End Error Display -->

		<?
		} // end if ( user >= superadmin )
		?>

		<script type="text/javascript">loadInventory( 'inventory', '' ); loadCharacterInfo();</script>
		
	<?
	} // end if ( session ingame is set )
	?>

	<!-- THE MAIN CONTENT DIV, NOTHING, EXCEPT TRANSPARENT MESSAGEBOXES FALL OUTSIDE THIS DIV -->
	<div class="maindiv">

		<div class="headerbar"><? if ( isset( $_SESSION["ingame"] ) ): ?><a href='<?=$rootdir;?>/main.php'><? endif; ?><img border='0' src="<?=$rootdir;?>/images/logo.png" alt="Red Republic" /><? if ( isset( $_SESSION["ingame"] ) ): ?></a><? endif; ?></div>

		<div class="gradientbar">

			<div class="linkbarbutton" onclick="window.location = '<?=$rootdir;?>/index.php';">News</div>

			<? if ( !user_auth() ) { ?>
			<div class="linkbarbutton" onclick="window.location = '<?=$rootdir;?>/login.php';">Login</div>

			<div class="linkbarbutton" onclick="window.location = '<?=$rootdir;?>/register.php';">Register</div>

			<? } if ( user_auth() ) { ?>

			<div class="linkbarbutton" onclick="window.location = '<?=$rootdir;?>/account.php';">Account</div>
			
			<? if ( !isset($_SESSION['ingame']) ) { ?>
			
			<div class="linkbarbutton" onclick="window.location = '<?=$rootdir;?>/main.php';">Play</div>

			<? } ?>

			<div class="linkbarbutton" onclick="window.location = '<?=$rootdir;?>/logout.php';">Logout</div>

			<? } ?>

			<div class="linkbarbutton" onclick="window.location = '<?=$rootdir;?>/about.php';">About</div>

			<div class="linkbarbutton" onclick="window.location = '<?=$rootdir;?>/support.php';">Support</div>

			<div class="linkbarbutton">Donate</div>

			<div class="linkbarbutton" onclick="window.location = '<?=$rootdir;?>/forums/';">Forums</div>	
			
			<? if ( user_auth() && getUserRights() >= USER_MODERATOR ) { ?>

			<div class="linkbarbutton" onclick="window.location = '<?=$rootdir;?>/admin/';">Admins</div>

			<? } if ( user_auth() && getUserRights() == USER_SUPPORT ) { ?>

			<div class="linkbarbutton" onclick="window.location = '<?=$rootdir;?>/admin/supportmanagement.php';">Admins</div>
			
			<? } ?>

			<div align="right" style="margin-right: 5px;"><?=date( "g:i A" );?></div>
		</div>

		<!-- THE CONTENT DIV, THIS IS DIRECTLY POSITIONED UNDER THE HEADER!!! -->
		<div style="position: relative; min-height: 85%;">
			
			<!-- THE LEFT NAVIGATION, INSIDE CONTENT AND LEFT OF CONTENT_CONTENT -->
			<div class="leftnavigation" style="padding-bottom: 10px;">

				<? if ( !user_auth() ) { ?>
				<!-- QUICK LOGIN WINDOW -->
				<div class="title" style="margin-top: 20px; margin-left: 10px; margin-right: 10px; padding-left: 10px;">
					Quick Login
				</div>
				<div class="navcontent">
					<p>Use this form to quickly log-in. For advanced options, use <a href="<?=$rootdir;?>/login.php">this login</a>.</p>
					
						<form action="<?=$PHP_SELF;?>" method="post" onsubmit="login( document.getElementById('login_username').value, document.getElementById('login_password').value, true ); return false;">
						<p>
						<input type="text" id="login_username" class="std_input" <? if ( !isset( $_COOKIE['redrepublic']['username'] ) ) { ?>value="Username" onclick="this.value = ''; this.onclick = null;" <? } else { ?>value="<?=$_COOKIE['redrepublic']['username'];?>" <? } ?> /><br />
						<input type="password" id="login_password" class="std_input" <? if ( !isset( $_COOKIE['redrepublic']['password'] ) ) { ?>value="Password" onclick="this.value = ''; this.onclick = null;" <? } else { ?>value="<?=$_COOKIE['redrepublic']['password'];?>" <? } ?> /><br /><br />
						<input type="submit" id="login_submit" class="std_input" value="Log In" />
						</p>
						</form>
					
				</div>
				<? } ?>
				
				<?
				// Only show this if we are NOT in the game itself
				if ( !$_SESSION['ingame'] && !$_SESSION['inadmin'] ) {
				?>
				<!-- RECENT NEWS ITEMS WINDOW -->
				<div class="title" style="margin-top: 20px; margin-left: 10px; margin-right: 10px; padding-left: 10px;">
					Recent Newsitems
				</div>
				<div class="navcontent">
					<?
						// Use the getsitenews function to construct links to sitenews
						$news = getSiteNews();

						for ( $i = 0; $i < count( $news ); $i++ )
						{
							$arr = $news[$i];

							if ( strlen( $arr['subject'] ) > 25 )
								$subj = substr( $arr['subject'], 0, 25 ) . "...";
							else
								$subj = $arr['subject'];

							echo "<a href=\"".$rootdir."/news.php?id=". $arr['id'] ."\">" . $subj . "</a><br />";
						}
					?>
				</div>

				<!-- RECENT FORUM TOPICS WINDOW -->
				<div class="title" style="margin-top: 20px; margin-left: 10px; margin-right: 10px; padding-left: 10px;">
					Recent Forumposts
				</div>
				<div class="navcontent">
					<?
						$rights = ( getUserRights() === false ) ? 0 : getUserRights();
						// Query for topics ordered by last activity - filter out admin posts unless the user is an admin.
						$rec = $db->query( "SELECT forums_topics.id, forums_topics.name FROM forums_topics INNER JOIN forums_forums ON forums_topics.f_id = forums_forums.id WHERE forums_forums.auth_level <= $rights ORDER BY last_activity DESC LIMIT 0,5" );
						
						while ( $res = $db->fetch_array( $rec ) )
						{
							if ( strlen( $res['name'] ) > 25 )
								$subj = substr( $res['name'], 0, 25 ) . "...";
							else
								$subj = $res['name'];

							echo "<a href=\"".$rootdir."/forums/viewtopic.php?id=". $res['id'] ."\">" . stripslashes( $subj ) . "</a><br />";
						}
					?>
				</div>
				<?
				} elseif ( $_SESSION['inadmin'] ) {
				/**
				 * THIS IS THE INADMIN STATISTICS + NAV SCREEN!
				 */
				?>
				<div class="title" style="margin: 10px; margin-bottom: 0px;">
					<div style="margin-left: 10px;">Admin Navigation</div>
				</div>
				<div class="content" style="background-color: #fff686; padding: 0px; border: none; margin-left: 10px; margin-right: 10px;">
					<div style="cursor: pointer;<?=($nav == "admin")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "admin" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/admin/';">						
						<table>
						<tr>
						<td style="width: 40px;">
						<img src="<?=$rootdir;?>/images/forums_new.png" alt="Admin Index" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/admin/">Administration Index</a>
						</td>
						</tr>
						</table>
					</div>
					<div style="cursor: pointer;<?=($nav == "forumman")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "forumman" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/admin/forumsmanager.php';">						
						<table>
						<tr>
						<td style="width: 40px;">
						<img src="<?=$rootdir;?>/images/forums_old.png" alt="Forums Management" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/admin/forumsmanager.php">Forums Management</a>
						</td>
						</tr>
						</table>
					</div>

					<div style="cursor: pointer;<?=($nav == "userman")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "userman" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/admin/usermanager.php';">
						<table>
						<tr>
						<td style="width: 40px;">
						<img src="<?=$rootdir;?>/images/forums_new.png" alt="User Management" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/admin/usermanager.php">User Management</a>
						</td>
						</tr>
						</table>
					</div>

					<div style="cursor: pointer;<?=($nav == "newsman")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "newsman" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/admin/newsmanagement.php';">						
						<table>
						<tr>
						<td style="width: 40px;">
						<img src="<?=$rootdir;?>/images/forums_old.png" alt="News Management" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/admin/newsmanagement.php">Sitenews Management</a>
						</td>
						</tr>
						</table>
					</div>

					<div style="cursor: pointer;<?=($nav == "businessman")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "businessman" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/admin/businessmanagement.php';">						
						<table>
						<tr>
						<td style="width: 40px;">
						<img src="<?=$rootdir;?>/images/forums_new.png" alt="Business Management" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/admin/businessmanagement.php">Business Management</a>
						</td>
						</tr>
						</table>
					</div>
					<div style="cursor: pointer;<?=($nav == "configman")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "configman" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/admin/config.php';">						
						<table>
						<tr>
						<td style="width: 40px;">
						<img src="<?=$rootdir;?>/images/forums_old.png" alt="Game Configuration" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/admin/config.php">Game Configuration</a>
						</td>
						</tr>
						</table>
					</div>
					<div style="cursor: pointer;<?=($nav == "itemman")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "itemman" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/admin/itemmanagement.php';">						
						<table>
						<tr>
						<td style="width: 40px;">
						<img src="<?=$rootdir;?>/images/forums_new.png" alt="Item Management" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/admin/itemmanagement.php">Item Management</a>
						</td>
						</tr>
						</table>
					</div>
					<div style="cursor: pointer;<?=($nav == "pollman")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "pollman" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/admin/pollmanagement.php';">						
						<table>
						<tr>
						<td style="width: 40px;">
						<img src="<?=$rootdir;?>/images/forums_old.png" alt="Poll Management" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/admin/pollmanagement.php">Poll Management</a>
						</td>
						</tr>
						</table>
					</div>
					<div style="cursor: pointer;<?=($nav == "faqman")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "faqman" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/admin/supportmanagement.php';">						
						<table>
						<tr>
						<td style="width: 40px;">
						<img src="<?=$rootdir;?>/images/forums_new.png" alt="Support Management" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/admin/supportmanagement.php">Support Management</a>
						</td>
						</tr>
						</table>
					</div>
					<div style="cursor: pointer;<?=($nav == "deedman")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "deedman" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/admin/deedmanagement.php';">						
						<table>
						<tr>
						<td style="width: 40px;">
						<img src="<?=$rootdir;?>/images/forums_old.png" alt="Support Management" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/admin/deedmanagement.php">Vices and Virtues</a>
						</td>
						</tr>
						</table>
					</div>
					<!-- <?=$rootdir;?>/admin/deedmanagement.php -->
				</div>

				<div class="title" style="margin: 10px; margin-bottom: 0px;">
					<div style="margin-left: 10px;">Game Statistics</div>
				</div>
				<div class="content" style="background-color: #fff686; padding: 0px; border: none; margin-left: 10px; margin-right: 10px;">
				<?
					/* Query many things... */
					$numcategories = $db->getRowCount( $db->query( "SELECT * FROM forums_categories" ) );
					$numforums = $db->getRowCount( $db->query( "SELECT * FROM forums_forums" ) );
					$numtopics = $db->getRowCount( $db->query( "SELECT * FROM forums_topics" ) );
					$numreplies = $db->getRowCount( $db->query( "SELECT * FROM forums_replies" ) ) - $numtopics;

					$numplayers = $db->getRowCount( $db->query( "SELECT * FROM char_characters" ) );
					$totalcmoney = $db->fetch_array( $db->query( "SELECT SUM(money_clean) FROM char_characters;" ) );
					$totaldmoney = $db->fetch_array( $db->query( "SELECT SUM(money_dirty) FROM char_characters;" ) );
					$totalmoney = $totalcmoney[0] + $totaldmoney[0];
				?>
					<div style="padding: 10px;" onmouseover="javascript: this.style.backgroundColor = '#fee676';" onmouseout="javascript: this.style.backgroundColor = 'transparent';">
						<strong>Forum Statistics</strong>
						<table style="margin-left: 10px; width: 100%; border-spacing: 0px;">
						<tr>
							<td style="width: 65%;"># Categories:</td>
							<td style="width: 35%;"><?=$numcategories;?></td>
						</tr>
						<tr>
							<td style="width: 65%;"># Forums:</td>
							<td style="width: 35%;"><?=$numforums;?></td>
						</tr>
						<tr>
							<td style="width: 65%;"># Topics:</td>
							<td style="width: 35%;"><?=$numtopics;?></td>
						</tr>
						<tr>
							<td style="width: 65%;"># Replies:</td>
							<td style="width: 35%;"><?=$numreplies;?></td>
						</tr>
						</table>
					</div>

					<div style="padding: 10px;" onmouseover="javascript: this.style.backgroundColor = '#fee676';" onmouseout="javascript: this.style.backgroundColor = 'transparent';">
						<strong>Player Statistics</strong>
						<table style="margin-left: 10px; width: 100%; border-spacing: 0px;">
						<tr>
							<td style="width: 65%;"># Players:</td>
							<td style="width: 35%;"><?=$numplayers;?></td>
						</tr>
						<tr>
							<td style="width: 65%;">Clean money:</td>
							<td style="width: 35%;">$<?=$totalcmoney[0];?></td>
						</tr>
						<tr>
							<td style="width: 65%;">Dirty money:</td>
							<td style="width: 35%;">$<?=$totaldmoney[0];?></td>
						</tr>
						<tr>
							<td style="width: 65%;">Total money:</td>
							<td style="width: 35%;">$<?=$totalmoney;?></td>
						</tr>
						</table>
					</div>
					
				</div>
				<?
				} else {
				/**
				 * THIS IS THE INGAME MENU!
				 */
				?>
				<div class="title" style="margin: 10px; margin-bottom: 0px;">
					<div style="margin-left: 10px;">Game Navigation</div>
				</div>
				<div class="content" style="background-color: #fff686; padding: 0px; border: none; margin-left: 10px; margin-right: 10px;">

					<div style="cursor: pointer;<?=($nav == "comms")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "comms" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/comms/';">
						<?
						$flicker = $db->getRowCount( $db->query( "SELECT * FROM comms WHERE `to`=". $char->character_id ." AND `comm_new`=1" ) );
						?>
						<table>
						<tr>
						<td style="width: 45px;">
						<img src="<?=$rootdir;?>/images/nav/<?=($flicker==0) ? "nav_comms.png" : "nav_comms.gif";?>" style="width: 40px; height: 40px;" alt="Comms" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/comms/">Communications</a>
						</td>
						</tr>
						</table>
					</div>

					<div style="cursor: pointer;<?=($nav == "events")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "events" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/events/';">
						<?
						$flicker = $db->getRowCount( $db->query( "SELECT * FROM events WHERE `char_id`=". $char->character_id ." AND `event_new`=1" ) );
						?>
						<table>
						<tr>
						<td style="width: 45px;">
						<img src="<?=$rootdir;?>/images/nav/<?=($flicker==0) ? "nav_events.png" : "nav_events.gif";?>" style="width: 40px; height: 40px;" alt="Events" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/events/">Event Report</a>
						</td>
						</tr>
						</table>
					</div>

					<div style="cursor: pointer;<?=($nav == "income")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "income" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/income/';">
						<table>
						<tr>
						<td style="width: 45px;">
						<img src="<?=$rootdir;?>/images/nav/nav_income.png" style="width: 40px; height: 40px;" alt="Income" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/income/">Income</a>
						</td>
						</tr>
						</table>
					</div>

					<? if ( $char->hasHotelRoom( $char->location_nr ) ) { ?>
					<div style="cursor: pointer;<?=($nav == "hotel")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "hotel" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/localcity/hotel.php';">
						<table>
						<tr>
						<td style="width: 45px;">
						<img src="<?=$rootdir;?>/images/nav/nav_hotel.png" style="width: 40px; height: 40px;" alt="Go to work" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/localcity/hotel.php">Hotel</a>
						</td>
						</tr>
						</table>
					</div>
					<? } ?>

					<? if ( strlen( $char->work_url ) > 3 ) { ?>
					<div style="cursor: pointer;<?=($nav == "work")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "work" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/<?=$char->work_url;?>';">
						<table>
						<tr>
						<td style="width: 45px;">
						<img src="<?=$rootdir;?>/images/nav/nav_work.png" style="width: 40px; height: 40px;" alt="Go to work" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/<?=$char->work_url;?>">Go to work</a>
						</td>
						</tr>
						</table>
					</div>
					<? } ?>

					<div style="cursor: pointer;<?=($nav == "localcity")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "localcity" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/localcity/';">
						<table>
						<tr>
						<td style="width: 45px;">
						<img src="<?=$rootdir;?>/images/nav/nav_localcity.png" style="width: 40px; height: 40px;" alt="Localcity" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/localcity/">Visit <?=$char->location;?></a>
						</td>
						</tr>
						</table>
					</div>

					<div style="cursor: pointer;<?=($nav == "travel")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "travel" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/travel/';">
						<table>
						<tr>
						<td style="width: 45px;">
						<img src="<?=$rootdir;?>/images/nav/nav_travel.png" style="width: 40px; height: 40px;" alt="Travel" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/travel/">Travelling</a>
						</td>
						</tr>
						</table>
					</div>

					<div style="cursor: pointer;<?=($nav == "conflict")?" background-color: #fee676;":"";?>" onmouseover="javascript: this.style.backgroundColor = '#fee676';" <?if ( $nav != "conflict" ) {?>onmouseout="javascript: this.style.backgroundColor = 'transparent';"<? } ?> onclick="window.location='<?=$rootdir;?>/conflict/';">
						<table>
						<tr>
						<td style="width: 45px;">
						<img src="<?=$rootdir;?>/images/nav/nav_conflict.png" style="width: 40px; height: 40px;" alt="Conflict" />
						</td>
						<td style="vertical-align: middle;">
						<a href="<?=$rootdir;?>/conflict/">Conflict</a>
						</td>
						</tr>
						</table>
					</div>
						
				</div>

				<!-- THE WEATHER INFORMATION DISPLAY -->
				<div class="title" style="margin: 10px; margin-bottom: 0px;">
					<div style="margin-left: 10px;"><?=$char->location;?> Local Weather</div>
				</div>
				<div class="content" style="background-color: #fff686; padding: 0px; border: none; margin-left: 10px; margin-right: 10px;">
					<?
					/* Weather Query (direct array) */
					$weather = $db->getRowsFor( "SELECT * FROM locations WHERE id=" . $char->location_nr );

					/* Get Weather Icon */
					?>
					<table style="width: 100%;">
						<tr>
							<td style="width: 60px; height: 50px; text-align: center;" rowspan="2">
								<img src="<?=$rootdir;?>/images/weather/partial_sun_trans.png" alt="Partially Sunny" />
							</td>
							<td align="right">
								Temperature:
							</td>
							<td>
								<?
								/* "General" Temperature */
								$diff = $weather['max_temp']-$weather['min_temp'];
								$temperature = floor( $weather['min_temp']+($diff/2) );
								
								/* Temperature Colors
								 * Displays colors for cold, warm, and hot temperatures.
								 */
								if ( $temperature <= 40 )
									$tempcol = "#004182"; // Cold
								elseif ( $temperature >= 41 && $temperature <= 50 )
									$tempcol = "#9B4D17"; // Warm
								elseif ( $temperature >= 51 )
									$tempcol = "#93001E"; // Hot
								?>
								<font color="<?=$tempcol;?>"><?=$temperature;?></font>&deg;C
							</td>
						</tr>
						<tr>
							<td align="right">
								Wind Speed:
							</td>
							<td>
								<!-- KPH or MPH? -->
								<?=$weather['avg_wind_speed'];?><font style="font-size: 7px;">KPH</font>
							</td>
						</tr>
					</table>
				</div>
				<?
				if ( getUserRights() == USER_SUPERADMIN )
				{
				?>
					<!-- THIS IS THE DEVELOPER MENU -->
					<div class="title" style="margin: 10px; margin-bottom: 0px;">
						<? 
						$imgsrc = ( $_SESSION['show_debug'] ) ? $rootdir . "/images/icon_minimize.gif" : $rootdir . "/images/icon_expand.gif";
						$alt = ( $_SESSION['show_debug'] ) ? "expanded" : "minimized";
						$display = ( $_SESSION['show_debug'] ) ? "block" : "none";
						?>
						<div style="float: right;"><img src="<?=$imgsrc;?>" alt="<?=$alt;?>" style="margin-right: 5px; cursor: pointer;" onclick="setDebugView( this );" /></div>
						<div style="margin-left: 10px;">Quick Debug</div>
					</div>
					<div class="content" id="debug_content" style="background-color: #fff686; padding: 0px; border: none; margin-left: 10px; margin-right: 10px; padding: 10px; display: <?=$display;?>;">					
						<form method="post" action="<?=$PHP_SELF;?>">
						<table>
						<?
						$debugQuery = $db->query( "SELECT id FROM char_characters WHERE account_id=" . getUserID() );
						$debugAssoc = $db->fetch_assoc($debugQuery);
						?>
						<tr>
							<td style="width: 60%;"><strong>CharID:</strong></td><td><input type='text' style='font-family: verdana; font-size: 10px;' size='6' name='char_id' value='<?=$debugAssoc['id'];?>' /></td>
						</tr><tr>
							<td style="width: 60%;"><strong>CMoney:</strong></td><td><input type='text' style='font-family: verdana; font-size: 10px;' size='6' name='money_clean' value='<?=$char->money_clean;?>' /></td>
						</tr><tr>
							<td style="width: 60%;"><strong>DMoney:</strong></td><td><input type='text' style='font-family: verdana; font-size: 10px;' size='6' name='money_dirty' value='<?=$char->money_dirty;?>' /></td>
						</tr><tr>
							<td style="width: 45%;"><strong>Loc:</strong></td><td><input type='text' style='font-family: verdana; font-size: 10px;' size='6' name='location' value='<?=$char->location_nr;?>' /></td>
						</tr><tr>
							<td style="width: 45%;"><strong>Query:</strong></td><td><input type='text' style='font-family: verdana; font-size: 10px;' size='6' name='query' value='' /></td>
						</tr>
						</table>
						<table>
							<tr>
								<td style="width: 95%;">
								<?
								// Note Box (debugbox in settings_text)
								$debugboxQuery = $db->query( "SELECT value FROM settings_text WHERE setting='debugbox'" );
								$debugboxAssoc = $db->fetch_assoc( $debugboxQuery );
								?>
								<textarea name='debugbox' style="font-family: verdana; font-size: 10px;" cols="22" rows="2"><?=$debugboxAssoc['value'];?></textarea>
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td style="width: 45%;">
								<input type='submit' name='adminedit' style='font-family: verdana; font-size: 10px;' value='Edit' />
								</td>
								<td style="vertical-align: left;">
								<input type='reset' name='adminreset' style='font-family: verdana; font-size: 10px;' value='Reset' />
								</td>
							</tr>
						</table>
						</form>
					</div>
					<? } ?>
				<? } ?>

			</div>

			<!-- THE RIGHT CONTENT AREA, THIS WILL CONTAIN POLLS ETC. -->
			<? if ( isset( $_SESSION['ingame'] ) && user_auth() ) { ?>	
			<div class="rightcontent">
				<div class="title" style="background: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); text-align: right; margin: 10px;">	
				
				<?
				/* Event Request? */
				if ( $char->hasEventRequest() )
				{
					?>
					<img src="<?=$rootdir;?>/images/icon_read_red_anim.gif" alt="I" title="You have an event request." onclick="window.location='<?=$rootdir;?>/events/index.php';" style="margin-top: 2px; cursor: pointer;" />
					<?
				}

				/* Mailbox Animation or Not */
				$mailboxquery = $db->query( "SELECT * FROM char_mailbox WHERE char_id=". $char->character_id ." AND has_arrived=1" );
				if ( $db->getRowCount( $mailboxquery ) == 0 )
				{
					?>
					<img src="<?=$rootdir;?>/images/icon_mailbox.png" alt="I" title="Go to your mailbox" onclick="window.location='<?=$rootdir;?>/mailbox.php';" style="margin-top: 2px; cursor: pointer;" />
					<?
				}
				else
				{
					?>
					<img src="<?=$rootdir;?>/images/icon_mailbox_anim.gif" alt="I" title="You've got mail." onclick="window.location='<?=$rootdir;?>/mailbox.php';" style="margin-top: 2px; cursor: pointer;" />
					<?
				}
				?>

				<img src="<?=$rootdir;?>/images/charstats.png" alt="I" title="Show Character Information" onclick="showCharacter();" style="margin-top: 2px; cursor: pointer;" />
				<img src="<?=$rootdir;?>/images/icon_inventory.gif" alt="I" title="Show Inventory Listing" onclick="showInventory();" style="margin-top: 2px; cursor: pointer;" />
				<img src="<?=$rootdir;?>/images/icon_users.png" alt="U" title="Show User List" onclick="showUserList();" style="margin-top: 2px; cursor: pointer;" />
				</div>

				<div class="title" style="margin: 10px; margin-bottom: 0px;">
					<div style="margin-left: 10px;">Avatar</div>
				</div>
				<div class="content" style="border: none; margin-left: 10px; margin-right: 10px; background-color: #fff686;">
					<img src="<?=$rootdir;?>/images/<?
					if ( strlen( $char->avatar_url ) == 0 ) { print("cs_avatar150x150.png"); } else { print( $char->avatar_url ); }
					?>" alt="Avatar" style="margin-left: 5px;" />
				</div>

				<div class="title" style="margin: 10px; margin-bottom: 0px;">
					<div style="margin-left: 10px;">Character Information</div>
				</div>
				<div class="content" style="border: none; margin-left: 10px; margin-right: 10px; background-color: #fff686;">

				<table style="width: 100%;" cellspacing="0">
				<tr>
					<td style="width: 45%;"><strong>Nick:</strong></td><td><?=$char->nickname;?></td>
				</tr><tr>
					<td style="width: 45%;"><strong>First:</strong></td><td><?=$char->firstname;?></td>
				</tr><tr>
					<td style="width: 45%;"><strong>Last:</strong></td><td><?=$char->lastname;?></td>
				</tr><tr>
					<td style="width: 45%;"><strong>Birth:</strong></td><td><?=date( dateDisplay(), strtotime( $char->birthdate ) );?></td>
				</tr><tr>
					<td style="width: 45%;"><strong>Sex:</strong></td><td><?=$char->gender_full;?></td>
				</tr><tr>
					<td colspan="2" style="height: 15px;">&nbsp;</td>
				</tr><tr>				
					<td colspan="2" align="center" style="padding: 0px; border: 1px solid #990033; border-bottom: none; color: #fff; height: 20px; background: url( '<?=$rootdir;?>/images/grad_vert_20.png' ); width: 100%;">
						<strong>Health</strong>
					</td>
				</tr><tr>

					<td colspan="2" style="border: solid 1px #990033; border-top: none; padding: 0px;">
						<table style="width: 100%; padding: 0px;" cellspacing="0" cellpadding="0">
						<tr>

						<? $leftwidth = $char->health / $char->maxhealth; $leftwidth *= 100; ?>						
						<td style="width: <?=$leftwidth;?>%; padding: 3px; background-color: green; color: white; text-align: center;"><?=$leftwidth;?>%</td>
						<td style="width: <?=(100-$leftwidth);?>%;"></td>
						
						</tr>
						</table>						
					</td>
					
				</tr><tr>
					<td colspan="2" style="height: 15px;">&nbsp;</td>
				</tr>
				<?
				if ( $char->hasVehicle() )
				{
					?>
					<tr>				
						<td colspan="2" align="center" style="padding: 0px; border: 1px solid #990033; border-bottom: none; color: #fff; height: 20px; background: url( '<?=$rootdir;?>/images/grad_vert_20.png' ); width: 100%;">
						<strong><?=$char->getVehicleInfo('name');?></strong>
						</td>
					</tr><tr>
						
						<td colspan="2" style="border: solid 1px #990033; border-top: none; padding: 0px;">
							<table style="width: 100%; padding: 0px;" cellspacing="0" cellpadding="0">
							<tr>

							<? $leftwidth = $char->getVehicleHealth() / 100; $leftwidth *= 100; ?>				
							<td style="width: <?=$leftwidth;?>%; padding: 3px; background-color: #36505F; color: white; text-align: center;"><?=$leftwidth;?>%</td>
							<td style="width: <?=(100-$leftwidth);?>%;"></td>
							
							</tr>
							</table>						
						</td>
						
					</tr>
					<?
				}
				?>
				<tr>
					<td colspan="2" style="height: 15px;">&nbsp;</td>
				</tr><tr>
					<td colspan="2" style="text-align: center; border: 1px solid #990033; border-bottom: none; color: #fff; height: 20px; background: url( '<?=$rootdir;?>/images/grad_vert_20.png' ); width: 100%; padding: 0px;">
						<strong>Money</strong>
					</td>
				</tr><tr>
					<td style="width: 45%; border: solid 1px #990033; border-top: none; border-bottom: none; padding: 2px;"><strong>Money:</strong></td><td  style="border-right: solid 1px #990033; padding: 2px;" id="money_clean">$<?=$char->money_clean;?></td>
				</tr><tr>
					<td style="width: 45%; border: solid 1px #990033; padding: 2px;"><strong>Dirty:</strong></td><td style="border: solid 1px #990033; border-left: none; padding: 2px;">$<?=$char->money_dirty;?></td>
				</tr><tr>
					<td colspan="2" style="height: 15px;">&nbsp;</td>
				</tr>
				</table>		
						
				</div>
			</div>
			<? } else { ?>
			<div class="rightcontent">
				<div class="title" style="margin-top: 20px; margin-left: 10px; margin-right: 10px; padding-left: 10px;">
					Poll
				</div>
				<div class="navcontent" style="margin-left: 10px; margin-right: 10px;">
					<?
					$pollQuery = $db->query( "SELECT * FROM polls WHERE open='true' AND displayed='true'" );
					if ( $db->getRowcount( $pollQuery ) == 0 )
					{
						print("Sorry, no polls found.");
					} else {
						$poll = $db->fetch_array( $pollQuery );
						$voterQuery = $db->query( "SELECT * FROM polls_voters WHERE ip='" . $_SERVER["REMOTE_ADDR"] . "' AND poll=" . $poll['id'] );
						print( $poll['title'] . "<br />");
						if ( $db->getRowCount( $voterQuery ) == 0 )
						{
							?>
							<form method='post' action='<?=$rootdir;?>/vote.php'>
							<p>
							<input type='hidden' name='id' value='<?=$poll['id'];?>' />
							<?
							$optionQuery = $db->query( "SELECT * FROM polls_options WHERE parent=" . $poll['id'] . " ORDER BY id ASC" );
							while ( $option = $db->fetch_array( $optionQuery ) )
							{
								?>
								<input type="radio" class='std_input' name="option" value="<?=$option['id'];?>" /> <?=$option['option'];?><br />
								<?
							}
							?>
							<br />
							<input type="submit" class='std_input' name="vote" value="Vote" />
							</p>
							</form>
							<?
						} else { // Display results NOT poll
							?><br /><?
							$optionQuery = $db->query( "SELECT * FROM polls_options WHERE parent=" . $poll['id'] );
							while ( $option = $db->fetch_array( $optionQuery ) )
							{
								?>
								<?=$option['option'];?><br />(<span style='color: #CC0000'><?=$option['votes'];?></span> votes)<br />
								<?
							}
						}
					}
					?>
				</div>
			</div>
			<? } ?>
			

			<!-- THE 'CONTENT_CONTENT' DIV, THIS IS POSITIONED TO THE RIGHT OF THE LEFT NAVIGATION -->
			<div class="pagecontent">	

			<?
				$type = ( isset( $_SESSION['error'] ) ) ? "error" : "";
				$type = ( isset( $_SESSION['notify'] ) ) ? "notify" : $type;				
				$msg = ( $type == "" ) ? "" : ( $type == "error" ) ? $_SESSION['error'] : $_SESSION['notify'];
				$errorstyle = ( $type == "error" ) ? "style=\"display: block; visibility: visible;\"" : "style=\"display: none; visibility: hidden;\"";
				$notifystyle = ( $type == "notify" ) ? "style=\"display: block; visibility: visible;\"" : "style=\"display: none; visibility: hidden;\"";
				unset( $_SESSION['notify'] ); unset( $_SESSION['error'] );
			?>

			<!-- The error report div -->
			<div class="errordisplay" id="errordisplay" <?=$errorstyle;?>>
			<img src="<?=$rootdir;?>/images/checkError.png" alt="" style="margin-right: 7px;" /><?=$msg;?>
			</div>
			
			<!-- The notification report div -->
			<div class="notifydisplay" id="notifydisplay" <?=$notifystyle;?>>
			<img src="<?=$rootdir;?>/images/checkOK.png" alt="" style="margin-right: 7px;" /><?=$msg;?>
			</div>	