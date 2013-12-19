<?
 // support.php - A support center :)
 unset( $_SESSION['ingame'] );
 include "includes/utility.inc.php";
 include "includes/header.php";

$fquery = $db->query( "SELECT * FROM faqbook ORDER BY id ASC" );
$fquery2 = $db->query( "SELECT * FROM faqbook ORDER BY id ASC" ); // Why? Because fetch_array aparently isn't reuseable.

if ( $db->getRowCount( $fquery ) == 0 )
{
	?>
	<div class="title">
		<div style="margin-left: 10px;">Red Republic FAQ Book</div>
	</div>
	<div class="content">
		<p>There were no questions found in the FAQ Book database.</p>
	</div>
	<?
} else {
	?>
	<div class="title">
		<div style="margin-left: 10px;">Table of Contents</div>
	</div>
	<div class="content">
		<p>
		<ul>
		<?
		while ( $cont = $db->fetch_array( $fquery ) )
		{
			?>
			<li><?=$cont['question'];?></li>
			<?
		}
		?>
		</ul>
		</p>
	</div>
	<div class="title">
		<div style="margin-left: 10px;">FAQ Book</div>
	</div>
	<div class="content"><p>
	<?
	while ( $faq = $db->fetch_array( $fquery2 ) )
	{
		?>
		<div class="row">
			<div style="margin-left: 10px;"><font color='red'><?=$faq['question'];?></font></div>
		</div>
		<div class="content"><font color='#295374'>
			<p>
			<?
			$content = str_replace( chr( 13 ), "<br />", $faq['answer'] );
			print( $content );
			if ( strlen( $faq['credit'] ) > 0 ) print("<br /><br />Credited: " . $faq['credit'] );
			?>
			</p>
			</font>
		</div>
		<?
	}
	?></p>
	</div>
	<?
}

include "includes/footer.php";
?>