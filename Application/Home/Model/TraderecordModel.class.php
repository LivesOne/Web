<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Model;

use Think\Model;

/**
 * 分类模型
 */
class TraderecordModel extends Model
{
    //根据uid查询以太地址
    public function get_uid_order($uid = '', $field = true)
    {
        return '';
    }
    //根据uid查询lvt_num
    public function get_uid_lvt_num($uid = '', $type = 1, $field = true)
    {
        return '';
    }
    //根据以太地址获取交易记录
    public function get_address_trade($address = '', $status = '', $field = true)
    {
        return '';
    }
}
