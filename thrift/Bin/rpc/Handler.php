<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/6/4
 * Time: 0:49
 */
namespace Bin\rpc;

class Handler implements rpcIf
{

    /**
     * @param \Bin\rpc\Message $msg
     * @return int
     */
    public function sendMessage(\Bin\rpc\Message $msg)
    {

        // TODO: Implement sendMessage() method.
        return RetCode::PARAM_ERROR;

    }
}