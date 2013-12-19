<?
if ( $_POST['bankearn'] == "teller_assistant" )
{
	$money = mt_rand( 35, 70 );
	$exp = mt_rand( 8, 15 );
	$max = ( $char->getBankExp() / 500 ) * 25;
	$max = ( $max < 25 ) ? 25 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You ran around the building all day long, assisting many of the bank's teller staff. You earned $$money.";
	$gmessages[1] = "When one of the bank tellers had to go home, feeling ill, you took over! You earned $$money.";
	$gmessages[2] = "You spent all day hanging around near a friendly bank teller. You learned loads today! You also earned $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "You dropped a stack of bank account request forms. By the time they were sorted again it was 5 o'clock!";
	$fmessages[1] = "You mistook a teller that you were supposed to help for a customer! You still have lots to learn.";
	$fmessages[2] = "Of all forms you had to deliver today, you forgot your own paycheck!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setBankExp( $char->getBankExp() + $exp ); 	
		$char->setCunning( $char->getCunning() + ( $exp * 0.5 ) );
		$char->setIntellect( $char->getIntellect() + ( $exp * 1.5 ) );
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

if ( $_POST['bankearn'] == "teller" )
{
	$money = mt_rand( 50, 120 );
	$exp = mt_rand( 13, 20 );
	$max = ( $char->getBankExp() / 1500 ) * 25;
	$max = ( $max < 5 ) ? 5 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "It was an extremely busy day and you handled a record number of customers! You earned $$money.";
	$gmessages[1] = "You accepted a couple of bank account requests and changed some currency today. You earned $$money.";
	$gmessages[2] = "You didn't give in to an extremely aggressive customer that committed fraud on the account request form! You also earned $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "The manager had overstaffed the bank today - you weren't needed!";
	$fmessages[1] = "You overlooked a fraudulous bank account request! The manager wasn't happy!";
	$fmessages[2] = "You got beaten to a pulp by an extremely aggressive customer whom you had just denied a bank account!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setBankExp( $char->getBankExp() + $exp ); 	
		$char->setIntellect( $char->getCunning() + ( $exp * 0.5 ) );
		$char->setDefense( $char->getIntellect() + ( $exp * 1.5 ) );
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

if ( $_POST['bankearn'] == "loancenter" )
{
	$money = mt_rand( 90, 160 );
	$exp = mt_rand( 18, 25 );
	$max = ( $char->getBankExp() / 3500 ) * 25;
	$max = ( $max < 5 ) ? 5 : ( $max > 95 ) ? 95 : $max;
	$rand = mt_rand( 0, 100 );

	// The messages on succeeding the earn
	$gmessages = array();
	$gmessages[0] = "You showed unnerving integrity today. You declined the loan request of a feared gangster! You earned $$money.";
	$gmessages[1] = "A well respected person requested a loan with you and didn't object to the extra rent you charged! You earned $$money.";
	$gmessages[2] = "You did well today. With the loans you accepted today you gave many homeless bums a chance on a better life! You earned $$money!";

	// The messages for failing the earn
	$fmessages = array();
	$fmessages[0] = "Seems like you made a few mistakes - none of the loans you accepted were paid back!";
	$fmessages[1] = "You accused a well respected politician of not repaying his loan. It turns out you were mistaken!";
	$fmessages[2] = "It was a very quiet day at the loan center. So quiet, in fact, that you went home without profits!";

	// Update the timer
	$char->setEarnTimer();

	if ( $rand > $max )
	{
		$index = array_rand( $fmessages );
		$_SESSION['earn_msg'] = $fmessages[$index];		
	}
	else
	{
		$char->setBankExp( $char->getBankExp() + $exp ); 	
		$char->setIntellect( $char->getCunning() + ( $exp * 0.5 ) );
		$char->setDefense( $char->getIntellect() + ( $exp * 1.5 ) );
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
?>