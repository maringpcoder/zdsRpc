<?php

/**
 * 获取/处理用户信息
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/27
 * Time: 13:36
 */
class hProseServer_Agent {

    public function getAgentId()
    {
      $data =  $this ->select('user','*');
      return writeToJson(1000,'',$data);
    }
}
