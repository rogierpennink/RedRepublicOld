			</div>

		</div>	

	</div>

	</div>

	<div class="gradientbar" style="position: relative; margin-top: -22px; text-align: center; clear: both;">
		Copyright &copy; 2006-2007 Red Republic
		<?
		if ( getUserRights() == USER_SUPERADMIN || isset( $_SESSION['adminplayer'] ) )
		{
			if ( $_SERVER["REMOTE_ADDR"] == "127.0.0.1" ) 
				$link = "http://127.0.0.1/phpmyadmin";
			else
				$link = "http://www.red-republic.com:2082/3rdparty/phpMyAdmin/index.php";

			print( " - <a href='" . $link . "' alt='phpMyAdmin' title='MySQL Administration' style='text-decoration: none;'>phpMyAdmin</a>" );
			
			if ( !isset( $_SESSION["debugmode"] ) )
				print( " - <a href='?debugmode=true' style='text-decoration: none;'>Debug</a>" );
			else
				print( " - <a href='?debugmode=false' style='text-decoration: none;'>Debug Off</a>" );

			if ( !isset( $_SESSION['adminplayer'] ) )
				print( " - <a onclick='warnDisplay();' title='Switch to Player display.' style='text-decoration: none; cursor: pointer;'>Player Display</a>" );
			else
				print( " - <a href='" . $PHP_SELF . "?display=10' title='Switch to Admin display.' style='text-decoration: none;'>Admin Display</a>" );

			if ( isset( $_SESSION["debugmode"] ) )
			{
				$qcount = 0;
				if ( isset( $db ) ) $qcount = $qcount+$db->getQueryCount();
				if ( isset( $char ) ) $qcount = $qcount+$char->db->getQueryCount();
				if ( isset( $gov ) ) $qcount = $qcount+$gov->db->getQueryCount();
				print( " - " . $qcount . " Primary Queries" );
			}
		}
		?>
	</div>

</body>

</html>
<?
/* "Traveling" Large Events, Deletes 
 * Large events titled "Traveling" are travel messages,
 * for characters traveling to another location.
 * They take up space, and delete wont harm it.
 */
if ( isset( $_SESSION["ingame"] ) && isset( $char ) )
	$db->query( "DELETE FROM events_large WHERE subject='Traveling' AND char_id=" . $char->getCharacterID() );
?>