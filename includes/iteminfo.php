<?
include_once "utility.inc.php";

// Construct an item id
$item_id = ( !isset( $_GET['item_id'] ) || $_GET['item_id'] == "" ) ? 0 : $_GET['item_id'];

// Query database for item
$q = $db->query( "SELECT * FROM items WHERE item_id=". $db->prepval( $item_id ) );

$sucval = "success";

if ( $db->getRowCount( $q ) == 0 )
{
	$sucval = "error";
	$item_id = "This item does not exist in the database.";
}

// Fetch data into array
$r = $db->fetch_array( $q );

if ( !isset( $item_id ) ) $item_id = $r['item_id'];

$category = "";
switch ( $r['category'] )
{
	case 0: $category = "Trade Goods"; break;
	case 1: $category = "Consumables"; break;
	case 2: $category = "Head Armour"; break;
	case 3: $category = "Chest Armour"; break;
	case 4: $category = "Leg Armour"; break;
	case 5: $category = "Foot Armour"; break;
	case 6: $category = "Guns";	break;
	case 7: $category = "Knifes"; break;
	case 8: $category = "Bags"; break;
	case 9: $category = "Rifles"; break;
}

$output =  $sucval . "::";				// 0
$output .= $item_id . "::";				// 1
$output .= $r['name'] . "::";			// 2
$output .= $r['quality'] . "::";		// 3
$output .= $r['tier'] . "::";			// 4
$output .= $r['tradable'] . "::";		// 5
$output .= $r['category'] . "::";		// 6
$output .= $category . "::";			// 7
$output .= $r['equipable'] . "::";		// 8
$output .= $r['worth'] . "::";			// 9
$output .= $r['bagslots'] . "::";		// 10
$output .= $r['armor'] . "::";			// 11
$output .= $r['strength'] . "::";		// 12
$output .= $r['defense'] . "::";		// 13
$output .= $r['intellect'] . "::";		// 14
$output .= $r['cunning'] . "::";		// 15
$output .= $r['min_dmg'] . "::";		// 16
$output .= $r['max_dmg'] . "::";		// 17
$output .= $r['speed'] . "::";			// 18
$output .= ( ( $r['min_dmg'] + $r['max_dmg'] ) / 2 ) * ( 60 / $r['speed'] );	// 19

echo $output;
?>