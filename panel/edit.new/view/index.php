<?
#если события есть, то выводим их
if ($numSections) {
   
   if (!isset($_GET['edit']) && !isset($_GET['delete'])) {
?>
<div class="r1_golov">Редактирование новостей</div>
<div class="r1">Здесь вы можете изменить название новости или ее содержание.</div>
<?  
    
    while($section = $db -> fetch($allSections)) {

?>
<div class="r1_golov"><?=$section['new_title']; ?></div>
<div class="r1">
 [<a href="?edit&id=<?=$section['id']; ?>">Изменить</a>] 
 
 [<a href="?view=<?=$section['id']?>"><? if ($section['view']) { ?> Скрыть <? } else { ?> Показать <? } ?> </a>] 

  [<a href="?delete&id=<?=$section['id']; ?>">Удалить</a>] 
</div>
<?        }
    }
} else {
    echo 'Нет ни одной новости';
}