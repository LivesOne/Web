<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function check_verify($code, $id = 1)
{
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

/**
 * 获取列表总行数
 * @param  string  $category 分类ID
 * @param  integer $status   数据状态
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_list_count($category, $status = 1)
{
    static $count;
    if (!isset($count[$category])) {
        $count[$category] = D('Document')->listCount($category, $status);
    }
    return $count[$category];
}

/**
 * 获取段落总数
 * @param  string $id 文档ID
 * @return integer    段落总数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_part_count($id)
{
    static $count;
    if (!isset($count[$id])) {
        $count[$id] = D('Document')->partCount($id);
    }
    return $count[$id];
}

/**
 * 获取导航URL
 * @param  string $url 导航URL
 * @return string      解析或的url
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_nav_url($url)
{
    switch ($url) {
        case 'http://' === substr($url, 0, 7):
        case '#' === substr($url, 0, 1):
            break;
        default:
            $url = U($url);
            break;
    }
    return $url;
}

// 获取短信验证码
function get_vcode($lang = '', $area_code = '', $mobile = '')
{   
    $url  = '';
    $data = array();
    $rs   = requestAPI($url, 'post', $data);
    return $rs['code'];
}
// 验证码验证
function get_vcode_devali($data)
{
    $url = '';
    $rs  = requestAPI($url, 'post', $data);
    return $rs['code'];
}
// 发送curl
function requestAPI($url, $type = 'get', $params = array(), $header = array())
{
    // 检测当前系统环境是否支持curl
    if (!function_exists('curl_init')) {
        die('not found funciont:curl_init');
    }

    $_url = parse_url($url);

    /* =============curl开始=============== */
    $ch = curl_init($url);
    if ($_url['scheme'] == 'https') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    if ($header) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    } else {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;'));
    }

    if ($type == 'post') {
        curl_setopt($ch, CURLOPT_HEADER, falsh);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    } else if ($type == 'post_1') {
        curl_setopt($ch, CURLOPT_HEADER, falsh);
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    } else {
        if (!empty($params) && is_array($params)) {
            $params = http_build_query($params);
        }
        $re_url = $url . ((strpos($url, '?') !== false) ? '&' : '?') . $params;
        curl_setopt($ch, CURLOPT_URL, $re_url);
    }

    //设置cURL允许执行的最长毫秒数
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    //是否开启DNS缓存，默认开启的
    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    //设置userage
    curl_setopt($ch, CURLOPT_USERAGENT, 'UserAPI client version of php 2.0');
    // 返回结果，而不是输出它
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $contents  = curl_exec($ch);
    $headers   = curl_getinfo($ch);
    $error_msg = curl_error($ch);
    curl_close($ch);
    /* =============curl结束=============== */
    
    if (intval($headers['http_code']) == 200) {
        return json_decode($contents, true);
    } else {
        // logError($error_msg);
        return false;
    }
}

function LogDebug($msg = '') 
{
    $filename = 'Runtime/Data/Log/Log' . '.' . date('Ymd', time()) . '.log';
    $log = date('[Y-m-d H:i:s]', time()) . "\t";
    $log .= "[{$_SERVER['REMOTE_ADDR']}]" . "\t";
    $log .= $msg . "\n";
    WriteFile($filename, $log, 'ab');
}

function logError($msg = '') 
{
    $filename = 'Runtime/Data/error/error_' . '.' . date('Ymd', time()) . '.log';
    $log = date('[Y-m-d H:i:s]', time()) . "\t";
    $log .= "[{$_SERVER['REMOTE_ADDR']}]" . "\t";
    $log .= $msg . "\n";
    WriteFile($filename, $log, 'ab');
    return true;
}

function WriteFile($filename, $data, $method = 'wb+', $iflock = 0, $check = 1, $chmod = 1) 
{
    if (empty($filename)) {
        return false;
    }

    if ($check && strpos($filename, '..') !== false) {
        return false;
    }

    if (!is_dir(dirname($filename)) && !mkdirRecursive(dirname($filename), 0777)) {
        return false;
    }
    if (false == ($handle = fopen($filename, $method))) {
        return false;
    }

    if ($iflock) {
        flock($handle, LOCK_EX);
    }
    fwrite($handle, $data);
    touch($filename);

    if ($method == "wb+") {
        ftruncate($handle, strlen($data));
    }
    if ($iflock) {
        flock($handle, LOCK_UN);
    }
    fclose($handle);
    $chmod && @chmod($filename, 0777);

    return true;
}

function mkdirRecursive($pathname, $mode = 0666) 
{
    if (strpos($pathname, '..') !== false) {
        return false;
    }
    $pathname = rtrim(preg_replace(array('/\\{1,}/', '/\/{2,}/'), '/', $pathname), '/');
    if (is_dir($pathname)) {
        return true;
    }

    is_dir(dirname($pathname)) || mkdirRecursive(dirname($pathname), $mode);
    return is_dir($pathname) || @mkdir($pathname, $mode);
}

// 获取国家code
function getSMSCountryListV2()
{
    include dirname(dirname(__FILE__)) . "/Conf/sms_country.php";
    $sms_countries = array_sort($sms_countries, 1, 'asc');
    return $sms_countries;
}

/**
 * 根据地区代码检测手机号格式
 * 例如： code=86 mobile=15910987855
 * @param string $code
 * @param string $mobile
 * @return boolean true/false
 */
function mobile_regex($code, $mobile)
{
    if (!$code || !$mobile) {
        return false;
    }

    $sms_countries = getSMSCountryListV2();
    /*
    $_conf = array(
    'CN' => array('/^(86){0,1}1\d{10}$/', '86', '中国大陆'),
    'TW' => array('/^(00){0,1}(886){1}0{0,1}[6,7,9](?:\d{7}|\d{8}|\d{10})$/', '886', '台湾'),
    ); */

    /* 没找到返回空 */
    $t = array_filter_by_value($sms_countries, '1', $code);

    if (is_null($t)) {
        return false;
    }

    $t = array_pop($t);

    $_regex  = $t[0];
    // $_mobile = $t[1] . $mobile;

    return preg_match($_regex, $mobile);
}

/**
 * 遍历查找二维数组的值
 * 返回当前子数组
 * @param array $array
 * @param string $index
 * @param string $value
 * @return array/null
 */
function array_filter_by_value($array, $index, $value)
{
    $newarray = null;
    if (is_array($array) && count($array) > 0) {
        foreach (array_keys($array) as $key) {
            $temp[$key] = $array[$key][$index];

            if ($temp[$key] == $value) {
                $newarray[$key] = $array[$key];
            }
        }
    }
    return $newarray;
}


function array_sort($arr, $keys, $type = 'asc')
{
    $keysvalue = $new_array = array();
    foreach ($arr as $k => $v) {
        $keysvalue[$k] = $v[$keys];
    }
    if ($type == 'asc') {
        asort($keysvalue);
    } else {
        arsort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}

//  获取验证数字
function getrandchar($len)
{
    $chars    = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $charslen = count($chars) - 1;
    shuffle($chars);
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charslen)];
    }
    return $output;
}
//获取发送人员
function email_user() 
{
    include dirname(dirname(__FILE__)) . "/Conf/email_user.php";
    return $email_user;
}

//发送email方法
function mymail($address_array, $subject = "", $body = "", $attachment_type = 1, $replyto_email)
{

    include_once 'email/class.phpmailer.php';
    include_once 'email/class.smtp.php';
    //$subject = "发送给您";
    //$body = "http的连接";
    $post_states = '';
    date_default_timezone_set("Asia/Shanghai"); //设定时区东八区
    $mail = new PHPMailer(true); //new一个PHPMailer对象出来

    $body          = eregi_replace("[\]", '', $body); //对邮件内容进行必要的过滤
    $mail->CharSet = "UTF-8"; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->IsHTML(true);
    $mail->SMTPDebug  = 1; // 启用SMTP调试功能
    $mail->SMTPAuth   = true; // 启用 SMTP 验证功能
    $mail->SMTPSecure = ""; // 安全协议
    $mail->Host       = ""; // SMTP 服务器
    $mail->Port       = ''; // SMTP服3务器的端口号
    $mail->Username   = ''; // SMTP服务器用户名
    $mail->Password   = ''; // SMTP服务器密码
    $mail->SetFrom($mail->Username, "token@lives.one");

    $mail->Subject = $subject;
    $mail->AltBody = $body;
    $mail->MsgHTML($body);
    foreach ($address_array as $address) {
        $mail->AddAddress($address, isset($address) ? $address : '');
    }
    if ($attachment_type == 1) {
        $path_att    = dirname(__file__) . '/email/';
        $cn_pdf_name = 'lvt认购协议中文版.pdf';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $cn_filename = $path_att . iconv('UTF-8', 'GB2312', $cn_pdf_name);
        } else {
            $cn_filename = $path_att . $cn_pdf_name;
        }
        $attachment = array(
            "lvt subscription agreement.pdf" => $path_att . "lvt subscription agreement.pdf",
            $cn_pdf_name                     => $cn_filename,
        );
        foreach ($attachment as $name => $file) {
            $mail->AddAttachment($file, $name); //附件
        }
    }
    
    if (!$mail->Send()) {
        $post_states = -1;
    } else {
        $post_states = 1;
    }
    $mail->SmtpClose();
    return $post_states;
}
//email判断
function emailcheck($email)
{
    $ret = false;
    if (isset($email)) {
        if (strstr($email, '@') && strstr($email, '.')) {
            if (eregi("^([_a-z0-9]+([._a-z0-9-]+)*)@([a-z0-9]{2,}(.[a-z0-9-]{2,})*.[a-z]{2,3})$", $email)) {
                $ret = true;
            }
        }
    }
    return $ret;
}
function body_email($lang = 'zh-cn', $num, $name, $ratio)
{
    $total = eth_lvt($num);
    if ($lang == 'en-us') {
        $body = '<div><h2>Hello&nbsp;' . $name . ',</h2>
                <p style="text-indent:28px;">Congratulations on successfully pre-ordered ' . $total . 'LVT（equivalent to ' . $num . 'ETH）. The transfer entrance is not yet open, please keep an eye on your email or SMS notification.</p>
                <p style="text-indent:28px;">——  Please download LVT Subscription Agreement from attachment.</p>
                <p style="text-align:right;margin-right:15px;">LivesToken(LVT) Private Placement Notification</p></div>';
    } else {
        $body = '<div><h2>' . $name . '，您好：</h2>
                <p style="text-indent:28px;">恭喜你成功预购' . $total . '个LVT（' . $num . '个ETH），转账入口暂未开放，请随时关注邮件或短信的通知；</p>
                <p style="text-indent:28px;">——  认购协议附件下载</p>
                <p style="text-align:right;margin-right:15px;">共生基金会</p></div>';
    }
    return $body;
}

/**
 * 根据以太币计算可以得到多少LVT
 *
 * 预购查询页面用到
 * 预购自动发送确认邮件中也用到
 *
 * @param type $num
 * @return type
 */
function eth_lvt($num)
{
    return true;
}
/**
 * 以太金钱转换
 * 从接口获取用户转账过来的以太币金额末尾多18个0（存在风险，小数不能用这个算法）
 *
 * 预购查询页面用到
 * @param type $value
 * @param type $int
 * @return type
 */
function eth_money($value)
{
    $i = mb_strlen($value) - 18;
    $eth = insertToStr($value, $i, '.');
    $eth = floatval($eth);
    return $eth;
}

/**
 * 指定位置插入字符串
 * @param $str  原字符串
 * @param $i    插入位置
 * @param $substr 插入字符串
 * @return string 处理后的字符串
 */
function insertToStr($str, $i, $substr)
{
    //指定插入位置前的字符串
    $startstr="";
    for($j=0; $j<$i; $j++){
        $startstr .= $str[$j];
    }
     
    //指定插入位置后的字符串
    $laststr="";
    for ($j=$i; $j<strlen($str); $j++){
        $laststr .= $str[$j];
    }
     
    //将插入位置前，要插入的，插入位置后三个字符串拼接起来
    $str = $startstr . $substr . $laststr;
     
    //返回结果
    return $str;
}


/**
 *补位18个0
 */
function cash_money($value, $int = '000000000000000000')
{
    $celnum = getFloatLength($value);
    if ($celnum > 0) {
        $pow    = pow(10,$celnum);
        $subnum = 18-$celnum;
        $value  = $value * $pow;
        $int    = substr($int,0,$subnum);
    }
    return  $value . $int;
}
/**
 *获取小数点后面位数
 */
function getFloatLength($num) 
{
    $count = 0;
    $temp = explode ( '.', $num );
    if (sizeof ( $temp ) > 1) {
        $decimal = end ( $temp );
        $count = strlen ( $decimal );
    }
    return $count;
}



/**
 * 以太地址检测
 *
 *
 * @return boolean
 */
function eth_address()
{
    // 目前交给前端控制，故暂时后台不限制
    return true;
}
/**
 *  date1日期1
 *  date2 日期2
 *  tags 年月日之间的分隔符标记,默认为'-'
 *  相差的月份数量
 */
function get_month_num($date1, $date2)
{
    $date_1 = explode("-", $date1);
    $date_2 = explode("-", $date2);
    return abs($date_1[0] - $date_2[0]) * 12 + $date_2[1] - $date_1[1];
}


//语言判断
function get_lang_data($data, $lang = 'en-us', $type = ''){
    if ($lang == 'zh-cn') {
        return $data;
    } else {
        if ($type == 'title') {
            $data['title'] = $data['title_en'];
        } else {
            $data['title'] = $data['title_en'];
            $data['content'] = $data['content_en'];
            $data['keywords'] = $data['keywords_en'];
            $data['description'] = $data['description_en'];
        }
        return $data;
    }
}
//获取浏览器
function get_device(){

    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    $device = 'PC';

    if ( empty($user_agent) ) {
    } else if (preg_match("/(iphone|ipod|ipad)/i", $user_agent) && !preg_match("/android/i", $user_agent)) {
        $device = 'IPHONE';
    } else if (preg_match("/mac/i", $user_agent)) {
        $device = 'MAC';
    } else if (preg_match("/Windows Phone/i", $user_agent)) {
        $device = 'Windows Phone';
    } else if (preg_match("/linux/i", $user_agent)) {
        if(!preg_match("/android/i", $user_agent) && preg_match("/linux/i", $user_agent)) {
            $device = 'LINUX';
        } else if (preg_match("/android/i", $user_agent) || preg_match("/linux/i", $user_agent)) {
            $device = 'ANDROIDPHONE';
        } else {
            $device = 'PC';
        }
    }
    return $device;

}