/** comms_compose_js.js */

function sendMessage( form )
{
	var ajax = new AjaxConnection( response );
	var to = form.send_to.value;
	var subject = form.subject.value;
	var message = form.message.value;

	ajax.setScriptUrl( 'index.php' );
	ajax.method = "POST";
	ajax.addParam( 'ajaxrequest', 'compose_msg' );
	ajax.addParam( 'to', to );
	ajax.addParam( 'subject', subject );
	ajax.addParam( 'message', message );
	ajax.send( null );

	function response( text )
	{		
		var parts = text.split( "::" );
		if ( parts[0] == "error" && parts[1] == "notloggedin" )
		{
			window.location = '../login.php';
		}
		else if ( parts[0] == "error" && parts[1] != "notloggedin" )
		{
			setMessage( 'error', parts[1] );
		}
		else if ( parts[0] == "success" )
		{
			comms_pane.selectTab( 'inbox' );
			document.getElementById('compose_form').send_to.value = "";
			document.getElementById('compose_form').subject.value = "";
			document.getElementById('compose_form').message.value = "";
			setMessage( 'notify', parts[1] );
			loadMessagesSent();
		}
		else
		{
			setMessage( 'error', "An error has occurred, an unknown response was sent by the server." );
		}
	}	
}

function reply( id )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/comms/index.php' );
	ajax.addParam( 'ajaxrequest', 'replymessage' );
	ajax.addParam( 'id', id );
	ajax.send( null );

	function response( text )
	{
		form = document.getElementById('compose_form');

		parts = text.split( "::" );

		if ( parts[0] == "error" && parts[1] == "notloggedin" )
		{
			window.location = rootdir + "/login.php";
		}
		else if ( parts[0] == "error" && parts[1] != "notloggedin" )
		{
			setMessage( parts[0], parts[1] );
		}
		else if ( parts[0] == "success" )
		{
			form.send_to.value = parts[1];
			form.subject.value = parts[2];
			msg = "";
			for ( i = 3; i < parts.length; i++ )
				msg += parts[i];
			form.message.value = msg;
		}
		else
		{
			setMessage( 'error', 'An error occurred; an unknown response was sent from the server.' );
		}

		comms_pane.selectTab( 'compose' );
	}	
}

function save( id )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl('index.php');
	ajax.addParam( 'ajaxrequest', 'savemessage' );
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
			loadMessages();
			loadMessagesSent();
			loadMessagesSaved();
		}
		else
		{
			setMessage( 'error', 'An error occurred; an unknown response was sent by the server.' );
		}
	}
}

function delete_msg( id )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl('index.php');
	ajax.addParam( 'ajaxrequest', 'deletemessage' );
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
			loadMessages();
			loadMessagesSent();
			loadMessagesSaved();
		}
		else
		{
			setMessage( 'error', 'An error occurred; an unknown response was sent by the server.' );
		}
	}
}

function delete_selected_messages( which )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl('index.php');
	ajax.method = "POST";
	ajax.addParam( 'ajaxrequest', 'deletemessages' );
	
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

		loadMessages();
		loadMessagesSent();
		loadMessagesSaved();
	}
}

function loadMessages()
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/comms/index.php' );
	ajax.addParam( 'ajaxrequest', 'getmessages' );	
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
			document.getElementById('pane_inbox').innerHTML = "An error occurred, could not load messages.";
		}
		else if ( parts[0] == "success" )
		{
			txt = "";
			for ( i = 1; i < parts.length; i++ )			
				txt += parts[i];

			document.getElementById('pane_inbox').innerHTML = txt;			
		}
		else
		{
			setMessage( 'error', 'An error occurred, an unknown response was sent by the server!' );
			document.getElementById('pane_inbox').innerHTML = "An error occurred, could not load messages.";
		}		
	}
}

function loadMessagesSent()
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/comms/index.php' );
	ajax.addParam( 'ajaxrequest', 'getsentmessages' );	
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
			document.getElementById('pane_sent').innerHTML = "An error occurred, could not load sent messages.";
		}
		else if ( parts[0] == "success" )
		{
			txt = "";
			for ( i = 1; i < parts.length; i++ )			
				txt += parts[i];

			document.getElementById('pane_sent').innerHTML = txt;			
		}
		else
		{
			setMessage( 'error', 'An error occurred, an unknown response was sent by the server!' );
			document.getElementById('pane_sent').innerHTML = "An error occurred, could not load sent messages.";
		}		
	}
}

function loadMessagesSaved()
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/comms/index.php' );
	ajax.addParam( 'ajaxrequest', 'getsavedmessages' );	
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
			document.getElementById('pane_saved').innerHTML = "An error occurred, could not load saved messages.";
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
			document.getElementById('pane_saved').innerHTML = "An error occurred, could not load saved messages.";
		}		
	}
}