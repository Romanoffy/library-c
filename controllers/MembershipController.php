<?php

require 'Controller.php';

class MembershipController extends Controller
{
    public static function index(){
        return self::view('views/membership.php');
    }

}
MembershipController::index();