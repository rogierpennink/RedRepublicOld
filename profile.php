<?
/**
 * Profile.php - Shows any users profile.
 */
$REDIRECT = true;
$CHARNEEDED = true;
$AUTH_LEVEL = 0;
$nav = "profile";
$_SESSION['ingame'] = true;

include_once "includes/utility.inc.php";

$characterid = $_GET["id"];

// Making sure Character id is numeric. Stupid how much work you have to put in now to stop hacking!
if( !is_numeric( $characterid ) )
{
	$_SESSION["error"] = "Character does not exist (no ID parameter).";
	header( "Location: main.php" );
	die;
} 
else 
{
	//Fetching information
	$sql = "SELECT * FROM char_characters WHERE id=". $characterid;
	$result = $db->fetch_array( $db->query( $sql ) );
	$nickname = $result['nickname'];
	$firstname = $result['firstname'];
	$lastname = $result['lastname'];
	$homecityno = $result['homecity'];
	$moneyclean = $result['money_clean'];
	$moneydirty = $result['money_dirty'];
	$gender = $result['gender'];
	$accountid = $result['account_id'];
	$rankid = $result['rank_id'];
	$quote = $result['quote'];
	$background = $result['background'];
	$health = $result['health'];
	$maxhealth = $result['maxhealth'];
	$locationno = $result['location'];

	//Getting Location	
	$sql = "SELECT * FROM locations WHERE id=". $homecityno;
	$result = $db->getRowsFor( $sql );
	$homecity = $result['location_name'];
	$sql = "SELECT * FROM locations WHERE id=". $locationno;
	$result = $db->getRowsFor( $sql );
	$location = $result['location_name'];

	//Getting Last Online Time & Username
	$sql = "SELECT * FROM accounts WHERE id=". $accountid;
	$result = $db->getRowsFor( $sql );
	$lastonline = $result['last_active'];
	$stillonline = $lastonline + 120;
	$username = $result['username'];

	//Getting Character Occupation + Rank
	$sql = "SELECT * FROM char_ranks WHERE id=". $rankid;
	$result = $db->getRowsFor( $sql );
	$occupationid = $result['occupation_id'];
	$rank = $result['rank_name'];
	$avatar = $result['rank_image'];
	$sql = "SELECT * FROM char_occupations WHERE id=". $occupationid;
	$result = $db->getRowsFor( $sql );
	$occupation = $result['occ_name'];

	//Money Calculations
	$numplayers = $db->getRowCount( $db->query( "SELECT * FROM char_characters" ) );
	$totalcmoney = $db->fetch_array( $db->query( "SELECT SUM(money_clean) FROM char_characters;" ) );
	$totaldmoney = $db->fetch_array( $db->query( "SELECT SUM(money_dirty) FROM char_characters;" ) );
	$totalmoney = $totalcmoney[0] + $totaldmoney[0];
	$playermoneytotal = $moneyclean + $moneydirty;
	$wealthaverage = $totalmoney / $numplayers;
	$wealthpoor = $wealthaverage / 3;
	$wealthrich = $wealthaverage * 1.5;
	$wealthwealthy = $wealthaverage * 2;
}

include_once "includes/header.php";

?>
<div style="min-width: 50%;">
	
	<div class="title">
		<div style="margin-left: 10px;">Player Profile</div>
	</div>

	<div class="content" style="padding: 0px;">

	<table class="row" style="width: 100%; border: solid 1px #bb6; border-spacing: 0px; margin-bottom: 10px;">
		<tr>
			<td colspan="4">
				<div class="row" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');"><table class="row"><tr><td class="field"><strong>Player's Profile</strong></td></tr></table></div>
			</td>
			<td>
				<div class="row" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');"><table class="row"><tr><td class="field"><strong>Player's Avatar</strong></td></tr></table></div>
			</td>
		</tr>
		<tr>
			<td class="field" style="padding: 5px; width: 20%;"><strong>Username:</strong></td>
			<td class="field" style="padding: 5px; width: 30%;"><?php echo $username;?></td>
			<td class="field" style="padding: 5px; width: 20%;"><strong>Wealth:</strong></td>
			<td class="field" style="padding: 5px; width: 30%;"><?php if ($playermoneytotal >= $wealthwealthy){ echo "Wealthy"; 	} elseif ($playermoneytotal >= $wealthrich){ echo "Rich"; } elseif ($playermoneytotal >= $wealthaverage){ echo "Average"; } elseif ($playermoneytotal >= $wealthpoor) { echo "Poor"; 	}	else { 	echo "Bum"; } ?>
			</td>						
			<td rowspan="6" style="border-left: solid 1px #bb6;">
				<table class="row"><tr><td class="field" style="padding-right: 5px;"><img src="<?=$rootdir;?>/images/<?=( $avatar == "" ? "cs_avatar150x150.png" : $avatar );?>" style="width: 150px; height: 150px;" alt="<?php echo $username; ?>" /></td></tr></table>
			</td>
		</tr>				
		<tr>
			<td class="field" style="padding: 5px; width: 20%;"><strong>First name:</strong></td>
			<td class="field" style="padding: 5px; width: 30%;"><?php echo $firstname; ?></td>
			<td class="field" style="padding: 5px; width: 20%;"><strong>Occupation:</strong></td>
			<td class="field" style="padding: 5px; width: 30%;"><?php echo $occupation; ?></td>
		</tr>
		<tr>
			<td class="field" style="padding: 5px; width: 20%;"><strong>Last name:</strong></td>
			<td class="field" style="padding: 5px; width: 30%;"><?php echo $lastname; ?></td>
			<td class="field" style="padding: 5px; width: 20%;"><strong>Rank:</strong></td>
			<td class="field" style="padding: 5px; width: 30%;"><?php echo $rank; ?></td>
		</tr>
		<tr>
			<td class="field" style="padding: 5px; width: 20%;"><strong>Gender:</strong></td>
			<td class="field" style="padding: 5px; width: 30%;"><?php if($gender == 'm'){echo "Male";}elseif($gender == 'f'){echo "Female"; } else { echo "Hacker"; } ?></td>
			<td class="field" style="padding: 5px; width: 20%;"></td>
			<td class="field" style="padding: 5px; width: 30%;"></td>
		</tr>
		<tr>
			<td class="field" style="padding: 5px; width: 20%;"><strong>Last Online:</strong></td>
			<td class="field" style="padding: 5px; width: 30%;"><?php echo date(timeDisplay(), $lastonline);?></td>
			<td class="field" style="padding: 5px; width: 20%;"></td>
			<td class="field" style="padding: 5px; width: 30%;"></td>
		</tr>
		<tr>
			<td class="field" style="padding: 5px; width: 20%;"><strong>Homecity:</strong></td>
			<td class="field" style="padding: 5px; width: 30%;"><?php echo $homecity; ?></td>
			<td class="field" style="padding: 5px; width: 20%;"></td>
			<td class="field" style="padding: 5px; width: 30%;"></td>
		</tr>
	</table>

	<table class="row" style="width: 100%; border: solid 1px #bb6; border-spacing: 0px; text-align: center; margin-bottom: 10px;">
		<tr>
			<td>
				<div class="row" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');">
					<table class="row">
					<tr>
						<td class="field"><strong>Player's Backround</strong></td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td class="field" style="padding: 10px; width: 100%;">
				<div style="text-align: center;"><?php echo $background;?></div>
			</td>						
		</tr>				
	</table>

	<? 
	/* Don't display empty table if the character's quote still doesn't say anything */
	if ( strlen( $quote ) > 0 ):
	?>
	<table class="row" style="width: 100%; border: solid 1px #bb6; border-spacing: 0px; text-align: center; margin-bottom: 10px;">
		<tr>
			<td>
				<div class="row" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');"><table class="row"><tr><td class="field"><strong>Player's Quote</strong></td></tr></table></div>
			</td>
		</tr>
		<tr>
			<td class="field" style="padding: 10px; width: 100%;">
			<div style="text-align: center;">
				<?=stripslashes( str_replace( "\n", "<br />", $quote ) );?>
			</div>
			</td>						
		</tr>				
	</table>
	<? 
	endif;

	/* Don't display empty table if the character doesn't have any vices or virtues yet. */
	if ( hasPersonality( $_GET['id'] ) ): 
	?>
	<table class="row" style="width: 100%; border: solid 1px #bb6; border-spacing: 0px; text-align: center;">
		<tr>
			<td>
				<div class="row" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');">
					<table class="row">
					<tr>
						<td class="field">
							<img src='<?=$rootdir;?>/images/hammer.png' alt='' /> <strong>Deeds and Personality</strong>
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td class="field" style="padding: 10px; width: 100%;">
			<?
			/* Get Deeds for slot a through p */
			 getDeeds( $_GET['id'], true );
			?>
			</td>						
		</tr>
	</table>
	<? 
	endif;

	if( getUserRights() == USER_SUPERADMIN ):
	?>
	<br />
	<table class="row" style="width: 100%; border: solid 1px #bb6; border-spacing: 0px; text-align: center;">
		<tr>
			<td colspan="4">
				<div class="row" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png');">
					<table class="row">
					<tr>
						<td class="field"><strong>Admin</strong></td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td class="field" style="padding: 5px; width: 20%;"><strong>Clean Money:</strong></td>
			<td class="field" style="padding: 5px; width: 80%;">$<?php echo $moneyclean; ?></td>
		</tr>				
		<tr>
			<td class="field" style="padding: 5px; width: 20%;"><strong>Dirty Money:</strong></td>
			<td class="field" style="padding: 5px; width: 80%; ">$<?php echo $moneydirty; ?></td>
		</tr>
		<tr>
			<td class="field" style="padding: 5px; width: 20%;"><strong>Health:</strong></td>
			<td class="field" style="padding: 5px; width: 80%;"><?php echo $health . "/" . $maxhealth; ?></td>
		</tr>
		<tr>
			<td class="field" style="padding: 5px; width: 20%;"><strong>Location:</strong></td>
			<td class="field" style="padding: 5px; width: 80%;"><?php echo $location; ?></td>
		</tr>
		<? 
		/* Admin Rank */
		if ( getUserRights( $username ) > 0 ) 
		{ 
			?>
				<tr>
					<td class="field" style="padding: 5px; width: 20%;"><img src='<?=$rootdir;?>/images/<?=rightsImage( getUserRights( $username ) );?>' border='0' alt='Admin' title='This player is an administrator, the image represents what rank he or she is.' /></td>
				</tr>
			<?
		}
		?>
	</table>		
	<? endif; ?>

	</div>
</div>
<div><!-- Spacer --></div>
<?
include_once "includes/footer.php";
?>