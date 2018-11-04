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

    //获取日志概要列表
    'getJournalList$'       => 'action/action/getJournalList',
    //日志发布|下线
    'journalIssueToggle$'   => 'action/action/journalIssueToggle',
    //删除日志
    'journalDel$'           => 'action/action/journalDel',
    //根据id获取日志具体内容
    'getJournalById$'       => 'action/action/getJournalById',

    //获取好文推荐列表
    'getPushList$'          => 'action/action/getPushList',
    //好文发布|下线
    'pushIssueToggle$'      => 'action/action/pushIssueToggle',
    //好文推荐删除
    'pushDel$'              => 'action/action/pushDel',
    //新增推荐
    'createPush$'           => 'action/action/createPush',
    //修改推荐内容
    'updatePush$'           => 'action/action/updatePush'
];
