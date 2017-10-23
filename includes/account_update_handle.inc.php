<?php

//mysqli_real_escape_string($link, $data);

if(!empty($_POST['first_name'])&&is_string($_POST['first_name'])){
	$first_name = htmlspecialchars(add_slashes($_POST['first_name']));
}else{
	$errors_array['first_name'] = "Please enter a valid first name!";
}

if(!empty($_POST['last_name'])&&is_string($_POST['last_name'])){
	$last_name = htmlspecialchars(add_slashes($_POST['last_name']));
}else{
	$errors_array['last_name'] = "Please enter a valid last name!";
}

if(!empty($_POST['email'])&&filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	$email = htmlspecialchars(add_slashes($_POST['email']));
}else{
	$errors_array['email'] = "Please enter a valid email!";
}

if(!empty($_POST['phone'])){
	$phone = htmlspecialchars(add_slashes($_POST['phone']));
}else{
	$errors_array['phone'] = "Please enter a valid phone!";
}

if(!empty($_POST['address_1'])){
	$address_1 = htmlspecialchars(add_slashes($_POST['address_1']));
}else{
	$errors_array['address_1'] = "Please enter a valid home address!";
}

if(!empty($_POST['address_2'])){
	$address_2 = htmlspecialchars(add_slashes($_POST['address_2']));
}else{
	$address_2 = null;
}

if(!empty($_POST['city'])){
	$city = htmlspecialchars(add_slashes($_POST['city']));
}else{
	$errors_array['city'] = "Please enter a valid City!";
}

if(isset($_POST['shirt_states_id'])){
	$shirt_states_id = $_POST['shirt_states_id'];
}else{
	$errors_array['shirt_states_id'] = "Please pick a state!";
}

if(!empty($_POST['zip'])){
	$zip = htmlspecialchars(add_slashes($_POST['zip']));
}else{
	$errors_array['zip'] = "Please enter a valid zip!";
}

if(!empty($_POST['date_created'])){
	$date_created = htmlspecialchars(add_slashes($_POST['date_created']));
}else{
	$errors_array['date_created'] = "Please enter a valid date!";
}

if(count($errors_array)==0){
	mysqli_query($link, 'AUTOCOMMIT = 0');
	$update_shirt_customers = "UPDATE shirt_customers 
		SET 
		first_name = '$first_name',
		last_name = '$last_name',
		email = '$email',
		phone = '$phone',
		address_1 = '$address_1',
		address_2 = '$address_2',
		city = '$city',
		shirt_states_id = '$shirt_states_id',
		zip = '$zip',
		date_created = '$date_created'
		WHERE shirt_customers_id = $shirt_customers_id";
	$exec_update_shirt_customers = @mysqli_query($link, $update_shirt_customers);
	if(mysqli_affected_rows($link)==0){
		mysqli_query('COMMIT');
		redirect('No Account Updated', 'account_info.php', 2);
	}elseif(mysqli_affected_rows($link)==1){
		mysqli_query($link, 'COMMIT');
		redirect('Account Updated. being redirected...', 'account_info.php', 2);
	}else{
		rollback('The following error occurred.'.mysqli_error($link));
	}
}else{
	include('./includes/header.inc.php');
	require('./includes/account_update.inc.php');
}

?>