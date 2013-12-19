<?
/**
 * The emp_agency.php file is the standard income file for many people who don't have a job yet or
 * want
 */
$continue = true;

if ( !isset( $_POST['emp_earn'] ) )
{
	$_SESSION['earn_msg'] = "You have to select an earn!";	
	$continue = false;
}

if ( $char->earn_timer > time() )
{
	$_SESSION['earn_msg'] =  "You cannot do another job so quickly! You hardly recovered from the last!";
	$continue = false;
}

/* THE BANK TRAINEE EARN! */
if ( $continue && $_POST['emp_earn'] == "bank_trainee" && $char->eco == 3 )
{
	$money = mt_rand( 25, 60 );
	$exp = mt_rand( 8, 12 );
	$max = ( $char->getBankExp() / 100 ) * 25;
	$max = ( $max < 25 ) ? 25 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You impressed the bank manager to no limits as you professionally dealt with many clients today! You were given $$money.";
	$gmessages[1] = "You nearly got yourself locked up in the vault, but managed to make a good impression nonetheless! You earned $$money.";
	$gmessages[2] = "You worked hard all day, doing paperwork and other administrative tasks, until finally the manager sent you home with $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "By accident, you managed to lock yourself up in the bank's vault! It took three hours before you were rescued and sent home!";
	$fmessages[1] = "You could no longer stand the whining for loans from unemployed bums and lost control! The manager sent you back home!";
	$fmessages[2] = "You mixed up two transactions today, causing a multinational to loose millions of dollars!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setIntellect( $char->getIntellect() + $exp );
		$char->setCunning( $char->getCunning() + $exp / 2 );
		$char->setBankExp( $char->getBankExp() + $exp * 2 );
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";
	}
}

/* THE BANK TRAINEE EARN! */
if ( $continue && $_POST['emp_earn'] == "med_trainee" && $char->med == 3 )
{
	$money = mt_rand( 25, 45 );
	$exp = mt_rand( 8, 12 );
	$max = ( $char->getHospitalExp() / 100 ) * 25;
	$max = ( $max < 25 ) ? 25 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You drove an old lady safely to her home after she'd been fired from hospital. For your generosity, you earned $$money.";
	$gmessages[1] = "You finally learned how to vaccinate small children and did a wonderful job at that! You earned $$money.";
	$gmessages[2] = "When the hospital director cut himself, you were the one to bandage his sore finger. For this sucking-up, you earned $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You were sent to vaccinate a small child, but you used an infected needle! You were sent home without money.";
	$fmessages[1] = "You bandaged someone's right leg, but he turned out to be injured in his left leg! You failed to impress.";
	$fmessages[2] = "You were asked to bring a patient to the OR, but you braught the doctors the wrong patient!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setIntellect( $char->getIntellect() + $exp );
		$char->setDefense( $char->getDefense() + $exp / 2 );
		$char->setHospitalExp( $char->getHospitalExp() + $exp * 2 );
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];		

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";
	}
}

/* THE DAIRY FACTORY EARN! */
if ( $continue && $_POST['emp_earn'] == "dairy_factory" )
{
	$money = mt_rand( 15, 40 );
	$exp = mt_rand( 1, 3 );
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You worked hard in the dairy factory and brought home a self-made cheese! You also earned $$money.";
	$gmessages[1] = "The head of the cheese-department was thrilled with you! You earned $$money.";
	$gmessages[2] = "You made many cheeses today and brought back a meager $$money.";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You had no luck today and to top it all, you sent a cart of cheeses crashing through a glass door.";
	$fmessages[1] = "You sent a plastic cheese-cover through the cutting machine and broke the production line!";
	$fmessages[2] = "The cheeses made you sick today, you went home feeling shaky...";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand < 25 )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setStrength( $char->getStrength() + $exp ); 
		$char->setDefense( $char->getDefense() + $exp );
		$char->setIntellect( $char->getIntellect() + $exp );
		$char->setCunning( $char->getCunning() + $exp );
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];		

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";
	}
}

/* THE PUBLIC TOILETS EARN! */
if ( $continue && $_POST['emp_earn'] == "public_toilets" )
{
	$money = mt_rand( 10, 30 );
	$exp = mt_rand( 1, 3 );
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You cleaned many public toilets today. You earned yourself $$money.";
	$gmessages[1] = "You cleaned toilets the whole day and even got to kick some tramps! You earned $$money.";
	$gmessages[2] = "You made the public toilets shine, only for a meager $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You had no motivation today, many toilets remained uncleaned!";
	$fmessages[1] = "Just when you wanted to piss over your third tramp you were spotted by a cop...";
	$fmessages[2] = "You slipped over a banana and you were unable to continue working.";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand < 25 )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setStrength( $char->getStrength() + $exp ); 
		$char->setDefense( $char->getDefense() + $exp );
		$char->setIntellect( $char->getIntellect() + $exp );
		$char->setCunning( $char->getCunning() + $exp );
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];		

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";		
	}
}

/* THE OFFICE ARCHIVE EARN! */
if ( $continue && $_POST['emp_earn'] == "office_archive" )
{
	$money = mt_rand( 20, 40 );
	$exp = mt_rand( 1, 3 );
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You archived a record amount of dossiers in one day. You earned yourself $$money.";
	$gmessages[1] = "There were just a few dossiers today, you had a good time drinking coffee! You earned $$money.";
	$gmessages[2] = "You had little motivation but worked your way through the pile nonetheless. You earned $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You managed to archive dossiers completely at random. Nothing could be found anymore!";
	$fmessages[1] = "You thought you could risk lighting a cigarette but torched the whole archive!";
	$fmessages[2] = "You arrived at work but there were no dossiers for you to archieve. Bad luck.";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand < 25 )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setStrength( $char->getStrength() + $exp ); 
		$char->setDefense( $char->getDefense() + $exp );
		$char->setIntellect( $char->getIntellect() + $exp );
		$char->setCunning( $char->getCunning() + $exp );
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];		

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";		
	}
}

/* THE NIGHTCLUB BOUNCER EARN! */
if ( $continue && $_POST['emp_earn'] == "nightclub_bouncer" && $char->getStrength() >= 1000 )
{
	$money = mt_rand( 30, 50 );
	$str = mt_rand( 3, 6 );
	$max = ( $char->getStrength() / 750 ) * 25;
	$max = ( $max < 5 ) ? 5 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "It was a quiet night, only two people needed to be kicked from the club. You earned $$money.";
	$gmessages[1] = "You had to beat the crap out of a dealer standing next to the club! You earned $$money.";
	$gmessages[2] = "It was a rough night, many guests drank too much and needed your persuasive abilities! You earned $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You arrived at work but you'd been replaced by a newcomer! So much for bouncing today!";
	$fmessages[1] = "You didn't notice a guest wearing a gun, the massacre was unbelievable!";
	$fmessages[2] = "Just when you wanted to beat the crap out of a dealer his mates turned up. Bad luck!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setStrength( $char->getStrength() + $str ); 			
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];		

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";		
	}
}

/* THE NIGHTCLUB BARMAN EARN! */
if ( $continue && $_POST['emp_earn'] == "nightclub_barman" && $char->getDefense() >= 1000 )
{
	$money = mt_rand( 25, 60 );
	$def = mt_rand( 3, 6 );
	$max = ( $char->getDefense() / 750 ) * 25;
	$max = ( $max < 5 ) ? 5 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "It was crowded around the bar and you made the club a good profit! You earned $$money.";
	$gmessages[1] = "There weren't many guests but you still managed to sell enough to keep the manager happy! You earned $$money.";
	$gmessages[2] = "A rich guy organised a party at your club, business went booming! You earned $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You got many complaints today and the manager sent you home early!";
	$fmessages[1] = "You broke eight bottles of beer and ruined a party member's shirt. No luck tonight!";
	$fmessages[2] = "A man with a gun went on a massacre, you survived but made no money!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setDefense( $char->getDefense() + $def ); 			
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];		

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";		
	}
}

/* THE SUPERMARKET STOCKS EARN! */
if ( $continue && $_POST['emp_earn'] == "supermarket_stocks" && $char->getIntellect() >= 1000 )
{
	$money = mt_rand( 25, 60 );
	$int = mt_rand( 3, 6 );
	$max = ( $char->getIntellect() / 750 ) * 25;
	$max = ( $max < 5 ) ? 5 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You turned out to be a natural talent at analyzing stocks! You earned $$money.";
	$gmessages[1] = "You slightly overstocked today, but you got paid nonetheless! You earned $$money.";
	$gmessages[2] = "You got to shout at a shelf stocker today, he completely ruined your stocking scheme! You earned $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You completely failed at analyzing the supermarket's needs. No pay for you today!";
	$fmessages[1] = "You grossly overstocked, half of what you ordered will need to be thrown away!";
	$fmessages[2] = "Your stocks were way too low. Soon customers complained that their favourite yoghurt wasn't there!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setIntellect( $char->getIntellect() + $int ); 			
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];			

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";	
	}
}

/* THE CALLCENTER EARN! */
if ( $continue && $_POST['emp_earn'] == "callcenter" && $char->getCunning() >= 1000 )
{
	$money = mt_rand( 25, 55 );
	$cun = mt_rand( 3, 6 );
	$max = ( $char->getCunning() / 750 ) * 25;
	$max = ( $max < 5 ) ? 5 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You are a natural talent at deceiving people! You earned $$money.";
	$gmessages[1] = "You had many old ladies on the phone and you had no problems selling your products! You earned $$money.";
	$gmessages[2] = "Nobody could resist your cute voice and careful flattery! You earned $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You caught a cold and your voice wasn't quite able to convince people!";
	$fmessages[1] = "You tried to sell fake-guns to a local police station. They weren't happy!";
	$fmessages[2] = "The first person you called tricked you into believing you were suspect in a murder case! Definately not your day!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setCunning( $char->getCunning() + $cun ); 			
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];			

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";
	}
}

/* THE BROTHEL BOUNCER EARN! */
if ( $continue && $_POST['emp_earn'] == "brothel_bouncer" && $char->getStrength() >= 2000 )
{
	$money = mt_rand( 35, 70 );
	$exp = mt_rand( 5, 10 );
	$max = ( $char->getStrength() / 1500 ) * 25;
	$max = ( $max < 5 ) ? 5 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You stood guard at a meeting of your boss and a rival pimp! You earned $$money.";
	$gmessages[1] = "When a whore went missing you were the one who found and brought her back! You earned $$money.";
	$gmessages[2] = "You prevented a few tramps from abusing the brothel's hospitality! You earned $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "A guy got away without paying - that means no pay for you!";
	$fmessages[1] = "You were called upstairs to swing your bat, but instead <i>you</i> received a painful blow in the face!";
	$fmessages[2] = "You beat up an unwilling guest in plain view of a cop! He wasn't happy, nor was your boss!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setStrength( $char->getStrength() + $exp ); 			
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];			

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";	
	}
}

/* THE BROTHEL CLEANER EARN! */
if ( $continue && $_POST['emp_earn'] == "brothel_cleaner" && $char->getDefense() >= 2000 )
{
	$money = mt_rand( 30, 65 );
	$exp = mt_rand( 5, 10 );
	$max = ( $char->getDefense() / 1500 ) * 25;
	$max = ( $max < 5 ) ? 5 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You cleaned the toilets inside and out. As you were scraping the last piece of crap off the seat, you found 2 crack rocks! You sold them for $$money.";
	$gmessages[1] = "Your boss asked to work a little longer tonight. You got home stinking like the toilets you cleaned, but earned $$money!";
	$gmessages[2] = "As you finished the toilets, someone came to have sex with you and they actually paid you! You earned $$money";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You asked a friend to help you at work today. As you finished the shift he beat you up and stole your earnings. Bad luck!";
	$fmessages[1] = "Just when you left the toilets a sick-looking guest went in... You knew you wasted a night!";
	$fmessages[2] = "You smelt so badly that your boss didn't want to come close to give you your money!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setDefense( $char->getDefense() + $exp ); 			
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];			

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";	
	}
}

/* THE NEWSPAPER REDACTION EARN! */
if ( $continue && $_POST['emp_earn'] == "newspaper_red" && $char->getIntellect() >= 2000 )
{
	$money = mt_rand( 40, 75 );
	$exp = mt_rand( 5, 10 );
	$max = ( $char->getIntellect() / 1500 ) * 25;
	$max = ( $max < 5 ) ? 5 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You wrote an article on the damaging effects of masturbation! It was published and you earned $$money!";
	$gmessages[1] = "You spent a good day copying articles from colleagues. Yours were published first and you earned $$money!";
	$gmessages[2] = "You helped your friend write a top story and got a fair share of the profit! You earned $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "Your article about the local brothel contained way too many 'unfit' details. Your article wasn't published!";
	$fmessages[1] = "You went out to smoke a cigarette just when an anonymous call came in warning about a terrorist attack! A colleague took it and you got nothing!";
	$fmessages[2] = "You were so 'into' your story about local politics that you overheated your keyboard and crashed the computer!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setIntellect( $char->getIntellect() + $exp ); 			
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];		

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";	
	}
}

/* THE DOOR BY DOOR EARN */
if ( $continue && $_POST['emp_earn'] == "door_by_door" && $char->getCunning() >= 2000 )
{
	$money = mt_rand( 45, 80 );
	$exp = mt_rand( 5, 10 );
	$max = ( $char->getCunning() / 1500 ) * 25;
	$max = ( $max < 5 ) ? 5 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You managed to sell a load of broken dildos to a few old ladies and men! You made $$money!";
	$gmessages[1] = "You sold many iPods today. Thankfully the buyers didn't know about the porn you stashed on them as well! You earned $$money!";
	$gmessages[2] = "You persuaded the local don to buy your super-soakers for his drive-by's. You earned $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You spent a long day feeling doors being slammed in your face! You'll need a bit more practise!";
	$fmessages[1] = "You sold huge amounts of products! Unfortunately you got mugged on your way back and you were left with nothing!";
	$fmessages[2] = "You accidently tried to sell a product to a lady who bought a broken dildo from you earlier. She slapped you in the face!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setCunning( $char->getCunning() + $exp ); 			
		$char->setCleanMoney( $char->getCleanMoney() + $money );

		$index = array_rand( $gmessages );
		$_SESSION['earn_msg'] = $gmessages[$index];		

		$add = "";
		if ( ( $add = dropRandomSpecialItem( $char ) ) == "" )
			if ( ( $add = dropRandomUnusualItem( $char ) ) == "" )
				$add = dropRandomTrivialItem( $char );

		$_SESSION['earn_msg'] .= " $add";
					
	}
}
