<?
$ext_style = "forums_style.css";
include_once "../includes/utility.inc.php";
unset( $_SESSION['ingame'] );
include_once "../includes/header.php";
include_once "../includes/forums.inc.php";
?>
	<h1>Welcome to the Red Republic forums</h1>

	<p>Welcome to the Red Republic forums. This is the forums index page, from where you can select forums that you wish to read. In order to be able to post on these forums you are required to have successfully registered an account with us. Please note that we wish to promote a nice community, so be sure to have read our <a href="../docs.php?type=frules">forum rules</a>. Happy posting!</p>

	<?
	// Fetch the information about forum categories
	$catres = $db->query( "SELECT * FROM forums_categories" );

	if ( $db->getRowCount( $catres ) == 0 )
	{
		?>
			<div class="title">
				<div style="margin-left: 10px;">No categories found</div>
			</div>
			<div class="content">
				No forum categories were found in the database. If you see this message in conjunction with an error, please alert the administrator(s).
			</div>
		<?
	}
	else
	{
		while ( $cat = $db->fetch_array( $catres ) )
		{
			if ( $cat['auth_level'] <= getUserRights() )
			{
			?>
				<div class="title">
					<div style="margin-left: 10px;"><?=$cat['name'];?></div>
				</div>
				<div class="content" style="padding: 0px; margin-bottom: 30px;">
					<?
						// Select forums within this category
						$forumres = $db->query( "SELECT * FROM forums_forums WHERE cat_id=". $db->prepval( $cat['id'] ) );

						if ( $db->getRowCount( $forumres ) == 0 )
						{
							echo "<div class=\"content\" style=\"border: none;\">No forums were found within category '". $cat['name'] ."'</div>\n";
						}
						else
						{
							?>
								<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
									<table class="row">
										<tr>
											<td class="field" style="width: 42px;">&nbsp;</td>
											<td class="field"><strong>Forum name</strong></td>
											<td class="field" style="width: 10%;"><strong># Topics</strong></td>
											<td class="field" style="width: 10%;"><strong># Replies</strong></td>
											<td class="field" style="width: 25%;"><strong>Last poster info</strong></td>
										</tr>
									</table>
								</div>
							<?
							while ( $forum = $db->fetch_array( $forumres ) )
							{
								$f = getForumInfo( $forum['id'] );
								$t = getTopicInfo( $f['lasttopicid'] );

								if ( $forum['auth_level'] > getUserRights() )
									continue;
								?>
									<div class="row">
										<table class="row">
											<tr>
												<td class="field" style="width: 42px;">
												<?
												$src = $rootdir . "/images/forums_old.png";
												// Loop through all topics to check if there's one that wasn't seen yet...
												$q = $db->query( "SELECT * FROM forums_topics WHERE f_id=". $db->prepval( $forum['id'] ) );
												while ( $s = $db->fetch_array( $q ) )
												{
													$r = getTopicInfo( $s['id'] );
													if ( !hasSeenTopic( getUserID(), $r ) )
													{
														$src = $rootdir . "/images/forums_new.png";
														break;
													}
												}
												?>
												<img src="<?=$src;?>" alt="" style="margin-left: 5px;" />
												</td>
												<td class="field"><a href="viewforum.php?id=<?=$forum['id'];?>"><?=stripslashes( $forum['name'] );?></a></td>
												<td class="field" style="width: 10%;">#<?=$f['numtopics'];?></td>
												<td class="field" style="width: 10%;">#<?=$f['numreplies'];?></td>
												<td class="field" style="width: 25%;">
													<strong>In:</strong> 
													<?
														if ( $f['lasttopic'] != "..." )
														echo "<a href=\"viewtopic.php?id=". $f['lasttopicid'] ."\">";
														echo ( strlen( stripslashes( $f['lasttopic'] ) ) > 25 ) ? substr( stripslashes( $f['lasttopic'] ), 0, 25 ) . "..." : stripslashes( $f['lasttopic'] );
														if ( $f['lasttopic'] != "..." )
														echo "</a>\n";
													?><br />
													<strong>By:</strong> <?=$t['lastposter'];?>
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
			}
		}		
	}

	?>
<?
include "../includes/footer.php";
?>