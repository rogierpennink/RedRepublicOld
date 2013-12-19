<?
$nav = "conflict";
$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = true;
include "../includes/utility.inc.php";
$char = new Player( getUserID() );

include "../includes/header.php";
?>
<h1>Conflicts</h1>
<p>Is your country at war and have you been called to arms? Is your crew at war and are you planning a vicious drive-by? Or do you just want to warn someone with more than feeble threats? If you feel that the time of negotiations is really over, you can try your luck here. You'll find a wide array of resourceful manners all intended to hurt people. Hurt them, to the point of death...</p>

<?
if ( isset ( $_POST['domurder'] ) )
{
	?><p <? echo ( ( $_GET['act'] != "murder" )? "style='width: 80%; margin-left: auto; margin-right: auto;'" : "" ); ?>><strong><?

	if ( $char->murder_timer > time() )
		echo "You cannot murder so soon again! Have a little more patience!";
	else
		include_once "conflict.murderhandler.php";

	?></strong></p><?	
}
?>

<?
switch ( $_GET['act'] )
{
	case "murder": 
		if ( $char->murder_timer > time() ) 
		{
			echo "<p><strong>You cannot murder so soon again! Have a little more patience!</strong></p>"; 
			include_once "conflict.default.php"; 
		}
		else 
		{ 
			include_once "conflict.murder.php"; 
		} 
	break;
	case "assault": include_once "conflict.assault.php"; break;
	default: include_once "conflict.default.php";
}
include "../includes/footer.php";
?>