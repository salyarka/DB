<?php
include '../functions.php';
include '../config.php';
require_once '../twig/vendor/autoload.php';
spl_autoload_register('autoLoadClass');	

$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader, [
    'cache' => '../tmp/cache',
    'auto_reload' => true
]);
$template = $twig->loadTemplate('todo.html');
$params = [];

session_start();
if (!empty($_SESSION['login'])) {
    $task = new Task();
    $params['login'] = $_SESSION['login'];
    if (isset($_POST['task'])) {
        $task->newTask(cleanData($_POST['task']), $_SESSION['id']);
    }
    if (isset($_POST['do'])) {
        $task->markDone(cleanData($_POST['do']), $_SESSION['id']);
    }
    if (isset($_POST['delete'])) {
        $task->delTask(cleanData($_POST['delete']), $_SESSION['id']);
    }
    if (isset($_POST['edit'])) {
        $task->editTask(cleanData($_POST['edit']), cleanData($_POST['id']), $_SESSION['id']);
    }
    if (isset($_POST['set_to_user']) && isset($_POST['task_id'])) {
        $task->setTask(cleanData($_POST['set_to_user']), cleanData($_POST['task_id']), $_SESSION['id']);
    }
    if (isset($_POST['sort_by'])) {
        $params['tasks'] = $task->getTasks($_SESSION['id'], cleanData($_POST['sort_by']));
    } else {
        $params['tasks'] = $task->getTasks($_SESSION['id']);
    }
    $params['users'] = $task->getUsers($_SESSION['id']);
    $params['assignedTasks'] = $task->getAssignedTasks($_SESSION['id']);
    $template->display($params);
} else {
    header('Location: ../index.php');
}
?>