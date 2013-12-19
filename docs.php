<?
 // RR Documents, grabbed from db config (settings_text)
 unset( $_SESSION['ingame'] );
 include "includes/header.php";
?>
 
 <div class="title">
	<div style="margin-left: 10px;">Red Republic Documents</div>
 </div>
 <div class="content">
 <p>
 <?
 $docq = $db->query( "SELECT * FROM settings_text WHERE setting=" . $db->prepval( $_GET['type'] ) );
 if ( $db->getRowCount( $docq ) == 0 )
 {
	 ?>
	 Sorry, this document wasn't found.
	 <?
 } else {
	$doc = $db->fetch_array( $docq );
	print( $doc['value'] );
}
 ?>
</p>
 </div>
<?
 include "includes/footer.php";
?>