<?
include_once "includes/utility.inc.php";

if ( isset( $_POST['ajaxrequest'] ) )
{	
	if ( $_POST['ajaxrequest'] == "addcomment" )
	{
		// Check if the passed news ID exists at all
		$idres = $db->query( "SELECT * FROM frontpage_news WHERE id=" . $db->prepval( $_POST['id'] ) );
	
		if ( strlen( $_POST['name'] ) < 3 || strlen( $_POST['name'] ) > 15 || !ctype_alnum( $_POST['name'] ) )
		{
			echo "error::Your name should contain only letters and/or numbers, with a length between 3-15.";
			exit;
		}
		elseif ( !check_email_address( $_POST['email'] ) )
		{
			echo "error::Your email address is not in a valid format!";
			exit;
		}
		elseif ( strlen( $_POST['text'] ) < 3 )
		{
			echo "error::You have to enter at least three characters text!";	
			exit;
		}
		elseif ( strlen( $_POST['text'] ) > 255 )
		{
			echo "error::Your post exceeded the maximum amount of characters (255)!";	
			exit;
		}
		elseif ( $db->getRowCount( $idres ) == 0 )
		{
			echo "error::The news ID provided with your request does not point to any news!";
			exit;
		}
		else
		{
			$id = $_POST['id'];
			$name = htmlspecialchars( $_POST['name'] );
			$email = htmlspecialchars( $_POST['email'] );
			$text = htmlspecialchars( $_POST['text'] );
			$text = str_replace( Chr(13), "<br />", $text );

			// Query db
			$res = $db->query( "INSERT INTO frontpage_comments SET news_id=". $db->prepval( $id ) .", author=". $db->prepval( $name ) .", email=". $db->prepval( $email ) .", comment=". $db->prepval( $text ) .", ip_address=". $db->prepval( $_SERVER['REMOTE_ADDR'] ) );

			if ( $res === false )
			{
				echo "error::An error occurred on placing your comment in the database. Contact the site administrators.";
			}
			else
			{
				$arr = $db->fetch_array( $idres );
				echo "success::Your comment has successfully been added to '". $arr['news_subject'] ."'";
			}
		}
	}
	else
	{		
		echo "error::The server received an unknown request, please try again.";
	}

	exit;
}

if ( isset( $_GET['ajaxrequest'] ) )
{
	if ( $_GET['ajaxrequest'] == "delcomment" )
	{
		// Check if the passed news ID exists at all
		$idres = $db->query( "SELECT * FROM frontpage_comments WHERE id=" . $db->prepval( $_GET['id'] ) );

		if ( !user_auth() )
		{
			echo "error::You are not logged in, you cannot execute this action.";
		}
		elseif ( getUserRights() < USER_MODERATOR )
		{
			echo "error::You do not have sufficient rights to execute this action.";
		}
		elseif ( $db->getRowCount( $idres ) == 0 )
		{
			echo "error::A non-existant comment id was sent with the request!";
		}
		else
		{
			if ( $db->query( "DELETE FROM frontpage_comments WHERE id=". $db->prepval( $_GET['id'] ) ) === false )
			{
				echo "error::An error occurred upon attempting to delete the comment.";
			}
			else
			{
				echo "success::Comment ". $_GET['id'] ." was deleted successfully!";
			}
		}
	}

	if ( $_GET['ajaxrequest'] == "getcomments" )
	{
		// Check if the passed news ID exists at all
		$idres = $db->query( "SELECT * FROM frontpage_news WHERE id=" . $db->prepval( $_GET['id'] ) );

		if ( $db->getRowCount( $idres ) == 0 )
		{
			echo "error::A non-existant news id was sent with the request!";
		}
		else
		{
			$newsres = $db->query( "SELECT * FROM frontpage_news WHERE id=" . $db->prepval( $_GET['id'] ) );
			$news = $db->fetch_array( $newsres );
			$commres = $db->query( "SELECT * FROM frontpage_comments WHERE news_id=" . $db->prepval( $_GET['id'] ) );

			echo "contentID::commentslist::";
				
			if ( $db->getRowCount( $commres ) == 0 )
			{
				?>
				<div class="title">
					<div style="margin-left: 10px;">No comments were found</div>
				</div>
				<div class="content">
					No comments were found for selected news-item: "<?=$news['news_subject'];?>"
				</div>
				<?
			}
			else
			{
				while ( $res = $db->fetch_array( $commres ) )
				{
					?>
					<div class="title">
						<div style="margin-left: 10px;">Comment added by: <a href="mailto:<?=$res['email'];?>"><?=$res['author'];?></a> on <?=date( timeDisplay(), strtotime( $res['date'] ) );?>
						<?
							if ( getUserRights() >= USER_MODERATOR )
								echo " - logged ip: " . $res['ip_address'];
							if ( getUserRights() >= USER_MODERATOR )
								echo " <a href=\"news.php\" onclick=\"delComment( ". $res['id'] .", ". $news['id'] ." ); return false;\">del</a>";
						?>
						</div>
					</div>
					<div class="content">
						<?=$res['comment'];?>
					</div>
					<?
				}
			}
		}
	}

	exit;
}

if ( !isset( $_GET['id'] ) || $_GET['id'] == "" )
{
	header( "Location: index.php" );
	exit;
}
else
{
	// Check if the ID is valid
	if ( $db->getRowCount( $db->query( "SELECT * FROM frontpage_news WHERE id=" . $db->prepval( $_GET['id'] ) ) ) == 0 )
	{
		header( "Location: index.php" );
		exit;
	}
}

unset( $_SESSION['ingame'] );

include_once "includes/forums.inc.php";
include_once "includes/header.php";

?>
<script type="text/javascript" src="javascript/news_js.js"></script>

<h1>Crime Syndicate News</h1>

<p>The Crime Syndicate news pages allow you to view and make comments to certain news items. Please note that this is only a very limited way to provide feedback - if you have an account and wish to provide more feedback you can use our <a href="forums/index.php">forums</a> for that.</p>

<!-- UNDERNEATH THE NEWS WILL BE LISTED, FOLLOWED BY COMMENTS -->
<?
	$newsres = $db->query( "SELECT * FROM frontpage_news WHERE id=" . $db->prepval( $_GET['id'] ) );
	$news = $db->fetch_array( $newsres );

	?>
	<div class="title">
		<div style="margin-left: 10px;"><?=$news['news_subject'];?></div>
	</div>
	<div class="content">
		<span style="font-size: 11px;">- Posted by <?=$news['news_author'];?> on <?=date( timeDisplay(), strtotime( $news['date'] ) );?></span><br /><br />
		<?=str_replace( chr(13), "<br />", stripslashes( $news['news_message'] ) );?>
	</div>

	<?

	$commres = $db->query( "SELECT * FROM frontpage_comments WHERE news_id=" . $db->prepval( $news['id'] ) );
	
	/* THIS IS IMPORTANT, THE CONTAINER DIV FOR COMMENTS! */
	?>
	<div id="commentcontainer">
	<?

	if ( $db->getRowCount( $commres ) == 0 )
	{
		?>
		<div class="title">
			<div style="margin-left: 10px;">No comments were found</div>
		</div>
		<div class="content">
			No comments were found for selected news-item: "<?=$news['news_subject'];?>"
		</div>
		<?
	}
	else
	{
		while ( $res = $db->fetch_array( $commres ) )
		{
			?>
			<div class="title">
				<div style="margin-left: 10px;">Comment added by: <a href="mailto:<?=$res['email'];?>"><?=$res['author'];?></a> on <?=date( timeDisplay(), strtotime( $res['date'] ) );?>
				<?
					if ( getUserRights() >= USER_MODERATOR )
						echo " - logged ip: " . $res['ip_address'];
					if ( getUserRights() >= USER_MODERATOR )
						echo " <a href=\"news.php\" onclick=\"delComment( ". $res['id'] .", ". $news['id'] ." ); return false;\">del</a>";
				?>
				</div>
			</div>
			<div class="content">
				<?=$res['comment'];?>
			</div>
			<?
		}
	}

	?>
	</div>
	
	<div class="title">
		<div style="margin-left: 10px;">Place a new comment</div>
	</div>
	<div class="content">

		<p>Use the form below to add a new comment to this news item. Please be aware that though no log-in is required you should adhere to the <a href="docs.php?type=frules">forum rules</a> when placing a comment. The maximum size of your post is 255 characters.</p>

		<div class="navcontent" style="border: solid 1px #990033; margin: 0px; width: 60%; margin-right: 19%; margin-left: 19%;">
			<form action="news.php" method="post" onsubmit="addComment( <?=$news['id'];?>, document.getElementById('comment_name').value, document.getElementById('comment_email').value, document.getElementById('comment_text').value ); return false;">
				<input type="hidden" name="id" value="<?=$_GET['id'];?>" />
				<input type="text" class="std_input" id="comment_name" value="Your name..." style="min-width: 150px; width: auto;" onclick="this.value=''; this.onclick=null;" /><br />
				<input type="text" class="std_input" id="comment_email" value="Your email..." style="min-width: 150px; width: auto;" onclick="this.value=''; this.onclick=null;" /><br />
				<textarea class="std_input" style="width: 100%; min-height: 150; height: auto;" maxlength="255" id="comment_text"></textarea><br /><br />

				<input type="submit" class="std_input" value="Publish" />

			</form>
		</div>

	</div>
	<?
	

?>

<?
include "includes/footer.php";
?>