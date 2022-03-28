<?php

namespace api\Controllers;

use api\Controller;

class HomeController extends Controller
{
    public $tree = '';

    public function index()
    {
        $this->is_login();
        $this->smarty->display('Home.tpl');
    }
}