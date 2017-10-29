<?php

#выбираем все матчи, которые уже прошли.
$timeNow = time();
$queryCalculation = $db -> query("SELECT * FROM events WHERE timestart < $timeNow AND old = 0");
$numCalculation = $db -> numRows($queryCalculation);

if (isset($_POST['calculate'])) {
    
    #ID-события, которое расчитываем
    $idEvent = clear($_GET['id']);
    
    #обрабатываем данные
    $_POST['winner'] = clear($_POST['winner']);
    
    #запоминаем победителя (возможно и ничья)
    $winner = (!empty($_POST['winner'])) ? $_POST['winner'] : false;
    
    if ($winner) {
        
        #обновим данные в таблице events
        $update = $db -> query("UPDATE events SET result = ?i, old = 1 WHERE id = ?i", $winner, $idEvent);
		
		#выберем событие
		$select_event = $db->query("SELECT * FROM events WHERE id = ?i", $idEvent);
		$data_event   = $db->fetch($select_event);
        
        #выбираем всех, кто поставил на это событие и оказался победителем
        $querySelectBet = $db -> query("SELECT * FROM betting WHERE idsob = ?i AND onwhom = ?i", $idEvent, $winner);
        
        #посчитаем, сколько победителей
        $numBetWin = $db -> numRows($querySelectBet);
        
        
        #если победители есть, то начисляем им деньги
        if ($numBetWin > 0) {
        
        #заведем переменную, которая будет хранить кол-во выплаченных денег победителям по данной ставке
        $amountWin = 0;
        
        #перебирая всех победителей, начисляем им на баланс
        while ($winBet = $db -> fetch($querySelectBet)) {
            
            
            #запоминаем сколько выиграл пользователь
            $how = $winBet['howwin'];
            
            #увеличиваем кол-во выплаченных денег
            $amountWin += $how;
            
            #запоминаем id победителя
            $who = $winBet['who'];
            
            #обновляем баланс
            $updateBalance = $db -> query("UPDATE users SET balance = balance + ?s WHERE id = ?i", $how, $who);
			
			if ($updateBalance) {
					add_notification('Ваша ставка на событие <b>"'.$data_event['team1'].' - '.$data_event['team2'].'"</b> сыграла! На Ваш внутренний счет начислено '.$how.' руб.!', $who);
					//id кому начислить
					$refer = user_refer($who);
					//если есть тот, кто привел
					if ($refer > 0) {
						$summa = $how * ($settingsSystem['ref_win']/100);
						$summa = round($summa, 2);
						user_balance($summa, $refer);
						add_notification('По реферальной программе Вам зачислено '.$summa.' руб. от пользователя '.userLogin($who), $refer);
						plus_amount_of_ref($summa, $who);
					}
				}
            
        }
        
            #показываем что все успешно расчиталось и выводим сумму всех выигрышей
            show('Ставка успешно расчитана!<br />Сумма выплат составила: '.$amountWin.' руб.');
			?>
			<script type="text/javascript">
  setTimeout('location.replace("/panel/calculation/")', 3000);
			</script>
			<?
        
        } else {
		
		errors('Победителей нет.');
		?>
       	<script type="text/javascript">
  setTimeout('location.replace("/panel/calculation/")', 3000);
			</script>
			<?
	   } 
        
        
    }
    
}