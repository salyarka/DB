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
}
?>