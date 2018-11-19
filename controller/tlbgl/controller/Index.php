<?php
namespace tlb\tlbgl\controller;
use think\Controller;
class Index extends Controller{
	
    public function index()
    {
        $this->assign('admin_username', session('admin_username'));
		return $this->fetch();   
    }

}
