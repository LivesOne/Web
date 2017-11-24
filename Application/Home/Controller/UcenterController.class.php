<?php
/**
 * Description for here
 *
 * @author  zhaohongfeng
 * @date    2017/9/27 15:15
 * @version v1.0
 */

namespace Home\Controller;

// use Think\Controller;
use User\Api\UserApi;

class UcenterController extends HomeController
{
    private $uid  = 0;
    private $user = null;

    protected function _initialize()
    {

        $uid        = is_login();
        $this->user = new UserApi();

        if (!$uid) {
            $this->redirect('User/login');
            exit;
        } else {
            $userInfo = $this->user->info($uid);

            if (is_array($userInfo)) {
                $this->uid = $uid;
                $this->assign('mobile', substr_replace($userInfo['mobile'], '****', 3, 4));
                $this->assign('username', $userInfo['username']);
            }
        }
        $this->assign('lang', $_COOKIE['lives_lvt_language'] ? $_COOKIE['lives_lvt_language']: 'en-us');
    }

    public function index()
    {
        $this->display();
    }

    public function resetpassword()
    {
        if (IS_POST) {
            if ($this->uid > 0) {
                $userInfo = $this->user->info($this->uid);

                $oldpassword = I('post.oldpassword');
                $password    = I('post.password');
                $repassword  = I('post.repassword');

                //检查密码合法性
                if ($userInfo['password'] != think_ucenter_md5($oldpassword, UC_AUTH_KEY)) {
                    $this->assign('error', L('error_password_text2'));
                    $this->display();
                    exit;
                } elseif ($password != $repassword) {
                    $this->assign('error', L('error_password_text1'));
                    $this->display();
                    exit;
                } elseif (!isValidPasswordV2($password)) {
                    $this->assign('error', L('error_password_text'));
                    $this->display();
                    exit;
                } else {
                    $res = $this->user->updateSetUserPasswordByMobile($userInfo['mobile'], $password);
                    if ($res) {
                        $this->redirect('Ucenter/successful');
                        exit;
                    } else {
                        $this->assign('error', L('set_password_fail'));
                        $this->display();
                        exit;
                    }
                }
            }
        } else {
            $this->display();
        }
    }

    public function successful()
    {
        $this->display();
    }
}
