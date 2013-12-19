<?
/**
 * forumadmin.php - Along with offering a nice admin display for managing categories,
 * forums and more, this also contains an AJAX part with which the file will interact
 * when updates are needed etc.
 * 
 * Author:	Rogier Pennink
 * Date:	07/02/2007
 */
$nav = "forumman";
$ext_style = "forums_style.css";

$AUTH_LEVEL = 9;
$REDIRECT = true;
include_once "../includes/utility.inc.php";
include_once "../includes/forums.inc.php";

if ( isset( $_GET['delcat'] ) )
{
	$continue = true;

	$rec = $db->query( "SELECT id FROM forums_categories WHERE id=". $db->prepval( $_GET['delcat'] ) );

	if ( $db->getRowCount( $rec ) == 0 )
	{
		$_SESSION['info_msg'] = "You can't delete non-existing categories!";
		$continue = false;
	}

	$res = $db->fetch_array( $rec );

	if ( $continue && $db->query( "DELETE FROM forums_categories WHERE id=". $res['id'] ) === false )
	{
		$_SESSION['info_msg'] = "A database error prevented you from deleting this category.";
		$continue = false;
	}

	// Delete all forums, topics and replies in this category
	while ( $continue && $fres = $db->fetch_array( $db->query( "SELECT id FROM forums_forums WHERE cat_id=". $res['id'] ) ) )
	{
		$deltopics = false;

		$db->query( "DELETE FROM forums_forums WHERE id=". $fres['id'] );
		while ( $tres = $db->fetch_array( $db->query( "SELECT id FROM forums_topics WHERE f_id=". $fres['id'] ) ) )
		{
			$db->query( "DELETE FROM forums_replies WHERE t_id=". $tres['id'] );
			$deltopics = true;
		}
		if ( $deltopics )
		$db->query( "DELETE FROM forums_topics WHERE f_id=". $fres['id'] );
	}	
}

include_once "../includes/header.php";
?>
	<script type="text/javascript">
	function deleteCat()
	{
		if ( !window.confirm( "You are about to delete a forum category.\nIf you continue, all forums and posts in that category will be lost!\nAre you sure you want to continue?" ) )	
			return false;
		return true;
	}		
	</script>

	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>Forums Management</h1>
	<p>This is your forums management page. This is an administrator's feature and if you found yourself being able to view this page while not being an administrator, please report this as soon as possible. Be aware that this is a powerful tool with which you can shape the forums, but also inflict great damage upon it. Use it wisely.</p>

	<? if ( isset( $_SESSION['info_msg'] ) ) { ?><p><strong><?=$_SESSION['info_msg'];?></strong></p><? unset( $_SESSION['info_msg'] ); } ?>

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">Manage Categories</div>
	</div>
	<div class="content" style="padding: 0px;">
		
		<!-- Table for categories -->
		<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
			<table class="row">
				<tr>
					<td class="field" style="width: 50%;"><strong>Category Name</strong></td>
					<td class="field" style="width: 25%;"><strong>Num. Forums</strong></td>
					<td class="field" style="width: 25%;"><strong>Options</strong></td>
				</tr>
			</table>
		</div>

		<?
		// The php code that will make us fetch the categories as they are now
		$catres = $db->query( "SELECT * FROM forums_categories" );

		if ( $db->getRowCount( $catres ) == 0 )
		{
			?>
			<div class="row">
				No categories were found in the database, you'd better quickly add some more.
			</div>
			<?
		}
		else
		{
			while ( $cat = $db->fetch_array( $catres ) )
			{
				?>
				<div class="row">
					<table class="row">
						<tr>
							<td class="field" style="width: 50%;"><?=$cat['name'];?></td>
							<td class="field" style="width: 25%;">
							<?
							$forumres = $db->query( "SELECT * FROM forums_forums WHERE cat_id=" .$db->prepval( $cat['id'] ) );
							echo $db->getRowCount( $forumres );
							?>
							</td>
							<td class="field" style="width: 25%;"><a href="forumsmanager.php?delcat=<?=$cat['id'];?>" onclick="return deleteCat();" title="Deleting a category will also delete all forums and posts within it!">delete</a></td>
						</tr>
					</table>
				</div>
				<?
			}
		}
		?>

	</div>

<?
include_once "../includes/footer.php";
?>