<?php
/**
 * Created by VlAD for Cigitus company 24.10.2020
 */
include_once $GLOBALS['PTH'].'api/ApiController.php';
class homeController extends ApiController
{
	function index()
	{
		$this->home();
	}
	function home()
	{

		// $captcha = $this->loadGSCP('captcha','Tcaptcha');
		$homeModel = $this->loadModel('home/home');
		$this->p($homeModel->getCountMessages(),'messagesCount');
		$this->p($homeModel->getCountUsers(),'usersCount');
		$this->p($homeModel->getCountButtons(),'buttonsCount');
		$this->p($homeModel->getCountCommands(),'commandsCount');
		$this->render('home/home');
	}
}
?>