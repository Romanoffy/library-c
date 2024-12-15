<?php
session_start();
define('SECURE_ACCESS', true);

$uri = $_SERVER['REQUEST_URI'];
$query_string = $_SERVER["QUERY_STRING"] ?? null;

if($uri =='/') {
    return require 'controllers/HomeController.php';
}

if($uri =='/book') {
    
    return require 'controllers/BookController.php';
}

if($uri =='/book?' . $query_string) {
    
    return require 'controllers/BookController.php';
}
if($uri =="/register" || $uri == "/login") {
    
    return require 'controllers/AuthController.php';
}
if($uri =="/visitor") {
    
    return require 'controllers/VisitorController.php';
}
if($uri =="/membership") {
    
    return require 'controllers/MembershipController.php';
}
if($uri == "/borrow" || $uri == "/borrow?". $query_string) {
    return require 'controllers/BorrowController.php';
}
if($uri == "/return" || $uri == "/return?". $query_string) {
    return require 'controllers/ReturnController.php';
}

return require 'views/notFoundPage.php';