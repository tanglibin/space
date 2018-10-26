<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

return [
    //用户管理
	'getUserList$'   		=> 'action/action/getUserList',
	'createUser$'   		=> 'action/action/createUser',
    'delUser$'   		    => 'action/action/delUser',
    'updatePassword$'       => 'action/action/updatePassword',
    'updateUserStatus$'     => 'action/action/updateUserStatus',

    //AK 管理
    'createAk$'             => 'action/action/createAk',
    'getAkList$'   		    => 'action/action/getAkList',
    'delAk$'   		        => 'action/action/delAk',
    'updateAk$'             => 'action/action/updateAk',

    //获取分类标签
    'getCategoryList$'      => 'action/action/getCategoryList',
];
