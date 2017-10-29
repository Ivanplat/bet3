<?  
#для начала проверим, заполнены ли у пользователя реквизиты (т.е. кошельки WebMoney или QIWI)
if (!ref_status()) fatalError('Реферальная программа не активна.');
?>
<div class="r1_golov">Реферальная программа</div>

<div class="r1">
Ваша ссылка:<br />
<input type="text" value="http://<?=$_SERVER['SERVER_NAME'].'/?ref='.userLogin($_SESSION['userId']);?>"/>

<br>
Ваши рефералы:<br />
<?
$q = $db->query("SELECT * FROM users WHERE refer = ?i", $_SESSION['userId']);

	if ($db->numRows($q)>0) {
		$i = 0;
		while($all_ref = $db->fetch($q)) {
			?>
			<b><?=++$i.') '.$all_ref['login'];?> </b>
			[прибыль от него: <?=$all_ref['sum_ref'] ? $all_ref['sum_ref'] : 0;?> руб.]<br>
			<?
		}

	} else {
		echo 'У вас нет ни одного реферала!<br>';
	}
?>
</div>