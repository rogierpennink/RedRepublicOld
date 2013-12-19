function changeAvatar( newavatar )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( 'account.php' );
	ajax.addParam( 'ajaxrequest', 'avatar' );
	ajax.addParam( 'avatar', newavatar );
	ajax.send( null );

	function response( text )
	{
		parts = text.split( "::" );
		if ( parts[0] == "error" )
		{
			setMessage( parts[0], parts[1] );
		}
		else if ( parts[0] == "success" )
		{
			setMessage( "notify", parts[1] );
			if ( parts[2] != "" )			
				document.getElementById('avatar').src = parts[2];			
		}
		else
		{
			setMessage( "error", "Error - an unknown response was received from the server." );
		}
	}
}

function updateAccData()
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( 'account.php' );
	ajax.addParam( 'ajaxrequest', 'accdata' );
	ajax.addParam( 'pw', document.getElementById('pwd').value );
	ajax.addParam( 'pw1', document.getElementById('pwd1').value );
	ajax.addParam( 'email', document.getElementById('email').value );
	ajax.send( null );

	function response( text )
	{
		parts = text.split( "::" );
		if ( parts[0] == "error" )
		{
			setMessage( parts[0], parts[1] );
		}
		else if ( parts[0] == "success" )
		{
			setMessage( "notify", parts[1] );			
		}
		else
		{
			setMessage( "error", "Error - an unknown response was received from the server." );
		}
	}
}