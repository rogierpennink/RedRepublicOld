<?
$show = ( isset( $_POST['carbomb'] ) ) ? $continue : false;
?>
<div style="width: 50%; max-width: 100%;">

	<div class="title">
		<div style="margin-left: 10px;">Car Bombing</div>
	</div>
	<div class="content">
	<p>There are several reasons why someone would want to bomb a car, to stop someone from taveling, trafficing, or racing. Whatever your reason, a small amount of C4 should do the trick.</p>
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
		<input type="submit" class="std_input" name="carbomb" value="Place Bomb" />
		<input type="hidden" name="submit" />
		<input type="hidden" name="agg_crime" value="carbomb" />
	</form>
	</div>
	
</div>