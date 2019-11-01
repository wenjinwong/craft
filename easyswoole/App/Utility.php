<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-05-02
 * Time: 12:04
 */

namespace App;


use EasySwoole\EasySwoole\ServerManager;

class Utility
{
    public static function getWsList():array
    {
        $rer = [];
        $start_fd = 0;
        while(true)
        {
            $conn_list = ServerManager::getInstance()->getSwooleServer()->getClientList($start_fd, 10);
            if ($conn_list === false or count($conn_list) === 0)
            {
                break;
            }
            $start_fd = end($conn_list);
            foreach($conn_list as $fd)
            {
                if(isset(ServerManager::getInstance()->getSwooleServer()->getClientInfo($fd)['websocket_status'])){
                    array_push($rer,$fd);
                }
            }
        }
        return $rer;
    }

    public static function pushAll(string $data)
    {
        $list = self::getWsList();
        foreach ($list as $fd){
            ServerManager::getInstance()->getSwooleServer()->push($fd,$data=bin2hex($data));
        }
    }

    public static function getTcpList()
    {
        $rer = [];
        $start_fd = 0;
        while(true)
        {
            $conn_list = ServerManager::getInstance()->getSwooleServer()->getClientList($start_fd, 10);
            if ($conn_list === false or count($conn_list) === 0)
            {
                break;
            }
            $start_fd = end($conn_list);
            foreach($conn_list as $fd)
            {
                if(ServerManager::getInstance()->getSwooleServer()->getClientInfo($fd)['server_port'] == 4236){
                    array_push($rer,$fd);
                }
            }
        }
        return $rer;
    }

    public static function sendAll(string $data)
    {
        $list = self::getTcpList();
        foreach ($list as $fd){
            ServerManager::getInstance()->getSwooleServer()->send($fd,$data);
        }
    }
}