<?
 // support.php - A support center :)
 unset( $_SESSION['ingame'] );
 include "includes/utility.inc.php";
 include "includes/header.php";

$wikienabled = $db->getRowsFor( "SELECT value FROM settings_general WHERE setting='wikienabled'" );
$wikilink = $db->getRowsFor( "SELECT value FROM settings_general WHERE setting='wikilink'" );
?>

<h1>Red Republic Support</h1>
<p>If you are new to Red Republic, or just not entirely sure about how things work you can try to find out if your questions are answered in this support section. A number of ways to gain information are available to you. From this page you can choose to visit the 'book' of frequently asked questions (The FAQ Book), to visit the player guides sections where guides have been added, describing the game in detail, or you can choose to send a support request directly to our support e-mail: support@red-republic.com. Please note, though not listed here, the forums may be a great source of help as well!</p>
 
 <div class="title">
	<div style="margin-left: 10px;">Red Republic FAQ Book</div>
 </div>
 <div class="content">
 <p>The FAQ Book is a large collection of questions and answers compiled by experienced players, moderators, and administrators. Its probably a good idea to give this a scan before asking a question.<br /><br /><a href='faqbook.php' style='text-decoration: none;'>Enter the FAQ Book</a></p>
 </div>
 <div class="title">
	<div style="margin-left: 10px;">Red Republic Player Guides</div>
 </div>
 <div class="content">
 <p>This section is similar to the FAQ book, but contains long and detailed texts concerning various parts of the game, how to do this and that, and other articles.<br /><br /><a href='playerguides.php' style='text-decoration: none;'>Enter the Player Guides</a></p>
 </div>
<div style="width: 100%; border: solid 1px; background-color: #ffc;">
	<div class="row" style="background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border-bottom: solid 1px;">
		<div style="margin-left: 10px;"><b>Support Staff</b></div>
	 </div>
	 <div class="content" style="border: solid 0px;">
	 <p>
	 Below is a list of our volunteer support staff members listed by their account username.<br /><br /><ul>
	 <?
	 $sq = $db->query( "SELECT username, id FROM accounts WHERE rights=5" ); // Support Staff
	 if ( $db->getRowCount( $sq ) == 0 )
	 {
		 print("Currently there are no support staff, if your interested in helping out you can try applying to become a support staff member.");
	 } else {
		while ( $r = $db->fetch_array( $sq ) )
		{
			 print( "<li>" . $r['username'] . " <span style=\"font-size: 10px;\">(<a href=\"" . $rootdir . "/profile.php?id=" . getCharacterID( getUserID( $r['username'] ) ) . "\">" . getCharNickname( getCharacterID( getUserID( $r["username"] ) ) ) . "</a>)</span><br />" );
		}
	 }
	 ?></ul>
	 </p>
	 </div>
 </div>
<?
if ( $wikienabled['value'] == 'true' )
{
	?>
	<div class="title">
		<div style="margin-left: 10px;">Red Republic Wiki</div>
	</div>
	<div class="content">
	<p>The Wiki is a good source of collective works on Red Republic from the general public. Its a good place to start looking for help.<br /><br /><a style="text-decoration: none;" href="<?=$rootdir;?>/<?=$wikilink['value'];?>">Enter the Wiki</a>
	</div>
	<?
}
include "includes/footer.php";
?>