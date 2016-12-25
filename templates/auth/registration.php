<?php 
include '../config.php';
include '../functions.php';
require_once '../twig/vendor/autoload.php';
spl_autoload_register('autoLoadClass');

$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader, [
    'cache' => '../tmp/cache',
    'auto_reload' => true
]);
$template = $twig->loadTemplate('registration.html');
$params = [];

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
                $params['fail'] = 'Пользователь с таким логином существует!';
            }
        } else {
            $params['fail'] = 'Логин может содержать только буквы латинского алфавита и цифры!';
        }
    } elseif ($_POST['password'] != $_POST['confirm']) {
        $params['fail'] = 'Введенные пароли не совпадают!';
    } else {
        $params['fail'] = 'Не все поля заполнены!';
    }
}
$template->display($params);
?>