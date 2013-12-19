<?
// stalker_handler.php
if ( isset( $_POST['stalk'] ) )
{
	$continue = true;

	// Find information about victim
	if ( $db->getRowCount( ( $rec = $db->query( "SELECT * FROM char_characters WHERE nickname=" . $db->prepval( $_POST['victim'] ) ) ) ) == 0 )
	{
		$_SESSION['agg_msg'] = "The name of your victim does not exist!";
		unset( $_POST['stalk'] );
		$continue = false;
	}

	$res = $db->fetch_array( $rec );
	$victim = new Player( $res['account_id'] );
	$char = new Player( getUserID() );

	if ( $continue && !$char->isAlive() )
	{
		$_SESSION['agg_msg'] = "What, are you going to stalk a grave...?";
		unset( $_POST['mug'] );
		$continue = false;
	}	
	if ( $continue && $char->nickname == $victim->nickname )
	{
		$_SESSION['agg_msg'] = "What... stalk yourself?";
		unset( $_POST['stalk'] );
		$continue = false;
	}
	if ( $continue && time() > $victim->online_timer )
	{
		$_SESSION['agg_msg'] = "Your victim has to be online!";
		unset( $_POST['stalk'] );
		$continue = false;
	}	
	if ( $continue && $victim->getHealth() <= 0 )
	{
		$_SESSION['agg_msg'] = "A dead person probably doesn't need stalking...";
		unset( $_POST['stalk'] );
		$continue = false;
	}
	if ( $continue && $victim->location != $char->location )
	{
		$_SESSION['agg_msg'] = "You have to be in the same city as your victim!";
		unset( $_POST['stalk'] );
		$continue = false;
	}
	if ( $continue )
	{
		/**
		 * We want a base 5% chance to succeed. With $max being at least 100 we can accomplish this.
		 * At the same time we want there to always be a 5% chance to fail even if you're 100x better.
		 * We accomplish this by limiting max to 1900, which is 95*20...
		 *
		 * Furthermore, for stalking there are certain multipliers. How important is a certain stat to
		 * accomplishing a stalking? Does intellect weigh in? Does cunning do? Multipliers may be different
		 * for resisting a stalking..
		 */
		$defStr = 1.0; $defDef = 1.0; $defInt = 1.2; $defCun = 1.3;
		$attStr = 1.0; $attDef = 1.0; $attInt = 1.4; $attCun = 1.5;

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
		
		if ( $rand > $check )
		{
			/* Add defense to victim */
			$victim->setDefense( $victim->getDefense() * 1.005 );
			$victim->setIntellect( $victim->getIntellect() * 1.005 );

			/* STALK WAS FAILED! */
			$hisher = ( $char->gender == "m" ) ? "his" : "her";
			$heshe = ( $victim->gender == "m" ) ? "he" : "she";
			$himher = ( $char->gender == "m" ) ? "him" : "her";

			/* ADD EVENT TO VICTIM! */
			$msg = "You were walking down the street, and kept noticing a strange person following you. As you continued your stroll you figured you were being stalked! You turned to confront $himher and before $heshe fled the scene you recognised $himher to be ". $char->nickname ."!";
			$victim->addEvent( "Attempted Stalking", $msg );

			/* Show fail Message */			
			$_SESSION['agg_msg'] = "You followed " . $victim->nickname . " down the street, but before you knew it " . $heshe . " turned around to confront you! Embarrassingly, you were forced to flee the scene!";
			unset( $_POST['submit'] );
		}
		else
		{
			$perc = ( $attPoints / $defPoints ) * 50;
			$perc = ( $perc < 15 ) ? 15 : ( $perc > 85 ) ? 85 : $perc;
			$perc /= 100;

			/* ADD STATS TO CHAR */
			$char->setCunning( $char->getCunning() + 70 );
			$char->setCriminalExp( $char->getCriminalExp() + 75 );
			$char->setIntellect( $char->getIntellect() + 50 );

			$heshe = ( $char->gender == "m" ) ? "He" : "She";
			$himher = ( $char->gender == "m" ) ? "him" : "her";
			$heshel = ( $char->gender == "m" ) ? "he" : "she";

			/* ADD EVENT TO VICTIM! */
			$msg = "Today while at market, you noticed a shadowy person in a trenchcoat and dark hat following you around the place. You feel slightly nervous and uneasy. You don't know why, but its obvious someone is keeping a watch on you!";
			$victim->addEvent( "Are you being followed?", $msg );

			$hisher = ( $victim->gender == "m" ) ? "his" : "her";

			$_SESSION['agg_msg'] = "You followed " . $victim->nickname . " closely down the street. They didn't even notice you! You learnt quite a few things about your victim! The notes you took were sent to your event reports.";

			// Some strings...
			$h = $victim->getHealth();
			if ( $h < 1 ) $healthmsg = "they were dead";
			if ( $h > 0 && $h < 21 ) $healthmsg = "they were very unhealthy, and possibly near death";
			if ( $h > 20 && $h < 41 ) $healthmsg = "they were unhealthy and vulnerable";
			if ( $h > 40 && $h < 61 ) $healthmsg = "they had an average health rate, but seemed somewhat vulnerable still";
			if ( $h > 60 && $h < 81 ) $healthmsg = "they are healthy, but have previously suffered injuries";
			if ( $h > 80 ) $healthmsg = "they are very healthy, and are likely not to die anytime soon";

			$m = ( $victim->getCleanMoney() + $victim->getDirtyMoney() ) / ( $char->getCleanMoney() + $char->getDirtyMoney() );
			if ( $m < 0.6 ) $moneymsg = "have very little money, and shop at the cheapest places";
			if ( $m >= 0.6 && $m < 0.8 ) $moneymsg = "have little money, but they carry enough to be noteworthy";
			if ( $m >= 0.8 && $m < 1.0 ) $moneymsg = "have fair amounts of money, if their not carefull they could be targets of muggers";
			if ( $m >= 1.0 && $m < 1.4 ) $moneymsg = "carry large amounts of money, they could have been buying something expensive, or they regularly carry a great deal of it";
			if ( $m >= 1.4 && $m < 1.8 ) $moneymsg = "be carrying a very large amount of money. Its likely they were in the process of buying something expensive, for it is so unusual to see that much money beeing carried at once";
			if ( $m >= 1.8 ) $moneymsg = "be carrying extremely large amounts of money. They bought only the most expensive items and went only to the most expensive bars";

			$i = $victim->getTotalInt() / $char->getTotalInt();
			if ( $i < 0.6 ) $intmsg = "didn't apear very smart, infact they semt downright dumb at times";
			if ( $i >= 0.6 && $i < 0.8 ) $intmsg = "appeared somewhat unitellectual, favoring materialistic conversation and events";
			if ( $i >= 0.8 && $i < 1.0 ) $intmsg = "appeared to have some knowledge of maths, science, and politics, making them somewhat intellectual";
			if ( $i >= 1.0 && $i < 1.4 ) $intmsg = "appeared quite intellectual, they spent a good deal of time at the library";
			if ( $i >= 1.4 && $i < 1.8 ) $intmsg = "appeared very intellectual, they have obviously been schooled well";
			if ( $i >= 1.8 ) $intmsg = "appeared extremely intellectual, almost unusually";
	

			$msg = "While you were stalking " . $victim->nickname . " you found out that " . $healthmsg . ". You also found the victim to " . $moneymsg . ". " . $victim->nickname . " " . $intmsg . ".";
			$char->addEvent( "Stalking Report", $msg );
			unset( $_POST['submit'] );
		}
	}
}	  