<?php 
require_once '../connect.php';
$id = $_POST['id'];
$user = mysql_query("SELECT called FROM request_number WHERE id = '{$id}'") or die (mysql_error());;

while ($param = mysql_fetch_array($user)) {
	if ($param['called'] == 1) {
		mysql_query("UPDATE request_number SET called = 0 WHERE id = {$id}");
	} elseif ($param['called'] == 0) {
		mysql_query("UPDATE request_number SET called = 1 WHERE id = {$id}");
	}
}
?>