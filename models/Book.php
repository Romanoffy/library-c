<?php

require_once 'config/database.php';

class Book {
    private $id, $title, $author, $year;

    public function getId()
    {
        return $this->id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getAuthor()
    {
        return $this->author;
    }
    public function getYear()
    {
        return $this->year;
    }

    static function filter($search)
    {
        global $pdo;
        $query = $pdo->query("SELECT * FROM books WHERE title LIKE '%$search%'");
        return $query->fetchAll(PDO::FETCH_CLASS, 'Book');
    }
    static function get()
    {
        global $pdo;
        $query = $pdo->query("SELECT * FROM books");
        return $query->fetchAll(PDO::FETCH_CLASS, 'Book');
    }
}