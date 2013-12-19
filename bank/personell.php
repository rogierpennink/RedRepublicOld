<?
$nav = "work";
$CHARNEEDED = true;
$AUTH_LEVEL = 0;
$OCCNEEDED = 3;
$REDIRECT = true;

include_once "../includes/utility.inc.php";
$char = new Player( getUserID() );

/* SELECT THE LOCAL BANK. */
$bquery = $db->query( "SELECT * FROM businesses AS b INNER JOIN localcity AS lc ON lc.business_id=b.id WHERE lc.location_id=". $char->homecity_nr ." AND b.business_type=6" );
if ( $db->getRowCount( $bquery ) == 0 )
{
	$_SESSION['error'] = "Unfortunately, there is no bank in your homecity!";
	header( "Location: ../main.php" );
	exit;
}
$business = $db->fetch_array( $bquery );

/* ONLY MANAGERS AND PRESIDENTS ARE ALLOWED HERE... */
if ( $char->rank_nr != 7 && $char->rank_nr != 8 )
{
	$_SESSION['error'] = "Unless you are a manager or higher, you don't belong in the personell management section!";
	header( "Location: index.php" );
	exit;
}

$banksettings = $db->getRowsFor( "SELECT * FROM bank_settings WHERE business_id=". $business['business_id'] );
if ( !$banksettings ) 
{
	$_SESSION['error'] = "Could not retrieve bank settings from database. Management page unavailable.";
	header( "Location: index.php" );
	exit;
}

if ( isset( $_POST['sack'] ) )
{
	if ( $char->rank_nr != 8 )
	{
		$message = "Only Bank Presidents can sack employees!";
	}
	else
	{
		/* Check the to-be-sacked player... */
		$empquery = $db->query ( "SELECT * FROM char_characters INNER JOIN char_ranks ON char_characters.rank_id=char_ranks.id WHERE char_characters.id=". $db->prepval( $_POST['sack_player'] ) );
		$emp = $db->fetch_array( $empquery );
		if ( $db->getRowCount( $empquery ) == 0 )
		{
			$message = "The player you're trying to sack doesn't even exist!";
		}
		elseif ( $emp['occupation_id'] != 3 || $emp['homecity'] != $business['location_id'] )
		{
			$message = "You can't sack people that are not employed at your bank!";
		}
		else
		{
			$sacked = new Player( $emp['account_id'] );
			/* Make a sack event and update player stats... */
			$eventmsg = $char->rank ." ". $char->nickname ." has given you the sack! In an ever changing organisation like he one you used to be part of, relics like yourself just don't fit anymore! You're going to have to look for other jobs now!";
			$sacked->addEvent( "You have been sacked!", $eventmsg );

			/* Update player stats... */
			$sacked->setBankExp( 0 );
			
			/* Update player career... */
			$db->query( "UPDATE char_characters SET rank_id=1 WHERE id=". $sacked->character_id );

			$message = "You sacked ". $sacked->nickname ."! They won't be happy with you!";			
		}
	}
}

if ( isset( $_POST['promote'] ) )
{
	if ( $char->rank_nr != 8 && $char->rank_nr != 7 )
	{
		$message = "Only Bank Presidents and Bank Managers can promote employees!";
	}
	else
	{
		/* Check if there is a promotion for the selected player... */
		$promoquery = $db->query ( "SELECT char_promos.id AS id, rank_name FROM char_promos INNER JOIN char_ranks ON char_promos.next_rank=char_ranks.id WHERE char_id=". $db->prepval( $_POST['promotion_player'] ) ." AND char_promos.occupation_id=3 AND status=1" );
		$charquery = $db->query( "SELECT * FROM char_characters INNER JOIN char_ranks ON char_characters.rank_id=char_ranks.id WHERE char_characters.id=". $db->prepval( $_POST['promotion_player'] ) );
		$employee = $db->fetch_array( $charquery );

		if ( $db->getRowCount( $charquery ) == 0 )
		{
			$message = "The player you're trying to promote doesn't even exist!";
		}
		elseif ( $employee['homecity'] != $business['location_id'] || $employee['occupation_id'] != 3 )
		{
			$message = "You can only promote people that are from your bank!";
		}
		elseif ( $db->getRowCount( $promoquery ) == 0 )
		{
			$message = "You can't promote the person you selected!";
		}
		else
		{
			$promo = $db->fetch_array( $promoquery );
			$player = new Player( $employee['account_id'] );

			/* Update the promotion... */
			$db->query( "UPDATE char_promos SET status=2 WHERE id=". $promo[0] );

			/* Create an event for the player... */
			$eventmsg = "Congratulations, ". $char->rank ." ". $char->nickname ." has seen fit to promote you to the rank of ". $promo['rank_name'] ."! You have set one step forward on the long road to success!";
			$player->addEvent( "Promotion!", $eventmsg );
			
			$message = "You promoted ". $player->nickname ." to the rank of ". $promo['rank_name'] ."!";
		}
	}
}

include_once "../includes/header.php";
?>

<h1><?=$business['name'];?> Personell Management</h1>
<p>Keeping your personell happy is almost as important, if not more important, than keeping your customers happy! Bank managers and the the bank's president can adjust salaries and manage other personell-related issues.</p>

<div style="width: 90%; margin-left: auto; margin-right: auto;">
	<form action="personell.php" method="post">
	<?
	if ( isset( $message ) )
	{
		?>
		<p><strong><?=$message;?></strong></p>
		<?
	}
	?>
	<div class="title" style="padding-left: 10px;">
		Human Resources Management
	</div>
	<div class="content">
		<p><strong>Currently employed:</strong><br />Below is a list of currently employed bank employees. As a manager or higher, you are able to promote and fire employees. All necessary information can be found below</p>
		<div class="title" style="padding-left: 10px;">
			Employees
		</div>
		<div class="content" style="padding: 0px;">
			<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
				<table class="row">
					<tr>
						<td style="width: 30%; padding-left: 5px;"><strong>Name</strong></td>
						<td style="width: 30%;"><strong>Current Rank</strong></td>
						<td style="width: 25%;"><strong>Progression</strong></td>
						<td style="width: 15%;"><strong></strong></td>
					</tr>
				</table>
			</div>
		<?
		/* Query the current employees... */
		$empquery = $db->query( "SELECT * FROM char_characters AS c INNER JOIN char_ranks AS r ON c.rank_id=r.id WHERE r.occupation_id=3 AND c.homecity=". $db->prepval( $business['location_id'] ) );
		$bgcolor = "transparent";
		while ( $employee = $db->fetch_array( $empquery ) )
		{
			$bgcolor = ( $bgcolor == "transparent" ? "#fff686" : "transparent" );
			$player = new Player( $employee['account_id'] );
			?>
			<div class="row" style="background-color: <?=$bgcolor;?>;">
			<table class="row">
				<tr>
					<td style="width: 30%; padding-left: 5px;">
						<? echo $player->firstname ." ". $player->lastname .", nicknamed ". $player->nickname; ?>
					</td>
					<td style="width: 30%;">
						<? echo $player->rank; ?>
					</td>
					<td style="width: 25%;">
						<?
						/* Select the next rank, if there is no next rank, width = 100%. */
						$rquery = $db->query( "SELECT * FROM char_ranks WHERE order_index=". ( $employee['order_index'] + 1 ) ." AND occupation_id=3" );
						$r = $db->fetch_array( $rquery );
						$expdiff = $r['exp_required'] - $employee['exp_required'];
						$width = ( $db->getRowCount( $rquery ) == 0 ? 100 : ( ( $player->getBankExp() - $employee['exp_required'] ) / $expdiff * 100 ) );
						?>
						<table style="width: 100%; max-width: 150px; padding: 0px;" cellspacing="0" cellpadding="0">
						<tr>

						<td style="width: <?=$width;?>%; padding: 3px; background-color: #36505F; color: white; text-align: center;"><?=$width;?>%</td>
						<td style="width: <?=(100-$width);?>%;"></td>
						
						</tr>
						</table>	
					</td>
					<td style="width: 15%;">
						
					</td>
				</tr>
			</table>
			</div>
			<?
		}
		if ( $db->getRowCount( $empquery ) == 0 )
		{
			?>
			<div class="row" style="text-align: center; padding-top: 5px; padding-bottom: 5px;">
				No bank employees could be found!
			</div>
			<?
		}
		?>
		</div>

		<p><strong>Promotions and sacking:</strong><br />Sometimes it is necessary to prune a business from powers that, whether knowingly or not, harm the organisation. At other times, a motivation in the shape of a promotion can be necessary as well! Note that only the Bank's president can sack employees.</p>

		<div class="title" style="padding-left: 10px;">
			Promote/sack employees
		</div>
		<div class="content">
			<table>
			<tr>
				<td style="width: 100px;"><strong>Promotions:</strong>
				<td>
					<select class="std_input" name="promotion_player">
						<option value="0">Select an employee</option>
						<?
						$pquery = $db->query( "SELECT * FROM char_characters AS c INNER JOIN char_promos AS p ON p.char_id=c.id WHERE p.occupation_id=3 AND p.status=1 AND c.homecity=". $business['homecity'] );						
						while ( $promo = $db->fetch_array( $pquery ) )
						{
							?><option value="<?=$promo['char_id'];?>"><?=$promo['nickname'];?></option><?
						}
						?>
					</select>
					<input type="submit" value=" Promote " name="promote" class="std_input" style="margin-left: 7px;" />
				</td>
			</tr>
			<tr>
				<td style="width: 100px;"><strong>Sacking:</strong>
				<td>
					<select class="std_input" name="sack_player">
						<option value="0">Select an employee</option>
						<?
						mysql_data_seek( $empquery, 0 );
						while ( $promo = $db->fetch_array( $empquery ) )
						{
							?><option value="<?=$promo[0];?>"><?=$promo['nickname'];?></option><?
						}
						?>
					</select>
					<input type="submit" value="  Sack  " name="sack" class="std_input" style="margin-left: 7px;" />
				</td>
			</tr>
			</table>
		</div>



	</div>

	</form>
</div>

<?
include_once "../includes/footer.php";
?>