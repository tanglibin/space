<?php
namespace tlb\common\controller;
use think\Controller;
class AdminBase extends controller{
	
	protected function _initialize() {		
		if(!session('admin_username')){
            $this->error('请先登录!', -1);
        }			
	}

}
