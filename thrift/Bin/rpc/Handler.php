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
        $result=['code'=>1000,'msg'=>'','data'=>'ok'];
        return json_encode($result);
    }


    /**
     * @param int $id
     * @return string
     */
    public function getUserInfoById($id)
    {
        // TODO: Implement getUserInfoById() method.
        $result=['code'=>1001,'msg'=>'','data'=>['name'=>'zhengdiao','age'=>21]];
        return json_encode($result);
    }
}