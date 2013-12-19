<?
$cron = true;
include_once "../public_html/includes/utility.inc.php";

function Elections()
{
	/* Prepare all cities for elections */
	$cityquery = $db->query( "SELECT id FROM locations" );
	
	while ( $city = $db->fetch_array( $cityquery ) )
	{
		/* Start looping parties */
		$partyquery = $db->query( "SELECT * FROM localgov_parties WHERE location_id=" . $city['id'] );

		while ( $party = $db->fetch_array( $partyquery ) )
		{
			$votes = $db->getRowCount( $db->query( "SELECT * FROM localgov_votes WHERE party_id=" . $party['id'] ) );

			// Party must have 5 members/donators.
			if ( $party['party_member_1'] != 0 && $party['party_member_2'] !=0 && $party['party_member_3'] != 0 && $party['party_member_4'] != 0 && $party['party_member_5'] != 0 )
			{
				if ( !isset( $winner ) && !isset( $winner_votes ) )
				{
					// Default win, for now...
					$winner = $party['id'];
					$winner_votes = $votes;
				}
				elseif ( isset( $winner ) && isset( $winner_votes ) )
				{
					// Check for new winner
					if ( $votes > $winner_votes )
					{
						$winner = $party['id'];
						$winner_votes = $votes;
					}
				}
			}
		}

		// Check for localgov existance...
		if ( $db->getRowCount( $db->query( "SELECT id FROM localgov WHERE location_id=" . $city['id'] ) ) == 0 )
			$db->query( "INSERT INTO localgov (id, government_id, location_id) VALUES ('', 0, " . $city['id'] . ")" );

		/* Check anarchist movement... */
		$anarchistvotes = $db->getRowCount( $db->query( "SELECT * FROM localgov_votes WHERE party_id=0 AND location_id=" . $city['id'] ) );
		if ( $anarchistvotes > $winner_votes )
		{
			// Anarchists win the day, boot the gov out of the db.
			$db->query( "DELETE FROM localgov WHERE location_id=" . $city['id'] );
			$db->query( "DELETE FROM localgov_votes WHERE location_id=" . $city['id'] );
			$db->query( "DELETE FROM localgov_parties WHERE location_id=" . $city['id'] );
			$db->query( "DELETE FROM localgov_research WHERE location_id=" . $city['id'] . " WHERE status=0 OR status=1" );
		}
		else
		{
			// Legit Party Wins
			$newparty = $db->getRowsFor( "SELECT * FROM localgov_parties WHERE id=" . $winner );

			// Proclaim winner for this city
			$db->query( "UPDATE localgov SET government_id=" . $newparty['government_id'] . ", budget=" . $newparty['budget'] . ", leader_id=" . $newparty['leader_id'] . ", unemp_check_status='false', unemp_check=0, taxes=" . $newparty['tax_proposal'] . ", notice='', freespeech_status='true', freepress_status='true', html_newspaper='' WHERE location_id=" . $city['id'] );

			// Delete other parties as a penalty for losing
			$db->query( "DELETE FROM localgov_parties WHERE location_id=" . $city['id'] );

			// Delete voters for this city
			$db->query( "DELETE FROM localgov_votes WHERE location_id=" . $city['id'] );

			// Delete all technology researched
			$db->query( "DELETE FROM localgov_research WHERE location_id=" . $city['id'] . " WHERE status=0 OR status=1" );
		}

		// Unset temp vars
		unset( $winner );
		unset( $winner_votes );
	}
}

Elections();
?>