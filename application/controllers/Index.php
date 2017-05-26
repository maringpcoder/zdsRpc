<?php
/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends Yaf_Controller_Abstract {

	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/ApiService/index/index/index/name/root 的时候, 你就会发现不同
     */
	public function indexAction($name = "Stranger") {
        $configObjecter =Yaf_Registry::get('config');
        var_dump($configObjecter);
        $cf =$configObjecter->database->config->toArray();
        var_dump($cf);
		//1. fetch query
		$get = $this->getRequest()->getQuery("get", "default value");

		//2. fetch model
		$model = new SampleModel();

		//3. assign
		$this->getView()->assign("content", $model->selectSample());
		$this->getView()->assign("name", $name);

		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        return TRUE;
	}

	public function dbTestAction()
    {
        ini_set("display_errors", "On");
        error_reporting(E_ALL | E_STRICT);
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
        $dbClient=new mysql_dbclient;

        //print_r($data);
        for ($i=0; $i <10 ; $i++) {
            //$dbClient->query("INSERT INTO user(name) VALUES('$i')");
            $dbClient ->insert('user',['name'=>'']);
            //echo "INSERT INTO user(name) VALUES('$i')";
        }

        $data=$dbClient->query("select * from user");
        $dbClient->close();
        print_r($data);
        exit;
    }

    public function getUserAction()
    {
        ini_set("display_errors", "On");
        error_reporting(E_ALL | E_STRICT);
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);

        $userModel = new UserModel();
        echo json_encode($userModel ->getUserById());
    }


}