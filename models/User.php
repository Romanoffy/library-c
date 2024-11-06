<?php
require_once 'config/database.php';

class User {
    private $id, $username, $password, $role_id;

    public function __construct(
        $username,
        $password,
        $role_id
    )
    {
        $this->username=$username;
        $this->password=$password;
        $this->role_id=$role_id;
    }

    public function registerUser()
    {
        global $pdo;
        $sql = "INSERT INTO users (username, password, role_id) VALUES ('$this->username', '$this->password', '$this->role_id')";

        try{
            $pdo->exec($sql);
            echo "Register Success !";
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
}