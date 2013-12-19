<?
/**
 * config.php - Add, edit, remove variables from a database or other source.
 * 
 * Author:	Aaron Amann
 * Date:	08/03/2007
 */
$nav = "configman";
$ext_style = "forums_style.css";
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
	elseif ( $_GET['ajaxrequest'] == "addcat" )
	{
	}
	else
	{
		echo "error::An unknown request was received, no response output.";
	}
	
	exit;
}

if ( isset( $_GET['var'] ) && isset ( $_GET['type'] ) )
{
	switch( $_GET['type'] )
	{
		case 'varchar': $table = 'settings_general'; break;
		case 'text': $table = 'settings_text'; break;
		case 'include': $table = 'null'; break;
	}
	if ( $table != 'null' )
	{
		$db->query( "DELETE FROM " . $table . " WHERE setting=" . $db->prepval( $_GET['var'] ) . " AND hidden='false'" );
		adminLog( $_SESSION['username'], "Deleted a " . $table . " configuration variable (setting: " . $_GET['var'] . ")." );
	} else {
		$db->query( "DELETE FROM settings_include WHERE filename=" . $db->prepval( $_GET['var'] ) );
		adminLog( $_SESSION['username'], "Deleted an include configuration variable (filename: " . $_GET['var'] . ")." );
	}
}

include_once "../includes/header.php";

?>	
	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>Game Configuration</h1>
	<p>From here you can add, edit, and delete variables from a database or other source. This section isn't as detailed as the others and only lists variable names and values for simplicity, it is strongly advised that you take caution when editing, and especially deleting, variables.</p>

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">Database Variables (VARCHAR)</div>
	</div>
	<div class="content" style="padding: 0px;">
		
		<!-- Table for categories -->
		<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
			<table class="row">
				<tr>
					<td class="field" style="width: 50%;"><strong>Setting</strong></td>
					<td class="field" style="width: 30%;"><strong>Value</strong></td>
					<td class="field" style="width: 20%;"><strong>Options</strong></td>
				</tr>
			</table>
		</div>

		<?
		$dataQuery = $db->query( "SELECT * FROM settings_general ORDER BY formal" );
		if ( $db->getRowCount( $dataQuery ) == 0 )
		{
			?>
			<div class="row">
				No variables found.
			</div>
			<?
		}
		else
		{
			while ( $dataArray = $db->fetch_array( $dataQuery ) )
			{
				?>
				<div class="row">
					<table class="row">
						<tr>
							<td class="field" style="width: 50%;"><?=$dataArray['formal'];?> (<a href='editconfig.php?var=<?=$dataArray['setting'];?>&type=varchar' style='text-decoration: none;' title='<?=$dataArray['description'];?>'><?=$dataArray['setting'];?></a>)</td>
							<td class="field" style="width: 30%;"><?=$dataArray['value'];?></td>
							<td class="field" style="width: 20%;"><a href='config.php?var=<?=$dataArray['setting'];?>&type=varchar' style='text-decoration: none;' title='Delete Variable'>Delete</a></td>
						</tr>
					</table>
				</div>
				<?
			}
		}
		?>
	</div>
	<div style="margin-bottom: 5px;">
	<input type="button" class="std_input" value="New Varchar Variable" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px;" onclick="window.location = 'editconfig.php?var=0&type=varchar';" />
	</div>

	<!-- TEXT Variables from Database -->
	<div class="title">
		<div style="margin-left: 10px;">Database Variables (TEXT)</div>
	</div>
	<div class="content" style="padding: 0px;">
		
		<!-- Table for categories -->
		<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
			<table class="row">
				<tr>
					<td class="field" style="width: 50%;"><strong>Setting</strong></td>
					<td class="field" style="width: 30%;">&nbsp;</td>
					<td class="field" style="width: 20%;"><strong>Options</strong></td>
				</tr>
			</table>
		</div>

		<?
		$dataQuery = $db->query( "SELECT * FROM settings_text ORDER BY formal" );
		if ( $db->getRowCount( $dataQuery ) == 0 )
		{
			?>
			<div class="row">
				No variables found.
			</div>
			<?
		}
		else
		{
			while ( $dataArray = $db->fetch_array( $dataQuery ) )
			{
				?>
				<div class="row">
					<table class="row">
						<tr>
							<td class="field" style="width: 50%;"><?=$dataArray['formal'];?> (<a href='editconfig.php?var=<?=$dataArray['setting'];?>&type=text' style='text-decoration: none;' title='<?=$dataArray['description'];?>'><?=$dataArray['setting'];?></a>)</td>
							<td class="field" style="width: 30%;">&nbsp;</td>
							<td class="field" style="width: 20%;"><a href='config.php?var=<?=$dataArray['setting'];?>&type=text' style='text-decoration: none;' title='Delete Variable'>Delete</a></td>
						</tr>
					</table>
				</div>
				<?
			}
		}
		?>
	</div>
	<div style="margin-bottom: 5px;">
	<input type="button" class="std_input" value="New Text Variable" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px;" onclick="window.location = 'editconfig.php?var=0&type=text';" />
	</div>

	<!-- FILE Variable from Database -->
	<div class="title">
		<div style="margin-left: 10px;">Database Variables (INCLUDE)</div>
	</div>
	<div class="content" style="padding: 0px;">
		
		<!-- Table for categories -->
		<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
			<table class="row">
				<tr>
					<td class="field" style="width: 50%;"><strong>File</strong></td>
					<td class="field" style="width: 30%;">&nbsp;</td>
					<td class="field" style="width: 20%;"><strong>Options</strong></td>
				</tr>
			</table>
		</div>

		<?
		$dataQuery = $db->query( "SELECT * FROM settings_include" );
		if ( $db->getRowCount( $dataQuery ) == 0 )
		{
			?>
			<div class="row">
				&nbsp;No includes found.
			</div>
			<?
		}
		else
		{
			while ( $dataArray = $db->fetch_array( $dataQuery ) )
			{
				?>
				<div class="row">
					<table class="row">
						<tr>
							<td class="field" style="width: 50%;"><?=$dataArray['formal'];?> (<a href='editconfig.php?var=<?=$dataArray['filename'];?>&type=include' style='text-decoration: none;' title='<?=$dataArray['description'];?>'><?=$dataArray['filename'];?></a>)</td>
							<td class="field" style="width: 30%;">&nbsp;</td>
							<td class="field" style="width: 20%;"><a href='config.php?var=<?=$dataArray['filename'];?>&type=include' style='text-decoration: none;' title='Delete Variable'>Delete</a></td>
						</tr>
					</table>
				</div>
				<?
			}
		}
		?>
	</div>
	<div style="margin-bottom: 5px;">
	<input type="button" class="std_input" value="New Include Variable" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px;" onclick="window.location = 'editconfig.php?var=0&type=include';" />
	</div>
<div>&nbsp;</div>
<?
include_once "../includes/footer.php";
?>