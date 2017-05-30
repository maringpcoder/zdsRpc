<?php
require_once dirname(__DIR__) . '/hprose/lib/Server.php';
/**
 * rpc服务端,提供方法,函数,类的公开方法，类的静态方法发布,对象等其他操作
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/25
 * Time: 0:32
 * @property Server  $_server
 */

class rpcServer
{
    private $_server = null;

    public function __construct()
    {
        $process = new swoole_process([$this ,'hProseServerCall'],false,true);
        $process->start();
        echo 'FID:'.posix_getpid().PHP_EOL;//获取主进程id
        swoole_process::daemon(false);
        swoole_process::wait();
    }

    public function hProseServerCall(swoole_process $worker)
    {
        define('APPLICATION_PATH', dirname(__DIR__));
        define('MYPATH', dirname(APPLICATION_PATH));
        $application = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
        $application->bootstrap();
        $config_obj = Yaf_Registry::get("config");
        $hProseConfig = $config_obj->hprose->toArray();
        $this->_server = new Server("tcp://" . $hProseConfig['ServerIp'] . ":" . $hProseConfig['port']);
        $this->_server->setErrorTypes(E_ALL);
        $this->_server->setDebugEnabled();//打开调试开关
        $this->release();
        $this->_server->start();
    }

    /**
     * 方法发布
     */
    private function release()
    {
        $this ->releaseStaticMethods();
        $this ->releaseObjMethods();
        $this ->releaseFunction();
    }

    /**
     * 发布类的静态method
     */
    public function releaseStaticMethods()
    {

    }

    /**
     * 发布对象的公开method
     */
    protected function releaseObjMethods()
    {
        $methodConfig = $this ->getPrepareToReleaseConfig();
        foreach ($methodConfig as $methodArr=>$obj){
            $this ->_server ->addMethods(explode(',',$methodArr),$obj);
        }
    }

    /**
     * 新加类或者对象的公开方法配置
     */
    protected function getPrepareToReleaseConfig()
    {
        return $methodsObjectItems = array(
            "getAllUser,editUser" =>new hProseServer_User(),//发布hproseServer_User类中的方法配置
            "getAgentId" => new hProseServer_Agent()
            //serialize(new hProseServer_Agent())=>[]
        );
    }

    /**
     * 发布函数
     */
    protected function releaseFunction()
    {

        $this->_server->addFunction('testFc');
    }


    public static function run()
    {
        new self();
    }
}

rpcServer::run();
