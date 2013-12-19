<?
$ext_style = "forums_style.css";

// Before generating any output we have to check if an id was sent. If this
// is not the case we'll redirect back to index.php and generate an error message.
if ( !isset( $_GET['id'] ) || $_GET['id'] == "" )
{
	session_start();
	$_SESSION["error"] = "You tried to view a topic without ID, if you got this error by pressing a link - contact the administrators.";	
	header( "Location: index.php" );
}
else // We still can't be sure things are OK, therefore, test validness first
{
	include_once "../includes/utility.inc.php";
	include_once "../includes/forums.inc.php";
	
	// Query
	$topicres = $db->query( "SELECT * FROM forums_topics WHERE id=" . $db->prepval( $_GET['id'] ) );

	// Check if it exists
	if ( $db->getRowCount( $topicres ) == 0 )
	{
		$_SESSION["error"] = "You tried to view a topic with a false ID, if you got this error by pressing a link - contact the administrators.";	
		header( "Location: index.php" );
		exit;
	}

	$topic = $db->fetch_array( $topicres );

	// Get information about the forum that belongs to this topic
	$forumres = $db->query( "SELECT * FROM forums_forums WHERE id=" . $db->prepval( $topic['f_id'] ) );
	
	if ( $db->getRowCount( $forumres ) == 0 )
	{
		$_SESSION["error"] = "You tried to view a topic that is not linked to any forum.";	
		header( "Location: index.php" );
		exit;
	}

	$forum = $db->fetch_array( $forumres );

	// Check if the user has the rights to visit this page
	if ( $forum['auth_level'] > getUserRights() )
	{
		$_SESSION["error"] = "You tried to view a topic that you are not allowed to view.";	
		header( "Location: index.php" );
		exit;
	}

	// Data for the LIMIT keyword in MySQL
	$num = 10;
	if ( $_GET['pag'] == "" || $_GET['pag'] == "1" || !isset( $_GET['pag'] ) ) $min = 0 ; else $min = $_GET['pag'] - 1;	
	$min *= $num;
	
	// Data for the paging part of the page
	$max = $db->getRowCount( $db->query( "SELECT * FROM forums_replies WHERE t_id=". $db->prepval( $topic['id'] ) ) );
	

	// Apparently nothing went wrong, so we can safely add to the number of views
	// Don't check for errors, it's not that important if a view num doesn't get added...
	$db->query( "UPDATE forums_topics SET num_views=num_views+1 WHERE id=". $db->prepval( $topic['id'] ) );

	// We can also safely add, or update information in the posttracker table
	if ( user_auth() )
	{
		$t = getTopicInfo( $topic['id'] );
		$trackres = $db->query( "SELECT * FROM forums_posttracker WHERE account_id=". $db->prepval( getUserID() ) ." AND reply_id=". $db->prepval( $t['lastreplyID'] ) );
		if ( $db->getRowCount( $trackres ) == 0 ) 
		{
			$db->query( "INSERT INTO forums_posttracker SET account_id=". $db->prepval( getUserID() ) .", reply_id=". $db->prepval( $t['lastreplyID'] ) );
		}
		else
		{
			$db->query( "UPDATE forums_posttracker SET last_seen=". $db->prepval( date( "Y-m-d H:i:s", time() ) ) ." WHERE reply_id=". $db->prepval( $t['lastreplyID'] ) ." AND account_id=". $db->prepval( getUserID() ) );
		}
	}
	
	unset( $_SESSION['ingame'] );

	include_once "../includes/header.php";
	include_once "../includes/forums.inc.php";
	
	?>

	<script type="text/javascript" src="<?=$rootdir;?>/javascript/forums_viewtopic_js.js"></script>

	<!-- The forum navigation -->
	<div class="title">
		<div style="margin-left: 10px;">Forum Navigation</div>
	</div>
	<div class="content">
		<a href="index.php">Forum Index</a> &#187; <a href="viewforum.php?id=<?=$forum['id'];?>"><?=stripslashes( $forum['name'] );?></a> &#187; <a href="viewtopic.php?id=<?=$topic['id'];?>"><?=stripslashes( $topic['name'] );?></a>
	</div>

	<?
	if ( $max > $num )
	{
		?>
		<div class="content" style="border: solid 1px #990033;">
		<? for ( $i = 0; $i < ceil( $max/$num ); $i++ )
		{
			$pag = $min / $num;
			
			echo "<span style=\"margin-right: 3px;\">";
			if ( $pag != $i )
				echo "<a href=\"viewtopic.php?id=". $topic['id'] ."&amp;pag=". ( $i + 1 ) ."\">";

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

	<div style="margin-bottom: 5px;">
	<input type="button" class="std_input" value="New Topic" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px; width: auto;" onclick="window.location = 'newtopic.php?id=<?=$forum['id'];?>';" />
	<input type="button" class="std_input" value="Post Reply" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); width: auto;" onclick="window.location = 'reply.php?id=<?=$topic['id'];?>';" />
	<? 
	if ( user_auth() && getUserRights() >= USER_MODERATOR ) 
	{ 
		if ( $topic['topic_type'] == TOPIC_STICKY )	{ $btext = "Unsticky Topic"; $jscript = "unsticky( ". $topic['id'] ." );"; } 
		else { $btext = "Sticky Topic"; $jscript = "sticky( ". $topic['id'] ." );"; }
	?>
	<input type="button" class="std_input" value="<?=$btext;?>" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');" onclick="<?=$jscript;?>" />
	<? } ?>
	</div>	

	<!-- The forum itself -->
	<div class="title">
		<div style="margin-left: 10px;">Topic: <?=stripslashes( $topic['name'] );?></div>
	</div>
	<div class="content" style="padding: 0px;">		
		<?
			$replyres = $db->query( "SELECT * FROM forums_replies INNER JOIN accounts ON forums_replies.account_id = accounts.id INNER JOIN forums_users ON forums_replies.account_id = forums_users.account_id WHERE t_id=". $db->prepval( $topic['id'] ) . " ORDER BY date ASC LIMIT ". $db->prepval( $min ) .",". $db->prepval( $num ) );

			if ( $db->getRowCount( $replyres ) == 0 )
			{
				$_SESSION["error"] = "No replies were found in topic: '". $topic['name'] ."'";	
				echo "<script type=\"text/javascript\">window.location = 'viewforum.php?id=". $forum['id'] ."';</script>\n";
				exit;
			}
			else
			{
				?>
				<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
					<table class="row">
						<tr>							
							<td class="field" style="width: 125px; border-right: solid 1px #bb6;"><strong>Author</strong></td>
							<td class="field"><strong>Message</strong></td>
						</tr>
					</table>
				</div>
				<?
				while ( $reply = $db->fetch_array( $replyres ) )
				{					
					?>
						<div class="row">
							<table class="row">
								<tr>
									<td class="field" style="width: 125px; border-right: solid 1px #bb6; vertical-align: top;">
										<strong><?=$reply['username'];?></strong>

										<? 
										if ( $reply['avatar'] != "" ) 
											$image = $reply['avatar'];
										else
											$image = $rootdir . "/images/cs_avatar.png";

										$r = getForumRanks();
				
										$i = 0;
										while ( $reply['num_posts'] >= $r[$i]['num_posts'] )
										{
											$rank = $r[$i++]['rank'];
										}
										?>
										<img src="<?=$image;?>" alt="No avatar" style="width: 100px; height: 100px; margin-left: 11.5px; margin-top: 10px;" />

										<div style="font-size: 11px; margin-top: 10px; margin-bottom: 30px;">
											
											<span style="color: #990033;">Posts: <?=$reply['num_posts'];?></span><br />
											<span style="color: #990033;">Rank: <?=$rank;?></span><br />
											<? $test = ( time() - $reply['last_active'] >= 600 ); ?>
											<span style="color: #<?=( $test ) ? "990033;" : "339933;";?>">Status: <?=( $test ) ? "Not online" : "Online"; ?></span><br />
											<?
											/* Admin Insignia */
											if ( getUserRights( $reply['username'] ) > 0 )
											{
												?>
												<span><img src='<?=$rootdir;?>/images/<?=rightsImage( getUserRights( $reply['username'] ) );?>' border='0' alt='Admin' title='This user is an administrator, the image represents what rank he or she is.' /></span>
												<?
											}
											?>
										
										</div>
									</td>

									<td class="field" style="padding: 0px; vertical-align: top;">
										<div class="row" style="border: none; background-color: #ee9; font-size: 11px; min-height: 20px;">

										<input type="button" class="std_input" value="Reply" style="float: right; background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-top: 1px; width: auto;" onclick="window.location = 'reply.php?id=<?=$topic['id'];?>';" />
										<input type="button" class="std_input" value="Quote" style="margin-right: 3px; float: right; background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-top: 1px;" />
										<?
										if ( getUserRights() >= USER_MODERATOR || getUserID() == $reply['account_id'] )
										{
											?><input type="button" class="std_input" value="Edit" style="margin-right: 3px; float: right; background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-top: 1px;" onclick="window.location='edit.php?id=<?=$reply[0];?>';" /><?
										}
										?>
										<img src="<?=$rootdir;?>/images/small_page.gif" alt="" /> - Posted on <?=date( timeDisplay(), strtotime( $reply['date'] ) );?>
										</div>
										<div style="padding: 5px; font-family: Tahoma; font-size: 12px;">
										<?
											$msg = $reply['message'];
											$msg = stripslashes( $msg );
											$msg = addEmoticons( $msg, $rootdir );
											$msg = addBBCode( $msg );

											echo $msg;										
										?>
										</div>
									</td>
								</tr>
							</table>
						</div>
					<?
				}
			}
		?>
	</div>

	<?
	if ( $max > $num )
	{
		?>
		<div class="content" style="border: solid 1px #990033;">
		<? for ( $i = 0; $i < ceil( $max/$num ); $i++ )
		{
			$pag = $min / $num;

			echo "<span style=\"margin-right: 3px;\">";
			if ( $pag != $i )
				echo "<a href=\"viewtopic.php?id=". $topic['id'] ."&amp;pag=". ( $i + 1 ) ."\">";

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

	<div style="margin-bottom: 30px;">
	<input type="button" class="std_input" value="New Topic" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px;" onclick="window.location = 'newtopic.php?id=<?=$forum['id'];?>';" />
	<input type="button" class="std_input" value="Post Reply" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');" onclick="window.location = 'reply.php?id=<?=$topic['id'];?>';" />
	<? 
	if ( user_auth() && getUserRights() >= USER_MODERATOR ) 
	{ 
		if ( $topic['topic_type'] == TOPIC_STICKY )	{ $btext = "Unsticky Topic"; $jscript = "unsticky( ". $topic['id'] ." );"; } 
		else { $btext = "Sticky Topic"; $jscript = "sticky( ". $topic['id'] ." );"; }
	?>
	<input type="button" class="std_input" value="<?=$btext;?>" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');" onclick="<?=$jscript;?>" />
	<? } ?>
	</div>	

	<?
	include_once "../includes/footer.php";
}