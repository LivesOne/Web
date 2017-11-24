<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace User\Api;

class OrderApi
{
    const URL           = '';
    const HASH_URL      = '';
    const ORDER_URL     = '';
    const ADDRESS_TOTAL = '';
    const RULE_URL      = 1;
    //查询提取的唯一hash
    public function get_transaction_hash($address = '', $quantity = '')
    {
        return $content;
    }
    // 查询订单
    public function GetOrderDetail($address = '')
    {
       
    }

    // 查询address交易记录
    public function GetAddressMoney($uid = '', $address = array())
    {
       
    }
}
