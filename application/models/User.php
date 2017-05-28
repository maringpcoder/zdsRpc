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
    private $table = 'user';

    public function __construct()
    {
        parent::__construct();
    }

    public function getUserById()
    {
//        return $this ->db ->select($this->table,['name','id'],["id"=>[2,3,4,5]]);
//        return $this ->db ->query('select * from user')->fetchAll(PDO::FETCH_ASSOC);
        return $this ->db->get($this->table,'*');
        //return $this ->db ->query('select * from user')->fetchAll();
    }
}