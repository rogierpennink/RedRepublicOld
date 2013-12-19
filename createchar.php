<?
include_once "includes/utility.inc.php";

if ( isset( $_POST['ajaxrequest'] ) )
{
	if ( $_POST['ajaxrequest'] == "createchar" )
	{
		if ( !user_auth() )
		{
			echo "error::You must be logged in to perform this action.";
			exit;
		}

		// Check for the existence of the city ID and character nickname
		if ( $db->getRowCount( $db->query( "SELECT * FROM locations WHERE id=" . $db->prepval( $_POST['city'] ) ) ) == 0 )
		{
			echo "error::Invalid city chosen, you aren't trying to exploit stuff, are you?";
			exit;
		}
		if ( $db->getRowCount( $db->query( "SELECT * FROM char_characters WHERE nickname=". $db->prepval( $_POST['nickname'] ) ) ) != 0 )
		{
			echo "error::That nickname has already been chosen by someone else!";
			exit;
		}
		if ( $db->getRowCount( $db->query( "SELECT * FROM accounts AS a INNER JOIN char_characters AS c ON c.account_id = a.id WHERE a.id=". $db->prepval( getUserID() ) ) ) != 0 )
		{
			echo "error::You already have a character on your account!";
			exit;
		}
		if ( !ctype_alnum( $_POST['nickname'] ) || strlen( $_POST['nickname'] ) < 3 || strlen( $_POST['nickname'] ) > 25 )
		{
			echo "error::Your nickname should consist of only numbers and letters and be between 3 and 25 characters!";
			exit;
		}
		if ( !ctype_alpha( $_POST['firstname'] ) || strlen( $_POST['firstname'] ) < 3 || strlen( $_POST['firstname'] ) > 25 )
		{
			echo "error::Your first name should consist of only letters and be between 3 and 25 characters!";
			exit;
		}
		if ( !ctype_alpha( $_POST['lastname'] ) || strlen( $_POST['lastname'] ) < 3 || strlen( $_POST['lastname'] ) > 25 )
		{
			echo "error::Your last name should consist of only letters and be between 3 and 25 characters!";
			exit;
		}
		if ( $_POST['gender'] != "v" && $_POST['gender'] != "m" )
		{
			echo "error::Invalid gender format, you aren't trying to exploit stuff, are you?";
			exit;
		}
		if ( !checkdate( $_POST['month'], $_POST['day'], $_POST['year'] ) )
		{
			echo "error::That date does not exist, make sure you choose a valid date combination!";
			exit;
		}
		if ( strlen( $_POST['background'] ) < 20 )
		{
			echo "error::Any character has a background, even yours. A background of at least 20 characters!";
			exit;
		}

		// Calculate the character attributes from the choices that were made
		$int = 0; $str = 0; $def = 0; $cun = 0;

		if ( $_POST['choice1'] == 0 ) $int += 500;
		if ( $_POST['choice1'] == 1 ) $cun += 500;
		if ( $_POST['choice1'] == 2 ) $def += 500;

		if ( $_POST['choice2'] == 0 ) $str += 500;
		if ( $_POST['choice2'] == 1 ) $cun += 500;
		if ( $_POST['choice2'] == 2 ) $int += 500;

		if ( $_POST['choice3'] == 0 ) $def += 500;
		if ( $_POST['choice3'] == 1 ) $int += 500;
		if ( $_POST['choice3'] == 2 ) $str += 500;

		if ( $_POST['choice4'] == 0 ) $def += 500;
		if ( $_POST['choice4'] == 1 ) $cun += 500;
		if ( $_POST['choice4'] == 2 ) $str += 500;

		$stats_id = $timer_id = $char_id = 0;

		// Insert stats and get the record's ID
		if ( $db->query( "INSERT INTO char_stats SET strength=$str, defense=$def, intellect=$int, cunning=$cun" ) === false )
		{
			echo "error::An error occurred on adding your character to the database!";
			exit;
		}

		$stats_id = mysql_insert_id();

		// Create a new record for this char in the timers table
		if ( $db->query( "INSERT INTO char_timers SET earn_timer=0, agg_timer=0, murder_timer=0, online_timer=0" ) === false )
		{
			// Reset the previous query and give an error
			$db->query( "DELETE FROM char_stats WHERE id=". $db->prepval( $stats_id ) );
			echo "error::An error occurred on adding your character to the database!";
			exit;
		}

		$timer_id = mysql_insert_id();

		// Insert a record into the char_characters table
		if ( $db->query( "INSERT INTO char_characters SET account_id=". getUserID() .", nickname=". $db->prepval( $_POST['nickname'] ) .", firstname=". $db->prepval( $_POST['firstname'] ) .", lastname=". $db->prepval( $_POST['lastname'] ) .", gender='". $_POST['gender'] ."', birthdate='". $_POST['year'] ."-". $_POST['month'] ."-". $_POST['day'] ."', creationdate='". date( "Y-m-d", time() ) ."', background=". $db->prepval( addslashes( $_POST['background'] ) ) .", money_dirty=0, money_clean=". mt_rand( 1500, 4500 ) .", homecity=". $_POST['city'] .", location=". $_POST['city'] .", stats_id=$stats_id, timers_id=$timer_id, rank_id=1, health=100, maxhealth=100" ) === false )
		{
			// Reset previous queries and give an error
			$db->query( "DELETE FROM char_stats WHERE id=". $db->prepval( $stats_id ) );
			$db->query( "DELETE FROM char_timers WHERE id=$timer_id" );

			echo "error::An error occurred on adding your character to the database!";
			exit;
		}

		$char_id = mysql_insert_id();		

		$db->query( "INSERT INTO char_equip SET bag=19, char_id=". $char_id );

		echo "success::Your character, '". $_POST['firstname'] ." ". $_POST['lastname'] ."' was successfully created! <a href=\"account.php\">Go back</a>";
	}

	exit;
}

if ( !user_auth() )
{
	$_SESSION['error'] = "You need to be logged in to access those pages.";
	header( "Location: login.php" );
	exit;
}

unset( $_SESSION['ingame'] );

include_once "includes/header.php";
?>
<script type="text/javascript" src="javascript/createchar_js.js"></script>

<h1>Create a new character</h1>
<p>This is the character creation screen. You can only have one character per account and having multiple accounts is not allowed either. Please note that once you have named your character it can no longer be renamed unless it is an inappropriate name in which case your account may be suspended as well. Try to put a bit of thought in your name, it will be your identity as long as your character lives!</p>

<div class="title">
	<div style="margin-left: 10px;">Character Creation Window</div>
</div>
<div class="content">
<form action="createchar.php" method="post" onsubmit="createchar( this ); scroll( 0, 0 );return false;">
	
	<table style="width: 100%;">
		<tr>
			<td colspan="2" style="width: 100%;"><div class="notifydisplay" style="display: block; visibility: visible;">Please enter your character's general information:</div></td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right;"><strong>Nickname:</strong></td>
			<td style="width: 85%;"><input type="text" name="nickname" class="std_input" style="width: 250px; margin-left: 15px;" /></td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right;"><strong>First Name:</strong></td>
			<td style="width: 85%;"><input type="text" name="firstname" class="std_input" style="width: 250px; margin-left: 15px;" /></td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right;"><strong>Last Name:</strong></td>
			<td style="width: 85%;"><input type="text" name="lastname" class="std_input" style="width: 250px; margin-left: 15px;" /></td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right;"><strong>Gender:</strong></td>
			<td style="width: 85%;">
			<select name="gender" class="std_input" style="width: 100px; margin-left: 15px;">
				<option value="m">Male</option>
				<option value="v">Female</option>
			</select>
			</td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right;"><strong>Birthdate:</strong></td>
			<td style="width: 85%;">
			<select name="day" class="std_input" style="width: 40px; margin-left: 15px;">
				<? for ( $i = 1; $i < 32; $i++ ) { ?>
				<option value="<?=$i;?>"><?=$i;?></option>
				<? } ?>
			</select>
			<select name="month" class="std_input" style="width: 40px;">
				<? for ( $i = 1; $i < 13; $i++ ) { ?>
				<option value="<?=$i;?>"><?=$i;?></option>
				<? } ?>
			</select>
			<select name="year" class="std_input" style="width: 70px;">
				<? for ( $i = date( "Y" ) - 65; $i < date( "Y" ) - 15; $i++ ) { ?>
				<option value="<?=$i;?>"><?=$i;?></option>
				<? } ?>
			</select>
			</td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right;"><strong>Homecity:</strong></td>
			<td style="width: 85%;">
			<select name="city" class="std_input" style="width: 250px; margin-left: 15px;">
				<?
				$res = $db->query( "SELECT * FROM locations ORDER BY location_name ASC" );
				while ( $city = $db->fetch_array( $res ) )
				{
					?><option value="<?=$city['id'];?>"><?=$city['location_name'];?></option><?
				}
				?>
			</select>
			</td>
		</tr>

		<tr>
			<td colspan="2" style="width: 100%;"><div class="notifydisplay" style="display: block; visibility: visible;">Please describe your character's background:</div></td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right; vertical-align: top;"><strong>Background:</strong></td>
			<td style="width: 85%; padding-left: 15px;"><textarea name="background" class="std_input" style="width: 100%; min-height: 250px;"></textarea></td>
		</tr>

		<tr>
			<td colspan="2" style="width: 100%;"><div class="notifydisplay" style="display: block; visibility: visible;">Please take this survey to describe your character:</div></td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right; vertical-align: top;"><strong>Question 1:</strong></td>
			<td style="width: 85%;">
				<strong>You see a good friend of yours threaten a colleague. What do you do?</strong><br />
				<table>
				<tr>
				<td><input type="radio" name="choice1" value="0" checked="checked" /></td><td>Report him to the police.</td>
				</tr><tr>
				<td><input type="radio" name="choice1" value="1" /></td><td>Tell his manager about it, he'll know what to do.</td>
				</tr><tr>
				<td><input type="radio" name="choice1" value="2" /></td><td>He's your friend, you don't betray your friends.</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right; vertical-align: top;"><strong>Question 2:</strong></td>
			<td style="width: 85%;">
				<strong>You're late for work and you damage another man's car because you forget to brake. What do you do?</strong><br />
				<table>
				<tr>
				<td><input type="radio" name="choice2" value="0" checked="checked" /></td><td>The road is clear, you're in a hurry, forget about the man's damage.</td>
				</tr><tr>
				<td><input type="radio" name="choice2" value="1" /></td><td>You're in a hurry so you quickly write your phone number down and drive away.</td>
				</tr><tr>
				<td><input type="radio" name="choice2" value="2" /></td><td>You get out, talk to the man politely and arrange a deal with him for the damage.</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right; vertical-align: top;"><strong>Question 3:</strong></td>
			<td style="width: 85%;">
				<strong>On your way to the supermarket a child asks you 50 cent to buy some candy. What do you do?</strong><br />
				<table>
				<tr>
				<td><input type="radio" name="choice3" value="0" checked="checked" /></td><td>You're nice, but not overly. The kid can get it elsewhere.</td>
				</tr><tr>
				<td><input type="radio" name="choice3" value="1" /></td><td>Of course you'll give the kid his 50 cents, you have enough of it and he looks so sad!</td>
				</tr><tr>
				<td><input type="radio" name="choice3" value="2" /></td><td>You tell the kid to grow up and mind his own business.</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right; vertical-align: top;"><strong>Question 4:</strong></td>
			<td style="width: 85%;">
				<strong>A friend got killed by a gang known to be violent, what do you do?</strong><br />
				<table>
				<tr>
				<td><input type="radio" name="choice4" value="0" checked="checked" /></td><td>You inform the police of your suspicions and hope the gangleader gets jailed.</td>
				</tr><tr>
				<td><input type="radio" name="choice4" value="1" /></td><td>You have this friend who's connected with the local mafia, maybe he can arrange something?</td>
				</tr><tr>
				<td><input type="radio" name="choice4" value="2" /></td><td>You don't take any of this shit, you organise a drive-by and kill the bastard!</td>
				</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td colspan="2" style="width: 100%;"><div class="notifydisplay" style="display: block; visibility: visible;">If you're sure about your choices it's time to create your character!</div></td>
		</tr>
		<tr>
			<td style="width: 15%;">&nbsp;</td>
			<td style="width: 85%;"><input type="submit" class="std_input" value="Create Character" /></td>
		</tr>

	</table>

</form>
</div>

<?
include_once "includes/footer.php";
?>