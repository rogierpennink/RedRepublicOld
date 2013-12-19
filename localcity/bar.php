<?
$ext_style = "forums_style.css";

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
	exit;
}

$business = $db->fetch_array( $q );
$gov = new Government( $char->location_nr );

if ( $business['location_id'] != $char->location_nr )
{
	$_SESSION["error"] = "You can't view this tavern, its not in your city...";
	header( "Location: index.php" );
	exit;
}

$char->setBarTimer();

if ( isset( $_GET['delmsg'] ) && is_numeric( $_GET['delmsg'] ) )
{
	if ( ( getUserRights() >= USER_MODERATOR ) || ( $business['owner_id'] == $char->getCharacterID() && !$gov->FreeSpeechBool ) )
		$db->query( "DELETE FROM taverns_messages WHERE id=" . $db->prepval( $_GET['delmsg'] ) );
}

if ( isset( $_GET['ajaxrequest'] ) && $_GET['ajaxrequest'] == 'getbarcontent' )
{
	$messagequery = $db->query( "SELECT * FROM taverns_messages WHERE location_id=" . $char->location_nr . " AND DATEDIFF(CURDATE(), date) < 2 ORDER BY id DESC LIMIT 0,30" );

	while ( $message = $db->fetch_array( $messagequery ) )
	{
		?>
		<font face='verdana' size='1'>
		<? if ( ( getUserRights() >= USER_MODERATOR ) || ( $business['owner_id'] == $char->getCharacterID() && !$gov->FreeSpeechBool ) ) { ?>
		<a title="Delete Message" style='text-decoration: none;' href='<?=$PHP_SELF;?>?delmsg=<?=$message['id'];?>'>
			<img src='<?=$rootdir;?>/images/icon_delete.gif' border='0' />
		</a><? } ?>
		<? 
		if ( $message['type'] == 0 ) 
		{
			if ( $message['char_id'] == $char->getCharacterID() ) { ?><font color='#812E3F'><? } ?><b><?=getCharNickname( $message['char_id'] );?></b><? if ( $message['char_id'] == $char->getCharacterID() ): ?></font><? endif; ?>:
			<?=addEmoticons( addChatFormatting( $message['message'] ), $rootdir );?><br /></font>
			<?
		}
		elseif ( $message['type'] == 1 )
		{
			?>
			<font color='#993300'><i><?=getCharNickname( $message['char_id'] ) . " " . addEmoticons( $message['message'], $rootdir ); ?></i><br /></font>
			<?
		}
	}

	exit;
}

/* This page gets a custom header... */
?>

<html>

<head>

	<script type="text/javascript" src="../javascript/xmlhttprequest.js"></script>

</head>

<body bgcolor='#FFFFFF' text='#000000'>

	<div id="barcontent">
	</div>

	<script type="text/javascript">
	function getBarContent()
	{
		var ajax = new AjaxConnection( response );
		ajax.setScriptUrl( 'bar.php' );
		ajax.addParam( 'ajaxrequest', 'getbarcontent' );
		ajax.send( null );

		function response( text )
		{
			document.getElementById('barcontent').innerHTML = "";
			document.getElementById('barcontent').innerHTML = text;
			setTimeout( "getBarContent()", 5000 );
		}
	}
	
	getBarContent();	
	</script>

</body>