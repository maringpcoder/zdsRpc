<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/31
 * Time: 14:19
 */
class Cache_RedisPool extends Cache_RedisClient
{
    public function hGet($option)
    {
        $cmdStr = $this ->commandGenorate($option);
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
        }
        return $cmdStr;
    }
}