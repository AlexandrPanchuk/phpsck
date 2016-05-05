 <?php

$allowed = array('static','contacts','cab','news','program1','file1','errors','painting','galery','comments');
if(!isset($_GET['module'])) {
	$_GET['module'] = 'static';
} elseif(!in_array($_GET['module'],$allowed)) {
	header("Location: /index.php?module=errors&page=404");
	exit();
}

if(!isset($_GET['page'])) {
	$_GET['page'] = 'main';
}
