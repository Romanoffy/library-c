<?php

class Controller
{

    protected static function view($page, $data = [])
    {
        $data;
        return require $page;
    }
}