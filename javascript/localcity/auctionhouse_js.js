function getItems( page, category, quality, name )
{
	ajax = new AjaxConnection( response );
	ajax.setScriptUrl( '../localcity/auction_ajax.php' );
	ajax.addParam( 'reqtype', 'getitemlist' );
	ajax.addParam( 'keywords', name );
	ajax.addParam( 'category', category );
	ajax.addParam( 'page', page );
	ajax.addParam( 'quality', quality );
	ajax.send( null );

	function response( text )
	{
		document.getElementById('auctionhouse_itemlist').innerHTML = text;
		itemsHoverOverEffect();
	}
}