<?
/**
 * pollmanagement.php - Add, edit, delete polls for the side content.
 * 
 * Author:	Aaron Amann
 * Date:	08/03/2007
 */
$nav = "pollman";
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

if ( isset( $_GET['id'] ) && isset( $_GET['act'] ) && $_GET['act'] == 'delete' )
{
	$db->query( "DELETE FROM polls_options WHERE parent=" . $db->prepval( $_GET['id'] ) );
	$db->query( "DELETE FROM polls WHERE id=" . $db->prepval( $_GET['id'] ) );
}

if ( isset( $_GET['id'] ) && isset( $_GET['act'] ) && $_GET['act'] == 'display' )
{
	$selectPreviousPoll = $db->query( "SELECT id FROM polls WHERE displayed='true'" );
	$selectPreviousPollArray = $db->fetch_array( $selectPreviousPoll );
	$db->query( "UPDATE polls SET displayed='false' WHERE id=" . $db->prepval( $selectPreviousPollArray['id'] ) );
	$db->query( "UPDATE polls SET displayed='true' WHERE id=" . $db->prepval( $_GET['id'] ) );
}

if ( isset( $_GET['id'] ) && isset( $_GET['act'] ) && $_GET['act'] == 'close' )
{
	$getPollQ = $db->query( "SELECT open FROM polls WHERE id=" . $db->prepval( $_GET['id'] ) );
	$getPoll = $db->fetch_array( $getPollQ );
	if ( $getPoll['open'] == 'true' )
	{
		$db->query( "UPDATE polls SET open='false' WHERE id=" . $db->prepval( $_GET['id'] ) );
	} elseif ( $getPoll['open'] == 'false' ) {
		$db->query( "UPDATE polls SET open='true' WHERE id=" . $db->prepval( $_GET['id'] ) );
	}
}

if ( isset( $_GET['id'] ) && isset( $_GET['act'] ) && $_GET['act'] == 'announce' )
{
	$pQuery = $db->query( "SELECT * FROM polls WHERE id=" . $db->prepval( $_GET['id'] ) );
	$poQuery = $db->query( "SELECT * FROM polls_options WHERE parent=" . $db->prepval( $_GET['id'] ) );
	$Poll = $db->fetch_array( $pQuery );
	$Message = "The polls have closed for <i>" . $Poll['title'] . "</i> and the results are in:<br /><br />";
	while ( $Options = $db->fetch_array( $poQuery ) )
	{
		$Message = $Message . addslashes( $Options['option'] ) . " (" . $Options['votes'] . " votes)<br />";
	}
	$Message = $Message . "<br />We hope you participate in our next poll!<br /><br />- Administration";
	$db->query( "UPDATE polls SET open='false' WHERE id=" . $db->prepval( $_GET['id'] ) );
	$db->query( "INSERT INTO frontpage_news (id, news_type, news_subject, news_date, news_author, news_message) VALUES ('', 0, 'Poll Results: " . $Poll['title'] . "', '" . date( "Y-m-d H:i:s ", time() ) . "', '" . $_SESSION['username'] . "', '" . $Message . "')");
}



include_once "../includes/header.php";

?>	
	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>Poll Management</h1>
	<p>Heres were you can add, edit, delete, polls. This is also were you select which one is supposed to be displayed. It may be possible to make mistakes here, so use your best judgement when making them. Remember, displaying a poll will switch the current poll with the poll you have chosen. Closing and Announcing a poll is a rather unique feature, allowing you to close a poll and announce the results on the front page news.</p>

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">Polls</div>
	</div>
	<div class="content" style="padding: 0px;">
		
		<!-- Table for categories -->
		<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
			<table class="row">
				<tr>
					<td class="field" style="width: 42%;"><strong>Title</strong></td>
					<td class="field" style="width: 15%;"><strong>Date</strong></td>
					<td class="field" style="width: 10%;"><strong>Author</strong></td>
					<td class="field" style="width: 33%;"><strong>Options</strong></td>
				</tr>
			</table>
		</div>

		<?
		$pollQuery = $db->query( "SELECT * FROM polls ORDER BY date DESC LIMIT 20" );
		if ( $db->getRowCount( $pollQuery ) == 0 )
		{
			?>
			<div class="row">
				No items in database.
			</div>
			<?
		}
		else
		{
			while ( $pollArray = $db->fetch_array( $pollQuery ) )
			{
				?>
				<div class="row">
					<table class="row">
					<?
					$pollArrayQEx = $db->query( "SELECT * FROM polls WHERE id=" . $db->prepval( $pollArray['id'] ) );
					$pollArrayEx = $db->fetch_array( $pollArrayQEx );
					?>
						<tr>
							<td class="field" style="width: 42%;"><a href='editpoll.php?id=<?=$pollArrayEx['id'];?>' style='text-decoration: none;' title='Edit Poll: <?=$pollArrayEx['title'];?>'><?=$pollArrayEx['title'];?></a> (<?=$pollArrayEx['votes'];?> votes)
								<?
								if ( $pollArrayEx['displayed'] == 'true' )
								{
									print(" (<font color='#CC0000'>displayed</font>)");
								}

								if ( $pollArrayEx['open'] == 'true' )
								{
									print(" (<font color='#3A6585'>opened</font>)");
								}
								?>
							</td>
							<td class="field" style="width: 15%;"><?=$pollArrayEx['date'];?></td>
							<td class="field" style="width: 10%;"><?=$pollArrayEx['author'];?></td>
							<td class="field" style="width: 33%;"><a href='pollmanagement.php?id=<?=$pollArrayEx['id'];?>&act=delete' title='Delete this poll?' style='text-decoration: none;'>Delete</a> | <a href='pollmanagement.php?id=<?=$pollArrayEx['id'];?>&act=display' title='Use this poll as the displayed poll?' style='text-decoration: none;'>Display</a> | <a href='pollmanagement.php?id=<?=$pollArrayEx['id'];?>&act=close' title='Close this poll, but save it?' style='text-decoration: none;'>Close</a> | <a href='pollmanagement.php?id=<?=$pollArrayEx['id'];?>&act=announce' title='Close poll, and announce results on frontpage?' style='text-decoration: none;'>Close & Announce</a>
						</tr>
					</table>
				</div>
				<?
			}
		}
		?>
	</div>
	<div style="margin-bottom: 5px;">
	<input type="button" class="std_input" value="New Poll" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px;" onclick="window.location = 'editpoll.php?id=0';" />
	</div>

<?
include_once "../includes/footer.php";
?>