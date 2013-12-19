<?
/* Basic checks... */
if ( !isset( $_POST['victim'] ) || $_POST['victim'] == "" )
{
	$_GET['act'] = "murder";
	echo "You must enter a victim's nickname!";
}
elseif ( !isset( $_POST['reason'] ) || strlen( $_POST['reason'] ) < 5 )
{
	$_GET['act'] = "murder";
	echo "You don't kill without a reason! Enter at least 5 characters!";
}
else
{
	/* Check if the victim and weapon exists. */
	$victimq = $db->query( "SELECT * FROM char_characters WHERE health > 0 AND nickname=". $db->prepval( $_POST['victim'] ) );
	if ( $db->getRowCount( $victimq ) > 0 )
	{
		$victim = $db->fetch_array( $victimq );
		$victim = new Player( $victim['account_id'] );
	}

	if ( $_POST['weapon'] != 0 || $_POST['weapon'] != -1 )
	{
		$weaponq = $db->query( "SELECT * FROM items WHERE item_id=". $db->prepval( $_POST['weapon'] ) );
		$weapon = $db->fetch_array( $weaponq );
	}

	if ( $db->getRowCount( $victimq ) == 0 )
	{
		$_GET['act'] = "murder";
		echo "The character you are trying to murder is already dead or doesn't actually exist!";
	}
	elseif ( $victim->character_id == $char->character_id )
	{
		$_GET['act'] = "murder";
		echo "You wouldn't even dare kill yourself, weakling!";
	}
	elseif ( $char->location_nr != $victim->location_nr )
	{
		$_GET['act'] = "murder";
		echo "You have to be at the same location as your victim in order to commit a murder attempt!";
	}
	elseif ( !$victim->isOnline() )
	{
		$_GET['act'] = "murder";
		echo "Your victim has to be online in order to attempt murder!";
	}
	elseif ( time() < $victim->kill_pro )
	{
		$_GET['act'] = "murder";
		echo "You can't attempt murder on your victim yet! He has recently survived another attempt against his life!";
	}
	elseif ( $_POST['weapon'] != 0 && $_POST['weapon'] != -1 && $char->main_weapon != $weapon['item_id'] )
	{
		$_GET['act'] = "murder";
		echo "You are trying to murder your victim with a weapon that you do not have equipped!";
	}
	else
	{	
		/* Calculate whether the murder succeeds or not. */
		$attStr = 1.5; $attDef = 1.0; $attInt = 1.2; $attCun = 1.3;
		$defStr = 1.0; $defDef = 1.5; $defInt = 1.1; $defCun = 1.4;

		/* Calculate the attacker's and defender's total points. */
		$attPoints = $char->getTotalStr() * $attStr + $char->getTotalDef() * $attDef + $char->getTotalInt() * $attInt + $char->getTotalCun() * $attCun;
		$defPoints = $victim->getTotalStr() * $defStr + $char->getTotalDef() * $defDef + $char->getTotalInt() * $defInt + $victim->getTotalCun() * $defCun;

		/* Calculate the value to check our random value against. */
		$quotient = $attPoints / $defPoints;
		$check = 0;
		if ( $quotient <= 0.8 ) { $check = 800 * $quotient - 200; }
		elseif ( $quotient >= 1 ) { $check = 300 * $quotient + 200; }
		else { $check = 1000 * pow( $quotient - 1, 3 ) + 250 * $quotient + 250; }
		$check = ( $check < 50 ) ? 50 : $check;
		$check = ( $check > 950 ) ? 950 : $check;
		
		$rand = mt_rand( 1, 1000 );

		/* Set the murder timer. */
		if ( $_POST['weapon'] == 0 || $_POST['weapon'] == -1 )
			$char->setMurderTimer( 3600 );
		else
			$char->setMurderTimer( $weapon['speed'] * 60 );
		$victim->setMurderPro();
				
		if ( $rand > $check )	// Attacker failed, add defense to victim
		{
			$victim->setDefense( $victim->getDefense() * 1.01 );

			/* MURDER WAS FAILED! */
			$hisher = ( $victim->gender == "m" ) ? "his" : "her";
			$heshe = ( $victim->gender == "m" ) ? "He" : "She";

			/* ADD EVENT TO VICTIM! */
			$msg = $char->nickname . " has attempted to take your life! If it wasn't for your lightning-fast response and your quick evasive maneouver you might just have kicked the bucket! Thankfully, that didn't happen and you're ready to take revenge!";
			$victim->addEvent( "Failed Murder Attempt", $msg );
			
			/* Show fail Message */			
			echo "You carefully sneaked up on " . $victim->nickname . " but when you were about to take $hisher life you trod on a twig, breaking it. $heshe turned around and dodged away from you, pulling a gun from $hisher bag. There's no way you're going to pull this off now!";			
		}
		else
		{
			$quotient = ( $quotient < 0.2 ) ? 0.2 : ( $quotient > 2.1 ) ? 2.1 : $quotient;
			
			$damage = 0;
			$redfactor = 0;

			// Damage Calculations //////////////////////////////////////////
			if ( $_POST['weapon'] == 0 ) /* Using bare hands. */
			{
				$damage = 3 * $quotient;
				$redfactor = $attPoints / ( $defPoints + ( $victim->armour * 50 ) );
				$redfactor = $redfactor > 1.2 ? 1.2 : $redfactor;
			}
			elseif ( $_POST['weapon'] == -1 ) /* Using vehicle */
			{
				$damage = 4 * $quotient;
				$redfactor = $attPoints / ( $defPoints + ( $victim->armour * 50 ) );
				$redfactor = $redfactor > 1.2 ? 1.2 : $redfactor;
			}
			else
			{				
				$damage = $quotient * mt_rand( $weapon['min_dmg'], $weapon['max_dmg'] );
				$redfactor = $attPoints / ( $defPoints + ( $victim->armour * 50 ) );
				$redfactor = $redfactor > 1.2 ? 1.2 : $redfactor;
			}			
			// End of Damage Calculations ///////////////////////////////////

			$damage = round( $damage * $redfactor );
			$dmg = ( $victim->getHealth() < $damage ) ? $victim->getHealth() : $damage;
			
			$newhealth = $victim->getHealth() - $dmg;			
			/* Apply the damage to the victim. */
			$victim->setHealth( $newhealth );

			/* Add to the character's strength, cunning and criminal exp. */
			$max = $quotient <= 1 ? 1200 : 800;
			$char->setStrength( $char->getStrength() * 1.01 );
			$char->setCunning( $char->getCunning() * 1.003 );
			$char->setCriminalExp( $char->getCriminalExp() + mt_rand( 500, $max ) );

			/* GENDER STUFF. */
			$himher = ( $victim->gender == "m" ) ? "him" : "her";
			$hisher = ( $victim->gender == "m" ) ? "his" : "her";
			$heshe = ( $victim->gender == "m" ) ? "he" : "she";

			/* Now, check if the victim is dead or not. */
			if ( $victim->getHealth() <= 0 )
			{
				/* Add a large event. */
				$msg = "<h1>You are dead, ". $victim->nickname ."!</h1><br /><br />";
				$msg .= "<img src='$rootdir/images/murder_attempt.png' alt='' /><br />";
				$msg .= "<p><strong>You have been brutally murdered on ". date( TIME_FORMAT, time() ) .". Your murderer left a message on your body, saying:</strong><br /><i>\"". str_replace( Chr( 13 ), "<br />", $_POST['reason'] ) ."\"</i></p>";
				$msg .= "<p><strong>Rest in Pieces</strong></p>";
				$msg .= "<p><a href='$rootdir/account.php'>Account</a></p>";

				$victim->addLargeEvent( $msg, true );

				$char->setCleanMoney( $char->getCleanMoney() + ( $victim->getCleanMoney() + $victim->getDirtyMoney() ) );

				if ( $_POST['weapon'] == -1 ) $char->setVehicleHealth( $char->getVehicleHealth() - 15 );
				
				if ( $_POST['weapon'] == 0 )
				{
					echo "You cornered ". $victim->nickname ." in a deserted street with malfunctioning streetlights. Before $heshe could plead with you, you sank your fists into $hisher stomach and began hitting $himher wherever you could! As seconds passed, $hisher resistance got weaker until finally you delivered the killing blow with a staggering $damage damage! You grabbed $". ( $victim->getCleanMoney() + $victim->getDirtyMoney() ) ." from $himher and ran away!";
				} 
				elseif ( $_POST['weapon'] == -1 )
				{
					echo "You sat outside the tavern waiting for " . $victim->nickname . " to leave and head for the parking lot across the street. As soon as they walked out you started up your car and burned rubber heading toward $himher! They were caught completely off guard and only starred into your headlights as you smashed into $himher! Their body made a large dint in your front bumper, you'll need to get this fixed at the local garage. Before driving away from the scene you stopped to empty $hisher pockets and found $" . ( $victim->getCleanMoney() + $victim->getDirtyMoney() ) . ".";
				}
				else
				{
					switch ( $weapon['category'] )
					{
						case KNIFES:
							echo "You sneaked up towards ". $victim->nickname ." and before $heshe could flee or defend $himherself you slit $hisher throat! You watched as $heshe died in a pool of blood and took whatever money $heshe had left on $himher!  You delivered a total of $damage damage and you gained $". ( $victim->getCleanMoney() + $victim->getDirtyMoney() ) ."!";
							break;
						case GUNS: case RIFLES:
							echo "As you walked past ". $victim->nickname ." you put your weapon in $hisher back and forced $himher to wander into a deserted alley. Before $heshe could defend $himherself you had pushed $himher over and shot $himher through the head! As $heshe died a horrible death, you quickly took $". ( $victim->getCleanMoney() + $victim->getDirtyMoney() ) ." from $himher and fled the scene! You delivered a total of $damage damage...";
					}
				}
			}
			else
			{
				/* Add a large event. */
				$msg = "<h1>You survived a murder attempt!</h1><br /><br />";
				$msg .= "<img src='$rootdir/images/murder_attempt.png' alt='' /><br />";
				$msg .= "<p><strong>You have been brutally attacked on ". date( TIME_FORMAT, time() ) .". You are lucky to be alive! Your attacker delivered $damage damage to you - you had better find yourself a hospital to treat your wounds!</strong></p";

				$victim->addLargeEvent( "Murder!", $msg );
				$victim->addEvent( "Murder Attempt", "You have been brutally attacked on ". date( TIME_FORMAT, time() ) .". You are lucky to still find yourself in the land of the living! You have suffered $damage damage from the attack - perhaps it is better to find yourself a hospital and get your wounds treated!" );

				if ( $_POST['weapon'] == 0 )
				{
					echo "You cornered ". $victim->nickname ." in a deserted street with malfunctioning streetlights. Before $heshe could plead with you, you sank your fists into $hisher stomach and began hitting $himher wherever you could! Before $heshe passed away though, you had to flee from angry onlookers. You delivered a total of $damage damage!";
				} 
				elseif ( $_POST['weapon'] == -1 )
				{
					echo "You sat outside the tavern waiting for " . $victim->nickname . " to leave and head for the parking lot across the street. As soon as they walked out you started up your car and burned rubber heading toward $himher! They tried to jump out of the way but didn't make it completely and were struck only by the corner of your car. You were forced to flee the scene before anyone could read your license plates.";
				}
				else
				{
					switch ( $weapon['category'] )
					{
						case KNIFES:
							echo "You sneaked up towards ". $victim->nickname ." and before $heshe could flee or defend $himherself you stabbed $himher everywhere you could! With a groan, $heshe fell down in drenching the ground with blood. Unfortunately, the $damage damage you inflicted upon $himher wasn't enough to kill $himher...";
							break;
						case GUNS: case RIFLES:
							echo "As ". $victim->nickname ." walked past you, you rose and released a salvo of bullets upon $himher! Failing to kill $himher, you still delivered $damage damage before you had to run for it!";
							break;
					}
				}
			}
		}		
	}
}