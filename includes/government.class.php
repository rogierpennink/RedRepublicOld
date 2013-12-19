<?
/**
 * The Government Class
 * An object in which you can easily update and get information from a localcity government.
 */

class Government
{
	var $db;

	/* Local Gov Vars */
	var $LocationID;			// Localcity ID
	var $GovernmentID;			// Government Template ID
	var $LeaderID;				// Character ID of Leader

	var $UnempCheckBool;		// Status of Unemployment Check (on/off, true/false)
	var $FreePressBool;			// Status of Free Press (on/off, true/false)
	var $FreeSpeechBool;		// Status of Free Speech (on/off, true/false)
	
	var $Budget;				// Current Budget Ammount ($)
	var $UnempCheckSize;		// Size of the Unemployment Check ($)
	var $Taxes;					// Current Tax Rate (%)
	var $Notice;				// Government Notice String

	var $NewspaperContent;		// HTML Newspaper Content

	/* Gov Style Vars */
	var $GovInfo;				// Array of Government Template Information ["Name"] (str), ["Formal"] (str), ["Description"] (str).

	/* Other Vars */
	var $err;					// The Error object to write errors to.

	/* Government Class Constructor
	 * @param location_nr int The localcity # government to operate on.
	 * @return bool true on success, false on failure.
	 */
	function Government( $location_nr )
	{
		global $db;
		$this->db = $db;

		if ( !is_numeric( $location_nr ) )
		{
			$err->Error = "The location_nr parameter of the Government constructor must be a city ID.";
			return false;
		}

		$result = $this->db->query( "SELECT * FROM localgov WHERE location_id=" . $this->db->prepval( $location_nr ) );
		if ( $this->db->getRowCount( $result ) == 0 )
		{
			$err->Error = "Local government could not be found by the location_id provided through the Government constructor.";
			return false;
		}

		$array = $this->db->fetch_array( $result );

		/* Setup Local Government Variables */
		$this->LocationID = $location_nr;
		$this->GovernmentID = $array['government_id'];
		$this->LeaderID = $array['leader_id'];

		$this->UnempCheckBool = $this->_strbooltobool( $array['unemp_check_status'] );
		$this->FreePressBool = $this->_strbooltobool( $array['freepress_status'] );
		$this->FreeSpeechBool = $this->_strbooltobool( $array['freespeech_status'] );

		$this->Budget = $array['budget'];
		$this->UnempCheckSize = $array['unemp_check'];
		$this->Taxes = $array['taxes'];
		$this->Notice = $array['notice'];

		$this->NewspaperContent = $array['html_newspaper'];
		
		/* Setup Government Style Variables */
		$result = $this->db->query( "SELECT * FROM governments WHERE id=" . $this->db->prepval( $this->GovernmentID ) );
		if ( $this->db->getRowCount( $result ) == 0 )
		{
			$err->Error = "The government template could not be found for id " . $this->GovernmentID . ".";
			return false;
		}

		$array = $this->db->fetch_array( $result );

		/* Gov Array, Usage Example:
		 * echo $gov->GovInfo["Name"] . "'s formal name is " . $gov->GovInfo["Formal"] . " and a description is " . $gov->GovInfo["Description"] . ".<br />";
		 */
		$this->GovInfo = array(
			"Name" => $array['name'],
			"Formal" => $array['formal'],
			"Description" => $array['description']
		);

		return true;
	}

	/* A function to turn a string bool ("true" or "false", like in a varchar field) into an actual boolean. */
	function _strbooltobool( $str )
	{
		if ( $str == "true" )
			return true;
		elseif ( $str == "false" )
			return false;
		elseif ( $str === true )
			return true;
		elseif ( $str === false )
			return false;
		else
			return false;
	}

	/* Turn a bool to a string, similar to _strbooltobool, except backwards */
	function _booltostr( $bool )
	{
		if ( !is_bool( $bool ) ) return false;

		if ( $bool )
			return 'true';
		else
			return 'false';
	}

	/* A reusable way to setup the class */
	function refresh()
	{
		$this->Government( $this->LocationID() );
	}

	/* This function sets the budget
	 * @param newbudget int New budget size.
	 * @return mixed The new size of the budget on success, false on failure.
	 */
	function setBudget( $newbudget )
	{
		if ( !is_numeric( $newbudget ) )
		{
			$err->Error = "Error setting new budget. New budget value must be numeric.";
			return false;
		}

		/* Update the budget */
		$result = $this->db->query( "UPDATE localgov SET budget=" . $this->db->prepval( $newbudget ) . " WHERE location_id=" . $this->LocationID );

		if ( $result )
		{
			$this->Budget = $newbudget;
			return $newbudget;
		}
		else
		{
			$err->Error = "The query to update the government budget was found to be invalid.";
			return false;
		}
	}

	/* This function changes the localcity leader
	 * @param user int The char_id of the new city leader.
	 * @return bool True on success, false on failure.
	 */
	function setLeader( $user )
	{
		if ( !is_numeric( $user ) )
		{
			$err->Error = "The user parameter of the changeLeader function must be the character id of the new city leader.";
			return false;
		}

		/* Check for character existance */
		$result = $this->db->query( "SELECT id FROM char_characters WHERE id=" . $user );

		if ( !$result )
		{
			$err->Error = "Could not change city leaders, the query was invalid.";
			return false;
		}
		
		/* Update localgov */
		if ( $this->db->getRowCount( $result ) != 0 )
		{
			$this->LeaderID = $user;
			$this->db->query( "UPDATE localgov SET leader_id=" . $user . " WHERE location_id=" . $this->LocationID );
			return true;
		}
		else
		{
			$err->Error = "Could not change city leader, the new leader's character could not be found.";
			return false;
		}

		return true; // Default
	}

	/* This function changes the local government template
	 * @param int Government template to change to
	 * @return bool True on success, false on failure.
	 */
	function setGovernment( $government_id )
	{
		if ( !is_numeric( $government_id ) )
		{
			$err->Error = "The government_id parameter of the setGovernment function must be the ID of a government template.";
			return false;
		}

		/* Check for template existance */
		$result = $this->db->query( "SELECT id FROM governments WHERE id=" . $this->db->prepval( $government_id ) );

		if ( $this->db->getRowCount( $result ) == 0 )
		{
			$err->Error = "The government template with the ID (" . $government_id . ") didn't exist.";
			return false;
		}

		/* Update the local government */
		$update = $this->db->query( "UPDATE localgov SET government_id=" . $government_id . " WHERE location_id=" . $this->LocationID );

		if ( !$update )
		{
			$err->Error = "The query to change government templates was invalid.";
			return false;
		}

		/* Reconstruct the class */
		$this->refresh();
		return true;
	}

	/* setTaxes, sets a new tax level
	 * @param newtaxes int New tax level.
	 * @param bool False on failure, true on success.
	 */
	function setTaxes( $newtaxes )
	{
		if ( !is_numeric( $newtaxes ) )
		{
			$err->Error = "The newtaxes parameter of setTaxes must be numeric.";
			return false;
		}

		/* Update taxes */
		$result = $this->db->query( "UPDATE localgov SET taxes=" . $db->prepval( $newtaxes ) . " WHERE location_id=" . $this->LocationID );

		if ( !$result )
		{
			$err->Error = "The query to update the local city's taxes was invalid.";
			return false;
		}
	
		$this->Taxes = $newtaxes;
		return true;
	}

	/* This function sets the unemployment check size 
	 * @param newcheck int The new amount of the unemployment check
	 * @return bool True on success, false on failure.
	 */
	function setUnemploymentCheck( $newcheck )
	{
		if ( !is_numeric( $newcheck ) )
		{
			$err->Error = "The newcheck parameter of setUnemploymentCheck must be numeric.";
			return false;
		}

		/* Update check */
		$result = $this->db->query( "UPDATE localgov SET unemp_check=" . $this->db->prepval( $newcheck ) . " WHERE location_id=" . $this->LocationID );

		if ( !$result )
		{
			$err->Error = "The query to update the unemployment check size was invalid.";
			return false;
		}

		$this->UnempCheckSize = $newcheck;
		return true;
	}

	/* This function sets the status of the unemployment check.
	 * @param onoff bool Set to true for on, and false for off.
	 * @return bool True on success, false on failure.
	 */
	function setUnempCheckStatus( $onoff )
	{
		if ( !is_bool( $onoff ) )
		{
			$err->Error = "The onoff parameter of setUnempCheckStatus must be a boolean.";
			return false;
		}

		/* Update check status */
		$result = $this->db->query( "UPDATE localgov SET unemp_check_status=" . $this->db->prepval( $this->_booltostr( $onoff ) ) . " WHERE location_id=" . $this->LocationID );

		if ( !$result )
		{
			$err->Error = "The query to update the unemployment check status was invalid.";
			return false;
		}

		$this->UnempCheckBool = $onoff;
		return true;
	}

	/* This function sets a new government notice, or can erase the current one.
	 * @param newnotice string The text for our new notice. Use "" to erase the current one.
	 * @return bool True on success, false on failure.
	 */
	function setNotice( $newnotice )
	{
		if ( !is_string( $newnotice ) )
		{
			$err->Error = "The newnotice parameter of setNotice must be a string.";
			return false;
		}

		/* Update notice */
		$result = $this->db->query( "UPDATE localgov SET notice=" . $this->db->prepval( $newnotice ) . " WHERE location_id=" . $this->LocationID );

		if ( !$result )
		{
			$err->Error = "The query to update the government notice was invalid.";
			return false;
		}

		$this->Notice = $newnotice;
		return true;
	}

	function hasNotice()
	{
		if ( strlen( $this->Notice ) > 0 ) 
			return true;
		else
			return false;
	}

	/* The following two functions set the freespeech and freepress status.
	 * @param onoff bool True for on, false for off.
	 * @return bool True on success, false on failure.
	 */
	function setFreeSpeechStatus( $onoff )
	{
		if ( !is_bool( $onoff ) )
		{
			$err->Error = "The onoff parameter of setFreeSpeechStatus must be a boolean.";
			return false;
		}

		/* Update status */
		$result = $this->db->query( "UPDATE localgov SET freespeech_status=" . $this->db->prepval( $this->_booltostr( $onoff ) ) . " WHERE location_id=" . $this->LocationID );

		if ( !$result ) 
		{
			$err->Error = "The query to update the Free Speech Status was invalid.";
			return false;
		}

		$this->FreeSpeechBool = $onoff;
		return true;
	}

	function setFreePressStatus( $onoff )
	{
		if ( !is_bool( $onoff ) )
		{
			$err->Error = "The onoff parameter of setFreePressStatus must be a boolean.";
			return false;
		}

		/* Update status */
		$result = $this->db->query( "UPDATE localgov SET freepress_status=" . $this->db->prepval( $this->_booltostr( $onoff ) ) . " WHERE location_id=" . $this->LocationID );

		if ( !$result ) 
		{
			$err->Error = "The query to update the Free Press Status was invalid.";
			return false;
		}

		$this->FreePressBool = $onoff;
		return true;
	}

	/* Get Research Status */
	function getResearchStatus( $location_id, $research_id )
	{
		$result = $this->db->query( "SELECT id FROM governments_research WHERE id=" . $db->prepval( $research_id ) );
		if ( $this->db->getRowCount( $result ) == 0 )
			return -1; // !Not Researched

		$result = $this->db->query( "SELECT * FROM localgov_research WHERE location_id=" . $this->db->prepval( $location_id ) . " AND research_id=" . $this->db->prepval( $research_id ) );
		if ( $this->db->getRowCount( $result ) == 0 )
			return -1; // !Not Researched

		$research = $this->db->fetch_array( $result );

		/* Return
		 **
		 * -1: Not Researched
		 * 0: Researching
		 * 1: Implemented
		 * 2: Widely Accepted.

		 */
		return $research["status"];
	}

	/* Set Research Status */
	function setResearchStatus( $location_id, $research_id, $newstatus )
	{
		$resulta = $this->db->query( "SELECT gov_id FROM governments_research WHERE id=" . $this->db->prepval( $research_id ) );
		if ( $this->db->getRowCount( $resulta ) == 0 )
			return false;

		$result = $this->db->query( "SELECT id FROM localgov_research WHERE location_id=" . $this->db->prepval( $location_id ) . " AND research_id=" . $this->db->prepval( $research_id ) );
		if ( $this->db->getRowCount( $result ) == 0 )
		{
			$chk = $this->db->fetch_array( $resulta );
			if ( $chk["gov_id"] != $this->GovernmentID )
				return false;

			/* Not found, insert */
			$this->db->query( "INSERT INTO localgov_research (location_id, research_id, status) VALUES (" . $this->db->prepval( $location_id ) . ", " . $this->db->prepval( $research_id ) . ", " . $this->db->prepval( $newstatus ) . ")" );
		}
		else
		{
			/* Found, just update. */
			$this->db->query( "UPDATE localgov_research SET status=" . $this->db->prepval( $newstatus ) . " WHERE location_id=" . $this->db->prepval( $location_id ) . " AND research_id=" . $this->db->prepval( $research_id ) );
		}
	}

	function getResearchStatusText( $status )
	{
		if ( !is_numeric( $status ) ) return false;

		switch ( $status )
		{
			case -1: return "Un-Researched";
			case 0: return "Researching";
			case 1: return "Implemented";
			case 2: return "Widely Accepted";
			default: return "Un-Researched";
		}
	}

	/* A function to return strings based on input, something like a string table. */
	function getStrings( $strname )
	{
		if ( !is_string( $strname ) ) return false;
		switch ( $strname )
		{
			case "FreeSpeech":
			{
				if ( !$this->FreeSpeechBool )
					return "The government does not guarantee Freedom of Speech. ";
				else
					return "Freedom of Speech is a natural right here given to every citizen. ";
			}

			case "FreePress":
			{
				if ( !$this->FreePressBool )
					return "Citizens are not allowed to engage in reading any news material not issued by the government.";
				else
					return "Citizens may enjoy reading from any news source they'd like, Freedom of Press is a right here.";
			}

			case "Unemployment":
			{
				if ( $this->UnempCheckBool )
					return "Citizens are recieving unemployment checks, the current rate is $" . $this->UnempCheckSize . " per week. ";
				else
					return "The government does not hand out unemployment checks to its citizens. ";
			}
		}
	}

	/* Three functions to that set the newspaper content, check if there is any, and an alias function to get the content. */
	function getNewspaper()
	{
		return $this->NewspaperContent;
	}

	function setNewspaper( $htmlstring )
	{
		if ( !is_string( $htmlstring ) ) return false;

		$this->db->query( "UPDATE localgov SET html_newspaper=" . $this->db->prepval( $htmlstring ) . " WHERE location_id=" . $this->LocationID );

		$this->NewspaperContent = $htmlstring;

		return mysql_insert_id();
	}

	function hasNewspaper()
	{
		if ( strlen( $this->NewspaperContent ) > 0 )
			return true;
		else
			return false;
	}
}
?>