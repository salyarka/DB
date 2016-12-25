<?php
/**
* Реализует подключение к БД и выполняет запросы
*/
class DataBase
{
    protected $db;

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
}
?>