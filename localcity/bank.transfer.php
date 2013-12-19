<script type="text/javascript">
function is_numeric( data )
{	
	if ( data.search( /[^0-9]/ ) != -1 )
		return false;
	return true;
}

function updateFee( amount )
{
	if ( is_numeric( amount ) )
	{
		document.getElementById('transferfee').innerHTML = "Fee: $" + ( rnd( ( <?=$banksettings['transfer_fee'];?> / 100 ) * amount, 2 ) );
	}
}
</script>

<div style="width: 90%; margin-left: auto; margin-right: auto;">
	<div class="title" style="padding-left: 10px;">
		Transfer funds from one of your accounts to another bank account
	</div>
	<div class="content">
		<p>Use this form to transfer funds from your bank accounts to other bank accounts. Unless you are transferring funds to one of your own bank accounts, the Bank of <?=$char->location;?> charges a fee of <?=($banksettings['transfer_fee']/100);?>% for withdrawing funds. In practise, you will pay this fee with whatever money you have left at your bank account, but if that amount is insufficient, the fee will be taken from your transfer.</p>
		
		<form action="bank.php" method="post">
		<table style="width: 100%;">
		<tr>
			<td style="width: 20%;">From account:</td>
			<td>				
				<select name="from_account" class="std_input" style="width: 200px;">
				<?
				$accquery = $db->query( "SELECT * FROM bank_accounts WHERE account_status <> 0 AND owner_id=". $char->character_id );
				while ( $acc = $db->fetch_array( $accquery ) )
				{
					echo "<option value='". $acc['account_number'] ."'>". $acc['account_number'] ." (". getBankAccountTypeString( $acc['account_type'] ) .")</option>";
				}
				?>
				</select>
			</td>
		</tr>

		<tr>
			<td style="width: 20%;">To Account:</td>
			<td><input type="text" class="std_input" name="to_account" style="width: 100px;" onkeyup="javascript: updateFee( document.getElementById('amount').value );" />				
		</tr>	

		<tr>
			<td style="width: 20%;">Amount:</td>
			<td><input type="text" class="std_input" name="amount" id="amount" style="width: 100px;" onkeyup="javascript: updateFee( this.value );" />
				<span style="margin-left: 10px;" id="transferfee">Fee: $0.00</td>
		</tr>			
		</table>

		<p style="margin-top: 10px;">
			<input type="submit" class="std_input" name="transfer" value="Transfer" />
		</p>
		</form>
	</div>
</div>

