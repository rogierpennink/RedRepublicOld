<?
/**
 * The localcity index page.
 */
$nav = "localcity";
$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = true;
include_once "../includes/utility.inc.php";

// Start the real main page
include_once "../includes/header.php";
?>

<h1>Downtown <?=$char->location;?></h1>
<p>You slowly walk over the trottoirs of <?=$char->location;?> and enjoy the view provided by the skyline of the city. As you walk you come across many stores and shops and people are generally being quite nice to you. Forget about your appointments and important meetings, just check out <?=$char->location;?>!</p>

<?
// Query for Businesses in this city
$rec = $db->query( "SELECT * FROM localcity INNER JOIN businesses ON localcity.business_id = businesses.id WHERE location_id=" . $char->location_nr );
$num = $db->getRowCount( $rec );
?>
<div class="title">
	<div style="margin-left: 10px;"><?=$char->location;?>'s Facilities</div>
</div>
<div class="content">
<?
if ( $num == 0 )
{
	echo "This city has no facilities for you to use!\n";
}
else
{
	echo "<table style=\"width: 100%;\">\n";
	$i = 0;
	while ( $res = $db->fetch_array( $rec ) )
	{
		if ( !$i ) echo "<tr>\n";
		?>
			<td style="width: 50%;">
				<table style="width: 100%;">
				<tr>
					<td style="width: 40px; text-align: left;">
						<? if ( $res['icon'] != "" ) { ?><img src="<?=$rootdir."/images/".$res['icon'];?>" alt="" style="height: 32px; width: 32px;" /><? }
						else { ?><img src="../images/nav/nav_localcity.png" alt="" style="height: 32px; width: 32px;" /><? } ?>
					</td>
					<td>
						<a href="<?=$res['url'];?>"><strong><?=$res['name'];?></strong></a><br />
						<?=$res['desc'];?>
					</td>
				</tr>
				</table>
			</td>
		<?
		if ( $i++ ) { echo "</tr>\n"; $i = 0; }
	}
	echo "</table>\n";
}
?>
</div> 
<!-- Government Information -->
<div style="width: 60%; margin-left: auto; margin-right: auto;">
	<? 
	$imgsrcg = ( $_SESSION['show_gov'] ) ? $rootdir . "/images/icon_minimize.gif" : $rootdir . "/images/icon_expand.gif";
	$altg = ( $_SESSION['show_gov'] ) ? "expanded" : "minimized";
	$displayg = ( $_SESSION['show_gov'] ) ? "block" : "none";
	?>
	<div class="titlegov"><div style="float: right;"><img src="<?=$imgsrcg;?>" alt="<?=$altg;?>" style="margin-right: 5px; cursor: pointer;" onclick="setGovernmentView( this );" /></div><?=$char->location;?> Local Government</div>
	<div class="contentgov" id="gov_content" style="display: <?=$displayg;?>;">
	<?
	/* Setup */
	if ( !$gov ) $govtype = 0; else $govtype = $gov->GovernmentID;

	/* Gov Template Info */
	$title = "The " . $char->location . " " . $gov->GovInfo["Formal"];
	$description = $gov->GovInfo["Description"];

	/* Unemployment Checks */
	if ( $govtype == 0 ) 
		$unemployment = "Citizens do not recieve unemployment checks in an anarchy society. ";
	else
		$unemployment = $gov->getStrings( "Unemployment");
	
	/* Leader */
	if ( $govtype == 0 )
		$leader = "";
	else
	{
		$gmn = new Player( $gov->LeaderID, false );
		$leadername = $gmn->info['firstname'] . " " . $gmn->info['lastname'];
		$leader = "The current President is <a href='" . $rootdir . "/profile.php?id=" . $gov->LeaderID . "' style='text-decoration: none;'>"  . $leadername . "</a>. ";
	}

	/* Taxes */
	if ( $govtype == 0 )
		$taxrate = "The city does not have a tax policy. ";
	else
		$taxrate = "The government has set the tax rate to " . $gov->Taxes . "%. ";

	/* Citizen Rights */
	if ( $govtype == 0 )
		$citrights = "While the city is in anarchy, Freedom of Speech and Freedom of Press is pretty much guaranteed.";
	else
	{
		$citrights = $gov->getStrings( "FreeSpeech" );
		$citrights .= $gov->getStrings( "FreePress" );
	}
	?>
	<!-- Start Government Display -->
	<?
	echo "<center><b>" . $title . "</b><br />" . $description . "</center><br /><br />";
	echo $leader . $unemployment . $taxrate . $stats . $citrights . "<br /><br />";

	/* Notice Text */
	if ( $govtype != 0 && $gov->hasNotice() ) 
		print("<img border='0' src='" . $rootdir . "/images/icon_read_red.png' alt='Notice' title='Notice'><font color='#E15959'>The government has posted a notice for the citizens</font>, it reads...<br />" . $gov->Notice . "<br /><br />" );
	
	/* Research */
	$researchquery = $db->query( "SELECT * FROM localgov_research WHERE location_id=" . $char->location_nr );
	if ( $db->getRowCount( $researchquery ) > 0 )
	{
		?>
		<table style="width: 100%;">
			<?
			while ( $research = $db->fetch_array( $researchquery ) )
			{
				$res = $db->getRowsFor( "SELECT * FROM governments_research WHERE id=" . $research["research_id"] );
				?>
				<table>
					<tr>
						<td style="width: 50px; vertical-align: top;"><img src="<?=$rootdir;?>/images/research/<?=$res["icon_url"];?>" alt="" style="border: 0px solid;" /></td>
						<td style="vertical-align: top;"><span style="font-size: 11px;"><b><?=$res["name"];?></b><br /><?=$res["text"];?><br />Status: <span style='color: #723267;'><?=$gov->getResearchStatusText( $research['status'] );?></span></span></td>
					</tr>
				</table>
				<table><tr><td>&nbsp</td></tr></table>
				<?
			}
			?>
		</table>
		<?
	}
	?>
	</div>
</div>
<!-- End Government Information -->

<div style="height: 5px;"><!-- Spacer --></div>

<!-- Start Newspaper -->
<div style="width: 60%; margin-left: auto; margin-right: auto;">
	<? 
	$imgsrcn = ( $_SESSION['show_news'] ) ? $rootdir . "/images/icon_minimize.gif" : $rootdir . "/images/icon_expand.gif";
	$altn = ( $_SESSION['show_news'] ) ? "expanded" : "minimized";
	$displayn = ( $_SESSION['show_news'] ) ? "block" : "none";
	?>
	<div class="titlepaper"><div style="float: right; vertical-align: center;"><img title="Expand" src="<?=$imgsrcn;?>" alt="<?=$altn;?>" style="margin-right: 5px; cursor: pointer;" onclick="setNewspaperView( this );" /></div>Newspaper</div>
	<div class="contentpaper" id="news_content" style="display: <?=$displayn;?>;">
	<?
	if ( !$gov->hasNewspaper() ) 
		print( "<center>No Paper Printed</center>" ); 
	else 
		print( $gov->getNewspaper() );
	?>
	</div>
</div>
<!-- End Newspaper -->

<div><!--Spacer-->&nbsp;</div>
<?
include_once "../includes/footer.php";
?>