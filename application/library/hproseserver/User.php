<?php
/**
 * 获取/处理用户信息
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/27
 * Time: 13:36
 */
class hProseServer_User {

    /**
     *
     * @return array|bool
     */
    public function getAllUser()
    {
        $data =  hProseServer_DBfactory::New()->select('user','*');
        return writeToJson(1000,'',$data);
    }
    public function editUser($data)
    {
        return 'RPC editUser methods!';
    }

    /**
     * 获取用户数据,分页
     */
    public function getUserPage()
    {

    }

}
