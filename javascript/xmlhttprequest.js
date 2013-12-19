function AjaxConnection( call_back )
{
	var _this = this;

	// The getConn is used to create an internal 'connection' object with 
	// which connections to php scripts can be made.
	this.getConn = function() 
	{
		var xmlhttp;

		try 
		{	
			// Firefox and IE 7
			xmlhttp = new XMLHttpRequest();
		} 
		catch ( e )
		{
			// The old IE ( <= 6 )
			var XMLHTTP_IDS = new Array( 'MSXML2.XMLHTTP.5.0',
										'MSXML2.XMLHTTP.4.0',
										'MSXML2.XMLHTTP.3.0',
										'MSXML2.XMLHTTP',
										'Microsoft.XMLHTTP' );
			var success = false;
		 
			for ( var i = 0; i < XMLHTTP_IDS.length && !success; i++ ) 
			{
				try 
				{
					xmlhttp = new ActiveXObject( XMLHTTP_IDS[i] );
					success = true;
				} 
				catch ( e ) 
				{
				}
			}

			if ( !success ) 
			{
				throw new Error('Unable to create XMLHttpRequest.');
			}
		}
		try
		{
			xmlhttp.setRequestHeader( "Connection", "close" );
		}
		catch ( e )
		{
		}
		
		return xmlhttp;
	};

	this.stateHandler = function()
	{
		if ( conn.readyState == 4 )
		{			
			if ( _this.xml )			
				_this.callback( conn.responseXML );
			else
				_this.callback( conn.responseText );			
		}
	};

	// Public member fields
	this.callback = call_back;
	this.params = new Array();
	this.url = null;
	this.method = "GET";	// GET is the protocol we use by default	
	this.xml = false;
	
	// Private member fields
	var self = this;	// Necessary for the asynchronous part where 'this' is no longer valid
	var conn = this.getConn();	
	conn.onreadystatechange = this.stateHandler;

	// The send method will send a normal http request to the specified URL with specified parameters.
	// Optionally, data can be sent as well but this is not usually done.
	this.send = function( data )
	{			
		var length = this.params.length;
		var parameters = ( length == 0 ) ? "" : "?";	
		
		// Put the arguments in a string
		for ( var i = 0; i < length; i++)
		{
			parameters += this.params[i]["name"] + "=" + this.params[i]["value"];
			if ( i < length - 1 ) parameters += "&";
		}

		if ( this.method == "POST" )
		{					
			data = parameters.substring( 1 );
		}

		try
		{				
			if ( this.method == "POST" )				
				conn.open( this.method, this.url, true );	
			else			
				conn.open( this.method, this.url + parameters, true );
			
			conn.onreadystatechange = this.stateHandler;

			if ( this.xml )
				try{ conn.setRequestHeader( "Content-type", "text/xml" ); }
				catch( e ){}
			else if ( this.method == "POST" )				
				try{ conn.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded; charset=UTF-8' ); }
				catch ( e ){} 										
			else			
				try{ conn.setRequestHeader( "Content-type", "text/html" ); }
				catch( e ){}
				
			conn.send( data );		
		}
		catch ( e )
		{			
		}		
	};	

	// The addParam function will add a parameter to the string that is to be concatenated
	// to the URL. Two arguments are passed, the parameter's name and it's value; both are strings.
	// Furthermore, this is to be used with the GET method of sending.
	this.addParam = function( name, value )
	{		
		var index = this.params.length;

		// Check if a param with that name is already in the list
		for ( var i = 0; i < index; i++ )
		{
			if ( this.params[i]["name"] == name ) return false;
		}		

		this.params[index] = new Array();
		this.params[index]["name"] = name;
		this.params[index]["value"] = value;		

		return true;
	};

	// The clearParams method will clear all params so a new request can be formed
	this.clearParams = function()
	{		
		this.params = new Array();
	};

	// The removeParam method will remove a param from the parameter list. Addresses
	// said parameter by it's name
	this.removeParam = function( name )
	{
		var length = this.params.length;
		var index = -1;

		// Check if a param with that name is already in the list
		for ( var i = 0; i < length; i++ )
		{
			if ( this.params[i]["name"] == name ) index = i;
		}	

		if ( index == -1 )
		{
			return false; // The parameter wasn't in the list
		}
		else
		{
			this.params.splice( index, 1 ); // Remove the element
		}
	};

	// The setScriptUrl method sets the server script url that should be used for
	// the next send action. Example: http://www.examples.com/scripts/myscript.php
	this.setScriptUrl = function( scripturl )
	{
		this.url = scripturl;
	};	
}
