<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/31
 * Time: 15:15
 */
class  Log
{
    protected $_fs =null;
    protected $_log_path=null;
    public function __construct()
    {
        $this ->_log_path = Yaf_Registry::get('config')->application->log;
        $this ->detection();
    }

    protected function __destruct(){
        if(is_resource($this->_fs)){
            fclose($this->_fs);
        }
    }

    public static function getInstance()
    {
        static $self = null;
        if(is_null($self)){
            $self = new self();
        }
        return $self;
    }

    /**
     * 检查日志是否可写
     */
    public function detection()
    {
        if(file_exists($this->_log_path.'log.lock') && $this ->_fs){
            fclose($this->_fs);
            $this->_fs =false;
        }elseif (!file_exists($this->_log_path.'log.lock')&& !$this->_fs){
            $this->_fs = @fopen($this->_log_path.'log.txt','w');
        }
    }

    /**
     * 打印
     * @param string $log
     */
    public function println($log){
        if($this->_fs){
            fwrite($this->_fs, date('Y-m-d H:i:s')."\t\t".$log."\n");
        }
    }
}