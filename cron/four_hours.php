<?
$cron = true;
require_once( "/home/roger/public_html/includes/utility.inc.php" );

function health_regen()
{
	global $db;

	/* Select all players and loop through them. */
	$result = $db->query( "SELECT * FROM char_characters WHERE health < maxhealth" );
	while ( $row = $db->fetch_array( $result ) )
	{
		/* If player is alive, update! */
		if ( $row['health'] > 0 )
		{
			$diff = $row['maxhealth'] - $row['health'];
			$diff = ( $diff > 3 ) ? 3 : $diff;
			
			$db->query( "UPDATE char_characters SET health=health+$diff WHERE id=". $row['id'] );
		}
	}
}

/* Execute all functions in this file. */
health_regen();
?>