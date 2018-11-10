<?php

namespace tlb\action\module;
use tlb\common\controller\Base;
use think\Db;
class Category extends Base
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

    //获取分类标签列表
    public function getCategoryList(){
        $is_simple = input('get.simple');
        $is_simple = isset($is_simple);

        //下拉列表数据获取
        if($is_simple){
            return Db::name('category')->field('id, category_name')->order('create_time desc')->select();
        }

        //列表页面数据获取
        $list =  Db::query("SELECT a.*, 
                                (select count(1) from tlb_article_info b where b.category_id=a.id ) as jourbal_num, 
                                (select count(1) from tlb_essay_push c where c.category_id=a.id ) as push_num,
                                (select count(1) from tlb_microcode d where d.category_id=a.id ) as micro_num
                            FROM tlb_category a order by a.create_time desc");
        return $list;
    }

    //创建
    public function createCategory(){
        if(request()->isPost()){
            $data=input('post.');
            $now = date("Y-m-d H:i:s");
            $data['create_time'] = $now;
            $data['last_update_time'] = $now;
            $id = Db::name('category')->insert($data,false,true);
            if($id){
                $data['id'] = $id;
                return $data;
            }else{
                $this->error('新增失败，请稍后再试！');
            }
        }
    }
    
    //删除
    public function delCategory(){
        if(request()->isPost()){
            $ids = input('id/a');
            $ids = implode(",",$ids) ;

            $map['id']  = array('in',$ids);
            $r=Db::name("category")->where($map)->delete();
            if($r){
                return true;
            }else{
                return $this->error("删除失败！");
            }
        }
    }

    //修改
    public function updateCategory(){
        if(request()->isPost()){
            $data=input('post.');
            $data['last_update_time'] = date("Y-m-d H:i:s");
            $r=Db::name("category")->update($data);
            if($r){
                return $data['last_update_time'];
            }else{
                return $this->error("修改失败！");
            }
        }
    }

}
