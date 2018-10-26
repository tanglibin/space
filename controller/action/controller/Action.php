<?php

namespace tlb\action\controller;
use tlb\common\controller\Base;
use tlb\action\module\User;
use tlb\action\module\Ak;
use tlb\action\module\Category;
use think\Db;
class Action extends Base
{

    /**********************************用户管理************************************************* */
    //新增用户
    public function createUser(){
        $this->success(User::getInstance()->createUser(), '新增成功!');
    }
    //删除用户
    public function delUser(){
        $this->success(User::getInstance()->delUser(), '删除成功!');
    }
	//查询用户列表
	public function getUserList()
    {    
        $this->success(User::getInstance()->getList());
    }
    //修改用户密码
    public function updatePassword(){
        $this->success(User::getInstance()->updatePassword(), '修改密码成功!');
    }
    //更新用户状态
    public function updateUserStatus(){
        $this->success(User::getInstance()->updateUserStatus(), '用户状态更新成功!');
    }

    /**********************************AK管理************************************************* */
    //创建AK
    public function createAk(){
        $this->success(Ak::getInstance()->createAk(), '新增成功!');
    }
    //删除AK
    public function delAk(){
        $this->success(Ak::getInstance()->delAk(), '删除成功!');
    }
    //修改AK
    public function updateAk(){
        $this->success(Ak::getInstance()->updateAk(), '修改成功!');
    }
    //查询AK数据集合
    public function getAkList(){
        $this->success(Ak::getInstance()->getAkList());
    }

    /**********************************分类标签************************************************* */
    //获取分类标签数据
    public function getCategoryList(){
        $this->success(Category::getInstance()->getCategoryList());
    }
}
