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

$validfile = true;
include_once "account_request_handler.php";

include_once "../includes/header.php";
?>
<h1>Review bank account subscription forms</h1>
<p>Virtually everyone needs a bank account these days, and this is where they request them! As a bank employee you are to judge who can be trusted, and who can't. Keep that in mind when dealing with these requests!</p>

<div style="width: 100%; margin-left: auto; margin-right: auto;">
	<?
	if ( isset( $_SESSION['msg'] ) ) { echo $_SESSION['msg']; unset( $_SESSION['msg'] ); }
	?>
	<div class="title" style="padding-left: 10px;">
		Bank account requests
	</div>
	<div class="content" style="padding: 0px;">
	<?
	$reqquery = $db->query( "SELECT bank_accounts.firstname AS firstname, bank_accounts.lastname AS lastname, account_type, nickname, id, account_number, account_status, curr_employee, balance FROM bank_accounts INNER JOIN char_characters ON bank_accounts.curr_employee = char_characters.id WHERE account_status=0 OR account_status=10 OR account_status=11 AND business_id=". $business['business_id'] );
	if ( $db->getRowCount( $reqquery ) == 0 ) {
	?>
		<div style="padding: 10px;">There are no new bank account requests at present!</div>
	<?
	}
	else
	{
		$bgcolor = "transparent";
		while ( $request = $db->fetch_array( $reqquery ) )
		{
		$bgcolor = ( $bgcolor == "transparent" ) ? "#fff686" : "transparent";
		?>
		<div class="row" style="background-color: <?=$bgcolor;?>;">
			<form action="<?=$PHP_SELF;?>" method="post">
			<table class="row" style="margin: 5px;">
			<tr>
				<td style="width: 10%;">First name:</td>
				<td style="width: 10%;"><?=$request['firstname'];?></td>
				<td style="width: 12%;">Account Type:</td>
				<td style="width: 15%;"><?=getBankAccountTypeString( $request['account_type'] );?></td>
				<td style="width: 12%;">Initial Deposit:</td>
				<td style="width: 10%;">$<?=$request['balance'];?></td>
				<td rowspan="3" style="width: 40%; text-align: center;">					
					<input type="hidden" name="req_id" value="<?=$request['account_number'];?>" />
					<? if ( $request['account_status'] == 0 ) { ?>
					<input type="submit" name="credentials" value="Investigate" class="std_input" />
					<? } else { ?>
					<select class="std_input" name="option">
						<option value="accept">Accept Request</option>
						<option value="decline">Decline Request</option>
						<? if ( $request['account_status'] == 10 ) { ?>
						<option value="report">Report Fraud</option>
						<? } ?>
					</select>
					<input type="submit" name="action" value="Go" class="std_input" />
					<? } 
					if ( $request['curr_employee'] != $char->character_id ) { ?>
					<input type="submit" name="take" value="Take Request" class="std_input" />
					<? }
					if ( $request['curr_employee'] == $char->character_id && $request['account_status'] == 0 ) { ?>
					<input type="submit" name="drop" value="Drop Request" class="std_input" />
					<? } ?>
				</td>
			</tr>
			<tr>
				<td style="width: 10%;">Last name:</td>
				<td style="width: 10%;"><?=$request['lastname'];?></td>
				<td style="width: 12%;">Account Status:</td>
				<td style="width: 15%;"><?=getBankAccountStatusString( $request['account_status'] );?></td>
				<td style="width: 12%;">Curr. Employee:</td>
				<td style="width: 10%;"><?=( $request['nickname'] == "ADMINISTRATOR" ? "None" : "<a href='../profile.php?id=". $request['id'] ."'>". $request['nickname'] . "</a>" );?></td>
			</tr>				
			</table>
			</form>
		</div>
		<? 
		}
	}	
	?>
	</div>
</div>
<?
include_once "../includes/footer.php";
?>