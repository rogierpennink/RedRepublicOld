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
if ( $char->rank_nr == 4 )
{
	$_SESSION['error'] = "As a Bank Trainee, you don't have access to the loan central yet!";
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

/* Make sure no dead employees or ex-employees still hold requests. */
$db->query( "UPDATE bank_loans INNER JOIN char_characters ON bank_loans.employee = char_characters.id INNER JOIN char_ranks ON char_characters.rank_id = char_ranks.id SET employee=0 WHERE health <= 0 OR occupation_id<>3" );

if ( isset( $_POST['take'] ) )
{
	/* Check the loan_id. */
	$checkq = $db->query( "SELECT * FROM bank_loans WHERE business_id=". $business['business_id'] ." AND employee=0 AND loan_id=". $db->prepval( $_POST['loan_id'] ) );
	if ( $db->getRowCount( $checkq ) == 0 )
	{
		$message = "An invalid loan ID was specified: you can't take that loan!";
	}
	else
	{
		$loan = $db->fetch_array( $checkq );

		if ( $loan['char_id'] == $char->character_id )
		{
			$message = "You can't work on loans that you have requested yourself!";
		}
		elseif ( $loan['loan_amount'] >= 150000 && $char->rank_nr < 6 )
		{
			$message = "You cannot yet work on loans that involve sums of $150000 and larger!";
		}
		else
		{
			$db->query( "UPDATE bank_loans SET employee=". $char->character_id ." WHERE loan_id=". $db->prepval( $_POST['loan_id'] ) );

			$message = "You are now working on loan #". $_POST['loan_id'] ."!";
		}
	}
}

include_once "../includes/header.php";
?>
<h1><?=$business['name'];?>'s Loan Central</h1>
<p>Loans are easily granted, but hard to pay back. As a bank employee that's been allowed the responsibility of handing out loans and seeing to it that they are repaid, it is your job to judge people at first glance. No matter how convincing someone may be, you are the one who is responsible if the bank ends up loosing profit!</p>
<p>Earning money at the loan central is two-fold. The bank is happy if you bring in a new loan and therefore pays you upon accepting a loan. If the loan is also repaid and you're the one to close the file, you will be rewarded again.</p>

<? /* Decide whether to display the list or the specific loan display. */
$query = $db->query( "SELECT * FROM bank_loans WHERE employee=". $char->character_id );
if ( $db->getRowCount( $query ) > 0 )
{
	$validfile = true;
	include_once "loan_case.php";
}

if ( isset( $message ) )
{
	echo "<p style='color: red;'><strong>$message</strong></p>";
	unset( $message );
}

$query = $db->query( "SELECT * FROM bank_loans WHERE employee=". $char->character_id );
if ( $db->getRowCount( $query ) == 0 )
{
?>
	<div class="title" style="padding-left: 10px;">
		Loan Requests Listing
	</div>
	<div class="content" style="padding: 0px;">
	<?
	$lquery = $db->query( "SELECT * FROM bank_loans WHERE loan_status<>10 AND business_id=". $business['business_id'] );
	if ( $db->getRowCount( $lquery ) == 0 )
	{
		?><div style="padding: 10px;">No loans and/or loan requests were found!</div><?
	}
	else
	{
		$bgcolor = "transparent";
		while ( $loan = $db->fetch_array( $lquery ) )
		{
		$loanchar = new Player( $loan['char_id'], false );
		$bgcolor = $bgcolor == "transparent" ? "#fff686" : "transparent";
		?>
			<div class="row" style="background-color: <?=$bgcolor;?>;">
				<form action="<?=$PHP_SELF;?>" method="post">
				<table class="row" style="margin: 5px;">
				<tr>
					<td style="width: 12%;">First name:</td>
					<td style="width: 10%;"><?=$loanchar->firstname;?></td>
					<td style="width: 15%;">Amount Requested:</td>
					<td style="width: 15%;">$<?=$loan['loan_amount'];?></td>
					<td style="width: 15%;">Pledge:</td>
					<td style="width: 15%;">
					<? 
						if ( $loan['pledge'] != 0 ) { $pledge = new Item( $loan['pledge'] ); echo $pledge->name; } else { echo "None"; } 
					?>
					</td>
					<td rowspan="3" style="width: 20%; text-align: center;">
						<? if ( $loan['employee'] == 0 ) { ?>
						<input type="submit" name="take" value="Take Request" class="std_input" />
						<input type="hidden" name="loan_id" value="<?=$loan['loan_id'];?>" />
						<? } else { 
						   $worker = $db->getRowsFor( "SELECT * FROM char_characters WHERE id=". $loan['employee'] );
						   echo "This request is handled by ". $worker['nickname'];
						} ?>					
					</td>
				</tr>
				<tr>
					<td style="width: 12%;">Last name:</td>
					<td style="width: 10%;"><?=$loanchar->lastname;?></td>
					<td style="width: 15%;">Loan Status:</td>
					<td style="width: 15%;"><?=getLoanStatusString( $loan['loan_status'] );?></td>
					<td style="width: 15%;">Pledge Worth:</td>
					<td style="width: 15%;">$<?=($loan['pledge'] != 0 ? $pledge->worth : 0);?></td>
				</tr>
				</table>
				</form>
			</div>
		<?
		}
	}
	?>
	</div>
<?
}
include_once "../includes/footer.php";
?>