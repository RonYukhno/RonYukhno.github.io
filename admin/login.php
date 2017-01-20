<?php

	session_start();

	require_once '../connect.php';

	$user = mysql_query("SELECT login, password FROM users WHERE login='{$_POST['login']}'")
	or die (mysql_error());

	$psw = "";
	while ($row = mysql_fetch_array($user)) {
		$psw = $row['password'];
	}

	if (password_verify($_POST['password'], $psw)) {
		$_SESSION['logged_user'] = $_POST['login'];
		header("Location: /admin");
	} else {
		header("Location: /admin");
	}

?>