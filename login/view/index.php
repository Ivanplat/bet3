<? if (!isset($_POST['captcha'])) { ?>


<? if (isset($_GET['recover_password'])) { ?>
<div class="r1_golov">Восстановление пароля</div>
<div class="r1">
<form action="?recover_password" method="post">
Ваш логин в системе:<br />
<input type="text" name="login_recover" required><br />

Ваш email в системе:<br />
<input type="email" name="email_recover" required><br />

<img src="../captcha/captcha.php" width="170px" height="50px"><br />
Символы с картинки:<br />
<input type="text" name="captcha"><br>

<input type="submit" name="autOn" value="Войти">

</form>

<? } else { ?>
<div class="r1_golov">Авторизация</div>
<div class="r1">
<form action="" method="post">

Логин:<br />
<input type="text" name="login" value="<? echo $log = (isset($_SESSION['login'])) ? $_SESSION['login'] : ''; ?>"><br />

Пароль: <a href="?recover_password">[Забыли?]</a><br />
<input type="password" name="password"><br />

<img src="../captcha/captcha.php" width="170px" height="50px"><br />
Символы с картинки:<br />
<input type="text" name="captcha"><br>

<input type="submit" name="autOn" value="Войти">

</form>
<? }
} ?>
</div>