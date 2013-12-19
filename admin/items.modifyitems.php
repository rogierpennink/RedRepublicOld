<?

if ( $_GET['act'] == "delitem" && isset( $_GET['id'] ) && $_GET['id'] != "" )
{
	/* Just delete it... */
	if ( $db->query( "DELETE FROM items WHERE item_id=". $db->prepval( $_GET['id'] ) ) === false )
		echo "<p><strong>Something went wrong while deleting that item!</strong></p>";
	else
	{
		echo "<p><strong>Your item has been successfully deleted!</strong></p>";
		adminLog( $_SESSION['username'], "Deleted an item object (id: " . $_GET['id'] . ")." );
	}
}

// NOT DONE YET!
if ( isset( $_POST['edit'] ) || isset( $_POST['add'] ) ) // Seeing as only super-admins can edit this, we'll assume the information is correct...
{
	$icon = $db->prepval( $_POST['icon'] );
	$name = $db->prepval( $_POST['name'] );
	$quality = $db->prepval( $_POST['quality'] );
	$category = $db->prepval( $_POST['category'] );
	$worth = $db->prepval( $_POST['worth'] );
	$tier = $db->prepval( $_POST['tier'] );
	$bagslots = $db->prepval( $_POST['bagslots'] );
	$armour = $db->prepval( $_POST['armour'] );
	$min_dmg = $db->prepval( $_POST['min_dmg'] );
	$max_dmg = $db->prepval( $_POST['max_dmg'] );
	$speed = $db->prepval( $_POST['speed'] );
	$str = $db->prepval( $_POST['str'] ); $def = $db->prepval( $_POST['def'] );
	$int = $db->prepval( $_POST['int'] ); $cun = $db->prepval( $_POST['cun'] );
	$equipable = ( isset( $_POST['equipable'] ) ) ? 1 : 0;
	$tradable = ( isset( $_POST['tradable'] ) ) ? 1 : 0;

	/* Try to update the DB, depending on whether to add or to edit. */
	if ( isset( $_POST['edit'] ) )
	{
		if ( $db->query( "UPDATE items SET icon=$icon, name=$name, quality=$quality, category=$category, worth=$worth, tier=$tier, bagslots=$bagslots, armor=$armour, min_dmg=$min_dmg, max_dmg=$max_dmg, speed=$speed, strength=$str, defense=$def, intellect=$int, cunning=$cun, equipable=$equipable, tradable=$tradable WHERE item_id=". $db->prepval( $_POST['item_id'] ) ) === false )
		{
			echo "<p><strong>A database error occurred on editing the selected item!</strong></p>";
			$_GET['act'] = "edit";
		}
		else
		{
			echo "<p><strong>You successfully edited the item you selected!</strong></p>";
		}
	}
	elseif( isset( $_POST['add'] ) )
	{
		if ( $db->query( "INSERT INTO items SET icon=$icon, name=$name, quality=$quality, category=$category, worth=$worth, tier=$tier, bagslots=$bagslots, armor=$armour, min_dmg=$min_dmg, max_dmg=$max_dmg, speed=$speed, strength=$str, defense=$def, intellect=$int, cunning=$cun, equipable=$equipable, tradable=$tradable" ) === false )
		{
			echo "<p><strong>A database error occurred on adding the your new item!</strong></p>";
			$_GET['act'] = "add";
		}
		else
		{
			echo "<p><strong>Your item, '". $_POST['name'] ."', has been successfully added!</strong></p>";
			adminLog( $_SESSION["username"], "Added a new item, <i>" . $_POST['name'] . "</i>" );
		}
	}
}

if ( $_GET['act'] == "add" )
{
	$dontshow = true;

	?>
	<div style="width: 60%; margin-left: auto; margin-right: auto;">
		<div class="title" style="padding-left: 10px;">
			Add a new item
		</div>
		<div class="content">
			<form action="itemmanagement.php" method="post">
			<table style="width: 100%;">
			<tr>
				<td style="width: 35%;">Current Icon:</td>
				<td>
					<div style="width: 48px; height: 48px; border: solid 1px #990033;">
					<img id="curricon" src="<?=$rootdir."/images/items/unknown.png";?>" alt="" />
					</div>
				</td>
			</tr>
			<tr>
				<td style="width: 35%;">Choose Icon:</td>
				<td>
					<select class="std_input" name="icon" onchange="getIconImage( this.value, document.getElementById('curricon') );">
					<?
					$iconquery = $db->query( "SELECT * FROM icons ORDER BY url ASC" );
					while ( $icon = $db->fetch_array( $iconquery ) ) { ?>
						<option value="<?=$icon['icon_id'];?>"><?=$icon['url'];?></option>
					<? } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width: 35%;">Choose Name:</td>
				<td>
					<input type="text" class="std_input" style="min-width: 150px;" name="name" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Choose Category:</td>
				<td>
					<select name="category" class="std_input">
					<? for ( $i = 0; $i < 11; $i++ ) { ?>
						<option value="<?=$i;?>"><?=categoryString( $i );?></option>
					<? } ?>
					</select>
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Choose Quality:</td>
				<td>
					<select name="quality" class="std_input">
					<? for ( $i = 0; $i < 5; $i++ ) { ?>
						<option value="<?=$i;?>"><?=qualityString( $i );?></option>
					<? } ?>
					</select>
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Choose Worth:</td>
				<td>
					<input type="text" class="std_input" style="min-width: 150px;" name="worth" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Choose Tier Level:</td>
				<td>
					<input type="text" class="std_input" name="tier" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Choose # Bagslots:</td>
				<td>
					<input type="text" class="std_input" name="bagslots" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Choose Armour:</td>
				<td>
					<input type="text" class="std_input" name="armour" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Choose Damage:</td>
				<td>
					<input type="text" class="std_input" name="min_dmg" size="3" /> - <input type="text" class="std_input" name="max_dmg" size="3" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Choose Speed:</td>
				<td>
					<input type="text" class="std_input" name="speed" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Str/Def/Int/Cun:</td>
				<td>
					<input type="text" class="std_input" name="str" size="3" /> <input type="text" class="std_input" name="def" size="3" /> <input type="text" class="std_input" name="int" size="3" /> <input type="text" class="std_input" name="cun" size="3" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Extra Options:</td>
				<td>
					<input type="checkbox" name="equipable" /> Equipable<br />
					<input type="checkbox" name="tradable" /> Tradable
				</td>
			</tr>

			<tr><td style="height: 10px;">&nbsp;</td></tr>

			<tr>
				<td style="width: 35%;">&nbsp;</td>
				<td><input type="submit" value="Add Item" name="add" class="std_input" /></td>
			</tr>
			</table>			
			</form>
		</div>
	</div>
	<?
}

if ( $_GET['act'] == "edit" && isset( $_GET['id'] ) && $_GET['id'] != "" )
{
	$dontshow = true;

	/* Check if item exists etc. */
	$iquery = $db->query( "SELECT * FROM items WHERE item_id=". $db->prepval( $_GET['id'] ) );
	if ( $db->getRowCount( $iquery ) == 0 )
	{
		echo "<p><strong>The item you wish to edit does not exist!</strong></p>";
	}
	else
	{
		$item = new Item( $_GET['id'] );
	?>
	<div style="width: 60%; margin-left: auto; margin-right: auto;">
		<div class="title" style="padding-left: 10px;">
			Edit item: '<?=$item->name;?>'
		</div>
		<div class="content">
			<form action="itemmanagement.php" method="post">
			<table style="width: 100%;">
			<tr>
				<td style="width: 35%;">Current Icon:</td>
				<td>
					<div style="width: 48px; height: 48px; border: solid 1px #990033;">
					<img id="curricon" src="<?=$rootdir."/images/".$item->icon;?>" alt="" />
					</div>
				</td>
			</tr>
			<tr>
				<td style="width: 35%;">Choose Icon:</td>
				<td>
					<select class="std_input" name="icon" onchange="getIconImage( this.value, document.getElementById('curricon') );">
					<?
					$iconquery = $db->query( "SELECT * FROM icons ORDER BY url ASC" );
					while ( $icon = $db->fetch_array( $iconquery ) ) { ?>
						<option value="<?=$icon['icon_id'];?>" <?=($item->icon==$icon['url'])?"selected='true'":"";?>><?=$icon['url'];?></option>
					<? } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width: 35%;">Edit Name:</td>
				<td>
					<input type="text" class="std_input" style="min-width: 150px;" name="name" value="<?=$item->name;?>" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Edit Category:</td>
				<td>
					<select name="category" class="std_input">
					<? for ( $i = 0; $i < 11; $i++ ) { ?>
						<option value="<?=$i;?>" <?=($i == $item->category)?"selected='true'":"";?>><?=categoryString( $i );?></option>
					<? } ?>
					</select>
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Edit Quality:</td>
				<td>
					<select name="quality" class="std_input">
					<? for ( $i = 0; $i < 5; $i++ ) { ?>
						<option value="<?=$i;?>" <?=($i == $item->quality)?"selected='true'":"";?>><?=qualityString( $i );?></option>
					<? } ?>
					</select>
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Edit Worth:</td>
				<td>
					<input type="text" class="std_input" style="min-width: 150px;" name="worth" value="<?=$item->worth;?>" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Edit Tier Level:</td>
				<td>
					<input type="text" class="std_input" name="tier" value="<?=$item->tier;?>" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Edit # Bagslots:</td>
				<td>
					<input type="text" class="std_input" name="bagslots" value="<?=$item->bagslots;?>" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Edit Armour:</td>
				<td>
					<input type="text" class="std_input" name="armour" value="<?=$item->armor;?>" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Edit Damage:</td>
				<td>
					<input type="text" class="std_input" name="min_dmg" size="3" value="<?=$item->min_dmg;?>" /> - <input type="text" class="std_input" name="max_dmg" size="3" value="<?=$item->max_dmg;?>" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Edit Speed:</td>
				<td>
					<input type="text" class="std_input" name="speed" value="<?=$item->speed;?>" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Str/Def/Int/Cun:</td>
				<td>
					<input type="text" class="std_input" name="str" size="3" value="<?=$item->strength;?>" /> <input type="text" class="std_input" name="def" size="3" value="<?=$item->defense;?>" /> <input type="text" class="std_input" name="int" size="3" value="<?=$item->intellect;?>" /> <input type="text" class="std_input" name="cun" size="3" value="<?=$item->cunning;?>" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Extra Options:</td>
				<td>
					<input type="checkbox" name="equipable" <?=($item->equipable==1)?"checked='true'":"";?> /> Equipable<br />
					<input type="checkbox" name="tradable" <?=($item->tradable==1)?"checked='true'":"";?> /> Tradable
				</td>
			</tr>

			<tr><td style="height: 10px;">&nbsp;</td></tr>

			<tr>
				<td style="width: 35%;">&nbsp;</td>
				<td><input type="submit" value="Edit" name="edit" class="std_input" /></td>
			</tr>
			</table>
			<input type="hidden" name="item_id" value="<?=$item->item_id;?>" />
			</form>
		</div>
	</div>
	<?
	}
}
?>