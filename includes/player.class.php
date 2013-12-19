<?
/**
 * The player class, on giving it a player name or user id it will fetch all information about that character
 * and become an object through which the programmer can update and retrieve character information.
 * Author: Rogier Pennink.
 */

class Player
{
	// The information variable, we can use this to loop through if necessary
	var $info;
	var $db;
	var $armour;
	var $extrastr;
	var $extradef;
	var $extraint;
	var $extracun;
	var $quote;

	// Initialize the structure, setting all class variables based on the
	// names from the characterinfo array.
	function Player( $user, $acc_id = true )
	{
		global $db;
		$this->db = $db;

		if ( is_numeric( $user ) )
			$this->info = getCharacterInfo( $user, $acc_id );
		else
			$this->info = getCharacterInfo( getUserID( $user ) );

		foreach ( $this->info as $key => $value )		
			$this->$key = $value;	
		
		$head = new Item( $this->armor_head );
		$chest = new Item( $this->armor_chest );
		$legs = new Item( $this->armor_legs );
		$feet = new Item( $this->armor_feet );
		$bag = new Item( $this->bag );
		$weap1 = new Item( $this->main_weapon );
		$weap2 = new Item( $this->second_weapon );
		$this->armour = $head->armor + $chest->armor + $legs->armor + $feet->armor + $bag->armor + $weap1->armor + $weap2->armor;

		$this->extrastr = $head->strength + $chest->strength + $legs->strength + $feet->strength + $bag->strength + $weap1->strength + $weap2->strength;
		$this->extradef = $head->defense + $chest->defense + $legs->defense + $feet->defense + $bag->defense + $weap1->defense + $weap2->defense;
		$this->extraint = $head->intellect + $chest->intellect + $legs->intellect + $feet->intellect + $bag->intellect + $weap1->intellect + $weap2->intellect;
		$this->extracun = $head->cunning + $chest->cunning + $legs->cunning + $feet->cunning + $bag->cunning + $weap1->cunning + $weap2->cunning;
	}

	/**
	 * Strength stat setters and getters.
	 * Author: Rogier Pennink.
	 */
	function setStrength( $newstrength )
	{
		// Update it in the database
		if ( $this->db->query( "UPDATE char_stats SET strength=". $this->db->prepval( $newstrength ) ." WHERE id=". $this->stats_id ) === false )
		{
			$err->Error = "The query to update the strength in the setStrength function was found to be invalid for stats_id: " . $this->stats_id . ".";
			return false;
		}

		$this->strength = $newstrength;

		return true;
	}

	function getStrength()
	{
		return $this->strength;
	}

	function getTotalStr()
	{
		return $this->strength + $this->extrastr * 100;
	}

	/**
	 * Defense stat setters and getters.
	 * Author: Rogier Pennink.
	 */
	function setDefense( $newdefense )
	{
		// Update it in the database
		if ( $this->db->query( "UPDATE char_stats SET defense=". $this->db->prepval( $newdefense ) ." WHERE id=". $this->stats_id ) === false )
		{
			$err->Error = "The query to update the defense in the setDefense function was found to be invalid for stats_id: " . $this->stats_id . ".";
			return false;
		}

		$this->defense = $newdefense;

		return true;
	}

	function getDefense()
	{
		return $this->defense;
	}

	function getTotalDef()
	{
		return $this->defense + $this->extradef * 100;
	}

	/**
	 * Intellect stat setters and getters.
	 * Author: Rogier Pennink.
	 */
	function setIntellect( $newintellect )
	{
		// Update it in the database
		if ( $this->db->query( "UPDATE char_stats SET intellect=". $this->db->prepval( $newintellect ) ." WHERE id=". $this->stats_id ) === false )
		{
			$err->Error = "The query to update the intellect in the setIntellect function was found to be invalid for stats_id: " . $this->stats_id . ".";
			return false;
		}

		$this->intellect = $newintellect;

		return true;
	}

	function getIntellect()
	{
		return $this->intellect;
	}

	function getTotalInt()
	{
		return $this->intellect + $this->extraint * 100;
	}

	/**
	 * Cunning stat setters and getters.
	 * Author: Rogier Pennink.
	 */
	function setCunning( $newcunning )
	{
		// Update it in the database
		if ( $this->db->query( "UPDATE char_stats SET cunning=". $this->db->prepval( $newcunning ) ." WHERE id=". $this->stats_id ) === false )
		{
			$err->Error = "The query to update the cunning in the setCunning function was found to be invalid for stats_id: " . $this->stats_id . ".";
			return false;
		}

		$this->cunning = $newcunning;

		return true;
	}

	function getCunning()
	{
		return $this->cunning;
	}

	function getTotalCun()
	{
		return $this->cunning + $this->extracun * 100;
	}

	/**
	 * Criminal Exp stat setters and getters.
	 * Author: Rogier Pennink.
	 */
	function setCriminalExp( $newexp )
	{
		// Update it in the database
		if ( $this->db->query( "UPDATE char_stats SET criminal_exp=". $this->db->prepval( $newexp ) ." WHERE id=". $this->stats_id ) === false )
		{
			$err->Error = "The query to update the criminal exp. in the setCriminalExp function was found to be invalid for stats_id: " . $this->stats_id . ".";
			return false;
		}

		$this->criminal_exp = $newexp;

		return true;
	}

	function getCriminalExp()
	{
		return $this->criminal_exp;
	}

	/**
	 * Bank Exp stat setters and getters.
	 * Author: Rogier Pennink.
	 */
	function setBankExp( $newexp )
	{
		// Check if a promotion should be inserted...
		$rindex = ( $this->occupation_nr != 3 ) ? 0 : $this->rank_index + 1;
		$ptestquery = $this->db->query( "SELECT * FROM char_ranks WHERE occupation_id=3 AND order_index=$rindex AND exp_required <= $newexp" );
		if ( $this->db->getRowCount( $ptestquery ) > 0 )
		{
			$promo = $this->db->fetch_array( $ptestquery );
			/* Check if there is a manager that can promote... */
			if ( $this->db->getRowCount( $this->db->query( "SELECT * FROM char_characters WHERE homecity=". $this->homecity_nr ." AND id<>". $this->character_id ." AND ( rank_id=7 OR rank_id=8 )" ) ) == 0 && $promo['id'] != 8 )
				$promo['auto_promo'] = 1;
			$this->db->query( "INSERT INTO char_promos SET char_id=". $this->character_id .", next_rank=". $promo['id'] .", status=". ( $promo['auto_promo'] + 1 ) .", occupation_id=3" );
		}

		// Update it in the database
		if ( $this->db->query( "UPDATE char_stats SET bank_exp=". $this->db->prepval( $newexp ) ." WHERE id=". $this->stats_id ) === false )
		{
			$err->Error = "The query to update the bank exp. in the setBankExp function was found to be invalid for stats_id: " . $this->stats_id . ".";
			return false;
		}

		$this->bank_exp = $newexp;

		return true;
	}

	function getBankExp()
	{
		return $this->bank_exp;
	}

	/**
	 * Bank Exp stat setters and getters.
	 * Author: Rogier Pennink.
	 */
	function setHospitalExp( $newexp )
	{
		// Check if a promotion should be inserted...
		$rindex = ( $this->occupation_nr != 4 ) ? 0 : $this->rank_index + 1;
		$ptestquery = $this->db->query( "SELECT * FROM char_ranks WHERE occupation_id=4 AND order_index=$rindex AND exp_required <= $newexp" );
		if ( $this->db->getRowCount( $ptestquery ) > 0 )
		{
			$promo = $this->db->fetch_array( $ptestquery );
			/* Check if there is a manager that can promote... */
			//if ( $this->db->getRowCount( $this->db->query( "SELECT * FROM char_characters WHERE homecity=". $this->homecity_nr ." AND id<>". $this->character_id ." AND ( rank_id=7 OR rank_id=8 )" ) ) == 0 )
				$promo['auto_promo'] = 1;
			$this->db->query( "INSERT INTO char_promos SET char_id=". $this->character_id .", next_rank=". $promo['id'] .", status=". ( $promo['auto_promo'] + 1 ) .", occupation_id=4" );
		}

		// Update it in the database
		if ( $this->db->query( "UPDATE char_stats SET hospital_exp=". $this->db->prepval( $newexp ) ." WHERE id=". $this->stats_id ) === false )
		{
			$err->Error = "The query to update the hospital exp. in the setHospitalExp function was found to be invalid for stats_id: " . $this->stats_id . ".";
			return false;
		}

		$this->hospital_exp = $newexp;

		return true;
	}

	function getHospitalExp()
	{
		return $this->hospital_exp;
	}

	/**
	 * Clean money setters and getters.
	 * Author: Rogier Pennink.
	 */
	function setCleanMoney( $newmoney, $allownegative = false )
	{
		// Update it in the database
		if ( $this->db->query( "UPDATE char_characters SET money_clean=". $this->db->prepval( $newmoney ) ." WHERE id=". $this->character_id ) === false )
		{
			$err->Error = "The query to update the clean money in the setCleanMoney function was found to be invalid for character id: " . $this->character_id . ".";
			return false;
		}

		$this->money_clean = $newmoney;

		if ( $this->money_clean < 0 )
			$err->Error = "The new clean money value for the character id " . $this->character_id . " has fallen into a negative value.";

		return true;
	}

	function getCleanMoney( $query = false )
	{
		if ( $query )
		{
			$rec = $this->db->query( "SELECT money_clean FROM char_characters WHERE id=". $this->character_id );
			$res = $this->db->fetch_array( $rec );

			$this->money_clean = $res['money_clean'];
		}

		return $this->money_clean;
	}

	function setHealth( $newhealth )
	{
		// Update it in the database
		if ( $this->db->query( "UPDATE char_characters SET health=". $this->db->prepval( $newhealth ) ." WHERE id=". $this->character_id ) === false )
		{
			$err->Error = "The query to update the health in the setHealth function was found to be invalid for character id: " . $this->character_id . ".";
			return false;
		}

		$this->health = $newhealth;

		if ( $this->health < 0 )
			$err->Error = "The health for character id " . $this->character_id . " has fallen into a negative value.";

		if ( $this->health > $this->maxhealth )
			$err->Error = "The health for character id " . $this->character_id . " has exceeded their max health value (" . $this->maxhealth . ").";

		return true;
	}

	function getHealth( $query = false )
	{
		if ( $query )
		{
			$rec = $this->db->query( "SELECT health FROM char_characters WHERE id=". $this->character_id );
			$res = $this->db->fetch_array( $rec );

			$this->health = $res['health'];
		}

		return $this->health;
	}

	/**
	 * Dirty money setters and getters.
	 * Author: Rogier Pennink.
	 */
	function setDirtyMoney( $newmoney, $allownegative = false )
	{
		// Update it in the database
		if ( $this->db->query( "UPDATE char_characters SET money_dirty=". $this->db->prepval( $newmoney ) ." WHERE id=". $this->character_id ) === false )
		{
			$err->Error = "The query to update the dirty money in the setDirtyMoney function was found to be invalid for character id: " . $this->character_id . ".";
			return false;
		}

		$this->money_dirty = $newmoney;

		if ( $this->money_clean < 0 )
			$err->Error = "The new dirty money value for the character id " . $this->character_id . " has fallen into a negative value.";


		return true;
	}

	function setDirtyMoneyEx( $newmoney )
	{
		$err->Error = "The setDirtyMoneyEx function has been called, but is obsolete and needs to be replaced with setDirtyMoney.";

		if ( $this->db->query( "UPDATE char_characters SET money_dirty=" . $newmoney . " WHERE id=" . $this->character_id ) === false )
		{
			$err->Error = "The query to update the dirty money in the setDirtyMoneyEx function was found to be invalid for character id: " . $this->character_id . ".";
			return false;
		}
		
		$gm = $this->db->getRowsFor( "SELECT money_dirty FROM char_characters WHERE id=" . $this->character_id );
		$this->money_clean = $gm['money_dirty'];
		
		return true;
	}

	function getDirtyMoney( $query = false )
	{
		if ( $query )
		{
			$rec = $this->db->query( "SELECT money_dirty FROM char_characters WHERE id=". $this->character_id );
			$res = $this->db->fetch_array( $rec );

			$this->money_dirty = $res['money_dirty'];
		}

		return $this->money_dirty;
	}

	/**
	 * Timer setters - we simply 'assume' the timer length
	 * Author: Rogier Pennink.
	 */
	function setEarnTimer( $time = 180 )
	{
		$time = time() + $time;

		if ( $this->db->query( "UPDATE char_timers SET earn_timer=$time WHERE id=". $this->timers_id ) === false )
		{
			$err->Error = "The query to update the earn timer in the setEarnTimer() function was found to be invalid for the timers_id " . $this->timers_id . ".";
			return false;
		}

		$this->earn_timer = $time;

		return true;
	}

	function setEarnTimer2( $time = 180 )
	{
		$time = time() + $time;

		if ( $this->db->query( "UPDATE char_timers SET earn_timer2=$time WHERE id=". $this->timers_id ) === false )
			return false;

		$this->earn_timer2 = $time;

		return;
	}
	
	function setAggTimer( $time = 1500 )
	{
		$time = time() + $time;		
		
		if ( $this->db->query( "UPDATE char_timers SET agg_timer=$time WHERE id=". $this->timers_id ) === false )
		{
			$err->Error = "The query to update the agg timer in the setAggTimer() function was found to be invalid for the timers_id " . $this->timers_id . ".";
			return false;
		}	

		$this->agg_timer = $time;

		return true;
	}

	function setAggTimer2( $time = 1500 )
	{
		$time = time() + $time;		
		
		if ( $this->db->query( "UPDATE char_timers SET agg_timer2=$time WHERE id=". $this->timers_id ) === false )
		{
			$err->Error = "The query to update the agg timer2 in the setAggTimer2() function was found to be invalid for the timers_id " . $this->timers_id . ".";
			return false;
		}	

		$this->agg_timer2 = $time;

		return true;
	}

	function setStudyTimer( $time = 900 )
	{
		$time = time() + $time;
		
		if ( $this->db->query( "UPDATE char_timers SET study_timer=$time WHERE id=". $this->timers_id ) === false )
		{
			$err->Error = "The query to update the study timer in the setStudyTimer() function was found to be invalid for the timers_id " . $this->timers_id . ".";
			return false;
		}

		$this->study_timer = $time;

		return true;
	}

	function setAggPro( $time = 6000 )
	{
		$time = time() + $time;

		if ( $this->db->query( "UPDATE char_timers SET agg_pro=$time WHERE id=". $this->timers_id ) === false )
		{
			$err->Error = "The query to update the agg pro in the setAggPro() function was found to be invalid for the timers_id " . $this->timers_id . ".";
			return false;
		}

		$this->agg_pro = $time;

		return true;
	}

	function setMurderTimer( $time )	// Time in seconds
	{
		if ( $this->db->query( "UPDATE char_timers SET murder_timer=". ( time() + $time ) ." WHERE id=". $this->timers_id ) === false )
		{
			$err->Error = "The query to update the murder timer in the setMurderTimer() function was found to be invalid for the timers_id " . $this->timers_id . ".";
			return false;
		}

		$this->murder_timer = time() + $time;

		return true;
	}

	function setMurderPro( $time = 10800)
	{
		$time = time() + $time;

		if ( $this->db->query( "UPDATE char_timers SET kill_pro=$time WHERE id=". $this->timers_id ) === false )
		{
			$err->Error = "The query to update the murder protection timer in the setMurderPro() function was found to be invalid for the timers_id " . $this->timers_id . ".";
			return false;
		}
		
		$this->kill_pro = $time;

		return true;
	}

	function setBarTimer()
	{
		$time = time() + 15;
		if ( $this->db->query( "UPDATE char_timers SET bar_timer=$time WHERE id=" . $this->timers_id ) === false )
		{
			$err->Error = "The query to update the bar in the setBarTimer() function was found to be invalid for the timers_id " . $this->timers_id . ".";
			return false;
		}

		$this->bar_timer = $time;

		return true;
	}

	function getOnlineTime( $query = false )
	{
		if ( $query )
		{
			$rec = $this->db->query( "SELECT online_timer FROM char_timers WHERE id=". $this->timers_id );
			$res = $this->db->fetch_array( $rec );

			$this->online_timer = $res['online_timer'];
		}

		return $this->online_timer;
	}

	function isOnline()
	{
		return ( time() - $this->getOnlineTime() > 600 ? false : true );
	}

	function isAlive()
	{
		return ( ( $this->health > 0 ) ? true : false );
	}

	/** 
	 * The getTierExp function returns the number you get when you add all stats together. This is the experience scale
	 * from which tier levels are deducted.
	 */
	function getTierExp()
	{
		return ( $this->strength + $this->defense + $this->intellect + $this->cunning );
	}

	function getExpToNextTier()
	{
		switch ( $this->getTier() )
		{
			case 0: return 10000 - $this->getTierExp();
			case 1: return 30000 - $this->getTierExp();
			case 2: return 60000 - $this->getTierExp();
			case 3: return 100000 - $this->getTierExp();
			case 4: return 150000 - $this->getTierExp();
			case 5: return 210000 - $this->getTierExp();
			case 6: return 280000 - $this->getTierExp();
			case 7: return 360000 - $this->getTierExp();
			case 8: return 450000 - $this->getTierExp();
			case 9: return 550000 - $this->getTierExp();
		}

		return 660000 - $this->getTierExp();
	}

	/**
	 * The getTier function returns the current tier of this character.
	 */
	function getTier()
	{
		$exp = $this->getTierExp();
		if ( $exp < 10000 ) return 0;
		if ( $exp < 30000 ) return 1;
		if ( $exp < 60000 ) return 2;
		if ( $exp < 100000 ) return 3; 
		if ( $exp < 150000 ) return 4;
		if ( $exp < 210000 ) return 5;
		if ( $exp < 280000 ) return 6;
		if ( $exp < 360000 ) return 7;
		if ( $exp < 450000 ) return 8;
		if ( $exp < 550000 ) return 9;

		return 10;
	}

	/**
	 * This function inserts a new piece of armor into the headslot of a character, and, if necessary, puts
	 * the old piece of armor into the inventory.
	 * @param new_armor an object of the Item class indicating the new armor that is to be placed in the head slot.
	 */
	function swapHeadArmour( $new_armor )
	{
		/* Check if this character matches the tier requirement. */
		if ( $this->getTier() < $new_armor->tier )
			return -1;

		/* Put the old item in the inventory if necessary. */
		if ( $this->armor_head > 0 )
			if ( $this->db->query( "INSERT INTO char_inventory SET char_id=". $this->character_id .", item_id=". $this->armor_head ) === false )
				return 0;

		/* Insert the new item. */
		if ( $this->db->query( "UPDATE char_equip SET armor_head=". $new_armor->item_id ." WHERE char_id=". $this->character_id ) === false )
		{
			if ( $this->armor_head > 0 )
				$this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ." AND item_id=". $this->armor_head );
			return 0;
		}

		/* Delete the new item from the backpack. */
		$this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ." AND item_id=". $new_armor->item_id );

		return 1;
	}

	/**
	 * This function inserts a new piece of armor into the chestslot of a character, and, if necessary, puts
	 * the old piece of armor into the inventory.
	 * @param new_armor an object of the Item class indicating the new armor that is to be placed in the chest slot.
	 */
	function swapChestArmour( $new_armor )
	{
		/* Check if this character matches the tier requirement. */
		if ( $this->getTier() < $new_armor->tier )
			return -1;

		/* Put the old item in the inventory if necessary. */
		if ( $this->armor_chest > 0 )
			if ( $this->db->query( "INSERT INTO char_inventory SET char_id=". $this->character_id .", item_id=". $this->armor_chest ) === false )
				return 0;

		/* Insert the new item. */
		if ( $this->db->query( "UPDATE char_equip SET armor_chest=". $new_armor->item_id ." WHERE char_id=". $this->character_id ) === false )
		{
			if ( $this->armor_chest > 0 )
				$this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ." AND item_id=". $this->armor_chest );
			return 0;
		}

		/* Delete the new item from the backpack. */
		$this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ." AND item_id=". $new_armor->item_id );

		return 1;
	}

	/**
	 * This function inserts a new piece of armor into the legslot of a character, and, if necessary, puts
	 * the old piece of armor into the inventory.
	 * @param new_armor an object of the Item class indicating the new armor that is to be placed in the leg slot.
	 */
	function swapLegArmour( $new_armor )
	{
		/* Check if this character matches the tier requirement. */
		if ( $this->getTier() < $new_armor->tier )
			return -1;

		/* Put the old item in the inventory if necessary. */
		if ( $this->armor_legs > 0 )
			if ( $this->db->query( "INSERT INTO char_inventory SET char_id=". $this->character_id .", item_id=". $this->armor_legs ) === false )
				return 0;

		/* Insert the new item. */
		if ( $this->db->query( "UPDATE char_equip SET armor_legs=". $new_armor->item_id ." WHERE char_id=". $this->character_id ) === false )
		{
			if ( $this->armor_legs > 0 )
				$this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ." AND item_id=". $this->armor_legs );
			return 0;
		}

		/* Delete the new item from the backpack. */
		$this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ." AND item_id=". $new_armor->item_id );

		return 1;
	}

	/**
	 * This function inserts a new piece of armor into the footslot of a character, and, if necessary, puts
	 * the old piece of armor into the inventory.
	 * @param new_armor an object of the Item class indicating the new armor that is to be placed in the foot slot.
	 */
	function swapFootArmour( $new_armor )
	{
		/* Check if this character matches the tier requirement. */
		if ( $this->getTier() < $new_armor->tier )
			return -1;

		/* Put the old item in the inventory if necessary. */
		if ( $this->armor_feet > 0 )
			if ( $this->db->query( "INSERT INTO char_inventory SET char_id=". $this->character_id .", item_id=". $this->armor_feet ) === false )
				return 0;

		/* Insert the new item. */
		if ( $this->db->query( "UPDATE char_equip SET armor_feet=". $new_armor->item_id ." WHERE char_id=". $this->character_id ) === false )
		{
			if ( $this->armor_feet > 0 )
				$this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ." AND item_id=". $this->armor_feet );
			return 0;
		}

		/* Delete the new item from the backpack. */
		$this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ." AND item_id=". $new_armor->item_id );

		return 1;
	}

	function swapMainWeapon( $new )
	{
		/* Check if this character matches the tier requirement. */
		if ( $this->getTier() < $new->tier )
			return -1;

		/* Put the old item in the inventory if necessary. */
		if ( $this->main_weapon > 0 )
			if ( $this->db->query( "INSERT INTO char_inventory SET char_id=". $this->character_id .", item_id=". $this->main_weapon ) === false )
				return 0;

		/* Insert the new item. */
		if ( $this->db->query( "UPDATE char_equip SET main_weapon=". $new->item_id ." WHERE char_id=". $this->character_id ) === false )
		{
			if ( $this->main_weapon > 0 )
				$this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ." AND item_id=". $this->main_weapon );
			return 0;
		}

		/* Delete the new item from the backpack. */
		$this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ." AND item_id=". $new->item_id );

		return 1;
	}

	function swapBag( $new )
	{
		/* Check if this character matches the tier requirement. */
		if ( $this->getTier() < $new->tier )
			return -1;

		/* Put the old item in the inventory if necessary. */
		if ( $this->bag > 0 )
			if ( $this->db->query( "INSERT INTO char_inventory SET char_id=". $this->character_id .", item_id=". $this->bag ) === false )
				return 0;

		/* Insert the new item. */
		if ( $this->db->query( "UPDATE char_equip SET bag=". $new->item_id ." WHERE char_id=". $this->character_id ) === false )
		{
			if ( $this->bag > 0 )
				$this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ." AND item_id=". $this->bag );
			return 0;
		}

		/* Delete the new item from the backpack. */
		$this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ." AND item_id=". $new->item_id );

		return 1;
	}

	/**
	 * The function that will add an event to this player's 
	 * event reports.
	 * Author: Rogier Pennink.
	 */
	function addEvent( $subject, $message )
	{
		$date = date( "Y-m-d H:i:s", time() );
		$subject = $this->db->prepval( $subject );
		$message = $this->db->prepval( $message );

		if ( $this->db->query( "INSERT INTO `events` SET `char_id`=". $this->character_id .", `date`='$date', `subject`=$subject, `message`=$message, `event_new`=1, `event_type`=0" ) === false )
		{
			$err->Error = "The query to add an event through the addEvent function was found to be invalid for the character id " . $this->character_id . ".";
			return false;
		}

		return mysql_insert_id();
	}

	function addLargeEvent( $subject, $message, $death_message = false )
	{
		$date = date( "Y-m-d H:i:s", time() );
		$message = $this->db->prepval( $message );

		if ( $this->db->query( "INSERT INTO `events_large` SET `char_id`=". $this->character_id .", `date`='$date', `subject`=". $this->db->prepval( $subject ) .", `content`=$message, `event_new`=1, `death_message`=".($death_message==true?1:0) ) === false )
		{
			$err->Error = "The query to add a large event through the addLargeEvent function was found to be invalid for the character id " . $this->character_id . ".";
			return false;
		}

		return mysql_insert_id();

	}

	/**
	 * The function to grab charactere quote
	 */
	function getQuote()
	{
		$nar = $this->db->getRowsFor( "SELECT quote FROM char_characters WHERE id=" . $this->character_id );
		
		if ( strlen( $nar['quote'] ) == 0 )
			return false;
		else
			return stripslashes( str_replace( "\n", "<br />", $nar['quote'] ) );
	}

	function hasQuote()
	{
		$ar = $this->db->query( "SELECT quote FROM char_characters WHERE id=". $this->character_id );

		if ( $this->db->getRowCount( $ar ) == 0 )
			return false;
		else
			return true;
	}

	function deleteCharacter()
	{
		$ret = true;

		if ( $this->db->query( "DELETE FROM char_characters WHERE id=". $this->character_id ) === false ) $ret = false;
		if ( $this->db->query( "DELETE FROM char_inventory WHERE char_id=". $this->character_id ) === false ) $ret = false;
		if ( $this->db->query( "DELETE FROM char_degrees WHERE character_id=". $this->character_id ) === false ) $ret = false;
		if ( $this->db->query( "DELETE FROM char_equip WHERE char_id=". $this->character_id ) === false ) $ret = false;
		if ( $this->db->query( "DELETE FROM char_stats WHERE id=". $this->stats_id ) === false ) $ret = false;
		if ( $this->db->query( "DELETE FROM char_timers WHERE id=". $this->timers_id ) === false ) $ret = false;
		if ( $this->db->query( "DELETE FROM char_vehicles WHERE id=". $this->character_id ) === false ) $ret = false;
		if ( $this->db->query( "DELETE FROM `comms` WHERE `to`=". $this->character_id ) === false ) $ret = false;
		if ( $this->db->query( "DELETE FROM events WHERE char_id=". $this->character_id ) === false ) $ret = false;
		if ( $this->db->query( "DELETE FROM events_large WHERE char_id=". $this->character_id ) === false ) $ret = false;
		if ( $this->db->query( "DELETE FROM char_deeds WHERE id=". $this->character_id ) === false ) $ret = false;
		
		if ( !$ret ) $err->Error = "An error has occurred while sending the delete character queries from deleteCharacter.";

		return $ret;
	}

	/**
	 * Add Deed Points
	 * @param deedslot slot Slot to be updated (slot_a, slot_b, or defines, DEED_SOMESLOT).
	 * @param value int Value to be updated (formula: current slot value + $value )
	 * @param override int [bool false to disable] Overrides max rand number (set in config).
	 */
	function addDeedPoint( $deedslot, $value, $override = false )
	{
		if ( $override == false )
		{
			$resulta = $this->db->getRowsFor( "SELECT value FROM settings_general WHERE setting='deedchances'" );
			$rand = mt_rand( 0, $resulta['value'] );
		} else {
			$rand = mt_rand( 0, $override );
		}
		
		if ( ( !$override && $rand != $resulta['value'] ) || ( $override && $rand != $override ) ) return false; 
		
		$this->db->query( "UPDATE char_deeds SET " . $deedslot . "=" . $deedslot . "+" . $value . " WHERE char_id=" . getCharacterID() );
		return true;
	}

	/* For getting deeds off slowly
	 * @param deedslot slot Slot to be updated (slot_a, slot_b, or defines, DEED_SOMESLOT).
	 * @param value int Value to be updated (formula: current slot value - $value )
	 * @param [override int [bool false to disable]] Overrides max rand number (set in config).
	 * @param [gonegative bool] Allows the characters deed points to fall below 0.
	 */
	function reduceDeedPoint( $deedslot, $value, $override = false, $gonegative = false )
	{
		$curval = $this->db->getRowsFor( "SELECT " . $deedslot . " FROM char_deeds WHERE char_id=" . getCharacterID() );

		if ( ( !$curval ) || ( !$gonegative && $curval[$deedslot]-$value < 0 ) ) return false;

		if ( $override === false )
		{
			$resulta = $this->db->getRowsFor( "SELECT value FROM settings_general WHERE setting='deedchances'" );
			$rand = mt_rand( 0, $resulta['value'] );
		} else {
			$rand = mt_rand( 0, $override );
		}
		
		if ( ( !$override && $rand != $resulta['value'] ) || ( $override && $rand != $override ) ) return false; 
		
		$this->db->query( "UPDATE char_deeds SET " . $deedslot . "=" . $deedslot . "-" . $value . " WHERE char_id=" . getCharacterID() );
		return true;
	}

	/* Check if character has a vehicle */
	function hasVehicle()
	{
		$result = $this->db->query( "SELECT * FROM char_vehicles WHERE id=" . $this->character_id );
		if ( $this->db->getRowCount( $result ) == 0 )
			return false;
		else
			return true;
	}

	/* Get vehicle information */
	function getVehicleInfo( $fieldname )
	{
		/* Get Player Vehicle */
		$result = $this->db->query( "SELECT vehicle FROM char_vehicles WHERE id=" . $this->character_id );
		if ( $this->db->getRowCount( $result ) == 0 ) return false;
		$vehicle = $this->db->fetch_array( $result );

		/* Get Vehicle Row from Vehicle Database */
		$newresult = $this->db->query( "SELECT " . $fieldname . " FROM vehicles WHERE id=" . $vehicle['vehicle'] );
		if ( $this->db->getRowCount( $newresult ) == 0 ) return false;
		$info = $this->db->fetch_array( $newresult );

		return $info[$fieldname];
	}

	/* Set vehicle health */
	function setVehicleHealth( $newhealth )
	{
		/* Error check */
		if ( $newhealth > 100 || $newhealth < 0 )
		{
			$err->Error = "The newhealth parameter of the setVehicleHealth function must an integer between 0 and 100.";
			return false;
		}

		/* Get Player Vehicle */
		$result = $this->db->query( "SELECT vehicle FROM char_vehicles WHERE id=" . $this->character_id );
		if ( $this->db->getRowCount( $result ) == 0 ) return false;
		
		/* Update Health */
		$this->db->query( "UPDATE char_vehicles SET health=" . $newhealth . " WHERE id=" . $this->character_id );
		return true;
	}

	/* Get vehicle health */
	function getVehicleHealth()
	{
		/* Get Player Vehicle */
		$result = $this->db->query( "SELECT vehicle FROM char_vehicles WHERE id=" . $this->character_id );
		if ( $this->db->getRowCount( $result ) == 0 ) return false;

		/* Get Health */
		$row = $this->db->getRowsFor( "SELECT health FROM char_vehicles WHERE id=" . $this->character_id );
		return $row['health'];
	}

	/* Check for vehicle mods, bool */
	function hasTuneups()
	{
		$result = $this->db->query( "SELECT * FROM char_tuneups WHERE char_id=" . $this->character_id );
		if ( $this->db->getRowCount( $result ) == 0 )
			return false;
		else
			return true;
	}

	/* Get total vehicle speed + tuneups */
	function getVehicleSpeed()
	{
		$result = $this->db->query( "SELECT * FROM char_vehicles WHERE id=" . $this->character_id );
		if ( $this->db->getRowCount( $result ) == 0 ) return 0;
		
		$charvehicle = $this->db->fetch_array( $result );

		// get vehicle
		$vehresult = $this->db->getRowsFor( "SELECT * FROM vehicles WHERE id=" . $charvehicle['vehicle'] );
		$speed = $vehresult['speed'];

		// get tuneups and to speed
		$tuneupq = $this->db->query( "SELECT * FROM char_tuneups WHERE char_id=". $this->character_id );
		if ( $this->db->getRowCount( $tuneupq ) == 0 ) return $speed;

		while ( $row = $this->db->fetch_array( $tuneupq ) )
		{
			$tuneupqex = $this->db->getRowsFor( "SELECT speed_increase FROM vehicles_tuneups WHERE id=" . $row['tuneup_id'] );
			if ( $this->db->getRowCount( $tuneupqex ) != 0 ) $speed = $speed + $tuneupqex['speed_increase'];
		}

		return $speed;
	}

	/* Check if the players vehicle is in the process of being traded. */
	function isTradingVehicles()
	{
		if ( !$this->hasVehicle() )
			return false;

		$result = $this->db->query( "SELECT trading FROM char_vehicles WHERE id=" . $this->character_id . " AND trading='true'" );

		if ( $this->db->getRowCount( $result ) == 0 ) 
			return false;
		else
			return true;
	}

	/* Turn trade status on vehicles on or off. */
	function setTradeStatus( $true_false_string )
	{
		if ( !$this->hasVehicle() )
			return false;

		$result = $this->db->query( "UPDATE char_vehicles SET trading='" . $true_false_string . "' WHERE id=" . $this->character_id );

		return true;
	}

	/* Get character id */
	function getCharacterID()
	{
		return $this->character_id;
	}

	/* Check for new event requests for this char */
	function hasEventRequest()
	{
		$result = $this->db->query( "SELECT id FROM events_requests WHERE char_id=" . $this->db->prepval( $this->character_id ) . " AND answered=0" );
		
		if ( $this->db->getRowCount( $result ) > 0 ) return true;
		
		return false;
	}

	/* Insert a new, custom event request
	 * @param name string The name of the event.
	 * @param text string (html) The text of the event.
	 * @param handler string (filename) The filename of the handler, for this event.
	 * @note The text for this should include your own input fields and an html submit button.
	 */
	function addEventRequest( $name, $text, $handler_filename )
	{
		if ( !is_string( $name ) || !is_string( $text ) || !is_string( $handler_filename ) )
			return false;

		/* Query for event */
		$result = $this->db->query( "INSERT INTO events_requests (char_id, date, name, html_message, handler_filename, answered) VALUES (" . $this->character_id . ", CURDATE(), " . $this->db->prepval( $name ) . ", " . $this->db->prepval( $text ) . ", " . $this->db->prepval( $handler_filename ) . ", 0)" );

		if ( !$result ) return false;

		return true;
	}

	/* Add a new event request from a pre-made event (template)
	 * @param template_name string The Name of the template in events_templates.
	 * @example
	 *			$char->addEventRequestFromTemplate( "Debug Request" );
	 */
	function addEventRequestFromTemplate( $template_name )
	{
		if ( !is_string( $template_name ) )
			return false;

		/* Query */
		$result = $this->db->query( "SELECT * FROM events_templates WHERE name=" . $this->db->prepval( $template_name ) );

		/* Check for template existance. */
		if ( $this->db->getRowCount( $result ) == 0 ) return false;

		/* Insert new event */
		$template = $this->db->fetch_array( $result );

		$insert = $this->db->query( "INSERT INTO events_requests (char_id, date, name, html_message, handler_filename, answered) VALUES (" . $this->character_id . ", CURDATE(), " . $this->db->prepval( $template["name"] ) . ", " . $this->db->prepval( $template["html_message"] ) . ", " . $this->db->prepval( $template["handler_filename"] ) . ", 0)" );

		/* Return */
		if ( !$insert ) return false;
		return true;
	}

	function addEventRequestFromTemplateID( $template_id )
	{
		if ( !is_numeric( $template_name ) )
			return false;

		/* Query */
		$result = $this->db->query( "SELECT * FROM events_templates WHERE id=" . $this->db->prepval( $template_id ) );

		/* Check for template existance. */
		if ( $this->db->getRowCount( $result ) == 0 ) return false;

		/* Insert new event */
		$template = $this->db->fetch_array( $result );

		$insert = $this->db->query( "INSERT INTO events_requests (char_id, date, name, html_message, handler_filename, answered) VALUES (" . $this->character_id . ", CURDATE(), " . $this->db->prepval( $template["name"] ) . ", " . $this->db->prepval( $template["html_message"] ) . ", " . $this->db->prepval( $template["handler_filename"] ) . ", 0)" );

		/* Return */
		if ( !$insert ) return false;
		return true;
	}

	/* Get event request information
	 * @param event_id int The ID of the event to get info on.
	 * @return bool False if the event does not exist. Array if the event does exist.
	 */
	function getEventRequest( $event_id )
	{
		if ( !is_numeric( $event_id ) )
			return false;

		/* Get query */
		$result = $this->db->query( "SELECT * FROM events_requests WHERE id=" . $this->db->prepval( $event_id ) );
		if ( $this->db->getRowCount( $result ) == 0 ) return false;

		$row = $this->db->fetch_array( $result );
	
		/* Translate int to bool for the field answered */
		if ( $row["answered"] == 0 )
			$answered = false;
		elseif ( $row["answered"] == 1 )
			$answered = true;

		$array = array(
			"ID" => $event_id,
			"CharID" => $char_id,
			"Date" => $row["date"],
			"Name" => $row["name"],
			"Message" => $row["html_message"],
			"Handler" => $row["handler_filename"],
			"Answered" => $answered
		);

		return $array;
	}

	/* Function to get the latest event request's ID 
	 * @example
	 *			 getEventRequest( getLastRequest() ); // Gets the latest event.
	 */
	function getLastRequest()
	{
		$result = $this->db->query( "SELECT id FROM events_requests WHERE answered=0 AND char_id=" . $this->character_id . " ORDER BY date ASC LIMIT 1" );

		if ( $this->db->getRowCount( $result ) == 0 ) return false;

		$row = $this->db->fetch_array( $result );
		
		return $row["id"];
	}

	/* Function to set an event request as 'answered'
	 * @param id int The ID of the event request.
	 * @param del bool Delete the event too, as its probably not much use now?
	 * @example
	 *			 $char->answerEvent( $_POST["id"] );
	 */
	function answerEvent( $id, $del = false )
	{
		if ( !is_numeric( $id ) ) return false;

		$result = $this->db->query( "UPDATE events_requests SET answered=1 WHERE id=" . $id );

		if ( $del ) $this->db->query( "DELETE FROM events_requests WHERE id=" . $id );

		return;
	}

	/* Change character location */
	function setLocation( $location_id )
	{
		if ( !is_numeric( $location_id ) ) return false;

		$result = $this->db->query( "UPDATE char_characters SET location=" . $this->db->prepval( $location_id ) . " WHERE id=" . $this->character_id );

		if ( !$result ) return false;

		return true;
	}

	/* Function to check if player has hotel or not */
	function hasHotelRoom( $location_id )
	{
		$result = $this->db->query( "SELECT * FROM char_hotelrooms WHERE char_id=" . $this->character_id . " AND location_id=" . $location_id );

		if ( $this->db->getRowCount( $result ) > 0 )
			return true;

		return false;
	}

	/* Set and Get bank account balance */
	function setBankAccountBalance( $account_id, $newbalance )
	{
		$res = $this->db->query( "UPDATE bank_accounts SET balance=" . $this->db->prepval( $newbalance ) . " WHERE account_number=" . $db->prepval( $account_id ) );
		
		if ( !$res ) return false;

		return true;
	}

	function getBankAccountBalance( $account_id )
	{
		$res = $this->db->getRowsFor( "SELECT balance FROM bank_accounts WHERE account_number=" . $this->db->prepval( $account_id ) );
		
		if ( $this->db->getRowCount( $res ) == 0 ) return false;

		return $res["balance"];
	}
}
?>