<?
$ext_style = "forums_style.css";

// Before generating any output we have to check if an id was sent. If this
// is not the case we'll redirect back to index.php and generate an error message.
if ( !isset( $_GET['id'] ) || $_GET['id'] == "" )
{
	session_start();
	$_SESSION["error"] = "You tried to view a forum without ID, if you got this error by pressing a link - contact the administrators.";	
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
		$_SESSION["error"] = "You tried to view a forum with a false ID, if you got this error by pressing a link - contact the administrators.";	
		header( "Location: index.php" );
		exit;
	}

	$forum = $db->fetch_array( $forumres );

	// Check if the user has the rights to visit this page
	if ( $forum['auth_level'] > getUserRights() )
	{
		$_SESSION["error"] = "You tried to view a forum that you haven't got access to.";	
		header( "Location: index.php" );
		exit;
	}

	// Data for the LIMIT keyword in MySQL
	$num = 20;
	if ( $_GET['pag'] == "" || $_GET['pag'] == "1" || !isset( $_GET['pag'] ) ) $min = 0 ; else $min = $_GET['pag'] - 1;	
	$min *= $num;

	// Data for the paging part of the page
	$max = $db->getRowCount( $db->query( "SELECT * FROM forums_topics WHERE f_id=". $db->prepval( $forum['id'] ) ) );

	unset( $_SESSION['ingame'] );

	include_once "../includes/header.php";
	include_once "../includes/forums.inc.php";
	?>

	<!-- The forum navigation -->
	<div class="title">
		<div style="margin-left: 10px;">Forum Navigation</div>
	</div>
	<div class="content">
		<a href="index.php">Forum Index</a> &#187; <a href="viewforum.php?id=<?=$forum['id'];?>"><?=stripslashes( $forum['name'] );?></a>
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
				echo "<a href=\"viewforum.php?id=". $forum['id'] ."&amp;pag=". ( $i + 1 ) ."\">";

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
	<input type="button" class="std_input" value="New Topic" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px;" onclick="window.location = 'newtopic.php?id=<?=$forum['id'];?>';" />
	</div>

	<!-- The forum itself -->
	<div class="title">
		<div style="margin-left: 10px;">Forum: <?=stripslashes( $forum['name'] );?></div>
	</div>
	<div class="content" style="padding: 0px;">
		<?
			$topicres = $db->query( "SELECT * FROM forums_topics WHERE f_id=". $db->prepval( $forum['id'] ) ." ORDER BY topic_type DESC, last_activity DESC LIMIT ". $db->prepval( $min ) .", ". $db->prepval( $num ) );

			if ( $db->getRowCount( $topicres ) == 0 )
			{
				echo "<div class=\"content\" style=\"border: none;\">No topics were found in the '". stripslashes( $forum['name'] ). "' forum...</div>";
			}
			else
			{
				?>
				<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
					<table class="row">
						<tr>							
							<td class="field" colspan="2"><strong>Topic name</strong></td>
							<td class="field" style="width: 15%;"><strong>Topic starter</strong>
							<td class="field" style="width: 10%;"><strong># Replies</strong></td>
							<td class="field" style="width: 10%;"><strong># Views</strong></td>
							<td class="field" style="width: 15%;"><strong>Last action</strong></td>
						</tr>
					</table>
				</div>
				<?
				while ( $topic = $db->fetch_array( $topicres ) )
				{
					$t = getTopicInfo( $topic['id'] );
					?>
						<div class="row">
							<table class="row">
								<tr>
									<td class="field" style="width: 42px;">
									<?
									if ( hasSeenTopic( getUserID(), $t ) )
									$src = $rootdir . "/images/forums_old.png";
									else
									$src = $rootdir . "/images/forums_new.png";

									if ( $topic['topic_type'] == TOPIC_STICKY ) $src = $rootdir . "/images/forums_sticky.png";
				
									echo "<img src=\"" . $src . "\" alt=\"\" style=\"margin-left: 5px;\" /></td>";
									?>
									<td class="field"><a href="viewtopic.php?id=<?=$topic['id'];?>"><?=stripslashes( $topic['name'] );?></a></td>
									<td class="field" style="width: 15%;"><?=$t['topicstarter'];?></td>
									<td class="field" style="width: 10%;">#<?=($t['numreplies'] - 1);?></td>
									<td class="field" style="width: 10%;">#<?=$t['numviews'];?></td>
									<td class="field" style="width: 15%; font-size: 11px;">
										<i><?=$t['lastreplydate'];?></i><br />
										<u>Post by:</u> <?=$t['lastposter'];?>
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
				echo "<a href=\"viewforum.php?id=". $forum['id'] ."&amp;pag=". ( $i + 1 ) ."\">";

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
	</div>

	<?
	include_once "../includes/footer.php";
}