<?php
require_once dirname(__DIR__) . '/hprose/lib/Server.php';
/**
 * rpc PHP服务端,提供方法,函数,类的公开方法，类的静态方法发布,对象等其他操作,简单封装
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/25
 * Time: 10:32
 * @property Server  $_server
 */

class rpcServer
{
    private $_server = null;

    public function __construct()
    {
        $process = new swoole_process([$this ,'hProseServerCall'],true);
//        echo 'FID:'.posix_getpid().PHP_EOL;//获取主进程id
        $process->start();
        swoole_process::daemon(true);
        swoole_process::wait();//可能需要开锁
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
        //$this->_server->setDebugEnabled();//打开调试开关
        Yaf_Loader::getInstance()->registerLocalNamespace(['hproseserver','cache','mysql']);
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
    protected function releaseStaticMethods()
    {

    }

    /**
     * 发布对象的公开method
     */
    protected function releaseObjMethods()
    {
        $methodConfig = $this ->getObjPrepareToReleaseConfig();
        foreach ($methodConfig as $methodArr=>$obj){
            $this ->_server ->addMethods(explode(',',$methodArr),$obj);
        }
    }

    /**
     * 发布函数
     */
    protected function releaseFunction()
    {
        $this->_server->addFunction('testFc');
    }

    /**
     * 新加类或者对象的公开方法配置
     */
    protected function getObjPrepareToReleaseConfig()
    {
        //方法名字符串与对象的映射关系  'public_methodsA,public_methodsB'=>$object
        return $methodsObjectItems = array(
            "getAllUser,editUser" =>new hProseServer_User(),//发布hproseServer_User类中的方法配置
            "getAgentId,updateUser,userList" => new hProseServer_Agent()
        );
    }

    public static function run()
    {
        new self();
    }
}

rpcServer::run();
