<?
$nav = "admin";
/**
 * The index.php file is the first page into the game itself. Admin page only
 */

$AUTH_LEVEL = 8;
$REDIRECT = true;
include "../includes/utility.inc.php";

unset( $_SESSION['ingame'] );
$_SESSION['inadmin'] = true;

/* Auto MySQL Update */
$aud = $db->getRowsFor( "SELECT value FROM settings_general WHERE setting='autoupdate'" );

if ( ( $_SERVER["SERVER_ADDR"] == "127.0.0.1" && $aud['value'] == 'true' ) || ( isset( $_GET['sqlupdate'] ) ) )
{
	$fp = fopen( "../dbdumplast.sql", "r" );
	$fpex = fopen( "../dbdump.sql", "r" );
	$sgc = stream_get_contents( $fp );
	$sgcex = stream_get_contents( $fpex );
	if ( $sgc != $sgcex )
	{
		// Temporary variable, used to store current query
		$res = true;
		$templine = '';

		// Read in entire file
		$lines = file( "../dbdump.sql" );

		// Loop through each line
		foreach ( $lines as $line_num => $line ) {
			// Only continue if it's not a comment
			if ( substr( $line, 0, 2 ) != '--' && $line != '' ) {
				// Add this line to the current segment
				$templine .= $line;

				// If it has a semicolon at the end, it's the end of the query
				if ( substr( trim( $line ), -1, 1 ) == ';' ) {
					// Perform the query
					if ( !$db->query( $templine ) ) $res = false;

					// Reset temp variable to empty
					$templine = '';
				}
			}
		} 

		fclose( $fp );
		fclose( $fpex );

		if ( $res )
		{
			$_SESSION['notify'] = 'Your database has been updated. More information <a href="' . $rootdir . '/docs.php?type=autosqlupdate" style="text-decoration: none;">here</a>.';
			adminLog( $_SESSION['username'], "Updated the MySQL Database." );
			if ( !copy( '../dbdump.sql', '../dbdumplast.sql' ) )
			{
				$_SESSION['notify'] = 'Your database has been updated, however the database dump could not be written to dbdumplast.sql.';
			}
		}

		if ( !$res ) $_SESSION['error'] = "The query to update your database (dbdump.sql) was invalid.";
	}
}

/* Cleanup Operations */
if ( isset( $_POST['cleanup'] ) )
{
	// Start the Purification
	if ( isset( $_POST['communications'] ) )
	{
		// Delete all communications
		$result = $db->query( "DELETE FROM comms WHERE DATEDIFF(CURDATE(), date) > 15 AND comm_new=0 AND (comm_type=1 OR comm_type=0)" );
		if ( !$result ) $_SESSION["error"] = "Error deleting communications from database.";
	}
	
	if ( isset( $_POST['events'] ) )
	{
		// Delete all events
		$result = $db->query( "DELETE FROM events WHERE DATEDIFF(CURDATE(), date) > 15  AND event_new=0 AND event_type=0" );
		if ( !$result ) $_SESSION["error"] = "Error deleting event reports from database.";
	}

	if ( isset( $_POST['largeevents'] ) )
	{
		// Delete all large events
		$result = $db->query( "DELETE FROM events_large WHERE DATEDIFF(CURDATE(), date) > 30 AND event_new=0 AND death_message=0" );
		if ( !$result ) $_SESSION["error"] = "Error deleting large event reports from database.";
	}

	if ( isset( $_POST['posttrackers'] ) )
	{
		// Delete all post trackers
		$result = $db->query( "DELETE FROM forums_posttracker" );
		if ( !$result ) $_SESSION["error"] = "Error deleting forum post trackers from database.";
	}

	if ( isset( $_POST['auctions'] ) )
	{
		// Delete all auctions
		$result = $db->query( "DELETE FROM auctions" );
		if ( !$result ) $_SESSION["error"] = "Error deleting auctions from database.";
	}

	if ( isset( $_POST['taverns'] ) )
	{
		// Delete all old tavern messages
		$result = $db->query( "DELETE FROM taverns_messages WHERE DATEDIFF(CURDATE(), date) > 2" );
		if ( !$result ) $_SESSION["error"] = "Error deleting tavern messages from database.";
	}

	if ( isset( $_POST['pollvoters'] ) )
	{
		// Delete all poll voter rows
		$result = $db->query( "DELETE FROM polls_voters" );
		if ( !$result ) $_SESSION["error"] = "Error deleting voter entries from the database.";
	}

	if ( strlen( $_SESSION["error"] ) == 0 )
	{
		$_SESSION["notify"] = "The database has been purged.";
		adminLog( $_SESSION['username'], "Performed a cleanup operation on the database." );
	}
}

/* Save database dump */
if ( isset( $_POST['savedump'] ) )
{
	//databasedump, savefile
	$filename = $_POST['savefile'];
	$data = $_POST['databasedump'];

	$fp = fopen( $filename, "w" );

	if ( !$fp )
	{
		$_SESSION["error"] = "Could not open " . $filename . " for writing.";
	}
	else
	{
		if ( fwrite( $fp, stripslashes( $data ) ) === false )
			$_SESSION["error"] = "The database dump was not written.";
		else
		{
			$_SESSION["notify"] = "The database dump was saved.";
			adminLog( $_SESSION['username'], "Saved a database dump to <i>" . shortstr( $filename ) . "</i>." );
		}
	}
}

/* Admin Log Operations */
$showfulllog = false;

if ( isset( $_GET['act'] ) && getUserRights() >= USER_SUPERADMIN )
{
	if ( $_GET['act'] == 'showlog' )
		$showfulllog = true;

	if ( $_GET['act'] == 'clearlog' )
	{
		$loghandle = fopen( "../logs/admin_log.txt", "w" );

		if ( !$loghandle )
			$_SESSION["error"] = "Error opening admin log for clearing operation.";
		else
		{
			fwrite( $loghandle, "" );
			fclose( $loghandle );
			$_SESSION["notify"] = "The administration log has been cleared.";
			adminLog( $_SESSION['username'], "Cleared the administration log." );
		}
	}
}

/* Get recent activities from a flat file, explode on \n */
if ( !file_exists( "../logs/admin_log.txt" ) )
{
	$adminlog = fopen( "../logs/admin_log.txt", "w" );
	fclose( $adminlog );
}

$adminloghandle = fopen( "../logs/admin_log.txt", "r" );

if ( !$adminloghandle )
	$_SESSION["error"] = "Error opening the administration log file.";

$adminlogcontents = fread( $adminloghandle, filesize( "../logs/admin_log.txt" ) );

$adminlog = explode( "\n", $adminlogcontents );

/* Change User Rights */
if ( isset( $_POST['changerights'] ) )
{
	if ( $_POST['rights'] == 'null' )
		$_SESSION["error"] = "You must select a level to change the user to.";
	else
	{
		$admchk = $db->query( "SELECT id FROM accounts WHERE username=" . $db->prepval( $_POST['username'] ) );
		if ( $db->getRowCount( $admchk ) == 0 )
			$_SESSION["error"] = "Cannot change the rights of a user who does not exist (" . $_POST['username'] . ").";
		else
		{
			$db->query( "UPDATE accounts SET rights=" . $_POST['rights'] . " WHERE username=" . $db->prepval( $_POST['username'] ) );
			$_SESSION["notify"] = $_POST['username'] . "'s rights have been updated to " . $user_rights[$_POST['rights']] . ".";
		}
	}
}

// Start the real main page
include_once "../includes/header.php";
?>

<h1>Administration Panel</h1>
<p>This is the index to the administration panel.<br />
<? if ( $_SERVER["SERVER_ADDR"] == "127.0.0.1" ): ?>It is detected that you are working from localhost. If you would like to import the latest <a style='text-decoration: none;' href='<?=$rootdir;?>/dbdump.sql'>database dump</a> then <a style='text-decoration: none;' href='<?=$rootdir;?>/admin/index.php?sqlupdate=true'>click here</a>.<? endif; ?>
</p>

<!-- Main Body Starting -->
<div class="title">
	<div style="margin-left: 10px;">Administration Main</div>
</div>
<div class="content">
	<td style="width: 50%;">
		<table style="width: 100%;">
			<tr>
				<td style="width: 40px; text-align: left;">
					<img src="<?=$rootdir;?>/images/forums_old.png" alt="" style="height: 32px; width: 32px;" />
				</td>
				<td>
					<a href="<?=$rootdir;?>/admin/forumsmanager.php"><strong>Forum Manager</strong></a><br />
					Manage and modify the message board.
				</td>
				<td style="width: 40px; text-align: left;">
					<img src="<?=$rootdir;?>/images/forums_old.png" alt="" style="height: 32px; width: 32px;" />
				</td>
				<td>
					<a href="<?=$rootdir;?>/admin/usermanager.php"><strong>User Management</strong></a><br />
					Manager user accounts and options.
				</td>
			</tr>
			<!-- NEW ROW -->
			<tr>
				<td style="width: 40px; text-align: left;">
					<img src="<?=$rootdir;?>/images/forums_old.png" alt="" style="height: 32px; width: 32px;" />
				</td>
				<td>
					<a href="<?=$rootdir;?>/admin/newsmanagement.php"><strong>News System</strong></a><br />
					Create frontpage news.
				</td>
				<td style="width: 40px; text-align: left;">
					<img src="<?=$rootdir;?>/images/forums_old.png" alt="" style="height: 32px; width: 32px;" />
				</td>
				<td>
					<a href="<?=$rootdir;?>/admin/config.php"><strong>Game Configuration</strong></a><br />
					Edit the various settings and configurations.
				</td>
			</tr>
			<!-- NEW ROW -->
			<tr>
				<td style="width: 40px; text-align: left;">
					<img src="<?=$rootdir;?>/images/forums_old.png" alt="" style="height: 32px; width: 32px;" />
				</td>
				<td>
					<a href="<?=$rootdir;?>/admin/businessmanagement.php"><strong>Business Editor</strong></a><br />
					Create and edit businesses for the local cities.
				</td>
				<td style="width: 40px; text-align: left;">
					<img src="<?=$rootdir;?>/images/forums_old.png" alt="" style="height: 32px; width: 32px;" />
				</td>
				<td>
					<a href="<?=$rootdir;?>/admin/itemmanagement.php"><strong>Item Editor</strong></a><br />
					Create and edit user items.
				</td>
			</tr>
			<!-- NEW ROW -->
			<tr>
				<td style="width: 40px; text-align: left;">
					<img src="<?=$rootdir;?>/images/forums_old.png" alt="" style="height: 32px; width: 32px;" />
				</td>
				<td>
					<a href="<?=$rootdir;?>/admin/deedmanagement.php"><strong>Vices and Virtues</strong></a><br />
					Manage character personalities and deeds through this page.
				</td>
				<td style="width: 40px; text-align: left;">
					<img src="<?=$rootdir;?>/images/forums_old.png" alt="" style="height: 32px; width: 32px;" />
				</td>
				<td>
					<a href="<?=$rootdir;?>/admin/pollmanagement.php"><strong>Poll Management</strong></a><br />
					Edit the sites current poll.
				</td>
			</tr>
			<!-- NEW ROW -->
			<tr>
				<td style="width: 40px; text-align: left;">
					<img src="<?=$rootdir;?>/images/forums_old.png" alt="" style="height: 32px; width: 32px;" />
				</td>
				<td>
					<a href="<?=$rootdir;?>/admin/supportmanagement.php"><strong>Support Management</strong></a><br />
					Manage the FAQ book. Create, edit, or delete questions and answers.
				</td>
				<td style="width: 40px; text-align: left;">
					<!-- Empty Image Container -->
				</td>
				<td>
					<!-- Empty Text Container -->
				</td>
			</tr>
		</table>
	</td>
</div>

<!-- Secondary Body Starting... -->
<table style="width: 100%;">
	<tr>
		<td style="width: 40%; vertical-align: top;">
			<div style="width: 100%;">
				<div class="title">
					<div style="margin-left: 10px;">Cleanup Operations</div>
				</div>
				<div class="content" style="height: 250px;">
					Select the following tables you would like to clean.
					<form method='post' action='<?=$rootdir;?>/admin/index.php'>
					<table>
						<tr>
							<td style="width: 10%;"><input type="checkbox" name="communications" /></td>
							<td style="width: 90%;">Communications</td>
						</tr>
						<tr>
							<td style="width: 10%;"><input type="checkbox" name="events" /></td>
							<td style="width: 90%;">Event Reports</td>
						</tr>
						<tr>
							<td style="width: 10%;"><input type="checkbox" name="largeevents" /></td>
							<td style="width: 90%;">Large Events</td>
						</tr>
						<tr>
							<td style="width: 10%;"><input type="checkbox" name="posttrackers" /></td>
							<td style="width: 90%;">Forum Post Trackers *</td>
						</tr>
						<tr>
							<td style="width: 10%;"><input type="checkbox" name="auctions" /></td>
							<td style="width: 90%;">Auctions *</td>
						</tr>
						<tr>
							<td style="width: 10%;"><input type="checkbox" name="taverns" /></td>
							<td style="width: 90%;">Tavern Messages</td>
						</tr>
						<tr>
							<td style="width: 10%;"><input type="checkbox" name="pollvoters" /></td>
							<td style="width: 90%;">Voters (Polls) *</td>
						</tr>
						<tr>
							<td style="width: 10%;"></td>
							<td style="width: 90%;"><input name="cleanup" type="submit" class="std_input" value="Cleanup" /></td>
						</tr>
						<tr><td style="width: 10%;"></td><td style="width: 90%;"></td></tr>
						<tr>
							<td style="width: 10%;"></td>
							<td style="width: 90%;"><div style="font-size: 10px;">If applicable the cleanup utility will only delete old and viewed items in the database, however, tables marked with an asterisk (*) will be purged entirely.</div></td>
						</tr>
					</table>
					</form>
				</div>
			</div>
		</td>
		<td style="width: 40%; vertical-align: top;">
			<div style="width: 100%;">
				<div class="title">
					<div style="margin-left: 10px;">Database Dump</div>
				</div>
				<div class="content" style="height: 250px;">
					<form method='post' action='<?=$PHP_SELF;?>'>
						<textarea name='databasedump' cols='110' rows='16' style="font-family: tahoma; font-size: 10px;" class="std_input"><? include( "databasedumper.php" ); ?></textarea>
					<br />
					<input type='submit' class='std_input' value='Save to...' name='savedump' />&nbsp;<input type='text' class='std_input' value='../dbdump.sql' name='savefile' />
					</form>
				</div>
			</div>
		</td>
	</tr>
</table>

<!-- Recent Activity Table -->
<div class="title">
	<div style="margin-left: 10px;">Recent Activity</div>
</div>
<div class="content" style="padding: 0px;">
	<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
		<table class="row">
			<tr>
				<td class="field" style="width: 25%;"><strong>User</strong></td>
				<td class="field" style="width: 55%;"><strong>Description</strong></td>
				<td class="field" style="width: 20%;"><strong>Date</strong></td>
			</tr>
		</table>
	</div>
	<?
	$i = count( $adminlog ) - 2;

	if ( !$showfulllog )
		$clause = count( $adminlog )-8;
	else
		$clause = -1;

	while ( ( $i > $clause ) && ( $i > -1 ) )
	{
		$exp = explode( "::", $adminlog[$i] );
		?>
		<div class="row">
			<table class="row">
				<tr>
					<td class="field" style="width: 25%;"><?=$exp[0];?></td>
					<td class="field" style="width: 55%;"><?=shortstr( $exp[1] );?></td>
					<td class="field" style="width: 20%;"><?=$exp[2];?></td>
				</tr>
			</table>
		</div>
		<?
		$i--;
	}
	?>
	<div class="row">
		<table class="row">
			<tr>
				<? if ( getUserRights() >= USER_SUPERADMIN ) { ?><td class="field" style="width: 100%;"><a style='text-decoration: none;' href='<?=$rootdir;?>/admin/index.php?act=clearlog'><img style="border: none;" src="<?=$rootdir;?>/images/icon_delete.gif" alt="" title="Clear log?" /> Clear Admin Log</a> | 
				<? } ?><a style='text-decoration: none;' href='<?=$rootdir;?>/admin/index.php?act=showlog'><img style="border: none;" src="<?=$rootdir;?>/images/icon_read.png" alt="" title="Show Full Log" /> Show Full Log</a></td>
			</tr>
		</table>
	</div>
</div>

<!-- Admin Ranks Table -->
<center>
	<div style="width: 60%;">
		<div class="title">
			<div style="margin-left: 10px;">Administration Hierarchy</div>
		</div>
		<div class="content">
			<?
			$i = 10;
			while ( $i != 0 )
			{
				$admlayout = $db->query( "SELECT username FROM accounts WHERE rights=" . $i );
				
				if ( $db->getRowCount( $admlayout ) > 0 )
				{
					?>
					<table align="center">
						<tr>
							<?
							while ( $admin = $db->fetch_array( $admlayout ) )
							{
								?>
								<td align="center"><img style="border: 0px;" alt="Level <?=$i;?>" title="Level <?=$i;?> Administrator" src="<?=$rootdir;?>/images/valour_<?=$i;?>.png" /><br /><?=$admin['username'];?></td><td align="center">&nbsp;</td>
								<?
							}
							?>
						</tr>
					</table>
					<?
				}
				
				$i--;
			}
			?>
			<br /><br />
			<form method='post' action='<?=$rootdir;?>/admin/index.php'>
				Set <input class='std_input' name='username' value='username...' style='font-size: 11px;' /> rights to <select name='rights' class='std_input' style='font-size: 11px;' /><option value='null'>--- Select Level ---</option>
				<?
				$i = count( $user_rights );
				while ( $i != 0 )
				{
					if ( strlen( $user_rights[$i] ) != 0 && ( getUserRights() > $i || getUserRights() == USER_SUPERADMIN ) )
					{
						?><option value='<?=$i;?>'><?=$user_rights[$i];?></option><?
					}
					$i--;
				}
				?>
				</select>
				<input type='submit' class='std_input' value='Go' name='changerights' />
			</form>
		</div>
	</div>
</center>

<div>&nbsp;</div>
<?
include_once "../includes/footer.php";
?>