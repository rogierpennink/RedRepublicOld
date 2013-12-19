<?
/*
	database.class.php - A wrapper class around the mysqli object

	Author: Rogier Pennink
	Date: 03/02/2007

	Revision: Aaron Amann
	Date: 03/06/2007

	Last update: 16/08/2007 (Rogier)
 */

class Database
{
	var $host;
	var $user;
	var $pass;
	var $db;
	var $conn;
	var $logfile;
	var $flog;
	var $loglevel;
	var $queries;

	/** The class' constructor */
	function Database( $host = "", $user = "", $pass = "", $db = "" )
	{
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->db = $db;
	}

	/** The connect function */
	function connect()
	{
		$this->queries = 0;

		if ( $this->loglevel >= 1 )
			$this->log( "Trying to establish connection ( host = ". $this->host .", user = " . $this->user . " )." );

		$this->conn = mysql_connect( $this->host, $this->user, $this->pass );

		/* check connection */
		if ( mysql_errno() ) 
		{
			$this->log( "An error occurred on connecting to the database:" );
			$this->log( "    ==> Error: " . mysql_error() );
			$this->log( "    ==> File: " . $_SERVER['SCRIPT_FILENAME'] );
			return false;
		}

		mysql_select_db( $this->db, $this->conn );

		if ( mysql_errno() )
		{
			$this->log( "An error occurred on selecting the database:" );
			$this->log( "    ==> Error: " . mysql_error() );
			$this->log( "    ==> File: " . $_SERVER['SCRIPT_FILENAME'] );
			return false;
		}	

		return true;
	}

	/**
	 * The quoteSmart function with a slightly shorter name to not mess up scripts so much
	 */
	function prepval( $value )
	{
		if ( get_magic_quotes_gpc() )		// Strip slashes if magic quotes is on
		{
			$value = stripslashes( $value );
		}

		if ( !is_numeric( $value ) )		// Add mysql's slashes if not numeric
		{
			$value = "'" . mysql_real_escape_string( $value ) . "'";
		}
		
		return $value;
	}
	
	/** The query function */
	function query( $sql )
	{
		// Log query if necessary
		if ( $this->loglevel >= 2 )
		{
			$this->log( "Attempting execution of new query:" );
			$this->log( "    ==> Query: ". $sql );
		}

		// The return value from the query we'll execute
		$val = mysql_query( $sql );

		if ( $val === false )
		{
			$this->log( "An error occurred on querying the database:" );
			$this->log( "    ==> Query: " . $sql );
			if ( mysql_errno() )
				$this->log( "    ==> Error: " . mysql_error() );
			else
				$this->log( "    ==> Error: No Error Description Found." );

			$this->log( "    ==> File: " . $_SERVER['SCRIPT_FILENAME'] );

			return false;
		}
		elseif ( $val === true )
		{	
			if ( $this->loglevel >= 2 )
				$this->log( "    ==> Query executed successfully." );

			$this->queries++;
			return true;
		}
		else
		{
			if ( $this->loglevel >= 2 )
				$this->log( "    ==> Query executed successfully." );

			$this->queries++;
			return $val;
		}
	}

	/** The fetch_assoc function */
	function fetch_assoc( $resource )
	{
		return mysql_fetch_assoc( $resource );
	}

	/** The fetch_array function */
	function fetch_array( $resource )
	{
		return mysql_fetch_array( $resource );
	}

	/** The getRowCount function */
	function getRowCount( $resource )
	{
		$result = mysql_num_rows( $resource );

		return $result; // Return anyway
	}

	/** The disconnected function */
	function disconnect()
	{
		mysql_close( $this->conn );
		
		/* check for errors */
		if ( mysql_errno() ) 
		{
			 $this->log( "An error occurred on disconnecting from the database:" );
			 $this->log( "    ==> Error: " . mysql_error() );
			 $this->log( "    ==> File: " . $_SERVER['SCRIPT_FILENAME'] );
			 return false;
		}

		return true;
	}

	/**
	 * mysqlInfo returns version and debug information about the MySQL server. You can specify
	 * to return a formated version with a delimiter of choice (optional). If you don't specify
	 * the delimiter it will return an array of MySQL information.
	 * Order of information (array):
	 *	0: Client Library Version
	 *	1: MySQL Connection Method
	 *	2: Protocol
	 *	3: MySQL Version
	 *	4: Ping Response
	 *	5: Uptime
	 *	6: Threads
	 *	7: Questions
	 *	8: Slow Queries
	 *	9: Opens
	 *	10: Flush Tables
	 *	11: Open Tables
	 *	12: QPS (Average)
	 *	13: Thread ID
	 *	14: Client Encoding
	 * @param string Optional delimiter to append, leave empty to return an array.
	 * @return Array if a delimiter was not provided, otherwise returns a string.
	 * @author Aaron Amann
	 */
	function mysqlInfo( $string = "" )
	{
		$mysqlInfo_LibVer = mysql_get_client_info();
		$mysqlInfo_ConMethod = mysql_get_host_info();
		$mysqlInfo_Protocol = mysql_get_proto_info();
		$mysqlInfo_Version = mysql_get_server_info();

		if ( mysql_ping() )
			$mysqlInfo_PingResponse = true;
		else 
			$mysqlInfo_PingResponse = false;

		$mstat = mysql_stat();
		$mysqlInfo_Uptime = $mstat[0];
		$mysqlInfo_Threads = $mstat[1];
		$mysqlInfo_Questions = $mstat[2];
		$mysqlInfo_SlowQs = $mstat[3];
		$mysqlInfo_Opens = $mstat[4];
		$mysqlInfo_Flushed = $mstat[5];
		$mysqlInfo_Opened = $mstat[6];
		$mysqlInfo_QPS = $mstat[7];
		$mysqlInfo_ThreadID = mysql_thread_id();
		$mysqlInfo_Encode = mysql_client_encoding();

		if ( strlen( $string ) == 0 )
		{
			return array($mysqlInfo_LibVer, $mysqlInfo_ConMethod, $mysqlInfo_Protocol, $mysqlInfo_Version, $mysqlInfo_PingResponse, $mysqlInfo_Uptime, $mysqlInfo_Threads, $mysqlInfo_Questions, $mysqlInfo_SlowQs, $mysqlInfo_Opens, $mysqlInfo_Flushed, $mysqlInfo_Opened, $mysqlInfo_QPS, $mysqlInfo_ThreadID, $mysqlInfo_Encode);
		} else {
			return "Client Library Version: " . $mysqlInfo_LibVer . $string . "Connection Method: " . $mysqlInfo_ConMethod . $string . "Protocol: " . $mysqlInfo_Protocol . $string . "Version: " . $mysqlInfo_Version . $string . "Ping Response: " . $mysqlInfo_PingResponse . $string . "Uptime: " . $mysqlInfo_Uptime . $string . "Threads: " . $mysqlInfo_Threads . $string . "Questions: " . $mysqlInfo_Questions . $string . "Slow Queries: " . $mysqlInfo_SlowQs . $string . "Opens: " . $mysqlInfo_Opens . $string . "Flush Tables: " . $mysqlInfo_Flushed . $string . "Queries Per Second: " . $mysqlInfo_QPS . $string . "Thread ID: " . $mysqlInfo_ThreadID . $string . "Client Encoding: " . $mysqlInfo_Encode;
		}
	}

	/**
	* The setLogging method allows the user to enable and disable logging. If the user enables 
	* logging, he/she can also provide a filename to which to log. If no filename is provided 
	* and logging is set to true, the default filename will be default_db_log.txt. 
	* @param bool A boolean value that enables or disables logging. 
	* @param filename An optional string that specifies the filename of the log. 
	* @return A boolean value that is false when errors occurred with the file-handling. 
	* @author Rogier Pennink 
	*/ 
	function setLogging( $bool, $loglevel = 0, $filename = null ) 
	{ 
		$this->dologging = $bool; 
		$this->loglevel = $loglevel;
		$startfile = false;

		if ( $bool == true ) 
		{ 
			if ( $filename == null ) 
			{ 
				$this->logfile = "default_db_log.txt"; // No filename provided, create default log file 

				$startfile = ( file_exists( $this->logfile ) ) ? true : false;
				
				$this->flog = fopen( $this->logfile, "a" );
				 
				if ( $this->flog == null ) 
					return false; 
			} 
			else 
			{ 
				$this->logfile = $filename; // Open a file with specified filename 

				$startfile = ( file_exists( $this->logfile ) ) ? true : false;
				
				$this->flog = fopen( $this->logfile, "a" );
				
				if ( $this->flog == null ) 
					return false; 
			} 
			
			if ( filesize( $this->logfile ) == 0 )
				$this->log( "==> Red Republic Database Logfile <==" . Chr(13) ); 
		} 

		if ( $this->loglevel >= 1 )
		{
			if ( $bool )
			{
				$this->log( "Logging turned on with parameters:" );
				$this->log( "    ==> Log File: " . $this->logfile );
				$this->log( "    ==> Log Level: " . $this->loglevel );
			}
			else
			{
				$this->log( "Logging turned off." );
			}
		}

		return true; 
	} 

	/**
	* The isLogging method tells wether or not logging is currently disabled. 
	* @return A boolean value that describes the current logging state. 
	* @author Rogier Pennink 
	*/ 
	function isLogging() 
	{ 
		return $this->dologging; 
	} 

	/**
	* The log method will write a string to the specified output log if logging is enabled. 
	* @param message The message to write to the log. 
	* @return A boolean value that is false if logging is disabled or if the action failed. 
	* @author Rogier Pennink. 
	*/ 
	function log( $message ) 
	{ 
		if ( !$this->isLogging() ) // If we are not logging, return false 
			return false; 
		if ( !is_writeable( $this->logfile ) ) // If the file cannot be written to, return false 
			return false; 
		if ( flock( $this->flog, LOCK_EX ) )
		{
			if ( !fwrite( $this->flog, date( "[d/m/Y - H:i:s]: " ) . $message . Chr(13) ) )		// If writing failed, return false 
			{	
				return false; 
			}
			
			flock( $this->flog, LOCK_UN );			
		}
		else
		{
			return false;
		}
			
		return true; // All went well 
	} 

	/**
	 * getRowsFor runs a query and returns an array (from a mysql_fetch_array call).
	 * @param Query A MySQL query to run.
	 * @return An array from mysql_fetch_array.
	 */
	function getRowsFor( $Query )
	{
		$result = $this->query( $Query );

		if ( !$result )
			return false;

		return $this->fetch_array( $result );
	}

	/**
	 * getRowCountFor works exactly like getRowCount, but instead of a mysql query resource,
	 * the query itself should be entered.
	 * @param Query A MySQL query to run
	 * @return int mysql_num_rows/this->getRowCount.
	 */
	function getRowCountFor( $Query )
	{
		$result = $this->query( $Query );

		if ( !$result )
			return false;
		
		return $this->getRowCount( $Query );
	}

	/* Get how many queries were ran. 
	 * @string int Return amount as an int or formated string.
	 */
	function getQueryCount( $int = true )
	{
		if ( $int )
			return $this->queries;
		else
			return $this->queries . " executed since constructor.";
	}
}