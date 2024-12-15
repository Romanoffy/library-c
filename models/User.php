<?php

require_once 'config/database.php';

class User {
    private $id, $username, $password, $role_id;

    public function __construct($username = null, $password = null, $role_id = null)
    {
        if ($username !== null) {
            $this->username = $username;
            $this->password = $password;
            $this->role_id = $role_id;
        }
    }

    public static function auth($username, $password)
    {
        try{
            global $pdo;
            // Gunakan prepared statement untuk keamanan
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$user){
                $_SESSION["username"] = $username;
                $_SESSION["error"] = "$username has not registered!";
                header("location: /login");
                exit();
            }

            if (password_verify($password, $user['password'])){
                $_SESSION['is_login'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['id'];

                header("location: /book");
                exit();
            }

            $_SESSION["error"] = "WRONG PASSWORD!";
            header('location: /login');
            exit();

        } catch (PDOException $e) {
            error_log("Auth Error: " . $e->getMessage());
            $_SESSION["error"] = "Terjadi kesalahan saat login";
            header('location: /login');
            exit();
        }
    }

    public function registerUser()
    {
        try {
            global $pdo;
            
            $stmt = $pdo->prepare("SELECT username FROM users WHERE username = ?");
            $stmt->execute([$this->username]);
            
            if($stmt->rowCount() > 0) {
                $_SESSION["error"] = "Username sudah digunakan!";
                header("location: /register");
                exit();
            }

            $stmt = $pdo->prepare("INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)");
            $result = $stmt->execute([
                $this->username,
                $this->password,
                $this->role_id
            ]);
            
            if($result) {
                $_SESSION["success"] = "Registrasi berhasil! Silakan login.";
                header("location: /login");
                exit();
            } else {
                $_SESSION["error"] = "Registrasi gagal!";
                header("location: /register");
                exit();
            }
        } catch (PDOException $e) {
            error_log("Register Error: " . $e->getMessage());
            $_SESSION["error"] = "Terjadi kesalahan saat registrasi";
            header("location: /register");
            exit();
        }
    }
}