<?php ob_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>TODO</title>
	<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
	<h1>Список задач</h1>
	<form method="POST">
		<label for="task">Новая задача:</label>
		<input type="text" name="task">
		<button type="submit">Добавить</button>
	</form>	
	<form method="POST">
        <label for="sort">Сортировать по:</label>
        <select name="sort_by">
            <option value="description">Задачам</option>
            <option value="date_added">Дате</option>
            <option value="is_done">Статусу</option>
        </select>
        <input type="submit" name="sort" value="Отсортировать">
    </form>
    <div id="logout">
    	<a href="../auth/logout.php">Выход(<?= $_SESSION['login'] ?>)</a>
    </div>	
	<hr>
	<table>
		<tr>
			<th></th>
			<th>Задачи</th>
			<th>Статус</th>	
			<th>Добавлено</th>
			<th>Изменить</th>
			<th>Выполнить/Удалить</th>
			<th>Ответственный</th>
			<th>Закрепить за другим пользователем</th>
		</tr>
		<?php foreach ($tasks as $task): ?>
			<tr>
				<th><?= $id ?></th>
				<td><?= $task['description'] ?></td>
				<td><?= isDone($task['is_done']) ?></td>
				<td><?= $task['date_added'] ?></td>
				<td>
					<form method="POST">
						<input type="text" name="edit">
						<input type="hidden" name="id" value="<?= $task['id'] ?>">
						<button type="submit">Редактировать</button>
					</form>	
				</td>
				<td>
					<?php if (!$db->getResp($task['id'])): ?>
						<form method="POST">
							<input type="hidden" name="do" value="<?= $task['id'] ?>">
							<button type="submit">Выполнить</button>
						</form>
					<?php endif; ?>		
					<form method="POST">
						<input type="hidden" name="delete" value="<?= $task['id'] ?>">
						<button type="submit">Удалить</button>
					</form>	
				</td>
				<td>
					<?= extLogin($db->getResp($task['id']), 'Вы') ?>
				</td>
				<td>
					<form method="POST">
        				<input type="hidden" name="task_id" value="<?= $task['id'] ?>">
        				<select name="set_to_user">
        					<?php foreach ($users as $user): ?>
        						<option value="<?= $user['id'] ?>"><?= $user['login'] ?></option>
        					<?php endforeach; ?>	
        				</select>
        				<input type="submit" name="sort" value="Выполнить">
    				</form>
				</td>
			</tr>
		<?php $id++; endforeach; $id = 1; ?>
	</table>
	<hr>
	<h2>Задачи назначенные Вам другими пользователями</h2>
	<hr>
	<table>
		<tr>	
			<th></th>
			<th>Задачи</th>
			<th>Статус</th>	
			<th>Добавлено</th>
			<th>Выполнить</th>
			<th>Автор</th>
		</tr>	
		<?php foreach ($assignedTasks as $assignedTask): ?>
			<tr>
				<th><?= $id ?></th>
				<td><?= $assignedTask['description'] ?></td>
				<td><?= isDone($assignedTask['is_done']) ?></td>
				<td><?= $assignedTask['date_added'] ?></td>
				<td>
					<form method="POST">
						<input type="hidden" name="do" value="<?= $assignedTask['id'] ?>">
						<button type="submit">Выполнить</button>
					</form>
				</td>
				<td> <?= extLogin($db->getAuthor($assignedTask['id'])) ?> </td>
			</tr>	
		<?php $id++; endforeach; ?>
	</table>
	<hr>
</body>
</html>
<?php if ($_POST && !$_POST['sort_by']) {header('Location: ' . $_SERVER['PHP_SELF']);} ?>