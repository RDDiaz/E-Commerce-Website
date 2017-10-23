<?php
session_start();
$title = 'Order Product';
require('./includes/mysql.inc.php');
$errors_array = array();
require('./includes/functions.inc.php');
if(isset($_SESSION['shirt_customers_id']) && isset($_SESSION['full_name'])){
	$shirt_customers_id = $_SESSION['shirt_customers_id'];
	if(isset($_POST['form_submitted'])){
		require('./includes/order_handle.inc.php');
	}
	include('./includes/header.inc.php');
	require('./includes/order.inc.php');
	include('./includes/footer.inc.php');
}else{
	redirect('You are not an authentic user', 'login.php', 1);
}
?>