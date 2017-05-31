<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/28
 * Time: 23:33
 */
class ClientRpc {

    /**
     * 获取远程调用客户端
     * @param $clientName
     */
    public static function getClient($clientName)
    {
        switch ($clientName){
            case 'hPClient':
                break;
            case 'Thrift':
                break;
        }
    }
}