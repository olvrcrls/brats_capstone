jQuery(document).ready(function($) {
	$('#btnSubmit').on('click', function () {
		let confirmBox = confirm("Please make sure of everything. \nProceed to printing your Transaction Voucher (E-Receipt)?");
		// alert(confirmBox);
		if ( confirmBox == true ) {
			document.finalCheckoutForm.submit();
		} else { document.finalCheckoutForm.preventDefault(); }
	});
});
