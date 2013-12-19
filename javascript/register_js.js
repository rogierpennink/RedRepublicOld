function register( form )
{
	var ajax =  new AjaxConnection( response );
	ajax.setScriptUrl( 'register.php' );
	ajax.method = "POST";
	ajax.addParam( 'ajaxrequest', 'register' );
	ajax.addParam( 'name', form.username.value );
	ajax.addParam( 'password', form.password.value );
	ajax.addParam( 'password1', form.password1.value );
	ajax.addParam( 'firstname', form.firstname.value );
	ajax.addParam( 'lastname', form.lastname.value );
	ajax.addParam( 'country', form.country.value );
	ajax.addParam( 'city', form.city.value );
	ajax.addParam( 'email', form.email.value );
	ajax.send( null );

	function response( text )
	{
		parts = text.split( "::" );
		if ( parts[0] == "error" )
		{
			setMessage( "error", parts[1] );
		}
		else if ( parts[0] == "contentID" && parts[1] == "register" )
		{
			setMessage( "notify", parts[2] );
		}
		else
		{
			setMessage( "error", "Error - an unknown response was received from the server." );
		}
	}
}