<?php
session_start();

#путь к скрипту
define('DIR', $_SERVER['DOCUMENT_ROOT']);

#подключаем библиотеку для работы с БД
include_once 'lib/mysqli.class.php';

#подключение библиотеки постраничной навигации
include_once 'lib/navigation.php';


#указываем данные от БД (базы данных)
$DB_HOST='localhost'; //сервер, обычно "localhost"
$DB_USER='root'; //имя пользователя БД
$DB_PASS=''; //пароль
$DB_BASE='bets'; //имя БД

#устанавливаем соединение
$db = new SafeMySQL(array('user' => $DB_USER, 'pass' => $DB_PASS,'db' => $DB_BASE, 'charset' => 'utf8'));

$settQ = $db->query("SELECT * FROM settings");
$settingsSystem = $db->fetch($settQ);

if (file_exists(DIR.'/system/settings.php')) {
#подключаем системные файлы
require_once DIR.'/system/settings.php';
}

