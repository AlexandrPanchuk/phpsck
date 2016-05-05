<?php
/*
include_once './config.php';
//include_once './index.php';
include_once './libs/default.php';
include_once './variables.php';
$link = mysqli_connect(DB_LOCAL, DB_LOGIN, DB_PASS, DB_NAME);


// что бы достать данные из запроса :
$res = mysqli_query($link, "SELECT * FROM `users` ");
$row = mysqli_fetch_assoc($res); // помещает в row один элемент массива
//echo mysqli_num_rows($res); // сколько запрос выбрал записей т.е. с `users`


if (mysqli_num_rows($res)){   // существуют ли записи в БД, если "ДА" то выполнять блок if
    while($row = mysqli_fetch_assoc($res)) { // помещает в row один элемент массива
        echo htmlspecialchars($row['login']).'<br>';
    } // помещает в row один элемент массива



} else echo 'Нет записей';

exit();

/*
 *
 *
 */


