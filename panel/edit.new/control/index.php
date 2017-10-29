<?php

#выбираем все события из БД
$allSections = $db -> query("SELECT * FROM news");
$numSections = $db -> numRows($allSections);


#удаление события
if (isset($_GET['delete']) && isset($_GET['id'])) {
    
    #принимаем id-события
    $id = clear($_GET['id']);
    
    #удаляем запись из БД
    $delEvent = $db -> query("DELETE FROM news WHERE id = '?i' ", $id);
    
    #информируем админа
    if ($delEvent) show('новость успешно удалена!');
    else
        errors('Ошибка удаления новости!');
    
}


#изменение события
if (isset($_GET['edit']) && isset($_GET['id'])) {
    
    #принимаем id-события
    $id = clear($_GET['id']);
    
    #выбираем данные к этой записи
    $editSections = $db -> query("SELECT * FROM news WHERE id = '?i' ", $id);
    
    #смотрим, есть ли такая запись в базе
    $numSections = $db -> numRows($editSections);
    
    #если нет, выдаем ошибку
    if (!$numSections) errors('Такой новости не существует!');
    
    #если существует, то...
    else {
        $edSections = $db -> fetch($editSections);
?>      
        <div class="r1_golov">Изменение новости</div>
        <div class="r1">
        <form action="/panel/edit.new/?update=<? echo $edSections['id']; ?>" method="post">
        Заголовок:<br/>
        <input type="text" name="new_title" value="<? echo $edSections['new_title']; ?>"><br>
        Текст новости:<br>
        <textarea name="new_text"><?=$edSections['new_text'];?></textarea>
        <input type="submit" name="editNew" value="Сохранить изменения"/>
        </form>
        </div>
        <?
    }
    
}


if (isset($_GET['update'])) {

$id = (clear($_GET['update'])) ? clear($_GET['update']) : false;
$new_title = (XSS($_POST['new_title'])) ? XSS($_POST['new_title']) : false;
$new_text = (XSS($_POST['new_text'])) ? XSS($_POST['new_text']) : false;

if ($id && $new_title && $new_text) {

$update = $db -> query("UPDATE news SET new_title = ?s, new_text = ?s WHERE id = ?i", $new_title, $new_text, $id);

if ($update) {
    show('Новость успешно изменена!');
    ?>
<script>
location.href = '/panel/edit.new/';
</script>
    <?
}
else
errors('Ошибка изменения новости!');

}


}


if (isset($_GET['view']) && !empty($_GET['view'])) {
    $id_new = intval(abs($_GET['view']));

    $sel = $db->query("SELECT * FROM news WHERE id = ?i", $id_new);

    if ($db->numRows($sel)) {
        $d = $db->fetch($sel);

        if ($d['view']) {
            $q = $db->query("UPDATE news SET view = ?i WHERE id = ?i", 0, $id_new);
        } else {
            $q = $db->query("UPDATE news SET view = ?i WHERE id = ?i", 1, $id_new);
        }

     ?> 
     <script>
        location.href = '/panel/edit.new/';
    </script>
     <?   

    } else {
        ?>
<script>
location.href = '/panel/edit.new/';
</script>
        <?
    }
}