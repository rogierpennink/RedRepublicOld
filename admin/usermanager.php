<?
/**
 * usermanager.php - Obviously allows us to edit users :)
 * 
 * Author:	Aaron Amann
 * Date:	08/03/2007
 */
$nav = "userman";
$ext_style = "forums_style.css";

$AUTH_LEVEL = 8;
$REDIRECT = true;
include_once "../includes/utility.inc.php";

if ( isset ( $_GET['ajaxrequest'] ) && $_GET['ajaxrequest'] != "" )
{
	if ( !user_auth() )
	{
		echo "error::notloggedin";
	}
	elseif ( getUserRights() < USER_MODERATOR )
	{
		echo "error::accessdenied";
	}
	else
	{
		echo "error::An unknown request was received, no response output.";
	}
	
	exit;
}

include_once "../includes/header.php";

?>	
	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>User Management</h1>
	<p>Welcome to the user management system. Here you can edit accounts, delete accounts, announce messages, and a few other options. Please be warned that you can cause severe damage if your not paying attention, and generally users might not like there account deleted, or altered in a negative way.</p>

	<?

	$max = $db->getRowCount( $db->query( "SELECT * FROM accounts" ) );

	// The php code that will make us fetch the categories as they are now
	$maxperpage = 20;
	$offset = $_GET['pag'] ? ( $_GET['pag'] - 1 ) * $maxperpage : 0;
	$userQuery = $db->query( "SELECT * FROM accounts ORDER BY username LIMIT $offset,$maxperpage" );

	if ( $max > $maxperpage )
	{
		?>
		<div class="content" style="border: solid 1px #990033;">
		<? for ( $i = 0; $i < ceil( $max/$maxperpage ); $i++ )
		{
			$pag = $offset / $maxperpage;
			
			echo "<span style=\"margin-right: 3px;\">";
			if ( $pag != $i )
				echo "<a href=\"usermanager.php?pag=". ( $i + 1 ) ."\">";

			echo $i + 1;

			if ( $pag != $i )
				echo "</a>";
			echo "</span>";
		}
		?>
		</div>
		<?
	}
	?>

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">User Menu</div>
	</div>
	<div class="content" style="padding: 0px;">
		
		<!-- Table for categories -->
		<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
			<table class="row">
				<tr>
					<td class="field" style="width: 40%;"><strong>Username</strong></td>
					<td class="field" style="width: 60%; vertical-align: right; text-align: center;"><strong>Options</strong></td>
				</tr>
			</table>
		</div>

		<?
		

		if ( $db->getRowCount( $userQuery ) == 0 )
		{
			?>
			<div class="row">
				No users found, you shouldn't even be here!
			</div>
			<?
		}
		else
		{
			while ( $userArray = $db->fetch_array( $userQuery ) )
			{
				$charq = $db->getRowsFor( "SELECT id FROM char_characters WHERE account_id=" . $userArray['id'] );
				$charid = $charq['id'];
				?>
				<div class="row">
					<table class="row">
						<tr>
							<td class="field" style="width: 40%;"><?=$userArray['username'];?></td>
							<td class="field" style="width: 60%; vertical-align: right; text-align: center;"><a style='text-decoration: none;' href="<?=$rootdir;?>/profile.php?id=<?=$charid;?>" title="Allows you to view a user's details.">Profile</a> - <a style='text-decoration: none;' href="edituser.php?id=<?=$userArray['id'];?>" title="Allows you to edit a user's details.">Edit</a> - <a style='text-decoration: none;' href="<?=$rootdir;?>/admin/announceuser.php?id=<?=$userArray['id'];?>" title="Allows you to make an announcement to a user.">Announcement</a> - <a style='text-decoration: none;' href="<?=$rootdir;?>/admin/deleteuser.php?id=<?=$userArray['id'];?>" title="Please make sure you have the right person.">Delete</a> - <a style='text-decoration: none;' href="<?=$PHP_SELF;?>?act=login&amp;id=<?=$userArray['id'];?>">Log in as this user</a></td>
						</tr>
					</table>
				</div>
				<?
			}
		}
		?>

	</div>

	<?
	if ( $max > $maxperpage )
	{
		?>
		<div class="content" style="border: solid 1px #990033;">
		<? for ( $i = 0; $i < ceil( $max/$maxperpage ); $i++ )
		{
			$pag = $offset / $maxperpage;
			
			echo "<span style=\"margin-right: 3px;\">";
			if ( $pag != $i )
				echo "<a href=\"usermanager.php?pag=". ( $i + 1 ) ."\">";

			echo $i + 1;

			if ( $pag != $i )
				echo "</a>";
			echo "</span>";
		}
		?>
		</div>
		<?
	}
	
include_once "../includes/footer.php";
?>