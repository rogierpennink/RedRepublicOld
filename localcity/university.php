<?
$nav = "localcity";

$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = true;
$_SESSION['ingame'] = true;
include_once "../includes/utility.inc.php";
$char = new Player( getUserID() );

/* Check if this city has a university at all! */
$q = $db->query( "SELECT * FROM localcity INNER JOIN businesses ON localcity.business_id=businesses.id WHERE business_type=5 AND location_id=". $db->prepval( $char->location_nr ) );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This city does not have a university you can go to!";
	header( "Location: index.php" );
	exit;
}
$business = $db->fetch_array( $q );

include_once "../includes/header.php";
?>

<h1>The <?=$char->location;?> University</h1>
<p>'Welcome to the University of <?=$char->location;?>' a small sign states as you enter through the oak front doors of <?=$char->location;?>'s university. You are in a large hall and students walk hurriedly from and to their courses. As you inquire the lady at the help-desk, asking for courses you can subscribe to, you get a nice feeling and think of how much more you'd be able to do with a university degree!</p>

<div style="width: 90%; margin-left: auto; margin-right: auto;">
	<div class="title" style="padding-left: 10px;">
		Courses at the University of <?=$char->location;?>
	</div>
	<div class="content">
	<?
	$q = $db->query( "SELECT * FROM university_faculties AS l INNER JOIN university_faculty AS f ON l.faculty_id=f.faculty_id WHERE l.business_id=". $business['business_id'] );
	while ( $r = $db->fetch_array( $q ) )
	{
		$degq = $db->query( "SELECT * FROM char_degrees WHERE character_id=". $char->character_id ." AND degree_type='". $r['abbreviation'] ."' AND business_id=". $business['business_id'] );
		$degree = $db->getRowCount( $degq );
		echo "<p>";
		if ( $degree == 0 )
			echo "<a href=\"university.faculty.php?faculty=". $r['abbreviation'] ."\">Subscribe to a course in ". $r['study_name'] ."</a><br />";
		else
		{
			$deg = $db->fetch_array( $degq );
			if ( $deg['degree_status'] != 3 )
				echo "<a href=\"university.faculty.php?faculty=". $r['abbreviation'] ."\">Continue studying for your ". $r['study_name'] ." course</a><br />";
			else
				echo "<strong>You already have a degree in ". $r['study_name'] ."!</strong><br />";
		}
		echo str_replace( "$(CITY)$", $char->location, $r['description'] );
		echo "</p>";
	}
	?>	
	</div>
</div>

<?
include_once "../includes/footer.php";
?>