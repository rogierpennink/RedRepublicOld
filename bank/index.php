<?
$nav = "work";
$CHARNEEDED = true;
$OCCNEEDED = 3;
$REDIRECT = true;
$AUTH_LEVEL = 0;

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

include_once "../includes/header.php";
?>
<h1>Work at the <?=$char->homecity;?> Bank</h1>
<p>Admittedly, the back entrance of <?=$business['name'];?> doesn't look as splendid as it's majestic front entrance, but at least you're not part of the tramps begging for money there! You are officially employed at the bank and you're proud to walk through these doors!</p>

<div style="width: 90%; margin-left: auto; margin-right: auto;">
	<div class="title" style="padding-left: 10px;">
		The bank's options
	</div>
	<div class="content">
		<p>
		<a href="bankearns.php">General Duties</a><br />
		When you're not accepting loans or making arrangements with customers about having their Will officially processed by our bank, there are always other important jobs to be done just to keep the bank running. Do them here!
		</p>

		<p>
		<a href="account_requests.php">View Bank Account Requests</a><br />
		Every now and then people will feel the need to open a new bank account with our bank. We cannot just accept everyone and we need to check people's credentials before accepting the creation of new bank accounts. This is the place to do that!
		</p>

		<? if ( $char->rank_nr != 4 ) { // Everyone *but* trainees can do this ?>
		<p>
		<a href="loan_requests.php">Review Loan Requests</a><br />
		The shady part of banking: loans. From here you can manage all requests made by people that are in need of a loan. Be aware though, many people abuse loans to have their money laundered. Integrity is highly valued here!
		</p>
		<? } if ( $char->rank_nr >= 7 ) { ?>
		<p>
		<a href="personell.php">Personell Management</a><br />
		Being a bank manager or higher, part of your job description reads 'human resources'. This is where you can manage the human resources of <i>this</i> bank!
		</p>
		<? } if ( $char->rank_nr == 8 ) { ?>
		<p>
		<a href="bank_settings.php">Review rates and fees</a><br />
		As the president of the bank it is your duty to formulate policies that your bank's employees can abide by. It is important for a bank to profile itself with the fees and rates it charges, and those can be influenced from this option!
		</p>
		<? } ?>

	</div>
</div>

<?
include_once "../includes/footer.php";
?>