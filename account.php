<?
include_once "includes/utility.inc.php";

if ( isset( $_GET['ajaxrequest'] ) )
{
	if ( $_GET['ajaxrequest'] == "avatar" )
	{
		if ( !user_auth() )
		{
			echo "error::You are not logged in. You have to be logged in to complete this action.";
			exit;
		}
		
		$img = getimagesize( $_GET['avatar'] );

		if ( $img[0] > 300 || $img[1] > 300 )
		{
			echo "error::Your avater source image may not be larger than 300x300 pixels for bandwidth issues!";
			exit;
		}
		
		$db->query( "UPDATE accounts INNER JOIN forums_users ON accounts.id = forums_users.account_id SET forums_users.avatar=". $db->prepval( $_GET['avatar'] ) ." WHERE accounts.username=". $db->prepval( $_SESSION['username'] ) );

		echo "success::Your avatar was successfully updated!::";
		echo ( $_GET['avatar'] == "" ) ? "images/cs_avatar.bmp" : $_GET['avatar'];
	}

	if ( $_GET['ajaxrequest'] == "accdata" )
	{
		$changepw = false;

		if ( !user_auth() )
		{
			echo "error::You are not logged in. You have to be logged in to complete this action.";
			exit;
		}		
		
		if ( !check_email_address( $_GET['email'] ) )
		{
			echo "error::Your e-mail address should be in the format of name@domain.com.";
			exit;
		}

		if ( $_GET['pw'] != "" || $_GET['pw1'] != "" )
		{
			if ( !ctype_graph( $_GET['pw'] ) || strlen( $_GET['pw'] ) < 6 || strlen( $_GET['pw'] ) > 20 )
			{
				echo "error::Your password must contain printable characters with a length between 6 and 20!";
				exit;
			}
			if ( !ctype_graph( $_GET['pw1'] ) || strlen( $_GET['pw1'] ) < 6 || strlen( $_GET['pw1'] ) > 20 )
			{
				echo "error::Your confirmation password must contain printable characters with a length between 6 and 20!";
				exit;
			}
			if ( $_GET['pw'] != $_GET['pw1'] )
			{
				echo "error::Your passwords didn't match!";
				exit;
			}
			
			$changepw = true;
		}
		
		// Construct an update query
		$sql = "UPDATE accounts INNER JOIN persons ON accounts.person_id = persons.id SET persons.email=". $db->prepval( $_GET['email'] );
		if ( $changepw )
			$sql .= ", accounts.password=". $db->prepval( md5( $_GET['pw'] ) );
		$sql .= " WHERE accounts.id=". $db->prepval( getUserID() );

		// Execute the query and check if it was successful.
		if ( $db->query( $sql ) === false )
			echo "error::A database exception occurred, your information wasn't updated.";
		else
			echo "success::Your account details were successfully updated!";		
	}

	exit;
}

if ( !user_auth() )
{
	$_SESSION['error'] = "You need to be logged in to access those pages.";
	header( "Location: login.php" );
	exit;
}

$ext_style = "forums_style.css";

unset( $_SESSION['ingame'] );

if ( $_GET['act'] == "del" && is_numeric( $_GET['id'] ) )
{
	$char = new Player( getUserID() );	
	if ( $char->isAlive() )
	{
		$_SESSION['error'] = "Please keep in mind that you can only delete dead characters!";
	}
	else
	{
		if ( $char->deleteCharacter() !== false )
		{
			$_SESSION['notify'] = "Your character, ". $char->firstname ." ". $char->lastname ." has successfully been deleted.";
		}
		else
		{
			$_SESSION['error'] = "An error occurred on deleting your character. Please contact an administrator.";
		}
	}
}

include_once "includes/header.php";
include_once "includes/forums.inc.php";
?>

<script type="text/javascript" src="javascript/account_js.js"></script>

<h1>
Your account management</h1>
<p>Welcome to your account overview page. From here you can manage some of your personal details (we would like to hear about e-mail changes for example) or forum settings. You can also create a new in-game character from this page. A note of caution: your account is personal and utterly your own responsibility. If your account is found breaking any rules you are responsible for it even though you might not really have broken the rules. Therefore, keep your account details safe and never share them with anyone else.</p>
<?
// ANNOUNCEMENT
// announcement_type:
//	0: None, default
//	1: Update
//	2: Warning
$announceQuery = $db->query( "SELECT * FROM announce WHERE account_id=" . $db->prepval( getUserID() ));
if ( $db->getRowCount( $announceQuery ) > 0 )
{
	$announceAssoc = $db->fetch_assoc( $announceQuery );
	if ( strlen( $announceAssoc['announce_text'] ) > 0 )
	{
		?>
		<table style="width: 100%">
			<tr>
				<td style="width: 95%; vertical-align: top;">
					<div class="title">
						<div style="margin-left: 10px;">Announcement</div>
					</div>
					<div class="content">
						<!-- Need CSS Equiv? -->
						<font color='#D3474A'>
						<?
						if ( $announceAssoc['announce_type'] == '0' )
						{
							printf( "%s", $announceAssoc['announce_text'] );
						} 
						elseif ( $announceAssoc['announce_type'] == '1' ) 
						{
							printf( "<strong>Update:</strong> %s", $announceAssoc['announce_text'] );
						}
						elseif ( $announceAssoc['announce_type'] == '2' )
						{
							printf( "<strong>Warning:</strong> %s", $announceAssoc['announce_text'] );
						}
						?>
						</font>
					</div>
				</td>
			</tr>
		</table>
		<?
		// You only get to read it until expiration date
		if ( time() > strtotime( $announceAssoc['remain_until'] ) )
			$db->query( "DELETE FROM announce WHERE account_id=" . $db->prepval( getUserID() ));
	}
}
// END ANNOUNCMENT
?>
<table style="width: 100%">
	<!-- THE CHARACTER DISPLAY WINDOW, OR, IF NO CHAR FOUND, A CHAR CREATION SCREEN! -->
	<tr>
		<td colspan="2">
		
		<div style="">

		<div class="title">
			<div style="margin-left: 10px;">Your character</div>
		</div>
		<div class="content" style="text-align: center;">
		<?
		$query = $db->query( "SELECT * FROM accounts INNER JOIN char_characters ON char_characters.account_id = accounts.id WHERE accounts.id=". $db->prepval( getUserID() ) );

		if ( $db->getRowCount( $query ) == 0)
		{
		?>
			<strong>No Character Found!</strong><br /><br />
			<img src="<?=$rootdir;?>/images/cs_avatar.bmp" alt="No character!" /><br /><br />
			To create a new character, <a href="createchar.php">click here</a>
		<?
		}
		else
		{
		$char = new Player( getUserID() );			
		?>			
			<!-- THE AVATAR BOX!!! -->
			<table class="row" style="text-align: center; width: 100%; border: solid 1px #bb6; border-spacing: 0px;">
				<tr>
					<td colspan="4">
						<div class="row" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');"><table class="row"><tr><td class="field"><strong>Character Information</strong></td></tr></table></div>
					</td>
					<td>
						<div class="row" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');"><table class="row"><tr><td class="field"><strong>Your Avatar</strong></td></tr></table></div>
					</td>
				</tr>
				<tr>
					<td class="field" style="padding: 5px; width: 20%;"><strong>Nickname:</strong></td>
					<td class="field" style="padding: 5px; width: 30%;"><?=$char->nickname;?></td>
					<td class="field" style="padding: 5px; width: 20%;"><strong>Location:</strong></td>
					<td class="field" style="padding: 5px; width: 30%;"><?=$char->location;?></td>						
					<td rowspan="6" style="border-left: solid 1px #bb6;">
						<table class="row"><tr><td class="field" style="padding-right: 5px;"><img src="<?=$rootdir;?>/images/<?=( $char->avatar_url == "" ? "cs_avatar150x150.png" : $char->avatar_url );?>" style="width: 150px; height: 150px;" alt="<?=$char->nickname;?>" /></td></tr></table>
					</td>
				</tr>				
				<tr>
					<td class="field" style="padding: 5px; width: 20%;"><strong>First name:</strong></td>
					<td class="field" style="padding: 5px; width: 30%;"><?=$char->firstname;?></td>
					<td class="field" style="padding: 5px; width: 20%;"><strong>Money:</strong></td>
					<td class="field" style="padding: 5px; width: 30%;">$<?=$char->money_clean;?></td>
				</tr>
				<tr>
					<td class="field" style="padding: 5px; width: 20%;"><strong>Last name:</strong></td>
					<td class="field" style="padding: 5px; width: 30%;"><?=$char->lastname;?></td>
					<td class="field" style="padding: 5px; width: 20%;"><strong>Dirty money:</strong></td>
					<td class="field" style="padding: 5px; width: 30%;">$<?=$char->money_dirty;?></td>
				</tr>
				<tr>
					<td class="field" style="padding: 5px; width: 20%;"><strong>Gender:</strong></td>
					<td class="field" style="padding: 5px; width: 30%;"><?=$char->gender_full;?></td>
					<td class="field" style="padding: 5px; width: 20%;"><strong>Bank money:</strong></td>
					<td class="field" style="padding: 5px; width: 30%;">$&nbsp;</td>
				</tr>
				<tr>
					<td class="field" style="padding: 5px; width: 20%;"><strong>Birthdate:</strong></td>
					<td class="field" style="padding: 5px; width: 30%;"><?=date( dateDisplay(), strtotime( $char->birthdate ) );?></td>
					<td class="field" style="padding: 5px; width: 20%;"><strong>Occupation</strong></td>
					<td class="field" style="padding: 5px; width: 30%;"><?=$char->occupation;?></td>
				</tr>
				<tr>
					<td class="field" style="padding: 5px; width: 20%;"><strong>Homecity:</strong></td>
					<td class="field" style="padding: 5px; width: 30%;"><?=$char->homecity;?></td>
					<td class="field" style="padding: 5px; width: 20%;"><strong>Rank:</strong></td>
					<td class="field" style="padding: 5px; width: 30%;"><?=$char->rank;?></td>
				</tr>
			</table>
			<br />
			<table class="row" style="width: 100%; border: solid 1px #bb6; border-spacing: 0px;">				
				<tr>
					<td colspan="4"><div class="row" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');"><table class="row"><tr><td class="field"><strong>Character Background</strong></td></tr></table></div></td>
				</tr>
				<tr>
					<td class="field" style="padding: 10px;" colspan="4"><?=stripslashes( str_replace( "\n", "<br />", $char->background ) );?></td>
				</tr>

				<tr>
					<td style="border-top: solid 1px #bb6;" colspan="4"><div class="row" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');"><table class="row"><tr><td class="field"><strong>Character Quote</strong></td></tr></table></div>
						<?
						//Quote Space
						echo $char->getQuote() . '<br /><a style="text-decoration: none;" href="account.edit.quote.php">(Edit Quote)</a>';
						?>
					</td>
				</tr>
				<tr>
					<td class="field" style="padding: 10px;" colspan="4">&nbsp;</td>
				</tr>

				<tr>
					<td style="border-top: solid 1px #bb6;" colspan="4"><div class="row" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');"><table class="row"><tr><td class="field"><strong>Options for '<?=$char->firstname . " " . $char->lastname;?>'</strong></td></tr></table></div></td>
				</tr>
				<tr>					
					<td class="field" style="text-align: center; padding: 10px;" colspan="4">
					<? if ( $char->isAlive() ) { ?>
						<input type="button" class="std_input" value="Play Character" onclick="javascript: window.location='main.php';" />
					<? } else { ?>
						<input type="button" class="std_input" value="Delete Character" onclick="window.location='account.php?act=del&amp;id=<?=$char->character_id;?>';" />
					<? } ?>
					</td>
				</tr>
			</table>
			
		<?
		}
		?>
		</div>
		</div></td>
	</tr>
	<tr>
		<td style="width: 50%; vertical-align: top;">

			<!-- THE GENERAL ACCOUNT WINDOW -->
			<div class="title">
				<div style="margin-left: 10px;">Account display and settings</div>
			</div>
			<div class="content">

				<?
				/* Fetch account details */
				$accres = $db->query( "SELECT * FROM accounts INNER JOIN persons ON accounts.person_id = persons.id WHERE accounts.id=" . $db->prepval( getUserID() ) );
				$user = $db->fetch_array( $accres );
				?>
				<p>Note that in order to change your password you will have to change the password and confirm password fields and click the update link.</p>
				<table style="width: 100%;">
					<tr>
						<td style="width: 30%; text-align: left;">
							<strong>Account name:</strong>
						</td>
						<td style="width: 70%; text-align: left;">
							<?=$user['username'];?>
						</td>
					</tr><tr>
						<td style="width: 30%; text-align: left;">
							<strong>First name:</strong>
						</td>
						<td style="width: 70%; text-align: left;">
							<?=$user['firstname'];?>
						</td>
					</tr><tr>
						<td style="width: 30%; text-align: left;">
							<strong>Last name:</strong>
						</td>
						<td style="width: 70%; text-align: left;">
							<?=$user['lastname'];?>
						</td>
					</tr><tr>
						<td style="width: 30%; text-align: left;">
							<strong>E-mail Address:</strong>
						</td>
						<td style="width: 70%; text-align: left;">
							<input type="text" id="email" class="std_input" value="<?=$user['email'];?>" style="width: 75%;" />
						</td>
					</tr><tr>
						<td style="width: 30%; text-align: left;">
							<strong>Password:</strong>
						</td>
						<td style="width: 70%; text-align: left;">
							<input type="password" id="pwd" class="std_input" style="width: 75%;" />
						</td>
					</tr><tr>
						<td style="width: 30%; text-align: left;">
							<strong>Confirm:</strong>
						</td>
						<td style="width: 70%; text-align: left;">
							<input type="password" id="pwd1" class="std_input" style="width: 75%;" />
						</td>
					</tr><tr>
						<td colspan="2">
							<div style="margin-bottom: 5px; margin-top: 5px;"><a href="account.php" onclick="updateAccData(); return false;">Update Information</a></div>
						</td>
					</tr>
				</table>

			</div>

		</td><td style="width: 50%; vertical-align: top;">

			<!-- THE FORUM WINDOW -->
			<div class="title">
				<div style="margin-left: 10px;">Manage forum settings</div>
			</div>
			<div class="content">

				<?
				/* Get information about this person's forum activities */
				$f = getForumUserInfo( $_SESSION['username'] );
				$r = getForumRanks();
				
				$i = 0;
				while ( $f['num_posts'] >= $r[$i]['num_posts'] )
				{
					$rank = $r[$i++]['rank'];
				}
				?>
				
				<table style="width: 100%;">
					<tr>
						<td style="width: 25%; text-align: left;">
							<strong>Posts made:</strong>
						</td>
						<td style="width: 75%; text-align: left;">
							<?=$f['num_posts'];?> posts!
						</td>
					</tr><tr>
						<td style="width: 25%; text-align: left;">
							<strong>Forum rank:</strong>
						</td>
						<td style="width: 75%; text-align: left;">
							<?=$rank;?>
						</td>
					</tr><tr>
						<td style="width: 25%; text-align: left; vertical-align: top;">
							<strong>Avatar:</strong>
						</td>
						<td style="width: 75%; text-align: left;">
							Please note that your avatar must be 100x100 in size, or it will be resized.<br />
							<img id="avatar" src="<? echo ( $f['avatar'] != "" ) ? $f['avatar'] : "images/cs_avatar.png"; ?>" alt="Avatar" style="width: 100px; height: 100px;" />
						</td>
					</tr><tr>
						<td style="width: 25%; text-align: left;">
							&nbsp;
						</td>
						<td style="width: 75%; text-align: left;">
							<input id="newav" type="text" class="std_input" value="<?=$f['avatar'];?>" style="width: 50%; margin-right: 10px;" /><a href="account.php" onclick="changeAvatar( document.getElementById('newav').value ); return false;">change avatar</a>
						</td>
					</tr>
				</table>

			</div>

		</td>
	</tr>	
</table>

<?
include_once "includes/footer.php";
?>