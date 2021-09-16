<?php
namespace bot\api\controller;
include($_SERVER['DOCUMENT_ROOT'] . '/bot/api/controller.php');
use bot\api\controller;

class message extends Controller
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
		$command = $modelMess->getCommandForAllMessages($text);

		if(!empty($this->data['new_chat_participant'])){$command = $modelMess->getCommandForNewChatParticipant($text);}

		if(empty($command)){$command = $modelMess->getCommand($text);}
		if(empty($command)){$command = $modelMess->getButtonsAsCommand($text);}
		if(!empty($command))
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
			$this->sendMessage('sendMessage',$this->data);
		}else
		{
			if($this->data['Message']['chat_type'] == 'private')
			{
				$this->data['Answer']['text'] = 'I don\'t understand you';
				$this->sendMessage('sendMessage',$this->data);
			}
		}

	}



}
?>