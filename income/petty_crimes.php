<?
$nav = "income";
/**
 * The emp_agency.php file is the standard income file for many people who don't have a job yet or
 * want
 */
$REDIRECT = true;
$CHARNEEDED = true;
$AUTH_LEVEL = 0;

include_once "../includes/utility.inc.php";

$_SESSION['ingame'] = true;

$char = new Player( getUserID() );

if ( isset( $_POST['submit'] ) )
{
	include_once "handler_pettycrimes.php";
}

// Start the real main page
include_once "../includes/header.php";
?>

<h1>Petty Crimes</h1>
<p>So you're not entirely happy with your current salary anymore? Or perhaps you just want to quit your boring life and search for adventure, the thrill of the hunt, and the great relief after every job? If so, you may want to try some of these petty crimes and enter the world of dirty money and tax evasion!</p>
<p>Now that you've decided to enter this exciting and dangerous world you'll find some suitable crimes listed here. As you grow more experienced in them, more options will appear.</p>

<? if ( isset( $_SESSION['earn_msg'] ) ) { ?><p id="output" style="font-weight: bold;"><?=$_SESSION['earn_msg'];?></p><? unset( $_SESSION['earn_msg'] ); } ?>

<!-- Start Centering Table --><table align='center' style="width: 100%;"><tr><td style="width: 100%;" align="center">
<div style="width: 30%;">
	<div class="title">
		<div style="margin-left: 10px;">Fitting Crimes</div>
	</div>
	<div class="content">
	<form action="<?=$PHP_SELF;?>" method="post">
		<table>
		<?
		if ( $char->getCriminalExp() < 250 )
		{
		?>
		<tr>
			<td style="width: 100%; text-align: center;"><strong>Petty crimes... you? Don't make me laugh! You wouldn't hurt a fly!</strong</td>
		</tr>
		<?
		}
		if ( $char->getCriminalExp() >= 250 )
		{
		?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="petty_crime" value="nickstores" />
			</td><td>
				Nick from stores
			</td>
		</tr>
		<?
		}
		if ( $char->getCriminalExp() >= 500 )
		{
			?>
			<tr>
				<td style="width: 20px; text-align: left;">
					<input type="radio" name="petty_crime" value="carradios" />
				</td><td>
					Steal car radios
				</td>
			</tr>
			<?
		}
		?>
		</table><br />
		<input type="submit" class="std_input" name="submit" value="Do Crime!" />
	</form>
	</div>
	
</div>
</td></tr></table><!-- End Centering Table -->


<?
include_once "../includes/footer.php";
?>