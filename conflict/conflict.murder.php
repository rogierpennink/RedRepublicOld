<div style="width: 80%; margin-left: auto; margin-right: auto;">
	<div class="title" style="padding-left: 10px;">
		Attempt Murder
	</div>
	<div class="content" style="font-size: 11.5px;" align="center">
	<form action="index.php" method="post">
		<div style="margin-bottom: 5px;"><strong>Enter your target's nickname</strong></div>
		<input type="text" class="std_input" name="victim" style="width: 200px;" value="<?=$_POST['victim'];?>" /><br /><br />
		<div style="margin-bottom: 5px;"><strong>Choose your weapon</strong></div>
		<select class="std_input" name="weapon" style="width: 200px;">
			<option value="0">Bare Hands</option>
			<? if ( $char->hasVehicle() ): ?><option value="-1"><?=$char->getVehicleInfo('name');?></option><? endif; ?>
			<? if ( $char->main_weapon > 0 ) { ?>
			<option value="<?=$char->main_weapon;?>"><? $weap = new Item( $char->main_weapon ); echo $weap->name; ?></option>
			<? } ?>
		</select><br /><br />
		<div style="margin-bottom: 5px;"><strong>Leave a message for your victim</strong></div>
		<textarea style="width: 400px; height: 200px;" name="reason" class="std_input"><?=$_POST['reason'];?></textarea><br /><br />
		<input type="submit" class="std_input" name="domurder" value="Attempt Murder" />
	</form>
	</div>
</div>