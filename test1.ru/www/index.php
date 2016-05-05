<?php
error_reporting(0);
ini_set('display_errors',1);
header('Content-Type: text/html; charset=utf-8');
session_start();

// Конфиг сайта
include_once './config.php';
include_once './libs/default.php';
include_once './variables.php';

// Роутер
$link = mysqli_connect(DB_LOCAL, DB_LOGIN, DB_PASS, DB_NAME);
mysqli_set_charset($link, 'utf8');




/*
$login = 'login3';
$age = 25;
$query = " INSERT INTO `users` SET
          `login` = '".mysqli_real_escape_string($link, $login)."',
          `age` = ".(int)$age;
mysqli_query($link, $query);

*/
/*
mysqli_query($link, "
INSERT INTO `users` SET
  `login` = 'login1',
  `age` = 34
  "
);
*/

include './modules/'.$_GET['module'].'/'.$_GET['page'].'.php';
include './skins/'.SKIN.'./index.tpl';

