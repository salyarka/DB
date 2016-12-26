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
     * Ищет список таблиц
     * @return array результат запроса
     */
    public function getTables()
    {
        $query = 'SHOW TABLES';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchall(PDO::FETCH_ASSOC); 
    }

    /**
     * Ищет информацию по заданной таблице
     * @param  string $tableName имя таблицы
     * @return array             результат запроса
     */
    public function getInfo($tableName)
    {

        $query = 'DESCRIBE ' . $tableName;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    /**
     * Удаляет столбец в заданной таблице
     * @param  string $table имя таблицы
     * @param  string $field имя столбца
     * @return void        
     */
    public function delField($table, $field)
    {
        $query = 'ALTER TABLE ' . $table . ' DROP COLUMN ' . $field;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    }

    /**
     * Добавляет новое поле в заданную таблицу
     * @param   string $table имя таблицы
     * @param   string $field имя поля
     * @param   string $type  тип поля
     * @param   string $lengh длина поля
     * @return  void
     */
    public function addField($table, $field, $type, $lengh = null)
    {
        if ($lengh != null) {
            $query = 'ALTER TABLE ' . $table . ' ADD ' . $field . ' ' . $type . '(' . $lengh . ')';
        } else {
            $query = 'ALTER TABLE ' . $table . ' ADD ' . $field . ' ' . $type;
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    }

    /**
     * Изменяет тип поля в заданной таблице
     * @param  string $table имя таблицы
     * @param  string $field имя поля
     * @param  string $type  тип поля
     * @param  string $lengh длина поля
     * @return void          
     */
    public function changeType($table, $field, $type, $lengh = null)
    {
        if ($lengh != null) {
            $query = 'ALTER TABLE ' . $table . ' MODIFY ' . $field . ' ' . $type . '(' . $lengh . ')';
        } else {
            $query = 'ALTER TABLE ' . $table . ' MODIFY ' . $field . ' ' . $type;    
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    } 
}
?>