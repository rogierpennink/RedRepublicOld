<?
// Mugging_handler.php
if ( isset( $_POST['torchmailbox'] ) )
{
	$continue = true;

	// Find information about victim
	if ( $db->getRowCount( ( $rec = $db->query( "SELECT * FROM char_characters WHERE nickname=" . $db->prepval( $_POST['victim'] ) ) ) ) == 0 )
	{
		$_SESSION['agg_msg'] = "The name of your victim does not exist!";
		unset( $_POST['torchmailbox'] );
		$continue = false;
	}

	$res = $db->fetch_array( $rec );
	$victim = new Player( $res['account_id'] );
	$char = new Player( getUserID() );

	if ( $continue && !$victim->isAlive() )
	{
		$_SESSION['agg_msg'] = "Your victim is dead and his mailbox has since been removed!";
		unset( $_POST['torchmailbox'] );
		$continue = false;
	}	
	if ( $continue && $char->nickname == $victim->nickname )
	{
		$_SESSION['agg_msg'] = "Fool! You cannot torch your own mailbox!";
		unset( $_POST['torchmailbox'] );
		$continue = false;
	}	
	if ( $continue && time() < $victim->agg_pro )
	{
		$_SESSION['agg_msg'] = "Your victim has recently been victim of another crime. He can't be harmed yet!";
		unset( $_POST['torchmailbox'] );
		$continue = false;
	}
	if ( $continue && $victim->homecity != $char->location )
	{
		$_SESSION['agg_msg'] = "You have to be in the same city as your victim's mailbox!";
		unset( $_POST['torchmailbox'] );
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
		$defStr = 1.0; $defDef = 1.7; $defInt = 1.1; $defCun = 1.2;
		$attStr = 1.1; $attDef = 1.0; $attInt = 1.3; $attCun = 1.6;

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
			$himher = ( $char->gender == "m" ) ? "him" : "her";
			$heshe = ( $victim->gender == "m" ) ? "he" : "she";
			$hisher = ( $victim->gender == "m" ) ? "his" : "her";

			/* ADD EVENT TO VICTIM! */
			$msg = $char->nickname . " has attempted your mailbox! Fortunately for you, you just entered your garden to empty it when you saw $himher messing around with matches near your mailbox! You chased $himher off your grounds!";
			$victim->addEvent( "Attempted Mailbox Torching", $msg );

			/* Show fail Message */			
			$_SESSION['agg_msg'] = "You got close to torching " . $victim->nickname . "'s mailbox but $heshe just walked into you while you were pouring gasoline over it! Humiliated, you were chased off $hisher grounds!";
			unset( $_POST['submit'] );
		}
		else
		{
			$perc = ( $attPoints / $defPoints ) * 50;
			$perc = ( $perc < 15 ) ? 15 : ( $perc > 85 ) ? 85 : $perc;
			$perc /= 100;

			/* ADD STATS TO CHAR */
			$char->setStrength( $char->getStrength() + 25 );
			$char->setIntellect( $char->getIntellect() + 45 );
			$char->setCunning( $char->getCunning() + 80 );
			$char->setCriminalExp( $char->getCriminalExp() + 90 );

			$heshe = ( $char->gender == "m" ) ? "He" : "She";
			$himher = ( $char->gender == "m" ) ? "him" : "her";
			$hisher = ( $victim->gender == "m" ) ? "his" : "her";

			$_SESSION['agg_msg'] = "You sneaked towards ". $victim->nickname ."'s mailbox with a can of gasoline and a few matches. It went up in flames much sooner than you thought it would and you quickly ran away!";

			/* Check if there's something in the mailbox. */
			$mbquery = $db->query( "SELECT * FROM char_mailbox WHERE char_id=". $victim->character_id ." AND has_arrived=1 ORDER BY RAND() LIMIT 1" );
			if ( $db->getRowCount( $mbquery ) > 0 )
			{
				/* Again, we calculate the success chance. Only this time, it determines whether you also got something from the mailbox. */
				$rand = mt_rand( 1, 1000 );
				if ( $rand <= $check )
				{
					$mb = $db->fetch_array( $mbquery );
					if ( $mb['item_id'] == 0 )
					{
						$char->setDirtyMoney( $char->getDirtyMoney() + $mb['money'] );

						$_SESSION['agg_msg'] = "Before you torched ". $victim->nickname ."'s mailbox you shortly groped around in it and found a cheque of $". $mb['money'] ."! Lucky you!";						
					}
					else
					{
						/* Check inventory size. */
						$bag = new Item( $char->bag );
						if ( $bag->bagslots > $db->getRowCount( $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id ) ) )
						{
							$item = new Item( $mb['item_id'] );

							/* put item in inventory. */
							$db->query( "INSERT INTO char_inventory SET char_id=". $char->character_id .", item_id=". $item->item_id );

							/* Adjust message. */
							$_SESSION['agg_msg'] = "Before you torched ". $victim->nickname ."'s mailbox you shortly groped around in it and found a ". $item->name . "! Lucky you!";
						}
					}
				}
			}		
			
			/* Delete all items in the victim's mailbox. */
			$db->query( "DELETE FROM char_mailbox WHERE char_id=". $victim->character_id );

			/* ADD EVENT TO VICTIM! */
			$vmsg = "When you went to check for new mail in your mailbox, you saw black clouds of smoke rising from it! As you got even closer it became obvious someone torched it! It looks like you're going to have to pay for a new mailbox soon - but that is the least of your worries. What if there was something valuable in it as well?";
			$victim->addEvent( "Victim of Mailbox Torching", $vmsg );
			
			unset( $_POST['submit'] );
		}
	}
}	  