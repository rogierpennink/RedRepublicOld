<?
/*
	Utility.inc.php
*/
if ( $cron ) error_reporting( E_ALL ^ E_WARNING ^ E_NOTICE );
session_start();

$rootdir = "http://localhost/red-republic";

include_once "error.class.php";
global $err;
$err = new ErrorObject();

include_once "defines.inc.php";
include_once "deeddecl.php";
include_once "database.class.php";
include_once "mailer.class.php";
include_once "government.class.php";
include_once "ai.class.php";

$db = getDatabase();
if ( $cron )
	$db->setLogging( true, 0, "/home/roger/public_html/logs/database_log.txt" );
else
	$db->setLogging( true, 0, $_SERVER["DOCUMENT_ROOT"] . "/red-republic/logs/database_log.txt" );	
$db->connect();

$mailer = new Mailer( "Red Republic", "mail@red-republic.com", "mail@red-republic.com" );
if ( $cron )
	$mailer->setLogging( "/home/roger/public_html/logs/mailer_log.txt" );
else
	$mailer->setLogging( $_SERVER["DOCUMENT_ROOT"] . "/red-republic/logs/mailer_log.txt" );

/* Get error_reporting value */
if ( !isset( $_SESSION['debugmode'] ) )
{
	$gset = $db->getRowsFor( "SELECT value FROM settings_general WHERE setting='error_reporting'" );
	error_reporting( $gset['value'] );
}

/* Get shortstr max length (default) */
$sstrlen = $db->getRowsFor( "SELECT value FROM settings_general WHERE setting='shortstrlen'" );
define( 'SHORT_STR_MAXLEN', $sstrlen['value'] );

/* Get used vehicle worth deductable */
$usedworthdeductq = $db->getRowsFor( "SELECT value FROM settings_general WHERE setting='worthdeduct'" );
define( 'VEHICLE_WORTH_DEDUCTABLE', $usedworthdeductq['value'] );

include_once "player.class.php";
include_once "item.class.php";

if ( isset( $_SESSION['ingame'] ) && user_auth() )
{
	$rec = $db->query( "SELECT timers_id FROM char_characters AS c INNER JOIN accounts AS a ON c.account_id = a.id WHERE a.id=" . getUserID() );
	$res = $db->fetch_array( $rec );

	$time = time() + 600;	
	$db->query( "UPDATE char_timers SET online_timer=$time WHERE id=" . $res['timers_id'] );

	/* If the page parameter $CUSTOMCHAR is not set, then create a new Player object ($char). */
	if ( !isset( $char ) && !isset( $CUSTOMCHAR ) ) $char = new Player( getUserID() );

	/* If the page parameter $CUSTOMGOV is not set, then create a new Government object ($gov). */
	if ( !isset( $gov ) && !isset( $CUSTOMGOV ) && isset( $char ) )
	{
		$govchk = $db->query( "SELECT id FROM localgov WHERE location_id=" . $char->location_nr );

		if ( $db->getRowCount( $govchk ) == 0 )
			$db->query( "INSERT INTO localgov (location_id, government_id) VALUES (" . $char->location_nr . ", 0)" );

		$gov = new Government( $char->location_nr );
	}

	/* Close the Party List display so it always looks nice, at first. */
	if ( isset( $_SESSION["show_parties"] ) ) unset( $_SESSION["show_parties"] );

	/* AI Functionality */
	$AI = array();
	if ( !is_readable( "config/ai/chk.dir" ) ) $pre = "../"; else $pre = "";

	/* Loop through available AIs, that are enabled */
	$result = sscandir( $pre . "config/ai/" );
	for ( $i = 0; $i < count( $result ); $i++ )
	{
		/* Skip the chk.dir file. */
		if ( $result[$i] != "chk.dir" )
		{
			$enabled = file_get_contents( $pre . "config/ai/" . $result[$i] . "/enable.ini" );

			/* If AI is enabled ... */
			if ( $enabled == "AI_ENABLED" )
				array_push( $AI, new AI( $pre . "config/ai/" . $result[$i] ) );

			/* ... push: $AI[?] as a new AI. */
		}
	}

	/* AI Implementation */
	for ( $i = 0; $i < count( $AI ); $i++ )
	{
		/* Call AI's result function. */
		$AI[$i]->result();
	}

	/* All other functionality and implementations for an AI
	 * should be appended to the AI class, and ->result() function.
	 */
}

if ( isset( $AUTH_LEVEL ) )
{
	if ( !user_auth() )
	{
		$_SESSION['error'] = "Your session has expired, please login again."; 
		if ( $REDIRECT == true ) { header( "Location: $rootdir/login.php" ); }
		else echo "error::notloggedin";
		exit;
	}
	else
	{
		if ( getUserRights() < $AUTH_LEVEL )
		{
			$_SESSION['error'] = "You tried to access a page that you didn't have access to! Please log in again."; 
			if ( $REDIRECT == true ) { header( "Location: $rootdir/login.php" ); }
			else { echo "error::notloggedin"; }
			exit;
		}
	}
}

if ( isset( $CHARNEEDED ) && $CHARNEEDED == true && !hasCharacter( getUserID() ) )
{
	$_SESSION['error'] = "You need to have a character to reach that page!";
	header( "Location: $rootdir/account.php" );
	exit;
}

if ( isset( $OCCNEEDED ) && is_numeric( $OCCNEEDED ) )
{
	if ( $db->getRowCount( $db->query( "SELECT * FROM char_characters AS c INNER JOIN char_ranks AS r ON c.rank_id=r.id WHERE r.occupation_id=$OCCNEEDED AND account_id=". getUserID() ) ) == 0 )
	{
		header( "Location: $rootdir/main.php" );
		exit;
	}
}

/* Something to put inside an empty bracket */
function DoNothing()
{
	return null;
}

function getDatabase()
{
	global $cron;

	// Construct the filename
	if ( $cron )
		$dbfile = "/home/roger/public_html/config/dbase.conf";
	else
		$dbfile = $_SERVER["DOCUMENT_ROOT"] . "/red-republic/config/dbase.conf";	
	
	// Read the database values from a configuration file
	if ( !file_exists( $dbfile ) )
	{
		$_SESSION["error"] = "Database configuration was not found, out of precaution no database connections are allowed.";
		return false;
	}
	else
	{
		if ( ( $fh = fopen( $dbfile, "r" ) ) === false )
		{
			$_SESSION["error"] = "Could not open database configuration file, no database connections will be allowed.";
			return false;
		}
		else
		{
			if ( ( $val = fread( $fh, filesize( $dbfile ) ) ) === false )
			{
				$_SESSION["error"] = "Could not read database configuration file, no database connections will be allowed.";
				return false;
			}
			else
			{					
				$parts = explode( "::", $val );
				$host = trim( $parts[0] );
				$user = trim( $parts[1] );
				$pass = trim( $parts[2] );
				$db = trim( $parts[3] );

				$dbase = new Database( $host, $user, $pass, $db );					

				// Close file before returning
				fclose( $fh );
				
				return $dbase;
			}
		}		
	}
}

/**
 * A more secure fashion  and alternative way of getting the database.
 * This gets a string ( $DBaseString ) from config/dbase.php (<- .php).
 * If this function is used, then the file config/dbase.php (<- .php not .conf!)
 * needs to be created, with the following content.
 **
 * <- START PHP TAG -> $DBaseString = "localhost::root::password::redrepublic"; <-END PHP TAG->
 */
if ( !function_exists( getDatabase ) ) // Safe Guard
{
	function getDatabase()
	{
		global $cron;

		// Filenames
		if ( $cron )
			$dbfile = "home/roger/public_html/config/dbase.php";
		else
			$dbfile = $_SERVER["DOCUMENT_ROOT"] . "/red-republic/config/dbase.php";

		// File Checking
		if ( !file_exists( $dbfile ) )
		{
			$_SESSION["error"] = "Database configuration was not found, out of precaution no database connections are allowed.";
			return false;
		} 
		else
		{
			include $dbfile;

			if ( !isset( $DBaseString ) ) 
			{
				$_SESSION["error"] = "Database configuration string missing, no database connections are allowed.";
				return false;
			}

			// Variables
			$DBaseString = explode( "::", $DBaseString );

			$host = trim( $DBaseString[0] );
			$user = trim( $DBaseString[1] );
			$pass = trim( $DBaseString[2] );
			$db = trim( $DBaseString[3] );

			$dbase = new Database( $host, $user, $pass, $db );

			return $dbase;
		}
	}
}

/**
 * The user_auth function will return a boolean value based on whether the user is logged in or not. This
 * function also allows the user to pass a username and password to be checked for validness.
 * @param username Optional parameter that will be checked for validness along with password
 * @param password Optional parameter that will be checked for validness along with username
 * @return A boolean indicating if the user's logged in or not
 * @author Rogier Pennink
 */
function user_auth( $username = "", $password = "" )
{
	// Make $db accessible
	global $db;

	// If one of the vars is not given, assume we need to check sessions
	if ( $username == "" || $password == "" )
	{
		$query = $db->query( "SELECT * FROM accounts WHERE username=" . $db->prepval( $_SESSION['username'] ) ." AND password=" . $db->prepval( $_SESSION['password'] ) );

		if ( $db->getRowCount( $query ) == 0 )
		{
			unset( $_SESSION['username'] );
			unset( $_SESSION['password'] );

			return false;
		}
		
		// Update the session vars just to be sure
		$result = $db->fetch_array( $query );

		$_SESSION['username'] = $result['username'];
		$_SESSION['password'] = $result['password'];

		// Update last activity field
		$db->query( "UPDATE accounts SET last_active=" .$db->prepval( time() ). " WHERE username=" .$db->prepval( $result['username'] ) );

		return true;
	}
	else
	{
		$query = $db->query( "SELECT * FROM accounts WHERE username=" . $db->prepval( $username ) ." AND password=" . $db->prepval( md5( $password ) ) );

		if ( $db->getRowCount( $query ) == 0 )
		{
			unset( $_SESSION['username'] );		// Just in case
			unset( $_SESSION['password'] );		// Just in case

			return false;
		}

		$result = $db->fetch_array( $query );

		$_SESSION['username'] = $result['username'];
		$_SESSION['password'] = $result['password'];

		// Update last activity field
		$db->query( "UPDATE accounts SET last_active=" .$db->prepval( time() ). " WHERE username=" .$db->prepval( $result['username'] ) );

		return true;
	}
}

/**
 * The getUserRights function will use either the $_SESSION['username'] value, or the
 * $user paramater to return the rights that a certain user has.
 * @param user Optional user parameter.
 * @return The rights the user has.
 * @author Rogier Pennink.
 */
function getUserRights( $user = "" )
{
	global $db;

	if ( !user_auth() ) return false;

	if ( $user == "" ) $usr = $_SESSION['username'];
	else $usr = $user;

	$res = $db->query( "SELECT rights FROM accounts WHERE username=". $db->prepval( $usr ) );

	if ( $db->getRowCount( $res ) == 0 ) return false;

	$arr = $db->fetch_array( $res );

	return $arr['rights'];
}

function setUserRights( $user = "", $level = 0 )
{
	global $db;

	if ( !user_auth() ) return false;

	if ( $user == "" ) $usr = $_SESSION['username'];
	else $usr = $user;

	$res = $db->query( "UPDATE accounts SET rights=" . $db->prepval( $level ) . " WHERE username=" . $db->prepval( $usr ) );

	if ( !$res ) return false;

	return true;
}

/**
 * The getUserID function will use either the $_SESSION['username'] value, or the
 * $user paramater to return the ID that belongs to a certain user.
 * @param user Optional user parameter.
 * @return The ID associated with a user
 * @author Rogier Pennink.
 */
function getUserID( $user = "" )
{
	global $db;

	if ( $user == "" && !user_auth() ) return false;

	if ( $user == "" ) $usr = $_SESSION['username'];
	else $usr = $user;

	$res = $db->query( "SELECT * FROM accounts WHERE username=". $db->prepval( $usr ) );

	if ( $db->getRowCount( $res ) == 0 ) return false;

	$arr = $db->fetch_array( $res );

	return $arr['id'];
}

function getCharacterID( $actid = "" )
{
	global $db;

	if ( $actid == "" )
	{
		if ( !user_auth() ) return false;
		$usr = getUserID();
	}
	else $usr = $actid;

	$res = $db->query( "SELECT * FROM char_characters WHERE account_id=". $usr );

	if ( $db->getRowCount( $res ) == 0 ) return false;

	$arr = $db->fetch_array( $res );

	return $arr['id'];
}

function getCharacterIDFromNickname( $nickname = "" )
{
	global $db;

	$res = $db->query( "SELECT id FROM char_characters WHERE nickname=" . $db->prepval( $nickname ) );

	if ( $db->getRowCount( $res ) == 0 ) return false;

	$arr = $db->fetch_array( $res );

	return $arr["id"];
}

function getCharNickname( $id )
{
	global $db;

	if ( !is_numeric( $id ) ) return false;

	$res = $db->query( "SELECT * FROM char_characters WHERE id=" . $id );

	if ( $db->getRowCount( $res ) == 0 ) return "(unknown)";

	$arr = $db->fetch_array( $res );

	return $arr['nickname'];
}

function getAccountID( $char_id )
{
	global $db;

	if ( !is_numeric( $char_id ) ) return false;

	$res = $db->query( "SELECT account_id FROM char_characters WHERE id=" . $char_id );

	if ( $db->getRowCount( $res ) == 0 ) return false;

	$arr = $db->fetch_array( $res );

	return $arra["account_id"];
}

/* charExists checks if a character with the specified value, as the clause field ("username" is "someone", or 34 is "id") 
 * @return int 0 if the character does not exist, 1 if the character does exist, and -1 if multiple characters exist. 
 */
function charExists( $value, $sql_field_clause = "nickname" )
{
	global $db;

	$result = $db->getRowCount( $db->query( "SELECT " . $sql_field_clause . " FROM char_characters WHERE " . $sql_field_clause . "=" . $db->prepval( $value ) ) );

	if ( $result == 0 ) return 0;
	elseif ( $result == 1 ) return 1;
	elseif ( $result > 1 ) return -1;
}

/**
 * The getCharacterInfo function returns an array that contains ALL information there is about a character.
 * This function is necessary because we want to abstract the process of getting the right column indexes
 * from any future character fetching. If something gets changed in the database, this'll be the only place
 * where changes will have to be made.
 * @param user_id The ID of the account to from which we want character info.
 * @return An array with character information.
 * @author Rogier Pennink.
 */
function getCharacterInfo( $user_id, $acc_id = true )
{
	global $db;

	// Construct the SQL query, spreading it over multiple lines for the purpose of better
	// readability <3.
	$sql = "SELECT * FROM accounts AS a ";
	$sql .= "INNER JOIN char_characters AS c ON c.account_id = a.id ";
	$sql .= "INNER JOIN char_equip AS e ON e.char_id = c.id ";
	$sql .= "INNER JOIN char_stats AS s ON c.stats_id = s.id ";	
	$sql .= "INNER JOIN char_timers AS t ON c.timers_id = t.id ";
	$sql .= "INNER JOIN char_ranks AS r ON c.rank_id = r.id ";
	$sql .= "INNER JOIN char_occupations AS o ON r.occupation_id = o.id ";
	if ( $acc_id == true ) $sql .= "WHERE a.id = ". $db->prepval( $user_id );
	else $sql .= "WHERE c.id = ". $db->prepval( $user_id );

	// Query
	$record = $db->query( $sql );

	if ( $db->getRowCount( $record ) == 0 ) return false;

	$result = $db->fetch_array( $record );

	/* Homecity information. */
	$sql = "SELECT * FROM locations WHERE id=". $result['homecity'];
	$homecity = $db->fetch_array( $db->query( $sql ) );

	/* Location information. */
	$sql = "SELECT * FROM locations WHERE id=". $result['location'];
	$location = $db->fetch_array( $db->query( $sql ) );

	/* Degree information. */
	$sql = "SELECT * FROM char_degrees WHERE character_id=". $result['char_id'];
	$degq = $db->query( $sql );
	
	$return = array( "account_id" => $result[0],
					 "username" => $result['username'],
					 "password" => $result['password'],
					 "character_id" => $result['char_id'],
					 "rights" => $result['rights'],
					 "account_last_active" => $result['last_active'],
					 "nickname" => $result['nickname'],
					 "firstname" => $result['firstname'],
					 "lastname" => $result['lastname'],
					 "gender" => $result['gender'],
					 "gender_full" => ( $result['gender'] == "m" ) ? "Male" : "Female",
					 "birthdate" => $result['birthdate'],
					 "creationdate" => $result['creationdate'],
					 "background" => $result['background'],
					 "money_clean" => $result['money_clean'],
					 "money_dirty" => $result['money_dirty'],
					 "homecity_nr" => $result['homecity'],
					 "homecity" => $homecity['location_name'],
					 "location_nr" => $result['location'],
					 "location" => $location['location_name'],
					 "strength" => $result['strength'],
					 "defense" => $result['defense'],
					 "intellect" => $result['intellect'],
					 "cunning" => $result['cunning'],
					 "criminal_exp" => $result['criminal_exp'],
					 "bank_exp" => $result['bank_exp'],
					 "hospital_exp" => $result['hospital_exp'],
					 "bank_trust" => $result['bank_trust'],
					 "earn_timer" => $result['earn_timer'],
					 "earn_timer2" => $result['earn_timer2'],
					 "study_timer" => $result['study_timer'],
					 "agg_timer" => $result['agg_timer'],
					 "agg_timer2" => $result['agg_timer2'],
					 "murder_timer" => $result['murder_timer'],
					 "online_timer" => $result['online_timer'],
					 "agg_pro" => $result['agg_pro'],
					 "kill_pro" => $result['kill_pro'],
					 "stats_id" => $result['stats_id'],
					 "timers_id" => $result['timers_id'],
					 "health" => $result['health'],
					 "maxhealth" => $result['maxhealth'],
					 "rank" => $result['rank_name'],
					 "rank_nr" => $result['rank_id'],
					 "rank_index" => $result['order_index'],
					 "occupation" => $result['occ_name'],
					 "occupation_nr" => $result['occupation_id'],
					 "work_url" => $result['work_url'],
					 "avatar_url" => $result['rank_image'],
					 "armor_head" => $result['armor_head'],
					 "armor_chest" => $result['armor_chest'],
					 "armor_legs" => $result['armor_legs'],
					 "armor_feet" => $result['armor_feet'],
					 "main_weapon" => $result['main_weapon'],
					 "carries_second_weapon" => $result['weap2_open'],
					 "second_weapon" => $result['second_weapon'],
					 "bag" => $result['bag']
					);
	
	while ( $degree = $db->fetch_array( $degq ) )
			$return[$degree['degree_type']] = $degree['degree_status'];


	return $return;					 
}

function hasCharacter( $id )
{
	global $db;

	if ( $db->getRowCount( $db->query( "SELECT * FROM accounts a INNER JOIN char_characters c ON c.account_id = a.id WHERE a.id=". $db->prepval( $id ) ) ) == 0 ) return false;

	return true;
}

function logout()
{
	unset( $_SESSION['username'] );
	unset( $_SESSION['password'] );
}

/**
 * The timeDisplay function will return a formatted string for use with the date function. This function
 * should always be called in order to ensure correctness of the date display.
 * @return A formatted string for use with the date function.
 * @author Rogier Pennink
 */
function timeDisplay()
{
	global $db;

	$query = $db->query( "SELECT * FROM settings_general WHERE setting='timedisplay'" );

	if ( $db->getRowCount( $query ) == 0 || $query === false )
		return "d/m/Y H:i:s";
	else
	{
		$arr = $db->fetch_array( $query );
		return $arr['value'];
	}
}

/* Alias */
define( "TIME_FORMAT", timeDisplay() );

function dateDisplay()
{
	global $db;

	$query = $db->query( "SELECT * FROM settings_general WHERE setting='datedisplay'" );

	if ( $db->getRowCount( $query ) == 0 || $query === false )
		return "d/m/Y";
	else
	{
		$arr = $db->fetch_array( $query );
		return $arr['value'];
	}
}

/* Alias */
define( "DATE_FORMAT", dateDisplay() );

/**
 * The getSiteNews function will return an array with all necessary information for displaying
 * news on the front page of the website.
 * @param amount The amount of news boxes that should be shown.
 * @param order The order in which to list them
 * @return An array containing the information that is necessary to list the news
 * @author Rogier Pennink
 */
function getSiteNews( $amount = 5, $order = "DESC" )
{
	global $db;

	$query = $db->query( "SELECT * FROM frontpage_news ORDER BY news_date ". $order ." LIMIT 0,". $amount );

	if ( $db->getRowCount( $query ) == 0 )
	{		
		$array[0] = array( "subject" => "No news available", "date" => date( timeDisplay(), time() ), "author" => false, "message" => "There is currently no news available." );

		return $array;
	}
	else
	{
		$i = 0;
		while ( $arr = $db->fetch_array( $query ) )
		{
			// Fetch the number of comments for this article
			$comments = $db->getRowCount( $db->query( "SELECT * FROM frontpage_comments WHERE news_id = ".$db->prepval( $arr['id'] ) ) );
			$array[$i] = array ( "id" => $arr['id'], "subject" => stripslashes( $arr['news_subject'] ), "date" => date( timeDisplay(), strtotime( $arr['news_date'] ) ), "author" => stripslashes( $arr['news_author'] ), "message" => str_replace( Chr(13), "<br />", stripslashes( $arr['news_message'] ) ), "numcomments" => $comments );
			$i++;
		}

		return $array;
	}
}

/**
 * The check_email_address is a pretty extensive function that will check if an email is correct. First 
 * it will check if an email address adheres to the general standards ( text@text.ext ), but it also
 * checks if the hostname part is an IP, and if it is valid...
 * @param email The email address to be checked.
 * @return A boolean that indicates if the e-mail address is valid or not
 * @author Rogier Pennink
 */
function check_email_address( $email ) 
{
	// First, we check that there's one @ symbol, and that the lengths are right
	if ( !ereg( "^[^@]{1,64}@[^@]{1,255}$", $email ) ) 
	{
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	
	// Split it into sections to make life easier
	$email_array = explode( "@", $email );
	$local_array = explode( ".", $email_array[0] );

	for ( $i = 0; $i < sizeof( $local_array ); $i++ ) 
	{
		if ( !ereg( "^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i] ) ) 
		{
			return false;
		}
	}  
	
	if ( !ereg( "^\[?[0-9\.]+\]?$", $email_array[1] ) ) 
	{ 
		// Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if ( sizeof( $domain_array ) < 2 ) 
		{
			return false; // Not enough parts to domain
		}
		
		for ( $i = 0; $i < sizeof( $domain_array ); $i++ ) 
		{
			if ( !ereg( "^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i] ) ) 
			{
				return false;
			}
		}
	}

	return true;
}

/**
 * The getFromInventory function retrieves all items in the inventory, and returns a standard
 * sized array of 20 items to fit in the inventory display.
 * @return Array of 20 elements
 * @author Rogier Pennink
 */
function getFromInventory( $tradable = false )
{
	global $db;

	if ( $tradable )
		$rec = $db->query( "SELECT * FROM char_inventory INNER JOIN items ON char_inventory.item_id = items.item_id INNER JOIN icons ON items.icon = icons.icon_id WHERE tradable=1 AND char_id=". getCharacterID() );
	else
		$rec = $db->query( "SELECT * FROM char_inventory INNER JOIN items ON char_inventory.item_id = items.item_id INNER JOIN icons ON items.icon = icons.icon_id WHERE char_id=". getCharacterID() );

	$count = 0;
	$arr = array();

	while ( $res = $db->fetch_array( $rec ) )
	{
		$arr[$count]['id'] = $res['item_id'];
		$arr[$count]['name'] = $res['name'];
		$arr[$count]['equipable'] = $res['equipable'];
		$arr[$count++]['icon'] = $res['url'];
	}

	return $arr;
}

/**
 * The getBusinessTypeString method converts a business type integer into a string representing
 * that business type in a more readable way.
 */
function getBusinessTypeString( $type )
{
	switch ( $type )
	{
		case 0: return "Tavern";
		case 1: return "Police Station";
		case 2: return "Clothing Shop";
		case 3: return "Auction House";
		case 4: return "Garage";
		case 5: return "University";
		case 6: return "Bank";
		case 7: return "Weapon Shop";
		case 8: return "Race Track";
		case 9: return "Real Estate Agency";
		case 10: return "Town Hall";
		case 11: return "Hotel";
		default: return "Unknown Business";
	}
}

/**
 * This function translates a vehicle's health (int) to
 * a descriptive string.
 */
function getVehicleHealthString( $health, $caps = true )
{
	if ( $health < 1 ) $str = "Totally Destroyed";
	if ( $health > 0 && $health < 31 ) $str = "A Hunk of Junk";
	if ( $health > 30 && $health < 51 ) $str = "In Bad Shape";
	if ( $health > 50 && $health < 71 ) $str = "Somewhat Dinged";
	if ( $health > 70 ) $str = "In Good Shape";

	if ( $caps )
		return ucwords( $str );
	else
		return strtolower( $str );
}

/**
 * The getBankAccountTypeString method converts a bank account type integer into a string representing
 * that bank account type in a more readable way.
 */
function getBankAccountTypeString( $type )
{
	switch ( $type )
	{
		case 0: return "Checking Account";
		case 1: return "Savings Account";
		case 2: return "Investment Account";
		default: return "Unknown Account";
	}
}

function getBankAccountStatusString( $type )
{
	switch ( $type )
	{
		case 0: return "Pending Approval";
		case 1: return "Account Frozen";
		case 2: return "Valid Account";
		case 10: return "Possible Fraud";
		case 11: return "Probably Fraudless";
	}
}

function getTransactionTypeString( $type )
{
	switch ( $type )
	{
		case 0: return "Deposit";
		case 1: return "Withdrawal";
		case 2: return "Transfer to";
		case 3: return "Transfer from";
		case 4: return "Direct Debit to";
		case 5: return "Transaction Server Hacked";
		default: return "Unknown Transaction";
	}
}

function getMaxLoanAmount( $trust )
{
	$trust = $trust < 0 ? 0 : $trust;
	$max = 10000 + $trust * 5000;
	return ( $max > 1E6 ? 1E6 : $max );
}

function getLoanStatusString( $str )
{
	switch ( $str )
	{
		case 0: return "New Loanrequest";
		case 1: return "Active Loan";
		case 10: return "Archived Loan";
	}
}

function getHotelTypeString( $int )
{
	switch ( $int )
	{
		case 0: return "Normal Room";
		case 1: return "Luxury Room";
		default: return "Unknown Type";
	}
}

/**
 * The scandir function scans a directory for files and returns these in an array.
 * @param dir The directory to scan
 * @return An array with the files in specified dir
 */
function sscandir( $dir ) 
{
	$dirArray = array();

	if ( $handle = opendir( $dir ) ) 
	{
		while ( false !== ( $file = readdir( $handle ) ) ) 
		{
			if ( $file != "." && $file != ".." ) 
			{
				if( is_dir( $file ) ) { continue; } 
			
				array_push( $dirArray, basename( $file ) );
			}
		}
		closedir( $handle );
	}
	return $dirArray;
}

/**
 * shortstr cuts the end of a lengthy string off, and adds a tail.
 * @param text A string to cut.
 * @param length The maximum characters to allow before cutoff.
 * @param tail A string beeing the tail sequence (...)
 * @return A modified version of `text`.
 */
function shortstr( $text, $length = SHORT_STR_MAXLEN, $tail = "..." ) 
{
    $text = trim( $text );
    $txtl = strlen( $text );
    if( $txtl > $length ) {
        for( $i = 1; $text[$length-$i] != " "; $i++ ) {
            if( $i == $length ) {
                return substr( $text, 0, $length ) . $tail;
            }
        }
        $text = substr( $text, 0, $length-$i+1 ) . $tail;
    }
    return $text;
}

/* 1 Percent chance to drop a trivial item... */
function dropRandomTrivialItem( $char )
{
	global $db;

	$bag = new Item( $char->bag );
	$rand = mt_rand( 1, 100 );
	if ( $rand == 50 )
	{
		/* Select an item to drop. */
		$result = $db->query( "SELECT * FROM items WHERE tier>=". ( $char->getTier() - 1 ) ." AND tier<=". ( $char->getTier() - 1 ) ." AND quality=0 AND name<>'Unknown' ORDER BY RAND() LIMIT 1" );

		/* Check the amount of space in inventory. */
		$num = $db->getRowCount( $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id ) );

		/* Only if there is space, continue. */
		if ( $num < $bag->bagslots )
		{
			$item = $db->fetch_array( $result );

			/* Insert the item and let the user know... */
			if ( $db->query( "INSERT INTO char_inventory SET item_id=". $item['item_id'] .", char_id=". $char->character_id ) !== false )
			{
				return "While strolling along the streets, on your way back from work, you found a ". $item['name'] ."!";
			}
		}
	}

	return "";
}

/* 0.1 Percent chance to drop an unusual item... */
function dropRandomUnusualItem( $char )
{
	global $db;

	$bag = new Item( $char->bag );
	$rand = mt_rand( 1, 1000 );
	if ( $rand == 500 )
	{
		/* Select an item to drop. */
		$result = $db->query( "SELECT * FROM items WHERE tier>=". ( $char->getTier() - 1 ) ." AND tier<=". ( $char->getTier() - 1 ) ." AND quality=1 AND name<>'Unknown' ORDER BY RAND() LIMIT 1" );

		/* Check the amount of space in inventory. */
		$num = $db->getRowCount( $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id ) );

		/* Only if there is space, continue. */
		if ( $num < $bag->bagslots )
		{
			$item = $db->fetch_array( $result );

			/* Insert the item and let the user know... */
			if ( $db->query( "INSERT INTO char_inventory SET item_id=". $item['item_id'] .", char_id=". $char->character_id ) !== false )
			{
				return "While strolling along the streets, on your way back from work, you found a ". $item['name'] ."!";
			}
		}
	}

	return "";
}

/* 0.05 Percent chance to drop a special item... */
function dropRandomSpecialItem( $char )
{
	global $db;

	$bag = new Item( $char->bag );
	$rand = mt_rand( 1, 5000 );
	if ( $rand == 2500 )
	{
		/* Select an item to drop. */
		$result = $db->query( "SELECT * FROM items WHERE tier>=". ( $char->getTier() - 1 ) ." AND tier<=". ( $char->getTier() - 1 ) ." AND quality=2 AND name<>'Unknown' ORDER BY RAND() LIMIT 1" );

		/* Check the amount of space in inventory. */
		$num = $db->getRowCount( $db->query( "SELECT * FROM char_inventory WHERE char_id=". $char->character_id ) );

		/* Only if there is space, continue. */
		if ( $num < $bag->bagslots )
		{
			$item = $db->fetch_array( $result );

			/* Insert the item and let the user know... */
			if ( $db->query( "INSERT INTO char_inventory SET item_id=". $item['item_id'] .", char_id=". $char->character_id ) !== false )
			{
				return "While strolling along the streets, on your way back from work, you found a ". $item['name'] ."!";
			}
		}
	}

	return "";
}

/* Character has any Deeds */
function hasPersonality( $id )
{
	global $db;

	$result = $db->query( "SELECT * FROM char_deeds WHERE char_id=" . $db->prepval( $id ) );
	
	/* Character doesn't even have a row yet. */
	if ( $db->getRowCount( $result ) == 0 ) return false;
	
	$chardeeds = $db->fetch_array( $result );

	/* Query Construction */
	if ( $id == getCharacterID() )
		$query = "SELECT * FROM personalities";
	else
		$query = "SELECT * FROM personalities WHERE secret='false'";

	/* Check for any deeds atained. */
	$resultb = $db->query( $query );
	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] ) return true;
	}

	return false;
}

/* Check if character has a specific deed.
 * @param char_id int ID of the character to check
 * @param deed_id int ID of deed in database
 * @return bool If the character has the deed, true, if not, false.
 */
function hasDeed( $char_id, $deed_id )
{
	global $db;

	/* Check for Deed */
	$deedquery = $db->query( "SELECT * FROM personalities WHERE id=" . $deed_id );

	/* Deed doesn't exist, how can they have it? */
	if ( $db->getRowCount( $deedquery ) == 0 ) return false;

	$deed = $db->fetch_array( $deedquery );
	
	/* Check to see if character has gained it */
	$charquery = $db->query( "SELECT * FROM char_deeds WHERE " . $deed['slot_required'] . ">=" . $deed['value_required'] );

	/* If gained, return true. If not, return false. */
	if ( $db->getRowCount( $charquery ) > 0 )
		return true;
	else
		return false;
}

/* Get deeds for character from database.
 * @param id int Id of character's deeds to get (char id)
 * @param print bool True to print, false to return html string
 * @return bool false on failure, html string on success.
 */
function getDeeds( $id, $print = false )
{
	/* Construction */
	global $db;
	$string = "";
	$doorstop = false;

	/* Deed Query */
	$result = $db->query( "SELECT * FROM char_deeds WHERE char_id=" . $db->prepval( $id ) );
	
	/* Character doesn't have a row yet. */
	if ( $db->getRowCount( $result ) == 0 ) return false;

	$chardeeds = $db->fetch_array( $result );
	
	/* slot_a */
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_a' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_a' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_b */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_b' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_b' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_c */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_c' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_c' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_d */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_d' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_d' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_e */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_e' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_e' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_f */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_f' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_f' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_g */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_g' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_g' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_h */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_h' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_h' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_i */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_i' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_i' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_j */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_j' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_j' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_k */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_k' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_k' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_l */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_l' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_l' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_m */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_m' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_m' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_n */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_n' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_n' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_o */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_o' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_o' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	/* slot_p */
	$doorstop = false;
	if ( $id == getCharacterID() )
	{
		$query = "SELECT * FROM personalities WHERE slot_required='slot_p' ORDER BY value_required DESC";
	} else {
		$query = "SELECT * FROM personalities WHERE slot_required='slot_p' AND secret='false' ORDER BY value_required DESC";
	}

	$resultb = $db->query( $query );

	while ( $row = $db->fetch_array( $resultb ) )
	{
		if ( $chardeeds[$row['slot_required']] >= $row['value_required'] && !$doorstop )
		{
			/* Char has deed */
			$string .= "<div style='width: 400px;'><b>" . $row['name'] . "</b><br />" . $row['description'] . "<br /><br /></div>";
			$doorstop = true;
		}
	}

	if ( $print ) print( $string );
	return $string;
}

/* Function to clear RR Cookies */
function cleanCookies()
{
	unset( $_COOKIE['redrepublic'] ); 
	return;
}


/* Simple set of functions to simulate easy data storage */
function saveCookie( $name, $value )
{
	if ( !isset( $_COOKIE['redrepublic'][$name] ) )
	{
		setcookie( "redrepublic[" . $name . "]", $value, time()+60*60*24*100  );
		return true;
	}

	$_COOKIE['redrepublic[$name]'] = $value;
	return true;
}

function loadCookie( $name, $value )
{
	if ( isset( $_COOKIE['redrepublic[' . $name . ']'] ) )
		return $_COOKIE['redrepublic[' . $name . ']'];
	else
		return false;
}

function clearCookie( $name )
{
	if ( isset( $_COOKIE['redrepublic[' . $name . ']'] ) )
	{
		unset( $_COOKIE['redrepublic[' . $name . ']'] );
		return true;
	} else return false;
}

/* Get a valour icon for admins. */
function rightsImage( $userrights )
{
	if ( !$userrights ) return false;
	return "valour_" . $userrights . ".png";
}

function time_diff( $time0, $time1 )
{
	$diff = abs( $time0 - $time1 );

	$days = floor( $diff / 86400 );
	$diff = $diff % 86400;

	$hours = floor( $diff / 3600 );
	$diff = $diff % 3600;

	$minutes = floor( $diff / 60 );
	$diff = $diff % 60;

	$seconds = $diff;

	return array( "days" => $days, "hours" => $hours, "minutes" => $minutes, "seconds" => $seconds );
}

/* This function adds a message to a tavern bar. 
 * @param location_id int The location id (player->location_nr) of the tavern.
 * @param character_id int The character id (player->character_id) of the message author.
 * @param message_text string The text of the message.
 * @param message_type int The type of message (0 for default, 1 for action).
 * @return bool True/false success/failure.
 */

function addTavernMessage( $location_id, $character_id, $message_text, $message_type = 0 )
{
	if ( !is_numeric( $location_id ) || !is_numeric( $character_id ) || !is_numeric( $message_type ) || !is_string( $message_text ) )
		return false;

	global $db;

	$result = $db->query( "INSERT INTO taverns_messages (id, date, message, char_id, location_id, type) VALUES ('', CURDATE(), " . $db->prepval( $message_text ) . ", " . $db->prepval( $character_id ) . ", " . $db->prepval( $location_id ) . ", " . $db->prepval( $message_type ) . ")" );

	if ( !$result )
		return false;
	else
		return true;
}

/* Requires the path to the emoticons text file. */
function getEmoticonArrays( $bool = true )
{
	$havehad = array();
	$numhavehad = 0;
	$emoarray = array();
	$emoarray[0] = array();
	$emoarray[1] = array();
	
	$file = file_get_contents( "../images/emoticons/emoticons.txt" );

	$line = explode( "\n", $file );

	for ( $i = 0; $i < count( $line ); $i++ )
	{
		$check = false;
		$split = explode( "::", $line[$i] );
		if ( !$bool )
			for ( $k = 0; $k < count( $havehad ); $k++ )
			{
				if ( $havehad[$k] == $split[1] )
				{
					$check = true;
					$numhavehad++;
					break;
				}
			}

		if ( $bool || !$check ) $emoarray[0][$i-$numhavehad] = $split[0];
		if ( $bool || !$check ) $emoarray[1][$i-$numhavehad] = $split[1];

		$havehad[count($havehad)] = $emoarray[1][$i-$numhavehad];
	}

	return $emoarray;
}

/* This function adds a message to the admin log
 **
 * Only for use in the /admin/ directory.
 */
function adminLog( $username, $text )
{
	$res = true;

	/* Check for existance, if not, create file. */
	if ( !file_exists( "../logs/admin_log.txt" ) )
	{
		$fp = fopen( "../logs/admin_log.txt", "w" );
		fclose( $fp );
	}

	/* Open and write text */
	$fp = fopen( "../logs/admin_log.txt", "a" );

	if ( !$fp ) $_SESSION["error"] = "Cannot open log file for writing through function, adminLog.";

	fwrite( $fp, $username . "::" . $text . "::" . date( TIME_FORMAT, time() ) . "\n" );
	fclose( $fp );

	return $res;
}

/* Return the value of a $field in the locations database, from the location id */
function getLocationInfo( $location_id, $field_name = "id" )
{
	global $db;

	$res = $db->query( "SELECT " . $field_name . " FROM locations WHERE id=" . $location_id );

	if ( $db->getRowCount( $res ) == 0 ) return false;

	$array = $db->fetch_array( $res );

	return $array[$field_name];
}

?>
