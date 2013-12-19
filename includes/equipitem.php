<?
$AUTH_LEVEL = 0;
$REDIRECT = false;
$CHARNEEDED = true;
include_once "utility.inc.php";

$char = new Player( getUserID() );

/* If no item ID is given. */
if ( !isset( $_GET['item_id'] ) || $_GET['item_id'] == "" )
{
	echo "The request for equipping an item was unsuccessful. Item ID required.";
	exit;
}

/* If the item doesn't exist. */
if ( $db->getRowCount( $db->query( "SELECT * FROM items WHERE item_id=". $_GET['item_id'] ) ) == 0 )
{
	echo "You cannot equip an item that doesn't exist!";
	exit;
}

/* It does exist, so create an item object from it. */
$item = new Item( $_GET['item_id'] );


/* Check if the player has the item in his/her inventory. */
if ( $db->getRowCount( $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id ." AND item_id=". $item->item_id ) ) == 0 )
{
	echo "You cannot equip items that you haven't got in your inventory!";
	exit;
}

/* Check if the item is equipable. */
if ( $item->equipable == 0 )
{
	echo "The item you're trying to equip is not equipable!";
	exit;
}

/* Depending on the item type, perform certain actions. */
switch ( $item->category )
{
	case HEAD_ARMOUR:
	{
		$result = $char->swapHeadArmour( $item );		
		if ( $result == 1 ) { echo "Your item, " . $item->name .", was equipped successfully!"; }
		elseif ( $result == -1 ) { echo "You do not match the tier requirement on this item!"; }
		else { echo "Something went wrong while equipping your item! Try again or contact an administrator!"; }		
	}
	break;

	case CHEST_ARMOUR:
	{
		$result = $char->swapChestArmour( $item );
		if ( $result == 1 ) { echo "Your item, " . $item->name .", was equipped successfully!"; }
		elseif ( $result == -1 ) { echo "You do not match the tier requirement on this item!"; }
		else { echo "Something went wrong while equipping your item! Try again or contact an administrator!"; }		
	}
	break;

	case LEG_ARMOUR:
	{
		$result = $char->swapLegArmour( $item );
		if ( $result == 1 ) { echo "Your item, " . $item->name .", was equipped successfully!"; }
		elseif ( $result == -1 ) { echo "You do not match the tier requirement on this item!"; }
		else { echo "Something went wrong while equipping your item! Try again or contact an administrator!"; }		
	}
	break;

	case FOOT_ARMOUR:
	{
		$result = $char->swapFootArmour( $item );
		
		if ( $result == 1 ) { echo "Your item, " . $item->name .", was equipped successfully!"; }
		elseif ( $result == -1 ) { echo "You do not match the tier requirement on this item!"; }
		else { echo "Something went wrong while equipping your item! Try again or contact an administrator!"; }		
	}
	break;

	case GUNS: case KNIFES: case RIFLES:
	{
		$result = $char->swapMainWeapon( $item );
		if ( $result == 1 ) { echo "Your item, " . $item->name .", was equipped successfully!"; }
		elseif ( $result == -1 ) { echo "You do not match the tier requirement on this item!"; }
		else { echo "Something went wrong while equipping your item! Try again or contact an administrator!"; }		
	}
	break;	

	case BAGS:
	{
		$result = $char->swapBag( $item );
		if ( $result == 1 ) { echo "Your item, " . $item->name .", was equipped successfully!"; }
		elseif ( $result == -1 ) { echo "You do not match the tier requirement on this item!"; }
		else { echo "Something went wrong while equipping your item! Try again or contact an administrator!"; }		
	}
	break;

	default: echo "Could not equip item. Item category could not be resolved!";
}

?>