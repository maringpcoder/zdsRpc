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
	    $redis = new Redis();
        $redis ->pconnect('192.168.3.176',6379);

//        new RedisCache();
//        $configObjecter =Yaf_Registry::get('config')->Cache->toArray()['redis'];
        var_dump(Yaf_Registry::get('config')->application->log);
        //var_dump(Yaf_Registry::get('config')->application->log->toArray());
        exit();


        $configObjecter =Yaf_Registry::get('config');
        var_dump($configObjecter);
        $cf =$configObjecter->database->config->toArray();
        var_dump($cf);exit();
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
        $dbClient=new mysql_dbclient();
        //print_r($data);
        for ($i=0; $i <10 ; $i++) {
            //$dbClient->query("INSERT INTO user(name) VALUES('$i')");
            $dbClient ->insert('user',['name'=>"zhengdiao".$i,'age'=>23]);
            //echo "INSERT INTO user(name) VALUES('$i')";
        }
        $data=$dbClient->query("select *  from user");
        echo  $data;
//        $dbClient->close();
//        print_r($data);
        exit;
    }

    public function getUserAction()
    {
        $userModel = new UserModel();
        echo json_encode($userModel ->getUserById());
    }

    public function hproseAction(){
        echo HProseClient::getInstance()->getAllUser();
    }

    public function getUsAction(){
        $us = new UserModel();
        var_dump($us ->getUserById());
    }

    public function getAgentIdAction()
    {

          echo HProseClient::getInstance()->getAgentId();
    }

    public function updateUserAction()
    {
        echo HProseClient::getInstance()->updateUser(['name'=>'孙雨梅','age'=>90],['id'=>1]);
    }

    public function userListAction()
    {
        $sql = 'select * from `user` ';
        echo HProseClient::getInstance()->getUserByPage($sql,2,2);
    }

}
