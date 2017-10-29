<?
#проверим, активирован ли модуль
if (!active_module(dirname($_SERVER["SCRIPT_NAME"]))) fatalError('Модуль отключен');

        #выбираем все события, которые есть в этом разделе
        $queryEvent = $db -> query("SELECT * FROM history WHERE whose_id = ?i", $_SESSION['userId']);
        
        #смотрим сколько их там
        $numEvent = $db -> numRows($queryEvent);
        
		#запускаем класс навигации
		$countViewEvents = 5;
		$navigation = new Navigator($numEvent, $countViewEvents, ''); 
        
		#выбираем повторно все события с учетом навигации, которые есть в этом разделе
        $queryEvent = $db -> query("SELECT * FROM history WHERE whose_id = ?i ORDER BY id DESC LIMIT {$navigation->start()},". $countViewEvents, $_SESSION['userId']);
