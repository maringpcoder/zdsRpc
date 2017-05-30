<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/30
 * Time: 22:50
 */
class hProseServer_DBfactory
{
    /**
     * @param $type
     * @return Medoo|Mysql_MysqlUsePool
     */
    public static  function New($type='medoo')
    {
        switch ($type){
            case 'pool':
                $db = new Mysql_MysqlUsePool();
                return $db;
                break;
            case 'medoo':
                $baseModel = new BaseModel();
                $db = $baseModel ->db;
                return $db;
                break;
        }
    }
}