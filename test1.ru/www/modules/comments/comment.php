<?php
/* Принимаем данные из формы */
/*
$name = $_POST["name"];
$page_id = $_POST["id"];
$text_comment = $_POST["comment"];
$name = htmlspecialchars($name);// Преобразуем спецсимволы в HTML-сущности
$text_comment = htmlspecialchars($text_comment);// Преобразуем спецсимволы в HTML-сущности
$mysqli = new mysqli(DB_LOCAL, DB_LOGIN, DB_PASS, DB_NAME);// Подключается к базе данных
$mysqli->query("INSERT INTO `comments` (`name`, `id`, `comment`) VALUES ('$name', '$id', '$comment')");// Добавляем комментарий в таблицу
header("Location: ".$_SERVER["HTTP_REFERER"]);// Делаем реридект обратно


  $page_id = 150;// Уникальный идентификатор страницы (статьи или поста)
  $mysqli = new mysqli("localhost", "root", "", "db");// Подключается к базе данных
  $result_set = $mysqli->query("SELECT * FROM `comments` WHERE `page_id`='$page_id'"); //Вытаскиваем все комментарии для данной страницы
  while ($row = $result_set->fetch_assoc()) {
      print_r($row); //Вывод комментариев
      echo "<br />";
  }
*/


  /* Принимаем данные из формы */
/*
  $name = $_POST["name"];
  $page_id = $_POST["page_id"];
  $text_comment = $_POST["text_comment"];
  $name = htmlspecialchars($name);// Преобразуем спецсимволы в HTML-сущности
  $text_comment = htmlspecialchars($text_comment);// Преобразуем спецсимволы в HTML-сущности
  $mysqli = new mysqli(DB_LOCAL, DB_LOGIN, DB_PASS, DB_NAME);// Подключается к базе данных
  $mysqli->query("INSERT INTO `comments` (`name`, `page_id`, `text_comment`) VALUES ('$name', '$page_id', '$text_comment')");// Добавляем комментарий в таблицу
  header("Location: ".$_SERVER["HTTP_REFERER"]);// Делаем реридект обратно


*/



if (isset($_POST['name_id'], $_POST['text_comment'])) {
  $errors = array();
  if (empty($_POST['name_id'])) {
    $err1 = $errors['name_id'] = 'Вы не заполнили свое имя!';
  }
  if (empty($_POST['text_comment'])) {
    $err = $errors['text_comment'] = 'Добавьте комментарий!';
  }
  if (!count($errors)) {
    mysqli_query($link, "
            INSERT INTO `comments` SET
            `name_id` = '" . mysqli_real_escape_string($link, $_POST['name_id']) . "',
            `text_comment` = '" . mysqli_real_escape_string($link, $_POST['text_comment']) . "',
            `page_id` = '" . mysqli_real_escape_string($link, $_POST['page_id']) . "'


        ") or exit(mysqli_error($link));


  }

  //$result_set = $mysqli->query("SELECT * FROM `comments` WHERE `page_id`='$page_id'"); //Вытаскиваем все комментарии для данной страницы
  $result_set = mysqli_query($link, "
            SELECT * FROM `comments` WHERE
            `page_id` = '" . mysqli_real_escape_string($link, $_POST['page_id']) . "'

        ") or exit(mysqli_error($link));

  /*while ($row = $result_set->fetch_assoc()) {
print_r($row); //Вывод комментариев
echo "<br />"; */


    while ($row = $result_set->fetch_assoc()) {
      //echo $row;
      //printf($row['text_comment']);
     // $view_comment_id = print_r($row['name_id']."<br>");
      $view_comment = print_r($row['text_comment']."<br>");


     // echo "<br>";
    }


}



















