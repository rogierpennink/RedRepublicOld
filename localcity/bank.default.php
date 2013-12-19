<div style="width: 90%; margin-left: auto; margin-right: auto;">
	<div class="title">
		<div style="margin-left: 10px;">Our Services</div>
	</div>
	<div class="content">
		<p>
		<a href="bank.php?act=newacc">Open a new account with us</a><br />
		Opening a new account with us is quick and easy. Provided that you haven't reached your maximum account limit, which is three, you can open a new checking account, a savings account or an investment account. Information about these accounts can be found at our helpdesk.
		</p>

		<p>
		<a href="bank.php?act=overview">Accounts Overview</a><br />
		Use this option to see an overview of all transactions made from and to all of your bank accounts. Your bank accounts overview is basically your financial center. From the overview, links are provided to all our services.
		</p>

		<p>
		<a href="bank.php?act=deposit">Deposit funds</a><br />
		Depositing funds means transferring cash to one of your bank accounts. We actively encourage depositing funds since, depending on the target account, you may receive rent. Also, keeping your funds at our bank prevents them from being robbed from you.
		</p>

		<p>
		<a href="bank.php?act=withdraw">Withdraw funds</a><br />
		You can only withdraw funds directly from a checking account, as opposed to a savings account or an investment account. Our services ensure that you can withdraw funds from anywhere in the world, although you might have to pay a small fee when withdrawing abroad.
		</p>

		<p>
		<a href="bank.php?act=transfer">Transfer funds</a><br />
		Transferring funds to any bank account from the Bank of <?=$char->location;?> is free of charge if your account has been created with us as well. Transferring from savings or investment accounts is limited to accounts of which you are the owner; in other words, you cannot use savings or investment accounts to transfer funds to other people. 
		</p>

		<p>
		<a href="bank.php?act=loans">Request a Loan</a><br />
		Requesting a loan from the Bank of <?=$char->location;?> is easy. There are only two conditions: you can't request loans from banks other than the one in your homecity, and your loan can't exceed a certain amount of money, which is determined by the amount of trust you've gained with the bank. This bank currently aims for a loanrate of <?=( $banksettings['loanrate'] / 100 );?>% and requires to be paid back in <?=( $banksettings['loan_period'] );?> day(s). Note that individual employees may not always follow these guidelines... 
		</p>

		<p>
		<a href="bank.php?act=helpdesk">Visit our helpdesk</a><br />
		If not everything is clear to you, you may want to visit our helpdesk. It is open at all times and can provide you with basic information about our services.
	</div>
</div>