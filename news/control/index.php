<?
#проверим, активирован ли модуль
//if (!active_module(dirname($_SERVER["SCRIPT_NAME"]))) fatalError('Модуль отключен');

if (!isset($_GET['view'])) {

        #выбираем все события, которые есть в этом разделе
        $queryEvent = $db -> query("SELECT * FROM news WHERE view = ?i", 1);
        
        #смотрим сколько их там
        $numEvent = $db -> numRows($queryEvent);
        
		#запускаем класс навигации
		$countViewEvents = 3;
		$navigation = new Navigator($numEvent, $countViewEvents, ''); 
        
		#выбираем повторно все события с учетом навигации, которые есть в этом разделе
        $queryEvent = $db -> query("SELECT * FROM news WHERE view = ?i ORDER BY id LIMIT {$navigation->start()},". $countViewEvents, 1);
		
     
} else {
    $id = intval(abs($_GET['view']));

    if ($id) {
        $sel = $db->query("SELECT * FROM news WHERE view = ?i AND id = ?i", 1, $id);
        if ($db->numRows($sel)) {
            $new = $db->fetch($sel);
            ?>
            <div class="r1_golov"><?=$new['new_title'];?></div>
            <div class="r1"><?=$new['new_text']?></div>

            <div class="r1_golov">Навигация</div>
            <div class="r1">
            <a href="/bukmeker/news/">Все новости</a>
            </div>
            <?
        } else {
            errors('Такой новости не существует!');
        }
    } else {
        errors('Такой новости не существует');
    }
}