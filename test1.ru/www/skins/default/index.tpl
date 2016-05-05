<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Art Ukraine</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<link href="/css/style.css" rel="stylesheet" type="text/css">
</head>

<body >

<div id = "maincontent" style="background-image: url(img/voron.jpg)">
    <div class="mycontent" >

          <div class="logo">
            <div class="sss1"><img class="logopng" src="img\logo.png" alt=""></div>
            <div class = "sign_in" >Вы имеете аккаунт? <a href = "#" class="sign_in_up">Вход</a> или <a href = "index.php?module=cab&page=registration" class="sign_in_up"> Зарегистрируйтесь </a> </div>
            <div class = "clear"> </div>
          </div>

          <br>

          <div class="navigation" style="height: 57px; width: 1040px; margin: auto; background-image: url(img/menu.jpg); background-position: left bottom; background-repeat: repeat-x; position: relative; ">
            <div class="home"> <a href="/index.php?module=static&page=main"><p> Главная  </p></a> </div>
            <div class="painting"> <a href="/index.php?module=painting&page=painting"><p> Художники </p></a> </div>
            <div class="gallery"> <a href="/index.php?module=galery&page=galery"><p> Галерея </p></a> </div>
            <div class="about"> <a href="index.php?module=news&page=news"><p> Новости </p></a> </div>
            <div class="contacts"><a href="index.php?module=contacts&page=contacts"> <p> Контакты </p> </a></div>
            <div class = "clear"> </div>
           </div>

           <div id="content">
                  <?php include $_GET['module'].'/'.$_GET['page'].'.tpl'; ?>
           </div>


           <div class="footer">
                  <div class="footer1"><p class="p_" >
                          <footer>Копирайты &copy; 2013</footer>
                  </div>
           </div>

    </div>
</div>
</body>
</html>


