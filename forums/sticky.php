<?
/*
 * Sticky.php - this file will sticky/unsticky topics if the user has the
 * authority to do so and if a valid topic id is passed to the script.
 * This script is mostly connected to by means of AJAX requests.
 */
include_once "../includes/utility.inc.php";
include_once "../includes/forums.inc.php";

if ( isset( $_GET['ajaxrequest'] ) )
{
	if ( $_GET['ajaxrequest'] == "sticky" )
	{
		// Check for the ID
		$topicres = $db->query( "SELECT * FROM forums_topics WHERE id=". $db->prepval( $_GET['id'] ) );

		if ( $db->getRowCount( $topicres ) == 0 )
		{
			echo "error::Error - The provided topic ID was not valid!";
			exit;
		}

		if ( !user_auth() || getUserRights() < USER_MODERATOR )
		{
			echo "error::You are not logged in or you don't have the rights to sticky topics.";
			exit;
		}

		$topic = $db->fetch_array( $topicres );

		// Run an update query and return success
		if ( $db->query( "UPDATE forums_topics SET topic_type=". $db->prepval( TOPIC_STICKY ) ." WHERE id=". $db->prepval( $topic['id'] ) ) === false )
		{
			echo "error::A database error occurred on attempting to flag this topic as a sticky.";
		}
		else
		{
			echo "success::Topic: '". $topic['name'] ."' was stickied successfully!";
		}
	}
	elseif ( $_GET['ajaxrequest'] == "unsticky" )
	{
		// Check for the ID
		$topicres = $db->query( "SELECT * FROM forums_topics WHERE id=". $db->prepval( $_GET['id'] ) );

		if ( $db->getRowCount( $topicres ) == 0 )
		{
			echo "error::Error - The provided topic ID was not valid!";
			exit;
		}

		if ( !user_auth() || getUserRights() < USER_MODERATOR )
		{
			echo "error::You are not logged in or you don't have the rights to unsticky topics.";
			exit;
		}

		$topic = $db->fetch_array( $topicres );

		// Run an update query and return success
		if ( $db->query( "UPDATE forums_topics SET topic_type=". $db->prepval( TOPIC_NORMAL ) ." WHERE id=". $db->prepval( $topic['id'] ) ) === false )
		{
			echo "error::A database error occurred on attempting to flag this topic as normal.";
		}
		else
		{
			echo "success::Topic: '". $topic['name'] ."' was unstickied successfully!";
		}
	}
	else
	{
		echo "error::Error - The server received an unknown request type.";
	}
}
else
{
	echo "error::More information was required to execute request actions.";
}