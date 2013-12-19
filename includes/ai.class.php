<?
/* AI Class
 * Creates actions based on an ai configuration file.
 */

class AI
{
	var $db;				// Class DB Connection
	var $folder;			// This AIs config folder

	var $Personality;		// AI Personality Array
	var $CharID;			// AI Character ID (char_characters row)
	var $AccountID;			// AI Account ID
	var $Player;			// AI Player()


	function AI( $folder_name )
	{
		global $db;
		$this->db = $db;

		/* Error Checking */
		if ( !is_string( $folder_name ) )
		{
			$err->Error = "Folder name for AI constructor is a string.";
			return false;
		}

		if ( !is_readable( $folder_name . "/personality.ini" ) )
		{
			$err->Error = "Could not read personality file for AI (" . $folder_name . ").";
			return false;
		}

		$this->folder = $folder_name;

		/* Personality Array */
		$this->Personality = parse_ini_file( $folder_name . "/personality.ini", true );

		/* Create a character, if needed. */
		$result = $this->db->query( "SELECT * FROM char_characters WHERE nickname='" . $this->Personality["Profile"]["Nickname"] . "'" );
		if ( $this->db->getRowCount( $result ) == 0 )
		{
			/* Create Account */
			$this->db->query( "INSERT INTO persons (id, firstname, lastname, country_id, city, email) VALUES ('', '" . $this->Personality["Profile"]["Firstname"] . "', '" . $this->Personality["Profile"]["Lastname"] . "', 1, 'Red Republic', 'ai@red-republic.com')" );
			$this->db->query( "INSERT INTO accounts (id, username, password, person_id, character_id, rights, last_active) VALUES ('', '" . $this->Personality["Profile"]["Nickname"] . "', '" . md5( $this->Personality["Profile"]["Password"] ) . "', " . mysql_insert_id() . ", 0, " . $this->Personality["AI"]["Rights"] . ", " . time() . ")" );
			$acct_id = mysql_insert_id();
			$this->AccountID = $acct_id;
			
			/* Create Char */
			$this->db->query( "INSERT INTO char_characters ( `id` , `account_id` , `nickname` , `firstname` , `lastname` , `gender` , `birthdate` , `creationdate` , `background` , `money_clean` , `money_dirty` , `homecity` , `location` , `stats_id` , `timers_id` , `rank_id` , `health` , `maxhealth` , `quote` ) VALUES ('', '" . $acct_id . "', '" . $this->Personality["Profile"]["Nickname"] . "', '" . $this->Personality["Profile"]["Firstname"] . "', '" . $this->Personality["Profile"]["Lastname"] . "', '" . $this->Personality["Profile"]["Gender"] . "', '" . $this->Personality["Profile"]["Birthdate"] . "', '" . $this->Personality["Profile"]["Creationdate"] . "', " . $this->db->prepval( $this->Personality["Description"]["Background"] ) . ", " . $this->Personality["Finance"]["StartingCash"] . ", 0, " . $this->Personality["Profile"]["HomeCity_ID"] . ", " . $this->Personality["Profile"]["StartingCity_ID"] . ", 0, 0, " . $this->Personality["Occupation"]["Rank_ID"] . ", " . $this->Personality["Profile"]["StartingHealth"] . ", " . $this->Personality["Profile"]["MaxHealth"] . ", " . $this->db->prepval( $this->Personality["Description"]["Quote"] ) . ")" );

			$this->CharID = mysql_insert_id();

			$this->db->query( "UPDATE accounts SET character_id=" . $this->CharID . " WHERE id=" . $acct_id );

			/* Setup Deed Vals */
			$this->db->query( "INSERT INTO char_deeds (char_id, slot_a, slot_b, slot_c, slot_d, slot_e, slot_f, slot_g, slot_h, slot_i, slot_j, slot_k, slot_l, slot_m, slot_n, slot_o, slot_p) VALUES (" . $this->CharID . ", " . $this->Personality["Deeds"]["slot_a"] . ", " . $this->Personality["Deeds"]["slot_b"] . ", " . $this->Personality["Deeds"]["slot_c"] . ", " . $this->Personality["Deeds"]["slot_d"] . ", " . $this->Personality["Deeds"]["slot_e"] . ", " . $this->Personality["Deeds"]["slot_f"] . ", " . $this->Personality["Deeds"]["slot_g"] . ", " . $this->Personality["Deeds"]["slot_h"] . ", " . $this->Personality["Deeds"]["slot_i"] . ", " . $this->Personality["Deeds"]["slot_j"] . ", " . $this->Personality["Deeds"]["slot_k"] . ", " . $this->Personality["Deeds"]["slot_l"] . ", " . $this->Personality["Deeds"]["slot_m"] . ", " . $this->Personality["Deeds"]["slot_n"] . ", " . $this->Personality["Deeds"]["slot_o"] . ", " . $this->Personality["Deeds"]["slot_p"] . ")" ); 

			/* Insert Into char_stats */
			$this->db->query( "INSERT INTO char_stats (id, strength, defense, intellect, cunning, criminal_exp, bank_exp, bank_trust) VALUES ('', " . $this->Personality["Stats"]["Strength"] . ", " . $this->Personality["Stats"]["Defense"] . ", " . $this->Personality["Stats"]["Intellect"] . ", " . $this->Personality["Stats"]["Cunning"] . ", " . $this->Personality["Stats"]["Criminal_Exp"] . ", " . $this->Personality["Stats"]["Bank_Exp"] . ", " . $this->Personality["Stats"]["Bank_Trust"] . ")" );
			$this->db->query( "UPDATE char_characters SET stats_id=" . mysql_insert_id() . " WHERE id=" . $this->CharID );

			/* Insert into char_timers */
			$this->db->query( "INSERT INTO char_timers (id, earn_timer, earn_timer2, study_timer, agg_timer, agg_timer2, murder_timer, online_timer, agg_pro, kill_pro, bar_timer) VALUES ('', " . time() . ", " . time() . ", " . time() . ", " . time() . ", " . time() . ", " . time() . ", " . time() . ", " . time() . ", " . time() . ", " . time() . ")" );
			$this->db->query( "UPDATE char_characters SET timers_id=" . mysql_insert_id() . " WHERE id=" . $this->CharID );

			/* Insert into equipment */
			$this->db->query( "INSERT INTO char_equip (char_id, armor_head, armor_chest, armor_legs, armor_feet, main_weapon, weap2_open, second_weapon, bag) VALUES (" . $this->CharID . ", " . $this->Personality["Equipment"]["ArmorHead"] . ", " . $this->Personality["Equipment"]["ArmorChest"] . ", " . $this->Personality["Equipment"]["ArmorLegs"] . ", " . $this->Personality["Equipment"]["ArmorFeet"] . ", " . $this->Personality["Equipment"]["MainWeapon"] . ", " . $this->Personality["Equipment"]["Wep2Open"] . ", " . $this->Personality["Equipment"]["SecondWeapon"] . ", " . $this->Personality["Equipment"]["Bag"] . ")" );
		}
		else
		{
			/* Update Char */
			$charrow = $this->db->fetch_array( $result );
			$this->CharID = $charrow["id"];
			$this->AccountID = $charrow["account_id"];
			
			$this->Player = new Player( $this->AccountID, true );

			if ( $this->Personality["AI"]["AlwaysOnline"] )
				$time = time() + 600;
			else
				$time = time(); // Debug, there is no AI scripting for different login and logoff times, as of yet.

			$this->db->query( "UPDATE char_timers SET online_timer=$time WHERE id=" . $charrow["timers_id"] );
		}
	}

	/* Result call operation, mastermind function */
	function result()
	{
		$activity = $this->getActivityLevel( $this->Personality["AI"]["Activity"] );

		/* Random Actions
		 * The following is ment to read "randomevents.ini" and
		 * select random lines to perform random behavoir.
		 * Having no random events means the AI only performs
		 * scripted behavoir.
		 */
		$rand = mt_rand( $activity[0], $activity[1] );
		if ( $rand == $activity[0] && $this->Personality["AI"]["RandomActions"] )
		{
			$events = file_get_contents( $this->folder . "/randomevents.ini" );
			$events_lines = explode( "\n", $events );
			$events_rand = mt_rand( 0, count( $events_lines )-1 );

			/* Perform Random Event */
			$event = explode( ":", $events_lines[$events_rand] );

			switch( $event[0] )
			{
				case "NormalEvent":
				{
					/* NormalEvent:char_id:Event Title:Event Text */
					$eventchar = new Player( getAccountID( $event[1] ) );
					$eventchar->addEvent( $event[2], $event[3] );
				} break;

				case "NormalEventCash":
				{
					/* NormalEventCash:char_id:ammount:Event Title:Event Text */
					if ( $this->Player->getCleanMoney() >= $event[2] )
					{
						$eventchar = new Player( getAccountID( $event[1] ) );
						$eventchar->addEvent( $event[3], $event[4] );
						$eventchar->setCleanMoney( $eventchar->getCleanMoney( true ) + $event[2] );
						$this->Player->setCleanMoney( $this->Player->getCleanMoney( true ) - $event[2] );
					}
				} break;

				case "EventRequest":
				{
					/* EventRequest:char_id:template_name */
					$eventchar = new Player( getAccountID( $event[1] ) );
					$eventchar->addEventRequestFromTemplate( $event[2] );
				} break;

				case "TransferCleanMoney":
				{
					/* TransferCleanMoney:char_id:ammount */
					if ( $this->Player->getCleanMoney() >= $event[2] )
					{
						$cashchar = new Player( getAccountID( $event[1] ) );
						$cashchar->setCleanMoney( $cashchar->getCleanMoney( true ) + $event[2] );
						$this->Player->setCleanMoney( $this->Player->getCleanMoney( true ) - $event[2] );
					}
				} break;

				case "AddDeedPoint":
				{
					/* AddDeedPoint:slot_?:value */
					$this->Player->addDeedPoint( $event[1], $event[2] );
				} break;

				case "ReduceDeedPoint":
				{
					/* ReduceDeedPoint:slot_?:value */
					$this->Player->reduceDeedPoint( $event[1], $event[2] );
				} break;

				case "SendMessage":
				{
					/* SendMessage:char_id:message title:message text */
					$smsg = str_replace( "(col)", ":", $event[3] );
					$this->db->query( "INSERT INTO comms SET `to`=" . $event[1] . ", `from`=" . $this->Player->getCharacterID() . ", `date`='" . date( "Y-m-d H:i:s", time() ) . "', `subject`=" . $this->db->prepval( $event[2] ) . ", `message`=" . $this->db->prepval( $smsg ) . ", `comm_new`=1, `comm_type`=0" );
				} break;
			}
		}

		/* Direction Actions
		 * These actions have special conditions
		 * as a command that trigger them, rather
		 * than at random.
		 */
		$devents = file_get_contents( $this->folder . "/events.ini" );
		$devents_lines = explode( "\n", $devents );

		$i = 0;
		while ( $i < count( $devents_lines ) )
		{
			$devent = explode( ":", $devents_lines[$i] );

			switch ( $devent[0] )
			{
				case "RespondToMessageWithSubject":
				{
					/* RespondToMessageWithSubject:Subject Condition:Reply Subject:Reply Message */
					/* Currently: NOT WORKING */
					$msgres = $this->db->query( "SELECT * FROM comms WHERE `to`=" . $this->Player->getCharacterID() . " AND `subject`=" . str_replace( "(col)", ":", $this->db->prepval( $devent[1] ) ) );
					
					if ( $this->db->getRowCount( $msgres ) > 0 )
					{
						while ( $msg = $this->db->fetch_array( $msgres ) )
						{
							$this->db->query( "INSERT INTO comms SET `to`=" . $msg["from"] . ", `from`=" . $this->Player->getCharacterID() . ", `date`='" . date( "Y-m-d H:i:s", time() ) . "', `subject`=" . $this->db->prepval( str_replace( "(col)", ":", $event[2] ) ) . ", `message`=" . $this->db->prepval( str_replace( "(col)", ":", $event[3] ) ) . ", `comm_new`=1, `comm_type`=0" );
							$this->db->query( "DELETE FROM comms WHERE `id`=" . $msg["id"] );
						}
					}
				} break;
			}
			
			$i++;
		}
	}

	/* This function switches "activity words" like "Activity_Medium" into rand() usable integers. */
	function getActivityLevel( $word )
	{
		if ( !is_string( $word ) )
		{
			$err->Error = "Invalid word parameter for getActivityLevel for the AI class.";
			return false;
		}

		switch ( $word )
		{
			case "VeryLow": return array( 1, 40 );
			case "Low": return array( 1, 30 );
			case "Medium": return array( 1, 20 );
			case "High": return array( 1, 15 );
			case "VeryHigh": return array( 1, 10 );
			default: return array( 1, 20 );
		}
	}
}

?>