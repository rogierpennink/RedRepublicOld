<?
include_once "includes/utility.inc.php";

$q = $db->query( "SELECT * FROM char_characters" );
while ( $r = $db->fetch_array( $q ) )
{
	$db->query( "INSERT INTO char_equip SET char_id=". $r['id'] .", bag=19" );
}
?>