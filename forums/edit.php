<?
$ext_style = "forums_style.css";

// Before generating any output we have to check if an id was sent. If this
// is not the case we'll redirect back to index.php and generate an error message.
if ( !isset( $_GET['id'] ) || $_GET['id'] == "" )
{
	session_start();
	$_SESSION["error"] = "You tried to edit without passing us an ID, if you got this error by pressing a link - contact the administrators.";	
	header( "Location: index.php" );
}
else // We still can't be sure things are OK, therefore, test validness first
{
	include_once "../includes/utility.inc.php";

	if ( !user_auth() )
	{
		$_SESSION["error"] = "You were not logged in, in order to edit posts this is necessary.";	
		header( "Location: ../login.php" );
		exit;
	}

	// Query
	$replyres = $db->query( "SELECT * FROM forums_replies WHERE id=" . $db->prepval( $_GET['id'] ) );

	// Check if it exists
	if ( $db->getRowCount( $replyres ) == 0 )
	{
		$_SESSION["error"] = "You tried to edit a reply with a false ID, if you got this error by pressing a link - contact the administrators.";	
		header( "Location: index.php" );
		exit;
	}
	
	$reply = $db->fetch_array( $replyres );

	// Query forum id
	$forumres = $db->query( "SELECT * FROM forums_forums WHERE id=". $reply['f_id'] );
	$forum = $db->fetch_array( $forumres );
	if ( $forum['auth_level'] > getUserRights() )
	{
		$_SESSION["error"] = "You tried to edit a post that you are not allowed to view or add to.";	
		header( "Location: index.php" );
		exit;
	}

	if ( getUserID() != $reply['account_id'] && getUserRights() < USER_MODERATOR )
	{
		$_SESSION["error"] = "You tried to edit a post for which you didn't have modification rights.";	
		header( "Location: index.php" );
		exit;
	}

	$topic = $db->fetch_array( $db->query( "SELECT * FROM forums_topics WHERE id=". $db->prepval( $reply['t_id'] ) ) );

	// In case of a submit
	if ( isset( $_POST['editreply'] ) )
	{
		if ( user_auth() && ( getUserRights() >= USER_MODERATOR || getUserID() == $reply['account_id'] ) )
		{
			if ( strlen( $_POST['reply'] ) < 3 )
			{
				$_SESSION['error'] = "Your post must be longer than three characters.";
			}
			else
			{
				$message = str_replace( array( "<", ">" ), array( "&lt;", "&gt;" ), $_POST['reply'] );
				$message = addslashes( str_replace( Chr(13), "<br />", $message ) );

				// Construct the query
				if ( $db->query( "UPDATE forums_replies SET message=". $db->prepval( $message ) ." WHERE id=". $db->prepval( $reply['id'] ) ) !== false )
				{

					$posted = true;
				}
				else
				{
					$_SESSION['error'] = "An error occurred on editing this post. Contact the administrators.";
					$posted = false;
				}
			}
		}	
		else
		{
			$_SESSION['error'] = "You were not logged in or didn't have sufficient rights to edit this post.";
			$posted = false;
		}
	}

	if ( $posted )
	{
		header( "Location: viewtopic.php?id=" . $reply['t_id'] );
		exit;
	}

	unset( $_SESSION['ingame'] );

	include_once "../includes/header.php";
	include_once "../includes/forums.inc.php";	

	?>
	<h1>Edit reply to topic: '<?=$topic['name'];?>'</h1>
	<p>You are about to edit a reply on the Crime Syndicate forums. Please note that, although we encourage activity on these forums, we are also strict in monitoring them. If your post trespasses our <a href="../docs.php?type=frules">forum rules</a> your post may be modified or even deleted. If your post is a severely breaching our forum rules, administrators may even resort to suspending your account for an indefinite amount of time.</p>

	<form action="edit.php?id=<?=$reply['id'];?>" method="post">
		
		<div class="title">
			<div style="margin-left: 10px;">Edit reply</div>
		</div>
		<div class="content" style="padding: 0px;">

			<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
				<table class="row">
					<tr>							
						<td class="field" style="width: 125px; border-right: solid 1px #bb6;">&nbsp;</td>
						<td class="field"><strong>Edit Reply</strong></td>
					</tr>
				</table>
			</div>

			<div class="row">
				<table class="row">
					<tr>
						<td class="field" style="width: 125px; border-right: solid 1px #bb6; vertical-align: top; padding-right: none;">				
							<div style="height: 20px; text-align: right; margin: 5px; margin-top: 2px;"><strong>Message:</strong></div>
							<div class="title" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: solid 1px #bb6; margin-right: 5px;"><div style="margin-left: 10px;">Smileys</div></div>
							<div class="content" style="background-color: #ee9; border: solid 1px #bb6; border-top: none; margin-right: 5px;">
							</div>
						</td>

						<td class="field" style="padding: 0px; vertical-align: top; padding: 5px;">							
							<textarea name="reply" class="std_input" style="margin-top: 5px; width: 100%; min-height: 300px;"><? 
							$msg = stripslashes( $reply['message'] );
							$msg = str_replace( "<br />", Chr(13), $msg );
							$msg = str_replace( array( "&lt;", "&gt;" ), array( "<", ">" ), $msg );
							echo $msg; ?>
							</textarea>
						</td>
					</tr>
				</table>
			</div>

			<div class="row" style="text-align: center;">
				<input type="submit" class="std_input" name="editreply" value="Submit Post" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-top: 5px;" />
				<input type="hidden" name="t_id" value="<?=$reply['id'];?>" />
			</div>
			
		</div>

	</form>
	<?

	include_once "../includes/footer.php";
}
?>