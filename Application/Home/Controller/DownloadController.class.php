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
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class DownloadController extends Controller
{

    //系统首页
    public function app()
    {
        $config = api('Config/lists');
        C($config); //添加配置

        $browser = get_device();

        if(strrpos(strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']), 'zh-cn') !== false) {
            if ($browser == 'IPHONE') {
                $url = C('APP_IPHONE_DOW');
            } else {
                $url = C('APP_ANDROIDPHONE_DOW');
            }

            $img_ios = 'dl_weixin.png';
            $img_and = 'live_weixin.png';
        } else {
            if ($browser == 'IPHONE') {
                $url = C('APP_IPHONE_DOW_EN');
            } else {
                $url = C('APP_ANDROIDPHONE_DOW_EN');
            }
            $img_ios = 'dl_weixin_en.png';
            $img_and = 'live_weixin_en.png';
        }

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) 
        {
            // 非微信浏览器直接跳转
            header("Location:$url");
            exit;
        } else {
            $this->assign('img_ios', $img_ios);
            $this->assign('img_and', $img_and);
            $this->assign('url', $url);
            $this->display();
        }
    }

}
