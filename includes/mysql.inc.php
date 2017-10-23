<?php
DEFINE('HOST', 'localhost');
DEFINE('USER', 'rddiaz');
DEFINE('PASS', 'Franklin02');
DEFINE('DB', 'rddiaz');

$link = @mysqli_connect(HOST, USER, PASS, DB) or die('The following error occurred: '.mysqli_connect_error());
mysqli_set_charset($link, 'utf8');
?>