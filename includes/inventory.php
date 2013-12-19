<?
$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = false;
include_once "utility.inc.php";

$char = new Player( getUserID() );

$it = getFromInventory(); $num = count( $it );	
?>

<div class="title">
	<img src="<?=$rootdir;?>/images/icon_close.gif" alt="close" title="Close Inventory" style="float: right; margin-left: 3px; cursor: pointer;" onclick="closeInventory();" />
	<img src="<?=$rootdir;?>/images/icon_inventory.gif" alt="" style="margin-left: 2px; margin-right: 5px;" />Inventory Listing
</div>
<div class="content" style="text-align: center; overflow: auto;">

	<?
	$q = $db->query( "SELECT * FROM char_equip INNER JOIN items ON char_equip.bag = items.item_id WHERE char_id=". $char->character_id );
	$r = $db->fetch_array( $q );

	$width = ( $r['bagslots'] >= 5 ) ? 5 : $r['bagslots']; 
	$width *= 52;			
	$rows = ceil( $r['bagslots'] / 5 );
	$columns = ( $r['bagslots'] >= 5 ) ? 5 : $r['bagslots'];
	?>

	<h1>Your Inventory</h1>
	<p>This is your inventory, containing all items you're currently wearing in your bag.<br />You currently have a <?=$r['name'];?> equipped to hold your items.<br />You have <?=$num;?> out of <?=$r['bagslots'];?> slots filled!</p>

	<? if ( isset( $_GET['invmessage'] ) && $_GET['invmessage'] != "" ) {
		echo "<p id=\"invmessage\" style=\"font-weight: bold;\">". $_GET['invmessage'] ."</p>";
	} ?>

	<div style="margin-left: auto; margin-right: auto; width: <?=$width;?>px;">
		<table style="width: 100%;" cellspacing="1">
		<?
		for ( $i = 0; $i < $rows; $i++ )
		{
		echo "<tr>\n";
			for ( $j = 0; $j < $columns; $j++ )
			{
				echo "<td style='border: solid 1px #990033; width: 50px;'>\n";
				
				if ( $it[$i * $columns + $j]['equipable'] == 1 ) echo "<a href=\"javascript: equip_item( ". $it[$i * $columns + $j]['id'] ." );\">";
				if ( !isset( $it[$i * $columns + $j] ) ) echo "<img src='". $rootdir ."/images/items/empty_item.png' alt='' />";
				else echo "<img id='item". $it[$i * $columns + $j]['id'] ."' src='". $rootdir ."/images/". $it[$i * $columns + $j]['icon'] ."' alt='' style='border: none;' />";	
				if ( $it[$i * $columns + $j]['equipable'] == 1 ) echo "</a>";
				
				echo "</td>\n";
			}
		echo "</tr>\n";
		}
		?>
		</table>				
	</div>
	
</div>