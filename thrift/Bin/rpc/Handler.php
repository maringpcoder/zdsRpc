<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/6/4
 * Time: 14:49
 */
namespace Bin\rpc;

class Handler implements rpcIf
{


    /**
     * @param \Bin\rpc\Message $msg
     * @return string
     */
    public function sendMessage(\Bin\rpc\Message $msg)
    {
        // TODO: Implement sendMessage() method.
        return 'this is ferformance framework stating!';
    }
}