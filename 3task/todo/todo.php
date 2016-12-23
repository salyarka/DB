<?php
include '../functions.php';
include '../config.php';
spl_autoload_register('autoLoadClass');	

session_start();
if (!empty($_SESSION['login'])) {
    $task = new Task();
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
        $tasks = $task->getTasks($_SESSION['id'], cleanData($_POST['sort_by']));
    } else {
        $tasks = $task->getTasks($_SESSION['id']);
    }
    $id = 1;
    $users = $task->getUsers($_SESSION['id']);
    $assignedTasks = $task->getAssignedTasks($_SESSION['id']);
    
    include '../templates/todo.html';
} else {
    header('Location: ../index.php');
}
?>