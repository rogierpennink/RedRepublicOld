/* events_js.js */

function delevent( id )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl('index.php');
	ajax.addParam( 'ajaxrequest', 'deleteevent' );
	ajax.addParam( 'id', id );
	ajax.send(null);

	function response( text )
	{		
		var parts = text.split( "::" );
		if ( parts[0] == "error" && parts[1] == "notloggedin" )
		{
			window.location = rootdir + '/login.php';
		}
		else if ( parts[0] == "error" && parts[1] != "notloggedin" )
		{
			setMessage( 'error', parts[1] );
		}
		else if ( parts[0] == "success" )
		{
			setMessage( 'notify', parts[1] );
			loadEvents();
			loadEventsSaved();
		}
		else
		{
			setMessage( 'error', 'An error occurred; an unknown response was sent by the server.' );
		}
	}
}

function delete_selected_events( which )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl('index.php');
	ajax.method = "POST";
	ajax.addParam( 'ajaxrequest', 'deleteevents' );
	
	/* Walk through the selected messages. */
	var list = "";
	var b = document.getElementsByTagName('input');
	for ( var i = 0; i < b.length; i++ ) 	
		if ( b[i].getAttribute('type') == "checkbox" && b[i].getAttribute('name').substr( 0, which.length ) == which ) 		
			if ( b[i].checked == true )
				list += ( list == "" ) ? b[i].getAttribute('name').substr( which.length ) : ( "::" + b[i].getAttribute('name').substr( which.length ) );

	ajax.addParam( 'ids', list );
	ajax.send( null );
	
	function response( text )
	{
		text = text.split( "::" );
		if ( text[0] == "success" )
		{
			setMessage( 'notify', text[1] );
		}
		else if ( text[0] == "error" && text[1] == "notloggedin" )
		{
			window.location = rootdir + '/login.php';
		}
		else
		{
			setMessage( 'error', text[1] );
		}

		loadEvents();
		loadEventsSaved();
	}
}

function saveevent( id )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl('index.php');
	ajax.addParam( 'ajaxrequest', 'saveevent' );
	ajax.addParam( 'id', id );
	ajax.send(null);

	function response( text )
	{		
		var parts = text.split( "::" );
		if ( parts[0] == "error" && parts[1] == "notloggedin" )
		{
			window.location = rootdir + '/login.php';
		}
		else if ( parts[0] == "error" && parts[1] != "notloggedin" )
		{
			setMessage( 'error', parts[1] );
		}
		else if ( parts[0] == "success" )
		{
			setMessage( 'notify', parts[1] );
			loadEvents();
			loadEventsSaved();
		}
		else
		{
			setMessage( 'error', 'An error occurred; an unknown response was sent by the server.' );
		}
	}
}

function loadEvents()
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/events/index.php' );
	ajax.addParam( 'ajaxrequest', 'getevents' );	
	ajax.send(null);

	function response( text )
	{		
		var parts = text.split( "::" );
		if ( parts[0] == "error" && parts[1] == "notloggedin" )
		{
			window.location = rootdir + '/login.php';
		}
		else if ( parts[0] == "error" && parts[1] != "notloggedin" )
		{
			setMessage( parts[0], parts[1] );
			document.getElementById('pane_events').innerHTML = "An error occurred, could not load messages.";
		}
		else if ( parts[0] == "success" )
		{
			txt = "";
			for ( i = 1; i < parts.length; i++ )			
				txt += parts[i];

			document.getElementById('pane_events').innerHTML = txt;			
		}
		else
		{
			setMessage( 'error', 'An error occurred, an unknown response was sent by the server!' );
			document.getElementById('pane_events').innerHTML = "An error occurred, could not load messages.";
		}		
	}
}

function loadEventsSaved()
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/events/index.php' );
	ajax.addParam( 'ajaxrequest', 'getsavedevents' );	
	ajax.send(null);

	function response( text )
	{		
		var parts = text.split( "::" );
		if ( parts[0] == "error" && parts[1] == "notloggedin" )
		{
			window.location = rootdir + '/login.php';
		}
		else if ( parts[0] == "error" && parts[1] != "notloggedin" )
		{
			setMessage( parts[0], parts[1] );
			document.getElementById('pane_saved').innerHTML = "An error occurred, could not load messages.";
		}
		else if ( parts[0] == "success" )
		{
			txt = "";
			for ( i = 1; i < parts.length; i++ )			
				txt += parts[i];

			document.getElementById('pane_saved').innerHTML = txt;			
		}
		else
		{
			setMessage( 'error', 'An error occurred, an unknown response was sent by the server!' );
			document.getElementById('pane_saved').innerHTML = "An error occurred, could not load messages.";
		}		
	}
}