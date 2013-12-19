<?
/*
 * Mailer class, used for sending mail via php
 */

class Mailer
{
	var $HostName;
	var $HostAddress;
	var $ReplyToAddress;

	var $Logging;
	var $LogFile;

	function Mailer( $FromName, $FromAddress, $ReplyToAddr )
	{
		$this->HostName = $FromName;
		$this->HostAddress = $FromAddress;
		$this->ReplyToAddress = $ReplyToAddr;
	}

	function setLogging( $filename )
	{
		if ( !file_exists( $filename ) )
		{
			$createfile = fopen( $filename, "w" );
			if ( !$createfile ) return false;
			fclose( $createfile );
		}

		$this->LogFile = fopen( $filename, "a" );

		if ( !$this->LogFile ) return false;

		if ( filesize( $filename ) == 0 )
		{
			fwrite( $this->LogFile, "==> Red Republic Mailer Logfile <==" . Chr(13) );
		}
	}

	function log( $text )
	{
		if ( !$this->LogFile ) return false;
		fwrite( $this->LogFile, date( "[d/m/Y - H:i:s]: " ) . $text . Chr(13) );
	}

	function mail( $ToAddress, $Subject, $Message )
	{
		$Header =  "MIME-Version: 1.0" . "\r\n" .
				   "Content-type: text/html; charset=iso-8859-1" . "\r\n" .
				   "From: " . $HostAddress . "\r\n" .
				   "Reply-To: " . $ReplyToAddress . "\r\n" .
				   "X-Mailer: PHP/" . phpversion();

		if ( $_SERVER["REMOTE_ADDR"] == "127.0.0.1" )
		{
			$this->log( "Warning, not sending sending mail from localhost." );
			$this->log( "    ==> To: " . $ToAddress );
			$this->log( "    ==> Subject: " . $Subject );
			$this->log( "    ==> File: " . $_SERVER['SCRIPT_FILENAME'] );
			$this->log( "    ==> Result: Undefined" );
			$this->log( "" );
			return false;
		}

		if ( !mail( $ToAddress, $Subject, $Message ) )
		{
			$this->log( "Call to mail function could not be processed." );
			$this->log( "    ==> File: " . $_SERVER['SCRIPT_FILENAME'] );
			$this->log( "    ==> To: " . $ToAddress );
			$this->log( "    ==> Subject: " . $Subject );
			$this->log( "    ==> Headers... " . Chr(13) . $Header );
			$this->log( "" );
			return false;
		}

		return true;
	}
}

?>