<?php

namespace api;

use Smarty;
use system\LoaderController;
use system\ProtectionController;

class Controller
{
    public $loader;
    public $smarty;
    public $protection;

    public function __construct()
    {
        $this->protection = new ProtectionController();
        $this->loader = new LoaderController();

        $this->smarty = new Smarty();
        $this->smarty->setConfigDir($_SERVER['DOCUMENT_ROOT'].'/system/modules/smarty/configs');
        $this->smarty->setTemplateDir($_SERVER['DOCUMENT_ROOT'].'/api/Views');
        $this->smarty->setCompileDir($_SERVER['DOCUMENT_ROOT'].'/public/templates_c');
        $this->smarty->setCacheDir($_SERVER['DOCUMENT_ROOT'].'/public/cache');

    }

    public function debug($arr)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }

    public function create_error($text, $type)
    {
        $_SESSION['errors'][] = [
            'text' => $text,
            'type' => $type
        ];
    }

    public function is_login()
    {
        if (!isset($_SESSION['admin'])) {
            $this->redirect('Login');
        }
    }

    public function redirect($class, $methode = 'index')
    {
        header('Location: https://'.$_SERVER['HTTP_HOST'].'?Class='.$class.'&Methode='.$methode, true, 301);
    }

}