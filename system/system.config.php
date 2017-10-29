<?php

#выключаем показ ошибок
error_reporting(E_ALL);
ini_set("display_errors", 1);

//очищаем POST-данные, если они есть
if ($_POST) clean_post_data();
	

function clean_post_data() {
        foreach($_POST as $key=>$value){
            $_POST[$key] = XSS($value);
        }
}

/* if (file_exists('db.config.php')) {

#подключаем настройки сайта
require_once 'settings.php';
} */

require_once 'lib/mail.php';

#id-пользователя (доступна после авторизации)
if (isset($_SESSION['userId'])) {
define('ID_USER', $_SESSION['userId']);
}


function plus_amount_of_ref($summa, $whom) {
	global $db;
	
	$summa = round($summa, 2);
	if (isset($_SESSION['userId']) && ($_SESSION['userId'] > 0)) {
		$q = $db->query("UPDATE users SET sum_ref = sum_ref + ?s WHERE id = ?i", $summa, $whom);
	}	
}

//проверка статуса реферальной программы (включена/выключена)
function ref_status() {
	global $db, $settingsSystem;
	
	if ($settingsSystem['ref_status'] == 'enable')
		return true;
	
	return 0;
}

//функция выдает логин того, кто привел по реферальной программе
function user_refer($id_user) {
	global $db;
	
	$select_id = $db->query("SELECT * FROM users WHERE id = ?i", $id_user);
	
	if ($db->numRows($select_id) === 1) { 
		$user = $db->fetch($select_id);
		return $user['refer'];
	} else {
		return 0;
	}
	
}

//добавление оповещения(текст оповещения, id-пользователя, которому адресовано оповещение)
function add_notification($text, $id_user = 0) {
	global $db;
	if ($id_user === 0) $id_user = $_SESSION['userId'];
	if (!empty($text) && isset($id_user)) {
		$q = $db->query("INSERT INTO history (text, whose_id) VALUES (?s, ?i)", $text, $id_user);
	}
}

//изменяем баланс пользователя (сумма(5, -150), id пользователя)
function user_balance($sum, $id_user = -1) {
	global $db;
	
	if ($id_user < 0) $id_user = $_SESSION['userId'];
	
	$q = $db->query("SELECT * FROM users WHERE id = ?i", $id_user);
	
	if ($q && $db->numRows($q) == 1) {
		$user = $db->fetch($q);
		
		if ($user['balance'] >= 0) {

			$updata = $db->query("UPDATE users SET balance = balance + ?s WHERE id = ?i", $sum, $id_user);
			
		//	$new_balance = $db->query("SELECT * FROM users WHERE id = ?i", $id_user);
		//	$user = $db->fetch($new_balance);
		
		//  if ($updata) 
			//add_notification('Ваш баланс изменился на <b>'.$sum.' руб.</b>. <br>Ваш новый баланс: <b>'.$user['balance'].' руб.</b>', $id_user);
			
			
		}
		
	}
}



#функция для "создания" странички
function page($title,$text){
	echo '<div class="r1_golov">'.$title.'</div><div class="r1">'.$text.'</div>';
}


#функция подсчета событий, которые нужно расчитать
function numEventCalc() {
	global $db;
	
	$timeNow = time();
	
	$queryCalculation = $db -> query("SELECT * FROM events WHERE timestart < $timeNow AND old = 0");
	$numCalculation = $db -> numRows($queryCalculation);
	
	return $numCalculation;	
}


#функция подсчета пользователей запросивших на ввод
function usersInput() {

global $db;

$queryIn = $db -> query("SELECT * FROM input_means WHERE confirm = 0");
$numIn = $db -> numRows($queryIn);

return $numIn;
}


#функция подсчета пользователей запросивших на вывод
function usersOutput() {

global $db;

$queryOut = $db -> query("SELECT * FROM output_means WHERE confirm = 0");
$numOut = $db -> numRows($queryOut);

return $numOut;
}


#общее кол-во пользователей в системе
function numUsers() {
	global $db;
	
	$query = $db -> query("SELECT * FROM users");
	$num = $db -> numRows($query);
	
	return $num;
}


#всего денег в системе
function allMoney() {
	global $db;

	$amount = $db -> getOne("SELECT SUM(balance) FROM users");
	
	return round($amount, 2);
}


#очистка данных. Защита от XSS.
function XSS($var) {
    $var = trim($var);
    $var = htmlspecialchars($var);
    $var = stripslashes($var);
    
    return $var;
}

#очистка числовых данных
function clear($var){
    $var = trim($var);
    $var = abs($var);
    $var = intval($var);
    
    return $var;
}

#функция для вывода ошибок
function errors($text) {
	echo '<div class="r1_golov">Ошибка</div>';
    echo '<div class="r1">';
    echo $text;    
    echo '</div>';
}

#функция для вывода фатальных ошибок (останавливает дальнейшее выпонение сценария)
function fatalError($text) {
    echo '<div class="r1_golov">Ошибка</div>';
    echo '<div class="r1">';
    echo $text;    
    echo '</div>';
    require_once 'footer.php';
    exit();
}

#вывод информации
function show($text) {
	echo '<div class="r1_golov">Информация</div>';
    echo '<div class="r1">';
    echo $text;
    echo '</div>';
}

#функция проверки email
function CheckEmail($email) {
global $db;

$match = 0;

#производим запрос в базу (поиск совпадений)
$query = $db -> query("SELECT `id` FROM `users` WHERE `email` = ?s ", $email);
if ($db -> numRows($query) > 0) 
#если нашли совпадение, то увеличиваем кол-во совпадений
$match++;


#база доменов, которые являются зеркалами
$baseEmail = array('yandex.com', 'yandex.ua', 'yandex.kz', 'yandex.by', 'ya.ru', 'yandex.ru');

#обрезаем E-mail до символа @
$emailShort = explode('@', $email);

#выбираем имя почтового ящика
$nameEmail = $emailShort[0];

#выбираем домен и зону
$domenAndZona = $emailShort[1];

#ищем домен и зону в нашей базе $baseEmail
if (in_array($domenAndZona, $baseEmail)) {
#если нашелся такой домен с зоной в базе, то проверяем,
#не регистрировался ли уже кто-то с этим email

#кол-во совпадений
$match = 0;

#перебираем базу до конца
foreach ($baseEmail as $domenZona) {

#формируем email
$newEmail = $nameEmail.'@'.$domenZona;

#производим запрос в базу (поиск совпадений)
$query = $db -> query("SELECT `id` FROM `users` WHERE `email` = ?s ", $newEmail);
if ($db -> numRows($query) > 0) 
#если нашли совпадение, то увеличиваем кол-во совпадений
$match++;

}

} 

#если уже регистрировались с этим именем почты, то выдаем false, иначе true
if ($match) 
return false;
else
return true;
}



#функция вывода ближайших события (в скобках указывается, кол-во матчей)
function forthcoming_events($count) {
        global $db;
   
   #проверяем больше ли 0 записей мы просим вывести
   $count = ($count > 0) ? $count : 1;
        
   #время сейчас
   $timeNow = time();
   
   #запрос на выборку событий, которые еще не начались
   $queryEvents = $db -> query("SELECT * FROM events WHERE timestart > ?i AND result = ?i AND old = ?i ORDER BY timestart LIMIT ?i", $timeNow, -1, 0, $count); 
   #кол-во
   $numSob = $db->numRows($queryEvents);
   #выводим события
?>
<div class="r1_golov">Ближайшие события</div>
<div class="r1">Выводится <?=$count?> ближайших события</div>
<?
if ($numSob) {
   while($event = $db -> fetch($queryEvents)) {
    
    #выбираем из базы название "раздела", в котором находится событие
   $querySection = $db -> query("SELECT * FROM sections WHERE id = ?i ", $event['section']);
   $dataSection = $db -> fetch($querySection);
    $time_st = date('Y-m-d H:i:s', $event['timestart']);
    ?>
<div class="r1_golov">
<?=$dataSection['title'] ?> <? echo $event['team1'], ' - ', $event['team2']; ?>
</div>
<div class="r1">
До начала события: <?=downcounter($time_st)?><br>
Начало: <? echo date('d-m-Y H:i', $event['timestart']), '<br>'; ?>
Коэфф.: 
<? echo '
<a href="put.on/?event='.$event['id'].'&outcome=1">[П.1 - ', $event['factor1'], ']</a> 
<a href="put.on/?event='.$event['id'].'&outcome=3">[Н.X - ', $event['factor0'], ']</a>
<a href="put.on/?event='.$event['id'].'&outcome=2">[П.2 - ', $event['factor2'], ']</a>'; 
?>

<? if (is_admin($_SESSION['userId'])) { ?>
<br>
<a href="/panel/statistic/?id=<?=$event['id']?>">[Статистика события]</a>
<? } ?>

</div>
<?
   }
   } else {
   ?>
   <div class="r1">Событий нет</div>
   <?
   }
   
}



#баланс пользователя, в аргументах передается ID-пользователя
function userBalance($id) {
    global $db;
    
    $query = $db -> query("SELECT * FROM users WHERE id = ?i", $id);
    $fetch = $db -> fetch($query);
    
    return $fetch['balance'];
}


#логин пользователя, в аргументах передается ID-пользователя
function userLogin($id) {
	global $db;
	
	$query = $db -> query("SELECT * FROM users WHERE id = ?i", $id);
	$fetch = $db -> fetch($query);
	
	return $fetch['login'];
}

#вывод всех существующих турниров
function all_tournaments() {
    global $db;
    
    ?>
    <div class="r1_golov">Все турниры</div>
	<div class="r1">
    <?
    $query = $db -> query("SELECT * FROM sections");
	$numTurnir = $db -> numRows($query);
	
	if ($numTurnir) {
    while ($tournament = $db -> fetch($query)) {
	$queryEv = $db->query("SELECT * FROM events WHERE section = ?i AND old = 0", $tournament['id']);
	$numEv = $db->numRows($queryEv);
 ?>       
        
        <?if ($numEv > 0) { ?>
		<a href="events/?section=<?=$tournament['id']; ?>">
		<img width="15px" height="15px" src="../style/default/imgs/gl3.png"> <?=$tournament['title']; ?>
        </a> [<?=$numEv;?>]<br>
		<?}?>
        
 <?       
    }
	?>
	</div>
	<?
	} else {
	?>
	<div class="r1">Турниров не найдено</div>
	</div>
	<?
	}
    
}



/**
   * Счетчик обратного отсчета
   *
   * @param mixed $date
   * @return
   */
  function downcounter($date){
      $check_time = strtotime($date) - time();
      if($check_time <= 0){
          return false;
      }

      $days = floor($check_time/86400);
      $hours = floor(($check_time%86400)/3600);
      $minutes = floor(($check_time%3600)/60);
      $seconds = $check_time%60; 

      $str = '';
      if ($days > 7) return 'Больше недели';
      if ($days >=  1) return 'Больше суток';
      if($days > 0) $str .= declension($days,array('день','дня','дней')).' ';
      if($hours > 0) {
        $str .= declension($hours,array('час','часа','часов')).' ';
      }

      if($minutes > 0) {
        $str .= declension($minutes,array('минута','минуты','минут')).' ';
        return $str;
      }

      if($seconds > 0) $str .= declension($seconds,array('секунда','секунды','секунд'));


      return $str;
  }


  /**
   * Функция склонения слов
   *
   * @param mixed $digit
   * @param mixed $expr
   * @param bool $onlyword
   * @return
   */
  function declension($digit,$expr,$onlyword=false){
      if(!is_array($expr)) $expr = array_filter(explode(' ', $expr));
      if(empty($expr[2])) $expr[2]=$expr[1];
      $i=preg_replace('/[^0-9]+/s','',$digit)%100;
      if($onlyword) $digit='';
      if($i>=5 && $i<=20) $res=$digit.' '.$expr[2];
      else
      {
          $i%=10;
          if($i==1) $res=$digit.' '.$expr[0];
          elseif($i>=2 && $i<=4) $res=$digit.' '.$expr[1];
          else $res=$digit.' '.$expr[2];
      }
      return trim($res);
  }


#функция проверки заполнения реквизитов (в аргементах ID-пользователя)
function checkDetails($id) {
    
    global $db;
    
    $queryDetails = $db -> query("SELECT * FROM users WHERE id = ?i", $id);
    $dataDetails = $db -> fetch($queryDetails);
    
    #проверяем на заполненность
    if (!$dataDetails['webmoney'] && !$dataDetails['qiwi'] && !$dataDetails['visa']) {
        fatalError('У вас не заполнено ни одного реквизита!');
    }
    
}


#новости
function view_news() {
  global $db;

  $q = $db->query("SELECT * FROM news WHERE view = ?i ORDER BY id DESC LIMIT 1", 1);
  ?>
<div class="r1_golov">
  Новости
</div>
<div class="r1">
  

  <?
  if ($db->numRows($q) > 0) {
      $new = $db->fetch($q);

      echo '<b>'.$new['new_title'].'</b> ('.date('d.m.Y', $new['new_date']).')<br>';
      echo substr($new['new_text'], 0, 151).'...<br>';
      echo '<a href="news/?view='.$new['id'].'">Читать полностью</a>';
  } else {
    echo 'Новостей нет';
  }
?>
</div>
<?  
}



#проверяем на заполненность wmr
function checkWMR($id) {
    
    global $db;
    
    $queryWMR = $db -> query("SELECT * FROM users WHERE id = ?i", $id);
    $dataWMR = $db -> fetch($queryWMR);
    
        #проверяем заполненность wmr
    if (!$dataWMR['webmoney']) 
    return false;
    else
    return true;
}


#проверяем на заполненность yandex
function checkYandex($id) {
    
    global $db;
    
    $queryWMR = $db -> query("SELECT * FROM users WHERE id = ?i", $id);
    $dataWMR = $db -> fetch($queryWMR);
    
        #проверяем заполненность yandex
    if (!$dataWMR['yandex']) 
    return false;
    else
    return true;
}


#проверяем на заполненность qiwi
function checkQIWI($id) {
    
    global $db;
    
    $queryQIWI = $db -> query("SELECT * FROM users WHERE id = ?i", $id);
    $dataQIWI = $db -> fetch($queryQIWI);
    
        #проверяем заполненность wmr
    if (!$dataQIWI['qiwi']) 
    return false;
    else
    return true;
}


#проверяем на заполненность visa
function checkVisa($id) {
    
    global $db;
    
    $queryVisa = $db -> query("SELECT * FROM users WHERE id = ?i", $id);
    $dataVisa = $db -> fetch($queryVisa);
    
        #проверяем заполненность wmr
    if (!$dataVisa['visa']) 
    return false;
    else
    return true;
}






#функция для проверки пользователя на привилегии админа
function is_admin($id) {

$id = clear($id);
global $db;

if (!$id) fatalError('Такого пользователя не существует!');

$query = $db->query("SELECT * FROM users WHERE id = ?i", $id);
$user = $db->fetch($query);

if (($user['admin'] == 1) && isset($_SESSION['userAdmin']) && !empty($_SESSION['userAdmin'])) 
return true;
else
return false;


}



#вывод модулей
function view_modules() {
global $db;
$select = $db->query("SELECT * FROM modules ORDER BY id");


while ($modules = $db->fetch($select)) {
if ($modules['active']) {
	?>
<img width="15px" height="15px" src="../style/default/imgs/<?=$modules['icon']?>"><a href="<?=$modules['link']?>"><?=$modules['name']?></a><br>
	<?
						}
}

}

#проверка модуля на активацию
function active_module($link) {
global $db;

$select = $db->query("SELECT * FROM modules WHERE link = ?s", $link);
$module = $db->fetch($select);

if ($module['active']) return true;
						   else
					   return false;
}



function genRnd($number)  
  {  
    $arr = array('a','b','c','d','e','f',  
                 'g','h','i','j','k','l',  
                 'm','n','o','p','r','s',  
                 't','u','v','x','y','z',  
                 'A','B','C','D','E','F',  
                 'G','H','I','J','K','L',  
                 'M','N','O','P','R','S',  
                 'T','U','V','X','Y','Z',  
                 '1','2','3','4','5','6',  
                 '7','8','9','0');  
    // Генерируем пароль  
    $pass = "";  
    for($i = 0; $i < $number; $i++)  
    {  
      // Вычисляем случайный индекс массива  
      $index = rand(0, count($arr) - 1);  
      $pass .= $arr[$index];  
    }  
    return $pass;  
  } 