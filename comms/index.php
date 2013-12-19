<?
$nav = "comms";
$ext_style = "tab_style.css";
/**
 * The communications index.php file. This will show the inbox and contain links to both
 * the outbox and saved messages.
 */

include_once "../includes/utility.inc.php";
include_once "../includes/forums.inc.php";

if ( $_POST['ajaxrequest'] == "compose_msg" )
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

	$char = new Player( getUserID() );

	if ( $char->nickname == $_POST['to'] && getUserRights() != USER_SUPERADMIN )
	{
		echo "error::You cannot send messages to yourself!";
		exit;
	}
	
	if ( $db->getRowCount( $db->query( "SELECT * FROM char_characters WHERE nickname=" . $db->prepval( $_POST['to'] ) ) ) == 0 )
	{
		echo "error::You entered a non-existing name in the recipient field!";
		exit;
	}

	if ( strlen( $_POST['subject'] ) > 65 )
	{
		echo "error::Your subject exceeded the max. length of 65 characters!";
		exit;
	}

	$recipient = $db->fetch_array( $db->query( "SELECT * FROM char_characters WHERE nickname=". $db->prepval( $_POST['to'] ) ) );

	// Add message to the database, first as a sent item for this user
	$message = str_replace( array( "<", ">" ), array( "&lt;", "&gt;" ), $_POST['message'] );
	$message = str_replace( array( "[img]", "[/img]", Chr(13) ), array( "", "", "<br />" ), $message );

	/* Add entry to sent items. */
	if ( $db->query( "INSERT INTO comms SET `to`=". $recipient['id'] .", `from`=". $char->character_id .", `date`='". date( "Y-m-d H:i:s", time() ) ."', `subject`=". $db->prepval( $_POST['subject'] ) . ", `message`=". $db->prepval( $_POST['message'] ) .", `comm_new`=1, `comm_type`=1" ) === false )
	{
		echo "error::An error occurred on sending your message. Message was discarded.";
		exit;
	}

	$from_id = mysql_insert_id();

	if ( $db->query( "INSERT INTO comms SET `to`=". $recipient['id'] .", `from`=". $char->character_id .", `date`='". date( "Y-m-d H:i:s", time() ) ."', `subject`=". $db->prepval( $_POST['subject'] ) . ", `message`=". $db->prepval( $_POST['message'] ) .", `comm_new`=1, `comm_type`=0" ) === false )
	{
		$db->query( "DELETE FROM comms WHERE id=". $from_id );
		echo "error::An error occurred on sending your message. Message was discarded.";
		exit;
	}

	echo "success::Your message was sent to '". $recipient['nickname'] ."' successfully!";
	exit;
}

if ( $_GET['ajaxrequest'] == "deletemessage" )
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

	// Check if the ID is a deletable message (it exists, and is yours)
	$rec = $db->query( "SELECT * FROM comms WHERE `to`=". $player->character_id ." OR `from`=" . $player->character_id . " AND `id`=". $db->prepval( $_GET['id'] ) );

	if ( $db->getRowCount( $rec ) == 0 )
	{
		echo "error::You tried to delete a non-existent message, or it wasn't yours!";
		exit;
	}

	if ( $db->query( "DELETE FROM comms WHERE id=". $db->prepval( $_GET['id'] ) ) === false )
	{
		echo "error::Could not delete message due to a database problem.";
		exit;
	}

	echo "success::Message was successfully deleted!";
	exit;
}

if ( $_POST['ajaxrequest'] == "deletemessages" )
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
	$ids = explode( "::", $_POST['ids'] );
	for ( $i = 0; $i < count( $ids ); $i++ )
	{
		/* Check if the message exists and is from this player. */
		if ( $db->getRowCount( $db->query( "SELECT * FROM comms WHERE id=". $ids[$i] ." AND ( `to`=". $player->character_id ." AND ( comm_type=0 OR comm_type=2 ) ) OR ( `from`=". $player->character_id ." AND comm_type=1 )" ) ) == 0 )
		{
			echo "error::One of the messages you tried to delete wasn't yours!";
			exit;
		}
		else
		{
			$db->query( "DELETE FROM comms WHERE id=". $ids[$i] );
		}
	}

	echo "success::The selected messages were deleted successfully!";
	exit;
}

if ( $_GET['ajaxrequest'] == "savemessage" )
{
	if ( !user_auth() )
	{
		$_SESSION['error'] = "You need to be logged in to access those pages!";
		echo "error::notloggedin";
		exit;
	}

	if ( !hasCharacter( getUserID() ) )
	{		
		echo "error::You can't save messages if you don't have a character!";
		exit;
	}
	
	$player = new Player( getUserID() );

	// Check if the ID is a saveable message (it exists, and is yours)
	$rec = $db->query( "SELECT * FROM comms WHERE `to`=". $player->character_id ." AND `id`=". $db->prepval( $_GET['id'] ) );

	if ( $db->getRowCount( $rec ) == 0 )
	{
		echo "error::You tried to save a non-existent message, or it wasn't yours!";
		exit;
	}

	if ( $db->query( "UPDATE comms SET comm_type=2 WHERE id=" . $db->prepval( $_GET['id'] ) ) === false )
	{
		echo "error::Could not save message due to a database problem.";
		exit;
	}

	echo "success::Message was successfully saved!";
	exit;
}

if ( $_GET['ajaxrequest'] == "replymessage" )
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

	// Query
	$rec = $db->query( "SELECT * FROM comms WHERE `to`=". $player->character_id ." AND `id`=". $db->prepval( $_GET['id'] ) );

	if ( $db->getRowCount( $rec ) == 0 )
	{
		echo "error::You tried to reply to a message you can't reply to!";
		exit;
	}

	$res = $db->fetch_array( $rec );

	$from = $db->fetch_array( $db->query( "SELECT * FROM char_characters WHERE id=". $res['from'] ) );

	if ( substr( $res['subject'], 0, 4 ) == "RE: " )
		$subj = $res['subject'];
	else
		$subj = "RE: " . $res['subject'];

	echo "success::". $from['nickname'] ."::" . $subj . "::[quote]\n" . $res['message'] ."\n[/quote]\n";
	exit;
}

if ( $_GET['ajaxrequest'] == "getmessages" )
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
	$rec = $db->query( "SELECT * FROM `comms` WHERE `to`=" . $player->character_id ." AND comm_type=0 ORDER BY date DESC" );

	if ( $db->getRowCount( $rec ) == 0 )
	{
		echo "success::There are no messages for you!";
		exit;
	}

	echo "success::";

	while ( $res = $db->fetch_array( $rec ) )
	{
		$from = $db->fetch_array( $db->query( "SELECT * FROM char_characters WHERE id=". $res['from'] ) );
	?>
	<div style="background: #fff686; border: none; font-weight: normal; margin-top: 3px;" onmouseover="this.style.backgroundColor = '#fee676';" onmouseout="this.style.backgroundColor = '#fff686';">
		<table style="width: 100%;">
			<tr>
				<td style="width: 20px;">
					<input type="checkbox" name="inboxdel<?=$res['id'];?>" />
				</td>
				<td style="padding-left: 5px; text-align: left;">
					<strong>"<?=$res['subject'];?>"</strong>
				</td>
				<td style="padding-right: 5px; text-align: right;">
					From <?=$from['nickname'];?> on <?=date( timeDisplay(), strtotime( $res['date'] ) );?>
				</td>
				<td style="text-align: right; width: 80px;">
					<img src="../images/icon_delete.gif" alt="" title="Delete Message" style="cursor: pointer;" onclick="delete_msg( <?=$res['id'];?> );" />
					<img src="../images/icon_read.png" alt="" title="Reply to Message" style="cursor: pointer;" onclick="reply( <?=$res['id'];?> );" />
					<img src="../images/checkOK.png" alt="" title="Save Message" style="cursor: pointer;" onclick="save( <?=$res['id'];?> );" />
					<img src="../images/icon_<?=($res['comm_new']==1)?"minimize.gif":"expand.gif";?>" alt="" title="Expand Message" style="cursor: pointer;" id="expand<?=$res['id'];?>" onclick="expandMsg(<?=$res['id'];?>);" />
				</td>
			</tr>
		</table>			
	</div>
	<div class="content" style="border: solid 1px #fff686; display: <?=(($res['comm_new']==1)?"block;":"none;");?>" id="msg<?=$res['id'];?>">
		<? 
		$msg = addEmoticons( $res['message'], $rootdir );
		$msg = addBBCode ( $msg );
		echo $msg;	
		?>
	</div>
	<?
	}	

	?>
	<div style="margin-top: 20px;">
	<p>
		<input type="button" class="std_input" value="Select All" onclick="this.value=toggleboxes( 'inboxdel' );" />
		<input type="button" class="std_input" value="Delete Selected" onclick="delete_selected_messages( 'inboxdel' );" />
	</p>
	</div>
	<?

	/** Since we saw the page, we'll just assume all messages have been seen. */
	$db->query( "UPDATE comms SET comm_new=0 WHERE comm_new=1 AND `to`=". $player->character_id );
	exit;
}

/* Fetch sent messages */
if ( $_GET['ajaxrequest'] == "getsentmessages" )
{	
	if ( !user_auth() )
	{
		$_SESSION['error'] = "You need to be logged in to access those pages!";
		echo "error::notloggedin";
		exit;
	}

	if ( !hasCharacter( getUserID() ) )
	{		
		echo "error::You can't view sent messages if you don't have a character!";
		exit;
	}
	
	$player = new Player( getUserID() );

	// Fetch inbox data from the database
	$rec = $db->query( "SELECT * FROM `comms` WHERE `from`=" . $player->character_id ." AND comm_type=1 ORDER BY date DESC" );

	if ( $db->getRowCount( $rec ) == 0 )
	{
		echo "success::No recently sent messages were found.";
		exit;
	}

	echo "success::";

	while ( $res = $db->fetch_array( $rec ) )
	{
		$to = $db->fetch_array( $db->query( "SELECT * FROM char_characters WHERE id=". $res['to'] ) );
	?>
	<div style="background: #fff686; border: none; font-weight: normal; margin-top: 3px;" onmouseover="this.style.backgroundColor = '#fee676';" onmouseout="this.style.backgroundColor = '#fff686';">
		<table style="width: 100%;">
			<tr>
				<td style="width: 20px;">
					<input type="checkbox" name="sentdel<?=$res['id'];?>" />
				</td>
				<td style="padding-left: 5px; text-align: left;">
					<strong>"<?=$res['subject'];?>"</strong>
				</td>
				<td style="padding-right: 5px; text-align: right;">
					To <?=$to['nickname'];?> on <?=date( timeDisplay(), strtotime( $res['date'] ) );?>
				</td>
				<td style="width: 60px;">
					<img src="../images/icon_delete.gif" alt="" title="Delete Message" style="cursor: pointer;" onclick="delete_msg( <?=$res['id'];?> );" />
					<img src="../images/icon_expand.gif" alt="" title="Expand Message" style="cursor: pointer;" id="expand<?=$res['id'];?>" onclick="expandMsg(<?=$res['id'];?>);" />
				</td>
			</tr>
		</table>			
	</div>
	<div class="content" style="border: solid 1px #fff686; display: none;" id="msg<?=$res['id'];?>">
		<? 
		$msg = addEmoticons( $res['message'], $rootdir );
		$msg = addBBCode ( $msg );
		echo $msg;	
		?>
	</div>
	<?
	}
		
	?>
	<div style="margin-top: 20px;">
	<p>
		<input type="button" class="std_input" value="Select All" onclick="this.value=toggleboxes( 'sentdel' );" />
		<input type="button" class="std_input" value="Delete Selected" onclick="delete_selected_messages( 'sentdel' );" />
	</p>
	</div>
	<?
	exit;
}

/* Fetch saved messages. */
if ( $_GET['ajaxrequest'] == "getsavedmessages" )
{	
	if ( !user_auth() )
	{
		$_SESSION['error'] = "You need to be logged in to access those pages!";
		echo "error::notloggedin";
		exit;
	}

	if ( !hasCharacter( getUserID() ) )
	{		
		echo "error::You can't view sent messages if you don't have a character!";
		exit;
	}
	
	$player = new Player( getUserID() );

	// Fetch inbox data from the database
	$rec = $db->query( "SELECT * FROM `comms` WHERE `to`=" . $player->character_id ." AND comm_type=2 ORDER BY date DESC" );

	if ( $db->getRowCount( $rec ) == 0 )
	{
		echo "success::No saved messages were found.";
		exit;
	}

	echo "success::";

	while ( $res = $db->fetch_array( $rec ) )
	{
		$from = $db->fetch_array( $db->query( "SELECT * FROM char_characters WHERE id=". $res['from'] ) );
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
					From <?=$from['nickname'];?> on <?=date( timeDisplay(), strtotime( $res['date'] ) );?>
				</td>
				<td style="width: 60px;">
					<img src="../images/icon_delete.gif" alt="" title="Delete Message" style="cursor: pointer;" onclick="delete_msg( <?=$res['id'];?> );" />
					<img src="../images/icon_expand.gif" alt="" title="Expand Message" style="cursor: pointer;" id="expand<?=$res['id'];?>" onclick="expandMsg(<?=$res['id'];?>);" />
				</td>
			</tr>
		</table>			
	</div>
	<div class="content" style="border: solid 1px #fff686; display: none;" id="msg<?=$res['id'];?>">
		<? 
		$msg = addEmoticons( $res['message'], $rootdir );
		$msg = addBBCode ( $msg );
		echo $msg;	
		?>
	</div>
	<?
	}	
		
	?>
	<div style="margin-top: 20px;">
	<p>
		<input type="button" class="std_input" value="Select All" onclick="this.value=toggleboxes( 'saveddel' );" />
		<input type="button" class="std_input" value="Delete Selected" onclick="delete_selected_messages( 'saveddel' );" />
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
<script type="text/javascript" src="../javascript/comms/comms_js.js"></script>
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

function expandMsg( id )
{
	var e = document.getElementById('msg'+id);
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

<h1>Communications</h1>
<p>Your communications panel allows you to interact with other players in Red Republic. You can send, receive and save any messages here.</p>

<div style="width: auto; height: 21px;">
	<div class="tab_selected" id="tab_inbox">
		Inbox
	</div>
	<div class="tab_unselected" id="tab_compose">
		Compose
	</div>
	<div class="tab_unselected" id="tab_sent">
		Sent Items
	</div>
	<div class="tab_unselected" id="tab_saved">
		Saved Items
	</div>
</div>
<div class="content" style="border-top: solid 1px #000; display: block;" id="pane_inbox">
<!-- INBOX CONTENT STARTS HERE! -->
Loading Messages <img src="../images/loading.gif" alt="Loading" style="margin-left: 7px;" />
<script type="text/javascript">loadMessages();</script>
<!-- INBOX CONTENT ENDS HERE! -->
</div>
<div class="content" style="border-top: solid 1px #000; display: none;" id="pane_compose">
<!-- COMPOSE MESSAGE CONTENT STARTS HERE! -->
	<form action="<?=$PHP_SELF;?>" method="post" id="compose_form" onsubmit="sendMessage( this ); return false;">
	<div class="title">
		<img src="../images/small_page.gif" alt="" style="margin-left: 5px; margin-right: 5px;" />Compose a new message
	</div>
	<div class="content" style="background-color: #fff686;">
		<table style="width: 100%; border-spacing: 0px;">
		<tr>
			<td style="width: 100px;">
				<strong>Send to:</strong>
			</td><td>
				<input type="text" name="send_to" class="std_input" />
			</td>
		</tr>
		<tr>
			<td style="width: 100px;">
				<strong>Subject:</strong>
			</td><td>
				<input type="text" name="subject" class="std_input" />
			</td>
		</tr>
		<tr>
			<td style="width: 100px;">
				<strong>Message:</strong>
			</td><td>
				<textarea name="message" style="width: 100%; min-height: 250px;" class="std_input"></textarea>
			</td>
		</tr>
		<tr>
			<td style="width: 100px;">
				&nbsp;
			</td><td>
				<br />
				<input type="submit" name="compose" class="std_input" value="Send Message" />
			</td>
		</tr>
		</table>
	</div>
	</form>
<!-- COMPOSE MESSAGE CONTENT ENDS HERE! -->
</div>
<!-- SENT ITEMS STARTS HERE -->
<div class="content" style="border-top: solid 1px #000; display: none;" id="pane_sent">
Loading Messages <img src="../images/loading.gif" alt="Loading" style="margin-left: 7px;" />
<script type="text/javascript">loadMessagesSent();</script>
</div>
<!-- SENT ITEMS ENDS HERE -->

<!-- SAVED ITEMS STARTS HERE -->
<div class="content" style="border-top: solid 1px #000; display: none;" id="pane_saved">
Loading Messages <img src="../images/loading.gif" alt="Loading" style="margin-left: 7px;" />
<script type="text/javascript">loadMessagesSaved();</script>
</div>
<!-- SAVED ITEMS ENDS HERE -->

<script type="text/javascript">
var comms_pane = new TabbedPane( 'tab_selected', 'tab_unselected' );
comms_pane.addTab( 'inbox', document.getElementById('tab_inbox'), document.getElementById('pane_inbox') );
comms_pane.addTab( 'compose', document.getElementById('tab_compose'), document.getElementById('pane_compose') );
comms_pane.addTab( 'sent', document.getElementById('tab_sent'), document.getElementById('pane_sent') );
comms_pane.addTab( 'saved', document.getElementById('tab_saved'), document.getElementById('pane_saved') );
</script>

<?
include_once "../includes/footer.php";
?>