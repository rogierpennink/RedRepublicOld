<?
include_once "includes/utility.inc.php";
include_once "includes/forums.inc.php";
unset( $_SESSION['ingame'] );
include_once "includes/header.php";
?>

<h1>Welcome to Red Republic</h1>

<p>Welcome to Red Republic. Red Republic is an online text-based browser RPG where you make a character and decide its future. How you get to the top is yours to decide. A plethora of careers, possibilities and exciting features are yours to use in your quest to become the most powerful character! Listed underneath is the site news. You can also make comments to it.</p>

<!-- UNDERNEATH THE NEWS WILL BE LISTED, OR NOT IF THERE IS NO NEWS -->
<?
	// Retrieve the news in a 2-dimensional array	
	$news = getSiteNews( 5, "DESC" );

	for ( $i = 0; $i < count( $news ); $i++ )
	{
		$array = $news[$i];
		?>
		<div class="title">
			<div style="margin-left: 10px;"><?=$array['subject'];?></div>
		</div>
		<div class="content">
			<? if ( $array['author'] ) { ?>
			<span style="font-size: 11px;">- Posted by <?=$array['author'];?> on <?=$array['date'];?> (<a href="news.php?id=<?=$array['id'];?>"><?=$array['numcomments'];?> comments</a>)</span><br /><br />
			<? } ?>
			<?=$array['message'];?>
		</div>
		<?
	}
?>

<?
include "includes/footer.php";
?>