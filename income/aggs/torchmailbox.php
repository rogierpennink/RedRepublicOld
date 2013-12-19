<?
$show = ( isset( $_POST['torchmailbox'] ) ) ? $continue : false;
?>
<div style="width: 50%; max-width: 100%;">

	<div class="title">
		<div style="margin-left: 10px;">Torch a mailbox</div>
	</div>
	<div class="content">
	<p>Torching a mailbox is a very serious crime and the damage inflicted upon your victim can lead to more than just fines and convictions... You can still go <a href="agg_crimes.php">back</a>!</p>
	<form action="agg_crimes.php" method="post">
		<table>
		<tr>
			<td style="width: 20%; text-align: left;">
				<strong>Victim:</strong>
			</td><td>
				<input type="text" class="std_input" name="victim" style="width: 130px;" />
			</td>
		</tr>
		</table><br />
		<input type="submit" class="std_input" name="torchmailbox" value="Torch Mailbox!" />
		<input type="hidden" name="submit" />
		<input type="hidden" name="agg_crime" value="torchmailbox" />
	</form>
	</div>
	
</div>