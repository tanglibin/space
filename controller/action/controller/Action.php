<?php

namespace tlb\action\controller;
use tlb\common\controller\Base;
use tlb\action\module\User;
use tlb\action\module\Ak;
use tlb\action\module\Category;
use tlb\action\module\Journal;
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


    /**********************************日志管理************************************************* */
    //获取日志概要列表
    public function getJournalList(){
        $this->success(Journal::getInstance()->getJournalList());
    }
    //日志发布|下线
    public function journalIssueToggle(){
        $this->success(Journal::getInstance()->journalIssueToggle(), '日志发布状态更新成功!');
    }
    //删除日志
    public function journalDel(){
        $this->success(Journal::getInstance()->journalDel(), '日志删除成功!');
    }
    //根据id获取日志具体内容
    public function getJournalById(){
        $this->success(Journal::getInstance()->getJournalById());
    }
}
