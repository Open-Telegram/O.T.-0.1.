<?php include_once('system/MainController.php');
define('SMARTY_DIR',$_SERVER['DOCUMENT_ROOT'].'/system/modules/smarty/');
require(SMARTY_DIR.'Smarty.class.php');
spl_autoload_register(function ($class) {
    if ($class != 'mysqli') {
        $class = str_replace('\\', '/', $class);
        if(!include $_SERVER['DOCUMENT_ROOT'].'/'.$class.'.php'){
            include $_SERVER['DOCUMENT_ROOT'].'/'.$class.'.class.php';
        };
    }
});
new \system\MainController();
