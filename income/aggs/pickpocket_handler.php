<?
// Mugging_handler.php
if ( isset( $_POST['pickpocket'] ) )
{
	$continue = true;

	// Find information about victim
	if ( $db->getRowCount( ( $rec = $db->query( "SELECT * FROM char_characters WHERE nickname=" . $db->prepval( $_POST['victim'] ) ) ) ) == 0 )
	{
		$_SESSION['agg_msg'] = "The name of your victim does not exist!";
		unset( $_POST['pickpocket'] );
		$continue = false;
	}

	$res = $db->fetch_array( $rec );
	$victim = new Player( $res['account_id'] );
	$char = new Player( getUserID() );

	/* Bag-full check. */
	$equipq = $db->query( "SELECT * FROM char_equip INNER JOIN items ON char_equip.bag = items.item_id WHERE char_id=". $char->character_id );
	$equip = $db->fetch_array( $equipq );
	$inventoryq = $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id );

	if ( $continue && !$char->isAlive() )
	{
		$_SESSION['agg_msg'] = "Pickpocketing dead people doesn't really add up!";
		unset( $_POST['mug'] );
		$continue = false;
	}
	if ( $continue && $char->nickname == $victim->nickname )
	{
		$_SESSION['agg_msg'] = "Fool! You cannot pickpocket yourself!";
		unset( $_POST['pickpocket'] );
		$continue = false;
	}
	if ( $continue && time() > $victim->online_timer )
	{
		$_SESSION['agg_msg'] = "Your victim has to be online!";
		unset( $_POST['pickpocket'] );
		$continue = false;
	}
	if ( $continue && time() < $victim->agg_pro )
	{
		$_SESSION['agg_msg'] = "Your victim has recently been victim of another crime. He can't be harmed yet!";
		unset( $_POST['pickpocket'] );
		$continue = false;
	}
	if ( $continue && $victim->location != $char->location )
	{
		$_SESSION['agg_msg'] = "You have to be in the same city as your victim!";
		unset( $_POST['pickpocket'] );
		$continue = false;
	}
	if ( $db->getRowCount( $inventoryq ) >= $equip['bagslots'] )
	{
		$_SESSION['agg_msg'] = "Your bags are full, even if you did succeed there'd be no place to keep the stolen goods!";
		unset( $_POST['pickpocket'] );
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

		$quotient = $attPoints/$defPoints;
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
			$msg = $char->nickname . " has attempted to pickpocket you in a busy street! Thanks to $hisher stupidy and the cries of warning from the people around you, you were saved from loosing your wallet!";
			$victim->addEvent( "Attempted Pickpocketing", $msg );

			/* Show fail Message */			
			$_SESSION['agg_msg'] = "When you thought nobody saw you and tried to slip your hand in " . $victim->nickname . "'s pocket, an onlooker cried a warning and your victim quickly turned around. You swore and quickly ran!";
			unset( $_POST['submit'] );
		}
		else
		{
			/* ADD STATS TO CHAR */
			$char->setStrength( $char->getStrength() + 40 );
			$char->setCunning( $char->getCunning() + 85 );
			$char->setCriminalExp( $char->getCriminalExp() + 60 );

			/* ATTEMPT TO TAKE AN ITEM FROM THE VICTIM. */
			$itemquery = $db->query( "SELECT * FROM char_inventory INNER JOIN items ON char_inventory.item_id = items.item_id WHERE char_id=" . $victim->character_id . " ORDER BY RAND() LIMIT 1" );

			if ( $db->getRowCount( $itemquery ) == 0 )
			{
				$himher = ( $victim->gender == "m" ) ? "him" : "her";
				$hisher = ( $victim->gender == "m" ) ? "his" : "her";
				$_SESSION['agg_msg'] = "You sneaked up on " . $victim->nickname . " in a large crowd and quickly slipped your hand into $hisher bags. You shortly groped around without $himher noticing but there was nothing in it! You turned around, disappointed.";
				unset( $_POST['submit'] );
			}			
			else
			{
				$item = $db->fetch_array( $itemquery );

				/* Transfer the item! */
				$db->query( "INSERT INTO char_inventory SET char_id=" . $char->character_id .", item_id=" . $item['item_id'] );
				$db->query( "DELETE FROM char_inventory WHERE char_id=" . $victim->character_id ." AND item_id=" . $item['item_id'] );

				$heshe = ( $char->gender == "m" ) ? "He" : "She";
				$himher = ( $char->gender == "m" ) ? "him" : "her";

				/* ADD EVENT TO VICTIM! */
				$msg = "You were walking through a busy street in " . $victim->location . " and suddenly noticed your bags feeling a lot lighter! After immediately checking your bag's contents you noticed a " . $item['name'] . " had gone missing! Looks like you've been victim of a pickpocket!";
				$victim->addEvent( "Victim of Pickpocketing", $msg );

				$hisher = ( $victim->gender == "m" ) ? "his" : "her";

				$_SESSION['agg_msg'] = "You came up behind " . $victim->nickname . " in a large crowd and quickly slipped your hand into $hisher bags. You shortly groped around and withdrew a ". $item['name'] ." from $hisher bag. Not a bad catch!";
				unset( $_POST['submit'] );
			}
		}
	}
}