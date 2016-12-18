<?php 
include '../templates/login.html';

if ($_SERVER['HTTP_REFERER'] == 'http://localhost/db/3task/auth/registration.php') {
	echo '<center>Регистрация прошла успешно, для входа введите логин и пароль</center>';
}

?>