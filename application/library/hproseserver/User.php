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
    public function getAllUser($page,$pageSize)
    {
        $page = intval($page) <= 1 ? 1 : intval($page);
        $pageSize = intval($pageSize) ? intval($pageSize) : 20;
        $startNum = ($page - 1) * $pageSize;
        $data =  hProseServer_Factory::NewDB()->select('users',['user_name','password','type','true_name'],['LIMIT'=>[$startNum,$pageSize]]);
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
