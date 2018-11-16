<?php
namespace tlb\tlbgl\controller;
use think\Controller;
class Index extends Controller{
	
    public function index()
    {
		return $this->fetch();   
    }

}
