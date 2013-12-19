<?
$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = true;
$CUSTOMCHAR = true;
include "utility.inc.php";

$char = new Player( getUserID() );
if ( $char->armor_head > 0 ) $head = new Item( $char->armor_head ); else $head = false;
if ( $char->armor_chest > 0 ) $chest = new Item( $char->armor_chest ); else $chest = false;
if ( $char->armor_legs > 0 ) $legs = new Item( $char->armor_legs ); else $legs = false;
if ( $char->armor_feet > 0 ) $feet = new Item( $char->armor_feet ); else $feet = false;
if ( $char->main_weapon > 0 ) $weap1 = new Item( $char->main_weapon ); else $weap1 = false;
if ( $char->second_weapon > 0 ) $weap2 = new Item( $char->second_weapon ); else $weap2 = false;
if ( $char->bag > 0 ) $bag = new Item( $char->bag ); else $bag = false;
?>

<div class="title">
	<img src="<?=$rootdir;?>/images/icon_close.gif" alt="close" title="Close This Screen" style="float: right; margin: 2px; cursor: pointer;" onclick="closeCharacter();" />
	<img src="<?=$rootdir;?>/images/charstats.png" alt="" style="margin-left: 2px; margin-right: 5px;" />Character Information Screen
</div>
<div class="content" style="text-align: center; overflow: auto;">
	<? if ( isset( $_GET['msg'] ) && $_GET['msg'] != "" ) {
		echo "<p><strong>". stripslashes( $_GET['msg'] ) ."</strong></p>";
	} ?>
	<table style="width: 100%;">
	<tr>
	<td style="width: 50%">

	<!-- IN HERE THE CHARACTER'S EQUIPMENT WILL BE DISPLAYED -->
	<div style="margin-right: 5px;">
		<div class="title" style="padding-left: 10px;">
			Your Character's Equipment
		</div>
		<div class="content">
			<table style="width: 100%;">	
			<tr>		
				<!-- HEAD ARMOR -->
				<td style="width: 50px; border: solid 1px #990033; font-size: 11px; padding: 10px; background-color: #fff686;" onmouseover="this.style.backgroundColor='#fee676';" onmouseout="this.style.backgroundColor = '#fff686';">
				<div style="margin-bottom: 5px;"><strong>Head</strong></div>
				<?=( $head ) ? "<a href='javascript: de_equip_item( ".$head->item_id." );'>" : "";?>
				<img <?=( $head ) ? "id='item".$head->item_id."' " : "";?> src="<?=$rootdir;?>/images/<?=( !$head ) ? "items/empty_item.png" : $head->icon;?>" alt="" style="border: solid 1px #990033;" />
				<?=( $head ) ? "</a>" : "";?>
				</td>

				<!-- MAIN WEAPON -->
				<td style="width: 50px; border: solid 1px #990033; font-size: 11px; padding: 10px; background-color: #fff686;" onmouseover="this.style.backgroundColor='#fee676';" onmouseout="this.style.backgroundColor = '#fff686';">
				<div style="margin-bottom: 5px;"><strong>Main Weapon</strong></div>
				<?=( $weap1 ) ? "<a href='javascript: de_equip_item( ".$weap1->item_id." );'>" : "";?>
				<img <?=( $weap1 ) ? "id='item".$weap1->item_id."' " : "";?> src="<?=$rootdir;?>/images/<?=( !$weap1 ) ? "items/empty_item.png" : $weap1->icon;?>" alt="" style="border: solid 1px #990033;" />
				<?=( $weap1 ) ? "</a>" : "";?>
				</td>
			</tr>

			<tr>		
				<!-- CHEST ARMOR -->
				<td style="width: 50px; border: solid 1px #990033; font-size: 11px; padding: 10px; background-color: #fff686;" onmouseover="this.style.backgroundColor='#fee676';" onmouseout="this.style.backgroundColor = '#fff686';">
				<div style="margin-bottom: 5px;"><strong>Chest</strong></div>
				<?=( $chest ) ? "<a href='javascript: de_equip_item( ".$chest->item_id." );'>" : "";?>
				<img <?=( $chest ) ? "id='item".$chest->item_id."' " : "";?> src="<?=$rootdir;?>/images/<?=( !$chest ) ? "items/empty_item.png" : $chest->icon;?>" alt="" style="border: solid 1px #990033;" />
				<?=( $chest ) ? "</a>" : "";?>
				</td>

				<!-- SECONDARY WEAPON -->
				<td style="width: 50px; border: solid 1px #990033; font-size: 11px; padding: 10px; background-color: #fff686;" onmouseover="this.style.backgroundColor='#fee676';" onmouseout="this.style.backgroundColor = '#fff686';">
				<div style="margin-bottom: 5px;"><strong>Second Weapon</strong></div>
				<?=( $weap2 ) ? "<a href='javascript: de_equip_item( ".$weap2->item_id." );'>" : "";?>
				<img <?=( $weap2 ) ? "id='item".$weap2->item_id."' " : "";?> src="<?=$rootdir;?>/images/<?=( !$weap2 ) ? "items/empty_item.png" : $weap2->icon;?>" alt="" style="border: solid 1px #990033;" />
				<?=( $weap2 ) ? "</a>" : "";?>
				</td>
			</tr>

			<tr>		
				<!-- LEG ARMOR -->
				<td style="width: 50px; border: solid 1px #990033; font-size: 11px; padding: 10px; background-color: #fff686;" onmouseover="this.style.backgroundColor='#fee676';" onmouseout="this.style.backgroundColor = '#fff686';">
				<div style="margin-bottom: 5px;"><strong>Legs</strong></div>
				<?=( $legs ) ? "<a href='javascript: de_equip_item( ".$legs->item_id." );'>" : "";?>
				<img <?=( $legs ) ? "id='item".$legs->item_id."' " : "";?> src="<?=$rootdir;?>/images/<?=( !$legs ) ? "items/empty_item.png" : $legs->icon;?>" alt="" style="border: solid 1px #990033;" />
				<?=( $legs ) ? "</a>" : "";?>
				</td>

				<!-- BAG -->
				<td style="width: 50px; border: solid 1px #990033; font-size: 11px; padding: 10px; background-color: #fff686;" onmouseover="this.style.backgroundColor='#fee676';" onmouseout="this.style.backgroundColor = '#fff686';">
				<div style="margin-bottom: 5px;"><strong>Bag</strong></div>
				<?=( $bag ) ? "<a href='javascript: de_equip_item( ".$bag->item_id." );'>" : "";?>
				<img <?=( $bag ) ? "id='item".$bag->item_id."' " : "";?> src="<?=$rootdir;?>/images/<?=( !$bag ) ? "items/empty_item.png" : $bag->icon;?>" alt="" style="border: solid 1px #990033;" />
				<?=( $bag ) ? "</a>" : "";?>
				</td>
			</tr>

			<tr>		
				<!-- FOOT ARMOR -->
				<td style="width: 50px; border: solid 1px #990033; font-size: 11px; padding: 10px; background-color: #fff686;" onmouseover="this.style.backgroundColor='#fee676';" onmouseout="this.style.backgroundColor = '#fff686';">
				<div style="margin-bottom: 5px;"><strong>Feet</strong></div>
				<?=( $feet ) ? "<a href='javascript: de_equip_item( ".$feet->item_id." );'>" : "";?>
				<img <?=( $feet ) ? "id='item".$feet->item_id."' " : "";?> src="<?=$rootdir;?>/images/<?=( !$feet ) ? "items/empty_item.png" : $feet->icon;?>" alt="" style="border: solid 1px #990033;" />
				<?=( $feet ) ? "</a>" : "";?>
				</td>

				<!-- NOTHING! -->
				<td></td>
			</tr>
			</table>
		</div>
	</div>

	</td>
	<td style="width: 50%;" valign="top" align="left">
	
	<div style="margin-left: 5px;">
		<div class="title" style="padding-left: 10px;">Character Information</div>
		<div class="content" style="padding: 0px; height: 100%;">
			<div class="row" style="width: 100%; background-color: #ee9; background-image: url('<?=$rootdir;?>/images/forumgrad_vert_20.png'); border: none;">
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;"><strong>Attribute</strong></td>
						<td class="field"><strong>Value</strong></td>
					</tr>
				</table>
			</div>

			<div class="row" style="background-color: #fff686;">
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;">First Name</td>
						<td class="field"><?=$char->firstname;?></td>
					</tr>
				</table>
			</div>

			<div class="row">
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;">Last Name</td>
						<td class="field"><?=$char->lastname;?></td>
					</tr>
				</table>
			</div>

			<div class="row" style="background-color: #fff686;">
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;">Nickname</td>
						<td class="field"><?=$char->nickname;?></td>
					</tr>
				</table>
			</div>

			<div class="row">
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;">Birthdate</td>
						<td class="field"><?=date( dateDisplay(), strtotime( $char->birthdate ) );?></td>
					</tr>
				</table>
			</div>

			<div class="row" style="background-color: #fff686;">
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;">Gender</td>
						<td class="field"><?=$char->gender_full;?></td>
					</tr>
				</table>
			</div>

			<div class="row">
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;">Checking Account</td>
						<td class="field">
							<? $bankq = $db->query( "SELECT * FROM bank_accounts WHERE owner_id=". $char->character_id ." AND account_type=0 AND account_status <> 0" );
							$bankacc = $db->fetch_array( $bankq );
							echo ( $db->getRowCount( $bankq ) == 0 ) ? "None" : $bankacc['account_number'] . " ($" . $bankacc['balance'] .")<br />";
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="row" style="background-color: #fff686;">
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;">Savings Account</td>
						<td class="field">
							<? $bankq = $db->query( "SELECT * FROM bank_accounts WHERE owner_id=". $char->character_id ." AND account_type=1 AND account_status <> 0" );
							$bankacc = $db->fetch_array( $bankq );
							echo ( $db->getRowCount( $bankq ) == 0 ) ? "None" : $bankacc['account_number'] . " ($" . $bankacc['balance'] .")<br />";
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="row">
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;">Investment Account</td>
						<td class="field">
							<? $bankq = $db->query( "SELECT * FROM bank_accounts WHERE owner_id=". $char->character_id ." AND account_type=2 AND account_status <> 0" );
							$bankacc = $db->fetch_array( $bankq );
							echo ( $db->getRowCount( $bankq ) == 0 ) ? "None" : $bankacc['account_number'] . " ($" . $bankacc['balance'] .")<br />";
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="row" style="background-color: #fff686;">
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;">Armour</td>
						<td class="field"><?=$char->armour;?></td>
					</tr>
				</table>
			</div>

			<div class="row">
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;">Extra Stats</td>
						<td class="field">
						<?
							echo ( $char->extrastr < 0 ) ? "-" : "+"; echo $char->extrastr . " Strength<br />";
							echo ( $char->extradef < 0 ) ? "-" : "+"; echo $char->extradef . " Defense<br />";
							echo ( $char->extraint < 0 ) ? "-" : "+"; echo $char->extraint . " Intellect<br />";
							echo ( $char->extracun < 0 ) ? "-" : "+"; echo $char->extracun . " Cunning<br />";
						?>
						</td>
					</tr>
				</table>
			</div>

			<div class="row" style="background-color: #fff686;">
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;">Current Tier</td>
						<td class="field"><?=$char->getTier();?></td>
					</tr>
				</table>
			</div>

			<div class="row">				
				<table class="row" style="width: 100%;">
					<tr>		
						<td class="field" style="width: 40%;">Next Tier</td>
						<td class="field">
							<table style="width: 100%; border: solid 1px #990033;" cellspacing="0">
							<tr>
								<?
								$pct = ( $char->getTierExp() / ( $char->getTierExp() + $char->getExpToNextTier() ) ) * 100;
								$pct = round( $pct > 100 ? 100 : $pct );
								?>
								<td align="center" style="width: <?=$pct;?>%; background-color: green; color: white;"><?=$pct;?>%</td>
								<td></td>
							</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
		</div>
	</div>
	
	</td>
	</tr>
	</table>
</div>