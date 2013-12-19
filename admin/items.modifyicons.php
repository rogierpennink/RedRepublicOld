<?
if ( $_GET['act'] == "delicon" && isset( $_GET['icon_id'] ) && $_GET['icon_id'] != "" )
{
	/* We're simply going to delete the item. */
	if ( $db->query( "DELETE FROM icons WHERE icon_id=" . $db->prepval( $_GET['icon_id'] ) ) === false )
		echo "<p><strong>Oh my god! There's been a problem, deleting your icon!</strong></p>";
	else
		echo "<p><strong>Congratulations, you successfully deleted that icon!</strong></p>";
}

if ( isset( $_POST['addicon'] ) )
{
	$dontshow = true;

	if ( $_FILES['image']['error'] == UPLOAD_ERR_NO_FILE && $_POST['url'] == "" )
	{
		echo "<p><strong>You must either upload a file or specify a file location for the icon!</strong></p>";
		$_GET['act'] = "addicon";
	}
	elseif ( $_FILES['image']['error'] == UPLOAD_ERR_OK ) // File upload has priority
	{
		$path = "/images/items/";
		$name = ( $_POST['filename'] != "" ) ? $_POST['filename'] : "icon" . mt_rand( 10000, 99999 );
		$path .= $name;

		if ( !move_uploaded_file( $_FILES['image']['tmp_name'], $_SERVER["DOCUMENT_ROOT"] . $path ) ) 
		{
			echo "<p><strong>An error occurred while trying to upload the icon. Please try again.</strong></p>";
			$_GET['act'] = "addicon";
		}
		else
		{
			/* Insert into database. */
			if ( $db->query( "INSERT INTO icons SET url='items/$name'" ) === false )
			{
				echo "<p><strong>An error occurred on adding your icon to the database!</strong></p>";
				$_GET['act'] = "addicon";
			}
			else
			{
				echo "<p><strong>You successfully uploaded and added the new icon!</strong></p>";
				$dontshow = false;
			}
		}		
	}
	else
	{
		if ( !file_exists( "../images/items/". $_POST['url'] ) )
		{
			echo "<p><strong>The image url you provided points to a non-existent file!</strong></p>";
			$_GET['act'] = "addicon";
		}
		else
		{
			/* Insert into database. */
			if ( $db->query( "INSERT INTO icons SET url='items/". $_POST['url'] ."'" ) === false )
			{
				echo "<p><strong>An error occurred on adding your icon to the database!</strong></p>";
				$_GET['act'] = "addicon";
			}
			else
			{
				echo "<p><strong>You successfully added the new icon!</strong></p>";
				$dontshow = false;
			}
		}
	}
}

if ( $_GET['act'] == "addicon" )
{
	$dontshow = true;
?>
	<div style="width: 60%; margin-left: auto; margin-right: auto;">
		<div class="title" style="padding-left: 10px;">
			Add a new icon
		</div>
		<div class="content">
			<form enctype="multipart/form-data" action="itemmanagement.php" method="POST">
			<table style="width: 100%;">
			<tr>
				<td style="width: 35%;">Upload Icon: *</td>
				<td>
					<input type="file" name="image" class="std_input" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Desired Filename: *</td>
				<td>
					<input type="text" name="filename" class="std_input" />
				</td>
			</tr>

			<tr>
				<td style="width: 35%;">Existing URL: **</td>
				<td>
					<input type="text" name="url" class="std_input" />
				</td>
			</tr>

			<tr><td style="height: 10px;">&nbsp;</td></tr>

			<tr>
				<td colspan="2" style="width: 100%;">
					* Use this if the icon you wish to add is not yet available in the images/items/ folder.<br />
					** Use this if the icon you wish to add is already present in the images/items folder.
				</td>
			</tr>

			<tr><td style="height: 10px;">&nbsp;</td></tr>

			<tr>
				<td style="width: 35%;">&nbsp;</td>
				<td><input type="submit" value="Add Icon" name="addicon" class="std_input" /></td>
			</tr>

			</table>
			</form>
		</div>
	</div>
<?
}
?>