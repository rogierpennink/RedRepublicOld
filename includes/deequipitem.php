<?
$AUTH_LEVEL = 0;
$REDIRECT = false;
$CHARNEEDED = true;
include_once "utility.inc.php";

$char = new Player( getUserID() );

/* If no item ID is given. */
if ( !isset( $_GET['item_id'] ) || $_GET['item_id'] == "" )
{
	echo "The request for de-equipping an item was unsuccessful. Item ID required.";
	exit;
}

/* If the item doesn't exist. */
if ( $db->getRowCount( $db->query( "SELECT * FROM items WHERE item_id=". $_GET['item_id'] ) ) == 0 )
{
	echo "You cannot de-equip an item that doesn't exist!";
	exit;
}

/* It does exist, so create an item object from it. */
$item = new Item( $_GET['item_id'] );
$bag = new Item( $char->bag );


/* Check if the player has the item in his/her equip. */
if ( $item->item_id != $char->bag && $item->item_id != $char->main_weapon && $item->item_id != $char->second_weapon && $item->item_id != $char->armor_feet && $item->item_id != $char->armor_legs && $item->item_id != $char->armor_chest && $item->item_id != $char->armor_head )
{
	echo "You cannot de-equip items that you haven't got equipped!";
	exit;
}

/* Make sure the bag isn't de-equipped. */
if ( $item->item_id == $char->bag )
{
	echo "You cannot de-equip your bag! In order to equip a new bag, click on it in your inventory!";
	exit;
}

/* Check if the player's inventory is full. */
if ( $db->getRowCount( $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id ) ) >= $bag->bagslots )
{
	echo "You cannot de-equip that item! Your inventory is full!";
	exit;
}

/* Depending on the item type, perform certain actions. */
switch ( $item->category )
{
	case HEAD_ARMOUR:
	{
		$update = $db->query( "UPDATE char_equip SET armor_head=0 WHERE char_id=". $char->character_id );
		if ( $update === false ) echo "Something went wrong! Please contact an administrator!";
		else 
		{
			$update == $db->query( "INSERT INTO char_inventory SET char_id=". $char->character_id .", item_id=". $item->item_id );
			if ( $update === false )
			{
				$db->query( "UPDATE char_equip SET armor_head=".$item->item_id." WHERE char_id=".$char->character_id );
				echo "Something went wrong! Please contact an administrator!";
			}
			else
			{
				echo "Your item, " . $item->name .", was de-equipped successfully!";
			}
		}			
	}
	break;

	case CHEST_ARMOUR:
	{
		$update = $db->query( "UPDATE char_equip SET armor_chest=0 WHERE char_id=". $char->character_id );
		if ( $update === false ) echo "Something went wrong! Please contact an administrator!";
		else 
		{
			$update == $db->query( "INSERT INTO char_inventory SET char_id=". $char->character_id .", item_id=". $item->item_id );
			if ( $update === false )
			{
				$db->query( "UPDATE char_equip SET armor_chest=".$item->item_id." WHERE char_id=".$char->character_id );
				echo "Something went wrong! Please contact an administrator!";
			}
			else
			{
				echo "Your item, " . $item->name .", was de-equipped successfully!";
			}
		}			
	}
	break;

	case LEG_ARMOUR:
	{
		$update = $db->query( "UPDATE char_equip SET armor_legs=0 WHERE char_id=". $char->character_id );
		if ( $update === false ) echo "Something went wrong! Please contact an administrator!";
		else 
		{
			$update == $db->query( "INSERT INTO char_inventory SET char_id=". $char->character_id .", item_id=". $item->item_id );
			if ( $update === false )
			{
				$db->query( "UPDATE char_equip SET armor_legs=".$item->item_id." WHERE char_id=".$char->character_id );
				echo "Something went wrong! Please contact an administrator!";
			}
			else
			{
				echo "Your item, " . $item->name .", was de-equipped successfully!";
			}
		}			
	}
	break;

	case FOOT_ARMOUR:
	{
		$update = $db->query( "UPDATE char_equip SET armor_feet=0 WHERE char_id=". $char->character_id );
		if ( $update === false ) echo "Something went wrong! Please contact an administrator!";
		else 
		{
			$update == $db->query( "INSERT INTO char_inventory SET char_id=". $char->character_id .", item_id=". $item->item_id );
			if ( $update === false )
			{
				$db->query( "UPDATE char_equip SET armor_feet=".$item->item_id." WHERE char_id=".$char->character_id );
				echo "Something went wrong! Please contact an administrator!";
			}
			else
			{
				echo "Your item, " . $item->name .", was de-equipped successfully!";
			}
		}			
	}
	break;

	case GUNS: case KNIFES: case RIFLES:
	{
		if ( $char->main_weapon == $item->item_id )
		{
			$update = $db->query( "UPDATE char_equip SET main_weapon=0 WHERE char_id=". $char->character_id );
			if ( $update === false ) echo "Something went wrong! Please contact an administrator!";
			else 
			{
				$update == $db->query( "INSERT INTO char_inventory SET char_id=". $char->character_id .", item_id=". $item->item_id );
				if ( $update === false )
				{
					$db->query( "UPDATE char_equip SET main_weapon=".$item->item_id." WHERE char_id=".$char->character_id );
					echo "Something went wrong! Please contact an administrator!";
				}
				else
				{
					echo "Your item, " . $item->name .", was de-equipped successfully!";
				}
			}
		}			
		else
		{
			$update = $db->query( "UPDATE char_equip SET second_weapon=0 WHERE char_id=". $char->character_id );
			if ( $update === false ) echo "Something went wrong! Please contact an administrator!";
			else 
			{
				$update == $db->query( "INSERT INTO char_inventory SET char_id=". $char->character_id .", item_id=". $item->item_id );
				if ( $update === false )
				{
					$db->query( "UPDATE char_equip SET second_weapon=".$item->item_id." WHERE char_id=".$char->character_id );
					echo "Something went wrong! Please contact an administrator!";
				}
				else
				{
					echo "Your item, " . $item->name .", was de-equipped successfully!";
				}
			}
		}		
	}
	break;	
	
	default: echo "Could not de-equip item. Item category could not be resolved!";
}