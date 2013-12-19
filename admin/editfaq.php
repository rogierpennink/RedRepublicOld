<?
/**
 * supportmanagement.php - Add, edit, remove guides and questions from the FAQ book and player guide.
 * 
 * Author:	Aaron Amann
 * Date:	08/29/2007
 */
$nav = "faqman";
$ext_style = "forums_style.css";
$AUTH_LEVEL = 5;
$REDIRECT = true;
include_once "../includes/utility.inc.php";

if ( getUserRights() == USER_SUPPORT ) $_SESSION['ingame'] = true;

if ( isset( $_POST['submit'] ) )
{
	$textA = array( "<", ">" );
	$textB = array( "&lt;", "&gt;" );
	if ( $_POST['hidden'] == 'new' )
	{
		$db->query( "INSERT INTO faqbook (id, question, answer, credit) VALUES ('', " . $db->prepval( $_POST['question'] ) . ", " . $db->prepval( str_replace( $textA, $textB, $_POST['answer'] ) ) . ", " . $db->prepval( $_POST['credit'] ) . ")" );

		adminLog( $_SESSION['username'], "Added a new FAQ Book entry." );

		header( "location: " . $rootdir . "/admin/supportmanagement.php" );
		exit();
	} else {
		$db->query( "UPDATE faqbook SET question=" . $db->prepval( $_POST['question'] ) . ", answer=" . $db->prepval( str_replace( $textA, $textB, $_POST['answer'] ) ) . ", credit=" . $db->prepval( $_POST['credit'] ) . " WHERE id=" . $_POST['hidden'] );
		header( "location: " . $rootdir . "/admin/supportmanagement.php" );
		exit();
	}
}

if ( isset ( $_GET['ajaxrequest'] ) && $_GET['ajaxrequest'] != "" )
{
	if ( !user_auth() )
	{
		echo "error::notloggedin";
	}
	elseif ( getUserRights() < USER_SUPERADMIN )
	{
		echo "error::accessdenied";
	}
	else
	{
		echo "error::An unknown request was received, no response output.";
	}
	
	exit;
}

if ( !isset( $_GET['id'] ) )
{
	$_SESSION['error'] = "ID was not set. Redirected.";
	header( "Location: " . $rootdir . "/admin/supportmanagement.php" );
	exit;
}

$ext_style = "forums_style.css";
include_once "../includes/header.php";

?>

	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>FAQ Book Editor</h1>
	<p>Here is where you can add or edit questions and answers. This is a simple editor, you should note that HTML is not enabled here.
	<? if ( isset( $ErrStr ) ) print( "<br /><br /><font face='verdana' size='2' color='red'><b>" . $ErrStr . "</b></font>" ); ?>
	</p>

	<!-- Category management first -->
	<div class="title">
		<div style="margin-left: 10px;">FAQ Book Entry</div>
	</div>
	<div class="content" style="padding: 0px;">
		<table style="width: 90%"><tr><td>
			<?
			if ( $_GET['id'] == 'new' )
			{
				$postType = 'new';
			} 
			else 
			{
				$postType = $_GET['id'];
				$varAssoc = $db->getRowsFor( "SELECT * FROM faqbook WHERE id=" . $db->prepval( $_GET['id'] ) );
			}
				?>
				<form method="post" action="<?=$PHP_SELF;?>">
				<input type='hidden' name='hidden' value='<?=$postType;?>' />
				<table style="width: 100%">
					<tr>
						<td style="width: 22%">ID: </td>
						<td><input size='4' type='text' class='std_input' name='id' value='<?=$_GET['id'];?>' readonly='true' />
					</tr>
					<tr>
						<td style="width: 22%">Question: </td>
						<td><input size='40' type='text' class='std_input' name='question' value='<?
						if ( $postType != 'new' )
						{
							print( $varAssoc['question'] );
						}
						?>' /></td>
					</tr>
					<tr>
						<td style="width: 22%">Answer: </td>
						<td>
							<textarea class='std_input' name='answer' cols='40' rows='10'><?
							if ( $postType != 'new' )
							{
								print( $varAssoc['answer'] );
							}
							?></textarea>
						</td>
					</tr>
					<tr>
						<td style="width: 22%">Author: </td>
						<td><input size='40' type='text' class='std_input' name='credit' value='<?
						if ( $postType != 'new' )
						{
							print( $varAssoc['credit'] );
						}
						?>' /></td>
					</tr>
					<tr>
						<td style="width: 22%;"><input type='submit' class='std_input' name='submit' value='    Done    '></td>
						<td><input type='reset' class='std_input' name='reset' value='    Reset    '></td>
					</tr>
				</table>
				</form>
		</td></tr></table>
	</div>

<?
include_once "../includes/footer.php";
?>