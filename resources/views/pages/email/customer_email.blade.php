<html>
<body>
<h3>
	<img 
		src='<?php echo $message->embed("./logo.png"); 
		// embedding the image of the company logo here
	?>' width='130px' height='100px'>
	<b><u>{{ $companyName }}</u></b>
</h3>
<br>
<hr>
<br>
E-Mail Transaction date: {{ date('m-d-Y') }} <br><br>
Greetings, Mr./Ms. {{ ucwords($customer_name) }} <br>
<br>
<br>
You made an online reservation transaction with a <b>Transaction Number: {{ $transaction_number }}.</b><br>
Thank you for engaging an online reservation with us. <br>
Please do note that your seats are not yet legitimately reserved for you as stated in our Terms & Agreements. <br>
Do make a down payment or installment via Payment to our bank account or directly to the terminal's cashier in order to secure your seat(s) reservation.
<br>
<br>
<br>
This e-mail has an attachment of back-up e-payment voucher for your transaction. <br>
E-Voucher is only valid for a number of days to transact (please check the Instructions). For more information & inquiries, please send an e-mail to support@brats.com <br> <br>
<br>
<br>
- {{ $companyName }} Support.
</body>
</html>