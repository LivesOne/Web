<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use Think\Controller;
use User\Api\UserApi;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller
{

    /* 空操作，用于输出404页面 */
    public function _empty()
    {
        $this->redirect('Index/index');
    }

    protected function _initialize()
    {

        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置

        if (!C('WEB_SITE_CLOSE')) {
            $this->error('站点已经关闭，请稍后访问~');
        }

        // 需判断的登陆页面
        $login_url = '|ucenter|ytaddress|lvtmanage|extractionmoney|';
        //判断用户是否登陆
        $this->uid = is_login();

        if (strpos($login_url, strtolower(CONTROLLER_NAME)) && !$this->uid) {
            $this->redirect('User/login');
            exit;
        }

        //获取个人信息
        $this->User  = new UserApi;
        $this->infos = $this->User->info($this->uid);

        $this->assign('username', $this->infos['username']);

        //默认选择en-us
        if(!$_COOKIE['lives_lvt_language']) {
            setcookie('lives_lvt_language', 'en-us', time() + 3600, '/');
        }
        $this->assign('lang', $_COOKIE['lives_lvt_language'] ? $_COOKIE['lives_lvt_language']: 'en-us');
    }

    /* 用户登录检测 */
    protected function login()
    {
        /* 用户登录检测 */
        is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
    }

}
