<?
$login = (XSS($_POST['login_recover'])) ? XSS($_POST['login_recover']) : false;
$email = (XSS($_POST['email_recover'])) ? XSS($_POST['email_recover']) : false;

if ($email && $login) {

$q = $db->query("SELECT id FROM users WHERE login = ?s AND email = ?s", $login, $email);

if ($db->numRows($q) > 0) {
		
		$new_pass = genRnd(6);
		$new_pass_hash = md5(sha1(md5($new_pass)));
		
		$up = $db->query("UPDATE users SET password = ?s WHERE login = ?s", $new_pass_hash, $login);
		
		if ($up) 
		{
		$mail = new Mail($email);
		$mail->setFromName(NameSait); // Устанавливаем имя в обратном адресе
		
		$text = "Ваш новый пароль от учетной записи на сайте \"".NameSait."\": <br>";
		$text .= $new_pass;
		$text .= "<br>Теперь вы можете авторизоваться на сайте используя этот пароль. Не забудьте сменить его!";
			
		
		if ($mail->send($email, "Восстановление пароля", $text)) show('На указанный email выслано письмо с новым паролем.');
		else show_error('Ошибка отправки email');
		}
		
} else errors('Пользователя с таким логином/email не сушествует');



}