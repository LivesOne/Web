<?php
/**
 * Description for here
 *
 * @author  zhaohongfeng
 * @date    2017/9/28 17:15
 * @version v1.0
 */

namespace Home\Controller;

use Think\Controller;

class LvtManageController extends HomeController
{

    public function index()
    {
    
        $this->assign('record_num', $record_num);
        $this->assign('extraction_num', $extraction_num);
        $this->assign('total_extraction', $total_extraction);
        $this->assign('total_money', $total_money);
        $this->assign('now_use_money', $now_use_money);
        $this->assign('locking_money', $locking_money);
        $this->assign('data_record', $data_record);
        $this->assign('data_extraction', $data_extraction);
        $this->display();
    }
}
