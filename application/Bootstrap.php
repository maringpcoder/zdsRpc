<?php
/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract{
    public $phpRunEnv;

    public function _initConfig() {
		//把配置保存起来
		$arrConfig = Yaf_Application::app()->getConfig();
		Yaf_Registry::set('config', $arrConfig);
		$debug = $arrConfig->debugError->toArray();
        ini_set('display_errors',$debug['display_errors']);
        ini_set('error_reporting',intval($debug['error_level']));
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
        $this ->phpRunEnv = php_sapi_name();
        if($this->phpRunEnv=='cli'){
            Yaf_Loader::import(APPLICATION_PATH.'/application/function/Functions.php');
        }
	}

	public function _initPlugin(Yaf_Dispatcher $dispatcher) {
		//注册一个插件
		$objSamplePlugin = new SamplePlugin();
		$dispatcher->registerPlugin($objSamplePlugin);
		//注册自动载入插件,函数或自己的类库文件
        if($this->phpRunEnv!='cli'){
            $autoLoadPlugin = new AutoloadPlugin();
            $dispatcher->registerPlugin($autoLoadPlugin);
        }
	}

	public function _initRoute(Yaf_Dispatcher $dispatcher) {
		//在这里注册自己的路由协议,默认使用简单路由
	}
	
	public function _initView(Yaf_Dispatcher $dispatcher){
		//在这里注册自己的view控制器，例如smarty,firekylin
	}

	public function _initCommonFunction(){

    }
}
