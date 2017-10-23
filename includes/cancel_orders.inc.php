<?php
mysqli_query($link, "SET AUTOCOMMIT = 0");
$select_shirts = "SELECT shirts_id, quantity from shirt_orders_shirts WHERE shirt_orders_id = $shirt_orders_id";
$exec_select_shirts = @mysqli_query($link, $select_shirts);
if(!$exec_select_shirts){
	rollback('Ordered shirts could not be retrieved becase '.mysqli_error($link));
}else{
	while($one_record = mysqli_fetch_assoc($exec_select_shirts)){
		$quantity = $one_record['quantity'];
		$shirts_id = $one_record['shirts_id'];
		$update_shirts = "UPDATE shirts set stock_quantity = (stock_quantity+$quantity) WHERE shirts_id = $shirts_id";
		$exec_update_shirts = @mysqli_query($link, $update_shirts);
		if(!$exec_select_shirts){
			rollback('Update was not successful becase '.mysqli_error($link));
		}
	}
	$delete_order = "DELETE shirt_shipping_addresses.*, shirt_billing_addresses.*, shirt_transactions.* FROM shirt_orders 
	INNER JOIN shirt_billing_addresses USING (shirt_billing_addresses_id)
	INNER JOIN shirt_shipping_addresses USING (shirt_shipping_addresses_id)
	INNER JOIN shirt_transactions USING (shirt_transactions_id)
	WHERE shirt_orders_id = $shirt_orders_id";
	$exec_delete_order = @mysqli_query($link, $delete_order);
	if(!$exec_delete_order){
		rollback('Delete was not successful becase '.mysqli_error($link));
	}else{
		mysqli_query($link, "COMMIT");
		redirect('successfully deleted...', 'view_current_orders.php', 1);
	}	
}
?>