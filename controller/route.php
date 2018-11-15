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
    'getSum$'               => 'action/admin/getSum',
    'getSumMonth$'          => 'action/admin/getSumMonth',

    //用户
	'getUserList$'   		=> 'action/admin/getUserList',
	'createUser$'   		=> 'action/admin/createUser',
    'delUser$'   		    => 'action/admin/delUser',
    'updatePassword$'       => 'action/admin/updatePassword',
    'updateUserStatus$'     => 'action/admin/updateUserStatus',

    //AK
    'createAk$'             => 'action/admin/createAk',
    'getAkList$'   		    => 'action/admin/getAkList',
    'delAk$'   		        => 'action/admin/delAk',
    'updateAk$'             => 'action/admin/updateAk',

    //分类标签
    'getCategoryList$'      => 'action/admin/getCategoryList',
    'createCategory$'       => 'action/admin/createCategory',
    'delCategory$'   		=> 'action/admin/delCategory',
    'updateCategory$'       => 'action/admin/updateCategory',

    //日志
    'getJournalList$'       => 'action/admin/getJournalList',
    'journalIssueToggle$'   => 'action/admin/journalIssueToggle',
    'journalDel$'           => 'action/admin/journalDel',
    'getJournalById$'       => 'action/admin/getJournalById',

    //好文推荐
    'getPushList$'          => 'action/admin/getPushList',
    'pushIssueToggle$'      => 'action/admin/pushIssueToggle',
    'pushDel$'              => 'action/admin/pushDel',
    'createPush$'           => 'action/admin/createPush',
    'updatePush$'           => 'action/admin/updatePush',

    //资源下载
    'getDownList$'          => 'action/admin/getDownList',
    'createDown$'           => 'action/admin/createDown',
    'updateDown$'           => 'action/admin/updateDown',
    'delDown$'              => 'action/admin/delDown'
];
