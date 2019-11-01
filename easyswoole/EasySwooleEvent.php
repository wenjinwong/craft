<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Utility;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
        $tcp = ServerManager::getInstance()->addServer('tcp',4236);

        $tcp->set($tcp::onConnect,function (\swoole_server $server, int $fd, int $reactorId){
            Utility::pushAll("UDT at Fd : {$fd} connect");
        });

        $tcp->set($tcp::onReceive,function (\swoole_server $server, int $fd, int $reactor_id, string $data){
            Utility::pushAll("{$data}");
        });

        $tcp->set($tcp::onClose,function (\swoole_server $server, int $fd, int $reactor_id){
            Utility::pushAll("UDT disconnect at Fd : {$fd} ");
        });

        $register->add($register::onMessage,function (\swoole_websocket_server  $server, \swoole_websocket_frame $frame){
            Utility::sendAll($frame->data);
        });
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}