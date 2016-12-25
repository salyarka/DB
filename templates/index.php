<?php
include 'config.php';
include 'functions.php';
require_once 'twig/vendor/autoload.php';
spl_autoload_register('autoLoadClass');	

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, [
    'cache' => 'tmp/cache',
    'auto_reload' => true
]);
$template = $twig->loadTemplate('index.html');
$params = [];

session_start();
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'http://university.netology.ru/user_data/salyarka/tasks/db/templates/auth/registration.php') {
    $params['success'] = 'Регистрация прошла успешно, теперь вы можете авторизоваться';
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
            $params['fail'] = 'Не правильный логин или пароль';
        }
    }
}
$template->display($params);
?>
