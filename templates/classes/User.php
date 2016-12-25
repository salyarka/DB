<?php 
/**
* Реализует работу по авторизации
*/
class User extends DataBase
{
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
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);
        $stmt->bindValue(':passHash', $passHash, PDO::PARAM_STR);
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
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);
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
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);
        $stmt->bindValue(':passHash', $passHash, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }
}
?>