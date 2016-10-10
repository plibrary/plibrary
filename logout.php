<?php 
	session_destroy();
	unset($_COOKIE['login']);
	setcookie('login', '', time() - 3600);
	header("Location: index.php");
	
?>