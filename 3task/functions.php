<?php 

/**
 * Автозагрузчик классов
 * @param  string $class название класса
 * @return void
 */
function autoLoadClass($class)
{
	if (is_file('classes/' . $class . '.php')) {
	    include 'classes/' . $class . '.php';
	}
	if (is_file('../classes/' . $class . '.php')) {
	    include '../classes/' . $class . '.php';
	}
}

/**
 * Чистит пользовательский ввод
 * @param  string $data введеные данные пользователем
 * @return string       очищенные данные
 */
function cleanData($data) 
{
	$result = trim($data);
	$result = stripslashes($result);
	$result = strip_tags($result);
	return $result = htmlspecialchars($result);
}

/**
 * Выводит состояние задания
 * @param integer $state 1 - выполнено, 0 - в работе 
 * @return string
 */
function isDone($state)
{
	if ($state == 1) {
		return 'Выполнено';
	} else {
		return 'В работе';
	}
}

/**
 * Извлекает логин из запроса
 * @param  array   $data     результат запроса
 * @param  string  $default  строка возвращающаяся, если нет ответа на запрос
 * @return string            логин или заданную строку
 */
function extLogin($data, $default = null)
{
	if ($data) {
		return $data[0]['login'];
	} else {
		return $default;
	}
}

?>