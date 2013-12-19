function addComment( id, name, email, text )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( 'news.php' );
	ajax.method = "POST";
	ajax.addParam( 'ajaxrequest', 'addcomment' );	
	ajax.addParam( 'id', id );
	ajax.addParam( 'name', name );
	ajax.addParam( 'email', email );
	ajax.addParam( 'text', text );
	ajax.send( null );

	var newsid = id;

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
			reloadcomments( newsid );
		}
		else
		{
			setMessage( "error", "Error - An unknown response was sent by the server." );
		}
	}
}

function delComment( id, newsid )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( 'news.php' );	
	ajax.addParam( 'ajaxrequest', 'delcomment' );		
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
			reloadcomments( newsid );
		}
		else
		{
			setMessage( "error", "Error - An unknown response was sent by the server." );
		}
	}
}

function reloadcomments( newsid )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( 'news.php' );	
	ajax.addParam( 'ajaxrequest', 'getcomments' );		
	ajax.addParam( 'id', newsid );
	ajax.send( null );

	function response( text )
	{	
		parts = text.split( "::" );
		if ( parts[0] == "error" )
		{
			setMessage( "error", parts[1] );
		}
		else if ( parts[0] == "contentID" && parts[1] == "commentslist" )
		{
			var txt = "";
			for ( i = 2; i < parts.length; i++ )
			{
				txt += parts[i];
			}
			document.getElementById('commentcontainer').innerHTML = txt;
		}
	}
}