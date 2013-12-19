<?
// Mugging_handler.php
if ( isset( $_POST['mug'] ) )
{
	$continue = true;

	// Find information about victim
	if ( $db->getRowCount( ( $rec = $db->query( "SELECT * FROM char_characters WHERE nickname=" . $db->prepval( $_POST['victim'] ) ) ) ) == 0 )
	{
		$_SESSION['agg_msg'] = "The name of your victim does not exist!";
		unset( $_POST['mug'] );
		$continue = false;
	}

	$res = $db->fetch_array( $rec );
	$victim = new Player( $res['account_id'] );
	$char = new Player( getUserID() );

	if ( $continue && !$char->isAlive() )
	{
		$_SESSION['agg_msg'] = "Mugging dead people doesn't really add up!";
		unset( $_POST['mug'] );
		$continue = false;
	}	
	if ( $continue && $char->nickname == $victim->nickname )
	{
		$_SESSION['agg_msg'] = "Fool! You cannot mug yourself!";
		unset( $_POST['mug'] );
		$continue = false;
	}
	if ( $continue && time() > $victim->online_timer )
	{
		$_SESSION['agg_msg'] = "Your victim has to be online!";
		unset( $_POST['mug'] );
		$continue = false;
	}
	if ( $continue && time() < $victim->agg_pro )
	{
		$_SESSION['agg_msg'] = "Your victim has recently been victim of another crime. He can't be harmed yet!";
		unset( $_POST['mug'] );
		$continue = false;
	}
	if ( $continue && $victim->location != $char->location )
	{
		$_SESSION['agg_msg'] = "You have to be in the same city as your victim!";
		unset( $_POST['mug'] );
		$continue = false;
	}
	if ( $continue )
	{
		/**
		 * We want a base 5% chance to succeed. With $max being at least 100 we can accomplish this.
		 * At the same time we want there to always be a 5% chance to fail even if you're 100x better.
		 * We accomplish this by limiting max to 1900, which is 95*20...
		 *
		 * Furthermore, for mugging there are certain multipliers. How important is a certain stat to
		 * accomplishing a mug? Does intellect weigh in? Does cunning do? Multipliers may be different
		 * for resisting a mug..
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

			/* MUG WAS FAILED! */
			$hisher = ( $char->gender == "m" ) ? "his" : "her";
			$heshe = ( $victim->gender == "m" ) ? "he" : "she";

			/* ADD EVENT TO VICTIM! */
			$msg = $char->nickname . " has attempted to mug you in a dark alley! Thanks to your alertness and $hisher clumsiness you were able to escape the dangerous situation!";
			$victim->addEvent( "Attempted Mugging", $msg );

			/* Show fail Message */			
			$_SESSION['agg_msg'] = "You got close to mugging " . $victim->nickname . " but $heshe saw you just in time and managed to get away!";
			unset( $_POST['submit'] );
		}
		else
		{
			$perc = ( $attPoints / $defPoints ) * 50;
			$perc = ( $perc < 15 ) ? 15 : ( $perc > 85 ) ? 85 : $perc;
			$perc /= 100;

			/* ADD STATS TO CHAR */
			$char->setStrength( $char->getStrength() + 75 );
			$char->setCunning( $char->getCunning() + 50 );
			$char->setCriminalExp( $char->getCriminalExp() + 50 );

			/* TAKE AND ADD MONEY FROM/TO VICTIM AND CHAR */
			$money = floor( $victim->getCleanMoney( true ) * $perc ) + floor( $victim->getDirtyMoney( true ) * $perc );
			$victim->setCleanMoney( $victim->getCleanMoney() - floor( $victim->getCleanMoney() * $perc ) );
			$victim->setDirtyMoney( $victim->getDirtyMoney() - floor( $victim->getDirtyMoney() * $perc ) );
			$char->setDirtyMoney( $char->getDirtyMoney() + $money );

			$heshe = ( $char->gender == "m" ) ? "He" : "She";
			$himher = ( $char->gender == "m" ) ? "him" : "her";

			/* ADD EVENT TO VICTIM! */
			$msg = "While walking through a cold, dark alleyway you suddenly felt someone put a hand over your mouth, holding you tightly. $heshe searched your pockets successfully and ran off with your money before you could get a good look at $himher! You lost $$money!";
			$victim->addEvent( "Victim of Mugging", $msg );

			$hisher = ( $victim->gender == "m" ) ? "his" : "her";

			$_SESSION['agg_msg'] = "You sneaked up on " . $victim->nickname . " from behind and put a hand over $hisher mouth. You then quickly searched for $hisher money and left the scene with a remarkably heavier wallet! You stole $$money!";
			unset( $_POST['submit'] );
		}
	}
}	  