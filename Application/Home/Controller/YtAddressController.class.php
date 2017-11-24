<?php
/**
 * Description for here
 *
 * @author  zhaohongfeng
 * @date    2017/9/28 17:12
 * @version v1.0
 */

namespace Home\Controller;

use Think\Controller;

class YtAddressController extends HomeController
{

    public function index()
    {
        //获取以太地址
        $Ethpurse = D('ethpurse');
        //获取address地址
        $address_list = $Ethpurse->get_uid_address($this->uid);
        $this->assign('address_list', $address_list);
        $this->display();
    }

    public function addAddress()
    {
        if (IS_POST) {
            $address  = I('post.address');
            $eth_name = I('post.eth_name');
            //计算address数量
            //获取以太地址
            $Ethpurse     = D('ethpurse');
            $address_list = $Ethpurse->get_uid_address($this->uid);
            $address_num  = count($address_list);
            if ($address_num >= 3) {
                $address_error = L('subscription_error_address_num');
            } else {
                //验证以太地址的格式
                $_address = eth_address($address);
                if ($_address === false) {
                    // $data['code'] = 1;
                    $address_error = L('subscription_error_address');
                } else {
                    //查询以太地址是否存在
                    $address_info = $Ethpurse->get_address($address);
                    if (!empty($address_info)) {
                        // $data['code'] = 2;
                        $address_error = L('subscription_error_address1');
                    } else {
                        //增加以太地址
                        $address_id = $Ethpurse->add_address($this->uid, $address, $eth_name);
                        // $data['code'] = 0;
                        $address_error = L('text_add_address');
                        $this->redirect('YtAddress/index');
                        exit;
                    }
                }
            }

        }
        $this->assign('address_error', $address_error);
        $this->display();
        // echo json_encode($data);
    }

    public function editadress()
    {

    }

}
