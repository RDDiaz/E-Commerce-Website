<?php

$first_name_pattern = '/^[a-zA-Z]{2,20}$/';
$last_name_pattern = '/^[a-zA-Z]{0,20}$/';
$email_pattern = '/^[a-zA-Z_][a-zA-Z0-9_.\-]+@[a-zA-Z]{2,}\.(com|org|edu|mil|biz)(\.[a-zA-Z]{2,3})?$/';
$phone_pattern = '/^\(?\d{3}[ )\-]{0,2}\d{3}[\- ]?\d{4}$/';
$password_pattern[0] = '/^[0-9a-zA-Z_!@#\$%\^&*().,;:]{6,}$/';
$password_pattern[1] = '/\d+/';
$password_pattern[2] = '/[A-Z]+/';
$address_1_pattern = '/^[1-9][0-9]*[ ,]?[a-zA-Z0-9_.# ]+$/';
$address_2_pattern = '/^([1-9][0-9]*[ ,]?[a-zA-Z0-9_.# ]+)?$/';
$city_pattern = '/^[a-zA-Z][a-zA-Z 0-9]{2,49}$/';
$shirt_states_id_pattern = '/^[1-5][0-9]?$/';
$zip_pattern = '/^[0-9]{5}([ -]\d{4})?$/';
$date_created_pattern = '/^\d{4}[\-\/.][0-1][0-9][\-\/.][0-3][0-9]$/';

$first_name = validate_input('first_name', $first_name_pattern, $_POST['first_name']);
$last_name = validate_input('last_name', $last_name_pattern, $_POST['last_name']);
$email = validate_input('email', $email_pattern, $_POST['email']);
$phone = validate_input('phone', $phone_pattern, $_POST['phone']);
$password = validate_input('password', $password_pattern, $_POST['password']);
$address_1 = validate_input('address_1', $address_1_pattern, $_POST['address_1']);
$address_2 = validate_input('address_2', $address_2_pattern, $_POST['address_2']);
$city = validate_input('city', $city_pattern, $_POST['city']);
$shirt_states_id = validate_input('shirt_states_id', $shirt_states_id_pattern, $_POST['shirt_states_id']);
$zip = validate_input('zip', $zip_pattern, $_POST['zip']);
$date_created = validate_input('date_created', $date_created_pattern, $_POST['date_created']);

if(count($errors_array)==0){
	mysqli_query($link, 'AUTOCOMMIT = 0');
	$insert_into_shirt_customers = "INSERT INTO shirt_customers (first_name, last_name, email, phone, password, address_1, address_2, city, shirt_states_id, zip, date_created) VALUES
	('$first_name', '$last_name', '$email', '$phone', '".password_hash($password, PASSWORD_BCRYPT)."', '$address_1', '$address_2', '$city', $shirt_states_id, '$zip', '$date_created')";
	$exec_insert_into_shirt_customers = @mysqli_query($link, $insert_into_shirt_customers);
	if(!$exec_insert_into_shirt_customers){
		rollback("The following error occurred when inserting into shirt_customers: ".mysqli_error($link));
	}else{
		mysqli_query($link, 'COMMIT');
		redirect('You are successfully registered.. You are now being redirected to login page..', 'login.php', 2);
	}
}
?>