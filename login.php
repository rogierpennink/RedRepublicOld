<?
/*
	login.php, a two-sided file because it accepts both requests for a quick ajax login and
	a more traditional (but with more advanced options) login request ( simply a html page + form ).
*/

// This is the AJAX part of the login page
if ( $_GET['requesttype'] == "quicklogin" || $_GET['requesttype'] == "normallogin" )
{	
	include "includes/utility.inc.php";

	if ( !user_auth( $_GET['user'], $_GET['pass'] ) )
	{
		echo "error::Could not find a matching username and password.";
	}
	else
	{
		if ( !isset( $_SESSION['username'] ) || !isset( $_SESSION['password'] ) )
		{
			unset( $_SESSION['username'] ); unset( $_SESSION['password'] );
			echo "error::Login successful, but an error occurred on saving your session.";
		}
		else
		{
			setcookie( "redrepublic[username]", $_GET["user"], time()+60*60*24*100 );
			setcookie( "redrepublic[password]", $_GET["pass"], time()+60*60*24*100 );
			$_SESSION['notify'] = "Success! You have been logged into Red Republic!";
			echo ( $_GET['requesttype'] == "quicklogin" ) ? "quicklogin::success" : "normallogin::success";
		}
	}
	exit;
}

include_once "includes/utility.inc.php";

/* If clean cookies option is selected */
if ( isset( $_GET['cleancookies'] ) ) cleanCookies();

unset( $_SESSION['ingame'] );

/*
	REAL CONTENT IS STARTING HERE...
*/
include_once "includes/header.php";

?>

	<div class="title">
		<div style="margin-left: 10px;">Log in to Red Republic</div>
	</div>
	<div class="content">

		<p>You can use this form to login to Red Republic. If you do not yet have an account you can <a href="register.php">register here</a>. If you have forgotten your password, attempt to <a href="lostpassword.php">retrieve it here</a>. If you're experiencing problems with the logging in process, remember to turn on javascript support. <? if ( isset( $_COOKIE['redrepublic']['username'] ) && isset( $_COOKIE['redrepublic']['password'] ) ) { ?>It has been detected that you have logged in before, click here if you'd like to <a href='<?=$rootdir;?>/login.php?cleancookies=true'>clear your cookies</a> set by Red Republic. <? } ?></p>

		<form action="account.php" method="post" style="text-align: center;" onsubmit="javascript: login( document.getElementById('username').value, document.getElementById('password').value ); return false;">
			<input type="text" id="username" <? if ( !isset( $_COOKIE['redrepublic']['username'] ) ) { ?>value="Username" onclick="this.value = ''; this.onclick = null;" <? } else { ?>value="<?=$_COOKIE['redrepublic']['username'];?>" <? } ?>class="std_input" style="width: auto;" /><br />
			<input type="password" id="password" <? if ( !isset( $_COOKIE['redrepublic']['password'] ) ) { ?>value="Password" onclick="this.value = ''; this.onclick = null;" <? } else { ?>value="<?=$_COOKIE['redrepublic']['password'];?>" <? } ?> class="std_input" style="width: auto;" /><br /><br />
			<input type="submit" id="submit" value="Log In" class="std_input" />
		</form>
	</div>

<?
include "includes/footer.php";	
?>