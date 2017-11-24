<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use User\Api\UserApi;

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class UserController extends HomeController
{
    public function _initialize()
    {

        if ($_COOKIE['lives_lvt_language'] == 'en-us') {
            $lang_num = 3;
        } else {
            $lang_num = 2;
        }
        
        $this->assign('lang', $_COOKIE['lives_lvt_language'] ? $_COOKIE['lives_lvt_language']: 'en-us');
        $this->assign('lang_num', $lang_num);
        $this->assign('_sms_countrys', getSMSCountryListV2());
    }

    /* 用户中心首页 */
    public function index()
    {

    }

    /* 注册页面 */
    public function register()
    {

        if (IS_POST) {
            //注册用户
            $mobile      = I('post.mobile');
            $password    = I('post.password');
            $repassword  = I('post.repassword');
            $verify_code = I('post.verify_code');
            $area_code   = I('post.area_code', 0, 'intval');

            /* 检测密码 */
            if ($password != $repassword) {
                $this->assign('mobile', $mobile);
                $this->assign('error', L('error_password_text1'));
                $this->display();
                exit;
            } elseif (!isValidPasswordV2($password)) {
                $this->assign('error', L('error_password_text'));
                $this->display();
                exit;
            }

            //检查手机号是否已注册账号
            $user     = new UserApi();
            $userInfo = $user->info($mobile, true);

            if ($userInfo['id'] > 0) {
                $this->assign('mobile', $mobile);
                $this->assign('error', L('error_mobile_text1'));
                $this->display();
                exit;
            }

            $rs = '';
            //验证码已通过
            if ($rs == 1) {
                /* 调用注册接口注册用户 */
                $uid = $user->register($mobile, $password, $email, $mobile, $area_code);
                if (0 < $uid) {
                    //注册成功
                    $this->redirect('user/login');
                } else {
                    //注册失败，显示错误信息
                    $this->assign('mobile', $mobile);
                    $this->assign('error', L('error_register_fail'));
                    $this->display();
                    exit;
                }
            } else {
                if ($rs == 102) {
                    $this->assign('error', L('error_vcode_102'));
                } else {
                    if ($rs == 103) {
                        $this->assign('error', L('error_vcode_103'));
                    } else {
                        $this->assign('error', L('error_vcode_999'));
                    }
                }

                $this->assign('mobile', $mobile);
                $this->assign('password', $password);
                $this->assign('repassword', $repassword);
                $this->display();
                exit;
            }

        } else {
            //显示注册表单
            $this->display();
        }

    }

    /* 手机注册 */
    public function login()
    {
        if (IS_POST) {
            $mobile    = I('post.mobile');
            $verify    = I('post.verify');
            $password  = I('post.password');
            $area_code = I('post.area_code', 0, 'intval');

            //检查手机号码格式是否正确
            if (!mobile_regex($area_code, $mobile)) {
                $this->assign('error', L('error_mobile_text'));
                $this->display();
                exit;
            }

            //检查密码格式是否正确
            if (!isValidPasswordV2($password)) {
                $this->assign('mobile', $mobile);
                $this->assign('error', L('error_password_text'));
                $this->display();
                exit;
            }

            //检查手机号是否已注册账号
            $user = new UserApi;

            // 调用UC登录接口登录
            $uid = $user->login($mobile, $password, 3, $area_code);
            if ($uid === -1) {
                //用户不存在或锁定状态
                $this->redirect('User/register');
                exit;
            } elseif ($uid === -3) {
                //区号错误
                $this->assign('mobile', $mobile);
                $this->assign('error', L('error_mobile_text'));
                $this->display();
                exit;
            } elseif ($uid === -2) {
                $this->assign('mobile', $mobile);

                //密码错误,登录失败
                $loginNum = $this->_setLoginTimes($mobile);

                if ($loginNum > 4) {
                    $this->assign('loginNum', true);
                }

                $this->assign('error', L('error_login1'));
                $this->display();
                exit;
            } elseif ($uid > 0) {
                $loginNum = $this->_getLoginFailNum($mobile);

                if ($loginNum >= 5) {
                    $this->assign('loginNum', true);

                    /* 检测验证码 */
                    if (!check_verify($verify)) {
                        $this->assign('mobile', $mobile);
                        $this->assign('error', L('error_vcode_102'));
                        $this->display();
                        exit;
                    }
                }

                //UC登录成功
                $Member = D('Member');
                if ($Member->login($uid)) {
                    $loginNum = $this->_setLoginTimes($mobile, true);
                    $this->redirect('LvtManage/index');
                    exit;
                } else {
                    $this->assign('mobile', $mobile);
                    $this->assign('error', L('error_login'));
                    $this->display();
                    exit;
                }
            }
        } else {
            //显示登录表单
            $this->display();
        }
    }

    /* 退出登录 */
    public function logout()
    {
        if (is_login()) {
            D('Member')->logout();
            $this->redirect('/index');
        } else {
            $this->redirect('User/login');
        }
    }

    /* 验证码，用于登录和注册 */
    public function verify()
    {
        $verify = new \Think\Verify();
        $verify->entry(1);
    }

    /**
     * 获取用户注册错误信息
     *
     * @param  integer $code 错误编码
     *
     * @return string        错误信息
     */
    private function showRegError($code = 0)
    {
        switch ($code) {
            case -1:
                $error = '用户名长度必须在16个字符以内！';
                break;
            case -2:
                $error = '用户名被禁止注册！';
                break;
            case -3:
                $error = '用户名被占用！';
                break;
            case -4:
                $error = '密码长度必须在6-30个字符之间！';
                break;
            case -5:
                $error = '邮箱格式不正确！';
                break;
            case -6:
                $error = '邮箱长度必须在1-32个字符之间！';
                break;
            case -7:
                $error = '邮箱被禁止注册！';
                break;
            case -8:
                $error = '邮箱被占用！';
                break;
            case -9:
                $error = '手机格式不正确！';
                break;
            case -10:
                $error = '手机被禁止注册！';
                break;
            case -11:
                $error = '手机号被占用！';
                break;
            default:
                $error = '未知错误';
        }
        return $error;
    }

    public function setPasswd()
    {
        if (IS_POST) {
            //手机验证
            $user        = new UserApi;
            $mobile      = I('post.mobile');
            $password    = I('post.password');
            $repassword  = I('post.repassword');
            $verify_code = I('post.verify_code');
            $area_code   = I('post.area_code');

            //检查手机号码格式是否正确
            if (!mobile_regex($area_code, $mobile)) {
                $this->assign('error', L('error_mobile_text'));
                $this->display();
                exit;
            }

            //检查手机号是否已注册账号
            $userInfo = $user->info($mobile, true);

            if ($userInfo['id'] < 0) {
                $this->assign('mobile', $mobile);

                $this->assign('lang', ($_COOKIE['lives_lvt_language']) ? $_COOKIE['lives_lvt_language'] : 'en-us');

                $this->redirect('user/register');
                exit;
            }

            //检查密码合法性
            if ($password != $repassword) {
                $this->assign('error', L('error_password_text1'));
                $this->display();
                exit;
            } elseif (!isValidPasswordV2($password)) {
                $this->assign('error', L('error_password_text'));
                $this->display();
                exit;
            }

            $rs = '';
            
            //验证码已通过
            if ($rs == 1) {
                $res = $user->updateSetUserPasswordByMobile($mobile, $password);

                if ($res > 0 || $res === 0) {
                    $this->redirect('user/login');
                    exit;
                } else {
                    $this->assign('error', L('set_password_fail'));
                    $this->display();
                    exit;
                }
            } else {
                if ($rs == 102) {
                    $error = L('error_vcode_102');
                } else {
                    if ($rs == 103) {
                        $error = L('error_vcode_103');
                    } else {
                        $error = L('error_vcode_999');
                    }
                }

                $this->assign('error', $error);
                $this->display();
                exit;
            }
        } else {
            $this->display();
        }
    }
        /* 获取手机验证码 */
    public function valicode($mobile = '', $area_code = '', $lang = '')
    {
        echo json_encode($content);
    }

    private function _getLoginFailNum($str = null)
    {
        $key = md5($str . '_' . getClientIdentification());

        $loginNum = cookie($key);

        return (int) $loginNum;
    }

    private function _setLoginTimes($str = null, $clean = false)
    {
        $key = md5($str . '_' . getClientIdentification());

        if ($clean) {
            cookie($key, null);
        } else {
            $loginFailNum = cookie($key);

            if ($loginFailNum) {
                $loginFailNum = ++$loginFailNum;
                cookie($key, $loginFailNum, 60 * 30);
                return $loginFailNum;
            } else {
                cookie($key, 1, 60 * 30);
                return 1;
            }
        }
    }

    private function _checkGetVcodeNum($area_code = 0, $mobile = 0) {
        $redisInfo = C('REDIS_CACHE_INFO');
        // 初始化redis服务器
        S($redisInfo);

        $client = getClientIdentification();
        $key = md5($client . $area_code . $mobile);

        $number = (int) S($key);

        if ($number > 0) {
            $number = ++$number;
            S($key, $number);
            return $number;
        } else {
            S($key, 1);
            return $number = 1;
        }
    }
}
