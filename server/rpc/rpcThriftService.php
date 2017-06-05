<?php

/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/6/4
 * Time: 0:18
 */
class  rpcThriftService
{

    public function __construct()
    {
        $process = new swoole_process([$this,'rpcServerCall'],false,true);
        $process ->start();
        swoole_process::daemon(true);
        swoole_process::wait();
    }

    public function rpcServerCall(swoole_process $worker)
    {

        define('APPLICATION_PATH', dirname(dirname(__DIR__)));
        define('THRIFT_DIR_PATH',APPLICATION_PATH."/thrift");
        require_once THRIFT_DIR_PATH. "/Thrift/ClassLoader/ThriftClassLoader.php";

        $loader = new \Thrift\ClassLoader\ThriftClassLoader();
        $loader ->registerNamespace('Thrift',THRIFT_DIR_PATH);//加载Thrift
        $loader ->registerNamespace('swoole',THRIFT_DIR_PATH);
        $loader ->registerNamespace('Bin',THRIFT_DIR_PATH);
        $loader ->registerDefinition('Bin',THRIFT_DIR_PATH);//加载类文件和数据定义
        $loader->register();

        define('MYPATH', dirname(APPLICATION_PATH));
        $application = new Yaf_Application(APPLICATION_PATH. "/conf/application.ini");
        $application->bootstrap();

        $config_obj=Yaf_Registry::get("config");
        $rpc_config=$config_obj->rpc->toArray();

        define('SERVERIP',$rpc_config['ServerIp']);
        define('SERVERPORT',$rpc_config['port']);
        define('SERVERHOST',$rpc_config['host']);

        $service = new Bin\rpc\Handler();
        $processor = new Bin\rpc\rpcProcessor($service);

        $socketTransport = new Thrift\Server\TServerSocket(SERVERIP,SERVERPORT);

        $out_factory = $in_factory = new Thrift\Factory\TFramedTransportFactory();
        $out_protocol = $in_protocol = new Thrift\Factory\TBinaryProtocolFactory();

        $server = new swoole\RpcServer($processor, $socketTransport, $in_factory, $out_factory, $in_protocol, $out_protocol);
        $server->serve();

    }

    public  static function run()
    {
        new self();
    }
}

rpcThriftService::run();