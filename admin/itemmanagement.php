<?
$ext_style = "tab_style.css::forums_style.css";
$nav = "itemman";
$AUTH_LEVEL = 10;
$REDIRECT = true;
include_once "../includes/utility.inc.php";

$dontshow = false;
include_once "../includes/header.php";
?>
<script type="text/javascript" src="../javascript/tabbedpane.js"></script>
<script type="text/javascript" src="../javascript/admin/items_js.js"></script>

<h1>Item/Icon Editor</h1>
<p>Items are an essential component of Red Republic and the flexibility of adding, modifying and deleting items quickly and easily is an absolute necessity if new content is to be created on a regular basis - content, including new items or modifying old ones.<br />
While this is a very useful tool, it is also powerful. Please keep in mind that changing item values may directly or indirectly cause undesirable effects to the game-balance and even the game play experience for many users.</p>

<?
require_once "items.modifyitems.php";
require_once "items.modifyicons.php";

if ( !$dontshow )
{
?>

<!-- The Tabs ( Items / Icons ) -->
<div style="width: auto; height: 21px;">
	<div class="tab_selected" id="tab1">
		Item Management
	</div>
	<div class="tab_unselected" id="tab2">
		Icon Management
	</div>	
</div>

<div>
	<div class="content" id="pane_items" style="padding: 0px; margin-bottom: 30px; border-top: solid 1px #000;">
	Loading... <img src="../images/loading.gif" alt="Loading" />
	</div>

	<div class="content" id="pane_icons" style="padding: 0px; display: none; margin-bottom: 30px; border-top: solid 1px #000;">
	Loading... <img src="../images/loading.gif" alt="Loading" />
	</div>
</div>

<div style="margin-bottom: 30px; margin-top: 20px;">
<input type="button" class="std_input" value="Add an Item" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px;" onclick="window.location = 'itemmanagement.php?act=add';" />

<input type="button" class="std_input" value="Add an Icon" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px;" onclick="window.location = 'itemmanagement.php?act=addicon';" />
</div>

<!-- The javascript for the tabs. -->
<script type="text/javascript">
var item_pane = new TabbedPane( 'tab_selected', 'tab_unselected' );
item_pane.addTab( 'items', document.getElementById('tab1'), document.getElementById('pane_items') );
item_pane.addTab( 'icons', document.getElementById('tab2'), document.getElementById('pane_icons') );
item_list();
icon_list();
</script>

<?
}
include_once "../includes/footer.php";
?>