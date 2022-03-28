<?php
/**
 * Created by VlA:D */
include_once $GLOBALS['PTH'].'api/ApiController.php';
include($_SERVER['DOCUMENT_ROOT'].'/bot/messSender.php');
use bot\messSender;
class usersController extends ApiController
{
	public function index()
	{
		$model = $this->loadModel('users/users');
		$this->p($model->getUsers(),'users');
		$this->render('users/users');
	}
	public function login()
	{
		// echo '<pre>';
		// print_r($this->conf);
		// print_r($_SESSION);
		// echo '</pre>';
		if(!empty($_SESSION))
		{
			if(isset($_SESSION['token']))
			{
				$this->redirect('home/home');
			}
			$user = false;
			if(isset($_SESSION['log_pass']) and isset($_SESSION['log_login']))
			{
				if($_SESSION['log_pass'] == $this->conf['admin_pass'] and $_SESSION['log_login'] == $this->conf['admin_log'])
					{
						$user = true; 
					}
			}
			if($user)
			{
				unset($_SESSION['log_login']);
				unset($_SESSION['log_pass']);
				$_SESSION['user_name'] = $this->conf['admin_name'];
				$_SESSION['project_name'] = $this->conf['project_name'];
				$_SESSION['token'] = '12335';
				$_SESSION['display_setting_style'] = 'darkStyle';
				$_SESSION['display_setting_template'] = 'default';
				$this->redirect($_SESSION['log_route_0'].'/'.$_SESSION['log_route_1']);
			}
		}
		$this->conf['layout'] = 'login';
		$this->render('users/login');
	}

	public function send_message()
	{
		if (isset($this->post['telegram_id']) and isset($this->post['message'])) 
		{
			$mess_sender = new bot\messSender;
			$mess_sender->sendMessForAdm($this->post['telegram_id'],$this->post['message'],$this->conf['Telegram_api']);
			$this->redirect('users/');
		}
	}

	public function out()
	{
		unset($_SESSION['user_id']);
		unset($_SESSION['user_name']);
		unset($_SESSION['token']);
		$this->redirect('users/login');
	}
	
	public function create()
	{
		if(isset($this->post['submit']))
		{
			//$this->debug($this->post);
			if(!empty($this->post['user_name']) 
				and !empty($this->post['user_log'])
				and !empty($this->post['user_pass']))
			{
				$model = $this->loadModel('users/users');
				$usId  = $model->setNewUser($this->post['user_name'],$this->post['user_log'],$this->post['user_pass'],$this->post['user_role']);
				$this->redirect('users/&user_id='.$usId);
			}
		}
		$this->render('users/create');
	}


}
?>