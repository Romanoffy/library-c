<?php

require 'Controller.php';
require 'models/Book.php';

class BookController extends Controller {
    
    public static function index()
    {
        $query_string = $_SERVER["QUERY_STRING"] ?? null;
       
        if(isset($query_string)){
            $filter = explode("=", $query_string);
            $listBook = Book::filter($filter[1]);
            return self::view("views/book.php",$listBook);
        }
        $listBook = Book::get();
        return self::view("views/book.php",$listBook);
    }
}

BookController::index();