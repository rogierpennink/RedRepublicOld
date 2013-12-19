<?
/**
 * editnews.php - Lets edit a news post, or if URL param ID is 0, create a new news post.
 * 
 * Author:	Aaron Amann
 * Date:	08/03/2007
 */
$AUTH_LEVEL = 8;
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

if ( isset( $_POST['submit'] ) )
{
	$message = str_replace( Chr(13), "<br />", $_POST['news_message'] );
	if ( $_POST['id'] == 'New' )
	{
		$db->query( "INSERT INTO frontpage_news (id, news_type, news_subject, news_date, news_author, news_message) VALUES ('', '0', " . $db->prepval( $_POST['news_subject'] ) . ", '" . date( "Y-m-d H:i:s ", time() ) . "', " . $db->prepval( $_SESSION['username'] ) . ", " . $db->prepval( $message ) . ")" );

		adminLog( $_SESSION['username'], "Added a news item with the title, <i>" . $_POST['news_subject'] . "</i>." );

		header( "location: newsmanagement.php" );
		exit;
	}
	else 
	{
		$db->query( "UPDATE frontpage_news SET news_subject=" . $db->prepval( $_POST['news_subject'] ) . ", news_message=" . $db->prepval( $message ) . " WHERE id=" . $db->prepval( $_POST['id'] ) );
		header( "location: newsmanagement.php" );
		exit;
	}
}

if ( !isset( $_GET['id'] ) )
{
	$_SESSION['error'] = "ID was not set. Redirected.";
	header( "Location: usermanager.php" );
	exit;
}


$ext_style = "forums_style.css";
include_once "../includes/header.php";

?>

	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>News Editor</h1>
	<p>From here you'll be able to edit or create a news post that shows up on the frontpage. <br /></p>

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">News Editor</div>
	</div>
	<div class="content" style="padding: 0px;">
		<table style="width: 90%"><tr><td>
			<?
			$newsQuery = $db->query( "SELECT * FROM frontpage_news WHERE id=" . $db->prepval( $_GET['id'] ) );
			if ( $db->getRowCount( $newsQuery ) == 0 )
			{
				$postType = 'new';
			} 
			else 
			{
				$postType = 'edit';
				$newsAssoc = $db->fetch_assoc( $newsQuery );
			}
				?>
				<form method="post" action="<?=$PHP_SELF;?>">
				<table style="width: 100%">
					<tr>
						<td style="width: 18%">News ID: </td>
						<td><input type='text' class='std_input' name='id' readonly='true' size='4' value='<?
						if ( $postType == 'edit' )
						{
							print( $_GET['id'] );
						} 
						else 
						{
							print( "New" );
						}
						?>' /></td>
					</tr>
					<tr>
						<td style="width: 18%">Subject: </td>
						<td><input type='text' class='std_input' name='news_subject' size='60' value='<?
						if ( $postType == 'edit' )
						{
							print( $newsAssoc['news_subject'] );
						} 
						?>' /></td>
					</tr>
					<tr>
						<td style="width: 18%">Message: </td>
						<td>
							<textarea class='std_input' name='news_message' cols='60' rows='9'><?
							if ( $postType == 'edit' )
							{
								print( $newsAssoc['news_message'] );
							}
							?></textarea>
						</td>
					</tr>
					<tr>
						<td style="width: 18%;"><input type='submit' class='std_input' name='submit' value='    Post    '></td>
						<td><input type='reset' class='std_input' name='reset' value='    Reset    '></td>
					</tr>
				</table>
				</form>
		</td></tr></table>
	</div>

<?
include_once "../includes/footer.php";
?>