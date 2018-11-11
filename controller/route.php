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
    //获取统计数量
    'getSum$'               => 'action/action/getSum',
    'getSumMonth$'          => 'action/action/getSumMonth',

    //用户
	'getUserList$'   		=> 'action/action/getUserList',
	'createUser$'   		=> 'action/action/createUser',
    'delUser$'   		    => 'action/action/delUser',
    'updatePassword$'       => 'action/action/updatePassword',
    'updateUserStatus$'     => 'action/action/updateUserStatus',

    //AK
    'createAk$'             => 'action/action/createAk',
    'getAkList$'   		    => 'action/action/getAkList',
    'delAk$'   		        => 'action/action/delAk',
    'updateAk$'             => 'action/action/updateAk',

    //分类标签
    'getCategoryList$'      => 'action/action/getCategoryList',
    'createCategory$'       => 'action/action/createCategory',
    'delCategory$'   		=> 'action/action/delCategory',
    'updateCategory$'       => 'action/action/updateCategory',

    //日志
    'getJournalList$'       => 'action/action/getJournalList',
    'journalIssueToggle$'   => 'action/action/journalIssueToggle',
    'journalDel$'           => 'action/action/journalDel',
    'getJournalById$'       => 'action/action/getJournalById',

    //好文推荐
    'getPushList$'          => 'action/action/getPushList',
    'pushIssueToggle$'      => 'action/action/pushIssueToggle',
    'pushDel$'              => 'action/action/pushDel',
    'createPush$'           => 'action/action/createPush',
    'updatePush$'           => 'action/action/updatePush',

    //资源下载
    'getDownList$'          => 'action/action/getDownList',
    'createDown$'           => 'action/action/createDown',
    'updateDown$'           => 'action/action/updateDown',
    'delDown$'              => 'action/action/delDown'
];
