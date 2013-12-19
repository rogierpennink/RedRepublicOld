<?
/* TODO:
 * Allow characters with the char_rank field taverns_notice_enabled
 * to post notices with new messages.
 */
$ext_style = "forums_style.css";
$nav = "localcity";

$AUTH_LEVEL = 0;
$REDIRECT = true;
$CHARNEEDED = true;

include_once "../includes/utility.inc.php";
include_once "../includes/forums.inc.php";

$char = new Player( getUserID() );

/* Check for tavern */
$q = $db->query( "SELECT * FROM localcity INNER JOIN businesses ON localcity.business_id = businesses.id WHERE business_type=0 AND location_id=". $char->location_nr );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This city doesn't have a tavern, you'll need to do your drinking elsewhere.";
	header( "Location: index.php" );
	exit;
}

$business = $db->fetch_array( $q );

if ( $business['location_id'] != $char->location_nr )
{
	$_SESSION["error"] = "You can't view this tavern, its not in your city...";
	header( "Location: index.php" );
	exit;
}

$char->setBarTimer();

/* Submit Chat Message */
if ( isset( $_POST['submit'] ) )
{
	if ( isset( $_POST['message'] ) && strlen( $_POST['message'] ) > 0 )
	{
		$db->query( "INSERT INTO taverns_messages (id, date, message, char_id, location_id, type) VALUES ('', CURDATE(), " . $db->prepval( $_POST['message'] ) . ", " . $char->getCharacterID() . ", " . $char->location_nr . ", 0)" );
		unset( $_POST['submit'] );
		unset( $_POST['message'] );
	}

	exit;
}

if ( isset( $_POST['getuserlist'] ) )
{
	$html = "";
	$personquery = $db->query( "SELECT id FROM char_timers WHERE bar_timer>" . time() );
	while ( $list = $db->fetch_array( $personquery ) )
	{
		$newlist = $db->getRowsFor( "SELECT * FROM char_characters WHERE timers_id=" . $list['id'] . " AND location=" . $char->location_nr );
		$html .= $newlist['id']. "::" .$newlist['nickname']. "\n";
	}

	$html = substr( $html, 0, strlen( $html ) - 1 );
	echo $html;
	
	exit;
}

/* Purchase Beer */
if ( isset( $_GET['baract'] ) && $_GET['baract'] == 'beer' )
{
	if ( $char->getCleanMoney() < 5 )
	{
		$message = "You don't have enough money on you to buy a beer.";
	}
	else
	{
		$char->setCleanMoney( $char->getCleanMoney() - 5 );
		$char->setIntellect( $char->getIntellect()-1 );
		$char->addDeedPoint( DEED_DRINKER, 1, 10 ); // Make it a little rarer to get the vice 'Drinker'.
		addTavernMessage( $char->location_nr, $char->getCharacterID(), "purchases a beer from the counter.", TAVERN_BAR_ACTION );
		header( "location: " . $rootdir . "/localcity/tavern.php?act=bar" );
		exit;
	}
}

/* Delete Forum Message */
if ( isset( $_GET['act'] ) && $_GET['act'] == "del" && isset( $_GET['tid'] ) && is_numeric( $_GET['tid'] ) )
{
	if ( ( getUserRights() >= USER_SUPERADMIN ) || ( $business['owner_id'] == $char->getCharacterID() && !$gov->PreeSpeechBool ) )
	{
		$db->query( "DELETE FROM taverns_topics WHERE location_id=" . $char->location_nr . " AND id=" . $_GET['tid'] );
		$db->query( "DELETE FROM taverns_replies WHERE location_id=" . $char->location_nr . " AND topic_id=" . $_GET['tid'] );
	}
}

/* Reply to Topic */
if ( isset( $_POST['reply'] ) && isset( $_GET['act'] ) && isset( $_GET['tid'] ) && is_numeric( $_GET['tid'] ) )
{
	/* Check for topic existance */
	if ( $db->getRowCount( $db->query( "SELECT id FROM taverns_topics WHERE id=" . $db->prepval( $_GET['tid'] ) ) ) != 0 )
	{
		$result = $db->query( "INSERT INTO taverns_replies (char_id, message, date, topic_id, location_id) VALUES (" . $char->getCharacterID() . ", " . $db->prepval( $_POST["reply_message"] ) . ", NOW(), " . $db->prepval( $_GET['tid'] ) . ", " . $db->prepval( $char->location_nr ) . ")" );

		if ( !$result ) 
			$message = "There was an error adding your reply to the database.";
		else
			$message = "Your reply has been added.";
	}
}

/* View topic info */
if ( isset( $_GET['tid'] ) && is_numeric( $_GET['tid'] ) )
{
	$vtquery = $db->query( "SELECT * FROM taverns_topics WHERE id=" . $_GET['tid'] . " AND location_id=" . $char->location_nr );
	if ( $db->getRowCount( $vtquery ) == 0 )
		$Topic = array( "Exists" => false );
	else
	{
		$tarray = $db->fetch_array( $vtquery );

		$TopicInformation = array(
			"Exists" => true,
			"Title" => $tarray['subject'],
			"Message" => $tarray['message'],
			"Notice" => $tarray['notice_tag'],
			"Date" => $tarray['date'],
			"Author" => $tarray['char_id'],
			"ID" => $tarray['id']
		);
	}
}

/* Insert New Topic */
if ( isset( $_POST['newtopic'] ) )
{
	if ( strlen( $_POST["subject"] ) < 5 )
		$message = "The subject for a new topic must be at least 5 characters long.";
	else
	{
		$words = explode( " ", $_POST["message"] );
		if ( count( $words ) < 2 )
			$message = "You must provide a longer message.";
		else
		{
			if ( isset( $_POST["notice"] ) ) $notice = 'true'; else $notice = 'false';
			$db->query( "INSERT INTO taverns_topics (location_id, date, notice_tag, subject, message, char_id) VALUES (" . $char->location_nr . ", NOW(), " . $db->prepval( $notice ) . ", " . $db->prepval( $_POST['subject'] ) . ", " . $db->prepval( $_POST["message"] ) . ", " . $char->getCharacterID() . ")" );
			$message = "Your message has been posted, click <a style='text-decoration: none;' href='" . $rootdir . "/localcity/tavern.php?act=showtopic&tid=" . mysql_insert_id() . "'>here</a> to view it.";
		}
	}
}

include_once "../includes/header.php";
?>

<script type="text/javascript">
function doSendBarMessage( e )
{
	var code;
	if ( !e ) var e = window.event;
	if ( e.keyCode ) code = e.keyCode;
	else if ( e.which ) code = e.which;
	
	if ( code == 13 ) 
	{
		var ajax = new AjaxConnection( response );
		ajax.setScriptUrl( 'tavern.php' );
		ajax.method = "POST";
		ajax.addParam( 'submit', 'post' );
		ajax.addParam( 'message', document.getElementById('t_input').value );
		ajax.send( null );

		document.getElementById('t_input').value = "";
	}

	function response( text )
	{
		iframe = window.frames["bar"];
		iframe.getBarContent();
	}	
}
</script>

<h1><?=$char->location;?> Tavern</h1>
<p>Welcome to the <?=$char->location;?> Tavern. Here you can socialize with others and drink yourself silly all for a cheap price.
<? if ( isset( $message ) ) { ?><br /><br /><b><?=$message;?></b><? } ?></p>

<?
if ( isset( $_GET['act'] ) && $_GET['act'] == "showtopic" )
{
	?>
	<div style="width: 100%;">
		<!-- The forum navigation -->
		<div class="title">
			<div style="margin-left: 10px;">Tavern Navigation</div>
		</div>
		<div class="content">
			<a href="tavern.php">The <?=$char->location;?> Tavern</a> &#187; <?=stripslashes( $TopicInformation['Title'] );?></a><br />
			<a href="tavern.php?act=bar">The <?=$char->location;?> Tavern's Bar</a>
		</div>

		<div class="title">
			<div style="margin-left: 10px;">
			<?
			if ( !$TopicInformation["Exists"] )
			{
				?>This topic has been removed or doesn't exist.<?
			}
			else
			{
				?><a href="<?=$rootdir;?>/localcity/tavern.php"><img src="<?=$rootdir;?>/images/icon_close.gif" alt="close" title="Close Topic" style="float: right; margin: 2px; border: 0px;" /></a><?=$TopicInformation["Title"];?> <span style="font-size: 10px;">- Posted on <?=$TopicInformation["Date"];?></span><?
			}
			?>
			</div>
		</div>
		<div class="content" style="padding: 0px; margin: 0px;">
			<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
				<table class="row">
					<tr>
						<td class="field" style="width: 150px;"><strong>Person Information</strong></td>
						<td class="field"><input type="button" class="std_input" value="  Reply  " style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px; width: auto; float: right; display: inline;" onclick="window.location = '<?=$PHP_SELF;?>?act=reply&amp;tid=<?=$TopicInformation['ID'];?>';" /><strong>Message</strong></td>
					</tr>
				</table>
			</div>
			<div class="row">
				<table class="row">
					<tr>
						<td class="field" style="width: 150px; vertical-align: top; border-right: 1px solid; margin-top: 0px;">
							<a style="text-decoration: none; font-weight: bold;" href="<?=$rootdir;?>/profile.php?id=<?=$TopicInformation["Author"];?>"><?=getCharNickname( $TopicInformation["Author"] );?></a><br />
							
							<span style="font-size: 10px;">
							Posts: <?=$db->getRowCount( $db->query( "SELECT id FROM taverns_topics WHERE char_id=" . $TopicInformation["Author"] ) );?><br />
							Replies: <?=$db->getRowCount( $db->query( "SELECT id FROM taverns_replies WHERE char_id=" . $TopicInformation["Author"] ) );?>
							</span>
							<br /><br /><br /><br />
						</td>
						<td class="field" style="vertical-align: top;">
							<?
							$exmessage = str_replace( Chr(13), "<br />", $TopicInformation["Message"] );
							$exmessage = addEmoticons( $exmessage, $rootdir );
							$exmessage = addBBCode( $exmessage );
							print( $exmessage );
							?>
							<br /><br />
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<?
	$replyresult = $db->query( "SELECT * FROM taverns_replies WHERE topic_id=" . $TopicInformation["ID"] . " AND location_id=" . $char->location_nr . " ORDER BY id ASC" );
	if ( $db->getRowCount( $replyresult ) > 0 )
	{
		while ( $reply = $db->fetch_array( $replyresult ) )
		{
			?>
			<div class="content" style="padding: 0px; margin: 0px; border-top: none;">
				<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
					<table class="row">
						<tr>
							<td class="field" style="width: 150px;"><strong>Person Information</strong></td>
							<td class="field"><input type="button" class="std_input" value="  Reply  " style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px; width: auto; float: right; display: inline;" onclick="window.location = '<?=$PHP_SELF;?>?act=reply&amp;tid=<?=$TopicInformation['ID'];?>';" /><strong>Message</strong></td>
						</tr>
					</table>
				</div>
				<div class="row">
					<table class="row">
						<tr>
							<td class="field" style="width: 150px; vertical-align: top; border-right: 1px solid; margin-top: 0px;">
								<a style="text-decoration: none; font-weight: bold;" href="<?=$rootdir;?>/profile.php?id=<?=$reply["char_id"];?>"><?=getCharNickname( $reply['char_id'] );?></a><br />
								
								<span style="font-size: 10px;">
								Posts: <?=$db->getRowCount( $db->query( "SELECT id FROM taverns_topics WHERE char_id=" . $reply["char_id"] ) );?><br />
								Replies: <?=$db->getRowCount( $db->query( "SELECT id FROM taverns_replies WHERE char_id=" . $reply["char_id"] ) );?>
								</span>
								<br /><br /><br /><br />
							</td>
							<td class="field" style="vertical-align: top;">
								<?
								$exmessage = str_replace( Chr(13), "<br />", $reply["message"] );
								$exmessage = addEmoticons( $exmessage, $rootdir );
								$exmessage = addBBCode( $exmessage );
								print( $exmessage );
								?>
								<br /><br />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<?
		}
	}

	?><div style="margin-bottom: 20px;">&nbsp;</div><?
}

if ( isset( $_GET['act'] ) && $_GET['act'] == "reply" )
{
	?>	
	<!-- Post Reply -->
	<form method='post' action='<?=$rootdir;?>/localcity/tavern.php?act=showtopic&tid=<?=$_GET['tid'];?>' name='replyForm'>
		<div class="title">
			<div style="margin-left: 10px;">Post new reply</div>
		</div>
		<div class="content" style="padding: 0px;">

			<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
				<table class="row">
					<tr>							
						<td class="field" style="width: 125px; border-right: solid 1px #bb6;">&nbsp;</td>
						<td class="field"><strong>New Reply</strong></td>
					</tr>
				</table>
			</div>

			<div class="row">
				<table class="row">
					<tr>
						<td class="field" style="width: 125px; border-right: solid 1px #bb6; vertical-align: top; padding-right: none;">				
							<div style="height: 20px; text-align: right; margin: 5px; margin-top: 2px;"><strong>Message:</strong></div>							
							<div class="title" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: solid 1px #bb6; margin-right: 5px;"><div style="margin-left: 10px;">Smileys</div></div>
							<div class="content" style="background-color: #ee9; border: solid 1px #bb6; border-top: none; margin-right: 5px;">
							<?
							$emo = getEmoticonArrays( false );
							for ( $i = 0; $i < count( $emo[0] ) / 5; $i++ )
							{
								if ( isset( $emo[0][$i*5+0] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+0] ."\" alt=\"". $emo[0][$i*5+0] ."\" title=\"". $emo[0][$i*5+0] ."\" />";
								if ( isset( $emo[0][$i*5+1] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+1] ."\" alt=\"". $emo[0][$i*5+1] ."\" title=\"". $emo[0][$i*5+1] ."\" />";
								if ( isset( $emo[0][$i*5+2] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+2] ."\" alt=\"". $emo[0][$i*5+2] ."\" title=\"". $emo[0][$i*5+2] ."\" />";
								if ( isset( $emo[0][$i*5+3] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+3] ."\" alt=\"". $emo[0][$i*5+3] ."\" title=\"". $emo[0][$i*5+3] ."\" />";
								if ( isset( $emo[0][$i*5+4] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+4] ."\" alt=\"". $emo[0][$i*5+4] ."\" title=\"". $emo[0][$i*5+4] ."\" />";
								echo "<br />";
							}
							?>
							</div>
							<span style="font-size: 10px;"><i>Hover over the smileys to see how to make them appear in your reply!</i></span>
						</td>

						<td class="field" style="padding: 0px; vertical-align: top; padding: 5px;">							
							<textarea name="reply_message" class="std_input" style="margin-top: 5px; width: 100%; min-height: 300px;"></textarea>
						</td>
					</tr>
				</table>
			</div>

			<div class="row" style="text-align: center;">
				<input type="submit" class="std_input" name="reply" value="    Submit Reply    " style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-top: 5px;" />
				<input type="hidden" name="t_id" value="<?=$topic['id'];?>" />
			</div>
			
		</div>

	</form>		
	<?
}

if ( $_GET['act'] != "reply" && $_GET['act'] != "bar" && $_GET['act'] != "showtopic" && $_GET['act'] != "newtopic" )
{
?>

<div style="width: 100%; margin-bottom: 30px;">
	<div class="title">
		<div style="margin-left: 10px;"><?=$char->location;?> Forum</div>
	</div>
	<div class="content" style="padding: 0px;">
		<!-- Table for categories -->
		<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
			<table class="row">
				<tr>
					<?
					if ( ( getUserRights() >= USER_SUPERADMIN ) || ( $business['owner_id'] == $char->getCharacterID() && !$gov->FreeSpeechBool ) )
					{
						?>
						<!-- Delete Field -->
						<td class="field" style="width: 15px;">&nbsp;</td>
						<?
					}
					?>
					<td class="field" style="width: 16px;"><img src="<?=$rootdir;?>/images/icon_blank_16x16.png" alt="&nbsp;" /><!-- Notice Icon --></td>
					<td class="field" style="width: 45%;"><strong>Title</strong></td>
					<td class="field" style="width: 20%;"><strong>Author</strong></td>
					<td class="field" style="width: 10%;"><strong># Replies</strong></td>
					<td class="field" style="width: 40%;"><strong>Date</strong></td>
				</tr>
			</table>
		</div>
		<!-- Content Listing -->
		<?
		if ( isset( $_GET['act'] ) && $_GET['act'] == 'showall' )
			$order = "ORDER BY date DESC";
		else
			$order = "ORDER BY date DESC LIMIT 7";

		$orderex = "ORDER BY date DESC"; // Always show all notices.

		$Results = array(
			"Notices" => $db->query( "SELECT * FROM taverns_topics WHERE location_id=" . $char->location_nr . " AND notice_tag='true' " . $orderex ),
			"Normals" => $db->query( "SELECT * FROM taverns_topics WHERE location_id=" . $char->location_nr . " AND notice_tag='false' " . $order )
		);
		
		/* Bar Link */
		?>
		<div class="row">
			<table class="row">
				<tr>
					<td class="field" style="width: 16px;"><img alt="C" style="border: 0px;" src="<?=$rootdir;?>/images/icon_users.png" /></td>
					<td class="field" style="width: 45%;"><a href="<?=$rootdir;?>/localcity/tavern.php?act=bar" title="Go to the bar..."><?=$char->location;?> Tavern Bar</a></td>
					<td class="field" style="width: 20%;">&nbsp;</td>
					<td class="field" style="width: 10%;">&nbsp;</td>
					<td class="field" style="width: 40%;">&nbsp;</td>
				</tr>
			</table>
		</div>
		<?

		if ( $db->getRowCount( $Results["Notices"] ) == 0 && $db->getRowCount( $Results["Normals"] ) == 0 )
		{
			?>
			<!-- No rows found -->
			<div class="row">
				<table class="row">
					<tr>
						<td class="field" style="width: 100%; margin-left: 10px;">No topics were found.</td>
					</tr>
				</table>
			</div>
			<?
		}
		else
		{
			/* Notices */
			while ( $topic = $db->fetch_array( $Results["Notices"] ) )
			{
				?>
				<div class="row">
					<table class="row">
						<tr>
							<?
							if ( ( getUserRights() >= USER_SUPERADMIN ) || ( $business['owner_id'] == $char->getCharacterID() && !$gov->PreeSpeechBool ) )
							{
								?>
								<!-- Delete Field -->
								<td class="field" style="width: 15px;"><a style="text-decoration: none;" href="<?=$rootdir;?>/localcity/tavern.php?act=del&tid=<?=$topic['id'];?>"><img style="border: 0px; width: 15px;" src="<?=$rootdir;?>/images/icon_delete.gif" alt="D" /></a></td>
								<?
							}
							?>
							<td class="field" style="width: 16px;"><img alt="N" style="border: 0px;" src="<?=$rootdir;?>/images/icon_read.png" /></td>
							<td class="field" style="width: 45%;"><a href="<?=$rootdir;?>/localcity/tavern.php?act=showtopic&tid=<?=$topic['id'];?>" title="<?=shortstr( $topic['message'] );?>"><?=shortstr( $topic["subject"] );?></a></td>
							<td class="field" style="width: 20%;"><a href="<?=$rootdir;?>/profile.php?id=<?=$topic['char_id'];?>" style="text-decoration: none;"><?=getCharNickname( $topic['char_id'] );?></a></td>
							<td class="field" style="width: 10%;"><?=$db->getRowCount( $db->query( "SELECT id FROM taverns_replies WHERE location_id=" . $char->location_nr . " AND topic_id=" . $topic['id'] ) );?></td>
							<td class="field" style="width: 40%;"><?=$topic['date'];?></td>
						</tr>
					</table>
				</div>
				<?
			}

			/* Now Normals */
			while ( $topic = $db->fetch_array( $Results["Normals"] ) )
			{
				?>
				<div class="row">
					<table class="row">
						<tr>
							<?
							if ( ( getUserRights() >= USER_SUPERADMIN ) || ( $business['owner_id'] == $char->getCharacterID() && !$gov->PreeSpeechBool ) )
							{
								?>
								<!-- Delete Field -->
								<td class="field" style="width: 15px;"><a style="text-decoration: none;" href="<?=$rootdir;?>/localcity/tavern.php?act=del&tid=<?=$topic['id'];?>"><img style="border: 0px; width: 15px;" src="<?=$rootdir;?>/images/icon_delete.gif" alt="D" /></a></td>
								<?
							}
							?>
							<td class="field" style="width: 16px;"><img alt="N" width='16' height='16' style="border: 0px;" src="<?=$rootdir;?>/images/icon_blank_16x16.png" /></td>
							<td class="field" style="width: 45%;"><a href="<?=$rootdir;?>/localcity/tavern.php?act=showtopic&tid=<?=$topic['id'];?>" title="<?=shortstr( $topic['message'] );?>"><?=shortstr( $topic["subject"] );?></a></td>
							<td class="field" style="width: 20%;"><a href="<?=$rootdir;?>/profile.php?id=<?=$topic['char_id'];?>" style="text-decoration: none;"><?=getCharNickname( $topic['char_id'] );?></a></td>
							<td class="field" style="width: 10%;"><?=$db->getRowCount( $db->query( "SELECT id FROM taverns_replies WHERE location_id=" . $char->location_nr . " AND topic_id=" . $topic['id'] ) );?></td>
							<td class="field" style="width: 40%;"><?=$topic['date'];?></td>
						</tr>
					</table>
				</div>
				<?
			}
		}
		?>
		<div class="row">
			<table class="row">
				<tr>
					<td class="field" style="width: 100%; margin-left: 10px;"><a style="text-decoration: none; font-weight: bold;" href="<?=$rootdir;?>/localcity/tavern.php?act=showall">Show All Topics</a> | <a id="newTopicLink" style="text-decoration: none; font-weight: bold; cursor: pointer;" href="<?=$rootdir;?>/localcity/tavern.php?act=newtopic">Post New Topic</a></td>
				</tr>
			</table>
		</div>
	</div>
</div>

<?
}
if ( isset( $_GET["act"] ) && $_GET["act"] == "newtopic" )
{
	?>
	<form method='post' action='<?=$rootdir;?>/localcity/tavern.php'>
		<div class="title">
			<div style="margin-left: 10px;">Post new topic</div>
		</div>
		<div class="content" style="padding: 0px;">

			<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
				<table class="row">
					<tr>							
						<td class="field" style="width: 125px; border-right: solid 1px #bb6;">&nbsp;</td>
						<td class="field"><strong>New Reply</strong></td>
					</tr>
				</table>
			</div>

			<div class="row">
				<table class="row">
					<tr>
						<td class="field" style="width: 125px; border-right: solid 1px #bb6; vertical-align: top; padding-right: none;">				
							<div style="height: 20px; text-align: right; margin: 5px; margin-top: 2px;"><strong>Subject:</strong></div>
							<div style="height: 20px; text-align: right; margin: 5px; margin-top: 2px;"><strong>Message:</strong></div>							
							<div class="title" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: solid 1px #bb6; margin-right: 5px;"><div style="margin-left: 10px;">Smileys</div></div>
							<div class="content" style="background-color: #ee9; border: solid 1px #bb6; border-top: none; margin-right: 5px;">
							<?
							$emo = getEmoticonArrays( false );
							for ( $i = 0; $i < count( $emo[0] ) / 5; $i++ )
							{
								if ( isset( $emo[0][$i*5+0] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+0] ."\" alt=\"". $emo[0][$i*5+0] ."\" title=\"". $emo[0][$i*5+0] ."\" />";
								if ( isset( $emo[0][$i*5+1] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+1] ."\" alt=\"". $emo[0][$i*5+1] ."\" title=\"". $emo[0][$i*5+1] ."\" />";
								if ( isset( $emo[0][$i*5+2] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+2] ."\" alt=\"". $emo[0][$i*5+2] ."\" title=\"". $emo[0][$i*5+2] ."\" />";
								if ( isset( $emo[0][$i*5+3] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+3] ."\" alt=\"". $emo[0][$i*5+3] ."\" title=\"". $emo[0][$i*5+3] ."\" />";
								if ( isset( $emo[0][$i*5+4] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+4] ."\" alt=\"". $emo[0][$i*5+4] ."\" title=\"". $emo[0][$i*5+4] ."\" />";
								echo "<br />";
							}
							?>
							</div>
							<span style="font-size: 10px;"><i>Hover over the smileys to see how to make them appear in your reply!</i></span>
						</td>

						<td class="field" style="padding: 0px; vertical-align: top; padding: 5px;">		
							<input type="text" name="subject" class="std_input" style="width: auto;" /><br />
							<textarea name="message" class="std_input" style="margin-top: 5px; width: 100%; min-height: 300px;"></textarea>
							<?
							/* Allow Notice? */
							if ( getUserRights() >= USER_SUPERADMIN )
							{
								?><br /><input type="checkbox" name="notice" /> Notice<br /><?
							}
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="row" style="text-align: center;">
				<input type="submit" class="std_input" name="newtopic" value="   Submit Topic   " style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-top: 5px;" />				
			</div>				
		</div>
	</form>
	<?
}

if ( isset( $_GET['act'] ) && $_GET['act'] == "bar" && !isset( $_GET["open"] ) )
{
	?>
	<div class="title">
		<div style="margin-left: 10px; margin-top: 1px; margin-right: 2px;">The Bar</div>
	</div>
	<div class="content" style="height: 250px;">
		<table style="width: 100%;">
		<tr>
			<td style="width: 80%;">

				<iframe id="bar" name="bar" style="border: solid 1px; width: 100%; height: 220px;" src="bar.php"></iframe>
				
			</td>
			<td style="width: 20%; vertical-align: top;">
				<select id="personlist" style="font-family: verdana; font-size: 10px; border: 1px solid; width: 100%; height: 222px;" onchange="window.location='../profile.php?id='+this.value;" multiple="true">
				</select>
				<script type="text/javascript">
				function getUserList()
				{
					var ajax = new AjaxConnection( response );
					ajax.setScriptUrl( 'tavern.php' );
					ajax.method = "POST";
					ajax.addParam( 'getuserlist', 'true' );
					ajax.send( null );

					function response( text )
					{
						lines = text.split( "\n" );
						for ( i = 0; i < lines.length; i++ )
						{
							line = lines[i].split( "::" );
							document.getElementById('personlist').options[i] = new Option( line[1], line[0] );
							document.getElementById('personlist').options[i].style.fontWeight = 'bold';
							document.getElementById('personlist').options[i].style.textAlign = 'right';
						}
						setTimeout( "getUserList()", 5000 );
					}
				}

				getUserList();
				</script>
			</td>
		</tr>

		<tr>
			<td colspan="2">				
				<input type='text' id="t_input" name='message' style='border: solid 1px #000; width: 100%;' />	
				<script type="text/javascript">
				if ( document.all )
					document.getElementById('t_input').attachEvent( 'onkeypress', function(e){ doSendBarMessage( e ); } );
				else
					document.getElementById('t_input').addEventListener( 'keypress', function(e){ doSendBarMessage( e ); }, false );
				</script>
			</td>
		</tr>
		</table>
	</div>

	<div class="title" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');"><div style="margin-left: 10px;">Options</div></div>
	<div class="content">
		<a onclick="showBeer();" alt='' href='#' style='text-decoration: none; cursor: pointer;'>Purchase Beer</a>
	</div>
			
	<?
}

include_once "../includes/footer.php";
?>