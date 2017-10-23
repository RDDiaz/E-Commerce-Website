<?php
session_start();
$title = 'Logout';
require('./includes/mysql.inc.php');
$errors_array = array();
require('./includes/functions.inc.php');

if(isset($_SESSION['shirt_customers_id']) && isset($_SESSION['full_name'])){
	//setcookie('shirt_customers_id', '', time()-5, '/', '', 0, 1);
	//setcookie('full_name', '', time()-5, '/', '', 0, 1);
	unset($_SESSION['shirt_customers_id']);
	unset($_SESSION['full_name']);
	$_SESSION = array();
	session_destroy();
	setcookie('PHPSESSID', '', time()-5, '/', '', 0, 0);
	redirect('You are NOW logget out', 'login.php', 1);
}else{
	redirect('You are already logget out', 'login.php', 1);
}
?>