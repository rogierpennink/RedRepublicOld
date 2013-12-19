<?
if ( isset( $_GET['ajaxrequest'] ) && $_GET['ajaxrequest'] == "getaccountinfo" )
{
	include_once "../includes/utility.inc.php";
	$char = new Player( getUserID() );

	/* Check the account number. */
	$accquery = $db->query( "SELECT * FROM bank_accounts WHERE account_number=". $db->prepval( $_GET['account_num'] ) ." AND owner_id=". $char->character_id );
	if ( $db->getRowCount( $accquery ) == 0 )
	{
		echo "error::You have provided a bank account that isn't yours or that doesn't exist!";
		exit;
	}	
	$acc = $db->fetch_array( $accquery );

	/* Retrieve the business this bank account is affiliated with. */
	$busquery = $db->query( "SELECT * FROM businesses WHERE id=". $acc['business_id'] );
	$bus = $db->fetch_array( $busquery );

	/* Select recent transactions for the selected bank account */
	$transactionquery = $db->query( "SELECT * FROM bank_transactions WHERE account_number=". $acc['account_number'] ." ORDER BY `datetime` DESC LIMIT 0,10" );

	echo "success::";

	?>
	<div class="title" style="padding-left: 10px; margin-top: 10px;">
		Account <?=$acc['account_number'];?>, <?=getBankAccountTypeString( $acc['account_type'] );?>
	</div>
	<div class="content">

		<table style="width: 100%;">
		<tr>

		<!-- The general information window. -->
		<td style="width: 50%;">
		<div style="padding-right: 5px;">
			<div class="title" style="padding-left: 10px;">
				General Information
			</div>
			<div class="content" style="background-color: #fff686;">
				<table style="width: 100%;">
					<tr>
						<td style="width: 150px;"><strong>Your Bank:</strong></td>
						<td><?=$bus['name'];?></td>
					</tr><tr>
						<td style="width: 150px;"><strong>Account Status:</strong></td>
						<td><?
							switch ( $acc['account_status'] )
							{
								case 0: echo "Account is not yet approved"; break;
								case 1: echo "Account is frozen"; break;
								case 2: echo "Account is active"; break;
							}
							?>
						</td>
					</tr><tr>
						<td style="width: 150px;"><strong>Current Balance:</strong></td>
						<td>$<?=$acc['balance'];?></td>
					</tr>
				</table>
			</div>
		</div>
		</td>
		<td style="width: 50%;">
		<div style="padding-left: 5px; height: 100%;">
			<div class="title" style="padding-left: 10px;">
				Bank Account Options
			</div>
			<div class="content" style="background-color: #fff686; height: 100%;">
				<a href="bank.php?act=deposit">Deposit Funds</a><br />
				<a href="bank.php?act=withdraw">Withdraw Funds</a><br />
				<a href="bank.php?act=transfer">Transfer Funds</a><br />
				<a href="bank.php?act=loans">Loans Center</a><br />
			</div>
		</div>
		</td>

		</tr>
		</table>

		<!-- The transactions window. -->
		<div class="title" style="padding-left: 10px;">
			Latest transactions
		</div>
		<div class="content" style="background-color: #fff686; padding: 0px;">			
			<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
				<table class="row">
					<tr>		
						<td class="field" style="width: 10%;"><strong>Date</strong></td>
						<td class="field" style="width: 40%;"><strong>Transaction</strong></td>
						<td class="field" style="width: 15%;"><strong>Amount</strong></td>
						<td class="field" style="width: 10%;"><strong>Fee Paid</strong></td>
						<td class="field" style="width: 15%;"><strong>Balance</strong></td>			
					</tr>
				</table>
			</div>	
			<?
			while ( $transaction = $db->fetch_array( $transactionquery ) )
			{
			$to = $db->getRowsFor( "SELECT * FROM bank_accounts AS b INNER JOIN char_characters AS c ON b.owner_id=c.id WHERE b.account_number=". $transaction['to_player'] );
			if ( $to['owner_id'] == 0 )
			{
				$toq = $db->query( "SELECT b.account_number AS account_number, b2.name AS name FROM bank_accounts AS b INNER JOIN businesses AS b2 ON b.account_number = b2.bank_id WHERE b.account_number=". $transaction['to_player'] );
				$to = $db->fetch_array( $toq );
			}
			
			$from = $db->fetch_array( $db->query( "SELECT * FROM bank_accounts AS b INNER JOIN char_characters AS c ON b.owner_id=c.id WHERE b.account_number=". $transaction['from_player'] ) );
			?>
			<div class="row">
				<table class="row">
					<tr>		
						<td class="field" style="width: 10%;"><?=date( DATE_FORMAT, strtotime( $transaction['datetime'] ) );?></td>
						<td class="field" style="width: 40%;">
							<?								
								echo getTransactionTypeString( $transaction['transaction_type'] );
								echo " ";
								switch ( $transaction['transaction_type'] )
								{
									case 0: echo "to this account."; break;
									case 1: echo "from this account."; break;
									case 2: echo "account ". $to['account_number'] ." (". ( !isset( $to['nickname'] ) ? $to['name'] : $to['nickname'] ) .")"; break;
									case 3: echo "account ". $from['account_number'] ." (". $from['nickname'] .")"; break;
									case 4: echo "account ". $to['account_number'] ." (". $to['name'] .")"; break;
									case 5: break;
									default: echo "Unknown transaction";
								}
							?>
						</td>
						<td class="field" style="width: 15%;">$<?=$transaction['amount'];?></td>
						<td class="field" style="width: 10%;">$<?=$transaction['fee'];?></td>
						<td class="field" style="width: 15%;">$<?=$transaction['balance'];?></td>			
					</tr>
				</table>
			</div>	
			<?
			}
			?>
		</div>

	</div>
	<?
		
	echo exit;
}

?>
<div style="width: 90%; margin-left: auto; margin-right: auto;">
	<div class="title">
		<div style="margin-left: 10px;">Overview of your bank accounts</div>
	</div>
	<div class="content">
		<p>This is the overview of all your bank accounts. The overview consists of a log, containing the last ten transactions made to and from the bank account you selected. Please be aware that the balances at these transaction logs don't refer to your current balance.</p>
		Select a bank account: 
		<select id="ba_select" class="std_input" onchange="javascript: getAccountSummary( this.value );">
		<? while ( $acc = $db->fetch_array( $checkquery ) )
		{
			echo "<option value=\"". $acc['account_number'] ."\">". $acc['account_number'] . " (". getBankAccountTypeString( $acc['account_type'] ) .")</option>";
		}		
		?>
		</select>
		<p style="margin-top: 10px;">Other options: <a href="bank.php?act=newacc">Request new Account</a> <a href="bank.php?act=services">Services Overview</a></p>
	</div>

	<div id="ba_info">
	</div>
</div>

<script type="text/javascript">
function getAccountSummary( account )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( 'bank.overview.php' );
	ajax.addParam( 'ajaxrequest', 'getaccountinfo' );
	ajax.addParam( 'account_num', account );
	ajax.send( null );

	function response( text )
	{
		text = text.split( "::" );
		if ( text[0] == "success" )
			document.getElementById('ba_info').innerHTML = text[1];
		else if ( text[0] == "error" )
			setMessage( 'error', text[1] );
		else
			setMessage( 'error', "Error: The server sent back an unknown response." );
	}
}
getAccountSummary( document.getElementById('ba_select').value );
</script>