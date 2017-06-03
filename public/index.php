<?php
define('APPLICATION_PATH', dirname(dirname(__FILE__)));
$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");
Yaf_Loader::getInstance()->registerLocalNamespace(['hproseserver','mysql','cache']);
$application->bootstrap()->run();

?>
