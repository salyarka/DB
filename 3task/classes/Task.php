<?php 
/**
* Реализует работу с заданиями
*/
class Task extends DataBase
{
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
        $query = 'SELECT t.id, t.user_id, t.description, t.is_done, t.date_added, u.login
                  FROM task AS t JOIN user AS u 
                  ON t.assigned_user_id = u.id 
                  WHERE t.user_id = :userId' . $sortQuery;
        $stmt = $this->db->prepare($query); 
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
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
        $query = 'INSERT INTO task (description, is_done, date_added, user_id, assigned_user_id)
                  VALUES (:task, 0, "' . date("Y-m-d H:i:s") . '", :userId, :assignedUserId)';
        $time = time();
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':task', $task, PDO::PARAM_STR);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':assignedUserId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Отмечает задание как сделанное
     * @param  integer $id идентификатор задания
     * @return void
     */
    public function markDone($id, $userId)
    {
        $query = 'UPDATE task SET is_done = 1 WHERE id = :id
                  AND (user_id = :user_id OR assigned_user_id = :user_id)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
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
        $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
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
        $stmt->bindValue(':task', $task, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
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
        $stmt->bindValue(':id', $exceptUserId, PDO::PARAM_INT);
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
        $query = 'UPDATE task SET assigned_user_id = :userId WHERE id = :taskId
                  AND user_id = :authorId';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':taskId', $taskId, PDO::PARAM_INT);
        $stmt->bindValue(':authorId', $authorId, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Ищет задачи назначенные от других пльзователей
     * @param  string $userId идентификатор задачи
     * @return array          результат запроса
     */
    public function getAssignedTasks($userId) 
    {
        $query = 'SELECT t.id, t.user_id, t.description, t.is_done, t.date_added, u.login
                  FROM task AS t JOIN user AS u ON t.user_id = u.id
                  WHERE t.assigned_user_id = :userId AND t.user_id <> :userId';
        $stmt = $this->db->prepare($query); 
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }
}
?>