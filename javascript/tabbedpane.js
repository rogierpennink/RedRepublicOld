/**
 * The TabbedPane class will provide a way to associate certain elements with a tab. The tab also 
 * has to be an element.
 */
function TabbedPane( selTabClass, unSelTabClass)
{
	// Two arrays, one of tabs, one of content
	this.tabs = new Array();
	this.panes = new Array();
	this.selectedTabClassName = selTabClass;
	this.unselectedTabClassName = unSelTabClass;

	// The addTab function requires a tab element and a content element and a
	// tab ID to associate the tab and the pane with each other.
	this.addTab = function( tabID, tab, pane )
	{
		if ( this.tabs.length != this.panes.length ) return false; // Internal error

		var index = this.tabs.length;
		this.tabs[index] = new Array();
		this.tabs[index]["tab"] = tab;
		this.tabs[index]["tabid"] = tabID;
		this.panes[index] = new Array();
		this.panes[index]["pane"] = pane;
		this.panes[index]["tabid"] = tabID;

		// Add Click listeners to the tabs
		var IE = document.all;
		var tid = this.tabs[index]["tabid"];
		var t = this;

		if ( IE )
		{
			this.tabs[index]["tab"].attachEvent( 'onclick', function(){ tabSelectEvent( t, tid ); } );
		}
		else
		{
			this.tabs[index]["tab"].addEventListener( 'click', function(){ tabSelectEvent( t, tid ); }, false );
		}

		return true;
	};		

	// The selectTab function takes a tabID and attempts to select that tab. The current
	// tab will be deselected and if tabID is not valid, the user will end up with no selection.
	this.selectTab = function( tabID )
	{
		if ( this.tabs.length != this.panes.length ) return false; // Internal error

		// Search for currently selected tabs		
		for ( var i = 0; i < this.tabs.length; i++ )
		{			
			if ( this.tabs[i]["tab"].className == this.selectedTabClassName )
			{				
				this.tabs[i]["tab"].className = this.unselectedTabClassName;	
				if ( this.panes[i]["pane"] != null ) 
				{ 						
					this.panes[i]["pane"].style.display = 'none'; 
					this.panes[i]["pane"].style.visibility = 'hidden';	 					
				}
			}
		}

		for ( var j = 0; j < this.panes.length; j++ )
		{
			// Check for tabID
			if ( this.panes[j]["tabid"] == tabID )
			{			
				this.tabs[j]["tab"].className = this.selectedTabClassName;				
				if ( this.panes[j]["pane"] != null ) 
				{
					this.panes[j]["pane"].style.display = 'block';
					this.panes[j]["pane"].style.visibility = "visible";					
				}

				return true;
			}
		}

		return false;
	};
}

// The tabSelectEvent function does the 'automatic' listening for clicks on tabs...
function tabSelectEvent( el, tabid )
{
	el.selectTab( tabid );
}