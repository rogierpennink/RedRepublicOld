<?
if ( !isset( $promo ) )
{
	echo "<p><strong>Error:</strong> no promotion information could be found. Contact an administrator.</p>";
}
else
{
	if ( isset ( $_POST['submit_promo'] ) )
	{
		if ( $_POST['promo_option'] != 1 && $_POST['promo_option'] != 2 && $_POST['promo_option'] != 3 )
		{
			$message = "Please select a valid option!";
		}
		else
		{
			/* Depending on choice, update certain stats and set a message... */
			if ( $_POST['promo_option'] == 1 )
			{
				$itemid = 40;
				$char->setCunning( $char->getCunning() + 10 );
				$char->setIntellect( $char->getIntellect() + 40 );
				$char->setDefense( $char->getDefense() + 50 );
			}
			elseif ( $_POST['promo_option'] == 2 )
			{
				$itemid = 39;
				$char->setIntellect( $char->getIntellect() + 20 );
				$char->setCunning( $char->getCunning() + 40 );
				$char->setStrength( $char->getStrength() + 40 );
			}
			else
			{
				$itemid = 41;
				$char->setStrength( $char->getStrength() + 50 );
				$char->setCunning( $char->getCunning() + 20 );
				$char->setDefense( $char->getDefense() + 30 );
			}

			$message = "You've picked yourself a good gun and feel a lot more secure now!";

			/* Insert item into inventory if possible... */
			$bag = new Item( $char->bag );
			if ( $db->getRowCount( $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id ) ) < $bag->bagslots )
			{
				$db->query( "INSERT INTO char_inventory SET char_id=". $char->character_id .", item_id=$itemid" );
			}

			/* Update the promotion... */
			$db->query( "UPDATE char_promos SET status=0, time_taken='". date( "Y-m-d H:i:s" ) ."' WHERE id=". $promo['id'] );	
			
			/* Update the character... */
			$db->query( "UPDATE char_characters SET rank_id=". $promo['next_rank'] ." WHERE id=". $char->character_id );
						
			/* Update bank exp for this player (not through setBankExp, or it will insert a new promo)... */
			$db->query( "UPDATE char_stats SET bank_exp=". ( $char->getBankExp() + 100 ) ." WHERE id=". $char->stats_id );

			$promocontinue = true;
		}
	}
	?>
	<h1><?=$rank['rank_name'];?>!</h1>
	<p>Congratulations! You have climbed the social ladder farther and farther until reaching this milestone. You have been promoted to the rank of <?=$rank['rank_name'];?> and with that, you gained yourself a bit more respect and influence.</p>
	<table style="width: 100%; margin-right: 10px; margin-bottom: 10px;">
	<tr>
		<td style="width: 160px; text-align: left; vertical-align: top;">
			<img src="<?=$rootdir."/images/".( strlen( $rank['rank_image'] ) == 0 ? "cs_avatar150x150.png" : $rank['rank_image'] );?>" alt="<?=$rank['rank_name'];?>" style="margin: 10px; margin-top: 0px;" />
		</td>
		<td style="vertical-align: top;">
			<p>
			You have made it to the top. You are now the president of <?=$business['name'];?> and that naturally means better income and more influence than you could possibly have dreamt of. You are now not only responsible for promoting and motivating the bank's employees, you are also able to fire them as you see fit. Being the Bank President means that you control everything. Everything from the tiniest detail, like the charges for transactions, to settings for the bank's major money-makers, like the minimum percentage that this bank charges on loans.
			</p>
			<p>
			Being the Bank President is a wonderful job, coming with great responsibilities, but there is also a dark side to it. Only last week you fired an employee and he threatened to have some of his mates pay you a visit. Naturally such threats mean nothing to a strong person like yourself, but all the same, it doesn't hurt to be prepared. There are people out there who would like nothing better than to see you 6 feet under and you better be able to defend yourself. As such, becoming Bank President has yielded you the chance of picking yourself a nice gun...
			</p>
			<?
			if ( isset( $message ) )
			{
				?><p style="color: green;"><strong><?=$message;?></strong></p><?
			}
			
			if ( $promocontinue == true )
			{
				?><p><a href="<?=$PHP_SELF;?>">Continue...</a></p><?
			}
			else
			{
			?>
			<form action="<?=$PHP_SELF;?>" method="post">
			<p>
			<?
			$firstitem = new Item( 40 );
			$seconditem = new Item( 39 );
			$thirditem = new Item( 41 );
			?>
			<input type="radio" name="promo_option" value="1"><img id="item40" src="<?=$rootdir;?>/images/<?=$firstitem->icon;?>" alt="" style="border: solid 1px #990033;" /> <?=$firstitem->name;?><br />
			<input type="radio" name="promo_option" value="2" checked="true"/><img id="item39" src="<?=$rootdir;?>/images/<?=$seconditem->icon;?>" alt="" style="border: solid 1px #990033;" /> <?=$seconditem->name;?>
			<input type="radio" name="promo_option" value="3" checked="true"/><img id="item41" src="<?=$rootdir;?>/images/<?=$thirditem->icon;?>" alt="" style="border: solid 1px #990033;" /> <?=$thirditem->name;?>
			</p>
			<script type="text/javascript">itemsHoverOverEffect();</script>
			<p>
			<input type="submit" value="  Submit  " class="std_input" name="submit_promo" />
			</p>
			</form>
			<?
			}
			?>
		</td>
	</tr>
	</table>
	<?
}
?>