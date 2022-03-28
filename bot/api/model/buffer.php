<?php

namespace bot\api\model;
include_once($_SERVER['DOCUMENT_ROOT'] . '/bot/api/model.php');
use bot\api\model;

class buffer extends model
{
    public function setBuffer($key,$data)
    {
        $buffer = $this->getBuffer($key);
        if(is_array($data))
        {
            $data = json_encode($data);
        }
        if(empty($buffer))
        {
            $sql = "INSERT INTO `buffer`(`buffer_id`, `buffer_data`, `buffer_key`) VALUES (NULL,'$data','$key')";
            mysqli_query($this->db,$sql);
        }else
        {
            $sql = "UPDATE `buffer` SET `buffer_data` = '$data'";
            mysqli_query($this->db,$sql);
        }
    }


    public function getBuffer($key)
    {
        $sql = "SELECT * FROM `buffer` WHERE `buffer_key` = '$key'";
        $buf = mysqli_query($this->db,$sql);
        $buffer_data = mysqli_fetch_assoc($buf);
        $buffer_data = json_decode($buffer_data['buffer_data'],true);
        return $buffer_data;
    }
    public function deleteBuffer($key)
    {
        $sql = "DELETE FROM `buffer` WHERE `buffer_key` = '$key'";
        $buf = mysqli_query($this->db,$sql);
    }
}