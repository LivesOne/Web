<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use User\Api\OrderApi;
use User\Api\UserApi;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class SubscriptionController extends HomeController
{

    public function _initialize()
    {
        $this->assign('lang', $_COOKIE['lives_lvt_language'] ? $_COOKIE['lives_lvt_language']: 'en-us');
    }

    //系统首页
    public function index()
    {

        $this->display();

    }
