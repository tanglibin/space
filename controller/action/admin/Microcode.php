<?php

namespace tlb\action\admin;
use tlb\common\controller\Base;
use think\Db;
class Microcode extends Base
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

    //ak校验
    private function validAk($data){
        $data=input('post.');
        if(empty($data['key'])){
            $this->error('Key不能为空！');
        }elseif(empty($data['value'])){
            $this->error('Value不能为空！');
        }elseif(empty($data['name'])){
            $this->error('名称不能为空！');
        }

        if(isset($data['id'])){
            $map['id'] = array('neq',$data['id']);
        }

        $map['key'] = $data['key'];
        $akinfo=Db::name('ak')->where($map)->find();
        if($akinfo){
            $this->error('Key已存在！');
        }
        unset($map['key']);

        $map['value'] = $data['value'];
        $akinfo=Db::name('ak')->where($map)->find();
        if($akinfo){
            $this->error('Value已存在！');
        }
        return true;
    }

    //创建AK
    public function createAk(){
        if(request()->isPost()){
            $data=input('post.');
            if($this->validAk($data)){
                $data['last_update_time'] = date("Y-m-d H:i:s");
                $id = Db::name('ak')->insert($data,false,true);
                if($id){
                    $data['id'] = $id;
                    return $data;
                }else{
                    $this->error('新增失败，请稍后再试！');
                }
            }
        }
    }
    
    //删除AK
    public function delAk(){
        if(request()->isPost()){
            $ids = input('id/a');
            $ids = implode(",",$ids) ;

            $map['id']  = array('in',$ids);
            $r=Db::name("ak")->where($map)->delete();
            if($r){
                return true;
            }else{
                return $this->error("删除失败！");
            }
        }
    }

    //修改AK
    public function updateAk(){
        if(request()->isPost()){
            $data=input('post.');
            if($this->validAk($data)){
                $r=Db::name("ak")->update($data);
                if($r){
                    return true;
                }else{
                    return $this->error("修改失败！");
                }
            }
        }
    }

    //获取总数量
    public function getCount(){
        return Db::name('microcode')->count();
    }

    //获取当年日志、微码、推荐月数量
    public function getCountMonth($year){
        return Db::query("select 
            sum(case date_format(b.create_time,'%m') when 01 then 1 else 0 end ) '1',
            sum(case date_format(b.create_time,'%m') when 02 then 1 else 0 end ) '2',
            sum(case date_format(b.create_time,'%m') when 03 then 1 else 0 end ) '3',
            sum(case date_format(b.create_time,'%m') when 04 then 1 else 0 end ) '4',
            sum(case date_format(b.create_time,'%m') when 05 then 1 else 0 end ) '5',
            sum(case date_format(b.create_time,'%m') when 06 then 1 else 0 end ) '6',
            sum(case date_format(b.create_time,'%m') when 07 then 1 else 0 end ) '7',
            sum(case date_format(b.create_time,'%m') when 08 then 1 else 0 end ) '8',
            sum(case date_format(b.create_time,'%m') when 09 then 1 else 0 end ) '9',
            sum(case date_format(b.create_time,'%m') when 10 then 1 else 0 end ) '10',
            sum(case date_format(b.create_time,'%m') when 11 then 1 else 0 end ) '11',
            sum(case date_format(b.create_time,'%m') when 12 then 1 else 0 end ) '12'
            from tlb_microcode b where date_format(b.create_time,'%Y') = " . $year);
    }
}
