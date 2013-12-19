<?
$cron = true;
include_once "../public_html/includes/utility.inc.php";

function drawUnemployment()
{
	// Unemployment checks are recieved from governments that allow them.
	global $db;

	/* Get unemployed characters */
	$q = $db->query( "SELECT * FROM char_characters WHERE rank_id=1 AND health > 0" );
	while ( $r = $db->fetch_array( $q ) )
	{
		$char = new Player( $r['id'] );

		/* Check if their home government is anarchy or not */
		$localgovq = $db->query( "SELECT * FROM localgov WHERE location_id=" . $r['homecity'] );
		if ( $db->getRowCount( $localgovq ) != 0 )
		{
			$localgov = new Government( $r['homecity'] );
			if ( $localgov->GovernmentID != 0 )
			{
				/* Government is not anarchy, check if they allow unemployment checks */
				if ( $localgov->UnempCheckBool )
				{
					/* Government allows unemployment checks */
					if ( $localgov->Budget < $localgov->UnempCheckSize )
					{
						/* Government does not have the funds to pay */
						$char->addEvent( "Unemployment Check", "Your government did not have enough funds to pay your unemployment check this week. Someone should fix their waggon good for treating citizens like this!" );
					} 
					else 
					{
						/* Government can pay, and will. */
						$char->setCleanMoney( $r['money_clean']+$localgov->UnempCheckSize );
						$char->addEvent( "Unemployment Check", "You recieved your unemployment check from the government today worth $" . $localgov->UnempCheckSize . "." );
						$db->query( "UPDATE localgov SET budget=budget-" . $localgov->UnempCheckSize . " WHERE location_id=" . $r['homecity'] );
					}
				}
			}
		}
	}
}

drawUnemployment();

?>