<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/28
 * Time: 23:33
 */
class ClientRpc {
    /**
     * 生成远程调用客户端对象
     * @param string $clientName
     * @return HProseClient|RpcClient
     */
    public static function getClient($clientName='hPClient')
    {
        switch ($clientName){
            case 'hPClient':
                return HProseClient::getInstance();
                break;
            case 'Thrift':
                return new RpcClient();
                break;
        }
    }
}