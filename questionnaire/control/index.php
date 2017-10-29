<?
#проверим, активирован ли модуль
if (!active_module(dirname($_SERVER["SCRIPT_NAME"]))) fatalError('Модуль отключен');

#показ формы - вкл.
$formActive = true;

#запросим уже имеющиеся данные
$queryDataUser = $db -> query("SELECT * FROM users WHERE id = ?i", $_SESSION['userId']);
#обрабатываем данные
$data = $db -> fetch($queryDataUser);

if (isset($_POST['save'])) {

	#показ формы - выкл.
	$formActive = false;
	
	#принимаем и обрабатываем wmr-кошелек
	$wmr = XSS($_POST['wmr']);
	
	#принимаем и обрабатываем qiwi-кошелек
	$qiwi = XSS($_POST['qiwi']);
	
	#принимаем и обрабатываем карту VISA
	$visa = XSS($_POST['visa']);

	#принимаем яндекс
	$yandex = XSS($_POST['yandex']);

	#старый пароль
	$old_password = XSS($_POST['old_password']);

	#новый пароль
	$new_password = XSS($_POST['new_password']);


	#если введен старый и новый пароль
	if ($old_password && $new_password) {
			#hash старого пароля
			$old_password = md5(sha1(md5($old_password)));

			#hash нового пароля
			$new_password = md5(sha1(md5($new_password)));

		$q = $db->query("SELECT * FROM users WHERE id = ?i AND password = ?s", $_SESSION['userId'], $old_password);
		
		if ($db->numRows($q) > 0) {
			$up = $db->query("UPDATE users SET password = ?s WHERE id = ?i", $new_password, $_SESSION['userId']);

			if ($up) show('Пароль успешно изменен!');

		} else {
			errors('Старый пароль введен неверно!');
		}
	}
	
	#если заполнен хотя бы 1 реквизит, то обновляем данные
	if ($wmr || $qiwi || $visa || $yandex) {
	
		$updata = $db -> query("UPDATE users SET webmoney = ?s, qiwi = ?s, visa = ?i, yandex = ?s WHERE id = ?i", $wmr, $qiwi, $visa, $yandex, $_SESSION['userId']);
		
		if ($updata) show('Реквизиты успешно сохранены');
		else
		errors('Ошибка сохранения реквизитов');
	
	#если не заполнен ни один реквизит, то выдаем ошибку
	} else fatalError('Заполните хотя бы один реквизит!');
}