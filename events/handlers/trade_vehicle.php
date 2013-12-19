<?
/* Trade Vehicles
 * This event trades vehicles between two people.
 */

/* Check if this is being answered */
if ( isset( $_SESSION["event"] ) )
{
	/* The following $_POST fields are ALWAYS there in any event request
	 * (hidden) id - ID of the event being answered.
	 * (hidden) handler - This filename.
	 */

	if ( isset( $_POST["tradeoption"] ) )
	{
		/* "wait" was our option for "dont answer yet" */
		if ( $_POST["tradeoption"] != "wait" )
		{
			/* Create another char for the requester */
			$charex = new Player( $_POST["chrid"], false );

			/* Accepting Offer */
			if ( $_POST["tradeoption"] == "accept" )
			{
				if ( !$charex->hasVehicle() )
				{
					$_SESSION["error"] = getCharNickname( $charex->getCharacterID() ) . " doesn't have a vehicle to trade anymore.";
					$charex->addEvent( "Vehicle Trade", getCharNickname( $char->getCharacterID() ) . " attempted to trade vehicles with you, but your vehicle was already gone! They'll probably be somewhat upset about this..." );
					$char->answerEvent( $_POST["id"] );
					$char->setTradeStatus( "false" );
					$charex->setTradeStatus( "false" );
				}
				elseif ( !$char->hasVehicle() )
				{
					$_SESSION["error"] = "You no longer have a vehicle to trade " . getCharNickname( $charex->getCharacterID() ) . ".";
					$charex->addEvent( "Vehicle Trade", getCharNickname( $char->getCharacterID() ) . " attempted to trade vehicles with you, but they no longer have a vehicle." );
					$char->setTradeStatus( "false" );
					$charex->setTradeStatus( "false" );
					$char->answerEvent( $_POST["id"] );
				}
				else
				{
					if ( $char->getTier() < $charex->getVehicleInfo( "tier_required" ) || $charex->getTier() < $char->getVehicleInfo( "tier_required" ) )
					{
						$_SESSION["error"] = "You do not have enough experience to run this vehicle.";
						$char->answerEvent( $_POST["id"] );
						$char->setTradeStatus( "false" );
						$charex->setTradeStatus( "false" );
					}
					else
					{
						/* Make the swap */
						$db->query( "UPDATE char_vehicles SET id=" . $char->getCharacterID() . " WHERE id=" . $charex->getCharacterID() . " AND vehicle=" . $charex->getVehicleInfo( "id" ) );
						$db->query( "UPDATE char_vehicles SET id=" . $charex->getCharacterID() . " WHERE id=" . $char->getCharacterID() . " AND vehicle=" . $char->getVehicleInfo( "id" ) );

						$result = $db->query( "SELECT * FROM char_tuneups WHERE char_id=" . $char->getCharacterID() . " OR char_id=" . $charex->getCharacterID() );

						while ( $row = $db->fetch_array( $result ) )
						{
							if ( $row["char_id"] == $char->getCharacterID() )
								$db->query( "UPDATE char_tuneups SET char_id=" . $charex->getCharacterID() . " WHERE id=" . $row["id"] );
							elseif ( $row["char_id"] == $charex->getCharacterID() )
								$db->query( "UPDATE char_tuneups SET char_id=" . $charex->getCharacterID() . " WHERE id=" . $row["id"] );
						}

						$_SESSION["notify"] = "You have traded vehicles with " . getCharNickname( $charex->getCharacterID() );
						$charex->addEvent( "Vehicle Traded", getCharNickname( $char->getCharacterID() ) . " has accepted your offer of a vehicle trade." );
						$char->answerEvent( $_POST["id"] );
						$char->setTradeStatus( "false" );
						$charex->setTradeStatus( "false" );
					}
				}
			}

			/* Declining Offer */
			if ( $_POST["tradeoption"] == "decline" )
			{
				$nick = getCharNickname( $char->getCharacterID() );
				$charex->addEvent( "Trade Request Declined", $nick . " has declined your request to trade vehicles." );
				$char->answerEvent( $_POST["id"] );
				$char->setTradeStatus( "false" );
				$charex->setTradeStatus( "false" );
			}
		}
		else
		{
			/* Not ready to answer... */
			header( "location: " . $rootdir . "/localcity/index.php" );
			exit();
		}
	}
	else 
		$_SESSION["error"] = "Event Request was not properly processed.";
}
