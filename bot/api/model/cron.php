<?php

namespace bot\api\model;
include_once('bot/api/model.php');
use bot\api\model;

class cron extends model
{
    public function getCronToken()
    {
        $token = $this->getGUID();
        $sql = "DELETE FROM `system` WHERE `cron_bot_token` IS NOT NULL";
        mysqli_query($this->db,$sql);
        $sql = "INSERT INTO `system` (`id`,`cron_bot_token`) VALUES (NULL,'$token')";
        mysqli_query($this->db,$sql);
        return $token;
    }
    public function deleteCronToken()
    {
        $sql = "DELETE FROM 'system' WHERE `cron_bot_token` NOT NULL";
        $buf = mysqli_query($this->db,$sql);
    }

    private function getGUID() {
            $guid = '';
            $namespace = rand(11111, 99999);
            $uid = uniqid('', true);
            $data = $namespace;
            $data .= $_SERVER['REQUEST_TIME'];
            $data .= $_SERVER['REMOTE_ADDR'];
            $data .= $_SERVER['REMOTE_PORT'];
            $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
            $guid = substr($hash,  0,  8) . '-' .
                    substr($hash,  8,  4) . '-' .
                    substr($hash, 12,  4) . '-' .
                    substr($hash, 16,  4) . '-' .
                    substr($hash, 20, 12);
            return $guid;
        }
}