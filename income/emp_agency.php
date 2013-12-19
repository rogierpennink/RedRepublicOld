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
	include_once "handler_empagency.php";
}

// Start the real main page
include_once "../includes/header.php";
?>

<h1>The Employment Agency</h1>
<p>The employment agency is a place where unemployed bums go when they're in need for a few dollars to buy vodka with. But don't let that stand in your way! Some highly successful businessmen have reportedly become even more successful by applying for jobs at this employment agency!</p>
<p>Now that you've entered the employment agency they quickly profiled your character and qualities. They ended up with a list of fitting jobs that you can see below. What's more, you can apply for any of these jobs straight away!</p>

<? if ( isset( $_SESSION['earn_msg'] ) ) { ?><p id="output" style="font-weight: bold;"><?=$_SESSION['earn_msg'];?></p><? unset( $_SESSION['earn_msg'] ); } ?>

<div style="width: 100%;">

	<div class="title">
		<div style="margin-left: 10px;">Fitting jobs</div>
	</div>
	<div class="content">
	<form action="<?=$PHP_SELF;?>" method="post">
		<table>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="dairy_factory" />
			</td><td>
				Work in a dairy factory
			</td>
		</tr><tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="public_toilets" />
			</td><td>
				Clean out public toilets
			</td>
		</tr><tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="office_archive" />
			</td><td>
				Archive dossiers in an office
			</td>
		</tr>
		
		<!-- THIS IS WHERE THE DEPENDENT EARNS START -->		
		<? if ( $char->getStrength() >= 1000 ) { ?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="nightclub_bouncer" />
			</td><td>
				Work as bouncer at a nightclub
			</td>
		</tr>
		<? } ?>
		<? if ( $char->getDefense() >= 1000 ) { ?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="nightclub_barman" />
			</td><td>
				Work as barman at a nightclub
			</td>
		</tr>
		<? } ?>
		<? if ( $char->getIntellect() >= 1000 ) { ?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="supermarket_stocks" />
			</td><td>
				Order stocks at the supermarket
			</td>
		</tr>
		<? } ?>
		<? if ( $char->getCunning() >= 1000 ) { ?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="callcenter" />
			</td><td>
				Trick people during a callcenter duty
			</td>
		</tr>
		<? } ?>

		<!-- SECOND LOAD OF EARNS -->
		<? if ( $char->getStrength() >= 2000 ) { ?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="brothel_bouncer" />
			</td><td>
				Work as bouncer at a brothel
			</td>
		</tr>
		<? } ?>
		<? if ( $char->getDefense() >= 2000 ) { ?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="brothel_cleaner" />
			</td><td>
				Clean toilets at the local brothel
			</td>
		</tr>
		<? } ?>
		<? if ( $char->getIntellect() >= 2000 ) { ?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="newspaper_red" />
			</td><td>
				Lend a hand at a newspaper redaction
			</td>
		</tr>
		<? } ?>
		<? if ( $char->getCunning() >= 2000 ) { ?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="door_by_door" />
			</td><td>
				Sell products door by door
			</td>
		</tr>
		<? } ?>
		<!-- THIS IS WHERE THEY END -->

		<!-- THIS IS WHERE THE DEGREE/CAREER DEPENDENT EARNS START -->
		<? if ( $char->eco == 3 ) { ?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="bank_trainee" />
			</td><td>
				Work as trainee at the local bank
			</td>
		</tr>
		<? } ?>

		<? if ( $char->med == 3 ) { ?>
		<tr>
			<td style="width: 20px; text-align: left;">
				<input type="radio" name="emp_earn" value="med_trainee" />
			</td><td>
				Work as trainee at the local hospital
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