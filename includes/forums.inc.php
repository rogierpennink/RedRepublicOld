<?
define( "TOPIC_NORMAL", 0 );
define( "TOPIC_STICKY", 1 );
define( "TOPIC_ANNOUNCEMENT", 2 );

function getForumInfo( $id )
{
	global $db;

	// Check if the forum ID exists
	$checkres = $db->query( "SELECT * FROM forums_forums WHERE id=". $db->prepval( $id ) );

	if ( $db->getRowCount( $checkres ) == 0 )
	{
		return false;
	}
	else
	{
		// Queries that will yield the amount of topics and replies
		$topicsres = $db->query( "SELECT * FROM forums_topics INNER JOIN accounts ON forums_topics.poster_id = accounts.id WHERE f_id=". $db->prepval( $id ). " ORDER BY last_activity DESC" );
		$repliesres = $db->query( "SELECT * FROM forums_replies INNER JOIN accounts ON forums_replies.account_id = accounts.id WHERE f_id=". $db->prepval( $id ) ." ORDER BY date DESC" );

		// Fetch that actual amount
		$numtopics = $db->getRowCount( $topicsres );
		$numreplies = $db->getRowCount( $repliesres ) - $numtopics;

		// Fetch information about the last topic ( ordered by date )
		$lasttopic = $db->fetch_array( $topicsres );
		$lastreply = $db->fetch_array( $repliesres );
		
		$topicname = ( $db->getRowCount( $topicsres ) == 0 ) ? "..." : $lasttopic['name'];
		$topicposter = ( $db->getRowCount( $repliesres ) == 0 ) ? "..." : $lastreply['username'];

		// Create the array that is to be returned.
		$retarray = array( "numtopics" => $numtopics, "numreplies" => $numreplies, "lasttopic" => $topicname, "lastposter" => $topicposter, "lasttopicid" => $lasttopic[0] );

		return $retarray;
	}
}

function getTopicInfo( $id )
{
	global $db;

	// Check if the forum ID exists
	$checkres = $db->query( "SELECT * FROM forums_topics WHERE id=". $db->prepval( $id ) );

	if ( $db->getRowCount( $checkres ) == 0 )
	{
		return false;
	}
	else
	{
		// Queries that will yield the amount of topics and replies
		$topicsres = $db->query( "SELECT * FROM forums_topics INNER JOIN accounts ON forums_topics.poster_id = accounts.id WHERE forums_topics.id=". $db->prepval( $id ) );
		$repliesres = $db->query( "SELECT * FROM forums_replies INNER JOIN accounts ON forums_replies.account_id = accounts.id WHERE t_id=". $db->prepval( $id ) ." ORDER BY date DESC" );

		// Fetch that actual amount		
		$numreplies = $db->getRowCount( $repliesres );

		// Fetch information about the last topic ( ordered by date )
		$lastreply = $db->fetch_array( $repliesres );	
		$topic = $db->fetch_array( $topicsres );
		
		
		// Create the array that is to be returned.
		$retarray = array( "numreplies" => $numreplies, "numviews" => $topic['num_views'], "lastreplystamp" => $lastreply['date'], "lastreplydate" => date( timeDisplay(), strtotime( $lastreply['date'] ) ), "topicstarter" => $topic['username'], "lastposter" => $lastreply['username'], "lastreplyID" => $lastreply[0] );

		return $retarray;
	}
}

function addEmoticons( $message, $rootdir, $ext = "png" )
{
	// $ext...
	// PNG: Transparent, nicer.
	// GIF: Some animation, no transparency.
	$emo = getEmoticonArrays();
	for ( $i = 0; $i < count( $emo[0] ); $i++ )
	{
		$message = str_replace( trim( $emo[0][$i] ), "<img src=\"". $rootdir ."/images/emoticons/". trim( $emo[1][$i] ) ."\" alt=\"\" />", $message );
	}

	return $message;
}

function getForumUserInfo( $usr )
{
	global $db;

	$userres = $db->query( "SELECT num_posts, avatar FROM forums_users INNER JOIN accounts ON forums_users.account_id = accounts.id WHERE accounts.username=" . $db->prepval( $usr ) );

	if ( $db->getRowCount( $userres ) == 0 ) return false;

	$user = $db->fetch_array( $userres );

	return array( "num_posts" => $user['num_posts'], "avatar" => $user['avatar'] );
}

function hasSeenTopic( $user_id, $t )
{
	global $db;	
	
	$r = $db->query( "SELECT * FROM forums_posttracker WHERE reply_id=" .$db->prepval( $t['lastreplyID'] ). " AND account_id=". $db->prepval( getUserID() ) );

	$res = $db->fetch_array( $r );
	
	if ( $res['last_seen'] < $t['lastreplystamp'] || $db->getRowCount( $r ) == 0 )
		return false;
	else
		return true;
}

function getForumRanks()
{
	global $db;

	$res = $db->query( "SELECT * FROM forums_ranks ORDER BY num_posts ASC" );

	if ( $db->getRowCount( $res ) == 0 ) return false;

	$i = 0;
	$arr = array();
	while ( $ranks = $db->fetch_array( $res ) )
	{
		$arr[$i++] = $ranks;
	}

	return $arr;
}

function getEmoticons()
{
	$files = sscandir( "../images/emoticons" );
	
	return $files;
}

function addBBCode( $message, $images = true )
{
	$message = str_replace( array( "[b]", "[/b]", "[u]", "[/u]", "[i]", "[/i]", "	" ), array( "<b>", "</b>", "<u>", "</u>", "<i>", "</i>", "&nbsp;&nbsp;&nbsp;&nbsp;" ), $message );
	$message = str_replace( array( "[list]", "[*]", "[/list]" ), array( "<ul>", "<li>", "</ul>" ), $message );
	if ( substr_count( strtolower( $message ), "[quote]" ) == substr_count( strtolower( $message ), "[/quote]" ) )
	{
		// Replace [quote] and [/quote] with divs
		$message = str_replace( "[quote]", "<div style=\"border: solid 1px #990033; margin: 5px; padding: 5px;\"><span style=\"font-size: 11px; font-weight: bold;\">Quote:<br /></span>", $message );
		$message = str_replace( "[/quote]", "</div>", $message );

		// Replace [code] and [/code] with divs
		$message = str_replace( "[code]", "<div style=\"border: solid 1px #5993B5; background: #FFFFFF; margin: 5px; padding: 5px;\"><span style=\"font-size: 11px; font-weight: bold;\">Code:<br /></span><font face='courier new' size='2'>", $message );
		$message = str_replace( "[/code]", "</font></div>", $message );

	}
	// Add imagery
	$pos = 0;
	while ( ( $pos = strpos( strtolower( $message ), "[img]", $pos ) ) !== false && $images )
	{
		if ( ( $pos0 = strpos( strtolower( $message ), "[/img]", $pos ) ) !== false )
		{
			$imgsrc = trim( substr( $message, $pos + 5, $pos0 - ( $pos + 5 ) ) );
			$imgstr = "<img src=\"".$imgsrc."\" alt=\"\" />";			
			$message = substr_replace( $message, $imgstr, $pos, $pos0 - $pos + 6 );
			$pos = $pos0;
		}
		else
		{
			continue;
		}
	}

	return $message;
}

function addChatFormatting( $message )
{
	// Placeholder
	return $message;
}

?>