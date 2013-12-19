<?
$nav = "income";
$CHARNEEDED = true;
$REDIRECT = true;
$AUTH_LEVEL = 0;
include_once "../includes/utility.inc.php";

$_SESSION['ingame'] = true;

$char = new Player( getUserID() );

if ( isset( $_POST['submit'] ) && time() < $char->agg_timer )
{
	$_SESSION['agg_msg'] = "You can't do another aggravated crime yet! You hardly recovered from the last!";
	unset( $_POST['submit'] );	
}
else
{
	switch ( $_POST['agg_crime'] )
	{
		case "mugging":
			include_once "aggs/mugging_handler.php";
			break;

		case "pickpocket":
			include_once "aggs/pickpocket_handler.php";
			break;

		case "stalking":
			include_once "aggs/stalker_handler.php";
			break;
		
		case "torchmailbox":
			include_once "aggs/torchmailbox_handler.php";
			break;

		case "carbomb":
			include_once "aggs/carbomb_handler.php";
			break;

		case "hackbank":
			include_once "aggs/hackbank_handler.php";
			break;
	}
}

// Start the real main page
include_once "../includes/header.php";
?>
<h1>Aggravated Crimes</h1>
<p>Aggravated Crimes are the way ruthless gangsters and experienced criminals make money. Don't go here unless you are confident that you are strong enough to perform these major crimes. Always remember that the police is the least of your fears if you messed these up...</p>
<p>Now that you've decided to give them a go anyway, you'll have to realise that you can't just go rampaging around. Start small, use the list below to work your way up and perhaps even gain a little respect.</p>

<? if ( isset( $_SESSION['agg_msg'] ) ) { ?><p style="font-weight: bold;"><?=$_SESSION['agg_msg'];?></p><? unset( $_SESSION['agg_msg'] ); } ?>

<?
$show = true;

if ( isset( $_POST['submit'] ) )
{
switch ( $_POST['agg_crime'] )
{
	case "mugging":
		include_once "aggs/mugging.php";
		break;

	case "pickpocket":
		include_once "aggs/pickpocket.php";
		break;
	
	case "stalking":
		include_once "aggs/stalker.php";
		break;

	case "torchmailbox":
		include_once "aggs/torchmailbox.php";
		break;

	case "carbomb":
		include_once "aggs/carbomb.php";
		break;

	case "hackbank":
		include_once "aggs/hackbank.php";
		break;

	default: echo "<p style=\"font-weight: bold;\">You must select a valid aggravated crime!</p>";
}
}

if ( $show )
{
?>
<div style="width: 100%;">

	<div class="title">
		<div style="margin-left: 10px;">Aggravated Crimes</div>
	</div>
	<div class="content">
	<form action="<?=$PHP_SELF;?>" method="post">
		<table>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="agg_crime" value="mugging" />
			</td><td>
				Mugging
			</td>
		</tr>		
		<?
		if ( $char->getCriminalExp() > 500 )
		{
			?>
			<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="agg_crime" value="pickpocket" />
			</td><td>
				Pickpocket
			</td>
			</td>
			<?
		}
		if ( $char->getCriminalExp() > 1000 )
		{
			?>
			<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="agg_crime" value="stalking" />
			</td><td>
				Stalking
			</td>
			</tr>
			<?
		}
		if ( $char->getCriminalExp() > 2000 )
		{
			?>
			<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="agg_crime" value="torchmailbox" />
			</td><td>
				Torch mailbox
			</td>
			</tr>
			<?
		}
		if ( $char->getCriminalExp() > 4000 )
		{
			?>
			<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="agg_crime" value="carbomb" />
			</td><td>
				Car Bomb
			</td>
			</tr>
			<?
		}
		if ( $char->getCriminalExp() > 10000 )
		{
			?>
			<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="agg_crime" value="hackbank" />
			</td><td>
				Hack the local bank's mainframe
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
<?
}
include_once "../includes/footer.php";
?>