/**
 * general_js.js - this file will contain general functions and classes that apply
 * for every page out there.
 */

var rootdir = "http://localhost/red-republic";

window.onload = initGeneral;

function rnd( num, dec )
{	
	return Math.round( num * Math.pow( 10, dec ) ) / Math.pow( 10, dec );
}

function getElementsByClassName( clsname )
{
	var retarray = new Array();
	var j = 0;
	for ( var i = 0; i < document.getElementsByTagName( "*" ).length; i++ )
	{
		if ( document.getElementsByTagName("*")[i].className == clsname )
		{			
			retarray[j] = document.getElementsByTagName("*")[i];			
			j++;
		}
	}

	return retarray;
}

function getSrcElement( e )
{
	// The source element
	var src;

	// Make sure we can use 'e'
	if ( !e ) var e = window.event;

	// Retrieve a valid source element
	if ( e.target ) src = e.target;
	else if ( e.srcElement ) src = e.srcElement;

	return src;
}

function buttonHoverOverEffect()
{
	var buttons = getElementsByClassName( "linkbarbutton" );
	
	for ( var i = 0; i < buttons.length; i++ )
	{		
		var b = buttons[i];		
		if ( document.all )		// IE specific
		{			
			b.attachEvent( 'onmouseover', function( e ) { getSrcElement( e ).style.background = "url('"+rootdir+"/images/grad_invert_20.png')";	} );
			b.attachEvent( 'onmouseout', function( e ) { getSrcElement( e ).style.background = "none"; } );
		} 
		else
		{
			b.addEventListener( 'mouseover', function( e ) { getSrcElement( e ).style.background = "url('"+rootdir+"/images/grad_invert_20.png')"; }, false );
			b.addEventListener( 'mouseout', function( e ) { getSrcElement( e ).style.background = "none"; }, false );
		}
	}
}

function getMousePos( e )
{
	var posx = 0; var posy = 0;

	if (!e) var e = window.event;
	if ( e.pageX || e.pageY ) 	
	{
		posx = e.pageX;
		posy = e.pageY;
	}
	else if ( e.clientX || e.clientY ) 
	{
		posx = e.clientX + document.body.scrollLeft
			+ document.documentElement.scrollLeft;
		posy = e.clientY + document.body.scrollTop
			+ document.documentElement.scrollTop;
	}

	retarray = new Array();
	retarray[0] = posx; retarray[1] = posy;
	
	return retarray;
}

function itemsHoverOverEffect()
{
	var items = document.getElementsByTagName( "img" );

	for ( i = 0; i < items.length; i++ )
	{
		if ( items[i].id.substr( 0, 4 ) == "item" )
		{
			if ( document.all )	 // IE SPECIFIC
			{
				items[i].attachEvent( 'onmouseover', function( e ) { showItemInfo( e ); } );
				items[i].attachEvent( 'onmouseout', function( e ) { hideItemInfo( e ); } );
			}
			else
			{
				items[i].addEventListener( 'mouseover', function( e ) { showItemInfo( e ); }, false );
				items[i].addEventListener( 'mouseout', function( e ) { hideItemInfo( e ); }, false );
			}
		}
	}
}

function showItemInfo( e )
{
	itemimg = getSrcElement( e );
	mouse = getMousePos( e );
	itemid = itemimg.id.substring( 4 );

	// If the div already exists, we just show it...
	if ( document.getElementById('itemdiv'+itemid) )
	{		
		document.getElementById('itemdiv'+itemid).style.left = ( mouse[0] + 10 ) + 'px';
		document.getElementById('itemdiv'+itemid).style.top = ( mouse[1] + 10 ) + 'px';
		document.getElementById('itemdiv'+itemid).style.display = 'block';
		return;
	}
	
	// Create the information element.
	var infodiv = document.createElement( 'div' );
	infodiv.setAttribute( 'id', 'itemdiv' + itemid );	
	infodiv.setAttribute( 'class', 'iteminfo' );
	infodiv.setAttribute( 'className', 'iteminfo' );
	infodiv.setAttribute( 'style', 'z-index: 4568; top: ' + ( mouse[1] + 10 ) + 'px; left: ' + ( mouse[0] + 10 ) + 'px;' );

	// IE SPECIFIC!
	if ( infodiv.style.setAttribute )
		infodiv.style.setAttribute( 'cssText', 'top: ' + ( mouse[1] + 10 ) + 'px; left: ' + ( mouse[0] + 10 ) + 'px;', 0 );
		
	// The contents of the
	infodiv.innerHTML = "<div class='title' style='border: none; padding-left: 10px;'>Loading...</div>";
	infodiv.innerHTML += "<div class='iteminfo'><img src='" + rootdir + "/images/loading.gif' alt='' /></div>";

	document.getElementById('body').appendChild( infodiv );	

	ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/includes/iteminfo.php' );
	ajax.addParam( 'item_id', itemid );
	ajax.send( null );

	function response( text )
	{
		html = "";
		text = text.split( "::" );
		switch ( text[0] )
		{
			case "error":
				if ( text[1] == "notloggedin" ) window.location = rootdir + 'login.php';
				else
				{	
					html = "<div class='title' style='border: none; padding-left: 10px;'>Error</div>";
					html += "<div class='iteminfo'>" + text[1] + "</div>";
				}
			break;
			case "success":
				speedcolor = 'green';
				if ( text[18] >= 45 && text[18] < 60 ) speedcolor = 'orange';
				if ( text[18] >= 60 ) speedcolor = 'red';
				
				color = ( text[3] == "0" ) ? "#999999" : ( text[3] == "1" ) ? "#007700" : ( text[3] == "2" ) ? "#0000FF" : "#6600CC";
				html = "<div class='title' style='border: none; padding-left: 10px;'>" + text[2] + "</div>";
				html += "<div class='iteminfo' style='font-weight: normal;'>";
				html += "<table width='100%' cellspacing='0' style='margin: 7px; margin-top: 2px;'>";
				html += "<tr><td style='color: " + color + "; font-weight: 900;'>" + text[2] + "</td></tr>";
				html += "<tr><td style='font-weight: bold; font-size: 10px;'>This item can " + ( ( text[5] == "0" ) ? "<span style='color: #CC0000;'>not</span> " : "" ) + "be traded</td></tr>";
				html += "<tr><td>Category: <span style='color: #613A3C;'>" + text[7] + "</span></td></tr>";
				if ( text[6] >= 2 && text[6] <= 5 ) html += "<tr><td>" + text[11] + " Armour</td></tr>";
				if ( text[6] == 6 || text[6] == 7 || text[6] == 9 ) html += "<tr><td style='color: red'>" + text[16] + " - " + text[17] + " Damage</td><td style='color: " + speedcolor + "'>Speed: " + text[18] + "</td></tr>"
				if ( text[6] == 6 || text[6] == 7 || text[6] == 9 ) html += "<tr><td>(<span style='color: #508718;'>" + text[19] + "</span> damage per hour)</td></tr>";
				if ( text[12] != 0 ) html += "<tr><td>" + ( ( text[12] > 0 ) ? "+" + text[12] : text[12] ) + " strength</td></tr>";
				if ( text[13] != 0 ) html += "<tr><td>" + ( ( text[13] > 0 ) ? "+" + text[13] : text[13] ) + " defense</td></tr>";
				if ( text[14] != 0 ) html += "<tr><td>" + ( ( text[14] > 0 ) ? "+" + text[14] : text[14] ) + " intellect</td></tr>";
				if ( text[15] != 0 ) html += "<tr><td>" + ( ( text[15] > 0 ) ? "+" + text[15] : text[15] ) + " cunning</td></tr>";
				if ( text[6] == "8" ) html += "<tr><td>This bag contains " + text[10] + " slots</td></tr>";
				html += "<tr><td>Requires tier <span style='color: #3300CC;'>" + text[4] + "</span></td></tr>";
				html += "<tr><td style='font-weight: bold; font-size: 10px;'>Rough Worth: $" + text[9] + "</td></tr>";
				if ( text[7] == "1" ) html += "<tr><td>This item can be equipped</td></tr>";
				html += "</table>";
				html += "</div>";				
			break;
			default:				
				html = "<div class='title' style='border: none; padding-left: 10px;'>Error</div>";
				html += "<div class='iteminfo'>An unknown error occurred.</div>";
		}

		infodiv.innerHTML = html;
	}
}

function hideItemInfo( e )
{
	itemimg = getSrcElement( e );
	itemid = itemimg.id.substring( 4 );

	// If the div already exists, we just hide it...
	if ( document.getElementById('itemdiv'+itemid) )
	{		
		document.getElementById('itemdiv'+itemid).style.display = 'none';
	}
}

function initGeneral()
{
	// Initiate the hover-over display for buttons
	buttonHoverOverEffect();	

	// Call the item-hover-over effects function
	itemsHoverOverEffect();
}

function login( username, password, quick )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/login.php' );

	type = ( quick == true ) ? 'quicklogin' : 'normallogin';
	
	ajax.addParam( 'requesttype', type );
	ajax.addParam( 'user', username );
	ajax.addParam( 'pass', password );
	ajax.send( null );

	function response( text )
	{			
		var parts = text.split( "::" );
		if ( parts[0] == "error" )
		{
			setMessage( "error", parts[1] );			
		}
		else if ( parts[0] == "quicklogin" && parts[1] == "success" )
		{
			window.location = rootdir + '/account.php';
		}
		else if ( parts[0] == "normallogin" && parts[1] == "success" )
		{
			window.location = rootdir + '/account.php';
		}
		else
		{
			setMessage( "error", "An error occurred, an unknown response was received from the server." );
		}
	}
}

function setMessage( type, message )
{
	if ( type == "notify" )
	{
		document.getElementById('errordisplay').style.visibility = 'hidden';
		document.getElementById('errordisplay').style.display = 'none';
		
		document.getElementById('notifydisplay').style.visibility = 'visible';
		document.getElementById('notifydisplay').style.display = 'block';
		document.getElementById('notifydisplay').innerHTML = "<img src=\""+rootdir+"/images/checkOK.png\" alt=\"\" style=\"margin-right: 7px;\" />";	
		document.getElementById('notifydisplay').innerHTML += message;		
	}
	if ( type == "error" )
	{
		document.getElementById('notifydisplay').style.visibility = 'hidden';
		document.getElementById('notifydisplay').style.display = 'none';
		
		document.getElementById('errordisplay').style.visibility = 'visible';
		document.getElementById('errordisplay').style.display = 'block';
		document.getElementById('errordisplay').innerHTML = "<img src=\""+rootdir+"/images/checkError.png\" alt=\"\" style=\"margin-right: 7px;\" />";	
		document.getElementById('errordisplay').innerHTML += message;
	}
}

function closeMessages()
{
	document.getElementById('notifydisplay').style.visibility = 'hidden';
	document.getElementById('notifydisplay').style.display = 'none';
	document.getElementById('errordisplay').style.visibility = 'hidden';
	document.getElementById('errordisplay').style.display = 'none';
}	

function showUserList()
{
	scroll( 0, 0 );
	document.getElementById('usercontainer').style.visibility = 'visible';
	document.getElementById('glasspane').style.visibility = 'visible';
	document.getElementById('userlist').innerHTML = 'Loading user list...' + '<img src="' + rootdir + '/images/loading.gif" alt="Loading" style="margin-left: 7px;" />';

	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/includes/user_list.php' );
	ajax.send( null );

	function response( text )
	{
		parts = text.split( "::" );
		if ( parts[0] == "!error" && parts[1] == "notloggedin" )
		{
			window.location = rootdir + "/login.php";
		}
		else if ( parts[0] == "!error" && parts[1] != "notloggedin" )
		{
			setMessage( 'error', parts[1] );
		}
		else if ( text == "!no_users" )
		{
			document.getElementById('userlist').innerHTML = "There are currently no users online!";
		}
		else if ( parts[0] == "!success" )
		{			
			// List the names!
			html = "";
			for ( i = 1; i < parts.length; i++ )
			{
				user = parts[i].split( "!!" );
				html += " <a href=\"" + user[1] + "\">" + user[0] + "</a> ";				
				if ( i == ( parts.length - 1 ) ) html += ""; else html += "| ";
			}			
			document.getElementById('userlist').innerHTML = html;			
		}
		else
		{
			setMessage( 'error', 'An error occurred, an unknown response was sent by the server.' );
		}
	}
}

function closeUserList()
{
	document.getElementById('usercontainer').style.visibility = 'hidden';
	document.getElementById('glasspane').style.visibility = 'hidden';	
}

function showInventory()
{
	document.getElementById('inventory').style.visibility = 'visible';
	document.getElementById('glasspane').style.visibility = 'visible';
}

function closeInventory()
{
	document.getElementById('inventory').style.visibility = 'hidden';
	document.getElementById('glasspane').style.visibility = 'hidden';
}

function showCharacter()
{
	document.getElementById('charinfo').style.visibility = 'visible';
	document.getElementById('glasspane').style.visibility = 'visible';
}

function closeCharacter()
{
	document.getElementById('charinfo').style.visibility = 'hidden';
	document.getElementById('glasspane').style.visibility = 'hidden';
}

function showBeer()
{
	document.getElementById('beerinfo').style.visibility = 'visible';
	document.getElementById('glasspane').style.visibility = 'visible';
}

function closeBeer()
{
	document.getElementById('beerinfo').style.visibility = 'hidden';
	document.getElementById('glasspane').style.visibility = 'hidden';
}

function warnDisplay()
{
	document.getElementById('warndisplay').style.visibility = 'visible';
	document.getElementById('glasspane').style.visibility = 'visible';
}

function closeWarnDisplay()
{
	document.getElementById('warndisplay').style.visibility = 'hidden';
	document.getElementById('glasspane').style.visibility = 'hidden';
}

function openErrorDisplay()
{
	document.getElementById('errordisplayex').style.visibility = 'visible';
	document.getElementById('glasspane').style.visibility = 'visible';
}

function closeErrorDisplay()
{
	document.getElementById('errordisplayex').style.visibility = 'hidden';
	document.getElementById('glasspane').style.visibility = 'hidden';
}

function showNewTopic()
{
	document.getElementById('newTopicForm').style.visibility='visible';
}

function closeNewTopic()
{
	document.getElementById('newTopicForm').style.visibility='hidden';
}

function openEventRequest()
{
	document.getElementById('eventrequest').style.visibility = 'visible';
	document.getElementById('glasspane').style.visibility = 'visible';
}

function closeEventRequest()
{
	document.getElementById('eventrequest').style.visibility = 'hidden';
	document.getElementById('glasspane').style.visibility = 'hidden';
}

function setDebugView( img )
{
	if ( img.alt == "expanded" )
	{
		document.getElementById('debug_content').style.display = 'none';
		img.src = rootdir + "/images/icon_expand.gif";
		img.alt = "minimized";
	}
	else
	{
		document.getElementById('debug_content').style.display = 'block';
		img.src = rootdir + "/images/icon_minimize.gif";
		img.alt = "expanded";
	}

	// Attempt to set a session for further use
	var ajax = new AjaxConnection( function( text ) { } );
	ajax.setScriptUrl( rootdir + "/includes/header.php" );
	ajax.addParam( 'ajaxrequest', 'show_debug' );
	ajax.send( null );
}

function setGovernmentView( img )
{
	if ( img.alt == "expanded" )
	{
		document.getElementById('gov_content').style.display = 'none';
		img.src = rootdir + "/images/icon_expand.gif";
		img.alt = "minimized";
	}
	else
	{
		document.getElementById('gov_content').style.display = 'block';
		img.src = rootdir + "/images/icon_minimize.gif";
		img.alt = "expanded";
	}

	// Attempt to set a session for further use
	var ajax = new AjaxConnection( function( text ) { } );
	ajax.setScriptUrl( rootdir + "/includes/header.php" );
	ajax.addParam( 'ajaxrequest', 'show_gov' );
	ajax.send( null );
}

function setNewspaperView( img )
{
	if ( img.alt == "expanded" )
	{
		document.getElementById('news_content').style.display = 'none';
		img.src = rootdir + "/images/icon_expand.gif";
		img.alt = "minimized";
	}
	else
	{
		document.getElementById('news_content').style.display = 'block';
		img.src = rootdir + "/images/icon_minimize.gif";
		img.alt = "expanded";
	}

	// Attempt to set a session for further use
	var ajax = new AjaxConnection( function( text ) { } );
	ajax.setScriptUrl( rootdir + "/includes/header.php" );
	ajax.addParam( 'ajaxrequest', 'show_news' );
	ajax.send( null );
}

function setPartyView( img )
{
	if ( img.alt == "expanded" )
	{
		document.getElementById('party_content').style.display = 'none';
		img.src = rootdir + "/images/icon_expand.gif";
		img.alt = "minimized";
	}
	else
	{
		document.getElementById('party_content').style.display = 'block';
		img.src = rootdir + "/images/icon_minimize.gif";
		img.alt = "expanded";
	}

	// Attempt to set a session for further use
	var ajax = new AjaxConnection( function( text ) { } );
	ajax.setScriptUrl( rootdir + "/includes/header.php" );
	ajax.addParam( 'ajaxrequest', 'show_parties' );
	ajax.send( null );
}

function loadCharacterInfo( msg )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/includes/character_info.php' );	
	if ( msg ) ajax.addParam( 'msg', msg );
	ajax.send( null );

	function response( text )
	{
		document.getElementById( 'charinfo' ).innerHTML = text;
		itemsHoverOverEffect();
	}
}

function loadInventory( inv_id, inv_message )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/includes/inventory.php' );
	ajax.addParam( 'invmessage', inv_message );
	ajax.send( null );

	function response( text )
	{
		document.getElementById( inv_id ).innerHTML = text;
		itemsHoverOverEffect();
	}
}

function equip_item( item_id )
{	
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/includes/equipitem.php' );
	ajax.addParam( 'item_id', item_id );
	ajax.send( null );
	
	function response( text )
	{		
		loadInventory( 'inventory', text );
		loadCharacterInfo();
		document.getElementById('itemdiv'+item_id).style.display = 'none';
	}
}

function de_equip_item( item_id )
{
	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/includes/deequipitem.php' );
	ajax.addParam( 'item_id', item_id );
	ajax.send( null );
	
	function response( text )
	{		
		loadCharacterInfo( text );
		loadInventory( 'inventory', '' );
		document.getElementById('itemdiv'+item_id).style.display = 'none';
	}
}
