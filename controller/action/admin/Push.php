<?php

namespace tlb\action\admin;
use tlb\common\controller\AdminBase;
use think\Db;
class Push extends AdminBase
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


    //获取好文推荐列表
    public function getPushList(){
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
                $where['a.issue_time'] = $tempA;
            }

            //如果没有过滤条件，则设为1
            if (empty($where)) {
                $where = 1;
            }
            $list = Db::name('essay_push')->alias('a')->join('category b', 'b.id = a.category_id', 'LEFT')
                        ->field('a.id, a.title, a.article,a.url, a.issue_time, a.status, a.category_id, b.category_name')
                        ->where($where)
                        ->order('a.create_time desc')
                        ->paginate(input('post.pageSize'));
            return $list;
        }
    }

    //推荐发布|下线
    public function pushIssueToggle(){
        if(request()->isPost()){
            $data=input('post.');

            $where['id'] = $data['id'];
            $o = Db::name("essay_push")->where($where)->find();

            if(empty($o['issue_time']) && $data['status'] == '2'){
                $data['issue_time'] = date("Y-m-d H:i:s");
            }

            $r=Db::name("essay_push")->where($where)->update($data);
            if($r){
                return $data;
            }else{
                return $this->error("推荐发布状态更新失败！");
            }
        }
    }

    //删除推荐
    public function pushDel(){
        if(request()->isPost()){
            $ids = input('id/a');
            $ids = implode(",",$ids) ;

            $r1=Db::name("essay_push")->where(['id'=>array('in',$ids)])->delete();
            if($r1){
                return true;
            }else{
                return $this->error("推荐删除失败！");
            }
        }
    }

    //创建推荐
    public function createPush(){
        if(request()->isPost()){
            $data=input('post.');
            $now = date("Y-m-d H:i:s");

            $issue_time = '';
            if($data['status'] == '2'){
                $data['issue_time'] = $now;
                $issue_time = $now;
            }
            $data['create_time'] = $now;
            $id = Db::name('essay_push')->insert($data,false,true);
            if($id){
                return ['id'=> $id, 'issue_time'=> $issue_time];
            }else{
                $this->error('新增失败，请稍后再试！');
            }
        }
    }

    //修改推荐
    public function updatePush(){
        if(request()->isPost()){
            $data=input('post.');
            
            if(strlen($data['issue_time']) != 19){
                if($data['status'] == '2'){
                    $data['issue_time'] = date("Y-m-d H:i:s");
                }else{
                    unset($data['issue_time']);
                }
            }
            unset($data['category_name']);
            $r=Db::name("essay_push")->update($data);
            if($r){
                return ['issue_time'=>$data['issue_time']];
            }else{
                return $this->error("修改失败！");
            }
        }
    }

    //获取总数量
    public function getCount(){
        return Db::name('essay_push')->count();
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
            from tlb_essay_push b where date_format(b.create_time,'%Y') = " . $year);
    }
}
