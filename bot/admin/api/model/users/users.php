<?php
/**
 * Created by VlAD for Cigitus company 24.10.2020
 */
include_once $GLOBALS['PTH'].'api/ApiModel.php';
class usersModel extends ApiModel
{

	public function isLogining($pass,$login)
	{
		$users = $this->dbSelect('users',['password'=>md5($pass),'login'=>$login]);
		if(!empty($users))
		{
			return $users[0];
		}else{return false;}
	}

	public function getUsers()
	{
		$users = $this->dbSelect('users');
		if(!empty($users))
		{
			return $users;
		}else{return false;}
	}
	public function setNewUser($name,$login,$pass,$role)
	{
		$getGUID = $this->getGUID();
		$pass = md5($pass);
		$users = $this->dbSql("INSERT INTO `users` (`user_id`, `name`, `password`, `login`, `role`, `telegram_id`) VALUES ('$getGUID', '$name', '$pass', '$login', '$role', '');");
		$usId = $this->getLastInserId();
		return $usId;
	}

	public function changeStyle($userId,$styleName)
	{
		$this->dbSql("UPDATE `users` SET `display_setting_style` = '$styleName' WHERE `users`.`user_id` = '$userId';");
		$_SESSION['display_setting_style'] = $styleName;
	}

	public function getManager()
	{
		$users = $this->dbSelect('users',['user_id'=>'43b39ff0-ec8d-448f-ad02-46a2ce7ad41a'],'*');
		return $users[0];
	}

}
?>