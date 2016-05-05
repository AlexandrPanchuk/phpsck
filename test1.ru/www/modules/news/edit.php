<?php
/**
 * Created by PhpStorm.
 * User: Alexandr
 * Date: 12.11.2015
 * Time: 23:02
 */



if (isset($_POST['edit'], $_POST['text'], $_POST['cat'], $_POST['title'], $_POST['description'] )) {

    foreach($_POST as $k=>$v) {
        $_POST[$k] = trim($v);
    }


    $_POST['text']= trim(&$_POST['text']); // удаление лишних пробелов
    mysqli_query($link, "
    UPDATE `news` SET
    `cat`         = '".mysqli_real_escape_string($link, $_POST['cat'])."',
    `title`       = '".mysqli_real_escape_string($link, $_POST['title'])."',
    `text`        = '".mysqli_real_escape_string($link, $_POST['text'])."',
    `description` = '".mysqli_real_escape_string($link, $_POST['description'])."'
    WHERE `id` = ".(int)$_GET['id']."
    ") or exit(mysqli_error());

    $_SESSION['info'] = 'Запись была изменена';
    header('Location: index.php?module=news&page=news');
    exit();
}



$news = mysqli_query($link, "
    SELECT *
    FROM `news` WHERE `id` = ".(int)$_GET['id']."
    LIMIT 1
") or exit(mysqli_error());
if(!mysqli_num_rows($news)) {
    $_SESSION['info'] = 'Данной новости не существует!';
    header('Location: index.php?module=news&page=news');
    exit();
}

$row = mysqli_fetch_assoc($news);
//wtf($row);

if (isset($_POST['title'])) {  // если при редактировании не затронули какое то поле, тогда подставить старое
    $row['title'] = $_POST['title'];
}