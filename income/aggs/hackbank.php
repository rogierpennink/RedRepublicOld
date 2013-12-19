<?
$show = ( isset( $_POST['hackbank'] ) ) ? $continue : false;
if ( isset( $_GET['ajaxrequest'] ) )
{
	include_once "../../includes/utility.inc.php";
	$char = new Player( getUserID() );

	/**
	 * Use a multi-dimensional array for the files.
	 */	 
	$farray = array();
	$pos = 0;
	$fs = file_get_contents( "../../config/mainframe_filesystem.conf" );	
	$curdir = "/";	
	$farray[$curdir] = array();

	/**
	 * Get the actual username and password.
	 */
	$pos = strpos( $fs, Chr( 13 ) );
	$line = trim( substr( $fs, 0, $pos ) );
	$line = explode( "::", $line );
	$_SESSION['hb_usr'] = $line[0];
	$_SESSION['hb_pwd'] = $line[1];
	
	while ( ( $pos = strpos( $fs, Chr(13) ) ) !== false )
	{
		$line = trim( substr( $fs, 0, $pos ) );				
		$line = explode( "::", $line );
		
		if ( trim( $line[1] ) == "dir" )
		{			
			$curdir = trim( $line[0] );
			$farray[$curdir] = array();			
		}
		elseif ( trim( $line[1] ) == "file" )
		{
			$farray[$curdir][trim($line[0])] = array();
			$farray[$curdir][trim($line[0])][0] = trim( $line[0] );
			$farray[$curdir][trim($line[0])][1] = trim( $line[2] ); 
		}		
		$fs = substr( $fs, $pos + 1 );
	}
	
	if ( !isset( $_SESSION['u_session'] ) )
	{
		$_SESSION['u_session'] = true;		
		$_SESSION['curr_dir'] = "/";
		$_SESSION['connected'] = false;
		$_SESSION['done'] = false;
	}

	if ( $_SESSION['done'] == true || $char->agg_timer > time() )
	{
		echo "You are no longer able to use this terminal. Try again later.";
		exit;
	}

	if ( $_GET['command'] == "help" )
	{		
		echo "The Bank of ". $char->location ."'s help utility.\n";
		echo "List of commands:\n";
		echo sprintf( "%-45s%s\n", "connect -u[Username] -p[Password]", "-- Connects to the transaction server." );
		echo sprintf( "%-45s%s\n", "ls", "-- Lists files in current directory." );
		echo sprintf( "%-45s%s\n", "cd [Directory]", "-- Change current directory to specified directory." );
		echo sprintf( "%-45s%s\n", "readfile [filename]", "-- Reads the specified file." );
		if ( $_SESSION['connected'] == true )
		{
			/* List additional, transaction server commands. */
			echo sprintf( "%-45s%s\n", "finduser [nickname]", "-- Finds information about a customer." );
			echo sprintf( "%-45s%s\n", "transfer [amount] [from] [to]", "-- Transfers money from 'from' to 'to'" );
		}
	}
	elseif ( trim( substr( trim( $_GET['command'] ), 0, 8 ) ) == "transfer" )
	{
		$paramlist = trim( substr( $_GET['command'], 8 ) );
		$parameters = preg_split( "/\s+/", $paramlist );

		if ( !is_numeric( $parameters[0] ) || $parameters[0] <= 0 )
		{
			echo "The first parameter must be numeric and greater than 0.";
		}
		elseif ( !is_numeric( $parameters[1] ) || strlen( $parameters[1] ) != 7 )
		{
			echo "The second parameter must be a 7 digit bank account number.";
		}
		elseif ( !is_numeric( $parameters[2] ) || strlen( $parameters[2] ) != 7 )
		{
			echo "The third parameter must be a 7 digit bank account number.";
		}
		else
		{
			$vquery = $db->query( "SELECT account_id, account_number, balance FROM bank_accounts INNER JOIN char_characters ON bank_accounts.owner_id=char_characters.id INNER JOIN localcity ON bank_accounts.business_id=localcity.business_id WHERE localcity.location_id=". $char->location_nr ." AND bank_accounts.business_id=localcity.business_id AND char_characters.id <> ". $char->character_id ." AND bank_accounts.account_type=0 AND bank_accounts.account_number=". $db->prepval( $parameters[1] ) );
			$destination = $db->query( "SELECT * FROM bank_accounts WHERE account_number=". $db->prepval( $parameters[2] ) ." AND account_type=0 AND owner_id<>0" );

			if ( $parameters[1] == $parameters[2] )
			{
				echo "Source and destination account numbers must be different.";
			}
			elseif ( $db->getRowCount( $vquery ) == 0 )
			{
				echo "Invalid source account number.";
			}
			elseif ( $db->getRowCount( $destination ) == 0 )
			{
				echo "Invalid destination account number.";
			}
			else
			{
				$destination = $db->fetch_array( $destination );

				$v = $db->fetch_array( $vquery );
				$victim = new Player( $v['account_id'] );

				if ( $victim->agg_pro > time() )
				{
					echo "The account you're transferring from is currently protected.";
					exit;
				}

				/* Standard agg calculations. */
				$defStr = 1.0; $defDef = 1.6; $defInt = 1.3; $defCun = 1.1;
				$attStr = 1.0; $attDef = 1.0; $attInt = 1.6; $attCun = 1.4;

				$attPoints = $char->getTotalStr() * $attStr + $char->getTotalDef() * $attDef + $char->getTotalInt() * $attInt + $char->getTotalCun() * $attCun;
				$defPoints = $victim->getTotalStr() * $defStr + $victim->getTotalDef() * $defDef + $victim->getTotalInt() * $defInt + $victim->getTotalCun() * $defCun;	

				$quotient = $attPoints / $defPoints;
				$check = 0;
				if ( $quotient <= 0.8 ) { $check = 800 * $quotient - 200; }
				elseif ( $quotient >= 1 ) { $check = 300 * $quotient + 200; }
				else { $check = 1000 * pow( $quotient - 1, 3 ) + 250 * $quotient + 250; }
				$check = ( $check < 50 ) ? 50 : $check;
				$check = ( $check > 950 ) ? 950 : $check;
				$rand = mt_rand( 1, 1000 );

				$char->setAggTimer();
				$victim->setAggPro();
				$_SESSION['done'] = true;
				
				/* We want 10% stolen, on average. */
				if ( $quotient * ( $v['balance'] * 0.10 ) < $parameters[0] || $rand > $check )
				{
					$victim->setDefense( $victim->getDefense() * 1.005 );
					
					/* ADD EVENT TO VICTIM! */
					$msg = $char->nickname . " has attempted to transfer funds from your bank account after managing to hack into the ".$char->location." Bank's mainframe! Thanks to the system administrator's alertness and their nifty security programs, you didn't loose any money!";
					$victim->addEvent( "Attempted Bank Hack", $msg );

					echo "Busted! You better get the hell out of the system or risk being caught!";					
				}
				else
				{
					/* Update bank accounts. */
					$db->query( "UPDATE bank_accounts SET balance=balance-". $db->prepval( $parameters[0] ) ." WHERE account_number=". $v['account_number'] );
					$db->query( "UPDATE bank_accounts SET balance=balance+". $db->prepval( $parameters[0] ) ." WHERE account_number=". $db->prepval( $parameters[2] ) );

					/* Insert transaction logs. */
					$db->query( "INSERT INTO bank_transactions SET account_number=". $v['account_number'] .", transaction_type=5, amount=". ( -$parameters[0] ) .", fee=0, balance=". ( $v['balance'] - $parameters[0] ) .", to_player=". $destination['account_number'] .", datetime=". $db->prepval( date( "Y-m-d H:i:s" ) ) );
					$db->query( "INSERT INTO bank_transactions SET account_number=". $destination['account_number'] .", transaction_type=5, amount=". $db->prepval( $parameters[0] ) .", fee=0, balance=". ( $destination['balance'] + $parameters[0] ) .", from_player=". $v['account_number'] .", datetime=". $db->prepval( date( "Y-m-d H:i:s" ) ) );

					/* Add Event to Victim. */
					$msg = "Someone managed to hack into the transaction server of the ". $char->location ." Bank and managed to transfer funds from bank account number ". $v['account_number'] .", which is one of your accounts, to another bank account! System administrators have not been able to restore your original balance. You lost an amount of $". $parameters[0] .".";
					$victim->addEvent( "Bank Account Hacked", $msg );

					/* Update character stats. */
					$char->setStrength( $char->getStrength() + 20 );
					$char->setIntellect( $char->getIntellect() + 100 );
					$char->setCunning( $char->getCunning() + 75 );

					echo "Transfer successful. Logging you out.";
				}				
			}
		}
	}
	elseif ( trim( substr( trim( $_GET['command'] ), 0, 8 ) ) == "finduser" )
	{
		if ( $_SESSION['connected'] == false )
		{
			echo "You are not connected to the transaction server.";
		}
		else
		{
			$usr = trim( substr( $_GET['command'], 8 ) );

			$result = $db->query( "SELECT nickname, account_number, account_type, balance FROM bank_accounts INNER JOIN char_characters ON bank_accounts.owner_id=char_characters.id INNER JOIN localcity ON bank_accounts.business_id=localcity.business_id WHERE localcity.location_id=". $char->location_nr ." AND bank_accounts.business_id=localcity.business_id AND char_characters.nickname=". $db->prepval( $usr ) );
			if ( $db->getRowCount( $result ) == 0 )
			{
				echo "That person is not known with the Bank of ". $char->location;
			}
			else
			{
				echo "Listing bank accounts for person: " . $usr ."\n";
				echo sprintf( "%-25s%-25s%-25s\n", "Account Number:", "Account Type:", "Balance:" );
				while ( $acc = $db->fetch_array( $result ) )
					echo sprintf( "%-25s%-25s%-25s\n", $acc['account_number'], getBankAccountTypeString( $acc['account_type'] ), "$". $acc['balance'] );
			}
		}
	}
	elseif ( trim( substr( trim( $_GET['command'] ), 0, 7 ) ) == "connect" )
	{
		$params = trim( substr( trim( $_GET['command'] ), 7 ) );
		if ( substr( $params, 0, 2 ) != "-u" && substr( $params, 0, 2 ) != "-p" )
			echo "Invalid parameter list.";
		else
		{
			$usr = $pwd = "";

			if ( substr( $params, 0, 2 ) == "-u" )
			{
				$pos = strpos( $params, "-p" );
				$usr = trim( substr( $params, 2, $pos - 2 ) );
				$pwd = trim( substr( $params, $pos + 2 ) );
			}
			else
			{
				$pos = strpos( $params, "-u" );
				$pwd = trim( substr( $params, 2, $pos - 2 ) );
				$usr = trim( substr( $params, $pos + 2 ) );
			}

			if ( $usr == $_SESSION['hb_usr'] && $pwd == $_SESSION['hb_pwd'] )
			{
				echo "You are now connected to the transaction server.";
				$_SESSION['connected'] = true;
			}
			else
			{
				echo "Invalid user/password combination.";
			}
		}
	}
	elseif ( $_GET['command'] == "ls" )
	{
		echo "Listing files for directory: ". ( $_SESSION['curr_dir'] == "/" ? $_SESSION['curr_dir'] : "/". $_SESSION['curr_dir'] ) ."\n";
		if ( $_SESSION['curr_dir'] == "/" )
		{
			foreach ( $farray as $key => $value )
				if ( $key != "/" )
					echo sprintf( "%-20s%s\n", $key, "dir" );
		}
		
		foreach ( $farray[$_SESSION['curr_dir']] as $key => $val )
			echo sprintf( "%-20s%s\n", $key, "file" );
		
	}
	elseif ( substr( trim( $_GET['command'] ), 0, 2 ) == "cd" )
	{
		$cmd = trim( $_GET['command'] );
		$dir = trim( substr( $cmd, 2 ) );

		$isdir = false;
		foreach( $farray as $key => $value )
			if ( $key == $dir || "/".$key == $dir )
				$isdir = true;

		if ( $isdir )
		{
			$_SESSION['curr_dir'] = ( substr( $dir, 0, 1 ) == "/" && strlen( $dir ) > 1 ? substr( $dir, 1 ) : $dir );
			echo "Changed directory to ". ( $_SESSION['curr_dir'] == "/" ? "/" : "/". $_SESSION['curr_dir'] ) ."\n";
		}
		else
		{
			echo "Path is no directory.\n";
		}
	}
	elseif ( substr( $_GET['command'], 0, 8 ) == "readfile" )
	{
		/* Check if the file exists. */
		$cmd = trim( $_GET['command'] );
		$file = trim( substr( $cmd, 8 ) );

		$isfile = false;
		foreach( $farray[$_SESSION['curr_dir']] as $key => $value )
			if ( $key == $file )
				$isfile = true;

		if ( $isfile )
		{
			echo "Listing file contents for file ". $file .":\n";
			echo $farray[$_SESSION['curr_dir']][$file][1];
		}
		else
		{
			echo "File not found.";
		}
	}
	else
	{
		echo "Unknown command";
	}		
	exit;
}

/* Apparently the page has been reloaded, so unset u_session. */
unset( $_SESSION['u_session'] );
?>
<script type="text/javascript">
function response( text )
{
	currtext = document.getElementById( 'output' ).value;
	document.getElementById( 'output' ).value += text + '\n';
	document.getElementById( 'output' ).scrollTop = document.getElementById( 'output' ).scrollHeight - document.getElementById( 'output' ).clientHeight;
	document.getElementById('t_input').readOnly = false;
	document.getElementById('t_input').focus();
}
function send_command( e )
{
	var code;
	if ( !e ) var e = window.event;
	if ( e.keyCode ) code = e.keyCode;
	else if ( e.which ) code = e.which;
	
	if ( code == 13 ) 
	{
		command = document.getElementById('t_input').value;
		ajax = new AjaxConnection( response );
		ajax.setScriptUrl('aggs/hackbank.php');
		ajax.addParam('ajaxrequest','command_entered');
		ajax.addParam('command',command);
		ajax.send( null );
		
		document.getElementById('t_input').value='';
		document.getElementById('output').value += "$: " + command + '\n';
		document.getElementById( 'output' ).scrollTop = document.getElementById( 'output' ).scrollHeight - document.getElementById( 'output' ).clientHeight;
		document.getElementById('t_input').readOnly = true;
	}	
}
</script>

<div style="width: 100%; max-width: 100%;">
	
	<div class="title">
		<div style="margin-left: 10px;">Hack into the local bank's mainframe</div>
	</div>
	<div class="content">
		You have forced entry into one of the Bank of <?=$char->location;?>'s terminals and you're staring at a black screen. You haven't had a lot of practise at this, but you're still reasonably sure you can get into the bank's mainframe. After all, common bank employees use this terminal as well! There must be some kind of help command...</p>
	</div>

	<div class="title">
		<div style="margin-left: 10px;">Terminal</div>
	</div>
	<div class="content">
		<textarea id="output" readonly="true" style="border: none; width: 100%; height: 300px; background-color: black; color: #fff; font-size: 11px; font-weight: bold; font-family: Courier New;"></textarea>
		<input type="text" id="t_input" style=" border: none; width: 100%; background-color: black; color: #fff; font-size: 11px; font-weight: bold; font-family: Courier New;" />
	</div>
	
</div>

<script type="text/javascript">
if ( document.all )
	document.getElementById('t_input').attachEvent( 'onkeypress', function(e){ send_command( e ); } );
else
	document.getElementById('t_input').addEventListener( 'keypress', function(e){ send_command( e ); }, false );
</script>