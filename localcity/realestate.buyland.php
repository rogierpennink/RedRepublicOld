<?
/**
 * Make sure the file isn't used when not included.
 */
if ( !isset( $continue ) || $continue == false ) exit; 

$restate = $db->getRowsFor( "SELECT * FROM realestate_agency WHERE business_id=". $business['business_id'] );

//See if user has bank account or not.
$bankq = $db->query( "SELECT * FROM bank_accounts WHERE owner_id=". $char->character_id ." AND account_type=0 AND account_status <> 0" );
$bankacc = $db->fetch_array( $bankq );				

?>

<div class="title" style="padding-left: 10px;">
	Buy Uncultivated Land
</div>
<div class="content">
	<p>
		If you are interested in buying some land that has not been touched by human hands as of yet, and that still needs cultivation then you need to consider buying a couple of square meters here.
	</p>
	<p>
		<strong>Currently</strong>, there is an amount of <strong><?=($maxland-$land['amount_taken']);?>m&sup2;</strong> available!<br />
		<strong>The price</strong> per square meter of land is: <strong>$<?=$restate['price_per_meter'];?></strong><br />
		You can pay from a <strong>checking account</strong>, if you wish to do so, enter its <strong>account number</strong> in the form below.
	</p>
	<p>
		<form action="<?=$PHP_SELF;?>" method="post">
		Please enter the amount of land you wish to buy: <input type="text" class="std_input" style="margin-left: 7px;" name="landamount" /><br />		
		Your bank account number (checking account): <input type="text" class="std_input" style="margin-left: 17px;" name="bank_account" value="<?php echo ( $db->getRowCount( $bankq ) == 0 ) ? "" : $bankacc['account_number'];?>" /><br /><br />
		<input type="submit" name="do_buyland" class="std_input" value="Buy Land!" /><br />
		</form>
	</p>
</div>