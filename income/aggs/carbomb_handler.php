<?
// Carbombing Handler
if ( isset( $_POST['carbomb'] ) )
{
	$continue = true;

	// Find information about victim
	if ( $db->getRowCount( ( $rec = $db->query( "SELECT * FROM char_characters WHERE nickname=" . $db->prepval( $_POST['victim'] ) ) ) ) == 0 )
	{
		$_SESSION['agg_msg'] = "The name of your victim does not exist!";
		unset( $_POST['carbomb'] );
		$continue = false;
	}

	$res = $db->fetch_array( $rec );
	$victim = new Player( $res['account_id'] );
	$char = new Player( getUserID() );

	if ( $continue && !$char->isAlive() )
	{
		$_SESSION['agg_msg'] = "Your victim isn't alive, it wouldn't make any sense to bomb their car.";
		unset( $_POST['carbomb'] );
		$continue = false;
	}	
	if ( $continue && $char->nickname == $victim->nickname )
	{
		$_SESSION['agg_msg'] = "You cannot bomb your own car...";
		unset( $_POST['carbomb'] );
		$continue = false;
	}
	if ( $continue && time() > $victim->online_timer )
	{
		$_SESSION['agg_msg'] = "Your victim has to be online!";
		unset( $_POST['carbomb'] );
		$continue = false;
	}
	if ( $continue && time() < $victim->agg_pro )
	{
		$_SESSION['agg_msg'] = "Your victim has recently been victim of another crime. He can't be harmed yet!";
		unset( $_POST['carbomb'] );
		$continue = false;
	}
	if ( $continue && $victim->location != $char->location )
	{
		$_SESSION['agg_msg'] = "You have to be in the same city as your victim!";
		unset( $_POST['carbomb'] );
		$continue = false;
	}
	if ( $continue && !$victim->hasVehicle() )
	{
		$_SESSION['agg_msg'] = "The victim doesn't have a vehicle to bomb.";
		unset( $_POST['carbomb'] );
		$continue = false;
	}
	if ( $continue )
	{
		/**
		 * We want a base 5% chance to succeed. With $max being at least 100 we can accomplish this.
		 * At the same time we want there to always be a 5% chance to fail even if you're 100x better.
		 * We accomplish this by limiting max to 1900, which is 95*20...
		 *
		 * Furthermore, for carbombing there are certain multipliers. How important is a certain stat to
		 * accomplishing a carbomb? Does intellect weigh in? Does cunning do? Multipliers may be different
		 * for resisting a carbomb..
		 */
		$defStr = 1.4; $defDef = 1.5; $defInt = 1.0; $defCun = 1.1;
		$attStr = 1.5; $attDef = 1.0; $attInt = 1.1; $attCun = 1.4;

		$attPoints = $char->getTotalStr() * $attStr + $char->getTotalDef() * $attDef + $char->getTotalInt() * $attInt + $char->getTotalCun() * $attCun;
		$defPoints = $victim->getTotalStr() * $defStr + $victim->getTotalDef() * $defDef + $victim->getTotalInt() * $defInt + $victim->getTotalCun() * $defCun;

		$quotient = $attPoints / $defPoints;
		$check = 0;
		if ( $quotient <= 0.8 ) { $check = 800 * $quotient - 200; }
		elseif ( $quotient >= 1 ) { $check = 300 * $quotient + 200; }
		else { $check = 1000 * pow( $quotient - 1, 3 ) + 250 * $quotient + 250; }
		$check = ( $check < 50 ) ? 50 : $check;
		$check = ( $check > 950 ) ? 950 : $check;
		
		$rand = mt_rand( 1, 1000 );

		$char->setAggTimer();
		$victim->setAggPro();

		if ( $rand > $check )
		{
			/* Add defense to victim */
			$victim->setDefense( $victim->getDefense() * 1.005 );

			/* CARBOMB WAS FAILED! */
			$hisher = ( $char->gender == "m" ) ? "his" : "her";
			$heshe = ( $victim->gender == "m" ) ? "he" : "she";

			/* ADD EVENT TO VICTIM! */
			$msg = "While you were in the city, you noticed someone under your car messing around! They ran off into a crowd of people when you started walking toward them. Its obvious that someone doesn't want you going anywhere with your car, you were lucky this time, but you should probably lay low for a while.";
			$victim->addEvent( "Car Bomb Attempt", $msg );

			/* Show fail Message */			
			$_SESSION['agg_msg'] = "You found the victim's car parked on the side of the road and went under to place the C4 charges, but just as soon as you started working you looked over and noticed the victim walking out of a store and heading toward the car! You were forced to flee into a crowd of people before the victim could recognize you.";
			unset( $_POST['submit'] );
		}
		else
		{
			$perc = ( $attPoints / $defPoints ) * 50;
			$perc = ( $perc < 15 ) ? 15 : ( $perc > 85 ) ? 85 : $perc;
			$perc /= 100;

			/* ADD STATS TO CHAR */
			$char->setStrength( $char->getStrength() + 80 );
			$char->setCunning( $char->getCunning() + 60 );
			$char->setIntellect( $char->getIntellect() + 40 );
			$char->setCriminalExp( $char->getCriminalExp() + 105 );

			/* DESTROY VEHICLE */
			$victim->setVehicleHealth( 0 );
			$db->query( "DELETE FROM char_vehicles WHERE id=" . $victim->getCharacterID() );
			$db->query( "DELETE FROM char_tuneups WHERE char_id=" . $victim->getCharacterID() );

			/* QUIT RACE */
			$resultrace = $db->query( "SELECT * FROM racetrack_race WHERE driver_id_1=" . $victim->getCharacterID() );
			if ( $db->getRowCount( $resultrace ) > 0 )
			{
				$race = $db->fetch_array( $resultrace );
				$db->query( "UPDATE racetrack_race SET driver_id_1='' WHERE race_id=" . $race['race_id'] );
			}

			$resultrace = $db->query( "SELECT * FROM racetrack_race WHERE driver_id_2=" . $victim->getCharacterID() );
			if ( $db->getRowCount( $resultrace ) > 0 )
			{
				$race = $db->fetch_array( $resultrace );
				$db->query( "UPDATE racetrack_race SET driver_id_2='' WHERE race_id=" . $race['race_id'] );
			}

			$resultrace = $db->query( "SELECT * FROM racetrack_race WHERE driver_id_3=" . $victim->getCharacterID() );
			if ( $db->getRowCount( $resultrace ) > 0 )
			{
				$race = $db->fetch_array( $resultrace );
				$db->query( "UPDATE racetrack_race SET driver_id_3='' WHERE race_id=" . $race['race_id'] );
			}

			$heshe = ( $char->gender == "m" ) ? "He" : "She";
			$himher = ( $char->gender == "m" ) ? "him" : "her";

			/* THERE IS A CHANCE THE VICTIM LOOSES SOME HEALTH! */
			$healthloss = mt_rand( 1, 4 );
			$msg = "You were casually walking around town going around your daily routine, when you started walking back to your car. As you went to unlock the door the car exploded into hundreds of peices sending fragments everywhere! Its obvious that someone is trying to send you a message.";
			if ( mt_rand( 1, 100 ) <= 50 && $victim->getHealth() > $healthloss )
			{				
				$victim->setHealth( $victim->getHealth() - $healthloss );
				$msg = "You were casually walking around town going around your daily routine, when you started walking back to your car. As you went to unlock the door the car exploded into hundreds of peices sending fragments everywhere! Not only did you loose your car, you sustained second degree burns as well and lost $healthloss health in the attack! Its obvious that someone is trying to send you a message.";
			}

			/* ADD EVENT TO VICTIM! */
			
			$victim->addEvent( "Victim of Car Bombing", $msg );

			$hisher = ( $victim->gender == "m" ) ? "his" : "her";

			$_SESSION['agg_msg'] = "You found the victim's car parked on the side of the road. When no one was looking you went under and placed some C4 charges. You crawled back out and patiently waited for the victim to come back. As soon as they were in clear view you blew the car in front of them and everyone! Looks like the plan went off with out a hitch, surely they get the message now!";
			unset( $_POST['submit'] );
		}
	}
}	  