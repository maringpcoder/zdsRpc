<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/26
 * Time: 16:24
 */
class BaseModel{
    public $configObject;
    public $configArr;
    public $db;

    public function __construct($option=null)
    {

        $this ->getDbConfig();
        if(!$option){
            $option = [
                'database_type'=>$this->configArr['type'],
                'database_name' =>$this->configArr['name'],
                'server'=>$this->configArr['host'],
                'username'=>$this->configArr['user'],
                'password'=>$this->configArr['pwd'],
                'charset'=>$this->configArr['charset'],
                'port' =>$this->configArr['port'],
            ];
        }
        $this ->db = new Medoo($option);
    }

    private function getDbConfig()
    {
        $this->configObject =Yaf_Registry::get('config');
        $this->configArr=$this->configObject ->database->config->toArray();
    }
}