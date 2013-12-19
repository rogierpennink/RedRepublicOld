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

/* TRAINEES AREN'T ALLOWED HERE! */
if ( $char->rank_nr != 8 )
{
	$_SESSION['error'] = "You are not the President of this bank, you can't visit that page!";
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

if ( isset( $_POST['submit'] ) )
{
	/* Get total amount of money in this bank. */
	$info = $db->getRowsFor( "SELECT SUM(balance) AS amount FROM bank_accounts WHERE business_id=". $business['business_id'] );

	if ( $_POST['loanrate'] < 0 || $_POST['loanrate'] > 5000 )
	{
		$_SESSION['message'] = "The loan rate should be between 0% and 50%!";
	}
	elseif ( $_POST['loan_period'] < 1 || $_POST['loan_period'] > 10 )
	{
		$_SESSION['message'] = "The amount of days in which a loan must be repaid must be between 1 and 10!";
	}
	elseif ( $_POST['savingsrent'] < 50 || $_POST['savingsrent'] > 1000 )
	{
		$_SESSION['message'] = "The savings rent should be between 0.5% and 10%!";
	}
	elseif ( $_POST['transferfee'] < 0 || $_POST['transferfee'] > 10 ) 
	{
		$_SESSION['message'] = "The fee that applies to transfers should be between 0% and 1%!";
	}
	elseif ( $_POST['depositfee'] < 0 || $_POST['depositfee'] > 10 )
	{
		$_SESSION['message'] = "The fee that applies to depositing should be between 0% and 1%!";
	}
	elseif ( $_POST['withdrawalfee'] < 0 || $_POST['withdrawalfee'] > 10 )
	{
		$_SESSION['message'] = "The fee that applies to withdrawing should be between 0% and 1%!";
	}
	elseif ( $_POST['buffer'] < 25000 || $_POST['buffer'] > ( $info['amount'] / 2 ) )
	{
		$_SESSION['message'] = "The monetary buffer size must be between $25000 and $". ( $info['amount'] / 2 ) ."!";
	}
	else
	{
		$loanrate = $db->prepval( $_POST['loanrate'] );
		$savingsrent = $db->prepval( $_POST['savingsrent'] );
		$transferfee = $db->prepval( $_POST['transferfee'] );
		$depositfee = $db->prepval( $_POST['depositfee'] );
		$withdrawfee = $db->prepval( $_POST['withdrawalfee'] );
		$buffer = $db->prepval( $_POST['buffer'] );

		/* Update. */
		$db->query( "UPDATE bank_settings SET loanrate=$loanrate, savings_rent=$savingsrent, transfer_fee=$transferfee, deposit_fee=$depositfee, withdrawal_fee=$withdrawfee, buffer_size=$buffer WHERE business_id=". $business['business_id'] );

		$_SESSION['message'] = "The bank's rates and fees were updated successfully!";

		$banksettings['loanrate'] = $loanrate;
		$banksettings['savings_rent'] = $savingsrent;
		$banksettings['transfer_fee'] = $transferfee;
		$banksettings['buffer_size'] = $buffer;
		$banksettings['withdrawal_fee'] = $withdrawfee;
		$banksettings['deposit_fee'] = $depositfee;
	}
}

include_once "../includes/header.php";
?>

<h1><?=$business['name'];?>'s president's office</h1>
<p>As the president of <?=$business['name'];?> you are required to operate the bank and ever attract new customers by providing the best services and most interesting loan rates and service fees. You can manage all these settings here!</p>

<div style="width: 80%; margin-left: auto; margin-right: auto;">
	<div class="title" style="padding-left: 10px;">
		Manage bank settings
	</div>
	<div class="content">
		<?
		if ( isset( $_SESSION['message'] ) )
		{
			echo "<p style='color: red; font-weight: bold;'><strong>". $_SESSION['message'] ."</strong></p>";
			unset( $_SESSION['message'] );
		}
		?>
		<form action="<?=$PHP_SELF;?>" method="post">

		<p><strong>Rate and fee percentages:</strong> enter percentages for the rates and fees that apply to this bank's services.</p>
		<table style="margin-left: 10px; margin-bottom: 10px; width: 250px; max-width: 100%;">
		<tr>
			<td style="width: 60%;">Target loan rate:</td>
			<td style="width: 40%;">
				<select class="std_input" name="loanrate" style="width: 100px;">
					<? for ( $i = 0; $i < 11; $i++ ) { ?>
					<option value="<?=( $i * 500 );?>" <?=( $i * 500  == $banksettings['loanrate'] ? "selected='true'" : "");?>><?=( $i * 5 );?>%</option>
					<? } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 60%;">Time to repay loans:</td>
			<td style="width: 40%;">
				<select class="std_input" name="loan_period" style="width: 100px;">
					<? for ( $i = 1; $i < 11; $i++ ) { ?>
					<option value="<?=$i;?>" <?=( $i == $banksettings['loan_period'] ? "selected='true'" : "");?>><?=$i;?> days</option>
					<? } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 60%;">Savings account rent:</td>
			<td style="width: 40%;">
				<select class="std_input" name="savingsrent" style="width: 100px;">
					<? for ( $i = 1; $i < 21; $i++ ) { ?>
					<option value="<?=( $i * 50 );?>" <?=( $i * 50  == $banksettings['savings_rent'] ? "selected='true'" : "");?>><?=( $i * 5 / 10 );?>%</option>
					<? } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 60%;">Transfer fee:</td>
			<td style="width: 60%;">
				<select class="std_input" name="transferfee" style="width: 100px;">
					<? for ( $i = 0; $i < 11; $i++ ) { ?>
					<option value="<?=$i;?>" <?=( $i == $banksettings['transfer_fee'] ? "selected='true'" : "" );?>><?=( $i / 100 );?>%</option>
					<? } ?>
			</td>
		</tr>
		<tr>
			<td style="width: 60%;">Deposit fee:</td>
			<td style="width: 60%;">
				<select class="std_input" name="depositfee" style="width: 100px;">
					<? for ( $i = 0; $i < 11; $i++ ) { ?>
					<option value="<?=$i;?>" <?=( $i == $banksettings['deposit_fee'] ? "selected='true'" : "" );?>><?=( $i / 100 );?>%</option>
					<? } ?>
			</td>
		</tr>
		<tr>
			<td style="width: 60%;">Withdrawal fee:</td>
			<td style="width: 60%;">
				<select class="std_input" name="withdrawalfee" style="width: 100px;">
					<? for ( $i = 0; $i < 11; $i++ ) { ?>
					<option value="<?=$i;?>" <?=( $i == $banksettings['withdrawal_fee'] ? "selected='true'" : "" );?>><?=( $i / 100 );?>%</option>
					<? } ?>
			</td>
		</tr>
		</table>

		<p><strong>Monetary buffer:</strong> A monetary buffer is an amount of money, taken from the bank itself, that serves as a buffer for bankrobberies and other crimes. In case of a bankrobbery, the money in this buffer will be used to reimburse clients to lower the effects of a bankrobbery on the bank's clients.</p>

		<table style="margin-left: 10px; width: 250px; max-width: 100%; margin-bottom: 10px;">
		<tr>
			<td style="width: 60%;">Buffer size:</td>
			<td style="width: 60%;">
				<input type="text" style="text-align: right; padding: 2px; width: 100px;" class="std_input" name="buffer" value="<?=$banksettings['buffer_size'];?>" />
			</td>
		</tr>
		</table>

		<p><input type="submit" name="submit" value="Update Settings" /></p>
		</form>
	</div>
</div>