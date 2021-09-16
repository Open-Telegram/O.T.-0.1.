<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/system/modules/telegram/telegram_c.php';
class Engine
{
	
	private $send;
	protected $data = [];
	function __construct($data)
	{
		$this->data = $data;
		$this->send = new telegrammSender;
	}
	
	protected function sendM()
	{
		$this->send->sendMessage('sendMessage',$this->data);
	}
	
	protected function debugT($data)
	{
		ob_start();
		print_r($data);
		echo PHP_EOL . '----------------------' . PHP_EOL;
		$out = ob_get_clean(); 
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/system/logs/telegram/telegram_debug.txt', $out, FILE_APPEND); 
	}
	
    public function getInlineKeyBoard($data)
    {
        $inlineKeyboard = array(
            "inline_keyboard" => $data,
        );
        return json_encode($inlineKeyboard);
    }
     
	public function getKeyBoard($data,$one_time_keyboard = true)
    {
        $keyboard = array(
            "keyboard" => $data,
            "one_time_keyboard" => $one_time_keyboard,
            "resize_keyboard" => true
        );
        return json_encode($keyboard);
    }
}
?>