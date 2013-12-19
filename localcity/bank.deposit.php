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
		document.getElementById('depositfee').innerHTML = "Fee: $" + ( rnd ( ( <?=$banksettings['deposit_fee'];?> / 100 ) * amount, 2 ) );
	}
}

function currBalance()
{
	acc = document.getElementById('to_account').value;
	
	<?
	$accquery = $db->query( "SELECT * FROM bank_accounts WHERE account_status <> 0 AND owner_id=". $char->character_id );
	while ( $acc = $db->fetch_array( $accquery ) )
	{
		echo "if ( acc == ". $acc['account_number'] ." ) { document.getElementById('currbalance').innerHTML = '$". $acc['balance'] ."'; }\n";
	}
	mysql_data_seek( $accquery, 0 );
	?>
}
</script>

<div style="width: 90%; margin-left: auto; margin-right: auto;">
	<div class="title" style="padding-left: 10px;">
		Deposit funds into one of your bank accounts.
	</div>
	<div class="content">
		<p>Use this form to deposit funds into one of your bank accounts. The Bank of <?=$char->location;?> charges a fee of <?=($banksettings['deposit_fee']/100);?>% for depositing funds. In practise this means you deposit a slightly larger amount of money than will actually be added to your account. If you do not have sufficient cash, the fee is withdrawn from your bank account.</p>
		
		<form action="bank.php" method="post">
		<table style="width: 100%;">
		<tr>
			<td style="width: 20%;">Current Balance:</td>
			<td>
				<?				
				$acc = $db->fetch_array( $accquery );
				mysql_data_seek( $accquery, 0 );
				?>
				<span id="currbalance">$<?=$acc['balance'];?></span>
			</td>
		</tr>

		<tr>
			<td style="width: 20%;">To account:</td>
			<td>				
				<select name="to_account" class="std_input" id="to_account" style="width: 200px;" onchange="javascript: currBalance();">
				<?
				while ( $acc = $db->fetch_array( $accquery ) )
				{
					echo "<option value='". $acc['account_number'] ."'>". $acc['account_number'] ." (". getBankAccountTypeString( $acc['account_type'] ) .")</option>";
				}
				?>
				</select>
			</td>
		</tr>

		<tr>
			<td style="width: 20%;">Amount:</td>
			<td><input type="text" class="std_input" name="amount" style="width: 100px;" onkeyup="javascript: updateFee( this.value );" />
				<span style="margin-left: 10px;" id="depositfee">Fee: $0.00</span></td>
		</tr>			
		</table>

		<p style="margin-top: 10px;">
			<input type="submit" class="std_input" name="deposit" value="Deposit" />
		</p>
		</form>
	</div>
</div>