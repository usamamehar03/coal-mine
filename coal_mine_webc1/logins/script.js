
$(function() {

	$("#username_error_message").hide();
	$("#password_error_message").hide();

	var error_username = false;
	var error_password = false;

	$("#inputname").focusout(function() {

		check_username();
		
	});

	$("#inputpass").focusout(function() {

		check_password();
		
	});

	function check_username() {
	
		var username_length = $("#inputname").val().length;
		
		if(username_length ==0) {
			$("#username_error_message").html("fill your name ");
			$("#username_error_message").show();
			error_username = true;
		} else {
			$("#username_error_message").hide();
		}
	
	}

	function check_password() {
	
		var password_length = $("#inputpass").val().length;
		
		if(password_length ==0) {
			$("#password_error_message").html("fill your pasword");
			$("#password_error_message").show();
			error_password = true;
		} else {
			$("#password_error_message").hide();
		}
	
	}

	// function check_retype_password() {
	
	// 	var password = $("#form_password").val();
	// 	var retype_password = $("#form_retype_password").val();
		
	// 	if(password !=  retype_password) {
	// 		$("#retype_password_error_message").html("Passwords don't match");
	// 		$("#retype_password_error_message").show();
	// 		error_retype_password = true;
	// 	} else {
	// 		$("#retype_password_error_message").hide();
	// 	}
	
	// }

	// function check_email() {

	// 	var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
	
	// 	if(pattern.test($("#form_email").val())) {
	// 		$("#email_error_message").hide();
	// 	} else {
	// 		$("#email_error_message").html("Invalid email address");
	// 		$("#email_error_message").show();
	// 		error_email = true;
	// 	}
	
	// }

	$("#logform").submit(function() {
											
		error_username = false;
		error_password = false;
											
		check_username();
		check_password();

		if(error_username == false && error_password == false) {
			return true;
		} else {
			return false;	
		}

	});

});