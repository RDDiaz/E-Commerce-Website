<?php
/**************** Regular Expression Patterns ******************************/
$address_1_pattern = '/^[1-9][0-9]*[ ,]?[a-zA-Z0-9_.# ]+$/';
$address_2_pattern = '/^([1-9][0-9]*[ ,]?[a-zA-Z0-9_.# ]+)?$/';
$city_pattern = '/^[a-zA-Z][a-zA-Z 0-9]{2,49}$/';
$shirt_states_id_pattern = '/^[1-5][0-9]?$/';
$zip_pattern = '/^[0-9]{5}([ -]\d{4})?$/';
$credit_type_pattern = '/^[a-zA-Z]{2,20}$/';
$credit_no_pattern = '/^[0-9]{16,24}$/';
$shirt_carriers_methods_id_pattern = '/^[0-9]{1,3}$/';

/*************** Call to validate_input() function to validate form data ******************/
$address_1 = validate_input('address_1', $address_1_pattern, $_POST['address_1']);
$address_2 = validate_input('address_2', $address_2_pattern, $_POST['address_2']);
$city = validate_input('city', $city_pattern, $_POST['city']);
$shirt_states_id = validate_input('shirt_states_id', $shirt_states_id_pattern, $_POST['shirt_states_id']);
$zip = validate_input('zip', $zip_pattern, $_POST['zip']);
$credit_type = validate_input('credit_type', $credit_type_pattern, $_POST['credit_type']);
$credit_no = validate_input('credit_no', $credit_no_pattern, $_POST['credit_no']);
$credit_no_four = substr($credit_no, -4);
$shirt_carriers_methods_id = validate_input('shirt_carriers_methods_id', $shirt_carriers_methods_id_pattern, $_POST['shirt_carriers_methods_id']);

/************************ Shipping Fee and Quantity Handled ****************************/
$select_shipping_fee = "SELECT fee from shirt_carriers_methods WHERE shirt_carriers_methods_id = $shirt_carriers_methods_id";
$exec_select_shipping_fee = @mysqli_query($link, $select_shipping_fee);
if(!$exec_select_shipping_fee){
	rollback("The following error occurred when retrieving shipping fee: ".mysqli_error($link));
}else{
	$one_record = mysqli_fetch_assoc($exec_select_shipping_fee);
	$fee = $one_record['fee'];
}

if(!empty($_POST['quantity'])&&is_array($_POST['quantity'])){
	$quantity = $_POST['quantity'];
	foreach($quantity as $shirts_id=>$arr){
		foreach($arr as $price => $value){
			$order_total += ($price * $value);
			$shipping_fee += ($fee * $value);
		}
		$amount_charged = $order_total + $shipping_fee;
	}
	if(!is_numeric($amount_charged) || $amount_charged == 0){
		$errors_array['quantity'] = "Invalid quantity";
	}
}else{
	$errors_array['quantity'] = "Please enter a quantity for at least a product type!";
}

/********************** Order Records are Inserted into Appropriate Tables ********************/
if(count($errors_array)==0){
	mysqli_query($link, 'AUTOCOMMIT = 0');
	$insert_shipping_addresses = "INSERT INTO shirt_shipping_addresses (address_1, address_2, city, shirt_states_id, zip, date_created) 
		VALUES ('$address_1', '$address_2', '$city', $shirt_states_id, '$zip', now())";
	$exec_insert_shipping_addresses = @mysqli_query($link, $insert_shipping_addresses);
	if(!$exec_insert_shipping_addresses){
		rollback("The following error occurred when inserting into shirt_shipping_addresses: ".mysqli_error($link));
	}else{
		$shirt_shipping_addresses_id = mysqli_insert_id($link);
		$insert_billing_addresses = "INSERT INTO shirt_billing_addresses (address_1, address_2, city, shirt_states_id, zip, date_created) 
		VALUES ('$address_1', '$address_2', '$city', $shirt_states_id, '$zip', now())";
		$exec_insert_billing_addresses = @mysqli_query($link, $insert_billing_addresses);
		if(!$exec_insert_billing_addresses){
			rollback("The following error occurred when inserting into shirt_billing_addresses: ".mysqli_error($link));
		}else{
			$shirt_billing_addresses_id = mysqli_insert_id($link);
			$insert_transactions = "INSERT into shirt_transactions (amount_charged, type, response_code, response_reason, response_text, date_created) VALUES ($amount_charged, 'credit', 'OK', '', 'Confirmed', now())";
			$exec_insert_transactions = @mysqli_query($link, $insert_transactions);
			if(!$exec_insert_transactions){
				rollback("The following error occurred when inserting into shirt_transactions: ".mysqli_error($link));
			}else{
				$shirt_transactions_id = mysqli_insert_id($link);
				$insert_orders = "INSERT into shirt_orders (shirt_customers_id, shirt_transactions_id, shirt_shipping_addresses_id, shirt_carriers_methods_id, shirt_billing_addresses_id, credit_no, credit_type, order_total, shipping_fee, order_date) VALUES($shirt_customers_id, $shirt_transactions_id, $shirt_shipping_addresses_id, $shirt_carriers_methods_id, $shirt_billing_addresses_id, '$credit_no_four', '$credit_type', $order_total, $shipping_fee, now())";
				$exec_insert_orders = @mysqli_query($link, $insert_orders);
				if(!$exec_insert_orders){
					rollback("The following error occurred when inserting into shirt_orders: ".mysqli_error($link));
				}else{
					$shirt_orders_id = mysqli_insert_id($link);
					foreach($quantity as $shirts_id=>$arr){
						foreach($arr as $price => $value){
							if(!empty($value)){
								$type_total = $price * $value;
								$insert_orders_shirts = "INSERT into shirt_orders_shirts (shirt_orders_id, shirts_id, quantity, price) VALUES ($shirt_orders_id, $shirts_id, $value, $type_total)";
								$exec_insert_orders_shirts = @mysqli_query($link, $insert_orders_shirts);
								if(!$exec_insert_orders_shirts){
									rollback('The following error ocurred when inserting into shirt orders'.mysqli_error($link));
								}else{
									$select_stock_quantity = "SELECT stock_quantity from shirts where shirts_id = $shirts_id";
									$exec_select_stock_quantity = @mysqli_query($link, $select_stock_quantity);
									if(!$exec_select_stock_quantity){
										rollback('The following error ocurred when selecting stock quantity'.mysqli_error($link));
									}else{
										$one_record = mysqli_fetch_assoc($exec_select_stock_quantity);
										$stock_quantity = $one_record['stock_quantity'];
										$updated_quantity = $stock_quantity - $value;
										$update_shirts = "UPDATE shirts SET stock_quantity = $updated_quantity WHERE shirts_id = $shirts_id";
										$exec_update_shirts = @mysqli_query($link, $update_shirts);
										if(!$exec_update_shirts){
											rollback('The following error ocurred when updating stock quantity'.mysqli_error($link));
										}
									}
								}
							}
						}
					}
					mysqli_query($link, 'COMMIT');
					redirect('Your orders were placed...You are now being redirected to order page ...', 'view_previous_orders.php', 1);
				}
			}
		}
	}
}
?>