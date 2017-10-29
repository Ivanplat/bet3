<? if (isset($numEvent) && $numEvent > 0) { ?>
 
<div class="r1_golov">Оповещения</div>
<div class="r1">Список всех оповещений</div>
<?
while ($history = $db -> fetch($queryEvent)) {
	$update_view = $db->query("UPDATE history SET view = 1 WHERE id = ?i AND whose_id = ?i", $history['id'], $_SESSION['userId']);
    
?>   
<div class="r1_golov">
<?=$history['text_date'];?>
</div>

<div class="r1">
<?=$history['text'];?>
</div>
<?   
}
echo $navigation -> navi(); 
} else {
?>
<div class="r1_golov">Оповещения</div>
<div class="r1">Нет ни одного оповещения!</div>
<?	
}