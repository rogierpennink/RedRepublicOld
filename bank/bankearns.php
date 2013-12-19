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

if ( isset( $_POST['submit'] ) )
{
	if ( !isset( $_POST['bankearn'] ) )
	{
		$_SESSION['earn_msg'] = "You have to select an earn!";			
	}
	elseif ( $char->earn_timer > time() )
	{
		$_SESSION['earn_msg'] =  "You cannot do another job so quickly! You hardly recovered from the last!";
	}
	else
	{
		include_once "bankearns_handler.php";
	}
}

include_once "../includes/header.php";
?>
<h1><?=$business['name'];?>: General Duties</h1>
<p>Though not as exciting as day to day interaction with actual customers, these duties need to be fulfilled as well. Always keep in mind that those who do their duties regularly may be preferred over those who don't when it comes to promotions!</p>

<? if ( isset( $_SESSION['earn_msg'] ) ) { ?><p id="output" style="font-weight: bold;"><?=$_SESSION['earn_msg'];?></p><? unset( $_SESSION['earn_msg'] ); } ?>

<div style="width: 30%; margin-left: auto; margin-right: auto;">
	<div class="title" style="padding-left: 10px;">
		General Bank Duties
	</div>
	<div class="content">
		<form action="<?=$PHP_SELF;?>" method="post">
		<table>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="bankearn" value="teller_assistant" />
			</td><td>
				Assist the bank's teller staff
			</td>
		</tr>
		
		<? if ( $char->getBankExp() > 1000 ) { ?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="bankearn" value="teller" />
			</td><td>
				Bank teller
			</td>
		</tr>
		<? } if ( $char->getBankExp() > 2000 ) { ?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="bankearn" value="loancenter" />
			</td><td>
				Work at the loan central
			</td>
		</tr>
		<? } ?>

		</table><br />
		<input type="submit" class="std_input" name="submit" value="Work!" />
		</form>
	</div>
</div>
<?
include_once "../includes/footer.php";
?>