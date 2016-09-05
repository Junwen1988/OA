<?php
/**
 * Created by Sublime Text.
 * User: Junwen
 * Date: 2016/6/21
 * Time: 15:21
 */

/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key 加密密钥
 * @param int $expire 过期时间 单位 秒
 * @return string
 */

function encrypt($data, $key = '', $expire = 0)
{
    $key = md5(empty($key) ? \think\Config::get('auth_key') : $key);
    $data = base64_encode($data);
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    $char = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    $str = sprintf('%010d', $expire ? $expire + time() : 0);

    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
    }
    return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
}

/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param  string $key 加密密钥
 * @return string
 */
function decrypt($data, $key = '')
{
    $key = md5(empty($key) ? \think\Config::get('auth_key') : $key);
    $data = str_replace(array('-', '_'), array('+', '/'), $data);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    $data = base64_decode($data);
    $expire = substr($data, 0, 10);
    $data = substr($data, 10);

    if ($expire > 0 && $expire < time()) {
        return '';
    }
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    $char = $str = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        } else {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}


/**
 *   格式化输出时间
**/

function timeFormat($timestr=''){
  // $datestr = date("Y年-m月-d日 H:i:s", $timestr)
  // return $datestr;
}


/**
 *   获取订单状态中文名
**/

function getstatuscnname($status=''){
    switch ($status) {
        case 'wprocessing':
            $cnname = '<span class="ui green label">等待处理</span>';
            break;
        case 'processing':
            $cnname = '<span class="ui olive  label">处理中 <i class="ellipsis horizontal icon"></i></span>';
            break;
        case 'cancelled':
            $cnname = '<span class="ui grey  label"><i class="frown icon"></i>已取消</span>';
            break;
        case 'pending':
            $cnname = '<span class="ui yellow label">待付款</span>';
            break;
        case 'onhold':
            $cnname = '<span class="ui violet  label"><i class="meh icon"></i>订单挂起</span>';
            break; 
        case 'outofstock':
            $cnname = '<span class="ui black label"><i class="meh icon"></i>订单缺货</span>';
            break;
        case 'dispute':
            $cnname = '<span class="ui red label"><i class="frown  icon"></i>订单争议</span>';
            break;  
        case 'completed':
            $cnname = '<span class="ui gray label"><i class="smile icon"></i>订单完成</span>';
            break;  
        case 'epack':
            $cnname = '<span class="ui red label visible"><i class="plane icon"></i>已发送到E邮宝</span>';
            break; 
        case 'epacked':
            $cnname = '<span class="ui  label visible"><i class="plane icon"></i>已用E邮宝发货</span>';
            break; 
        case 'gc':
            $cnname = '<span class="ui blue label"><i class="car icon"></i>已发送到谷仓</span>';
            break; 
        case 'gced':
            $cnname = '<span class="ui  label"><i class="car icon"></i>已通过谷仓发货</span>';
            break; 
        case '4px':
            $cnname = '<span class="ui purple  label"><i class="shipping icon"></i>已发送到递四方</span>';
            break; 
        case '4pxed':
            $cnname = '<span class="ui  label"><i class="shipping icon"></i>已通过递四方发货</span>';
            break;   
        case 'oneworld':
            $cnname = '<span class="ui purple  label"><i class="shipping icon"></i>已发送到易时达</span>';
            break; 
        case 'oneworlded':
            $cnname = '<span class="ui  label"><i class="shipping icon"></i>已通过易时达发货</span>';
            break;         
        
        default:
            $cnname = '';
            break;
    }
    return $cnname;
}


/**
 *   切换大小写
 **/

 function uplow($str=''){
      if(preg_match('/^[a-z]+$/', $str)){
         
          $newstr= strtoupper($str);

      }elseif(preg_match('/^[A-Z]+$/', $str)){

          $newstr= strtolower($str);        
      }

      return $newstr;
 }


 /***
    **
    *   根据国家代码获取国家中文名称
    **/
    
    
    function getGjname($guojia=''){
        $guojia = strtoupper($guojia);
        switch($guojia){
        case 'US':
        $gjname = '美国';
        break;
        case 'GB':
        $gjname = '英国';
        break;
        case 'CA':
        $gjname = '加拿大';
        break;
        case 'FR':
        $gjname = '法国';
        break;
        case 'DE':
        $gjname = '德国';
        break;
        case 'MY':
        $gjname = '马来西亚';
        break;
        case 'TW':
        $gjname = '台湾';
        break;
        case 'NO':
        $gjname = '挪威';
        break;
        case 'CH':
        $gjname = '瑞士';
        break;
        case 'BE':
        $gjname = '比利时';
        break;
        case 'AT':
        $gjname = '奥地利';
        break;
        case 'AF':
        $gjname = '阿富汗';
        break;
        case 'IT':
        $gjname = '意大利';
        break;
        case 'SE':
        $gjname = '瑞典';
        break;
        case 'AU':
        $gjname = '澳大利亚';
        break;
        case 'JP':
        $gjname = '日本';
        break;
        case 'IE':
        $gjname = '爱尔兰';
        break;
        case 'AE':
        $gjname = '阿联酋';
        break;
        case 'DK':
        $gjname = '丹麦';
        break;
        case 'FI':
        $gjname = '芬兰';
        break;
        case 'ES':
        $gjname = '西班牙';
        break;
        case 'PL':
        $gjname = '波兰';
        break;
        case 'PT':
        $gjname = '葡萄牙';
        break;
        case 'LU':
        $gjname = '卢森堡';
        break;
        case 'LV':
        $gjname = '拉脱维亚';
        break;
        case 'GR':
        $gjname = '希腊';
        break;
        case 'BN':
        $gjname = '文莱';
        break;
        case 'CN':
        $gjname = '中国';
        break;
        case 'MQ':
        $gjname = '马提尼克岛';
        break;
        case 'SI':
        $gjname = '斯洛文尼亚';
        break;
        case 'SK':
        $gjname = '斯洛伐克';
        break;
        case 'NL':
        $gjname = '荷兰';
        break;
        case 'CZ':
        $gjname = '捷克';
        break;
        case 'LT':
        $gjname = '立陶宛';
        break;
        case 'CY':
        $gjname = '塞浦路斯';
        break;      
        case 'RO':
        $gjname = '罗马尼亚';
        break;
        case 'TH':
        $gjname = '泰国';
        break;
        case 'NZ':
        $gjname = '新西兰';
        break;
        case 'ZA':
        $gjname = '南非';
        break;
        case 'HU':
        $gjname = '匈牙利';
        break;      
        case 'RU':
        $gjname = '俄罗斯';
        break;
        case 'HK':
        $gjname = '香港';
        break;
        case 'BA':
        $gjname = '波黑';
        break;
        case 'EE':
        $gjname = '爱沙尼亚';
        break;
        case 'PH':
        $gjname = '菲律宾';
        break;
        case 'IL':
        $gjname = '以色列';
        break;
        default:
        $gjname = $guojia;
        }
        
        return $gjname;
        }



        function getCnname($sku){
        $arr = Db::name('product')
                  ->where("sku", $sku)
                  ->find();
        $cnname = $arr['cnname'];
        return $cnname;
    }


    function getoneworldshippingmethod($country){
        switch($country){
        case 'US':
        $shippingmethod = 'OWEPUT';
        break;
        case 'GB':
        $shippingmethod = 'OWEPT';
        break;
        case 'DE':
        $shippingmethod = 'OWEPT';
        break;
        default:
        $shippingmethod = 'OWEPT';
        break;
        }
        return $shippingmethod;
    }


/**
 *  getCronjobstatustext 获取任务状态文字
 *
 **/    

 function getCronjobstatustext($status){
    switch ($status) {
        case '1':
            $cronjobstatustext = '<span class="ui green  label">已成功执行</span>';
            break;
        case '-1':
            $cronjobstatustext = '<span class="ui red label">执行失败</span>';
            break;
        case '0':
            $cronjobstatustext = '<span class="ui  label">等待执行</span>';
            break;
        default:
            $cronjobstatustext = '<span class="ui warning label">未知</span>';
            break;
    }
            return $cronjobstatustext;

 }


 /**
 *  getCronjobtext 获取任务描述文字
 *
 **/   

 function getCronjobtext($type){
    switch ($type) {
        case 'markship':
            $cronjobtext = '给网站和客户标记发货中';
            break;
        case 'markshiped':
            $cronjobtext = '给网站和客户标记已发货';
            break;
        default:
            $cronjobtext = '未知内容';
            break;
    }
    return $cronjobtext;
 }


 /**
  *  getLeveltext 获取管理员级别
  *
  **/
 function getLeveltext($levelname){
    switch ($levelname) {
        case '2':
            $leveltext = '超级管理员';
            break;
        case '1':
            $leveltext = '管理员';
            break;

        default:
            $leveltext = '普通用户';
            break;
    }
    return $leveltext;
 }