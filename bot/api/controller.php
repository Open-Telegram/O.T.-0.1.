<?php
namespace bot\api;
include('bot/api/load.php');
include('bot/messSender.php');
use bot\messSender;

class controller extends messSender
{

	public $load;
	function __construct()
	{
		$this->load = new load();
	}

	function debug($data)
	{
        ob_start();
        print_r($data);
        $out = ob_get_clean(); 
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/messageDEB.txt', $out, FILE_APPEND); 
	}
    public function deleteAfterMess($lastMessId,$nowMessId)
    {
        for ($i=$lastMessId; $i < $nowMessId ; $i++) { 
            $this->data['Message']['id'] = $i;
            $this->debug($this->data['Message']['id']);
            $this->sendMessage('deleteMessage',$this->data);
        }
    }

    public function getText($text)
    {
        $text1 = explode(' ',$text);
        foreach($text1 as $t)
        {
            if($t == 'SELECT' or $t == 'select' or $t == 'DELETE' or $t == 'delete' or $t == 'UPDATE' or $t == 'update')
            {
                $this->sendError(1);
                
            }
        }
        //if($arr == true){$text = explode("_", htmlspecialchars($text));return $text;}

        return htmlspecialchars($text);
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
    public function sendError($error = 0)
    {
        // if($this->conf['get_Error'] == 1)
        // {basename(__FILE__)}
        switch ($error) {
            case 0:
                $this->data['Answer']['text'] = 'Непредвиденная ошибка. Обратитесь к администратору по кнопке "Информация"';
                $this->sendMessage('sendMessage',$this->data);
                die;
                break;
            case 1:
                $this->data['Answer']['text'] = 'Произведена остановка в связи с ошибкой. Если вы не совершали никаких противоправных действий - обратитесь к администратору по кнопке "Информация".';
                $this->sendMessage('sendMessage',$this->data);
                die;
                break;
            
            default:
                # code...
                break;
        }
    }
}
?>