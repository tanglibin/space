<?php

namespace tlb\action\module;
use tlb\common\controller\Base;
use think\Db;
class Journal extends Base
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


    //获取日志概要列表
    public function getJournalList(){
        if(request()->isPost()){

            //模糊过滤标题
            $title = input('post.title');
            if (!empty($title)) {
                $where['a.title'] = ['like', '%' . mb_convert_encoding($title, "UTF-8", "auto") . '%'];
            }

            //模糊过滤概要
            $article = input('post.article');
            if (!empty($article)) {
                $where['a.article'] = ['like', '%' . mb_convert_encoding($article, "UTF-8", "auto") . '%'];
            }

            //过滤分类
            $category_id = input('post.category_id');
            if (!empty($category_id)) {
                $where['a.category_id'] = $category_id;
            }

            //过滤状态
            $status = input('post.status');
            if (!empty($status)) {
                $where['a.status'] = $status;
            }

            //起始时间
            $start = input('post.start');
            $end = input('post.end');

            if ($start && $end && strtotime($start) > strtotime($end)) {
                return $this->error('开始时间不能大于截止时间！');
            }
            $dateArr = [];
            if ($start) {
                $start = $start.'00:00:00';
                $dateArr[] = ['>= time', strtotime($start)];
            }
            if ($end) {
                $end = $end.'23:59:59';
                array_push($dateArr, ['<= time', strtotime($end)]);
            }
            if (count($dateArr) == 1) {
                $tempA = $dateArr[0];
            }
            if (count($dateArr) == 2) {
                $tempA = $dateArr;
            }
            if (isset($tempA)) {
                $where['a.createtime'] = $tempA;
            }

            //如果没有过滤条件，则设为1
            if (empty($where)) {
                $where = 1;
            }
            $list = Db::name('article_info')->alias('a')->join('category b', 'b.id = a.category_id', 'LEFT')
                        ->field('a.id, a.title, a.article, a.type, a.issue_time, a.status, b.category_name')
                        ->where($where)
                        ->order('a.create_time desc')
                        ->paginate(input('post.pageSize'));
            return $list;
        }
    }

    //日志发布|下线
    public function journalIssueToggle(){
        if(request()->isPost()){
            $data=input('post.');
            $r=Db::name("article_info")->where(['id'=>$data['id']])->update(['status'=>$data['status']],false,true);
            if($r){
                return true;
            }else{
                return $this->error("日志发布状态更新失败！");
            }
        }
    }

    //删除日志
    public function journalDel(){
        if(request()->isPost()){
            $ids = input('id/a');
            $ids = implode(",",$ids) ;

            //开启事务
            Db::startTrans();
            $r1=Db::name("article_info")->where(['id'=>array('in',$ids)])->delete();
            $r2=Db::name("article_detail")->where(['info_id'=>array('in',$ids)])->delete();
            if($r1 && $r2){
                Db::commit();
                return true;
            }else{
                Db::rollback();
                return $this->error("删除失败！");
            }
        }
    }

    //根据id获取日志具体内容
    public function getJournalById(){
        $id = input('get.id');
        $article = Db::name('article_info')->where(['id' => $id]) -> find();
        //tlb_article_detail
        $detail = Db::query('select t.id,t.chapter_title, case t.rowNo when 1 then t.content else '' end content from (
            select b.id, b.name,b.content (@rowNum:=@rowNum+1) rowNo  from a ,b, (Select (@rowNum :=0) )c 
            where a.id = b.id  order by create_time)t');
        return $id;
    }
}
