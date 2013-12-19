<?
/**
 * The user_list.php file - this file will return all names for the user list, in a
 * formatted way such that the javascript parser will show things correctly.
 */
$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = false;
include_once "utility.inc.php";

// Now we're safe, query for the users
$rec = $db->query( "SELECT DISTINCT c.id, c.nickname, a.rights FROM accounts AS a INNER JOIN char_characters AS c ON c.account_id = a.id INNER JOIN char_timers ON c.timers_id = char_timers.id WHERE online_timer > " . time() );

if ( $db->getRowCount( $rec ) == 0 )
{
	echo "!no_users";
	exit;
}

$output = "!success::";

while ( $res = $db->fetch_array( $rec ) )
{
	$output .= ( (int)$res['rights'] >= USER_MODERATOR ) ? "<strong>" : "";

	$output .= $res['nickname'];

	$output .= ( (int)$res['rights'] >= USER_MODERATOR ) ? "</strong>" : "";

	$output .= "!!" . $rootdir . "/profile.php?id=" . $res['id'] . "::";	
}



// Cut off the last two splitting chars
$output = substr( $output, 0, strlen( $output ) - 2 );

echo $output;
exit;