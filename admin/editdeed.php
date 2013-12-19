<?
/**
 * Edit personality trait
 */
$nav = "deedman";
$AUTH_LEVEL = 10;
$REDIRECT = true;
include_once "../includes/utility.inc.php";

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
	header( "Location: " . $rootdir . "/admin/index.php" );
	exit();
}

if ( isset( $_POST['submit'] ) )
{
	if ( !isset( $_POST['slot'] ) )
	{
		$_SESSION["error"] = "Slot needs to be set in order to add a trait.";
		header( "location: " . $rootdir . "/admin/index.php?row=" . $_GET['row'] );
		exit();
	}

	if ( !isset( $_POST['value'] ) || !is_numeric( $_POST['value'] ) )
	{
		$_SESSION["error"] = "Value Required must be set, and it must be numeric.";
		header( "location: " . $rootdir . "/admin/newdeed.php?row=" . $_GET['row'] );
		exit();
	}

	if ( !isset( $_POST['name'] ) || !isset( $_POST['description'] ) )
	{
		$_SESSION["error"] = "A name and description must be set for the trait.";
		header( "location: " . $rootdir . "/admin/newdeed.php?row=" . $_GET['row'] );
		exit();
	}

	if ( isset( $_POST['secret'] ) )
		$secret = 'true';
	else
		$secret = 'false';

	/* All clear for update */
	$db->query( "UPDATE personalities SET value_required=" . $_POST['value'] . ", name=" . $db->prepval( $_POST["name"] ) . ", description=" . $db->prepval( $_POST['description'] ) . ", secret='" . $secret . "' WHERE id=" . $_GET["id"] );
	$_SESSION["notify"] = "Updated, " . $_POST["name"] . ".";
	header( "location: " . $rootdir . "/admin/deedmanagement.php" );
}

$pquery = $db->query( "SELECT * FROM personalities WHERE id=" . $_GET['id'] );

if ( $db->getRowCount( $pquery ) == 0 )
{
	$_SESSION["error"] = "ID was invalid. Redirected.";
	header( "location: " . $rootdir . "/admin/index.php" );
	exit();
} else {
	$passoc = $db->fetch_array( $pquery );
}

$ext_style = "forums_style.css";
include_once "../includes/header.php";

?>

	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>Edit Personality Trait</h1>
	<p>Here is where you can edit a personality trait for ID <?=$_GET['id'];?>.<br />
	<br />
	<font color='red'>Some Reminders</font>: The field, Value Required is an integer that resembles how many deed points is needed to get this (generally only one point is added per <i>trigger</i>, which is based on a random chance of getting it). If the value is higher than another trait's value in this same slot (or category) then this value will be achieved as a second title, or third title, or whatever position its in. Also, remember not to use specific words like "he" or "her", these traits can be obtained by everyone so it needs to be able to apply to everyone.<br /><br />
	</p>

	<!-- Category management first -->
	<div style="width: 70%;">
	<div class="title">
		<div style="margin-left: 10px;">Trait Editor</div>
	</div>
	<div class="content" style="padding: 0px;">
		<table style="width: 90%"><tr><td>
				<form method="post" action="<?=$PHP_SELF;?>">
				<table style="width: 100%">
					<tr>
						<td style="width: 18%">Slot Required: </td>
						<td><input type='text' class='std_input' name='slot' readonly='true' size='4' value='<?=$passoc['slot_required'];?>' /></td>
					</tr>
					<tr>
						<td style="width: 18%">Value Required: </td>
						<td><input type='text' class='std_input' name='value' size='4' value='<?=$passoc['value_required'];?>' /></td>
					</tr>
					<tr>
						<td style="width: 18%">Name: </td>
						<td><input type='text' class='std_input' name='name' size='60' value='<?=$passoc['name'];?>' /></td>
					</tr>
					<tr>
						<td style="width: 18%; vertical-align: top;">Description: </td>
						<td>
							<textarea class='std_input' name='description' cols='60' rows='9'><?=$passoc['description'];?></textarea>
						</td>
					</tr>
					<tr>
						<td style="width: 18%">Secret:</td>
						<td><input type='checkbox' class='std_input' name='secret' size='60' <? if ( $passoc['secret'] == 'true' ) { ?>checked='true' <? } ?> /></td>
					</tr>
					<tr>
						<td style="width: 18%;"><input type='submit' class='std_input' name='submit' value='    Edit    '></td>
						<td><input type='reset' class='std_input' name='reset' value='    Reset    '></td>
					</tr>
				</table>
				</form>
		</td></tr></table>
	</div>
	</div>

<?
include_once "../includes/footer.php";
?>