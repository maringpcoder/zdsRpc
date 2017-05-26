<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/26
 * Time: 16:24
 */
class BaseModel extends Medoo {
    public $configObject;
    public $configArr;

    public function __construct($option=null) {

        $this ->getDbConfig();
        if(!$option){
            $option = [
                'database_type'=>$this->configArr['type'],
                'database_name' =>$this->configArr['name'],
                'server'=>$this->configArr['host'],
                'username'=>$this->configArr['user'],
                'password'=>$this->configArr['pwd']
            ];
        }
        parent::__construct($option);
    }

    public function getDbConfig()
    {
        $this->configObject =Yaf_Registry::get('config');
        $this->configArr=$this->configObject ->database->config->toArray();
    }
}