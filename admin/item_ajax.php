<?
include_once "../includes/utility.inc.php";

if ( $_GET['ajaxrequest'] == 'icon_image' && isset( $_GET['icon_id'] ) && $_GET['icon_id'] != "" )
{
	$iconquery = $db->query( "SELECT * FROM icons WHERE icon_id=". $db->prepval( $_GET['icon_id'] ) ." LIMIT 1" );
	$icon = $db->fetch_array( $iconquery );

	echo $rootdir . "/images/" . $icon['url'];
	exit;
}

if ( $_GET['request'] == 'itemlist' )
{
	/* Some paging variables. */
	$numperpage = 15;
	$page = ( isset( $_GET['page'] ) && $_GET['page'] != "" ) ? $_GET['page'] : 1;
	$offset = ( $page - 1 ) * $numperpage;

	/* Query the database. */
	$numrecords = $db->getRowCount( $db->query( "SELECT * FROM items INNER JOIN icons ON items.icon = icons.icon_id" ) );
	$q = $db->query( "SELECT * FROM items INNER JOIN icons ON items.icon = icons.icon_id ORDER BY name, quality LIMIT $offset,$numperpage" );

	/* Generate the html. */
	?>
	<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
		<table class="row">
			<tr>		
				<td class="field" style="width: 75px;"><strong>Icon</strong></td>
				<td class="field" style="width: 25%;"><strong>Item Name</strong></td>
				<td class="field" style="width: 25%;"><strong>Item Quality</strong></td>
				<td class="field" style="width: 25%;"><strong>Tier Requirement</strong></td>
				<td class="field"><strong>Options</strong></td>
			</tr>
		</table>
	</div>
	<!-- Paging -->
	<div class="row">		
		<div style="padding: 5px; font-family: Verdana; font-size: 11px;">
		<? 
		if ( $page > 1 ) echo "<a href=\"javascript: item_list( ". ( $page - 1 ) ." );\">";
		echo "<<<";
		if ( $page > 1 ) echo "</a>";
		for ( $i = 0; $i < ceil( $numrecords / $numperpage ); $i++ ) 
		{
			echo " ";
			if ( $i + 1 != $page ) echo "<a href=\"javascript: item_list( ". ( $i + 1 ) ." );\">";
			echo ( $i + 1 );
			if ( $i + 1 != $page ) echo "</a>";
		}
		echo " ";
		if ( $page < ceil( $numrecords / $numperpage ) ) echo "<a href=\"javascript: item_list( ". ( $page + 1 ) ." );\">";
		echo ">>>";
		if ( $page < ceil( $numrecords / $numperpage ) ) echo "</a>";
		?>
		</div>
	</div>
	<?	
	while ( $r = $db->fetch_array( $q ) )
	{
		?>
		<div class="row">
			<table class="row">
				<tr>
					<td class="field" style="width: 75px;">
						<img id="item<?=$r['item_id'];?>" src="<?=$rootdir;?>/images/<?=$r['url'];?>" alt="" />
					</td>
					<td class="field" style="width: 25%;"><?=$r['name'];?></td>
					<td class="field" style="width: 25%;"><?=qualityString( $r['quality'] );?> Quality</td>
					<td class="field" style="width: 25%;">Requires Tier <?=$r['tier'];?></td>
					<td class="field"><a href="itemmanagement.php?act=delitem&amp;id=<?=$r['item_id'];?>">delete</a> <a href="itemmanagement.php?act=edit&amp;id=<?=$r['item_id'];?>">edit</a></td>
				</tr>
			</table>
		</div>
		<?
	}
	?>
	<!-- Paging -->
	<div class="row">		
		<div style="padding: 5px; font-family: Verdana; font-size: 11px;">
		<? 
		if ( $page > 1 ) echo "<a href=\"javascript: item_list( ". ( $page - 1 ) ." );\">";
		echo "<<<";
		if ( $page > 1 ) echo "</a>";
		for ( $i = 0; $i < ceil( $numrecords / $numperpage ); $i++ ) 
		{
			echo " ";
			if ( $i + 1 != $page ) echo "<a href=\"javascript: item_list( ". ( $i + 1 ) ." );\">";
			echo ( $i + 1 );
			if ( $i + 1 != $page ) echo "</a>";
		}
		echo " ";
		if ( $page < ceil( $numrecords / $numperpage ) ) echo "<a href=\"javascript: item_list( ". ( $page + 1 ) ." );\">";
		echo ">>>";
		if ( $page < ceil( $numrecords / $numperpage ) ) echo "</a>";
		?>
		</div>
	</div>
	<?
}

if ( $_GET['request'] == "iconlist" )
{
	/* Some paging variables. */
	$numperpage = 15;
	$page = ( isset( $_GET['page'] ) && $_GET['page'] != "" ) ? $_GET['page'] : 1;
	$offset = ( $page - 1 ) * $numperpage;

	/* Query the database. */
	$numrecords = $db->getRowCount( $db->query( "SELECT * FROM icons" ) );
	$q = $db->query( "SELECT * FROM icons ORDER BY url LIMIT $offset,$numperpage" );

	/* Generate the html. */
	?>
	<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
		<table class="row">
			<tr>		
				<td class="field" style="width: 75px;"><strong>Icon</strong></td>
				<td class="field" style="width: 55%;"><strong>Icon Url</strong></td>
				<td class="field" style="width: 15%;"><strong>Icon Id</strong>				
				<td class="field"><strong>Options</strong>
			</tr>
		</table>
	</div>
	<!-- Paging -->
	<div class="row">		
		<div style="padding: 5px; font-family: Verdana; font-size: 11px;">
		<? 
		if ( $page > 1 ) echo "<a href=\"javascript: icon_list( ". ( $page - 1 ) ." );\">";
		echo "<<<";
		if ( $page > 1 ) echo "</a>";
		for ( $i = 0; $i < ceil( $numrecords / $numperpage ); $i++ ) 
		{
			echo " ";
			if ( $i + 1 != $page ) echo "<a href=\"javascript: icon_list( ". ( $i + 1 ) ." );\">";
			echo ( $i + 1 );
			if ( $i + 1 != $page ) echo "</a>";
		}
		echo " ";
		if ( $page < ceil( $numrecords / $numperpage ) ) echo "<a href=\"javascript: icon_list( ". ( $page + 1 ) ." );\">";
		echo ">>>";
		if ( $page < ceil( $numrecords / $numperpage ) ) echo "</a>";
		?>
		</div>
	</div>
	<?	
	while ( $r = $db->fetch_array( $q ) )
	{
		?>
		<div class="row">
			<table class="row">
				<tr>
					<td class="field" style="width: 75px;">
						<img src="<?=$rootdir;?>/images/<?=$r['url'];?>" alt="" />
					</td>
					<td class="field" style="width: 55%;"><?=$rootdir;?>/<b><?=$r['url'];?></b></td>
					<td class="field" style="width: 15%;">ID <?=$r['icon_id'];?></td>					
					<td class="field"><a href="itemmanagement.php?act=delicon&amp;icon_id=<?=$r['icon_id'];?>">delete</a></td>
				</tr>
			</table>
		</div>
		<?
	}
	?>
	<!-- Paging -->
	<div class="row">		
		<div style="padding: 5px; font-family: Verdana; font-size: 11px;">
		<? 
		if ( $page > 1 ) echo "<a href=\"javascript: icon_list( ". ( $page - 1 ) ." );\">";
		echo "<<<";
		if ( $page > 1 ) echo "</a>";
		for ( $i = 0; $i < ceil( $numrecords / $numperpage ); $i++ ) 
		{
			echo " ";
			if ( $i + 1 != $page ) echo "<a href=\"javascript: icon_list( ". ( $i + 1 ) ." );\">";
			echo ( $i + 1 );
			if ( $i + 1 != $page ) echo "</a>";
		}
		echo " ";
		if ( $page < ceil( $numrecords / $numperpage ) ) echo "<a href=\"javascript: icon_list( ". ( $page + 1 ) ." );\">";
		echo ">>>";
		if ( $page < ceil( $numrecords / $numperpage ) ) echo "</a>";
		?>
		</div>
	</div>
	<?
}
?>