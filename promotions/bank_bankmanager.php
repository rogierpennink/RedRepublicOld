<?
if ( !isset( $promo ) )
{
	echo "<p><strong>Error:</strong> no promotion information could be found. Contact an administrator.</p>";
}
else
{
	if ( isset ( $_POST['submit_promo'] ) )
	{
		if ( $_POST['promo_option'] != 1 && $_POST['promo_option'] != 2 )
		{
			$message = "Please select a valid option!";
		}
		else
		{
			/* Depending on choice, update certain stats and set a message... */
			if ( $_POST['promo_option'] == 1 )
			{
				$itemid = 42;
				$char->setCunning( $char->getCunning() + 10 );
				$char->setIntellect( $char->getIntellect() + 40 );
				$char->setDefense( $char->getDefense() + 50 );

				$message = "Within a week, you receive the T-shirt and despite looking a bit pale, it's very acceptable!";
			}
			else
			{
				$itemid = 43;
				$char->setIntellect( $char->getIntellect() + 20 );
				$char->setCunning( $char->getCunning() + 40 );
				$char->setStrength( $char->getStrength() + 40 );

				$message = "The brighter shirt is just what was needed! It looks fabulous on you and probably on the rest of the staff as well!";
			}

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
			You have finally managed to work your way into the Bank's management, congratulations for that! You quickly realise, however, that being part of the management is not quite the dream-job you imagined it to be. You find that salaries have to be paid on time and that you are blamed by everyone for everything. It almost seems like everything you do is wrong. But then again, when you were still part of the bank's teller staff you used to bitch at the management a lot as well. Surely, things'll be better once you've been on the job for a while...
			</p>
			<p>
			With two robberies in a week, things are a little hectic at the moment. On top of that, your tellers are starting to express their concerns about the way things are run at the Bank. It is time, or so the bank president tells you, for people to get to know each other again in an informal setting. He appoints the task of organising a team-building weekend to you. You have found a nice place somewhere along the coast on a long beach with white sand. Everything's arranged, but you feel that all staff ought to be wearing the same T-shirts, to increase the teamspirit. You must choose between these two samples...
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
			$firstitem = new Item( 42 );
			$seconditem = new Item( 43 );
			?>
			<input type="radio" name="promo_option" value="1"><img id="item42" src="<?=$rootdir;?>/images/<?=$firstitem->icon;?>" alt="" style="border: solid 1px #990033;" /> <?=$firstitem->name;?><br />
			<input type="radio" name="promo_option" value="2" checked="true"/><img id="item43" src="<?=$rootdir;?>/images/<?=$seconditem->icon;?>" alt="" style="border: solid 1px #990033;" /> <?=$seconditem->name;?>
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