<? if (isset($numEvent) && $numEvent > 0) { ?>
 
<div class="r1_golov">Новости</div>
<div class="r1">Все новости сайта</div>
<?
while ($news = $db -> fetch($queryEvent)) {
    
?>   
<div class="r1_golov">
<?=$news['new_title']?>
</div>

<div class="r1">
<?=substr($news['new_text'], 0, 151);?><br>
<a href="?view=<?=$news['id']?>">Читать полностью</a>
</div>
<?   
}
echo $navigation -> navi(); 
} 