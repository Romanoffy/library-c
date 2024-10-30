<?php

define('SECURE_ACCESS', true);
$uri = $_SERVER['REQUEST_URI'];
$query_string = $_SERVER["QUERY_STRING"] ?? null;


if($uri == "/"){
    return require 'controller/HomeController.php';
}
if($uri == "/book"){
    return require 'controller/BookController.php';
}
if($uri == "/book?" . $query_string){
    return require 'controller/BookController.php';
}
return require 'views/notFoundPage.php';