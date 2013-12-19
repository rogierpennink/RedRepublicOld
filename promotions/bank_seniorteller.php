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
				$char->setDefense( $char->getDefense() + 30 );
				$char->setIntellect( $char->getIntellect() + 50 );
				$char->setStrength( $char->getStrength() + 20 );

				$message = "The manager is very happy you've come to him! Even if you did harm your colleague's career, you've certainly boosted your own!";
			}
			elseif ( $_POST['promo_option'] ==  2 )
			{
				$char->setCunning( $char->getCunning() + 20 );
				$char->setDefense( $char->getDefense() + 80 );

				$message = "You do nothing. It may be weak, but there's no point in seeking trouble with your colleagues or sucking up to the management.";
			}
			else
			{
				$char->setIntellect( $char->getIntellect() + 20 );
				$char->setCunning( $char->getCunning() + 30 );
				$char->setStrength( $char->getStrength() + 50 );

				$message = "With an intimidating voice, you manage to convince your colleague to give you 40% of his profits. Looks like a nice source of extra income!";
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
			You are now a senior member of the bank's teller staff and this brings a few benefits. The managers no longer look at you as though you were filth, not worthy of any attention, and you've also noticed that some of the junior staff are starting to look at you with respect. The loans business has also become a lot more interesting now that you are a <?=$rank['rank_name'];?>. The bank's management trusts you with larger loans and this means that your clientele is getting more and more interesting and diverse. You now know that you've made an impression here. You're a stayer, and one day, you'll make it to the top!
			</p>
			<p>
			As the management increasingly relies on you, your responsibility increases enormously. You are no longer the freewheeling rookie teller that you used to be and the consequences of your actions are larger and more importantly, yours to deal with. You had a great time with the junior teller staff, but now that you're a senior employee, you notice that one of the junior tellers is asking much more rent than allowed by the bank. You know this money goes into their own pockets. You really don't want to betray your colleagues, but this behaviour can harm the costumer's trust in the bank, and thus influence the bank's profits. What do you do?
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
			<input type="radio" name="promo_option" value="1" style="margin-right: 10px;" /> You discuss the case with one of the bank's manager. You didn't really like that colleague anyway!<br />
			<input type="radio" name="promo_option" value="2" style="margin-right: 10px;" /> Do nothing. These practises aren't any of your business and it doesn't harm you either!<br />
			<input type="radio" name="promo_option" value="3" style="margin-right: 10px;" checked="true"/> You speak to your colleague about getting a share of the profit. Or else...
			</p>
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