<?
include_once "includes/utility.inc.php";

// Check if regs are open or not
$rec = $db->query( "SELECT * FROM settings_general WHERE setting='reg_status'" );
$res = $db->fetch_array( $rec );
$regstatus = $res['value'];

if ( isset( $_POST['ajaxrequest'] ) )
{
	if ( $_POST['ajaxrequest'] == "register" )
	{
		if ( $regstatus != "open" )
		{
			echo "error::Registrations are currently closed. You cannot register an account.";
			exit;
		}
		if ( user_auth() )
		{
			echo "error::You already have an account and are logged in. You can't create another account!";
			exit;
		}
		if ( !ctype_alnum( $_POST['name'] ) || strlen( $_POST['name'] ) < 3 || strlen( $_POST['name'] ) > 25 )
		{
			echo "error::Your accountname should consist of only numbers and letters and be between 3 and 25 characters!";
			exit;
		}
		if ( !ctype_graph( $_POST['password'] ) || strlen( $_POST['password'] ) < 6 || strlen( $_POST['password'] ) > 20 )
		{
			echo "error::Your password must contain printable characters with a length between 6 and 20!";
			exit;
		}
		if ( !ctype_graph( $_POST['password1'] ) || strlen( $_POST['password1'] ) < 6 || strlen( $_POST['password1'] ) > 20 )
		{
			echo "error::Your confirmation password must contain printable characters with a length between 6 and 20!";
			exit;
		}
		if ( $_POST['password'] != $_POST['password1'] )
		{
			echo "error::Your passwords didn't match!";
			exit;
		}
		if ( !ctype_alpha( $_POST['firstname'] ) || strlen( $_POST['firstname'] ) < 3 || strlen( $_POST['firstname'] ) > 25 )
		{
			echo "error::Your first name should consist of only letters and be between 3 and 25 characters!";
			exit;
		}
		if ( !ctype_alpha( $_POST['lastname'] ) || strlen( $_POST['lastname'] ) < 3 || strlen( $_POST['lastname'] ) > 25 )
		{
			echo "error::Your last name should consist of only letters and be between 3 and 25 characters!";
			exit;
		}
		if ( !check_email_address( $_POST['email'] ) )
		{
			echo "error::You entered an invalid e-mail address!";
			exit;
		}
		if ( strlen( $_POST['country'] ) != 3 )
		{
			echo "error::You passed the wrong country argument!";
			exit;
		}
		if ( !ctype_alnum( $_POST['city'] ) || strlen( $_POST['city'] ) < 3 || strlen( $_POST['city'] ) > 40 )
		{
			echo "error::Your city should consist of only letters and be between 3 and 40 characters!";
			exit;
		}

		// Check if a user with given accountname exists
		if ( $db->getRowCount( $db->query( "SELECT * FROM accounts WHERE username=" . $db->prepval( $_POST['name'] ) ) ) != 0 )
		{
			echo "error::The accountname you specified has already been taken!";
			exit;
		}
		
		// Apparently everything was OK. Thus, register!
		if ( $db->query( "INSERT INTO persons SET firstname=" .$db->prepval( $_POST['firstname'] ). ", lastname=" .$db->prepval( $_POST['lastname'] ). ", country_id=" .$db->prepval( $_POST['country'] ). ", city=" .$db->prepval( $_POST['city'] ).", email=" .$db->prepval( $_POST['email'] ) ) === false )
		{
			echo "error::A database error occurred on adding your account to the database!";
			exit;
		}
		else
		{
			$person_id = mysql_insert_id();
			if ( $db->query( "INSERT INTO accounts SET username=" .$db->prepval( $_POST['name'] ). ", password=" .$db->prepval( md5( $_POST['password'] ) ). ", person_id=" .$db->prepval( $person_id ). ", rights=0" ) === false )
			{
				// Delete the person entry
				$db->query( "DELETE FROM persons WHERE id=". $db->prepval( $person_id ) );

				echo "error::A database error occurred on adding your account to the database!";
				exit;
			}
			else
			{
				$acc_id = mysql_insert_id();
				if ( $db->query( "INSERT INTO forums_users SET account_id=". $db->prepval( $acc_id ) .", num_posts=0" ) === false )
				{
					// Roll back actions
					$db->query( "DELETE FROM persons WHERE id=". $db->prepval( $person_id ) );
					$db->query( "DELETE FROM accounts WHERE id=". $db->prepval( $acc_id ) );

					echo "error::A database error occurred on adding your account to the database!";
					exit;
				}

				echo "contentID::register::<b>You successfully registered an account on Red Republic</b>. You can <a href=\"login.php\">login here</a>.";
			}
		}
		
	}
	elseif ( $_POST['ajaxrequest'] == "activate" )
	{
	}

	exit;
}

unset( $_SESSION['ingame'] );

include_once "includes/header.php";

?>
<script type="text/javascript" src="<?=$rootdir;?>/javascript/register_js.js"></script>

<h1>Register a new account</h1>
<p>If you do not yet have a Crime Syndicate account, you can register one here. A Crime Syndicate account gives you access to a userpanel from which you can create a character, and play with it. A Crime Syndicate account also gives access to the forums. Please note, your accountname will be displayed when posting on the forums, not your character name! Furthermore, please take into account that by registering on this site you agree to our <a href="docs.php?type=tos">Terms of Service</a> and our <a href="docs.php?type=grules">Game Rules</a>.</p>

<? if ( $regstatus != "open" ) { ?><p><strong>Registrations are currently closed! You cannot register a new account!</strong></p><? } ?>

<div class="title">
	<div style="margin-left: 10px;">Register a new account</div>
</div>
<div class="content">
	<form action="register.php" method="post" onsubmit="register( this ); return false;">
	<table style="width: 100%;">
		<tr>
			<td colspan="2" style="width: 100%;"><div class="notifydisplay" style="display: block; visibility: visible;">Please enter your desired account details below:</div></td>
		</tr>

		<tr>
			<td style="width: 15%; text-align: right;"><strong>Username:</strong></td>
			<td style="width: 85%;"><input type="text" name="username" class="std_input" style="width: 250px; margin-left: 15px;" /></td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right;"><strong>Password:</strong></td>
			<td style="width: 85%;"><input type="password" name="password" class="std_input" style="width: 250px; margin-left: 15px;" /></td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right;"><strong>Confirm Password:</strong></td>
			<td style="width: 85%;"><input type="password" name="password1" class="std_input" style="width: 250px; margin-left: 15px;" /></td>
		</tr>

		<tr>
			<td colspan="2" style="margin: 5px;">&nbsp;</td>
		</tr>

		<tr>
			<td colspan="2" style="width: 100%;"><div class="notifydisplay" style="display: block; visibility: visible;">Please enter your personal details below:</div></td>
		</tr>

		<tr>
			<td style="width: 15%; text-align: right;"><strong>First Name:</strong></td>
			<td style="width: 85%;"><input type="text" name="firstname" class="std_input" style="width: 250px; margin-left: 15px;" /></td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right;"><strong>Last Name:</strong></td>
			<td style="width: 85%;"><input type="text" name="lastname" class="std_input" style="width: 250px; margin-left: 15px;" /></td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right;"><strong>E-mail Address:</strong></td>
			<td style="width: 85%;"><input type="text" name="email" class="std_input" style="width: 250px; margin-left: 15px;" /></td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right;"><strong>Country:</strong></td>
			<td style="width: 85%;">
			<select name="country" class="std_input" style="width: 250px; margin-left: 15px;">
			<?
			$rec = $db->query( "SELECT * FROM country ORDER BY name ASC" );
			while ( $res = $db->fetch_array( $rec ) )
			{
				echo "<option value=\"". $res['code'] ."\">". $res['name'] ."</option>\n";
			}
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td style="width: 15%; text-align: right;"><strong>City:</strong></td>
			<td style="width: 85%;"><input type="text" name="city" class="std_input" style="width: 250px; margin-left: 15px;" /></td>
		</tr>

		<tr>
			<td colspan="2" style="margin: 5px;">&nbsp;</td>
		</tr>

		<tr>
			<td colspan="2" style="width: 100%;"><div class="notifydisplay" style="display: block; visibility: visible;">If you are sure everything was entered correctly, use the button below to complete your registration:</div></td>
		</tr>

		<tr>
			<td style="width: 15%; text-align: right;">&nbsp;</td>
			<td style="width: 85%;"><input type="submit" class="std_input" style="margin-left: 15px;" value="Register!" /></td>
		</tr>


	</table>
	</form>
</div>
<?

include_once "includes/footer.php";
?>