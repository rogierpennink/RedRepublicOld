<div style="width: 90%; margin-left: auto; margin-right: auto;">
	<div class="title">
		<div style="margin-left: 10px;">Create a new bank account</div>
	</div>
	<div class="content">
		<form action="bank.php" method="post">
		<table style="width: 100%;">
		<tr>
			<td colspan="2" style="width: 100%;"><i>Creating a new bank account is quick and easy. You will need to provide us with some information about your identity which you should enter truthfully. Note that an investigation into your credentials may be conducted. You will be notified when your account has been accepted and created.</i></td>
		</tr>

		<tr><td colspan="2" style="height: 15px;">&nbsp;</td></tr>

		<tr>
			<td style="width: 20%;">First Name:</td>
			<td><input type="text" name="fname" class="std_input" style="width: 150px;" /></td>
		</tr>

		<tr>
			<td style="width: 20%;">Last Name:</td>
			<td><input type="text" name="lname" class="std_input" style="width: 150px;" /></td>
		</tr>

		<tr>
			<td style="width: 20%;">Account Type:</td>
			<td>
				<select name="acc_type" class="std_input" style="width: 150px;">
					<option value="0">Checking Account</option>
					<option value="1">Savings Account</option>
					<option value="2">Investment Account</option>
				</select>
			</td>
		</tr>

		<tr>
			<td style="width: 20%;">Initial Deposit: *</td>
			<td><input type="text" name="deposit" class="std_input" style="width: 100px;" /></td>
		</tr>
		
		<tr><td colspan="2" style="height: 15px;">&nbsp;</td></tr>

		<tr>
			<td style="width: 100%;" colspan="2"><input type="submit" value="Request Account" class="std_input" name="newacc" /></td>
		</tr>

		<tr><td colspan="2" style="height: 15px;">&nbsp;</td></tr>

		<tr>
			<td colspan="2" style="width: 100%;"><i>* Please note that your initial deposit may not be lower than $5000.</i></td>
		</tr>
		</table>
		</form>
	</div>
</div>