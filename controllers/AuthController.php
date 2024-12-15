<?php

require 'Controller.php';
require 'models/User.php';

class AuthController extends Controller
{
    public static function login()
    {
        if (count($_POST) > 0){
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($username == "" || $password == "") {
                $_SESSION["error"] = "All fields must be filled!";
                $_SESSION["username"] = $username;

                header("location: /login");
                die();
            }
            User::auth($username, $password);
        }
        return self::view('views/login.php');
    }
    public static function register()
    {
        if (count($_POST) > 0){
            $username = htmlspecialchars($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if ($username == "" || $_POST['password'] == "") {
                $_SESSION["error"] = "All fields must be filled!";
                $_SESSION["username"] = $username;

                header("location: /register");
                exit();
            }

            try {
                $user = new User($username, $password, 1);
                $user->registerUser();
            } catch (Exception $e) {
                error_log("Register Controller Error: " . $e->getMessage());
                $_SESSION["error"] = "Terjadi kesalahan saat registrasi";
                header("location: /register");
                exit();
            }
        }
        return self::view('views/register.php');
    }
}

if($uri == '/login'){
    return AuthController::login();
} else if($uri == '/register'){
    return AuthController::register();
}