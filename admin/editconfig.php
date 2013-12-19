<?
/**
 * editconfig.php - Add and edit variables.
 * 
 * Author:	Aaron Amann
 * Date:	08/03/2007
 */

$AUTH_LEVEL = 10;
$REDIRECT = true;
include_once "../includes/utility.inc.php";

if ( isset( $_POST['submit'] ) )
{
	if ( $_POST['type'] != 'include' )
	{
		if ( $_POST['hidden'] == 'new' )
		{
			if ( $_POST['type'] == 'varchar' )
			{
				$table = "settings_general";
			}
			elseif ( $_POST['type'] == 'text' )
			{
				$table = "settings_text";
			}
			$db->query( "INSERT INTO " . $table . " (setting, value, formal, description, hidden) VALUES (" . $db->prepval( $_POST['setting'] ) . ", " . $db->prepval( $_POST['value'] ) . ", " . $db->prepval( $_POST['formal'] ) . ", " . $db->prepval( $_POST['description'] ) . ", '" . $_POST['ishidden'] . "')" );

			adminLog( $_SESSION['username'], "Added a new " . $table . " configuration variable (setting: " . $_POST['setting'] . ")." );

			header( "location: config.php" );
			exit;
		}
		else 
		{
			if ( $_POST['type'] == 'varchar' )
			{
				$table = "settings_general";
			}
			elseif ( $_POST['type'] == 'text' )
			{
				$table = "settings_text";
			}
			$db->query( "UPDATE " . $table . " SET value=" . $db->prepval( $_POST['value'] ) . ", formal=" . $db->prepval( $_POST['formal'] ) . ", description=" . $db->prepval( $_POST['description'] ) . ", hidden='" . $_POST['ishidden'] . "' WHERE setting=" . $db->prepval( $_POST['setting'] ) );
			header( "location: config.php" );
			exit;
		}
	}
	else
	{
		if ( $_POST['hidden'] == 'new' )
		{
			$dbQuery = $db->query( "INSERT INTO settings_include (filename, formal, description) VALUES (" . $db->prepval( $_POST['setting'] ) . ", " . $db->prepval( $_POST['formal'] ) . ", " . $db->prepval( $_POST['description'] ) . ", '" . $_POST['ishidden'] . "')" );

			adminLog( $_SESSION['username'], "Added a new include configuration variable titled," . $_POST['setting'] . "." );

			header( "location: config.php" );
			exit;
		} 
		else
		{
			$dbQuery = $db->query( "UPDATE settings_include SET filename=" . $db->prepval( $_POST['setting'] ) . ", formal=" . $db->prepval( $_POST['formal'] ) . ", description=" . $db->prepval( $_POST['description'] ) . " hidden='" . $_POST['ishidden'] . "' WHERE filename=" . $db->prepval( $_POST['setting'] ) );
			header( "location: config.php" );
			exit;
		}
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

if ( !isset( $_GET['var'] ) || !isset( $_GET['type'] ) )
{
	$_SESSION['error'] = "ID or Type was not set. Redirected.";
	header( "Location: config.php" );
	exit;
}

$ext_style = "forums_style.css";
include_once "../includes/header.php";

?>

	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>Variable Editor</h1>
	<p>Here you can create a new variable, or modify an existing one.<br /><strong>Do not overwrite or copy variable names!</strong><br /></p>

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">Variable Editor</div>
	</div>
	<div class="content" style="padding: 0px;">
		<table style="width: 90%"><tr><td>
			<?
			if ( $_GET['type'] == 'varchar' )
			{
				$varQuery = $db->query( "SELECT * FROM settings_general WHERE setting=" . $db->prepval( $_GET['var'] ) );
			} 
			elseif ( $_GET['type'] == 'text' )
			{
				$varQuery = $db->query( "SELECT * FROM settings_text WHERE setting=" . $db->prepval( $_GET['var'] ) );
			}
			elseif ( $_GET['type'] == 'include' )
			{
				$varQuery = $db->query( "SELECT * FROM settings_include WHERE filename=" . $db->prepval( $_GET['filename'] ) );
			}

			if ( $_GET['var'] == '0' )
			{
				$postType = 'new';
			} 
			else 
			{
				$postType = 'edit';
				$varAssoc = $db->fetch_assoc( $varQuery );
			}
				?>
				<form method="post" action="<?=$PHP_SELF;?>">
				<input type='hidden' name='hidden' value='<?=$postType;?>' />
				<input type='hidden' name='hidden2' value='<?=$_GET['type'];?>' />
				<table style="width: 100%">
					<tr>
						<td style="width: 22%">Type: </td>
						<td><input type='text' class='std_input' readonly='true' name='type' value='<?=$_GET['type'];?>' /></td>
					</tr>
					<tr>
						<td style="width: 22%"><? if ( $_GET['type'] == 'include' ) { ?>* Filename: <? } else { ?>* Variable Name: <? } ?></td>
						<td><input size='40' type='text' class='std_input' name='setting' value='<?
						if ( $postType != 'new' )
						{
							print( $_GET['var'] );
						}
						?>' 
						<?
						if ( $postType != 'new' )
						{
							?>
							readonly='true'
							<?
						}
						?> /></td>
					</tr>
					<tr>
						<td style="width: 22%">Formal Name: </td>
						<td><input size='40' type='text' class='std_input' name='formal' value='<?
						if ( $postType != 'new' )
						{
							print( $varAssoc['formal'] );
						}
						else
						{
							print( "Category - Short Descript." );
						}
						?>' /></td>
					</tr>
					<tr>
						<td style="width: 22%">Description: </td>
						<td>
							<input size='40' class='std_input' name='description' type='text' value='<?
							if ( $postType != 'new' )
							{
								print( $varAssoc['description'] );
							}
							?>' />
						</td>
					</tr>
					<?
					if ( $_GET['type'] != 'include' )
					{
						?>
						<tr>
							<td style="width: 22%">* Value: </td>
							<td>
							<?
							if ( $_GET['type'] == 'varchar' )
							{
								?>
								<input size='40' type='text' class='std_input' name='value' value='<?
								if ( $postType != 'new' )
								{
									print( $varAssoc['value'] );
								}
								?>' />
								<?
							}
							elseif ( $_GET['type'] == 'text' )
							{
								?>
								<textarea class='std_input' name='value' cols='40' rows='10'><?
								if ( $postType != 'new' )
								{
									print( $varAssoc['value'] );
								}
								?></textarea>
								<?
							}
							?>
							</td>
						</tr>
					<?
					}
					?>
					<tr>
					<tr>
						<td style="width: 22%">* Hidden: </td>
						<td><select name='ishidden' class='std_input'><option name='false' value='false'>false</option><option name='true' value='true'>true</option></select></td>
					</tr>
						<td style="width: 22%;"><input type='submit' class='std_input' name='submit' value='    Done    '></td>
						<td><input type='reset' class='std_input' name='reset' value='    Reset    '></td>
					</tr>
				</table>
				</form>
		</td></tr></table>
	</div>

<?
include_once "../includes/footer.php";
?>