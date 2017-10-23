<?php
$update_account_info = "SELECT first_name, last_name, email, phone, address_1, address_2, city, shirt_states_id, zip, date(date_created) as date_created
	FROM shirt_customers WHERE shirt_customers_id = $shirt_customers_id";
$exec_update_account_info = @mysqli_query($link, $update_account_info);
if(!$exec_update_account_info){
	rollback('Customer account inof could not be retrieved because: '.mysqli_error($link));
}elseif(mysqli_num_rows($exec_update_account_info)==1){
	$one_record = mysqli_fetch_assoc($exec_update_account_info);
	$first_name = $one_record['first_name'];
	$last_name = $one_record['last_name'];
	$email = $one_record['email'];
	$phone = $one_record['phone'];
	$address_1 = $one_record['address_1'];
	$address_2 = $one_record['address_2'];
	$city = $one_record['city'];
	$shirt_states_id = $one_record['shirt_states_id'];
	$zip = $one_record['zip'];
	$date_created = $one_record['date_created'];
	mysqli_free_result($exec_update_account_info);
}else{
	redirect('unknown error', 'account_info.php', 2);
}

?>