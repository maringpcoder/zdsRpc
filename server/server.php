<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/25
 * Time: 10:36
 */
ini_set('display_errors',"On");
error_reporting(E_ALL|E_STRICT);
//检查扩展是否安装
if(!extension_loaded('yaf')){
    exit('Please install yaf extension.'.PHP_EOL);
}
if(!extension_loaded('redis')){
    exit('Please install redis extension.'.PHP_EOL);
}
if(!extension_loaded('swoole')){
    exit('Please install swoole extension.'.PHP_EOL);
}
//该服务启动脚本必须在cli模式下，所以需要检查当前php运行模式
if(php_sapi_name()!='cli'){
    exit('Please use php cli model.'.PHP_EOL);
}
$cmd = '/usr/local/php/bin/php';
function  syncServer()
{
    echo (yield ['mysqlPool']) .PHP_EOL;
//    echo (yield ['redisPool']) .PHP_EOL;
//    echo (yield ['task']) .PHP_EOL;
//    echo (yield ['hProse']) .PHP_EOL;
}

//异步调用
function asyncCaller(Generator $gen)
{
    global $cmd;
    $task = $gen->current();
    $taskName = $task[0];
    if(isset($taskName)){
        switch ($taskName){
            case 'mysqlPool':
                foreach (glob(__DIR__.'/mysql/*.php') as $k =>$startUpFile){
                    exec($cmd.' '.$startUpFile);
                }
                echo "Mysql Pool Service Success......".PHP_EOL;
                $gen ->send("Mysql Pool Service Success!");
                asyncCaller($gen);
                break;
            case 'redisPool':
                foreach (glob(__DIR__.'/cache/*.php') as $k => $startUpFile){
                    exec($cmd.' '.$startUpFile);
                }
                echo "Cache Driver Pool Service Success......".PHP_EOL;
                $gen->send("Cache Driver Pool Service Success!");
                asyncCaller($gen);
                break;
            case 'task':
                foreach(glob(__DIR__.'/swoole/Task*.php') as $startUpFile) {
                    exec($cmd.' '.$startUpFile);
                }
                echo "Task Service start ....".PHP_EOL;//任务服务器服务
                $gen->send('Task Service start OK!');
                asyncCaller($gen);
                break;
            case 'hProse':
                foreach (glob(__DIR__.'/') as $startUpFile){
                    exec($cmd.' '.$startUpFile);
                }
                echo 'The hProse Rpc Service Start......'.PHP_EOL;
                $gen->send('The hProse Rpc Service Start!');
                asyncCaller($gen);
                break;
            default:
                $gen ->send('no method');
                break;
        }
    }
}
//argc??
if($argc == 2){
    $server_command = $argv;
    !in_array($server_command[1],['start','stop','restart']) && exit('Argv is not Correct'.PHP_EOL);
    switch ($server_command[1]){
        case 'start'://启动服务
            asyncCaller(syncServer());
            break;
        case 'stop'://停止服务
            exec("ps -ef|grep -E '".$cmd."|grep -v 'grep'| awk'{print $2}'|xargs kill -9 > /dev/null 2>&1 &");
            echo "Kill all process success.".PHP_EOL;
            break;
        case 'restart'://重启服务
            exec("ps -ef|grep -E '".$cmd."|grep -v 'grep'| awk'{print $2}'|xargs kill -9 > /dev/null 2>&1 &");
            echo "Kill all process success.".PHP_EOL;
            asyncCaller(syncServer());
            break;
        default:
            exit('This command is not support,try to use start|stop|restart'.PHP_EOL);
            break;
    }




}else{
    echo 'Please input Correct params example：'.PHP_EOL;
    exit('php server.php start|stop|restart'.PHP_EOL);
}

