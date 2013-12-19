<?
/* The Error class stores information about a specific error, then can display it nicely in a dialog box. */

class ErrorObject
{
	/* Discriptives */
	var $Error;			// A string describing the error.
	
	/* Constructor */
	function ErrorObject()
	{
		$this->Error = "";
	}

	/* Check for an error */
	function is_error()
	{
		if ( strlen( $this->Error ) > 0 ) 
			return true;
		else
			return false;
	}

	function getError()
	{
		$tmp = $this->Error;
		$this->Error = "";
		return $tmp;
	}
}

?>