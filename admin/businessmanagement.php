<?
$ext_style="forums_style.css";
$nav = "businessman";

$AUTH_LEVEL = 10;
$REDIRECT = true;
include_once "../includes/utility.inc.php";

include_once "../includes/header.php";

?>
<h1>Business Management</h1>
<p>Welcome to the business management tool. Using this tool you can add or delete businesses to the database and associate already created businesses with certain cities.<br /><strong>Please Note:</strong> This is a powerful tool, use it wisely.</p>

<!-- City Selection -->
<div style="width: 220px;">
	<div class="title" style="padding-left: 10px;">
		Select a City
	</div>
	<div class="content">
		<select class="std_input" style="width: 200px;" onchange="javascript:window.location='<?=$PHP_SELF;?>?locid='+this.value;">
			<?
			$loc = "";
			$q = $db->query( "SELECT * FROM locations" );
			while ( $r = $db->fetch_array( $q ) )
			{
				echo "<option value='".$r['id']."' ". ( ( $_GET['locid'] == $r['id'] ) ? "selected='true'" : "" ) .">".$r['location_name']."</option>";
				if ( $_GET['locid'] == $r['id'] ) $loc = $r['location_name'];
			}
			mysql_data_seek( $q, 0 );
			?>
		</select>
	</div>
</div>

<?
$location_id = $_GET['locid'];
if ( !isset( $_GET['locid'] ) || $_GET['locid'] == "" )
{
	$r = $db->fetch_array( $q );
	$location_id = $r['id'];
	$loc = $r['location_name'];
}

/**
 * If a business has been edited.
 */
if ( isset( $_POST['change'] ) )
{
	/* Update the location. */
	$db->query( "UPDATE localcity SET location_id=". $db->prepval( $_POST['location'] ) ." WHERE location_id=$location_id AND business_id=". $db->prepval( $_POST['bid'] ) );

	/* Update the business. */
	$db->query( "UPDATE businesses SET name=". $db->prepval( $_POST['name'] ) .", `desc`=". $db->prepval( $_POST['desc'] ) .", owner_id=". $db->prepval( $_POST['owner'] ) .", url=". $db->prepval( $_POST['url'] ).", for_sale=" . $db->prepval( $_POST['for_sale'] ) . ", sale_price=" . $db->prepval( $_POST['sale_price'] ) . " WHERE id=". $db->prepval( $_POST['bid'] ) );

	adminLog( $_SESSION['username'], "Updated " . $_POST['name'] . " business." );

	echo "<p><strong>Business information updated.</strong></p>";
}

if ( isset( $_POST['add'] ) )
{
	/* Insert a new bank account. */
	/* Generate a new account number and check if it exists or not. */
	$num = mt_rand( 1000000, 9999999 );
	while ( $db->getRowCount( $db->query( "SELECT * FROM bank_accounts WHERE account_number=$num" ) ) > 0 )
		$num = mt_rand( 1000000, 9999999 );

	/* Insert a new, approved, bank account. */
	$db->query( "INSERT INTO bank_accounts SET account_number=$num, account_type=0, account_status=2, balance=0" );

	/* Insert the new business. */
	$q = $db->query( "INSERT INTO businesses SET name=". $db->prepval( $_POST['name'] ) .", business_type=". $db->prepval( $_POST['btype'] ) .", `desc`=". $db->prepval( $_POST['desc'] ) .", owner_id=". $db->prepval( $_POST['owner'] ) .", bank_id=$num, url=". $db->prepval( $_POST['url'] ) . ", for_sale=" . $db->prepval( $_POST['for_sale'] ) . ", sale_price=" . $db->prepval( $_POST['sale_price'] ) );

	$bid = mysql_insert_id();

	/* Insert the localcity entry. */
	$db->query( "INSERT INTO localcity SET location_id=". $db->prepval( $_POST['location'] ) .", business_id=".$bid );
	
	/* Depending on business type set up more queries. */
	switch ( $_POST['btype'] )
	{
		case 0: break;
		case 1: break;
		case 2: $db->query( "INSERT INTO clothingshop SET business_id=".$bid.", reset_timer=0" ); break;
		case 3: $db->query( "INSERT INTO auctionhouse SET business_id=".$bid.", auction_fee=5" ); break;
		case 4: break;
		case 5: break;
		case 6: $db->query( "INSERT INTO bank_settings SET business_id=".$bid.", savings_rent=25, deposit_fee=3, withdrawal_fee=3, transfer_fee=3" ); break;
		case 7: $db->query( "INSERT INTO weaponshop SET business_id=".$bid.", reset_timer=0" ); break;
		case 8: break;
		case 9: $db->query( "INSERT INTO realestate_agency SET business_id=".$bid.", price_per_meter=5000" ); break;
	}	

	echo "<p><strong>The new business '".$_POST['name']."' has been added!</strong></p>";
	adminLog( $_SESSION['username'], "Created a new business titled, " . $_POST['name'] . "." );
}

if ( $_GET['act'] == "delete" && isset( $_GET['bid'] ) && $_GET['bid'] != "" )
{
	/* Check if the business exists. */
	$q = $db->query( "SELECT * FROM businesses WHERE id=". $db->prepval( $_GET['bid'] ) );
	if ( $db->getRowCount( $q ) == 0 )
	{
		echo "<p><strong>The business you tried to delete doesn't actually exist!</strong></p>";
	}
	else
	{
		$r = $db->fetch_array( $q );
		$db->query( "DELETE FROM businesses WHERE id=". $db->prepval( $_GET['bid'] ) );
		$db->query( "DELETE FROM bank_accounts WHERE account_number=". $r['bank_id'] );
		$db->query( "DELETE FROM localcity WHERE business_id=". $db->prepval( $_GET['bid'] ) );

		/* Depending on business type, execute more deletions. */
		switch ( $r['business_type'] )
		{
			case 0: break;
			case 1: break;
			case 2: $db->query( "DELETE FROM clothingshop WHERE business_id=". $db->prepval( $_GET['bid'] ) ); break;
			case 3: $db->query( "DELETE FROM auctionhouse WHERE business_id=". $db->prepval( $_GET['id'] ) ); break;
			case 4: break;
			case 5: break;
			case 6: $db->query( "DELETE FROM bank_settings WHERE business_id=". $db->prepval( $_GET['bid'] ) ); break;
			case 7: $db->query( "DELETE FROM weaponshop WHERE business_id=". $db->prepval( $_GET['bid'] ) ); break;
			case 8: break;
			case 9: $db->query( "DELETE FROM realestate_agency WHERE business_id=". $db->prepval( $_GET['bid'] ) ); break;
		}

		adminLog( $_SESSION['username'], "Deleted a business (id: " . $_GET['bid'] . "; type: " . $r['business_type'] . ")." );
	}
}

if ( $_GET['act'] == "edit" && isset( $_GET['bid'] ) && $_GET['bid'] != "" )
{
	/* Check if the business exists. */
	$q = $db->query( "SELECT * FROM businesses WHERE id=". $db->prepval( $_GET['bid'] ) );
	if ( $db->getRowCount( $q ) == 0 )
	{
		echo "<p><strong>The business you tried to edit doesn't actually exist!</strong></p>";
	}
	else
	{
		$r = $db->fetch_array( $q );

		?>
		<div style="width: 50%;">
			<div class="title" style="padding-left: 10px;">
				Edit <?=$r['name'];?>
			</div>
			<div class="content">
				<form action="businessmanagement.php?locid=<?=$location_id;?>" method="post">
				<table style="width: 100%;">
				<tr>
					<td style="width: 35%;">Edit Name:</td>
					<td>
						<input type="text" class="std_input" style="min-width: 150px;" name="name" value="<?=$r['name'];?>" />
					</td>
				</tr>
				<tr>
					<td style="width: 35%;">Edit Location:</td>
					<td>
						<select style="min-width: 150px;" name="location" class="std_input">
						<?
						$q2 = $db->query( "SELECT * FROM locations" );
						while ( $r2 = $db->fetch_array( $q2 ) )
						{
							echo "<option value='".$r2['id']."'";
							if ( $r2['id'] == $location_id ) echo " selected='true'";
							echo ">".$r2['location_name']."</option>";
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td style="width: 35%;">Edit Ownership:</td>
					<td>
						<select style="min-width: 200px;" name="owner" class="std_input">
						<?
						$q2 = $db->query( "SELECT * FROM char_characters ORDER BY id" );
						while ( $r2 = $db->fetch_array( $q2 ) )
						{
							echo "<option value='".$r2['id']."'";
							if ( $r2['id'] == $r['owner_id'] ) echo " selected='true'";
							echo ">".$r2['nickname']."</option>".Chr(13);
						}
						?>
						</select>
					</td>
				</tr>	
				<tr>
					<td style="width: 35%;">Edit File Url:</td>
					<td>
						<input type="text" class="std_input" style="min-width: 200px;" name="url" value="<?=$r['url'];?>" />
					</td>
				</tr>	
				<tr>
					<td style="width: 35%;">Edit Description:</td>
					<td>
						<textarea class="std_input" name="desc" style="width: 200px; height: 100px;"><?=$r['desc'];?></textarea>
					</td>
				</tr>
				<tr>
					<td style="width: 35%;">For Sale:</td>
					<td>
						<select class="std_input" name="for_sale" style="width: 200px;">
							<?
							if ( $r['for_sale'] == 'true' )
							{
								?>
								<option value='true'>Yes</option>
								<option value='false'>No</option>
								<?
							}
							else
							{
								?>
								<option value='false'>No</option>
								<option value='true'>Yes</option>
								<?
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
				<td style="width: 35%;">Sale Price:</td>
					<td>
						<input type='text' name='sale_price' class='std_input' value='<?=$r['sale_price'];?>' style='width: 100px;' />$
					</td>
				</tr>
				<tr><td colspan="2" style="height: 20px;"></td></tr>
				<tr><td colspan="2" style="width: 100%;"><input type="submit" class="std_input" value="Submit Changes" name="change" /></td></tr>
				</table>
				<input type="hidden" name="bid" value="<?=$r['id'];?>" />
				</form>
			</div>
		</div>
		<?
	}
}

if ( $_GET['act'] == "add" )
{
	?>
	<div style="width: 50%;">
		<div class="title" style="padding-left: 10px;">
			Add a new business
		</div>
		<div class="content">
			<form action="businessmanagement.php?locid=<?=$location_id;?>" method="post">
			<table style="width: 100%;">
			<tr>
					<td style="width: 35%;">Business Type:</td>
					<td>
						<select class="std_input" name="btype">
						<? for ( $i = 0; $i < 12; $i++ )
							echo "<option value='".$i."'>".getBusinessTypeString( $i )."</option>";
						?>
						</select>
					</td>
				</tr>
			<tr>
				<td style="width: 35%;">Business Name:</td>
				<td>
					<input type="text" class="std_input" style="min-width: 150px;" name="name" />
				</td>
			</tr>
			<tr>
				<td style="width: 35%;">Add Location:</td>
				<td>
					<select style="min-width: 150px;" name="location" class="std_input">
					<?
					$q2 = $db->query( "SELECT * FROM locations" );
					while ( $r2 = $db->fetch_array( $q2 ) )
					{
						echo "<option value='".$r2['id']."'";
						if ( $r2['id'] == $location_id ) echo " selected='true'";
						echo ">".$r2['location_name']."</option>";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width: 35%;">Add Ownership:</td>
				<td>
					<select style="min-width: 200px;" name="owner" class="std_input">
					<?
					$q2 = $db->query( "SELECT * FROM char_characters ORDER BY id" );
					while ( $r2 = $db->fetch_array( $q2 ) )
					{
						echo "<option value='".$r2['id']."'";						
						echo ">".$r2['nickname']."</option>".Chr(13);
					}
					?>
					</select>
				</td>
			</tr>	
			<tr>
				<td style="width: 35%;">Add File Url:</td>
				<td>
					<input type="text" class="std_input" style="min-width: 200px;" name="url" />
				</td>
			</tr>	
			<tr>
				<td style="width: 35%;">Add Description:</td>
				<td>
					<textarea class="std_input" name="desc" style="width: 200px; height: 100px;"></textarea>
				</td>
			</tr>
			<tr>
				<td style="width: 35%;">For Sale:</td>
				<td>
					<select class="std_input" name="for_sale" style="width: 200px;">
						<option value='false'>No</option>
						<option value='true'>Yes</option>
					</select>
				</td>
			</tr>
			<tr>
			<td style="width: 35%;">Sale Price:</td>
				<td>
					<input type='text' name='sale_price' class='std_input' style='width: 100px;' />$
				</td>
			</tr>
			<tr><td colspan="2" style="height: 20px;"></td></tr>
			<tr><td colspan="2" style="width: 100%;"><input type="submit" class="std_input" value="Add Business" name="add" /></td></tr>
			</table>			
			</form>
		</div>
	</div>
	<?	
}

?>
<!-- List of businesses -->
<div class="title" style="padding-left: 10px;">
	List of <?=$loc;?> businesses
</div>
<div class="content" style="padding: 0px; margin-bottom: 30px;">
	<div class="row" style="background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
		<table class="row">
			<tr>		
				<td class="field" style="width: 50px;"><strong>Icon</strong></td>
				<td class="field" style="width: 35%;"><strong>Business Name</strong></td>
				<td class="field" style="width: 25%;"><strong>Current Owner</strong></td>
				<td class="field" style="width: 20%;"><strong>File Url</strong></td>
				<td class="field"><strong>Options</strong></td>
			</tr>
		</table>
	</div>
	<?
	$q = $db->query( "SELECT * FROM localcity INNER JOIN businesses ON localcity.business_id = businesses.id INNER JOIN char_characters ON businesses.owner_id = char_characters.id WHERE location_id=$location_id" );
	while ( $r = $db->fetch_array( $q ) )
	{
	?>
	<div class="row">
		<table class="row">
			<tr>
				<td class="field" style="width: 50px;">
				<? if ( $r['icon'] != "" ) { ?><img src="<?=$r['icon'];?>" alt="" style="height: 32px; width: 32px;" /><? }
				else { ?><img src="../images/nav/nav_localcity.png" alt="" style="height: 32px; width: 32px;" /><? } ?>
				</td>
				<td class="field" style="width: 35%;"><?=$r['name'];?></td>
				<td class="field" style="width: 25%;"><?=$r['nickname'];?></td>
				<td class="field" style="width: 20%;"><?=$r['url'];?></td>
				<td class="field"><a href="<?=$PHP_SELF;?>?locid=<?=$location_id;?>&amp;act=edit&amp;bid=<?=$r['business_id'];?>">edit</a> <a href="<?=$PHP_SELF;?>?locid=<?=$location_id;?>&amp;act=delete&amp;bid=<?=$r['business_id'];?>">delete</a></td>
			</tr>
		</table>
	</div>
	<?
	}
	?>
</div>

<div style="margin-bottom: 30px; margin-top: 20px;">
<input type="button" class="std_input" value="Add A Business" style="background-color: #bb6; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); margin-right: 3px;" onclick="window.location = 'businessmanagement.php?locid=<?=$location_id;?>&amp;act=add';" />
</div>

<?
include_once "../includes/footer.php";
?>