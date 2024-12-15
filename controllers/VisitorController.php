<?php

require 'Controller.php';

class VisitorController extends Controller
{
    public static function index(){
        return self::view('views/visitor.php');
    }

}
VisitorController::index();