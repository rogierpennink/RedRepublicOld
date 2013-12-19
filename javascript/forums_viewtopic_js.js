function sticky( id )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( 'sticky.php' );
	ajax.addParam( 'ajaxrequest', 'sticky' );
	ajax.addParam( 'id', id );
	ajax.send( null );

	function response( text )
	{
		parts = text.split( "::" );
		if ( parts[0] == "error" )
		{
			setMessage( "error", parts[1] );
		}
		else if ( parts[0] == "success" )
		{
			setMessage( "notify", parts[1] );
		}
		else
		{
			setMessage( "error", "Error - An unknown response was received by the server." );
		}
	}
}

function unsticky( id )
{
	var ajax = new AjaxConnection( response );	
	ajax.setScriptUrl( 'sticky.php' );
	ajax.addParam( 'ajaxrequest', 'unsticky' );
	ajax.addParam( 'id', id );	
	ajax.send( null );	

	function response( text )
	{		
		parts = text.split( "::" );
		if ( parts[0] == "error" )
		{
			setMessage( "error", parts[1] );
		}
		else if ( parts[0] == "success" )
		{
			setMessage( "notify", parts[1] );
		}
		else
		{
			setMessage( "error", "Error - An unknown response was received by the server." );
		}
	}
}