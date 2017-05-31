<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/28
 * Time: 23:41
 */
class ThriftClient
{
    public static $instance;
    public  $client=null;

    public function __construct()
    {
        //todo  创建客户端
    }

    public static function getInstance()
    {
        if (!(self::$instance instanceof hproseClient)) {
            self::$instance = new hproseClient();
        }
        return self::$instance;
    }
}