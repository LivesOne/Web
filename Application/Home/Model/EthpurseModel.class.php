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
class EthpurseModel extends Model
{
    //根据uid查询以太地址
    public function get_uid_address($uid = '', $field = true)
    {
        $map = array(
            'uid' => $uid,
        );

        /* 返回前一条数据 */
        return $this->field($field)->where($map)->select();
    }
    //根据address查询
    public function get_address($address = '', $uid = '', $field = true)
    {
        $map = array(
            'address' => $address,
        );
        if ($uid > 0) {
            $map['uid'] = $uid;
        }

        /* 返回前一条数据 */
        return $this->field($field)->where($map)->select();
    }
    //增加一条以太地址
    public function add_address($uid = '', $address = '', $eth_name = '')
    {
        $data = array(
            'uid'      => $uid,
            'eth_name' => $eth_name,
            'address'  => $address,
            'add_time' => date("Y-m-d H:i:s"),
        );

        /* 添加购买记录 */
        if ($this->create($data)) {
            $id = $this->add($this->create($data));
            return $id ? $id : 0; //0-未知错误，大于0-注册成功
        } else {
            return $this->getError(); //错误详情见自动验证注释
        }
    }

    //删除以太地址
    public function del_address($address = '')
    {
        $data = array(
            'address' => $address,
        );
        $del = $this->where($data)->delete();
    }
}
