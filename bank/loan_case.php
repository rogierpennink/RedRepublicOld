<?
if ( !isset( $validfile ) || $validfile == false ) { exit; }
$loan = $db->fetch_array( $query );
$player = new Player( $loan['char_id'], false );
$pledge = new Item( $loan['pledge'] );

if ( isset( $_POST['warn'] ) )
{
	if ( $char->earn_timer2 > time() )
	{
		$message = "You can't send a warning so quickly! Try again later...";
	}
	elseif ( $loan['num_warnings'] >= 3 )
	{
		$message = "You can't send any more warnings! If the loan has still not been paid pack, report this case to the police!";
	}
	elseif ( $loan['loan_status'] != 1 )
	{
		$message = "This loan is not currently active! You can only send warning in active loan cases!";
	}
	elseif ( time() < $loan['repay_time'] )
	{
		$message = "There is still time for your client to return the money! You can't send a warning yet!";
	}
	elseif ( ceil( $loan['loan_amount'] * ( 1 + $loan['rent'] / 100 ) ) <= $loan['loan_repaid'] )
	{
		$message = "This loan has been fully paid back! You can't send a warning now!";
	}
	else
	{
		/* Make an event message. */
		$evmsg = "WARNING: You have received an official warning from ". $business['name'] ." concerning your loan (#". $loan['loan_id'] ."). You have had time until ". date( TIME_FORMAT, $loan['repay_time'] ) ." to repay your loan, but failed doing so. Your debt currently amounts to a sum of $". ceil( $loan['loan_amount'] * ( 1 + $loan['rent'] / 100 ) - $loan['loan_repaid'] ) .". You have been granted 10 more hours to undo your debt. Failure to comply may lead to the pressing of charges against you!";

		/* Send the message. */
		$player->addEvent( "Official Warning", $evmsg );

		$newtime = time() + 36000;
		$newnotes = $loan['case_notes'] . "\n". date( TIME_FORMAT, time() ) ." - An official warning was issued by ". $char->rank ." ". $char->nickname;

		/* Update the loan. */
		$db->query( "UPDATE bank_loans SET repay_time=$newtime, num_warnings=num_warnings+1, case_notes=". $db->prepval( $newnotes )." WHERE loan_id=". $loan['loan_id'] );
		$loan['repay_time'] = $newtime;
		$loan['num_warnings'] += 1;
		$loan['case_notes'] = $newnotes;

		/* Add to employee's exp. */
		$char->setCunning( $char->getCunning() + 20 );
		$char->setDefense( $char->getDefense() + 30 );
		$char->setBankExp( $char->getBankExp() + 50 );

		/* Set the employee's timer. */
		$char->setEarnTimer2();

		$message = "You sent an official warning to ". $player->nickname .". They were given 10 additional hours to comply with your demands!";
	}
}

if ( isset( $_POST['archive'] ) )
{
	if ( $char->earn_timer2 > time() )
	{
		$message = "You can't archive another loan so quickly! Try again later...";
	}
	elseif ( $loan['loan_status'] == 0 )
	{
		$message = "You can't archive loans that have not yet been approved!";
	}
	elseif ( $loan['loan_repaid'] < ( $loan['loan_amount'] * ( 1 + $loan['rent'] / 100 ) ) )
	{
		$message = "You can't archive loans that have not been fully repaid yet!";
	}
	else
	{
		$notes = $_POST['notes'];
		$notes .= "\n". date( timeDisplay() ) ." - Loan #".$loan['loan_id']." was archived by ". $char->rank ." ". $char->nickname .".";

		/* Update the loan. */
		$db->query( "UPDATE bank_loans SET loan_status=10, employee=0, case_notes=". $db->prepval( $notes ) ." WHERE loan_id=". $loan['loan_id'] );

		$eventmessage = "Your loan has been archived by an employee of ". $business['name'] .", meaning that you are officially out of debt and that you can now request new loans, should the need arrive. For further reference to this particular loan, please use the loan's unique ID: #". $loan['loan_id'] .".";

		/* If there is a pledge, send it to the player's mailbox. */
		if ( $loan['pledge'] > 0 ) 
		{
			$eventmessage .= " Your pledge has been returned to your mailbox.";
			$db->query( "INSERT INTO char_mailbox SET char_id=". $loan['char_id'] .", item_id=". $loan['pledge'] .", money=0, sender=" . ( -1 * $business['business_id'] ) .", message='Hereby we return the pledge that was used in loan #". $loan['loan_id'] ."'" );
		}

		$player->addEvent( 'Loan Archived', $eventmessage );

		/* Add profits to the bank's bank account... */
		$bankprofit = ceil( $banksettings['loan_rate'] / 10000 * $loan['loan_amount'] );
		$db->query( "UPDATE bank_accounts SET balance=balance+$bankprofit WHERE account_number=". $business['bank_id'] );

		$playerprofit = ceil( $loan['rent'] / 100 * $loan['loan_amount'] );
		$playerprofit -= $bankprofit;
		$char->setCleanMoney( $char->getCleanMoney() + $playerprofit );

		/* Set timers and stats... */
		$char->setEarnTimer2();
		$char->setBankExp( $char->getBankExp() + 20 );
		$char->setIntellect( $char->getIntellect() + 20 );
		$char->setDefense( $char->getDefense() + 30 );

		/* Update the trust of the player. */
		if ( mt_rand( 1, 2 ) == 1 )
			$db->query( "UPDATE char_stats SET bank_trust=bank_trust+1 WHERE id=". $player->stats_id );

		$message = "You successfully archived loan #". $loan['loan_id'] ."!";
		if ( $playerprofit > 0 ) $message .= " The extra rent you charged yielded you $". $playerprofit ."!";

		$dontcontinue = true;
	}
}

if ( isset( $_POST['accept'] ) )
{
	if ( getMaxLoanAmount( $player->bank_trust ) + $pledge->worth < $loan['loan_amount'] )
	{
		$message = "You cannot accept a loan that exceeds the maximum amount of money that can be requested by this person!";
	}
	elseif ( $_POST['loanrent'] < $banksettings['loanrate'] || $_POST['loanrent'] > 5000 )
	{
		$message = "The rent must be between ". ( $banksettings['loanrate'] / 100 ) ."% and 50%!";
	}
	else
	{
		/* Update this loan. */
		$time = time() + ( $banksettings['loan_period'] * 86400 );
		$newnotes = $loan['case_notes'] . "\n" . date( TIME_FORMAT, time() ) ." - Loan accepted by ". $char->rank . " ". $char->nickname .".";
		$db->query( "UPDATE bank_loans SET loan_status=1, rent=". ( $_POST['loanrent'] / 100 ) .", case_notes=". $db->prepval( $newnotes ) .", repay_time=$time WHERE loan_id=". $loan['loan_id'] );
		$loan['loan_status'] = 1;
		$loan['rent'] = $_POST['loanrent'] / 100;
		$loan['case_notes'] = $newnotes;

		/* Add to the clean money of the borrower... */
		$db->query( "INSERT INTO char_mailbox SET char_id=". $loan['char_id'] .", item_id=0, money=". $loan['loan_amount'] .", sender=" . ( -1 * $business['business_id'] ) .", message='Hereby we grant you a loan of $". $loan['loan_amount'] ." as specified by loan #". $loan['loan_id'] ."'" );

		/* Send an event to the borrower... */
		$player->addEvent( "Loan Accepted", "Your request for a loan of $". $loan['loan_amount'] ." has been accepted by ". $char->rank ." ". $char->nickname .". They are charging a rent of ". $loan['rent'] ."%, making your total debt an amount of $". ceil( $loan['loan_amount'] * ( 1 + ( $loan['rent'] / 100 ) ) - $loan['loan_repaid'] ) .". In further correspondence, refer to this loan with the unique ID: #". $loan['loan_id'] .". The money has been transferred to you by mail!" );

		/* Set the player's aggtimer2 on 20 minutes... */
		$player->setAggTimer2( 1200 );

		/* Add to experience of the loanguy... */
		$char->setBankExp( $char->getBankExp() + 20 );

		$message = "You have accepted the loan request from ". $player->nickname ."! They have been informed of your action!";
	}
}

if ( isset( $_POST['decline'] ) )
{
	/* Add to the experience of the loanguy... */
	$char->setBankExp( $char->getBankExp() + 20 );

	/* Simply delete the loan request. */
	$db->query( "DELETE FROM bank_loans WHERE loan_id=". $loan['loan_id'] );

	$eventmessage = "Unfortunately, an employee from ". $business['business_id'] .", ". $char->rank ." ". $char->nickname .", has seen fit to decline your request for a loan. For further reference, or if you wish to take this up with the bank, the loan's unique ID was #". $loan['loan_id'] .".";

	/* If necessary, send back the pledge. */
	if ( $loan['pledge'] > 0 ) 
	{
		$eventmessage .= " Your pledge has been returned to your mailbox.";
		$db->query( "INSERT INTO char_mailbox SET char_id=". $loan['char_id'] .", item_id=". $loan['pledge'] .", money=0, sender=" . ( -1 * $business['business_id'] ) .", message='Hereby we return the pledge that was used in loan #". $loan['loan_id'] ."'" );
	}

	/* Add an event to the player... */
	$player->addEvent( "Loan Declined", $eventmessage );

	$message = "You have declined the loan request from ". $player->nickname ."! They have been informed of your action!";

	$dontcontinue = true;
}

if ( isset( $_POST['return'] ) )
{
	$newnotes = $loan['case_notes'] ."\n". date( TIME_FORMAT, time() ) ." - Loan returned by ". $char->rank ." ". $char->nickname .".";
	$db->query( "UPDATE bank_loans SET employee=0, case_notes=". $db->prepval( $newnotes ) ." WHERE loan_id=". $loan['loan_id'] );

	$message = "You stopped working on loan #". $loan['loan_id'] ."!";
	$dontcontinue = true;
}

if ( isset( $message ) )
{
	echo "<div style='width: 70%; margin-left: auto; margin-right: auto;'><p style='color: red;'><strong>$message</strong></p></div>";
	unset( $message );
}

if ( !isset( $dontcontinue ) || $dontcontinue == false )
{
?>

<div style="width: 70%; margin-left: auto; margin-right: auto; margin-bottom: 30px;">
	<div class="title" style="padding-left: 10px;">
		Work on loan #<?=$loan['loan_id'];?>
	</div>
	<div class="content">
	<form action="<?=$PHP_SELF;?>" method="post">
		<p>You are working on loan #<?=$loan['loan_id'];?>, which involves a sum of $<?=$loan['loan_amount'];?>. Below you will find all related information and options to make this loan into a success for both parties!</p>

		<div class="title" style="padding-left: 5px; font-weight: normal;">
			<i>Notepad for loan #<?=$loan['loan_id'];?></i>
		</div>
		<div class="content" style="background-color: #fff686;">
			<p><textarea name="notes" style="width: 100%; height: 100px;"><?=stripslashes( $loan['case_notes'] );?></textarea></p>
		</div>
		<table style="width: 100%; border: none; border-spacing: 0px;">
		<tr>
			<!-- THE PLEDGE INFORMATION. -->
			<td style="width: 40%;" valign="top">
				<div class="title" style="padding-left: 5px; font-weight: normal;">
					<i>Pledge Information</i>
				</div>
				<div class="content" style="text-align: center; background-color: #fff686;">
					<?					
					echo $pledge->name . "<br /><br />";
					echo "<img id='item". $pledge->item_id ."' src='../images/". $pledge->icon ."' alt='' style='border: solid 1px #990033;' />";
					?>
				</div>				
			</td>
			<!-- THE LOAN AMOUNT INFORMATION. -->
			<td style="width: 60%;" valign="top">
				<div class="title" style="padding-left: 5px; font-weight: normal;">
					<i>Financial Information</i>
				</div>
				<div class="content" style="bottom: 0px; background-color: #fff686;">
					<table style="width: 100%">				
					<tr>
						<td style="width: 40%;">Amount:</td>
						<td>$<?=$loan['loan_amount'];?> <? echo "<span". ( getMaxLoanAmount( $player->bank_trust ) + $pledge->worth < $loan['loan_amount'] ? " style='color: red;'" : "" ) .">( max: $". ( getMaxLoanAmount( $player->bank_trust ) + $pledge->worth ) ." )</span>"; ?></td>
					</tr>
					<tr>
						<td style="width: 40%;"><?=($loan['loan_status']==0 ? "Select " : "" );?>Rent:</td>
						<?
						if ( $loan['loan_status'] == 0 )
						{
							?>
							<td>
								<select class="std_input" name="loanrent" style="width: 100px;" onchange="document.getElementById('totaldebt').innerHTML = '$' + ( <?=$loan['loan_amount'];?> * ( 1 + ( this.value / 10000 ) ) - <?=$loan['loan_repaid'];?> );">
									<? for ( $i = $banksettings['loanrate'] / 500; $i < ( 5000 - $banksettings['loanrate'] ) / 500 + 1 + $banksettings['loanrate'] / 500; $i++ ) { ?>
									<option value="<?=( $i * 500 );?>"><?=( $i * 5 );?>%</option>
									<? } ?>
								</select>
							</td>
							<?
						}
						else
						{
							?><td>$<?=ceil($loan['loan_amount'] * ( $loan['rent'] / 100 ));?> (<?=$loan['rent'];?>%)</td><?
						}
						?>
					</tr>
					<tr>
						<td style="width: 40%;">Total Debt:</td>
						<td>
						<?
						if ( $loan['loan_status'] == 0 )
						{
							?><div id="totaldebt">
								$<?=abs( ceil( $loan['loan_amount'] * ( 1 + ( $banksettings['loanrate'] / 10000 ) ) - $loan['loan_repaid'] ) );?>
							</div><?
						}
						else
						{
							echo "$". abs( ceil( $loan['loan_amount'] * ( 1 + ( $loan['rent'] / 100 ) ) - $loan['loan_repaid'] ) );
						}						
						?>
						</td>
					</tr>
					<tr>
						<td style="width: 40%;">Amount repaid:</td>
						<td>$<?=$loan['loan_repaid'];?></td>
					</tr>
					</table>
				</div>
			</td>			
		</tr>
		<tr>
			<td style="width: 100%;" colspan="2">
				<div class="title" style="padding-left: 5px; font-weight: normal">
					<i>General Information</i>
				</div>
				<div class="content" style="background-color: #fff686;">
					<table style="width: 100%;">
					<tr>
						<td style="width: 12%;">First name:</td>
						<td style="width: 17%;"><?=$player->firstname;?></td>
						<td style="width: 12%;">Warnings:</td>
						<td style="width: 20%;"><?=$loan['num_warnings'];?></td>
						<td style="width: 12%;">Valid until:</td>
						<td style="width: 25%;"><?
						if ( $loan['loan_status'] == 0 )
						{
							echo date( TIME_FORMAT, time() + ( $banksettings['loan_period'] * 86400 ) );
						}
						else
						{
							echo date( TIME_FORMAT, $loan['repay_time'] );
						}
						?>
						</td>
					</tr>
					<tr>
						<td style="width: 12%;">Last name:</td>
						<td style="width: 17%;"><?=$player->lastname;?></td>
						<td style="width: 12%;">Time left:</td>
						<td style="width: 20%;"><?
						if ( $loan['loan_status'] == 0 )
						{
							echo $banksettings['loan_period'] . " days";
						}
						else
						{
							if ( $loan['repay_time'] < time() )
							{
								echo "<span style='color: red;'>No time left!</span>";
							}
							else
							{
								$arr = time_diff( time(), $loan['repay_time'] );
								echo $arr['days'] ." days, ". ( $arr['hours'] < 10 ? "0" : "" ) . $arr['hours'] .":". ( $arr['minutes'] < 10 ? "0" : "" ) . $arr['minutes'] .":". ( $arr['minutes'] < 10 ? "0" : "" ) . $arr['seconds'];
							}
						}
						?></td>
						<td style="width: 12%;">Loan status:</td>
						<td style="width: 25%;"><?=getLoanStatusString( $loan['loan_status'] );?></td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td style="width: 100%;" colspan="2">
				<div class="title" style="padding-left: 5px; font-weight: normal">
					<i>Possible Actions</i>
				</div>
				<div class="content" style="background-color: #fff686; text-align: center;">
					<?
					if ( $loan['loan_status'] != 0 )
					{
						?>
						<input type="submit" name="warn" value="Send Warning" style="width: 125px;" />
						<input type="submit" name="archive" value="Archive" style="width: 125px; margin-left: 10px;" />
						<input type="submit" name="return" value="Return Loan" style="width: 125px; margin-left: 10px;" />					
						<?
					}
					if ( $loan['loan_status'] == 0 )
					{
						?>
						<input type="submit" name="accept" value="Accept Loan" style="width: 125px;" />
						<input type="submit" name="decline" value="Decline Loan" style="width: 125px; margin-left: 10px;" />
						<?
					}
					?>
				</div>
			</td>
		</tr>
		</table>
	</form>
	</div>
</div>
<?
}
?>