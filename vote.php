<?
/**
 * vote.php, tally the vote.
 */
include "includes/utility.inc.php";

if ( isset( $_POST['vote'] ) && isset ( $_POST['option'] ) && isset ( $_POST['id'] ) )
{
	$db->query( "INSERT INTO polls_voters (ip, poll) VALUES ('" . $_SERVER["REMOTE_ADDR"] . "', " . $db->prepval( $_POST['id'] ) . ")");
	$db->query( "UPDATE polls_options SET votes=votes+1 WHERE id=" . $db->prepval( $_POST['option'] ) );
	$db->query( "UPDATE polls SET votes=votes+1 WHERE id=" . $db->prepval( $_POST['id'] ) );
}

// Start the real main page
include_once "includes/header.php";
?>

<h1>Thanks for voting!</h1>
<p>Your vote has been tallied and added to the database, thanks for your input!</p>

<?
include_once "includes/footer.php";
?>
