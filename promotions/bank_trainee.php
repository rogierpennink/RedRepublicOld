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
				$char->setCunning( $char->getCunning() + 50 );
				$char->setStrength( $char->getStrength() + 30 );
				$char->setDefense( $char->getDefense() + 20 );

				$message = "As your face grows red, you pretend to nod approvingly and put your friend's request on the 'accepted' list.";
			}
			else
			{
				$char->setIntellect( $char->getIntellect() + 50 );
				$char->setCunning( $char->getCunning() + 20 );
				$char->setDefense( $char->getDefense() + 30 );

				$message = "It pains you that your friendship may be over, but you can't allow your friend to get away with this so easily. You decline the request.";
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
			As a bank trainee you are constantly learning new skills that you will increasingly call upon while dealing with the bank's most precious treasure: its costumers. As a newly recruited bank trainee you are starting to help out the bank's teller staff, and you've been allowed to deal with costumer's requests for new bank accounts. This is a responsible job; it is common knowledge that committing identity fraud in the process of creating a new bank account means that your transactions can't be traced back to you. The police is quite eager to hear about this form of crime and as a bank employee you have a duty to find and report these people.
			</p>
			<p>
			A friend of yours heard you've now been employed at the local bank. Unfortunately, your friend has never really seen the use of law, and worse, abiding by it. As his request for a premium bank account is thrown on your desk you notice he has not been honest about his first and last name! If you accept his request you are effectively helping him get away with his future crimes! If you decline, you may risk loosing his friendship, but at least your integrity remains unquestioned. What do you do?
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
			<input type="radio" name="promo_option" value="1" style="margin-right: 10px;" /> Pretend not to have noticed the discrepancy and accept your friend's request!<br />
			<input type="radio" name="promo_option" value="2" style="margin-right: 10px;" checked="true"/> Decline the request! Not helping him commit crimes will help him in the long run!
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