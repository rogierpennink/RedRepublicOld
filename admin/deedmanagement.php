<?
/**
 * Vices and Virtues management. Manager character attainable deeds.
 */
$nav = "deedman";
$ext_style = "forums_style.css";
$AUTH_LEVEL = 10;
$REDIRECT = true;
include_once "../includes/utility.inc.php";

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

include_once "../includes/header.php";

?>	
	<!-- AN INTRODUCTORY PIECE OF TEXT -->
	<h1>Vices and Virtues</h1>
	<p>
	Here you can edit character deeds and personality traits, that are gained throughout the game by doing various actions. This tool is easy to use, but requires you to do some small editing, remember to read the instructions.<br /><br />
	<a style='text-decoration: none;' href='<?=$rootdir;?>/docs.php?type=slotdoc'><img border='0' alt='Info' src='<?=$rootdir;?>/images/charstats.png' /> Using and Preparing Unused Slots</a><br />
	<a style='text-decoration: none;' href='<?=$rootdir;?>/docs.php?type=trigtut'><img border='0' alt='Info' src='<?=$rootdir;?>/images/charstats.png' /> Deed Trigger Reference</a><br />
	</p>
	<?
	/* Get personalities for slot a through p */
	$slot_a = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_a' ORDER BY value_required ASC" );
	$slot_b = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_b' ORDER BY value_required ASC" );
	$slot_c = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_c' ORDER BY value_required ASC" );
	$slot_d = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_d' ORDER BY value_required ASC" );
	$slot_e = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_e' ORDER BY value_required ASC" );
	$slot_f = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_f' ORDER BY value_required ASC" );
	$slot_g = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_g' ORDER BY value_required ASC" );
	$slot_h = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_h' ORDER BY value_required ASC" );
	$slot_i = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_i' ORDER BY value_required ASC" );
	$slot_j = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_j' ORDER BY value_required ASC" );
	$slot_k = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_k' ORDER BY value_required ASC" );
	$slot_l = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_l' ORDER BY value_required ASC" );
	$slot_m = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_m' ORDER BY value_required ASC" );
	$slot_n = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_n' ORDER BY value_required ASC" );
	$slot_o = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_o' ORDER BY value_required ASC" );
	$slot_p = $db->query( "SELECT * FROM personalities WHERE slot_required='slot_p' ORDER BY value_required ASC" );
	?>
	<!-- Slot A -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_A;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_a ) == 0 ): ?>No personality traits found for <?=DEED_NAME_A;?>.<? endif; ?>
		<?
		while ( $row = $db->fetch_array( $slot_a ) )
		{
			?><b><a style='text-decoration: none;' href='editdeed.php?id=<?=$row['id'];?>'><?=$row['name'];?></a></b> (value required: <font color=''><?=$row['value_required'];?></font>)<br /><?=$row['description'];?><br /><br />
		<?
		}
		?>
		<input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=a";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot B -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_B;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_b ) == 0 ): ?>No personality traits found for <?=DEED_NAME_B;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=b";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot C -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_C;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_c ) == 0 ): ?>No personality traits found for <?=DEED_NAME_C;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=c";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot D -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_D;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_d ) == 0 ): ?>No personality traits found for <?=DEED_NAME_D;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=d";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot E -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_E;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_e ) == 0 ): ?>No personality traits found for <?=DEED_NAME_E;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=e";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot F -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_F;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_f ) == 0 ): ?>No personality traits found for <?=DEED_NAME_F;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=f";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot G -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_G;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_g ) == 0 ): ?>No personality traits found for <?=DEED_NAME_G;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=g";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot H -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_H;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_h ) == 0 ): ?>No personality traits found for <?=DEED_NAME_H;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=h";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot I -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_I;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_i ) == 0 ): ?>No personality traits found for <?=DEED_NAME_I;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=i";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot J -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_J;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_j ) == 0 ): ?>No personality traits found for <?=DEED_NAME_J;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=j";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot K -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_K;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_k ) == 0 ): ?>No personality traits found for <?=DEED_NAME_K;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=k";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot L -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_L;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_l ) == 0 ): ?>No personality traits found for <?=DEED_NAME_L;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=l";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot M -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_M;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_m ) == 0 ): ?>No personality traits found for <?=DEED_NAME_M;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=m";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot N -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_N;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_n ) == 0 ): ?>No personality traits found for <?=DEED_NAME_N;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=n";' value='          New Trait          '>
		</div>	</div>

	<!-- Slot O -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_O;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_o ) == 0 ): ?>No personality traits found for <?=DEED_NAME_O;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=o";' value='          New Trait          '>
		</div>
	</div>

	<!-- Slot P -->
	<div style="width: 80%;">
		<div class="titledeedman"><img alt="" border='0' src='<?=$rootdir;?>/images/hammer.png' /> <?=DEED_NAME_P;?></div>
		<div class="contentdeedman">
		<? if ( $db->getRowCount( $slot_p ) == 0 ): ?>No personality traits found for <?=DEED_NAME_P;?>.<? endif; ?>
		<br /><br /><input type='submit' class='std_input' name='new' onclick='window.location="newdeed.php?row=p";' value='          New Trait          '>
		</div>
	</div>
<?
include_once "../includes/footer.php";
?>