<?
/**
 * usermanager.php - Obviously allows us to edit users :)
 * 
 * Author:	Aaron Amann
 * Date:	08/03/2007
 */
$AUTH_LEVEL = 8;
$REDIRECT = true;
include_once "../includes/utility.inc.php";

if ( isset( $_POST['submit'] ) )
{
	$db->query( "UPDATE accounts SET username=" . $db->prepval( $_POST['username'] ) . ", person_id=" . $db->prepval( $_POST['person_id'] ) . ", character_id=" . $db->prepval( $_POST['character_id'] ) . ", rights=" . $db->prepval( $_POST['rights'] ) . " WHERE id=" . $db->prepval( $_GET['id'] ) );
	if ( strlen( $_POST['password'] ) > 0 )
	{
		$db->query( "UPDATE accounts SET password=" . $db->prepval( md5( $_POST['password'] ) ) . " WHERE id=" . $db->prepval( $_GET['id'] ) );
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
	<p>Here you can edit an accounts details, if you'd like to edit a character, please refer to the 'Quick Debug' panel for now.</p>

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">User Editor</div>
	</div>
	<div class="content" style="padding: 0px;">
		<table style="width: 90%"><tr><td>
			<?
			$userQuery = $db->query( "SELECT * FROM accounts WHERE id=" . $db->prepval( $_GET['id'] ) );
			if ( $db->getRowCount( $userQuery ) == 0 )
			{
				?>Unknown user.<?
			} 
			else 
			{
				$userAssoc = $db->fetch_assoc( $userQuery );
				?>
				<form method="post" action="<?=$PHP_SELF;?>">
				<table style="width: 100%">
					<tr>
						<td style="width: 18%">User ID: </td>
						<td><input type='text' class='std_input' name='id' readonly='true' size='4' value='<?=$_GET['id'];?>'></td>
					</tr>
					<tr>
						<td style="width: 18%">Username: </td>
						<td><input type='text' class='std_input' name='username' value='<?=$userAssoc['username'];?>'></td>
					</tr>
					<tr>
						<td style="width: 18%">New Password: </td>
						<td><input type='text' class='std_input' name='password' value=''></td>
					</tr>
					<tr>
						<td style="width: 18%">Person ID: </td>
						<td><input type='text' class='std_input' name='person_id' value='<?=$userAssoc['person_id'];?>'></td>
					</tr>
					<tr>
						<td style="width: 18%">Character ID: </td>
						<td><input type='text' class='std_input' name='character_id' value='<?=$userAssoc['character_id'];?>'></td>
					</tr>
					<tr>
						<td style="width: 18%">Rights: </td>
						<td><input type='text' class='std_input' name='rights' value='<?=$userAssoc['rights'];?>' size='2'></td>
					</tr>
					<tr>
						<td style="width: 18%;"><input type='submit' class='std_input' name='submit' value='    Edit    '></td>
						<td><input type='reset' class='std_input' name='reset' value='    Reset    '></td>
					</tr>
				</table>
				</form>
				<?
			}
			?>
		</td></tr></table>
	</div>

<?
include_once "../includes/footer.php";
?>