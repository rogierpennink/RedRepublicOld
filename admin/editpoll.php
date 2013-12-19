<?
/**
 * editpoll.php - Lets edit us some polls.
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
	$options = explode( chr( 13 ), $_POST['options'] );
	$title = addslashes( $_POST['title'] );
	if ( isset( $_POST['id'] ) )
	{
		$db->query( "INSERT INTO polls (id, title, date, author, options, votes, open, displayed) VALUES ('', '" . $title . "', '" . date( "Y-m-d H:i:s ", time() ) . "', " . $db->prepval( $_SESSION['username'] ) . ", " . $db->prepval( $options ) . ", '0', 'true', 'false')" );
		
		$i = 0;

		$moreData = $db->query("SELECT id FROM polls WHERE title=" . $db->prepval( $title ) );
		$moreArray = $db->fetch_array($moreData);
		while ( $i < count( $options ) )
		{
			$db->query( "INSERT INTO polls_options (id, parent, `option`, votes) VALUES ('', " . $moreArray['id'] . ", " . $db->prepval( $options[$i] ) . ", 0)" );
			$i++;
		}

		adminLog( $_SESSION['username'], "Added a poll titled, <i>" . $title . "</i>." );

		header( "location: pollmanagement.php" );
		exit;
	}
}

if ( !isset( $_GET['id'] ) )
{
	$_SESSION['error'] = "ID was not set. Redirected.";
	header( "Location: pollmanagement.php" );
	exit;
}


$ext_style = "forums_style.css";
include_once "../includes/header.php";

?>

	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>Poll Editor</h1>
	<p>This will create a new poll, if your editing an old one remember to delete it.<br /></p>

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">Poll Editor</div>
	</div>
	<div class="content" style="padding: 0px;">
		<table style="width: 90%"><tr><td>
			<?
			$pollQuery = $db->query( "SELECT * FROM polls WHERE id=" . $db->prepval( $_GET['id'] ) );
			if ( $db->getRowCount( $pollQuery ) == 0 )
			{
				$postType = 'new';
			} 
			else 
			{
				$postType = 'edit';
				$pollAssoc = $db->fetch_assoc( $pollQuery );
			}
				?>
				<form method="post" action="<?=$PHP_SELF;?>">
				<table style="width: 100%">
					<tr>
						<td style="width: 18%">Poll ID: </td>
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
						<td style="width: 18%">Title: </td>
						<td><input type='text' class='std_input' name='title' size='60' value='<?
						if ( $postType == 'edit' )
						{
							print( $pollAssoc['title'] );
						} 
						?>' /></td>
					</tr>
					<tr>
						<td style="width: 18%">Options: </td>
						<td>
							<textarea class='std_input' name='options' cols='60' rows='9'><?
							if ( $postType == 'edit' )
							{
								$opQuery = $db->query("SELECT * FROM polls_options WHERE parent=" . $db->prepval( $_GET['id'] ) );
								while ( $option = $db->fetch_array( $opQuery ) )
								{
									print( $option['option'] . "\n" );
								}
							}
							?></textarea>
						</td>
					</tr>
					<tr>
						<td style="width: 18%;"><input type='submit' class='std_input' name='submit' value='    Create    '></td>
						<td><input type='reset' class='std_input' name='reset' value='    Reset    '></td>
					</tr>
				</table>
				</form>
		</td></tr></table>
	</div>

<?
include_once "../includes/footer.php";
?>