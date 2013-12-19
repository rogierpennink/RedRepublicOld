<?
/**
 * The main.php file is the first page into the game itself. It has to check for a character
 * and if there is, set the 'ingame' session...
 */
$nav = "main";
$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = true;
include "includes/utility.inc.php";
$char = new Player( getUserID() );
if ( !$char->isAlive() )
{
	$_SESSION['error'] = "You have to be alive in order to play your character!";
	header( "Location: account.php" );
	exit;
}

$_SESSION['ingame'] = true;

$config = $db->query( "SELECT value FROM settings_general WHERE setting='ircserver'" );
$configEx = $db->query( "SELECT value FROM settings_general WHERE setting='ircchannel'" );
$configA = $db->fetch_array( $config );
$configAEx = $db->fetch_array( $configEx );

$servername = $configA['value'];
$channelname = $configAEx['value'];

// Start the real main page
include_once "includes/header.php";
?>

<h1>Welcome to the world of Red Republic</h1>
<p>Welcome to the world of Red Republic, <?=$char->firstname;?>, whether you're a veteran or a rookie, adventures are waiting for you already! If you are new to this game, please take your time to read some of the suggestions below.</p>

<div class="title">
	<div style="margin-left: 10px;">Meet other players at IRC!</div>
</div>
<div class="content">
Though many contacts are made through the game interface, many more contacts are made over IRC, a realtime chatting protocol. We are located on the server <?=$servername;?> in channel <?=$channelname;?>. If you haven't used IRC before, you can take <a href="playerguides.php?id=3">this tutorial</a> to get going with it!
</div>

<div class="title">
	<div style="margin-left: 10px;">Donate to Red Republic</div>
</div>
<div class="content">
Providing a good game and making it run smoothly is fun to do. We will always try to provide you with fun content and put all our efforts into making this game as enjoyable as possible, for you, the player. At the same time this costs a lot of time as well, and more importantly, money. We will soon have to move to dedicated hosting with increasing bandwidth and storage space and keeping the game alive really asks a lot of us. You can show how much you like the game by helping it staying alive and donating to us.
</div>
<?
include_once "includes/footer.php";
?>