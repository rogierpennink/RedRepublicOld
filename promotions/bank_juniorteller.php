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
				$char->setCunning( $char->getCunning() + 30 );
				$char->setStrength( $char->getStrength() + 50 );
				$char->setDefense( $char->getDefense() + 20 );

				$message = "Without hesitation, you accept your friend's loan request. Yes, you might loose your job, but what is that compared to being the next Bill Gates?";
			}
			else
			{
				$char->setIntellect( $char->getIntellect() + 30 );
				$char->setCunning( $char->getCunning() + 20 );
				$char->setDefense( $char->getDefense() + 50 );

				$message = "Your friend is very disappointed with you... Though you've shown integrity, that partnership is probably out of the question now.";
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
			As a junior teller you are now a full employee of the local bank. Though you still have much to learn, your superiors have expressed confidence in you - confidence that you will be able to represent the bank in costumer relations. Being a new member of the bank's teller staff, you have also been allowed to grant loans to people. This is a very delicate task, as loans are a huge risk to the bank. If dealt with correctly, loans generate a very good profit. However, people are known to not always be as honest as they look and it happens that loans aren't paid back. It is your duty to keep an eye out for these people; any mistakes on your side and you might make the bank loose profits!
			</p>
			<p>
			There's this guy that you still know from high school. You used to spend hours with him, designing grand business plans and planning to become the world's next software tycoon. Your plans didn't turn out to be that realistic in the end, so you ended up here. Your friend never abandoned those plans, however, and he's actually got very viable plans for the future! He comes to you, asking for a start-up loan that is way larger than loans that the bank usually grants. You ought to decline his request, but your friend has indicated that you could be his partner which means that accepting his request will benefit you as well! What do you do?
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
			<input type="radio" name="promo_option" value="1" style="margin-right: 10px;" /> You can't resist the temptation. You accept the loan request - after all, you know he can be trusted!<br />
			<input type="radio" name="promo_option" value="2" style="margin-right: 10px;" checked="true"/> With an aching heart, you decline the request. You can't risk loosing your current job.
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