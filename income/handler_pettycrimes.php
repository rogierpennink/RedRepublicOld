<?
/**
 * The emp_agency.php file is the standard income file for many people who don't have a job yet or
 * want
 */
$continue = true;

if ( !isset( $_POST['petty_crime'] ) )
{
	$_SESSION['earn_msg'] = "You have to select an earn!";	
	$continue = false;
}

if ( $char->earn_timer > time() )
{
	$_SESSION['earn_msg'] =  "You cannot do another job so quickly! You hardly recovered from the last!";
	$continue = false;
}

/* THE NICK FROM STORES EARN! */
if ( $continue && $_POST['petty_crime'] == "nickstores" && $char->getCriminalExp() >= 250 )
{	
	$exp = mt_rand( 7, 12 );
	$max = ( $char->getCriminalExp() / 300 ) * 25;
	$max = ( $max < 5 ) ? 5 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );
	
	/* Select an item from the db. */
	$iquery = $db->query( "SELECT * FROM items WHERE quality=0 AND category=0 AND name <> 'Unknown' ORDER BY RAND() LIMIT 1" );
	$item = $db->fetch_array( $iquery );

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You were just about to put a ". $item['name'] ." into your bag when the shopkeeper saw you and chased you out of his shop!";
	$fmessages[1] = "You managed to nick a ". $item['name'] ." from a store, but you dropped your bag, breaking it!";
	$fmessages[2] = "The shop you tried to steal from had a trap set up! You only just got away from two angry-looking police officers!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];
	}
	else
	{
		$bag = new Item( $char->bag );
		$numslots = $db->getRowCount( $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id ) );

		if ( $numslots >= $bag->bagslots )
		{
			$_SESSION['earn_message'] = "You cunningly nicked a ". $item['name'] ." from a shop's shelves, but were forced to put it back because your bag was full!";
		}
		else
		{
			$gmessages = array();
			$gmessages[0] = "You pretended to drop an item from one of the shelves but took care to not put it back! You gained yourself a ". $item['name'] ."!";
			$gmessages[1] = "You put aside all subtlety and tactics and threatened the shop owner with a gun. You ran out of store with a brand new ". $item['name'] ."!";
			$gmessages[2] = "There was nobody in the shop. You casually took a ". $item['name'] ." from the shelves and walked out, whistling a tune!";

			/* Add item to inventory. */
			$db->query( "INSERT INTO char_inventory SET char_id=". $char->character_id .", item_id=". $item['item_id'] );
		}

		/* Update player stats... */
		$char->setCriminalExp( $char->getCriminalExp() + $exp );
		$char->setCunning( $char->getCunning() + ( $exp / 2 ) );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];
	}
}

/* THE STEAL CAR RADIOS EARN! */
if ( $continue && $_POST['petty_crime'] == "carradios" && $char->getCriminalExp() >= 500 )
{	
	$exp = mt_rand( 12, 17 );
	$max = ( $char->getCriminalExp() / 750 ) * 25;
	$max = ( $max < 5 ) ? 5 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );
	$money = mt_rand( 100, 200 );
		
	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You forgot to check for the presence of a car alarm! Your attempt ended up rather humiliating for you!";
	$fmessages[1] = "Just when you broke the car's window a police constable came around the corner! You only just managed to get away!";
	$fmessages[2] = "You managed to extract a car radio from a BMW, but you were scammed while selling it! No profits for you!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$gmessages = array();
		$gmessages[0] = "You brutely forced your way into a shabby looking station wagon and rushed away with an old car radio! You earned $$money selling it!";
		$gmessages[1] = "You skillfully unlocked a brand new BMW with a hairpin and swiftly took the radio inside it! You made $$money selling it!";
				
		/* Update player stats... */
		$char->setCriminalExp( $char->getCriminalExp() + $exp );
		$char->setCunning( $char->getCunning() + ( $exp / 4 ) );
		$char->setStrength( $char->getStrength() + ( $exp / 4 ) );
		$char->setDirtyMoney( $char->getDirtyMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];
	}
}