<?

#выбираем всех, кто просит вывода
$queryOut = $db -> query("SELECT * FROM output_means WHERE confirm = 0");
#считаем сколько их
$numOut = $db -> numRows($queryOut);


#подтверждение перевода
if (isset($_GET['confirm']) && isset($_GET['id'])) {

#принимаем и "чистим от каки" id-запроса
$id = clear($_GET['id']);

if ($id) { 

		
	#устанавливаем на запись метку confirm(подтвержденная)
	$updateIn = $db -> query("UPDATE output_means SET confirm = 1 WHERE id = ?i", $id);
	
	#вытащим информацию о конкретном выводе
	$select_out = $db->query("SELECT * FROM output_means WHERE id = ?i", $id);
	$data_out   = $db->fetch($select_out);
	
	if ($updateIn) {
		add_notification('Ваша запрос на вывод <b>'.$data_out['how'].' руб. </b> подтвержден!', $data_out['who']);
	show('Успешное подтверждение!');
	?>
	 <script>location.href = "/panel/output.means/";</script>
	<?
	}
	else
	errors('Ошибка подтверждения');
} else fatalError('Такого запроса на вывод средств не существует!');

}

#если нажали "Вернуть деньги" удаляем запись из БД и начисляем деньги обратно
 elseif (isset($_GET['return']) && isset($_GET['id'])) {
	$id = clear($_GET['id']);
		
	if (isset($_GET['ok'])) {
	
		$queryOut = $db -> query("SELECT * FROM output_means WHERE id = ?i", $id);
	$dataOut = $db -> fetch($queryOut);
	
	#смотрим сколько нужно начислить
	$plus = $dataOut['how'];
	#смотрим кому нужно начислить
	$whom = $dataOut['who'];
	
	#возвращаем средства
	$updateBalance = $db -> query("UPDATE users SET balance = balance + ?s WHERE id = ?i", $plus, $whom);
		
		$query = $db -> query("DELETE FROM output_means WHERE id = ?i", $id);
		
		if ($query) {
			add_notification('Ваш запрос на вывод <b>'.$plus.' руб.</b> отменен! Средства возвращены на внутренний счет!', $whom);
		show('Успешный возврат');
		?>
		 <script>location.href = "/panel/output.means/";</script>
		<?
		}
		else
		errors('Ошибка возврата');
} else {

	errors('Вы точно хотите вернуть деньги?<br>
		<a href="?return&id='.$id.'&ok">ДА</a>
		<a href="/panel/input.means">НЕТ</a>');
}	
	

}

