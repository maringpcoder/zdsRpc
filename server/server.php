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

/**
 * 异步任务生成器
 */
function  syncServer()
{
    echo (yield ['thrift']) .PHP_EOL;
    echo (yield ['mysqlPool']) .PHP_EOL;
//todo redis线程池服务    echo (yield ['redisPool']) .PHP_EOL;
//todo task服务,检测各服务的状况,并实现重连    echo (yield ['task']) .PHP_EOL;
    echo (yield ['hProse']) .PHP_EOL;
}
$stashStartUpFile=array();

//argc??
if($argc == 2){
    serviceHandler($argv,$cmd);
}else{
    echo "please input option number:".PHP_EOL."1)start ".PHP_EOL."2)stop ".PHP_EOL."3)restart".PHP_EOL;
    $sh = fgetc(STDIN);
    $cmdOption = ['start'=>1,'stop'=>2,'restart'=>3];
    if(!in_array($sh,$cmdOption)){
        echo 'Please input Correct params example：'.PHP_EOL;
        exit('php server.php start|stop|restart'.PHP_EOL);
    }
    serviceHandler([1=>array_search($sh,$cmdOption)],$cmd);
}

//异步调用
function asyncCaller(Generator $gen)
{
    global $cmd;
    $task = $gen->current();
    $taskName = $task[0];
    if(isset($taskName)){
        switch ($taskName){
            case 'thrift':
                //todo thrift 接入
                break;
            case 'mysqlPool':
                foreach (glob(__DIR__.'/mysql/*.php') as $k =>$startUpFile){
                    exec($cmd.' '.$startUpFile);
                }
                echo "Mysql Pool Service Ready to start ........".PHP_EOL;
                $gen ->send("Mysql Pool Service Start Successful!");
                asyncCaller($gen);
                break;
            case 'redisPool':
                foreach (glob(__DIR__.'/cache/*.php') as $k => $startUpFile){
                    exec($cmd.' '.$startUpFile);
                }
                echo "Cache Driver Pool Service Success......".PHP_EOL;
                $gen->send("Cache Driver Pool Service Successful!");
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
                foreach (glob(__DIR__.'/hprose/*.php') as $startUpFile){
                    exec($cmd.' '.$startUpFile);
                }
                echo 'The hProse Rpc Service Ready to Start ........'.PHP_EOL;
                $gen->send('The hProse Rpc Service Start Successful!');
                asyncCaller($gen);
                break;
            default:
                $gen ->send('no method');
                break;
        }
    }
}


function serviceHandler($argv,$cmd)
{
    $server_command = $argv;
    !in_array($server_command[1],['start','stop','restart']) && exit('Argv is not Correct'.PHP_EOL);
    switch ($server_command[1]){
        case 'start'://启动服务
            asyncCaller(syncServer());
            break;
        case 'stop'://停止服务
            killAllProcess($cmd);
            break;
        case 'restart'://重启服务
            killAllProcess($cmd);
            asyncCaller(syncServer());
            break;
        default:
            exit('This command is not support,try to use start|stop|restart'.PHP_EOL);
            break;
    }
}


function killAllProcess($cmd)
{
    $stashStartUpFile=array();
    foreach (glob(__DIR__.'/mysql/*.php') as $k =>$startUpFile){
        array_push($stashStartUpFile,$startUpFile);
    }
    foreach (glob(__DIR__.'/hprose/*.php') as $startUpFile){
        array_push($stashStartUpFile,$startUpFile);
    }
    call_user_func(function($startUpFile)use($cmd){
        $stasTask = count($startUpFile);
        for ($n=0;$n<$stasTask;$n++){
            echo "Ready to kill $cmd {$startUpFile[$n]}".PHP_EOL;
            exec("ps -ef|grep -E '".$cmd." ".$startUpFile[$n]."' |grep -v 'grep'| awk '{print $2}'|xargs kill -9 > /dev/null 2>&1 &");
        }
        echo "Kill all process success.".PHP_EOL;
    },$stashStartUpFile);
}
exit();