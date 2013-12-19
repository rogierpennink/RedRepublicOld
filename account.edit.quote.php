<?
/* Edit Quote Page */
$CHARNEEDED = true;
$CUSTOMCHAR = true;
unset( $_SESSION['ingame'] );

include_once "includes/utility.inc.php";

// To Fetch Old Quote
$query = $db->query( "SELECT * FROM accounts INNER JOIN char_characters ON char_characters.account_id = accounts.id WHERE accounts.id=". $db->prepval( getUserID() ) );

$char = new Player( getUserID() );	

// Getting New Quote and Character ID
$newquote = $_POST["quote"];
$char_id = $char->getCharacterID();
$sql = "SELECT quote FROM char_characters WHERE id =".$char_id." LIMIT 1;";

//Getting Current Quote
$result = $db->fetch_array( $db->query( $sql ) );
$currentquote = $result['quote'];

if ( isset( $_POST["Submit"] ) )
{ 
	if ( strlen( $newquote ) > 300 ) 
		$error_message = "Quote over 300 characters. Please Try Again.";
	else 
	{ 
		$ar1 = array( "<", ">", chr(13) ); // No html, no breaks.
		$ar2 = array( "&lt;", "&gt;", " " ); // Replace the latter with "<br />" to enable quote breaking obviously.
		$db->query( "UPDATE char_characters SET quote ='".mysql_real_escape_string( str_replace( $ar1, $ar2, $newquote ) )."' WHERE id ='".$char_id."' LIMIT 1;" );
		header( "Location: account.php" );
		die;
	}
}

include_once "includes/header.php";
?>
<table style="width: 100%">
	<tr>
		<td style="width: 95%; vertical-align: top;">
			<div class="title">
				<div style="margin-left: 10px;">Edit Quote</div>
			</div>
			<div class="content">
				<?
				echo '<p id="output" style="font-weight: bold;">';
				echo $error_message;
				echo '</p>'; 
				?>
				<font color='#D3474A'>
					<form action="account.edit.quote.php" method="post">
						<textarea name="quote" rows="8" cols="65" maxlength="300" wrap="on" ><?php echo stripslashes(str_replace($ar1, $ar2, $currentquote)); ?></textarea>
						<br /><br />
						<input type="submit"  class="std_input" name="Submit" value="Edit Quote">
					</form>
				</font>
			</div>
		</td>
	</tr>
</table>
<?
include_once "includes/footer.php";
?>