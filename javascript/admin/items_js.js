/** The javascript file for the item editor. */
var items_curr_page = 1;
var icons_curr_page = 1;

function item_list( page )
{
	page = page || 1;
	items_curr_page = page;

	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/admin/item_ajax.php' );
	ajax.addParam( 'request', 'itemlist' );
	ajax.addParam( 'page', page );
	ajax.send( null );

	function response( text )
	{
		document.getElementById('pane_items').innerHTML = text;
		itemsHoverOverEffect();
	}
}

function icon_list( page )
{
	page = page || 1;
	icons_curr_page = page;

	var ajax = new AjaxConnection( response );
	ajax.setScriptUrl( rootdir + '/admin/item_ajax.php' );
	ajax.addParam( 'request', 'iconlist' );
	ajax.addParam( 'page', page );
	ajax.send( null );

	function response( text )
	{
		document.getElementById('pane_icons').innerHTML = text;		
	}
}

function getIconImage( icon_id, img_element )
{
	ajax = new AjaxConnection( response );
	ajax.setScriptUrl( 'item_ajax.php' );
	ajax.addParam( 'ajaxrequest', 'icon_image' );
	ajax.addParam( 'icon_id', icon_id );
	ajax.send( null );

	function response( text )
	{
		img_element.src=text;
	}
}