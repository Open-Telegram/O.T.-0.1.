<?php
/**
 * Created by VlAD for Cigitus company 24.10.2020
 */
include_once $GLOBALS['PTH'].'api/ApiController.php';
class modulesController extends ApiController
{
	public function index()
	{
		$modulesModel = $this->loadModel('modules/modules');
		// $commModel = $this->loadModel('commands/commands');
		// $buttonsModel = $this->loadModel('buttons/buttons');
		$modules = [];
		$functions = $modulesModel->get_all_functions();
		if($functions)
		{
			foreach ($functions as $function) 
			{
				$modules[$function['module_name']][] = $function;
			}
		}
		// $this->p($functions,'functions');
		$this->p($modules,'modules');
		// $this->debug($modules);
		$this->render('modules/modules');
		// $this->debug($modulesModel->get_all_functions());
	}
	public function install()
	{
		$array = [
			'action'    	=> 'getModules',
			'password'      => $this->conf['key_password'],
			'email' 		=> $this->conf['admin_email'],
			'module_id' 	=> 1
		];		
		 	
		 
		$modules = $this->getJson($array);
		$this->p($modules,'modules');
		$this->render('modules/install');
	}

	public function delete()
	{
		if(isset($this->get['name']) and !empty($this->get['name']))
		{
			$modulesModel = $this->loadModel('modules/modules');
			$modulesModel->deleteModule($this->get['name']);
			$this->redirect('modules/');
		}
	}

	public function download()
	{
		if(isset($this->get['module_id']) and !empty($this->get['module_id']))
		{
			$array = [
				'action'    	=> 'getModuleUrls',
				'password'      => $this->conf['key_password'],
				'email' 		=> $this->conf['admin_email'],
				'module_id' 	=> $this->get['module_id']
			];		
			 
			$moduleKeys = $this->getJson($array);
			if(!empty($moduleKeys) and !isset($moduleKeys['error']))
			{	
				foreach ($moduleKeys as $key => $moduleKey) 
				{
					$array_file = [
						'action'    	=> 'getModuleFile',
						'password'      => $this->conf['key_password'],
						'email' 		=> $this->conf['admin_email'],
						'module_id' 	=> $this->get['module_id'],
						'key' 			=> $moduleKey['key'],
					];
					$file = $this->getFile($array_file);
					$path_arr = explode('/', $moduleKey['path']);

					switch ($path_arr[0]) 
					{
						case 'controller':
							if(!isset($path_arr[2]))
							{
								$name = $path_arr[1];
								file_put_contents($GLOBALS['BOT_CONTROLLER_MODULES'].$name.'.php', $file);
							}else
							{
								unset($path_arr[0]);
								$name = array_pop($path_arr);
								$path = '';
								foreach ($path_arr as $key => $p) 
								{
									$path .= $path.'/';
								}
								file_put_contents($GLOBALS['BOT_CONTROLLER_MODULES'].$path.$name.'.php', $file);
							}
						break;
						case 'model':
							if(!isset($path_arr[2]))
							{
								$name = $path_arr[1];
								file_put_contents($GLOBALS['BOT_MODEL_MODULES'].$name.'.php', $file);
							}else
							{
								unset($path_arr[0]);
								$name = array_pop($path_arr);
								$path = '';
								foreach ($path_arr as $key => $p) 
								{
									$path .= $path.'/';
								}
								file_put_contents($GLOBALS['BOT_MODEL_MODULES'].$path.$name.'.php', $file);
							}
						break;
						case 'botcron':
							if(!isset($path_arr[2]))
							{
								$name = $path_arr[1];
								file_put_contents($GLOBALS['BOT_BOTCRON_MODULES'].$name.'.php', $file);
							}else
							{
								unset($path_arr[0]);
								$name = array_pop($path_arr);
								$path = '';
								foreach ($path_arr as $key => $p) 
								{
									$path .= $path.'/';
								}
								file_put_contents($GLOBALS['BOT_BOTCRON_MODULES'].$path.$name.'.php', $file);
							}
						break;
					}
				}
				$modulesModel = $this->loadModel('modules/modules');
				$modulesModel->setNewModule($this->get['module_id'],$name);	
			}

			$this->redirect('modules/');
			die();
		}
		$this->redirect('modules/install');
	}

	public function buy()
	{
		$this->redirect('modules/');
	}

	private function getJson($data)
	{
			$ch = curl_init('https://api.open-telegram.ru/');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$html = curl_exec($ch);
			curl_close($ch);
			$ret = json_decode($html,true);
			if(!isset($ret['redirect']))
			{
				return $ret;
			}else
			{
				$this->redirect($ret['redirect']);
				die();
			}
	}

	private function getFile($data)
	{
			$ch = curl_init('https://api.open-telegram.ru/');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$html = curl_exec($ch);
			curl_close($ch);
			 
			return htmlspecialchars_decode($html);
	}
}
?>