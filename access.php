<?php
require_once '../system/configurate.php';


if ($_POST) {
	$login    = $_POST['login_admin'];
	$password = $_POST['password_admin'];
	$password = sha1($password);
	
	
	$q = $db->query("SELECT * FROM settings WHERE login_admin = ?s AND password_admin = ?s", $login, $password);
	$isset_user = $db->numRows($q);
	
if ($isset_user) {
	$_SESSION['admin_access'] = TRUE;
	$_SESSION['login_admin']  = $login;
	echo 'Успешная авторизация</br><a href="admin.php">Перейти к управлению</a>';
} else {
	exit('Неверный пароль или логин!<br><a href="access.php">Повторить</a>');
}
	
} else {
	?>
	<form action="access.php" method="post">
	Login:<br>
	<input type="text" name="login_admin"><br>
	Password:<br>
	<input type="password" name="password_admin"><br>
	<input type="submit" name="go" value="Войти">
	</form>
	<?
}
?>