
<?php
if (isset($_POST['add'], $_POST['text'], $_POST['cat'], $_POST['title'], $_POST['description'] )) {

    foreach($_POST as $k=>$v) {
        $_POST[$k] = trim($v);
    }


    $_POST['text']= trim(&$_POST['text']); // удаление лишних пробелов
    mysqli_query($link, "
    INSERT INTO `news` SET
    `cat`         = '".mysqli_real_escape_string($link, $_POST['cat'])."',
    `title`       = '".mysqli_real_escape_string($link, $_POST['title'])."',
    `text`        = '".mysqli_real_escape_string($link, $_POST['text'])."',
    `description` = '".mysqli_real_escape_string($link, $_POST['description'])."',
    `date`        =  NOW()
    ") or exit(mysqli_error());



    $_SESSION['info'] = 'Запись была добавлена';

    header('Location: index.php?module=news&page=news');
    exit();
}


// mysqli_real_escape_string --> экранизирует кавычки и слеши