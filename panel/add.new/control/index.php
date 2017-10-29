<?php

if (isset($_POST['addNew'])) {
    $new_title = XSS($_POST['new_title']);
    $new_text  = XSS($_POST['new_text']);

    if ($new_text && $new_title) {
        $addSection = $db -> query("INSERT INTO news (new_title, new_text, new_date) VALUES (?s, ?s, ?i) ", $new_title, $new_text, time());
        
        if ($addSection) show('Новость добавлена!');
            else
                errors('Ошибка создания новости!<br /><a href="">Повторить</a>');
    }
}