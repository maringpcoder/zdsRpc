<?php
/**
 * 用户模型
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/26
 * Time: 16:58
 */
class UserModel extends BaseModel
{
    private $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    public function getUserById()
    {
        $this ->pdo ->select();
    }
}