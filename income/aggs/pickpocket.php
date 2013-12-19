<?
$show = ( isset( $_POST['pickpocket'] ) ) ? $continue : false;
?>
<div style="width: 50%; max-width: 100%;">

	<div class="title">
		<div style="margin-left: 10px;">Picking Pockets</div>
	</div>
	<div class="content">
	<p>You stand in a busy street, ready to snatch wallets from innocent tourists and careless locals. Do you think you can outsmart the police and make a nice profit? Remember, you can still go <a href="agg_crimes.php">back</a>!</p>
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
		<input type="submit" class="std_input" name="pickpocket" value="Pickpocket!" />
		<input type="hidden" name="submit" />
		<input type="hidden" name="agg_crime" value="pickpocket" />
	</form>
	</div>
	
</div>