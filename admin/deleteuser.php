<?
/**
 * usermanager.php - Obviously allows us to edit users :)
 * 
 * Author:	Aaron Amann
 * Date:	08/03/2007
 */

$AUTH_LEVEL = 9;
$REDIRECT = true;
include_once "../includes/utility.inc.php";

if ( isset( $_POST['submit'] ) )
{
	switch( $_POST['confirm'] )
	{
		case '----': 
			header( "location: usermanager.php" ); 
			exit; 
			break;
		case 'Yes': 
			$char = new Player( $_GET['id'] );
			$db->query( "DELETE FROM accounts WHERE id=" . $db->prepval( $_GET['id'] ) );
			$db->query( "DELETE FROM char_characters WHERE id=" . $char->character_id );
			$db->query( "DELETE FROM char_stats WHERE id=". $char->stats_id );
			$db->query( "DELETE FROM char_equip WHERE char_id=". $char->character_id );
			$db->query( "DELETE FROM char_inventory WHERE char_id=". $char->character_id );
			$db->query( "DELETE FROM char_vehicles WHERE id=". $char->character_id );
			$db->query( "DELETE FROM comms WHERE to=". $char->character_id );
			$db->query( "DELETE FROM events WHERE char_id=". $char->character_id );
			$db->query( "DELETE FROM events_large WHERE char_id=". $char->character_id );

			adminLog( $_SESSION['username'], "Has deleted a user (id: " . $_GET['id'] . ")." );

			break;
	}
}

if ( isset ( $_GET['ajaxrequest'] ) && $_GET['ajaxrequest'] != "" )
{
	if ( !user_auth() )
	{
		echo "error::notloggedin";
	}
	elseif ( getUserRights() < USER_SUPERADMIN )
	{
		echo "error::accessdenied";
	}
	else
	{
		echo "error::An unknown request was received, no response output.";
	}
	
	exit;
}

if ( !isset( $_GET['id'] ) )
{
	$_SESSION['error'] = "ID was not set. Redirected.";
	header( "Location: usermanager.php" );
	exit;
}

$ext_style = "forums_style.css";
include_once "../includes/header.php";

?>

	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>User Management</h1>
	<p>Here you can delete someone's life, please be careful when playing god.</p>

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">Delete</div>
	</div>
	<div class="content" style="padding: 0px;">
		<table style="width: 90%"><tr><td>
			<form method="post" action="<?=$PHP_SELF;?>">
			<table style="width: 100%">
				<tr>
					<td style="width: 18%">User ID: </td>
					<td><input type='text' class='std_input' name='id' readonly='true' size='4' value='<?=$_GET['id'];?>'></td>
				</tr>
				<tr>
					<td style="width: 18%">Are you sure?</td>
					<td>
					<select name='confirm' class='std_input'>
						<option value='----'>----</option>
						<option value='Yes'>Yes</option>
					</select>
					</td>
				</tr>
				<tr>
					<td style="width: 18%;"><input type='submit' class='std_input' name='submit' value='    Confirm    '></td>
					<td>&nbsp;</td>
				</tr>
			</table>
			</form>
		</td></tr></table>
	</div>

<?
include_once "../includes/footer.php";
?>