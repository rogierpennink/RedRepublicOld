<?
/**
 * supportmanagement.php - Add, edit, remove guides and questions from the FAQ book and player guide.
 * 
 * Author:	Aaron Amann
 * Date:	08/29/2007
 */
$nav = "faqman";
$ext_style = "forums_style.css";
$AUTH_LEVEL = 5;
$REDIRECT = true;
include_once "../includes/utility.inc.php";

if ( getUserRights() == USER_SUPPORT ) $_SESSION['ingame'] = true;

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
		case 'faqentry': $table = 'faqbook'; break;
		case 'playerguide': $table = 'playerguides'; break;
	}

	$db->query( "DELETE FROM " . $table . " WHERE id=" . $db->prepval( $_GET['var'] ) );
}

include_once "../includes/header.php";

?>	
	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>Support Administration</h1>
	<p>Here you can manage the Player Guides and FAQ Book. This is a tedious task, so watch carefully what you do. The FAQs should follow a normal question & answer theme, you may take suggestions from other players as you see fit. And the Player Guides should be drawn out articles on various subjects in the game, again you may take suggestions from players, if you deem it worthy. Enjoy administering.</p>

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">Red Republic FAQ Book Entries</div>
	</div>
	<div class="content" style="padding: 0px;">
		
		<!-- Table for categories -->
		<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
			<table class="row">
				<tr>
					<td class="field" style="width: 80%;"><strong>Question</strong></td>
					<td class="field" style="width: 20%;"><strong>Options</strong></td>
				</tr>
			</table>
		</div>

		<?
		$dataQuery = $db->query( "SELECT * FROM faqbook" );
		if ( $db->getRowCount( $dataQuery ) == 0 )
		{
			?>
			<div class="row">
				No FAQ Book entries found.
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
							<td class="field" style="width: 80%;"><a href='editfaq.php?id=<?=$dataArray['id'];?>' style='text-decoration: none;' alt='<?=shortstr( $dataArray['answer'] );?>' title='<?=shortstr( $dataArray['answer'] );?>'><?=shortstr( $dataArray['question'] );?></a></td>
							<td class="field" style="width: 20%;"><a href='supportmanagement.php?var=<?=$dataArray['id'];?>&type=faqentry' style='text-decoration: none;' title='Delete Question'>Delete</a></td>
						</tr>
					</table>
				</div>
				<?
			}
		}
		?>
	</div>
	<div style="margin-bottom: 5px;">
	<form action='<?=$rootdir;?>/admin/editguide.php?id=new' method='post'><input type="button" class="std_input" value="New Question" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px;" onclick="window.location = '<?=$rootdir;?>/admin/editfaq.php?id=new';" /></form>
	</div>

	<!-- TEXT Variables from Database -->
	<div class="title">
		<div style="margin-left: 10px;">Red Republic Player Guides</div>
	</div>
	<div class="content" style="padding: 0px;">
		
		<!-- Table for categories -->
		<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
			<table class="row">
				<tr>
					<td class="field" style="width: 50%;"><strong>Guide Name</strong></td>
					<td class="field" style="width: 30%;">&nbsp;</td>
					<td class="field" style="width: 20%;"><strong>Options</strong></td>
				</tr>
			</table>
		</div>

		<?
		$dataQuery = $db->query( "SELECT * FROM playerguides" );
		if ( $db->getRowCount( $dataQuery ) == 0 )
		{
			?>
			<div class="row">
				No player guides found.
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
							<td class="field" style="width: 80%;"><a href='editguide.php?id=<?=$dataArray['id'];?>' style='text-decoration: none;' alt='<?=shortstr( $dataArray['content'] );?>' title='<?=shortstr( $dataArray['content'] );?>'><?=shortstr( $dataArray['name'] );?></a></td>
							<td class="field" style="width: 20%;"><a href='supportmanagement.php?var=<?=$dataArray['id'];?>&type=playerguide' style='text-decoration: none;' title='Delete Guide'>Delete</a></td>
						</tr>
					</table>
				</div>
				<?
			}
		}
		?>
	</div>
	<div style="margin-bottom: 5px;">
	<form method='post' action='<?=$rootdir;?>/admin/editguide.php?id=new'><input type="button" class="std_input" value="New Player Guide" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px;" onclick="window.location = '<?=$rootdir;?>/admin/editguide.php?id=new';" /></form>
	</div>
<?
include_once "../includes/footer.php";
?>