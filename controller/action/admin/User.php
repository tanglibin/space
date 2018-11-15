<?php

namespace tlb\action\admin;
use tlb\common\controller\Base;
use think\Db;
class User extends Base
{
    //object 对象实例
    private static $instance;
	
	//单例模式	
	public static function getInstance(){    
        if (!(self::$instance instanceof self))  
        {  
            self::$instance = new self();  
        }  
        return self::$instance;  
    }


    //创建用户
    public function createUser(){
        if(request()->isPost()){
            $data=input('post.');
            if(empty($data['username'])){
				$this->error('用户名不能为空！');
			}elseif(empty($data['password'])){
				$this->error('密码不能为空！');
			}elseif(empty($data['user_type'])){
				$this->error('用户类型不能为空！');
            }

            $userinfo=Db::name('user')->where('username',$data['username'])->find();
            if($userinfo){
                $this->error('用户名已存在！');
            }

            $data['create_time'] = date("Y-m-d H:i:s");
            $data['access_times'] = 0;
            $data['user_type'] = 2;
            $data['status'] = 1;
            $id = Db::name('user')->insert($data,false,true);
            if($id){
                $data['id'] = $id;
                return $data;
            }else{
                $this->error('新增失败，请稍后再试！');
            }
        }
    }

    //删除用户
    public function delUser(){
        if(request()->isPost()){
            $ids = input('id/a');
            $ids = implode(",",$ids) ;

            $map['id']  = array('in',$ids);
            $map['user_type'] = 2;

            $r=Db::name("user")->where($map)->delete();
            if($r){
                return true;
            }else{
                return $this->error("删除失败！");
            }
        }
    }

    //获取用户列表
    public function getList(){
        return Db::name('user')->field('id,username,user_type,status,access_times,last_login_time,create_time')->order('create_time desc')->select();
    }

    //修改用户密码
    public function updatePassword(){
        if(request()->isPost()){
            $data=input('post.');
            $r=Db::name("user")->update($data);
            if($r){
                return true;
            }else{
                return $this->error("修改密码失败！");
            }
        }
    }

    //更新用户状态
    public function updateUserStatus(){
        if(request()->isPost()){
            $data=input('post.');

            $map['id']  = $data['id'];
            $map['user_type'] = 2;

            $r=Db::name("user")->where($map)->update(['status'=>$data['status']],false,true);
            if($r){
                return true;
            }else{
                return $this->error("用户状态更新失败！");
            }
        }
    }

}
