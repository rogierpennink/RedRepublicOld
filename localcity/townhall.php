<?
/**
 * Town Hall - Serves as a business trader, and citizen port into politics.
 **
 * TODO: Party Creation
 */

$nav = "localcity";
$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = false;
$_SESSION['ingame'] = true;
include_once "../includes/utility.inc.php";
$char = new Player( getUserID() );

/* Is a townhall in the current city? */
$q = $db->query( "SELECT * FROM localcity AS l INNER JOIN businesses AS b ON l.business_id = b.id WHERE b.url='townhall.php' AND l.location_id=". $db->prepval( $char->location_nr ) );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This city doesn't have a Town Hall!";
	header( "Location: index.php" );
	exit;
}

/* Purchase Business */
if ( isset( $_GET['act'] ) && $_GET['act'] == 'purchase' && isset( $_GET['bid'] ) && is_numeric( $_GET['bid'] ) )
{
	$pbquery = $db->query( "SELECT * FROM businesses WHERE id=" . $_GET['bid'] . " AND for_sale='true'" );
	if ( $db->getRowCount( $pbquery ) == 0 )
		$_SESSION["townhall_message"] = "You can't buy businesses that don't exist.";
	else
	{
		$bus = $db->fetch_array( $pbquery );
		$localcitychk = $db->query( "SELECT id FROM localcity WHERE business_id=" . $bus['id'] . " AND location_id=" . $char->location_nr );
		if ( $db->getRowCount( $localcitychk ) == 0 )
			$_SESSION['townhall_message'] = "You can't buy a business thats not in this city.";
		else
		{
			if ( $char->getCharacterID() == $bus['owner_id'] )
				$_SESSION['townhall_message'] = "You cannot buy a business thats already your's!";
			else
			{
				if ( $char->getCleanMoney() < $bus['sale_price'] )
					$_SESSION['townhall_message'] = "You don't have enough money to buy this business.";
				else
				{
					$previousowner = new Player( $bus['owner_id'], false );
					$char->setCleanMoney( $char->getCleanMoney( true )-$bus['sale_price'] );
					$previousowner->setCleanMoney( $previousowner->getCleanMoney( true )+$bus['sale_price'] );
					$db->query( "UPDATE businesses SET sale_price=0, for_sale='false', owner_id=" . $char->getCharacterID() . " WHERE id=" . $_GET['bid'] );
					$_SESSION['townhall_message'] = "You are now the proud new owner of " . $bus['name'] . ".";
					$previousowner->addEvent( "Business Sold", $char->nickname . " has bought " . $bus['name'] . " for $" . $bus['sale_price'] . ". You handed them the keys to the front door and strong box then watched them go to work in their new business." );
				}
			}
		}
	}
}

/* Sell Business */
if ( isset( $_POST['sell'] ) )
{
	if ( !isset( $_POST['price'] ) )
		$_SESSION["townhall_message"] = "You must place a price on your business before you decide to sell it.";
	else
	{
		if ( !is_numeric( $_POST['price'] ) )
			$_SESSION["townhall_message"] = "You must place a valid price for this business!";
		else
		{
			if ( $_POST['price'] < 1 )
				$_SESSION["townhall_message"] = "The price for this business must be at least $1.";
			else
			{
				$db->query( "UPDATE businesses SET sale_price=" . $db->prepval( $_POST['price'] ) . ", for_sale='true' WHERE id=" . $db->prepval( $_POST['bid'] ) );
				$_SESSION["townhall_message"] = "Your business has been put up for sale in its city, now you'll just have to wait for someone to buy it!";
			}
		}
	}
}

/* Unsell */
if ( isset( $_GET['act'] ) && $_GET['act'] == 'unsell' && isset( $_GET['bid'] ) )
{
	$bchkq = $db->query( "SELECT * FROM businesses WHERE id=" . $db->prepval( $_GET['bid'] ) . " AND owner_id=" . $char->getCharacterID() . " AND for_sale='true'" );

	if ( $db->getRowCount( $bchkq ) == 0 )
		$_SESSION["townhall_message"] = "You cannot unsell a business that is not yours.";
	else
	{
		$db->query( "UPDATE businesses SET for_sale='false', sale_price=0 WHERE id=" . $_GET['bid'] );
		$_SESSION["townhall_message"] = "You have taken the for sale sign out of the window of your business.";
	}
}

/* Vote */
if ( isset( $_GET['act'] ) && $_GET['act'] == 'vote' && isset( $_GET['gid'] ) )
{
	$govchk = $db->query( "SELECT * FROM localgov_parties WHERE id=" . $_GET['gid'] );

	if ( !is_numeric( $_GET['gid'] ) || $db->getRowCount( $govchk ) == 0 && $_GET['gid'] != 0 )
		$_SESSION["townhall_message"] = "You cannot vote for a non existing party.";
	else
	{
		if ( $char->homecity_nr != $char->location_nr )
			$_SESSION["townhall_message"] = "You cannot vote outside you're homecity!";
		else
		{

			$votechk = $db->query( "SELECT * FROM localgov_votes WHERE char_id=" . $char->getCharacterID() );
			if ( $db->getRowCount( $votechk ) == 0 )
			{
				$db->query( "INSERT INTO localgov_votes (id, party_id, char_id, location_id) VALUES ('', " . $db->prepval( $_GET['gid'] ) . ", " . $char->getCharacterID() . ", " . $char->location_nr . ")" );
				$_SESSION["townhall_message"] = "You're vote has been registered.";
			}
			else
				$_SESSION["townhall_message"] = "You have already voted, you cannot vote again.";
		}
	}
}

/* Withdraw Support */
if ( isset( $_GET['act'] ) && $_GET['act'] == 'withdraw' )
{
	$votechk = $db->query( "SELECT * FROM localgov_votes WHERE char_id=" . $char->getCharacterID() );
	if ( $db->getRowCount( $votechk ) == 0 )
		$_SESSION["townhall_message"] = "You have not voted support for any party yet, therefor you cannot withdraw your vote.";
	else
	{
		$db->query( "DELETE FROM localgov_votes WHERE char_id=" . $char->getCharacterID() );
		$_SESSION["townhall_message"] = "You have withdrawn your support for this party.";
	}
}

/* Become Party Member */
if ( isset( $_GET['act'] ) && $_GET['act'] == 'member' && isset( $_GET['pid'] ) )
{
	$partychk = $db->query( "SELECT * FROM localgov_parties WHERE id=" . $db->prepval( $_GET['pid'] ) );
	if ( !is_numeric( $_GET['pid'] ) || $db->getRowCount( $partychk ) == 0 )
		$_SESSION["townhall_message"] = "You cannot become a member for a non existing party.";
	else
	{
		if ( $char->getTier() < 4  )
			$_SESSION["townhall_message"] = "You should build up your reputation before concidering to be a party member.";
		else
		{
			$party = $db->fetch_array( $partychk );
			
			if ( $party['party_member_1'] == $char->getCharacterID() || $party['party_member_2'] == $char->getCharacterID() || $party['party_member_3'] == $char->getCharacterID() || $party['party_member_4'] == $char->getCharacterID() || $party['party_member_5'] == $char->getCharacterID() )
				$_SESSION["townhall_message"] = "You are already a member of this party.";
			else
			{
				if ( $char->getCleanMoney( true ) < 300000 )
					$_SESSION["townhall_message"] = "You do not have enough money to become a party member.";
				else
				{
					if ( $party['party_member_1'] == 0 )
					{
						$db->query( "UPDATE localgov_parties SET party_member_1=" . $char->getCharacterID() . " WHERE id=" . $party['id'] );
						$char->setCleanMoney( $char->getCleanMoney( true )-300000 );
						$db->query( "UPDATE localgov_parties SET budget=budget+300000 WHERE id=" . $party['id'] );
						$_SESSION["townhall_message"] = "You are now officially recognized as a party member.";
					}
					elseif ( $party['party_member_2'] == 0 )
					{
						$db->query( "UPDATE localgov_parties SET party_member_2=" . $char->getCharacterID() . " WHERE id=" . $party['id'] );
						$char->setCleanMoney( $char->getCleanMoney( true )-300000 );
						$db->query( "UPDATE localgov_parties SET budget=budget+300000 WHERE id=" . $party['id'] );
						$_SESSION["townhall_message"] = "You are now officially recognized as a party member.";
					}
					elseif ( $party['party_member_3'] == 0 )
					{
						$db->query( "UPDATE localgov_parties SET party_member_3=" . $char->getCharacterID() . " WHERE id=" . $party['id'] );
						$char->setCleanMoney( $char->getCleanMoney( true )-300000 );
						$db->query( "UPDATE localgov_parties SET budget=budget+300000 WHERE id=" . $party['id'] );
						$_SESSION["townhall_message"] = "You are now officially recognized as a party member.";
					}
					elseif ( $party['party_member_4'] == 0 )
					{
						$db->query( "UPDATE localgov_parties SET party_member_4=" . $char->getCharacterID() . " WHERE id=" . $party['id'] );
						$char->setCleanMoney( $char->getCleanMoney( true )-300000 );
						$db->query( "UPDATE localgov_parties SET budget=budget+300000 WHERE id=" . $party['id'] );
						$_SESSION["townhall_message"] = "You are now officially recognized as a party member.";
					}
					elseif ( $party['party_member_5'] == 0 )
					{
						$db->query( "UPDATE localgov_parties SET party_member_5=" . $char->getCharacterID() . " WHERE id=" . $party['id'] );$char->setCleanMoney( $char->getCleanMoney( true )-300000 );
						$db->query( "UPDATE localgov_parties SET budget=budget+300000 WHERE id=" . $party['id'] );
						$_SESSION["townhall_message"] = "You are now officially recognized as a party member.";
					}
					else
						$_SESSION["townhall_message"] = "Sorry, the party member seats have all been filled.";
				}
			}
		}
	}
}

/* Denounce Party, or Un-Member */
if ( isset( $_GET['act'] ) && $_GET['act'] == "denounce" && isset( $_GET['pid'] ) )
{
	$partychk = $db->query( "SELECT * FROM localgov_parties WHERE id=" . $db->prepval( $_GET['pid'] ) );
	if ( !is_numeric( $_GET['pid'] ) || $db->getRowCount( $partychk ) == 0 )
		$_SESSION["townhall_message"] = "You cannot denounce a pary that doesn't exist.";
	else
	{
		$party = $db->fetch_array( $partychk );
		if ( $party['party_member_1'] != $char->getCharacterID() && $party['party_member_2'] != $char->getCharacterID() && $party['party_member_3'] != $char->getCharacterID() && $party['party_member_4'] != $char->getCharacterID() && $party['party_member_5'] != $char->getCharacterID() )
			$_SESSION["townhall_message"] = "You're not even a member of this party.";
		else
		{
			if ( $party['party_member_1'] == $char->getCharacterID() ) $i = 1;
			if ( $party['party_member_2'] == $char->getCharacterID() ) $i = 2;
			if ( $party['party_member_3'] == $char->getCharacterID() ) $i = 3;
			if ( $party['party_member_4'] == $char->getCharacterID() ) $i = 4;
			if ( $party['party_member_5'] == $char->getCharacterID() ) $i = 5;

			$db->query( "UPDATE localgov_parties SET party_member_" . $i . "=0 WHERE id=" . $_GET['pid'] );
			$_SESSION["townhall_message"] = "You have denounced this party.";
		}
	}
}

/* Disband a Party */
if ( isset( $_GET['act'] ) && $_GET['act'] == "disband" && isset( $_GET['pid'] ) )
{
	$partychk = $db->query( "SELECT * FROM localgov_parties WHERE id=" . $db->prepval( $_GET['pid'] ) . " AND leader_id=" . $char->getCharacterID() );
	if ( !is_numeric( $_GET['pid'] ) || $db->getRowCount( $partychk ) == 0 )
		$_SESSION["townhall_message"] = "You cannot denounce a pary that doesn't exist or that your not the leader of.";
	else
	{
		$db->query( "DELETE FROM localgov_parties WHERE id=" . $_GET['pid'] );
		$db->query( "DELETE FROM localgov_votes WHERE party_id=" . $_GET['pid'] );
		$_SESSION["townhall_message"] = "You have disbanded the party, now what will become of the city?";
	}
}

// Start the real main page
include_once "../includes/header.php";
?>

<h1><?=$char->location;?> Hall</h1>
<p>Welcome to the central building for <?=$char->location;?> business and politics! Here is where the major part of politics are debated and businesses are bought and sold.
<?
if ( isset( $_SESSION['townhall_message'] ) )
{
	print( "<br /><br /><b>" . $_SESSION['townhall_message'] . "</b><br />" );
	unset( $_SESSION['townhall_message'] );
}
?>
</p>

<table style="width: 100%;">
	<tr>
		<td style="width: 45%; vertical-align: top;">
			<div class="title">
				<div style="margin-left: 10px;">Businesses up for Sale</div>
			</div>
			<div class="content">
				<table>
					<?
					$businessquery = $db->query( "SELECT * FROM localcity AS a INNER JOIN businesses AS b ON a.business_id = b.id WHERE b.for_sale='true' AND a.location_id=". $db->prepval( $char->location_nr ) );
					if ( $db->getRowCount( $businessquery ) == 0 )
					{
						?><tr><td>Currently there are no businesses up for sale in this city.</td></tr><?
					}
					else
					{
						while ( $bus = $db->fetch_array( $businessquery ) )
						{
							$localcitychk = $db->query( "SELECT id FROM localcity WHERE business_id=" . $bus['id'] . " AND location_id=" . $char->location_nr );

							if ( $db->getRowCount( $localcitychk ) != 0 )
							{
								?>
								<tr>
									<td style='width: 55px; vertical-align: top;'><img alt='' title="Business for sale." src="<?=$rootdir;?>/images/for_sale.gif" width="55" height="62" /></td>
									<td style='vertical-align: top;'><b><a href='<?=$rootdir;?>/localcity/townhall.php?act=purchase&bid=<?=$bus['id'];?>' style='text-decoration: none;'><?=$bus['name'];?></a></b><br />
									<?=$bus['desc'];?><br />
									
									<?
									if ( $bus['owner_id'] != 0 )
									{
										?>
										For sale by <a style='text-decoration: none;' href='<?=$rootdir;?>/profile.php?id=<?=$bus['owner_id'];?>' title="View owner's profile."><?=getCharNickname( $bus['owner_id'] );?></a><br />
										<?
									}
									?>
									
									Asking Price: <span style="color: #339900;">$<?=$bus['sale_price'];?></span><br />
									
									<?
									if ( $bus['owner_id'] == $char->getCharacterID() )
									{
										?>
										<a style='text-decoration: none;' href='<?=$rootdir;?>/localcity/townhall.php?act=unsell&bid=<?=$bus['id'];?>' title='Unsell your business.'>Unsell Business</a><br />
										<?
									}
									?>
								</tr>
								<?
							}
						}
					}
					?>
				</table>
				<?
				$pbownchk = $db->query( "SELECT * FROM businesses WHERE owner_id=" . $char->getCharacterID() . " AND for_sale='false'" );
				if ( $db->getRowCount( $pbownchk ) > 0 )
				{
					?>
					<br /><br /><b>Sell Businesses</b>
					<?
					while ( $owned = $db->fetch_array( $pbownchk ) )
					{
						?>
						<div style="margin-left: 10px;">
							<span style="color: #3F6774;"><?=$owned['name'];?></span><br />
							<?=$owned['desc'];?><br />
							<form method='post' action='<?=$rootdir;?>/localcity/townhall.php'>
								Asking Price: $<input type='text' name='price' class='std_input' style='font-size: 11px;' /> <input name='sell' type='submit' class='std_input' style='font-size: 11px;' value='Sell' />
								<input type='hidden' name='bid' value='<?=$owned['id'];?>' />
							</form>
							<br />
						</div>
						<?
					}
				}
				?>
			</div>
		</td>
		<td style="width: 10%;">&nbsp;</td>
		<td style="width: 45%; vertical-align: top;" align="center">
		<?
		$imgsrcp = ( $_SESSION['show_parties'] ) ? $rootdir . "/images/icon_minimize.gif" : $rootdir . "/images/icon_expand.gif";
		$altp = ( $_SESSION['show_parties'] ) ? "expanded" : "minimized";
		$displayp = ( $_SESSION['show_parties'] ) ? "block" : "none";
		?>
		<div class="title">
			<div style="float: right; vertical-align: center;"><img title="Expand" src="<?=$imgsrcp;?>" alt="<?=$altp;?>" style="margin-right: 5px; cursor: pointer;" onclick="setPartyView( this );" /></div><div style="margin-left: 10px;">Active Political Parties</div>
		</div>
		<div class="content" id="party_content" style="display: <?=$displayp;?>;">
			Here is where you can claim support for a political party. Elections are held every month, and the party with the most supporters wins, however, in order to even be up for election, a party must have at least five party members who have donated. You may vote, and withdraw your vote, to vote for another party at any time.
			<br /><br /><hr style="width: 80%;" /><br />
			<b>The <?=$char->location;?> Anarchy Party</b><br />
			The anarchist party never seems to go away, the idea that the city should be run by mob rule is always a possibility, and is also the enemy of legitimate parties, however still anarchist party draws supporters. An anarchist government means no funding for government services which can decline life in the city, however it brings a good kind of freedom for all citizens.<br /><br />
			<?
			$supporters = $db->getRowCount( $db->query( "SELECT * FROM localgov_votes WHERE location_id=" . $char->location_nr . " AND party_id=0" ) );
			?>
			<table>
			<tr><td>Supporters:&nbsp;</td><td><span style='color: #FF0033;'><?=$supporters;?></span></td></tr>
			<tr><td>Leader:&nbsp;</td><td><span style='color: #009933;'>None</span></td></tr>
			<tr><td>Party Members:&nbsp;</td><td><span style='color: #4E6966;'>None</span></td></tr>
			</table><br />
			<?
			/* Check if this party is in control, we can tell if the localgov is the parties gov id, because
			 * we may only have one of each gov id.
			 */
			$curparty = $db->query( "SELECT id FROM localgov WHERE government_id=0 AND location_id=" . $char->location_nr );
			if ( $db->getRowCount( $curparty ) > 0 )
			{
				?><img style="border: none;" alt="" src="<?=$rootdir;?>/images/checkOk.png" /> This party is currently in control.<br /><br /><?
			}
			?>
			<!-- List Citizen Options -->
			<?
			if ( $char->homecity == $char->location )
			{
				$chkvotes = $db->query( "SELECT * FROM localgov_votes WHERE char_id=" . $char->getCharacterID() . " AND party_id=0" );
				if ( $db->getRowCount( $chkvotes ) == 0 )
				{
					?><a style='text-decoration: none;' href='<?=$rootdir;?>/localcity/townhall.php?act=vote&gid=0'>Support this Party</a><?
				}
				else
				{
					?><a style='text-decoration: none;' href='<?=$rootdir;?>/localcity/townhall.php?act=withdraw'>Withdraw Support</a><?
				}
			}

			/* Start listing Parties */
			$partyq = $db->query( "SELECT * FROM localgov_parties WHERE location_id=" . $char->location_nr );

			while ( $party = $db->fetch_array( $partyq ) )
			{
				$govtemp = $db->getRowsFor( "SELECT * FROM governments WHERE id=" . $party['government_id'] );
				?><br /><br /><hr style="width: 80%;" /><br />
				<b>The <?=$char->location;?> <?=$govtemp['formal'];?> Party</b><br />
				<?=$govtemp['description'];?><br /><br />
				<img style="border: none;" alt="" src="<?=$rootdir;?>/images/icon_read_red.png" /> <?=$party['message'];?><br /><br />
				<?
				$supporters = $db->getRowCount( $db->query( "SELECT * FROM localgov_votes WHERE location_id=" . $char->location_nr . " AND party_id=" . $party['id'] ) );
				?>
				<table>
				<tr><td>Supporters:&nbsp;</td><td><span style='color: #FF0033;'><?=$supporters;?></span></td></tr>
				<tr><td>Leader:&nbsp;</td><td><a style='text-decoration: none;' title='View Profile' href='<?=$rootdir;?>/profile.php?id=<?=$party['leader_id'];?>'><span style='color: #009933;'><?=getCharNickname( $party['leader_id'] );?></span></a></td></tr>
				<tr><td>Party Members:&nbsp;</td><td><span style='color: #4E6966;'>
				<?
				if ( $party['party_member_1'] != 0 ) print( "<a style='text-decoration: none;' title='View Profile' href='$rootdir/profile.php?id=" . $party['party_member_1'] . "'>" . getCharNickname( $party['party_member_1'] ) . "</a>" );

				if ( $party['party_member_2'] != 0 ) print( ", <a style='text-decoration: none;' title='View Profile' href='$rootdir/profile.php?id=" . $party['party_member_2'] . "'>" . getCharNickname( $party['party_member_2'] ) . "</a>" );

				if ( $party['party_member_3'] != 0 ) print( ", <a style='text-decoration: none;' title='View Profile' href='$rootdir/profile.php?id=" . $party['party_member_3'] . "'>" . getCharNickname( $party['party_member_3'] ) . "</a>" );

				if ( $party['party_member_4'] != 0 ) print( ", <a style='text-decoration: none;' title='View Profile' href='$rootdir/profile.php?id=" . $party['party_member_4'] . "'>" . getCharNickname( $party['party_member_4'] ) . "</a>" );

				if ( $party['party_member_5'] != 0 ) print( ", <a style='text-decoration: none;' title='View Profile' href='$rootdir/profile.php?id=" . $party['party_member_5'] . "'>" . getCharNickname( $party['party_member_5'] ) . "</a>" );

				if ( $party['party_member_1'] == 0 && $party['party_member_2'] == 0 && $party['party_member_3'] == 0 && $party['party_member_4'] == 0 && $party['party_member_5'] == 0 ) { print( "None" ); }
				?>
				</span></td></tr>
				<tr><td>Tax Proposal:&nbsp;</td><td><span style='color: #70318A;'><?=$party['tax_proposal'];?></span>%</td>
				</table><br />
				<!-- List Options -->
				<?
				if ( $char->homecity == $char->location )
				{
					$chkvotes = $db->query( "SELECT * FROM localgov_votes WHERE char_id=" . $char->getCharacterID() . " AND party_id=" . $party['id'] );
					if ( $db->getRowCount( $chkvotes ) == 0 )
					{
						?><a style='text-decoration: none;' href='<?=$rootdir;?>/localcity/townhall.php?act=vote&gid=<?=$party['id'];?>'>Support this Party</a><?
					}
					else
					{
						?><a style='text-decoration: none;' href='<?=$rootdir;?>/localcity/townhall.php?act=withdraw'>Withdraw Support</a><?
					}

					if ( ( $party['party_member_1'] == 0 || $party['party_member_2'] == 0 || $party['party_member_3'] == 0 || $party['party_member_4'] == 0 || $party['party_member_5'] == 0 ) && ( $party['party_member_1'] != $char->getCharacterID() && $party['party_member_2'] != $char->getCharacterID() && $party['party_member_3'] != $char->getCharacterID() && $party['party_member_4'] != $char->getCharacterID() && $party['party_member_5'] != $char->getCharacterID() ) && ( $party['leader_id'] != $char->getCharacterID() ) )
					{
						?>
						 | <a style='text-decoration: none;' href='<?=$rootdir;?>/localcity/townhall.php?act=member&pid=<?=$party['id'];?>'>Become a Party Member</a> ($300,000)
						<?
					}
					elseif ( ( $party['party_member_1'] == $char->getCharacterID() || $party['party_member_2'] == $char->getCharacterID() || $party['party_member_3'] == $char->getCharacterID() || $party['party_member_4'] == $char->getCharacterID() || $party['party_member_5'] == $char->getCharacterID() ) )
					{
						?>
						 | <a style='text-decoration: none;' href='<?=$rootdir;?>/localcity/townhall.php?act=denounce&pid=<?=$party['id'];?>'>Denounce Party</a>
						<?
					}

					if( $party['leader_id'] == $char->getCharacterID() )
					{
						?>
						 | <a style='text-decoration: none;' href='<?=$rootdir;?>/localcity/townhall.php?act=disband&pid=<?=$party['id'];?>'>Disband Party</a>
						<?
					}
				}
			}
			?>
		</div>
		</td>
	</tr>
</table>
<?
include_once "../includes/footer.php";
?>