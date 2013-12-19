<?
$nav = "events";
$ext_style = "tab_style.css";
/**
 * The communications index.php file. This will show the inbox and contain links to both
 * the outbox and saved messages.
 */

include_once "../includes/utility.inc.php";
include_once "../includes/forums.inc.php";

if ( $_GET['ajaxrequest'] == "deleteevent" )
{
	if ( !user_auth() )
	{
		$_SESSION['error'] = "You need to be logged in to access those pages!";
		echo "error::notloggedin";
		exit;
	}

	if ( !hasCharacter( getUserID() ) )
	{		
		echo "error::You can't delete events if you don't have a character!";
		exit;
	}
	
	$player = new Player( getUserID() );

	// Check if the ID is a deletable message (it exists, and is yours)
	$rec = $db->query( "SELECT * FROM events WHERE `char_id`=". $player->character_id ." AND `id`=". $db->prepval( $_GET['id'] ) );

	if ( $db->getRowCount( $rec ) == 0 )
	{
		echo "error::You tried to delete a non-existent event, or it wasn't yours!";
		exit;
	}

	if ( $db->query( "DELETE FROM events WHERE id=". $db->prepval( $_GET['id'] ) ) === false )
	{
		echo "error::Could not delete event due to a database problem.";
		exit;
	}

	echo "success::Event was successfully deleted!";
	exit;
}

if ( $_POST['ajaxrequest'] == "deleteevents" )
{
	if ( !user_auth() )
	{
		$_SESSION['error'] = "You need to be logged in to access those pages!";
		echo "error::notloggedin";
		exit;
	}

	if ( !hasCharacter( getUserID() ) )
	{		
		echo "error::You can't delete events if you don't have a character!";
		exit;
	}

	$player = new Player( getUserID() );
	$ids = explode( "::", $_POST['ids'] );
	for ( $i = 0; $i < count( $ids ); $i++ )
	{
		/* Check if the message exists and is from this player. */
		if ( $db->getRowCount( $db->query( "SELECT * FROM events WHERE id=". $ids[$i] ." AND char_id=". $player->character_id ) ) == 0 )
		{
			echo "error::One of the messages you tried to delete wasn't yours!";
			exit;
		}
		else
		{
			$db->query( "DELETE FROM events WHERE id=". $ids[$i] );
		}
	}

	echo "success::The selected events were deleted successfully!";
	exit;
}

if ( $_GET['ajaxrequest'] == "saveevent" )
{
	if ( !user_auth() )
	{
		$_SESSION['error'] = "You need to be logged in to access those pages!";
		echo "error::notloggedin";
		exit;
	}

	if ( !hasCharacter( getUserID() ) )
	{		
		echo "error::You can't save events if you don't have a character!";
		exit;
	}
	
	$player = new Player( getUserID() );

	// Check if the ID is a saveable message (it exists, and is yours)
	$rec = $db->query( "SELECT * FROM events WHERE `char_id`=". $player->character_id ." AND `id`=". $db->prepval( $_GET['id'] ) );

	if ( $db->getRowCount( $rec ) == 0 )
	{
		echo "error::You tried to save a non-existent event, or it wasn't yours!";
		exit;
	}

	if ( $db->query( "UPDATE events SET event_type=1 WHERE id=" . $db->prepval( $_GET['id'] ) ) === false )
	{
		echo "error::Could not save event due to a database problem.";
		exit;
	}

	echo "success::Event was successfully saved!";
	exit;
}


if ( $_GET['ajaxrequest'] == "getevents" )
{	
	if ( !user_auth() )
	{
		$_SESSION['error'] = "You need to be logged in to access those pages!";
		echo "error::notloggedin";
		exit;
	}

	if ( !hasCharacter( getUserID() ) )
	{		
		echo "error::You can't compose messages if you don't have a character!";
		exit;
	}
	
	$player = new Player( getUserID() );

	// Fetch inbox data from the database
	$rec = $db->query( "SELECT * FROM `events` WHERE `char_id`=" . $player->character_id ." AND event_type=0 ORDER BY date DESC" );

	if ( $db->getRowCount( $rec ) == 0 )
	{
		echo "success::There are no events for you!";
		exit;
	}

	echo "success::";

	while ( $res = $db->fetch_array( $rec ) )
	{		
		$icon = ( $res['event_new'] == 1 ) ? "minimize.gif" : "expand.gif";
		$display = ( $res['event_new'] == 0 ) ? "none;" : "block;";
		$title = ( $res['event_new'] == 1 ) ? "Minimize Event" : "Expand Event";
	?>
	<div style="background: #fff686; border: none; font-weight: normal; margin-top: 3px;" onmouseover="this.style.backgroundColor = '#fee676';" onmouseout="this.style.backgroundColor = '#fff686';">
		<table style="width: 100%;">
			<tr>
				<td style="width: 20px;">
					<input type="checkbox" name="defaultdel<?=$res['id'];?>" />
				</td>
				<td style="padding-left: 5px; text-align: left;">
					<strong>"<?=$res['subject'];?>"</strong>
				</td>
				<td style="padding-right: 5px; text-align: right;">
					On <?=date( timeDisplay(), strtotime( $res['date'] ) );?>
				</td>
				<td style="width: 60px;">
					<img src="../images/icon_delete.gif" alt="" title="Delete Event" style="cursor: pointer;" onclick="delevent( <?=$res['id'];?> );" />	
					<img src="../images/checkOK.png" alt="" title="Save Event" style="cursor: pointer;" onclick="saveevent( <?=$res['id'];?> );" />	
					<img src="../images/icon_<?=$icon;?>" alt="" title="<?=$title;?>" style="cursor: pointer;" id="expand<?=$res['id'];?>" onclick="expandEvent(<?=$res['id'];?>);" />
				</td>
			</tr>
		</table>			
	</div>
	<div class="content" style="border: solid 1px #fff686; display: <?=$display;?>" id="event<?=$res['id'];?>">
		<? 
		$msg = stripslashes( $res['message'] );
		echo $msg;
		?>
	</div>
	<?
	/** Since we saw the page, we'll just assume all messages have been seen. */
	$db->query( "UPDATE events SET event_new=0 WHERE event_new=1 AND `char_id`=". $player->character_id );
	}	
	
	?>
	<div style="margin-top: 20px;">
	<p>
		<input type="button" class="std_input" value="Select All" onclick="this.value=toggleboxes( 'defaultdel' );" />
		<input type="button" class="std_input" value="Delete Selected" onclick="delete_selected_events( 'defaultdel' );" />
	</p>
	</div>
	<?
	exit;
}

if ( $_GET['ajaxrequest'] == "getsavedevents" )
{	
	if ( !user_auth() )
	{
		$_SESSION['error'] = "You need to be logged in to access those pages!";
		echo "error::notloggedin";
		exit;
	}

	if ( !hasCharacter( getUserID() ) )
	{		
		echo "error::You can't compose messages if you don't have a character!";
		exit;
	}
	
	$player = new Player( getUserID() );

	// Fetch inbox data from the database
	$rec = $db->query( "SELECT * FROM `events` WHERE `char_id`=" . $player->character_id ." AND event_type=1 ORDER BY date DESC" );

	if ( $db->getRowCount( $rec ) == 0 )
	{
		echo "success::No saved events were found.";
		exit;
	}

	echo "success::";

	while ( $res = $db->fetch_array( $rec ) )
	{		
		$icon = ( $res['event_new'] == 1 ) ? "minimize.gif" : "expand.gif";
		$display = ( $res['event_new'] == 0 ) ? "none;" : "block;";
		$title = ( $res['event_new'] == 1 ) ? "Minimize Event" : "Expand Event";
	?>
	<div style="background: #fff686; border: none; font-weight: normal; margin-top: 3px;" onmouseover="this.style.backgroundColor = '#fee676';" onmouseout="this.style.backgroundColor = '#fff686';">
		<table style="width: 100%;">
			<tr>
				<td style="width: 20px;">
					<input type="checkbox" name="saveddel<?=$res['id'];?>" />
				</td>
				<td style="padding-left: 5px; text-align: left;">
					<strong>"<?=$res['subject'];?>"</strong>
				</td>
				<td style="padding-right: 5px; text-align: right;">
					On <?=date( timeDisplay(), strtotime( $res['date'] ) );?>
				</td>
				<td style="width: 60px;">
					<img src="../images/icon_delete.gif" alt="" title="Delete Event" style="cursor: pointer;" onclick="delevent( <?=$res['id'];?> );" />	
					<img src="../images/icon_<?=$icon;?>" alt="" title="<?=$title;?>" style="cursor: pointer;" id="expand<?=$res['id'];?>" onclick="expandEvent(<?=$res['id'];?>);" />
				</td>
			</tr>
		</table>			
	</div>
	<div class="content" style="border: solid 1px #fff686; display: <?=$display;?>" id="event<?=$res['id'];?>">
		<? 
		$msg = stripslashes( $res['message'] );
		echo $msg;
		?>
	</div>
	<?
	/** Since we saw the page, we'll just assume all messages have been seen. */
	$db->query( "UPDATE events SET event_new=0 WHERE event_new=1 AND `char_id`=". $player->character_id );
	}	
	
	?>
	<div style="margin-top: 20px;">
	<p>
		<input type="button" class="std_input" value="Select All" onclick="this.value=toggleboxes( 'saveddel' );" />
		<input type="button" class="std_input" value="Delete Selected" onclick="delete_selected_events( 'saveddel' );" />
	</p>
	</div>
	<?
	exit;
}

if ( !user_auth() )
{
	$_SESSION['error'] = "You need to be logged in to access those pages.";
	header( "Location: ../login.php" );
	exit;
}

if ( !hasCharacter( getUserID() ) )
{
	$_SESSION['error'] = "You need to have a character to access those pages.";
	header( "Location: ../account.php" );
	exit;
}

$_SESSION['ingame'] = true;

// Start the real main page
include_once "../includes/header.php";
?>

<script type="text/javascript" src="../javascript/tabbedpane.js"></script>
<script type="text/javascript" src="../javascript/events/events_js.js"></script>
<script type="text/javascript">
var checkflag = false;
function toggleboxes( what )
{	
	checkflag = ( checkflag == true ) ? false : true;
	var cur = 1;
	var b = document.getElementsByTagName('input');
	for ( var i = 0; i < b.length; i++ ) 	
		if ( b[i].getAttribute('type') == "checkbox" && b[i].getAttribute('name').substr( 0, what.length ) == what ) 		
			if ( b[i].style.display == "" )
				b[i].checked = checkflag;	
	
	return ( checkflag == true ) ? "Unselect All" : "Select All";
}
function expandEvent( id )
{
	var e = document.getElementById('event'+id);
	var d = e.style.display;
	
	if ( d == 'block' )
	{
		e.style.display = 'none';
		document.getElementById('expand'+id).src = "../images/icon_expand.gif";
	}
	else
	{
		e.style.display = 'block';
		document.getElementById('expand'+id).src = "../images/icon_minimize.gif";
	}
}
</script>

<h1>Event Reports</h1>
<p>During your life all kind of things can happen to you. Good things, bad things - even very bad things. In your events reports you'll be kept up-to-date of everything that has happened to or around you!</p>

<div style="width: auto; height: 21px;">
	<div class="tab_selected" id="tab_events">
		Events
	</div>	
	<div class="tab_unselected" id="tab_saved">
		Saved Items
	</div>
</div>
<div class="content" style="border-top: solid 1px #000; display: block;" id="pane_events">

Loading Events <img src="../images/loading.gif" alt="Loading" style="margin-left: 7px;" />
<script type="text/javascript">loadEvents();</script>

</div>

<div class="content" style="border-top: solid 1px #000; display: none;" id="pane_saved">

Loading Events <img src="../images/loading.gif" alt="Loading" style="margin-left: 7px;" />
<script type="text/javascript">loadEventsSaved();</script>

</div>

<script type="text/javascript">
var comms_pane = new TabbedPane( 'tab_selected', 'tab_unselected' );
comms_pane.addTab( 'events', document.getElementById('tab_events'), document.getElementById('pane_events') );
comms_pane.addTab( 'saved', document.getElementById('tab_saved'), document.getElementById('pane_saved') );
</script>

<?
include_once "../includes/footer.php";
?>