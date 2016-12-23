<?php 
include '../config.php';
include '../functions.php';
spl_autoload_register('autoLoadClass');	

if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['confirm'])) {
    $login = cleanData($_POST['login']);
    if ($_POST['password'] == $_POST['confirm'] && ($_POST['password'] != '') && ($login != '')) {
        if (preg_match("/^[a-zA-Z0-9]+$/", $login)) {
            $passHash = md5($_POST['password']);
            $user = new User();
            if (!$user->checkLogin($login)) {
                $user->reg($login, $passHash);
                header('Location: ../index.php');
            } else {
                $fail = 'Пользователь с таким логином существует!';
            }
        } else {
            $fail = 'Логин может содержать только буквы латинского алфавита и цифры!';
        }
    } elseif ($_POST['password'] != $_POST['confirm']) {
        $fail = 'Введенные пароли не совпадают!';
    } else {
        $fail = 'Не все поля заполнены!';
    }
}

include '../templates/registration.html';
?>