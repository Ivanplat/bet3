<div class="r1_golov">Настройки системы</div>
<div class="r1">Здесь вы можете изменить основные параметры системы</div>

<form action="" method="post">

<div class="r1_golov">Различные настройки</div>
<div class="r1">
<b>Бонус при регистрации (зачисляется на счет пользователю):<br>
<input type="text" name="bonus_reg" value="<?=$settingsSystem['bonus_reg']?>"><br>
</div>


<div class="r1_golov">Настройки ввода средств</div>
<div class="r1">
<b>Минимальная сумма для ввода:<br>
<input type="text" name="min_in" value="<?=$settingsSystem['min_in']?>"><br>
<b>Максимальная сумма для ввода:<br>
<input type="text" name="max_in" value="<?=$settingsSystem['max_in']?>"><br>
</div>


<div class="r1_golov">Настройки вывода средств</div>
<div class="r1">
<b>Комиссия при выводе (%):<br>
<input type="text" name="com_out" value="<?=$settingsSystem['com_output']?>"><br>
<b>Минимальная сумма для вывода:<br>
<input type="text" name="min_out" value="<?=$settingsSystem['min_out']?>"><br>
<b>Максимальная сумма для вывода:<br>
<input type="text" name="max_out" value="<?=$settingsSystem['max_out']?>"><br>
</div>


<div class="r1_golov">Реферальная программа</div>
<div class="r1">
<? if (ref_status()) {?>
<input type="checkbox" name="ref_status" value="enable" checked>
<? } else {?>
<input type="checkbox" name="ref_status" value="enable">
<? } ?>
<b>Реферальная программа активирована<br><br>
<b>% от пополнения счета:<br>
<input type="text" name="ref_input" value="<?=$settingsSystem['ref_input']?>"><br>
<b>% от суммы выигрыша:<br>
<input type="text" name="ref_win" value="<?=$settingsSystem['ref_win']?>"><br>
</div>


<div class="r1_golov">Настройки ставок</div>
<div class="r1">
<b>Минимальная сумма ставки:<br>
<input type="text" name="min_rate" value="<?=$settingsSystem['min_rate']?>"><br>
<b>Максимальная сумма ставки:<br>
<input type="text" name="max_rate" value="<?=$settingsSystem['max_rate']?>"><br>
</div>


<div class="r1_golov">Системные счета</div>
<div class="r1">
<b>WebMoney:</b><br>
<input type="text" name="admin_webmoney" value="<?=$settingsSystem['admin_webmoney']?>"><br>

<b>QIWI:</b><br>
<input type="text" name="admin_qiwi" value="<?=$settingsSystem['admin_qiwi']?>"><br>

<b>Visa</b><br>
<input type="text" name="admin_visa" value="<?=$settingsSystem['admin_visa']?>"><br>
</div>

<div class="r1_golov">Иконки модулей</div>
<div class="r1">
<b>Максимальная ширина:</b><br>
<input type="text" name="icon_width" value="<?=$settingsSystem['icon_width']?>"><br>

<b>Максимальная высота:</b><br>
<input type="text" name="icon_height" value="<?=$settingsSystem['icon_height']?>"><br>

<b>Размер файла (КВ.):</b><br>
<input type="text" name="icon_size" value="<?=$settingsSystem['icon_size']/1024?>"><br>
</div>

<div class="r1">
<input type="submit" name="save" value="Сохранить настройки">
</div>
</form>