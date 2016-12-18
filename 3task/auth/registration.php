<?php 

include '../config.php';
include '../functions.php';
spl_autoload_register('autoLoadClass');	

if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['confirm'])) {
	$login = cleanData($_POST['login']);
	if ($_POST['password'] == $_POST['confirm'] && ($_POST['password'] != '') && ($login != '')) {
		if (preg_match("/^[a-zA-Z0-9]+$/", $login)) {
			$passHash = md5($_POST['password']);
			$db = new DataBase();
			if (!$db->checkLogin($login)) {
				$db->reg($login, $passHash);
				header('Location: ../index.php');
			} else {
				echo '<center><h4>Пользователь с таким логином существует!</h4></center>';
			}
		} else {
			echo '<center><h4>Логин может содержать только буквы латинского алфавита и цифры!</h4></center>';
		}
	} elseif ($_POST['password'] != $_POST['confirm']) {
		echo '<center><h4>Введенные пароли не совпадают!</h4></center>';
	} else {
		echo '<center><h4>Не все поля заполнены!</h4></center>';
	}
}

include '../templates/registration.html';

?>