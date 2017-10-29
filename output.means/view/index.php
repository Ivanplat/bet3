<?  
#для начала проверим, заполнены ли у пользователя реквизиты (т.е. кошельки WebMoney или QIWI)
checkDetails($_SESSION['userId']);
?>
<div class="r1_golov">Вывод средств</div>

<div class="r1">
<b>Комиссия при выводе:</b> <?=$settingsSystem['com_output'];?> %<br>
<form action="" method="post">
Система оплаты:<br />
<select name="type">
<? if (checkWMR($_SESSION['userId']) && !empty($settingsSystem['admin_webmoney'])) {?>
<option value="WebMoney">WebMoney</option>
<? } ?>

<? if (checkQIWI($_SESSION['userId']) && !empty($settingsSystem['admin_qiwi'])) {?>
<option value="QIWI">QIWI</option>
<? } ?>

<? if (checkVisa($_SESSION['userId']) && !empty($settingsSystem['admin_visa'])) {?>
<option value="Visa">Visa</option>
<? } ?>

<? if (checkYandex($_SESSION['userId']) && !empty($settingsSystem['admin_yandex'])) {?>
<option value="Yandex">Яндекс-Деньги</option>
<? } ?>
</select>

<br />
Введите сумму:<br />
<input type="text" name="amount" required><br />
<input type="submit" name="goAmount" value="Подтвердить">
</form>
</div>