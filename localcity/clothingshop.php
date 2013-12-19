<?
$nav = "localcity";
$AUTH_LEVEL = 0;
$REDIRECT = true;
$CHARNEEDED = true;

include_once "../includes/utility.inc.php";
$char = new Player( getUserID() );

/* Check if there is a clothing shop in this city at all... If not, redirect. */
$q = $db->query( "SELECT * FROM localcity AS l INNER JOIN businesses AS b ON l.business_id = b.id WHERE b.url='clothingshop.php' AND l.location_id=". $db->prepval( $char->location_nr ) );
if ( $db->getRowCount( $q ) == 0 )
{
	header( "Location: index.php" );
	exit;
}

$business = $db->fetch_array( $q );

/* Check if the items should be updated or not. */
$q = $db->query( "SELECT * FROM clothingshop WHERE business_id=". $db->prepval( $business['business_id'] ) );
$r = $db->fetch_array( $q );

/* Restock every 12 hours. */
if ( time() - $r['reset_timer'] > 43200 )
{
	$str = "";
	$stock = "";

	/* 3 to 6 trivial items. */
	$q = $db->query( "SELECT DISTINCT * FROM items WHERE ( ( category >= 2 AND category < 6 ) OR category=8 ) AND quality=0 ORDER BY RAND() LIMIT " . mt_rand( 3, 6 ) );
	while ( $r = $db->fetch_array( $q ) )
	{
		$str .= $r['item_id']." ";
		$stock .= mt_rand( 3, 8 )." ";
	}

	/* 1 to 2 unusual items. */
	$q = $db->query( "SELECT DISTINCT * FROM items WHERE ( ( category >= 2 AND category < 6 ) OR category=8 ) AND quality=1 ORDER BY RAND() LIMIT " . mt_rand( 1, 2 ) );
	while( $r = $db->fetch_array( $q ) )
	{
		$str .= $r['item_id']." ";
		$stock .= mt_rand( 1, 3 )." ";
	}

	/* 0 or 1 special items. */
	$q = $db->query( "SELECT DISTINCT * FROM items WHERE ( ( category >= 2 AND category < 6 ) OR category=8 ) AND quality=2 ORDER BY RAND() LIMIT " . mt_rand( 0, 1 ) );
	while( $r = $db->fetch_array( $q ) )
	{
		$str .= $r['item_id']." ";
		$stock .= "1 ";
	}

	/* Update database. */
	$db->query( "UPDATE clothingshop SET items='". trim( $str ) ."', stock='". trim( $stock ) ."', reset_timer=".time()." WHERE business_id=". $business['business_id'] );

	/* Redo the items query. */
	$q = $db->query( "SELECT * FROM clothingshop WHERE business_id=". $db->prepval( $business['business_id'] ) );
	$r = $db->fetch_array( $q );	
}

/* Retrieve the current items for this shop. */
$items = explode( " ", $r['items'] );
$stock = explode( " ", $r['stock'] );

/* Check if an action should be taken... */
if ( isset( $_GET['act'] ) && $_GET['act'] != "" )
{
	if ( $_GET['act'] == "purchase" )
	{
		/* Check if the item exists... */
		if ( $db->getRowCount( $db->query( "SELECT * FROM items WHERE item_id=". $db->prepval( $_GET['id'] ) ) ) == 0 )
		{
			$_SESSION['message'] = "You have uttered the wish to buy a non-existent item... Are you mad?!";
		}
		else
		{
			/* Check if the item is in the shop and in stock. */
			$inshop = false;
			$instock = false;
			for ( $i = 0; $i < count( $items ); $i++ )
			{
				if ( $items[$i] == $_GET['id'] )
				{
					$inshop = true;
					if ( $stock[$i] > 0 ) $instock = true;
				}
			}

			if ( $inshop == false )
			{
				$_SESSION['message'] = "You have tried to buy an item that this shop doesn't sell! Try somewhere else perhaps?";
			}
			elseif ( $instock == false )
			{
				$_SESSION['message'] = "Unfortunately, the clothing shop no longer has that item in stock!";
			}
			else
			{
				$item = new Item( $_GET['id'] );

				/* Check if there is bagspace left. */
				$inventory_used = $db->getRowCount( $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id ) );
				$equipq = $db->query( "SELECT * FROM char_equip INNER JOIN items ON char_equip.bag = items.item_id WHERE char_equip.char_id=". $char->character_id );
				$equipr = $db->fetch_array( $equipq );

				if ( $inventory_used >= $equipr['bagslots'] )
				{
					$_SESSION['message'] = "Your bag is full, you cannot purchase any other items.";
				}
				elseif ( $char->getCleanMoney() < $item->worth )
				{
					$_SESSION['message'] = "You do not have enough money to purchase that item!";
				}
				else
				{
					$char->setCleanMoney( $char->getCleanMoney() - $item->worth );
					$db->query( "INSERT INTO char_inventory SET char_id=". $char->character_id .", item_id=". $item->item_id );
					$_SESSION['message'] = "You purchased a '". $item->name ."' for $". $item->worth .".";

					/* Update the balance of this company */					
					$db->query( "UPDATE bank_accounts SET `balance`=`balance`+". $item->worth ." WHERE account_number=". $business['bank_id'] );

					/* Update the stocks of this company. */
					$stk = "";
					for ( $k = 0; $k < count( $items ); $k++ )
					{
						if ( $items[$k] == $item->item_id )
							$stk .= ($stock[$k] - 1 )." ";
						else
							$stk .= $stock[$k]." ";
					}
					$db->query( "UPDATE clothingshop SET `stock`='". trim( $stk ) ."' WHERE business_id=". $business['business_id'] );

					/* Redo the items query. */
					$q = $db->query( "SELECT * FROM clothingshop WHERE business_id=". $db->prepval( $business['business_id'] ) );
					$r = $db->fetch_array( $q );
					
					/* Retrieve the current items for this shop. */
					$items = explode( " ", $r['items'] );
					$stock = explode( " ", $r['stock'] );
				}
			}
		}
	}

	if ( $_GET['act'] == "sell" )
	{
		/* Check if the item exists... */
		if ( $db->getRowCount( $db->query( "SELECT * FROM items WHERE item_id=". $db->prepval( $_GET['id'] ) ) ) == 0 )
		{
			$_SESSION['message'] = "You have to sell a non-existent item... Did you really think the shopkeeper'd take that deal?!";
		}
		else
		{
			/* Check if the player has the item in question. */
			$item = new Item( $_GET['id'] );

			if ( $db->getRowCount( $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id ." AND item_id=". $item->item_id ) ) == 0 )
			{
				$_SESSION['message'] = "How exactly are you going to sell an item if you don't have it in your bag?!";
			}
			else
			{
				$price = mt_rand( ceil( $item->worth * 0.80 ), ceil( $item->worth * 1.20 ) );
				$char->setCleanMoney( $char->getCleanMoney() + $price );
				$db->query( "DELETE FROM char_inventory WHERE char_id=". $char->character_id ." AND item_id=". $item->item_id ." LIMIT 1" );

				if ( $price < $item->worth )
					$_SESSION['message'] = "After some bargaining you managed to sell your ". $item->name ." for a pathetic $$price!";
				elseif ( $price > $item->worth )
					$_SESSION['message'] = "The bargaining really was worthwile; you sold your ". $item->name ." for $$price!";
				else
					$_SESSION['message'] = "You managed to sell your ". $item->name ." for exactly what it's worth: $$price!";

				/* Update the balance of this company */
				$profit = $item->worth - $price;
				$db->query( "UPDATE bank_accounts SET `balance`=`balance`+$profit WHERE account_number=". $business['bank_id'] );
			}
		}
	}
}

/* Include the header file. */
include_once "../includes/header.php";

?>

<h1>The <?=$char->location;?> clothing shop</h1>

<p>Trotting along the streets in the center of <?=$char->location;?> you come across a medium-sized clothing shop. Its windows are covered with posters advertising the latest trends and you join a crowd of shopping women to take a look at this apparent clothing phenomenom. It is quite cool inside and clothes are everywhere. No doubt you'll find something suitable in here!</p>

<?
if ( isset( $_SESSION['message'] ) )
{
	echo "<div style='width: 90%; margin-left: auto; margin-right: auto;'><p><strong>". $_SESSION['message'] ."</strong></p></div>";
	unset( $_SESSION['message'] );
}
$rows = ceil( count( $items ) / 3 );
?>

<div style="width: 90%; margin-left: auto; margin-right: auto;">
	<div class="title" style="padding-left: 10px;">
		Clothes currently for sale
	</div>
	<div class="content" style="padding-bottom: 20px;">
		<table style="width: 100%;">
			
			<?
			for ( $i = 0; $i < $rows; $i++ )
			{
			?>
			<tr>
				<?
				for ( $j = 0; $j < 3; $j++ )
				{				
					if ( ( $i * 3 ) + $j < count( $items ) )
					{
						$item = new Item( $items[( $i * 3 ) + $j] );
						?>
						<td style="width: 33.3%; text-align: center; <? if ( $i < $rows - 1 ) echo "padding-bottom: 20px;"; ?>">
							<a href="<?=$PHP_SELF;?>?act=purchase&id=<?=$item->item_id;?>" style="text-decoration: none;">
							<img id="item<?=$item->item_id;?>" src="<?=$rootdir;?>/images/<?=$item->icon;?>" alt="" style="border: none;" />
							</a>
							<br />
							<a href="<?=$PHP_SELF;?>?act=purchase&id=<?=$item->item_id;?>">
							<?=$item->name;?><br />
							</a>
							$<?=$item->worth;?> - <?=$stock[$i * 3 + $j];?> in stock<br />						
						</td>
						<?
					}
				}			
				?>
			</tr>
			<?
			}
			?>
			
		</table>
	</div>
</div>

<?
$q = $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id );
if ( $db->getRowCount( $q ) > 0 )
{
?>
<div style="width: 50%; margin-left: auto; margin-right: auto;">
	<div class="title" style="padding-left: 10px;">
		Items that you can sell
	</div>
	<div class="content">
		<table style="width: 100%">
		<?
		while ( $r = $db->fetch_array( $q ) )
		{
			$item = new Item( $r['item_id'] );
			?>
			<tr>
			<td style="width: 60px; horizontal-align: center;">
				<a href="<?=$PHP_SELF;?>?act=sell&id=<?=$item->item_id;?>" style="text-decoration: none;"><img style="border: none;" id="item<?=$r['item_id'];?>" src="<?=$rootdir;?>/images/<?=$item->icon;?>" alt="" /></a>
			</td>
			<td>
				<a href="<?=$PHP_SELF;?>?act=sell&id=<?=$item->item_id;?>"><?=$item->name;?> - worth roughly $<?=$item->worth;?></a>
			</td>
			</tr>
			<?
		}
		?>
		</table>
	</div>
</div>
<?
}

include_once "../includes/footer.php";
?>