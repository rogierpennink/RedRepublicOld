<?
$show = ( isset( $_POST['stalk'] ) ) ? $continue : false;
?>
<div style="width: 50%; max-width: 100%;">

	<div class="title">
		<div style="margin-left: 10px;">Stalking</div>
	</div>
	<div class="content">
	<p>Stalking is an easy way to find out that extra bit of information on that special someone. You can still go <a href="agg_crimes.php">back</a> if your having second thoughts today.</p>
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
		<input type="submit" class="std_input" name="stalk" value="Stalk" />
		<input type="hidden" name="submit" />
		<input type="hidden" name="agg_crime" value="stalking" />
	</form>
	</div>
	
</div>