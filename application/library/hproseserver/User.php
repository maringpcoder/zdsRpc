<?php
/**
 * 获取/处理用户信息
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/27
 * Time: 13:36
 */
class hProseServer_User
{
    const USERS  = 'users'; // 用户表
    const USERS_AGENT = 'users_agent'; // 代理商表
    const USERS_NETBAR = 'users_netbar'; // 网吧表
    const USERS_AGENT_PERSON = 'users_agent_person'; // 代理商员工表
    const USERS_NETBAR_PERSON = 'users_netbar_person'; // 网吧员工表
    const USERS_ADMIN = 'users_admin'; // 用户管理员表
    const PURSE_ACCOUNT = 'purse_account'; // 电子钱包表
    const CODE_VERIFY = 'code_verify'; // 验证码表
    const TSYSAREA = 'tSysArea'; // 地区表
    const USERS_LOGIN_LOG = 'users_login_log'; // 用户登录日志表
    const AGENT_AUTH_ZONE = 'agent_auth_zone'; // 代理商授权中心表
    const USERS_WHITE = 'users_white'; // 用户白名单

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
