<?php
namespace tlb\common\controller;
use think\Controller;
class Base extends controller{
	
	protected function _initialize() {		
		
		if (!is_file(APP_PATH.'database.php')) {
			header('Location:'.request()->domain().'/install');
			die();
		}				
	}

}
