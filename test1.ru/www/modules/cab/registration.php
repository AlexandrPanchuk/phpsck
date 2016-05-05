<?php


if (isset($_POST['login'], $_POST['password'], $_POST['emeil'], $_POST['age'])) {
    $errors = array();
    if (empty($_POST['login'])) {
        $errors['login'] = 'Вы не заполнили логин!';
    }

    if (empty($_POST['password'])) {
        $errors['password'] = 'Вы не заполнили пароль!';
    }

    if (empty($_POST['emeil']) || !filter_var($_POST['emeil'], FILTER_VALIDATE_EMAIL)) {
        $errors['emeil'] = 'Вы не заполнили emeil!';
    }
    if(!count($errors)){
        mysqli_query($link, "
            INSERT INTO `users` SET
            `login` = '".mysqli_real_escape_string($link, $_POST['login'])."',
            `password` = '".mysqli_real_escape_string($link, $_POST['password'])."',
            `emeil` = '".mysqli_real_escape_string($link, $_POST['emeil'])."',
            `age` = ".(int)$_POST['age']."

        ") or exit(mysqli_error($link)); // вывод ошибки базы данных

            $_SESSION['regok'] = 'Ok';

            header("Location: index.php?module=cab&page=registration");
            //exit();
            // если не содержит элементов в данном массиве, то выполнять действия дальше
    }
}

/*
if (isset($_POST['password']) && empty($_POST['password'])) {
    $errors['password'] = 'Вы не заполнили поле!';
}

if (isset($_POST['emeil']) && empty($_POST['emeil'])) {
    $errors['emeil'] = 'Вы не заполнили поле!';
}
*/