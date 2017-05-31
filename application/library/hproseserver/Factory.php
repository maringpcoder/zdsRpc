<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/30
 * Time: 22:50
 */
class hProseServer_Factory
{
    /**
     * 创建数据库对象
     * @param $type
     * @return Medoo|Mysql_MysqlUsePool
     */
    public static  function NewDB($type='medoo')
    {
        switch ($type){
            case 'pool'://使用池
                $db = new Mysql_MysqlUsePool();
                return $db;
                break;
            case 'medoo'://使用Medoo
                $baseModel = new BaseModel();
                $db = $baseModel ->db;
                return $db;
                break;
        }
    }

    /**
     * 创建链接对象
     * @param string $type
     * @return Cache_RedisCache|Cache_RedisPool|null
     */
    public static function NewCache($type='')
    {
        switch ($type){
            case 'pool':
                return Cache_RedisCache::getInstance();
                break;
            case 'redis':
                return new Cache_RedisPool();
                break;
        }
    }
}