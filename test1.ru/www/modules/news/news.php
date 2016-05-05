<?php
/**
 * Created by PhpStorm.
 * User: Alexandr
 * Date: 12.11.2015
 * Time: 18:19
 */

if(isset($_POST['delete'])){ // УДАЛЕНИЕ записей через checkbox
   /*
    foreach($_POST['ids'] as $k=>$v){
        mysqli_query($link, "
        DELETE FROM `news`
        WHERE `id` = ".(int)$v."
    ");
    }
   */
    foreach($_POST['ids'] as $k=>$v) {
        $_POST['ids'][$k] = (int)$v; // обработка int-ом всех ==> (int)(".$ids.")
    }
    $ids = implode(',',$_POST['ids']); // делает массив в строку  | WHERE `id` = ".$ids."   ===>>   WHERE `id` IN (3, 2, 1)  ====>>  WHERE `login` IN ('inpost', 'winz')
    mysqli_query($link, "
        DELETE FROM `news`
        WHERE `id` IN (".$ids.")
    ") or exit(mysqli_error());
    $_SESSION['info'] = 'Новости были удалены';
    if($_POST['delete']) {

    }
    header('Location: index.php?module=news&page=news');
    exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'delete') { // Удаление записи
    mysqli_query($link, "
    DELETE FROM `news`
    WHERE `id` = ".$_GET['id']."
    ") or exit(mysqli_error());
    $_SESSION['info'] = 'Новость была удалена';
    header('Location: index.php?module=news&page=news');
    exit();
}



$news = mysqli_query($link,"
    SELECT * FROM `news`
   ORDER BY `id` DESC
");

if (isset($_SESSION['info'])) { //Вывод "запись была добавлена"
    $info = $_SESSION['info'];
    unset($_SESSION['info']);
}


