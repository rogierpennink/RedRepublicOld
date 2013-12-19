<?
 // playerguides.php - Where the guides are found.
 unset( $_SESSION['ingame'] );
 include "includes/utility.inc.php";
 include "includes/header.php";

$fquery = $db->query( "SELECT * FROM playerguides ORDER BY id ASC" );

if ( isset( $_GET['id'] ) )
{
	$dbq = $db->query( "SELECT * FROM playerguides WHERE id=" . $db->prepval( $_GET['id'] ) );
	if ( $db->getRowCount( $dbq ) == 0 )
	{
		header( "location: " . $rootdir . "/playerguides.php" );
		exit();
	}

	$dbassoc = $db->fetch_array( $dbq );
	?>
	<div class="title">
		<div style="margin-left: 10px;"><?=$dbassoc['name'];?></div>
	</div>
	<div class="content">
		<p>
		<?
		print( str_replace( chr( 13 ), "<br />", $dbassoc['content'] ) );
		?>
		</p>
	</div>
	<?
}

if ( $db->getRowCount( $fquery ) == 0 )
{
	?>
	<div class="title">
		<div style="margin-left: 10px;">Red Republic Player Guides</div>
	</div>
	<div class="content">
		<p>There were no questions found in the database.</p>
	</div>
	<?
} else {
	?>
	<div class="title">
		<div style="margin-left: 10px;">Bookshelf</div>
	</div>
	<div class="content">
		<p>
		<ul>
		<?
		while ( $cont = $db->fetch_array( $fquery ) )
		{
			?>
			<li><a href='playerguides.php?id=<?=$cont['id'];?>' style='text-decoration: none;'><?=$cont['name'];?></a><br /><?=shortstr( str_replace( chr( 13 ), "<br />", $cont['content'] ), 100);?></li><br />
			<?
		}
		?>
		</ul>
		</p>
	</div>
<?
}

include "includes/footer.php";
?>