<?php

namespace api\Controllers;

use api\Controller;

class LoginController extends Controller
{
    public function index()
    {
        if(isset($_SESSION['admin']))$this->redirect('Admin');

        if (isset($this->protection->post)
            && isset($this->protection->post['login'])
            && isset($this->protection->post['password'])) {

            $adminsModel = $this->loader->loadModel('Admins');
            $logging = $adminsModel->loginAdmin($this->protection->post['login'], md5($this->protection->post['password']));
            if(!empty($logging)){
                $_SESSION['admin'] = [];
                $_SESSION['admin']['name'] = $logging[0]['name'];
                $this->redirect('Admin');
            }else{
                $this->create_error('Не верный логин или пароль','error');
            }
        }
        $this->smarty->display('Login.tpl');
    }

    public function logout(){
        unset($_SESSION['admin']);
        $this->redirect('Login');
    }
}