<?php
session_start();
$title = 'Prior Orders';
require('./includes/mysql.inc.php');
$errors_array = array();
require('./includes/functions.inc.php');
if(isset($_SESSION['shirt_customers_id']) && isset($_SESSION['full_name'])){
	$shirt_customers_id = $_SESSION['shirt_customers_id'];
	include('./includes/header.inc.php');
	require('./includes/view_previous_orders.inc.php');
	include('./includes/footer.inc.php');
}else{
	redirect('You are nto an authentic user', 'login.php', 1);
}
?>