<?
include_once "includes/utility.inc.php";
logout();
$_SESSION['notify'] = "You were successfully logged out from Red Republic.";
header( "Location: index.php" );
?>