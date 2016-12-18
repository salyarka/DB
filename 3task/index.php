<?php 

include 'config.php';
include 'functions.php';
spl_autoload_register('autoLoadClass');	

session_start();
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'http://university.netology.ru/user_data/salyarka/tasks/db/3task/auth/registration.php') {
	echo '<center><h5>Регистрация прошла успешно, теперь вы можете авторизоваться</h5></center>';
}
if (isset($_POST['login']) && isset($_POST['password']) ) {
	if (($_POST['password'] != '') && (cleanData($_POST['login']) != '')) {
		$db = new DataBase();
		$user = $db->auth(cleanData($_POST['login']), md5($_POST['password']));
		if ($user) {
			session_start();
			$_SESSION['login'] = $user[0]['login'];
			$_SESSION['id'] = $user[0]['id'];
			header('Location: todo/todo.php'); 
		} else {
			echo '<center><h4>Не правильный логин или пароль</h4></center>';
		}
	}
}

include 'templates/index.html';

?>
