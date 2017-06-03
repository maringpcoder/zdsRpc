<?php
/**
 * 定义自动载入类库以及function的插件
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/27
 * Time: 16:28
 * @property Yaf_Config_Abstract $config
 */
class AutoloadPlugin extends Yaf_Plugin_Abstract
{
    public $config;
    public $fileload;

    public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        $this->config=Yaf_Application::app()->getConfig();
        if(isset($this->config->application->autofunction) && !empty($this->config->application->autofunction)){
            $funcs = explode(',',$this->config->application->autofunction);
            foreach ($funcs as $dir){
                $functionDirPath = APPLICATION_PATH.'/application/'.$dir;
                $functions = $this ->getList($functionDirPath);

                foreach ($functions as $file){
                    file_exists($file) && Yaf_Loader::import($file);
                }
            }
        }
    }

    /**
     * 此时路由一定正确完成, 否则这个事件不会触发
     * @param Yaf_Request_Abstract $request
     * @param Yaf_Response_Abstract $response
     */
    public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    }

    public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    }

    public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    }

    public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    }

    public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    }

    /**
     * 获取目录下所有的php文件
     * @param $path
     * @return array
     */
    private function getList($path){
        static  $fileArr=[];
        foreach (glob($path.'/*') as $f){
            if(strpos($f,'.php')){
                array_push($fileArr , $f);
            }else{
                $this->getList($f);
            }
        }
        return $fileArr;
    }

}