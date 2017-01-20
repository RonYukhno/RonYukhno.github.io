<?php session_start(); ?>

<!DOCTYPE>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Admin panel</title>
	<link rel="stylesheet" href="../style/bootstrap.css">
	<style>
		p {
			padding: 0;
			margin: 0;
		}
		#status {
			display: inline-block;
			padding-left: 20px;
		}
		.green {color: green;}
		.red {color: red;}
	</style>
</head>
<body>

	<?php 
	if (isset($_SESSION['logged_user'])) : 
		require_once '../connect.php';	
	$rows = mysql_query("SELECT * FROM request_number")
	or die (mysql_error()); ?>

	<table class="table table-hover">
		<thead>
			<tr>
				<th>Имя</th>
				<th>Телефон</th>
				<th>Дата по записи</th>
				<th>Позвонили?</th>
			</tr>
		</thead>
		<tbody>

			<!-- формирую таблицу и значения чекбоксов из бд -->
			<?php while ($row = mysql_fetch_array($rows)) {  ?>
			<tr id="<?php echo $row['id']?>">
				<td><?php echo "<p>{$row['name']}</p>"; ?></td>
				<td><?php echo "<p>{$row['phone']}</p>"; ?></td>
				<td><?php echo "<p>{$row['date']}</p>"; ?></td>
				<td><input type="checkbox" id="called" onchange="chk_call(this)" <?php if ($row['called'] == 1) echo 'checked'; ?> ><p id="status"></p></td>
			</tr>
			<?php } ?>

		</tbody>
	</table>
	<a href="logout.php">Logout</a>

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script type="text/javascript">
		function chk_call(called){
			var tr_parent = called.parentNode.parentNode;
			var id = tr_parent.getAttribute('id');
			$.ajax({
				url: "request.php",
				type: "POST",
				data: {id:id},
				success: function() {
						$(tr_parent).find('#status')
						.text("Готово")
						.addClass("green")
						.show(200)
						.delay(2000)
						.hide(200)
				},
				statusCode: {
					404: function() {
						$(tr_parent).find('#status')
						.text("Ошибка 404")
						.addClass("red")
						.show(200)
						.delay(2000)
						.hide(200)
					}
				}
			})
		};
	</script>

<?php else : ?>
	<div align="center">
		Вы не авторизованы <br>
		<form action="login.php" method="POST">
				<p><strong>Login</strong></p>
				<input required type="text" name="login" value="<?php echo @$data['login']; ?>">
				<p><strong>Password</strong></p>
				<input required type="password" name="password">
				<br>
				<button type="submit" name="do_login">Login</button>		
		</form>
	</div>
<?php endif; ?>

</body>
</html>