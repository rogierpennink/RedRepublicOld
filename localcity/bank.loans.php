<?
/**
 * Get number of employees that can deal with loans...
 */
$info = $db->getRowsFor( "SELECT COUNT(*) AS num FROM char_characters WHERE location=". $char->location_nr ." AND rank_id > 4 AND rank_id <= 8" );
?>
<div style="width: 90%; margin-left: auto; margin-right: auto;">
	<div class="title" style="padding-left: 10px;">
		<?=$business['name'];?>'s Loans Central
	</div>
	<div class="content" style="margin-bottom: 10px;">
		<p><strong>Important Information:</strong></p>
		<p>
		<?=$business['name'];?> currently employs <strong><?=$info['num'];?> employee<?=($info['num']==1?"":"s");?></strong> that <?=($info['num']==1?"is":"are");?> allowed to deal with loans.<br />
		<?=$business['name'];?> currently charges a minimum of <strong><?=($banksettings['loanrate']/100);?>% rent</strong><br />
		Loans are required to repaid within <strong><?=$banksettings['loan_period'];?> days</strong>
		</p>
	</div>

	<?
	$query = $db->query( "SELECT * FROM bank_loans WHERE loan_status<>10 AND char_id=". $char->character_id );
	if ( $char->homecity_nr != $char->location_nr )
	{
		?>
		<div class="title" style="padding-left: 10px;">
			Unable to request loan
		</div>
		<div class="content">
			Unfortunately, you can't request loans, or view your outstanding loans here! You need to be in your homecity!
		</div>
		<?
	}
	elseif ( $db->getRowCount( $query ) == 0 )
	{
		?>
		<div class="title" style="padding-left: 10px;">
			Request a new loan 
		</div>
		<div class="content">
			<p>If you have carefully read the information above, you may request a loan here! The maximum amount of money that you can currently borrow from this bank is $<?=getMaxLoanAmount( $char->bank_trust );?> plus the worth of any pledge you may want to use.</p>
			<form action="<?=$PHP_SELF;?>" method="post">
			<table style="margin-top: 10px; margin-bottom: 10px;">
			<tr>
				<td style="width: 120px;"><strong>Loan amount:</strong></td>
				<td><input type="text" class="std_input" name="loan_amount" />
			</tr>
			<tr>
				<td style="width: 120px;"><strong>Pledge:</strong> *</td>
				<td>
					<select name="pledge" class="std_input">
						<option value="0">No pledge</option>
						<?
						$pledgequery = $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id );
						while ( $item = $db->fetch_array( $pledgequery ) )
						{
							$pledge = new Item( $item['item_id'] );
							echo "<option value=\"". $item['item_id'] ."\">". $pledge->name . " ($". $pledge->worth .")</option>";
						}
						?>
					</select>
				</td>
			</tr>
			</table>
			<p>
				<i>* A pledge must be an item that is currently in your inventory. You can simply add the pledge's worthy to your current loan limit...</i>
			</p>
			<p>
			<input type="submit" name="request_loan" value="Request Loan" class="std_input" />
			</p>
			</form>
		</div>
		<?
	}
	else
	{
		$loan = $db->fetch_array( $query );
		?>
		<div class="title" style="padding-left: 10px;">
			Currently active loan
		</div>
		<div class="content">
			<p>You currently have an active loan with this bank. A loan will remain active until an employee of the bank has archived your case after you have paid back in full. Until that time, you can not request any other loan...</p>
			<form action="<?=$PHP_SELF;?>" method="post">
			<table style="margin-bottom: 10px;">
			<tr>
				<td style="width: 120px;"><strong>Loan Status:</strong></td>
				<td><?=getLoanStatusString( $loan['loan_status'] );?></td>
			</tr>
			<tr>
				<td style="width: 120px;"><strong>Loan ID:</strong></td>
				<td>#<?=$loan['loan_id'];?></td>
			</tr>
			<tr>
				<td style="width: 120px;"><strong>Rent:</strong></td>
				<td>$<?=ceil( $loan['loan_amount'] * ( $loan['rent'] / 100 ) );?> (<?=$loan['rent'];?>%)</td>
			</tr>
			<tr>
				<td style="width: 120px;"><strong>Total Debt:</strong></td>
				<td>$<?=ceil( $loan['loan_amount'] * ( 1 + $loan['rent'] / 100 ) );?></td>
			</tr>
			<tr>
				<td style="width: 120px;"><strong>Current Debt:</strong></td>
				<td>$<?=( ceil( $loan['loan_amount'] * ( 1 + $loan['rent'] / 100 ) ) - $loan['loan_repaid'] );?></td>
			</tr>
			<tr>
				<td style="width: 120px;"><strong>Amount Repaid:</strong></td>
				<td>$<?=$loan['loan_repaid'];?></td>
			</tr>
			<tr>
				<td style="width: 120px;"><strong>Runs until:</strong></td>
				<td>
				<?
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
				<td style="width: 120px;"><strong>Time Left:</strong></td>
				<td>
				<?
				if ( $loan['loan_status'] == 0 )
				{
					echo $banksettings['loan_period'] ." day(s)";
				}
				else
				{
					if ( $loan['repay_time'] < time() || $loan['num_warnings'] > 0 )
					{
						echo "<span style='color: red;'>No time left! Repay immediately!</span>";
					}
					else
					{
						$arr = time_diff( time(), $loan['repay_time'] );
						echo $arr['days'] ." days, ". ( $arr['hours'] < 10 ? "0" : "" ) . $arr['hours'] .":". ( $arr['minutes'] < 10 ? "0" : "" ) . $arr['minutes'] .":". ( $arr['minutes'] < 10 ? "0" : "" ) . $arr['seconds'];
					}
				}
				?>
				</td>
			</tr>
			<tr>
				<td style="width: 120px;"><strong>Repay Loan: *</strong>
				<td>
					<input type="text" class="std_input" name="repay_amount" style="margin-right: 5px;" /> from source:
					<select class="std_input" name="source" style="margin-left: 5px;">
						<option value="0">Money on hand</option>
						<option value="1">Dirty money</option>
						<?
						$baquery = $db->query( "SELECT * FROM bank_accounts WHERE account_type=0 AND owner_id=". $char->character_id );
						while ( $ba = $db->fetch_array( $baquery ) )
						{
							?><option value="<?=$ba['account_number'];?>"><?=getBankAccountTypeString( $ba['account_type'] );?> (<?=$ba['account_number'];?>)</option><?
						}
						?>
					</select>
				</td>
			</tr>
			</table>
			<p><i>* You are not required to repay your loan at once. You can repay any amount smaller than or equal to the current debt as long as the current debt reaches $0 before the time is up.</i></p>
			<p><input type="submit" name="repay_loan" value="Submit" class="std_input" /><input type="hidden" name="loan_id" value="<?=$loan['loan_id'];?>" /></p>
			</form>
		</div>
		<?
	}
	?>
		
</div>