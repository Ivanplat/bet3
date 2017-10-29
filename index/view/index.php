<head>
	<link rel="stylesheet" href="../../css/mainstyle.css">
</head>



<?php

/*
File: bukmeker/index/view/index.php
Файл отвечает за отображение личного кабинета пользователя
*/


#проверяем, авторизован ли пользователь
if (!isset($_SESSION['userId']) || !$_SESSION['userId']) 

#если нет, то закрываем доступ
fatalError('Доступ закрыт! Авторизуйтесь!<br />
                    <a href="/login">Авторизоваться</a>');
                    
#если авторизован, продолжаем выполнение скрипта
else {

#если пользователь является администратором, то выводим ссылку на панель управления
if (is_admin($_SESSION['userId'])) {
?>
<div class="r1_golov">Панель управления</div>
<div class="r1">
    <img width="15px" height="15px" src="../style/default/imgs/admin.png"><a href="../panel">Войти</a>
</div>
<?
}
?>
<?  #если есть непрочитанные оповещения
	if ($db->numRows($s_notif) > 0) {
?>	
		<div class="r1_golov">Новые оповещения</div>
		<div class="r1">
			Есть непрочитанные оповещения!<br>
			<a href="notifications/">Посмотреть</a>
		</div>
<?	
	}
?>
<div id="upper">
	
</div>
<div class="lk">Личный кабинет</div>

<div class="modules">
	
<!--Вашем счету: <? echo round(userBalance($_SESSION['userId']), 2); ?>  руб.<br> -->



<?
#выводим все активные модули
view_modules();
?>
</div>

<?
#показываем новости
view_news();
?>

<? 
#выводим 3 ближайших события
forthcoming_events(3); 

#выводим все турниры
all_tournaments();
}