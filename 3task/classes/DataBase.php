<?php 

/**
* Реализует подключение к БД и выполняет запросы
*/
class DataBase
{
    
    private $db;

    /**
     * Создает подключение к БД
     */
	public function __construct() 
    {
		try {
			global $dbName, $host, $charset, $user, $password;
			$this->db = new PDO("mysql:host=$host;dbname=$dbName;charset=$charset", $user, $password);
		} catch (PDOException $e) {
			echo 'Ошибка подключения!';
			die(http_response_code(500));
		}	
	}

    /**
     * Получает все строки из таблицы
     * @param  string $userId  идентификатор пользователя
     * @param  string $sort    строка по которой необходимо отсортировать
     * @return array           резудьтат запроса
     */
	public function getTasks($userId, $sort = null) 
	{
		$sortQuery = '';
		if ($sort == 'is_done' || $sort == 'date_added' || $sort == 'description') {
			$sortQuery = ' ORDER BY ' . $sort . ' ASC';
		}
		$query = 'SELECT id, description, is_done, date_added FROM task WHERE user_id = :userId' . $sortQuery;
        $stmt = $this->db->prepare($query);	
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();

        return $stmt->fetchall(PDO::FETCH_ASSOC);
	}
    
    /**
     * Записывает новое задание в БД
     * @param  string $task    наименование задания
     * @param  strinf $userId  идентификатор пользователя
     * @return void
     */
    public function newTask($task, $userId)
    {
        if ($task == '') {
            return FALSE;
        }
        $query = 'INSERT INTO task (description, is_done, date_added, user_id) VALUES (:task, 0, "' . date("Y-m-d H:i:s") . '", :userId)';
        $time = time();
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':task', $task);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
    }

    /**
     * Отмечает задание как сделанное
     * @param  integer $id идентификатор задания
     * @return void
     */
    public function markDone($id, $userId)
    {
    	$query = 'UPDATE task SET is_done = 1 WHERE id = :id AND (user_id = :user_id OR assigned_user_id = :user_id)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindvalue(':id', $id);
        $stmt->execute();
    }

    /**
     * Удаляет задание
     * @param  integet $id     идентификатор задания
     * @param  string  $userId идентификатор пользователя
     * @return void
     */
    public function delTask($id, $userId)
    {
    	$query = 'DELETE FROM task WHERE id = :id AND user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindvalue(':id', $id);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
    }

    /**
     * Редактирует описание задания
     * @param  string  $task новое описание
     * @param  integer $id   идентификатор
     * @return void 
     */
    public function editTask($task, $id, $userId)
    {
    	$query = 'UPDATE task SET description = :task WHERE id = :id AND user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':task', $task);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
    }

    /**
     * Заносит в БД нового пользователя
     * @param  string $login    логин пользователя
     * @param  string $passHash хеш пароля пользователя
     * @return void         
     */
    public function reg($login, $passHash)
    {
        $query = 'INSERT INTO user (login, password) VALUES (:login, :passHash)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':login', $login);
        $stmt->bindValue(':passHash', $passHash);
        $stmt->execute();
    }

    /**
     * Проверяет есть ли пользователь с таким логином
     * @param  string $login введенный пользователем логин
     * @return array         результат запроса
     */
    public function checkLogin($login)
    {
        $query = 'SELECT id FROM user WHERE login=:login';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':login', $login);
        $stmt->execute();
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    /**
     * Производит аутентификацию
     * @param  string $login    введенный пользователем логин
     * @param  string $passHash хэш введеного пользователем пароля
     * @return array            результат запроса
     */
    public function auth($login, $passHash)
    {
        $query = 'SELECT id, login FROM user WHERE login = :login AND password = :passHash';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':login', $login);
        $stmt->bindValue(':passHash', $passHash);
        $stmt->execute();
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    /**
     * Ищет всех имеющихся пользователей
     * @param  string $exceptUserId идентификатор пользователя который делает запрос
     * @return array                логины пользователей
     */
    public function getUsers($exceptUserId)
    {
        $query = 'SELECT login, id FROM user WHERE id <> :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $exceptUserId);
        $stmt->execute();
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    /**
     * Назначает задание другому пользователю
     * @param string $userId   идентификатор пользователя которому передаем задачу
     * @param string $taskId   идентификатор задания которое передаем
     * @param string $authorid идентификатор пользователя который назначает задачу
     */
    public function setTask($userId, $taskId, $authorId)
    {
        $query = 'UPDATE task SET assigned_user_id = :userId WHERE id = :taskId AND user_id = :authorId';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':taskId', $taskId);
        $stmt->bindValue(':authorId', $authorId);
        $stmt->execute();
    }

    /**
     * Ищет ответственного за задание
     * @param  string $taskID идентификатор задания
     * @return array          результат запроса
     */
    public function getResp($taskId)
    {
        $query = 'SELECT login FROM user JOIN task ON user.id = task.assigned_user_id AND task.id = :taskId';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':taskId', $taskId);
        $stmt->execute();
        return $stmt->fetchall(PDO::FETCH_ASSOC);
        
    }

    /**
     * Ищет задачи назначенные от других пльзователей
     * @param  string $userId идентификатор задачи
     * @return array          результат запроса
     */
    public function getAssignedTasks($userId) 
    {
        $query = 'SELECT id, description, is_done, date_added FROM task WHERE assigned_user_id = :userId';
        $stmt = $this->db->prepare($query); 
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    /**
     * Ищет автора задачи
     * @param  string $taskId идентификатор задачи
     * @return array          результат запроса
     */
    public function getAuthor($taskId)
    {
        $query = 'SELECT login FROM user JOIN task ON user.id = task.user_id AND task.id = :taskId';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':taskId', $taskId);
        $stmt->execute();
        return $stmt->fetchall(PDO::FETCH_ASSOC);

    }
}

?>