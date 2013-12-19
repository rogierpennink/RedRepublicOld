<?
/**
 * usermanager.php - Obviously allows us to edit users :)
 * 
 * Author:	Aaron Amann
 * Date:	08/03/2007
 */
// Sneo TEST! 
$AUTH_LEVEL = 9;
$REDIRECT = true;

include_once "../includes/utility.inc.php";

if ( isset( $_POST['submit'] ) )
{	
	$announceQuery = $db->query( "SELECT * FROM announce WHERE announce_id=" . $db->prepval( $_GET['id'] ) );
	if ( $db->getRowCount( $announceQuery ) == 0 )
	{
		$remain = date( "Y-m-d H:i:s", strtotime( $_POST['remain_until'] ) );
		
		$db->query( "INSERT INTO announce (account_id, remain_until, announce_type, announce_text) VALUES (" . $db->prepval( $_GET['id'] ) . ", " . $db->prepval( $remain ) . ", " . $db->prepval( $_POST['announce_type'] ) . ", " . $db->prepval( $_POST['announce_text'] ) . ")" );
	}

	$_SESSION['notify'] = "Announcement was successfully added!";
	header( "Location: usermanager.php" );
	exit;
}

// Check if the ID is valid
if ( $db->getRowCount( ( $rec = $db->query( "SELECT * FROM accounts WHERE id=". $db->prepval( $_GET['id'] ) ) ) ) == 0 )
{
	$_SESSION['error'] = "You cannot set announcements without a valid ID!";
	header( "Location: usermanager.php" );
	exit;
}

$account = $db->fetch_array( $rec );

$ext_style = "forums_style.css";
include_once "../includes/header.php";

?>

	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>User Management</h1>
	<p>From here it is possible to make an announcement to a user, once they see it the announcement will be deleted. If a previous announcement is available, and a user hasn't seen it, you will see the message here and will be allowed to make modifications to it.</p>	

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">Set announcement</div>
	</div>
	<div class="content" style="padding: 0px;">
		<table style="width: 90%"><tr><td>
			<form method="post" action="<?=$PHP_SELF;?>">
			<table style="width: 100%">
				<tr>
					<td style="width: 18%"><strong>Account:</strong></td>
					<td><?=$account['username'];?></td>
				</tr>
				<tr>
					<td style="width: 18%"><strong>Announcement Type:</strong></td>
					<td>
					<select name='announce_type' class='std_input'>
						<option value='0'>None</option>
						<option value='1'>Update</option>
						<option value='2'>Warning</option>
					</select>
					</td>
				</tr>
				<tr>
					<td style="width: 18%"><strong>Remain for:</strong></td>
					<td>
					<select name='remain_until' class='std_input'>
						<option value='+1 hour'>1 Hour</option>
						<option value='+2 hours'>2 Hours</option>
						<option value='+6 hours'>6 Hours</option>
						<option value='+12 hours'>12 Hours</option>
						<option value='+1 day'>1 Day</option>
						<option value='+2 days'>2 Days</option>
						<option value='+4 days'>4 Days</option>
						<option value='+1 week'>1 Week</option>
						<option value='+2 weeks'>2 Weeks</option>
						<option value='+1 month'>1 Month</option>
						<option value='+2 months'>2 Months</option>
						<option value='+6 months'>6 Months</option>
						<option value='+1 year'>1 Year</option>
					</select>
					</td>
				</tr>
				<tr>
					<td style="width: 18%; vertical-align: top;"><strong>Text (HTML):</strong></td>
					<td>
					<?
					$checkQuery = $db->query( "SELECT * FROM announce WHERE account_id=" . $db->prepval( $_GET['id'] ) );
					if ( $db->getRowCount( $checkQuery ) == 0 )
					{
						$announceText = "";
					} else {
						$tmpAssoc = $db->fetch_assoc( $checkQuery );
						$announceText = $tmpAssoc['announce_text'];
					}
					?>
					<textarea cols='60' rows='10' class='std_input' name='announce_text'><?=$announceText; ?></textarea>
					</td>
				</tr>
				<tr>
					<td style="width: 18%;">&nbsp;</td><td><input type='submit' class='std_input' name='submit' value='Announce' />&nbsp;&nbsp;<input type='reset' class='std_input' name='reset' value='Reset' /></td>
				</tr>
			</table>
			</form>
		</td></tr></table>
	</div>

<?
include_once "../includes/footer.php";
?>