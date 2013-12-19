<?
/**
 * newsmanagement.php - Add, edit, remove news.
 * 
 * Author:	Aaron Amann
 * Date:	08/03/2007
 */
$nav = "newsman";
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
	elseif ( getUserRights() < USER_ADMIN )
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

if ( isset( $_GET['id'] ) )
{
	$db->query( "DELETE FROM frontpage_news WHERE id=" . $db->prepval( $_GET['id'] ) );

	adminLog( $_SESSION['username'], "Deleted a news item (id: " . $_GET['id'] . ")." );
}

include_once "../includes/header.php";

?>	
	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>News Management</h1>
	<p>Welcome to the press room! If your filing a report to the public, or just looking for friends, your sure to get their undivided attention with a good ol' fashion news post. You can also edit news posts to get rid of those typos and delete news posts incase you've made an embarrasing mistake.</p>

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">News Items</div>
	</div>
	<div class="content" style="padding: 0px;">
		
		<!-- Table for categories -->
		<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
			<table class="row">
				<tr>
					<td class="field" style="width: 45%;"><strong>Subject</strong></td>
					<td class="field" style="width: 25%;"><strong>Date</strong></td>
					<td class="field" style="width: 15%;"><strong>Author</strong></td>
					<td class="field" style="width: 15%;"><strong>Delete</strong></td>
				</tr>
			</table>
		</div>

		<?
		$newsQuery = $db->query( "SELECT * FROM frontpage_news ORDER BY news_date DESC LIMIT 20" );
		if ( $db->getRowCount( $newsQuery ) == 0 )
		{
			?>
			<div class="row">
				No news items in database.
			</div>
			<?
		}
		else
		{
			while ( $newsArray = $db->fetch_array( $newsQuery ) )
			{
				?>
				<div class="row">
					<table class="row">
						<tr>
							<td class="field" style="width: 45%;"><a href='editnews.php?id=<?=$newsArray['id'];?>' style='text-decoration: none;' title='<?=shortstr( $newsArray['news_message'] );?>'><?=shortstr( $newsArray['news_subject'] );?></a></td>
							<td class="field" style="width: 25%;"><?=$newsArray['news_date'];?></td>
							<td class="field" style="width: 15%;"><?=$newsArray['news_author'];?></td>
							<td class="field" style="width: 15%;"><a href='newsmanagement.php?id=<?=$newsArray['id'];?>' title='Delete this post?' style='text-decoration: none;'>Delete</a>
						</tr>
					</table>
				</div>
				<?
			}
		}
		?>
	</div>
	<div style="margin-bottom: 5px;">
	<input type="button" class="std_input" value="New Post" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px;" onclick="window.location = 'editnews.php?id=0';" />
	</div>

<?
include_once "../includes/footer.php";
?>