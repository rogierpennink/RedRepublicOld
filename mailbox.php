<?
$CHARNEEDED = true;
$REDIRECT = true;
$AUTH_LEVEL = 0;
include_once "includes/utility.inc.php";
$char = new Player( getUserID() );
$_SESSION['ingame'] = true;

if ( $_GET['act'] == "del" )
{
	if ( !is_numeric( $_GET['id'] ) )
	{
		$_SESSION['message'] = "Please try to use a numeric ID, we don't appreciate the use of exploits.";
	}
	else
	{
		/* Check if the mail exists. */
		if ( $db->getRowCount( $db->query( "SELECT * FROM char_mailbox WHERE mail_id=". $db->prepval( $_GET['id'] ) ." AND has_arrived=1 AND char_id=". $char->character_id ) ) == 0 )
		{
			$_SESSION['message'] = "You can't delete that mail - it isn't in your mailbox!";
		}
		else
		{
			/* Delete the mail. */
			$db->query( "DELETE FROM char_mailbox WHERE mail_id=". $db->prepval( $_GET['id'] ) );

			$_SESSION['message'] = "Your mail has been deleted!";
		}
	}
}

if ( $_GET['act'] == "take" )
{
	if ( !is_numeric( $_GET['id'] ) )
	{
		$_SESSION['message'] = "Please try to use a numeric ID, we don't appreciate the use of exploits.";
	}
	else
	{
		/* Check if the mail exists. */
		$q = $db->query( "SELECT * FROM char_mailbox WHERE mail_id=". $db->prepval( $_GET['id'] ) ." AND has_arrived=1 AND char_id=". $char->character_id );		
		if ( $db->getRowCount( $q ) == 0 )
		{
			$_SESSION['message'] = "You can't take anything from this mail - it isn't in your mailbox!";
		}
		else
		{
			$r = $db->fetch_array( $q );

			if ( $r['item_id'] == 0 && $r['money'] == 0 )
			{
				$_SESSION['message'] = "This mail doesn't contain anything you can take!";
			}
			elseif( $r['item_id'] == 0 )
			{
				/* Update money, and delete the mail. */
				$char->setCleanMoney( $char->getCleanMoney() + $r['money'] );
				$db->query( "DELETE FROM char_mailbox WHERE mail_id=". $db->prepval( $_GET['id'] ) );
				$_SESSION['message'] = "You took $". $r['money'] ." from your mailbox!";
			}
			elseif ( $r['item_id'] > 0 )
			{
				/* Check if there is enough space in the inventory. */
				$bag = new Item( $char->bag );
				if ( $db->getRowCount( $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id ) ) >= $bag->bagslots )
				{
					$_SESSION['message'] = "You don't have enough space in your inventory to take this item from your mailbox!";
				}
				else
				{
					$item = new Item( $r['item_id'] );
					$db->query( "INSERT INTO char_inventory SET char_id=". $char->character_id .", item_id=". $r['item_id'] );
					$db->query( "DELETE FROM char_mailbox WHERE mail_id=". $r['mail_id'] );
					$_SESSION['message'] = "You collected a ". $item->name ." from your mailbox and put it into your bag!";
				}
			}
		}
	}
}

if ( $_GET['act'] == "return" )
{
	if ( !is_numeric( $_GET['id'] ) )
	{
		$_SESSION['message'] = "Please try to use a numeric ID, we don't appreciate the use of exploits.";
	}
	else
	{
		/* Check if the mail exists. */
		$q = $db->query( "SELECT * FROM char_mailbox WHERE mail_id=". $db->prepval( $_GET['id'] ) ." AND has_arrived=1 AND char_id=". $char->character_id );		
		if ( $db->getRowCount( $q ) == 0 )
		{
			$_SESSION['message'] = "You can't return a mail that is not inside your mailbox!";
		}
		else
		{
			$r = $db->fetch_array( $q );			

			if ( $r['sender'] <= 0 )
			{
				$_SESSION['message'] = "You can only return mails to actual players!";
			}
			else
			{
				$sender = new Player( $r['sender'], false );
				if ( !$sender->isAlive() )
				{
					$_SESSION['message'] = "The person you are trying to send this mail to is no longer alive!";
				}
				else
				{
					/* Return the mail. */
					$himher = $char->gender == "m" ? "him" : "her";
					$msg = "A mail you sent to ". $char->nickname ." has been returned by $himher!";
					$db->query( "UPDATE char_mailbox SET char_id=". $r['sender'] .", sender=". $char->character_id .", message='$msg', has_arrived=0 WHERE mail_id=". $r['mail_id'] );

					$_SESSION['message'] = "The mail has been returned to ". $sender->nickname ."!";
				}
			}			
		}
	}
}

include_once "includes/header.php";
?>
<h1>Your personal mailbox</h1>
<p>This is your personal mailbox in which you will receive items from businesses and other players. You are also be able to send any items to other players, optionally requiring an amount of money as well. Delivery is usually within the hour, but no guarantees can be made as to the exact time a delivery might take.</p>

<? if ( isset( $_SESSION['message'] ) )
{
	echo "<p><strong>". $_SESSION['message'] ."</strong></p>"; unset( $_SESSION['message'] );
} ?>

<? if ( $_GET['act'] == "send" ) { ?>
<div class="title" style="padding-left: 10px;">
	Send an item or money to another player
</div>
<div class="content" style="padding: 0px;">
<table style="width: 100%;" cellspacing="0">
<?
	/* Get the amount of items in the player's inventory that are tradable. */
	$it = getFromInventory( true); $num = count( $it );
	$rows = ceil( $num / 6 );
	$columns = ( $num >= 6 ) ? 6 : $num;
	for ( $i = 0; $i < $rows; $i++ )
	{
		?><tr><?
		for ( $j = $i * ceil( $num / 6 ); $j < $columns; $j++ )
		{
			?>
			<td style="width: <?=($num>=6)?(100/6):($num/6);?>%; text-align: center; border-bottom: solid 1px #bb6; <?=($j<$columns)?"border-right: solid 1px #bb6;":"";?>">
				<img id="item<?=$it[$i * $columns + $j]['id'];?>" src="<?=$rootdir;?>/images/<?=$it[$i * $columns + $j]['icon'];?>" alt="" />
			</td>
			<?
		}
		?></tr><?
	}
?>
</table>
</div>
<? } else { ?>

<div class="title" style="padding-left: 10px;">
	The contents of your mailbox
</div>
<div class="content" style="padding: 0px;">
	<div class="row" style="background-color: #fff686;">
	<div style="padding: 5px;">
		<input type="button" class="std_input" value="Send Mail" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px; width: auto;" onclick="window.location = 'mailbox.php?act=send';" />
	</div>
	</div>
<?
/* Check for contents in the mailbox that have arrived. */
$q = $db->query( "SELECT * FROM char_mailbox WHERE char_id=". $char->character_id ." AND has_arrived=1" );
if ( $db->getRowCount( $q ) == 0 )
{
	?>
	<div style="padding: 10px;">You currently don't have anything in your mailbox!</div>
	<?
}
else
{	
	$bgcolor = "#fff686";
	while ( $r = $db->fetch_array( $q ) )
	{
		$bgcolor = ( $bgcolor == "transparent" ) ? "#fff686" : "transparent";
		$item = new Item( $r['item_id'] );
		?>
		<div class="row" style="background-color: <?=$bgcolor;?>;">
			<table style="width: 100%;" style="border: solid 1px #bb6;" cellspacing="0">
				<tr>
					<td style="width: 75px; text-align: center;" rowspan="3">
						<a href="mailbox.php?act=take&amp;id=<?=$r['mail_id'];?>" style="text-decoration: none;">
						<?
						/* Try to get the sender. */
						$sender = 0;
						if ( $r['sender'] <= 0 )
						{							
							$sender = $db->fetch_array( $db->query( "SELECT name FROM businesses WHERE businesses.id=". abs( $r['sender'] ) ) );
						}
						else
						{
							$sender = $db->fetch_array( $db->query( "SELECT nickname AS name FROM char_characters WHERE id=". abs( $r['sender'] ) ) );
						}
	
						/* By definition, if no item is sent, it must be money. */
						if ( $r['item_id'] == 0 )
						{								
							echo "<img src=\"$rootdir/images/nav/nav_income.png\" alt=\"Money\" style=\"border: none;\" />";				
						}
						else
						{
							echo "<img id=\"item". $item->item_id ."\" src=\"$rootdir/images/". $item->icon ."\" alt=\"". $item->name ."\" style=\"border: none;\" />";			
						}
						?>
						</a>
					</td>
					<td style="border-left: solid 1px #bb6; padding-left: 5px; font-size: 11.5px;">
						<strong>Received: </strong>
						<?
						if ( $r['item_id'] == 0 )
							echo "You received a gift of $". $r['money'] ." from ". $sender['name'];							
						else
							echo "You received a ". $item->name ." from ". $sender['name'];							
						?>
					</td>
				</tr>
				<tr>
					<td style="border-left: solid 1px #bb6; border-top: solid 1px #bb6; padding-left: 5px;">
						<strong>Message: </strong> <?=stripslashes( $r['message'] );?>
					</td>
				</tr>
				<tr>
					<td style="border-left: solid 1px #bb6; border-top: solid 1px #bb6; padding-left: 5px;">
						<strong>Options:</strong> <a href="mailbox.php?act=take&amp;id=<?=$r['mail_id'];?>">Take from Mailbox</a> <a href="mailbox.php?act=del&amp;id=<?=$r['mail_id'];?>">Delete from Mailbox</a> 
						<? if ( $r['sender'] > 0 ) { ?><a href="mailbox.php?act=return&amp;id=<?=$r['mail_id'];?>">Return to sender</a><? } ?>
					</td>
				</tr>
			</table>
		</div>
		<?
	}
}

}
?>
</div>

<?
include_once "includes/footer.php";
?>