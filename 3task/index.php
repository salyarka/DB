<?php
include 'config.php';
include 'functions.php';
spl_autoload_register('autoLoadClass');	

session_start();
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'http://university.netology.ru/user_data/salyarka/tasks/db/3task/auth/registration.php') {
    $success = 'Регистрация прошла успешно, теперь вы можете авторизоваться';
}
if (isset($_POST['login']) && isset($_POST['password']) ) {
    if (($_POST['password'] != '') && (cleanData($_POST['login']) != '')) {
        $user = new User();
        $userInfo = $user->auth(cleanData($_POST['login']), md5($_POST['password']));
        if ($userInfo) {
            session_start();
            $_SESSION['login'] = $userInfo[0]['login'];
            $_SESSION['id'] = $userInfo[0]['id'];
            header('Location: todo/todo.php'); 
        } else {
            $fail = 'Не правильный логин или пароль';
        }
    }
}

include 'templates/index.html';
?>
