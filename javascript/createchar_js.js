function createchar( form )
{
	var ajax =  new AjaxConnection( response );
	ajax.setScriptUrl( 'createchar.php' );
	ajax.method = "POST";
	ajax.addParam( 'ajaxrequest', 'createchar' );
	ajax.addParam( 'nickname', form.nickname.value );	
	ajax.addParam( 'firstname', form.firstname.value );
	ajax.addParam( 'lastname', form.lastname.value );
	ajax.addParam( 'gender', form.gender.value );
	ajax.addParam( 'day', form.day.value );
	ajax.addParam( 'month', form.month.value );
	ajax.addParam( 'year', form.year.value );
	ajax.addParam( 'city', form.city.value );
	ajax.addParam( 'background', form.background.value );
	for ( i = 0; i < 3; i++ ) if ( form.choice1[i].checked == true ) ajax.addParam( 'choice1', form.choice1[i].value );	
	for ( i = 0; i < 3; i++ ) if ( form.choice2[i].checked == true ) ajax.addParam( 'choice2', form.choice2[i].value );
	for ( i = 0; i < 3; i++ ) if ( form.choice3[i].checked == true ) ajax.addParam( 'choice3', form.choice3[i].value );
	for ( i = 0; i < 3; i++ ) if ( form.choice4[i].checked == true ) ajax.addParam( 'choice4', form.choice4[i].value );
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
			setMessage( "error", "Error - an unknown response was received from the server." );
		}
	}
}