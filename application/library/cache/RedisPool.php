<?php
/**
 * 使用redis线程池操作redis命令
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/31
 * Time: 14:19
 */
class Cache_RedisPool extends Cache_RedisClient
{
    protected static $instance=null;

    public function returnResult($option)
    {
        $cmdStr = $this ->commandGenorate($option);
        if(!$cmdStr){
            return 'the command is not support';
        }
        $this ->client->send($cmdStr);
        return $this ->client->recv();
    }

    /**
     * @param $option ['cmd'=>'hGet','field']
     * @return string
     */
    protected function commandGenorate($option)
    {
        switch ($option['cmd']){
            case 'hGet':
                $cmdStr = "hGet {$option['key']} {$option['field']}";
                break;
            case 'hSet':
                $cmdStr = "hSet {$option['key']} {$option['value']}";
                break;
            case 'lpush':
                $cmdStr = "lpush {$option['key']} {$option['value']}";
                break;
            case 'rpush':
                $cmdStr = "rpush {$option['key']} {$option['value']}";
                break;
            default:
                $cmdStr = false;
        }
        return $cmdStr;
    }

    public static function getInstance()
    {
        if (!(self::$instance instanceof Cache_RedisPool)) {
            self::$instance = new Cache_RedisPool();
        }
        return self::$instance;
    }
}