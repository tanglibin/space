<?php
 
namespace tlb\index\controller;
use tlb\common\controller\Base;
class Index extends Base
{
    public function index()
    {    			
		return $this->fetch();
    }
}
