<?php

include '../functions.php';
include '../config.php';
spl_autoload_register('autoLoadClass');	

session_start();
if (!empty($_SESSION['login'])) {
	$db = new DataBase();
	if (isset($_POST['task'])) {
		$db->newTask(cleanData($_POST['task']), $_SESSION['id']);
	}
	if (isset($_POST['do'])) {
		$db->markDone(cleanData($_POST['do']), $_SESSION['id']);
	}
	if (isset($_POST['delete'])) {
		$db->delTask(cleanData($_POST['delete']), $_SESSION['id']);
	}
	if (isset($_POST['edit'])) {
		$db->editTask(cleanData($_POST['edit']), cleanData($_POST['id']), $_SESSION['id']);
	}
	if (isset($_POST['set_to_user']) && isset($_POST['task_id'])) {
	 	$db->setTask(cleanData($_POST['set_to_user']), cleanData($_POST['task_id']), $_SESSION['id']);
	}	
	if (isset($_POST['sort_by'])) {
		$tasks = $db->getTasks($_SESSION['id'], cleanData($_POST['sort_by']));
	} else {
		$tasks = $db->getTasks($_SESSION['id']);
	}
	$id = 1;
	$users = $db->getUsers($_SESSION['id']);
	$assignedTasks = $db->getAssignedTasks($_SESSION['id']);
	include '../templates/todo.php';
} else {
	echo '<center>Список дел могут просматривать только авторизованные пользователи!<br></center>';
	echo '<center><a href ="../index.php">Авторизоваться</a></center>';
}

?>