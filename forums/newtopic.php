<?
$ext_style = "forums_style.css";

// Before generating any output we have to check if an id was sent. If this
// is not the case we'll redirect back to index.php and generate an error message.
if ( !isset( $_GET['id'] ) || $_GET['id'] == "" )
{
	session_start();
	$_SESSION["error"] = "You tried to post a topic without passing us an ID, if you got this error by pressing a link - contact the administrators.";	
	header( "Location: index.php" );
}
else // We still can't be sure things are OK, therefore, test validness first
{
	include_once "../includes/utility.inc.php";

	// Query
	$forumres = $db->query( "SELECT * FROM forums_forums WHERE id=" . $db->prepval( $_GET['id'] ) );

	// Check if it exists
	if ( $db->getRowCount( $forumres ) == 0 )
	{
		$_SESSION["error"] = "You tried to post a topic with a false ID, if you got this error by pressing a link - contact the administrators.";	
		header( "Location: index.php" );
		exit;
	}

	$forum = $db->fetch_array( $forumres );
	
	// Check if the user has the rights to visit this page
	if ( $forum['auth_level'] > getUserRights() )
	{
		$_SESSION["error"] = "You tried to post a topic in a forum you're not allowed to post in.";	
		header( "Location: index.php" );
		exit;
	}
	
	// In case of a submit
	if ( isset( $_POST['newtopic'] ) )
	{
		$continue = true;

		if ( !user_auth() )
		{
			$continue = false;

			if ( !user_auth( $_POST['usr'], $_POST['pwd'] ) )
			{
				$_SESSION['error'] = "Your login details were wrong, could not post reply.";
				$continue = false;
			}
			else
			{
				$continue = true;
			}
		}
		
		if ( $continue )
		{
			if ( strlen( $_POST['reply'] ) < 3 )
			{
				$_SESSION['error'] = "Your post must be longer than three characters.";
			}
			elseif( strlen( $_POST['subject'] ) < 3 || strlen( $_POST['subject'] ) > 75 )
			{
				$_SESSION['error'] = "The subject of your topic must be between 3 and 75 characters in length.";
			}
			else
			{
				$subject = str_replace( array( "<", ">" ), array( "&lt;", "&gt;" ), $_POST['subject'] );
				$subject = addslashes( $subject );

				$message = str_replace( array( "<", ">" ), array( "&lt;", "&gt;" ), $_POST['reply'] );
				$message = addslashes( str_replace( Chr(13), "<br />", $message ) );		

				if ( $db->query( "INSERT INTO forums_topics SET f_id=". $db->prepval( $forum['id'] ) .", poster_id=". $db->prepval( getUserID() ) .", name=". $db->prepval( $subject ) .", last_activity=". $db->prepval( time() ) ) !== false )
				{
					$t_id = mysql_insert_id();				

					// Construct the query
					if ( $db->query( "INSERT INTO forums_replies SET f_id=". $db->prepval( $forum['id'] ) .", t_id=". $db->prepval( $t_id ) .", account_id=". $db->prepval( getUserID() ) .", message=". $db->prepval( $message ) ) !== false )
					{
						$db->query( "UPDATE forums_users SET num_posts=num_posts+1 WHERE account_id=" . $db->prepval( getUserID() ) );
						$posted = true;
					}
					else
					{
						// Roll back the topic addition
						$db->query( "DELETE FROM forums_topics WHERE id=". $db->prepval( $t_id ) );

						$_SESSION['error'] = "An error occurred on adding your new topic. Contact the administrators.";
						$posted = false;
					}
				}
				else
				{
					$_SESSION['error'] = "An error occurred on adding your new topic. Contact the administrators.";
					$posted = false;
				}
			}
		}			
	}

	unset( $_SESSION['ingame'] );

	include_once "../includes/header.php";
	include_once "../includes/forums.inc.php";	

	if ( $posted )
	{
		?>
		<div class="title">
			<div style="margin-left: 10px;">Topic Posted</div>
		</div>
		<div class="content" style="text-align: center;">
			<h1>Your topic '<?=stripslashes( $subject );?>' was posted successfully</h1>
			<p>Your topic will appear right at the top of the topics list, you can now choose to either:</p>
			<p><a href="viewtopic.php?id=<?=$t_id;?>">View the topic</a> <br /> <a href="index.php">Return to the forum index</a>
		</div>
		<?
		include_once "../includes/footer.php";
		exit;
	}

	?>
	<h1>Post new topic in forum: '<?=stripslashes( $forum['name'] );?>'</h1>
	<p>You are about to post a reply on the Red Republic forums. Please note that, although we encourage activity on these forums, we are also strict in monitoring them. If your post trespasses our <a href="../docs.php?type=frules">forum rules</a> your post may be modified or even deleted. If your post is a severely breaching our forum rules, administrators may even resort to suspending your account for an indefinite amount of time.</p>

	<form action="newtopic.php?id=<?=$forum['id'];?>" method="post">

		<? if ( !user_auth() ) { ?>
		<div class="title">
			<div style="margin-left: 10px;">Log in with your Red Republic account</div>
		</div>
		<div class="content">
			You are not yet logged in. Please note that in order to reply or create topics on these forums you will need to login. You can still create this topic by providing us with your account details. If they are correct your topic will be posted.<br /><br />
			<input type="text" class="std_input" name="usr" value="Username..." onclick="this.value='';this.onclick=null;" style="width: auto; margin-right: 10px;" />
			<input type="password" class="std_input" name="pwd" value="Password..." onclick="this.value='';this.onclick=null;" style="width: auto; margin-right: 10px;" />
		</div>
		<? } ?>

		<div class="title">
			<div style="margin-left: 10px;">Post new topic</div>
		</div>
		<div class="content" style="padding: 0px;">

			<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
				<table class="row">
					<tr>							
						<td class="field" style="width: 125px; border-right: solid 1px #bb6;">&nbsp;</td>
						<td class="field"><strong>New Reply</strong></td>
					</tr>
				</table>
			</div>

			<div class="row">
				<table class="row">
					<tr>
						<td class="field" style="width: 125px; border-right: solid 1px #bb6; vertical-align: top; padding-right: none;">				
							<div style="height: 20px; text-align: right; margin: 5px; margin-top: 2px;"><strong>Subject:</strong></div>
							<div style="height: 20px; text-align: right; margin: 5px; margin-top: 2px;"><strong>Message:</strong></div>							
							<div class="title" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: solid 1px #bb6; margin-right: 5px;"><div style="margin-left: 10px;">Smileys</div></div>
							<div class="content" style="background-color: #ee9; border: solid 1px #bb6; border-top: none; margin-right: 5px;">
							<?
							$emo = getEmoticonArrays( false );
							for ( $i = 0; $i < count( $emo[0] ) / 5; $i++ )
							{
								if ( isset( $emo[0][$i*5+0] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+0] ."\" alt=\"". $emo[0][$i*5+0] ."\" title=\"". $emo[0][$i*5+0] ."\" />";
								if ( isset( $emo[0][$i*5+1] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+1] ."\" alt=\"". $emo[0][$i*5+1] ."\" title=\"". $emo[0][$i*5+1] ."\" />";
								if ( isset( $emo[0][$i*5+2] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+2] ."\" alt=\"". $emo[0][$i*5+2] ."\" title=\"". $emo[0][$i*5+2] ."\" />";
								if ( isset( $emo[0][$i*5+3] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+3] ."\" alt=\"". $emo[0][$i*5+3] ."\" title=\"". $emo[0][$i*5+3] ."\" />";
								if ( isset( $emo[0][$i*5+4] ) ) echo "<img src=\"../images/emoticons/". $emo[1][$i*5+4] ."\" alt=\"". $emo[0][$i*5+4] ."\" title=\"". $emo[0][$i*5+4] ."\" />";
								echo "<br />";
							}
							?>
							</div>
							<span style="font-size: 10px;"><i>Hover over the smileys to see how to make them appear in your reply!</i></span>
						</td>

						<td class="field" style="padding: 0px; vertical-align: top; padding: 5px;">		
							<input type="text" name="subject" class="std_input" style="width: auto;" /><br />
							<textarea name="reply" class="std_input" style="margin-top: 5px; width: 100%; min-height: 300px;"></textarea>
						</td>
					</tr>
				</table>
			</div>

			<div class="row" style="text-align: center;">
				<input type="submit" class="std_input" name="newtopic" value="Submit Topic" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-top: 5px;" />				
			</div>
			
		</div>

	</form>
	<?

	include_once "../includes/footer.php";
}
?>