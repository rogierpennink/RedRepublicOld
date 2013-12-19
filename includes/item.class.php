<?
/**
 * The item class, on giving it an item name or id it will fetch all information about that item
 * and become an object through which the programmer can update and retrieve item information.
 * Author: Rogier Pennink.
 */

define( 'TRADE_GOODS', 0 );
define( 'CONSUMABLES', 1 );
define( 'HEAD_ARMOUR', 2 );
define( 'CHEST_ARMOUR', 3 );
define( 'LEG_ARMOUR', 4 );
define( 'FOOT_ARMOUR', 5 );
define( 'GUNS', 6 );
define( 'KNIFES', 7 );
define( 'BAGS', 8 );
define( 'RIFLES', 9 );
function qualityString( $quality )
{
	switch( $quality )
	{
		case 0:
			return "Trivial";

		case 1:
			return "Unusual";

		case 2:
			return "Special";

		case 3:
			return "Grand";

		case 4:
			return "Mythic";

		default:
			return "Unknown Quality";
	}
}

function categoryString( $cat)
{
	switch ( $cat )
	{
		case 0: return "Trade Goods"; break;
		case 1: return "Consumables"; break;
		case 2: return "Head Armour"; break;
		case 3: return "Chest Armour"; break;
		case 4: return "Leg Armour"; break;
		case 5: return "Foot Armour"; break;
		case 6: return "Guns";	break;
		case 7: return "Knifes"; break;
		case 8: return "Bags"; break;
		case 9: return "Rifles"; break;	
		case 10: return "Bombs"; break;
	}
}

class Item
{
	/* Variables to be used throughout the class. */
	var $db;
	var $lasterror;

	

	function Item( $item )
	{
		global $db;
		$this->db = $db;

		$q = "";

		if ( is_numeric( $item ) )
		{
			$q = $this->db->query( "SELECT * FROM items WHERE item_id=". $this->db->prepval( $item ) ." LIMIT 1" );
		}
		else
		{
			$q = $this->db->query( "SELECT * FROM items WHERE name=". $this->db->prepval( $item ) ." LIMIT 1" );
		}

		$result = $this->db->fetch_array( $q );
		foreach ( $result as $key => $value )		
			$this->$key = $value;
		
		$q = $this->db->query( "SELECT * FROM icons WHERE icon_id=". $this->db->prepval( $this->icon ) );
		$r = $this->db->fetch_array( $q );
		$this->icon = $r['url'];
	}

	function categoryString()
	{
		switch ( $this->category )
		{
			case 0: return "Trade Goods"; break;
			case 1: return "Consumables"; break;
			case 2: return "Head Armour"; break;
			case 3: return "Chest Armour"; break;
			case 4: return "Leg Armour"; break;
			case 5: return "Foot Armour"; break;
			case 6: return "Guns";	break;
			case 7: return "Knifes"; break;
			case 8: return "Bags"; break;
			case 9: return "Rifles"; break;	
			case 10: return "Bombs"; break;
		}
	}
	
	function qualityString()
	{
		return qualityString( $this->quality );
	}
		
}

?>