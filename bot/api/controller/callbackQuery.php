<?php
namespace bot\api\controller;
include($_SERVER['DOCUMENT_ROOT'] . '/bot/api/controller.php');
use bot\api\controller;
/**
 * 
 */
class callbackQuery extends Controller
{
	public $data;
	public $conf;
	function __construct($data,$conf)
	{
		parent::__construct();
		$this->data = $data;
		$this->conf = $conf;
		$modelMess = $this->load->load('model_message');

		$text = $this->getText($this->data['Message']['text']);
		$command = $modelMess->getCommand($text);
		if(empty($command)){$command = $modelMess->getButtonsAsCommand($text);}
		if(!empty($command))
		{
			$this->s($command);
		}else
		{
			$text = explode('|', $text);
			$text = $text[0];
			$command = $modelMess->getCommand($text);
			if(empty($command)){$command = $modelMess->getButtonsAsCommand($text);}

			if(!empty($command))
			{
				$this->s($command);
			}else
			{
				$this->data['Answer']['text'] = 'Не понимаю вас';
				$this->sendMessage('sendMessage',$this->data);
			}
		}
	}

	private function s($command)
	{
		$method = (string)$command['function_name'];
		$module_path = "bot\api\controller\modules\\".$command['module_name'].'Controller';
		include_once($_SERVER['DOCUMENT_ROOT'] . "/bot/api/controller/modules/".$command['module_name'].'.php');

		$command['return_data'] = json_decode($command['return_data'],true);
		$module = new $module_path($this->data,$this->conf,$command,$this->load);
		$moduleInfo = $module->info();
		$this->data = $module->$method();
		if(!empty($this->data['send']))
		{
			foreach ($this->data['send'] as $action => $data) 
			{
				$this->sendMessage($action,$data);
			}
		}
		// $this->debug($this->data);
		$this->sendMessage('sendMessage',$this->data);
	}
}
?>