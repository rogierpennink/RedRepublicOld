<?
$nav = "income";
/**
 * The main.php file is the first page into the game itself. It has to check for a character
 * and if there is, set the 'ingame' session...
 */
$CHARNEEDED = true;
$REDIRECT = true;
$AUTH_LEVEL = 0;
include "../includes/utility.inc.php";
$char = new Player( getUserID() );
$_SESSION['ingame'] = true;

// Start the real main page
include_once "../includes/header.php";
?>

<h1>Your Income</h1>
<p>You can't live without a living, and this is where you make it. If you're not employed you may want to try your luck at the employment agency. They often have some jobs left that might just suit you! Your ways of making money will change over time, but remember, you are the only one who can influence your financial position!</p>

<div class="title">
	<div style="margin-left: 10px;">Your income options</div>
</div>
<div class="content">
	<table style="width: 100%;">
		<tr>
			<td style="width: 170px; text-align: center; vertical-align: top;">
				<!-- EMPLOYMENT AGENCY! -->
				<a href="emp_agency.php"><img src="../images/miscellaneous/employment_agency.png" alt="Employment Agency" style="border: none; width: 150px; height: 150xp;" /></a>				
			</td>
			<td style="text-align: left; vertical-align: top;">
				<h1><a href="emp_agency.php" style="color: #000;">Employment Agency</a></h1>
				<p>The employment agency is a place where unemployed bums go when they're in need for a few dollars to buy vodka with. But don't let that stand in your way! Some highly successful businessmen have reportedly become even more successful by applying for jobs at this employment agency!</p>				
			</td>
		
		<?
		if ( $char->getCriminalExp() >= 250 )
		{
		?>
		</tr><tr>
			<td style="width: 170px; text-align: center; vertical-align: top;">
				<!-- PETTY CRIMES! -->
				<a href="petty_crimes.php"><img src="../images/income_pettycrimes.png" alt="Petty Crimes" style="border: none; width: 150px; height: 150xp;" /></a>				
			</td>
			<td style="text-align: left; vertical-align: top;">
				<h1><a href="petty_crimes.php" style="color: #000;">Petty Crimes</a></h1>
				<p>So you're not entirely happy with your current salary anymore? Or perhaps you just want to quit your boring life and search for adventure, the thrill of the hunt, and the great relief after every job? If so, you may want to try some of these petty crimes and enter the world of dirty money and tax evasion!</p>				
			</td>
		<?
		}
		?>

		</tr><tr>
			<td style="width: 170px; text-align: center; vertical-align: top;">
				<!-- AGGRAVATED CRIMES! -->
				<a href="agg_crimes.php"><img src="../images/income_aggs.png" alt="Aggravated Crimes" style="border: none;" /></a>				
			</td>
			<td style="text-align: left; vertical-align: top;">
				<h1><a href="agg_crimes.php" style="color: #000;">Aggravated Crimes</a></h1>
				<p>Aggravated Crimes are the way ruthless gangsters and experienced criminals make money. Don't go here unless you are confident that you are strong enough to perform these major crimes. Always remember that the police is the least of your fears if you mess these up...  </p>
			</td>

		</tr>
	</table>
</div>


<?
include_once "../includes/footer.php";
?>