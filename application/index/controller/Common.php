<?php
/**
 * 公共类
 * Created by sublime.
 * User: Zhangjunwen
 * Date: 2016/8/9
 * Time: 10:45
 */

namespace app\index\controller;
use think\Controller;
use think\Config;
use think\Db;
use think\Request;
class Common extends Base
{
   



/**
  *   根据国家获取省份 洲
 **/ 

public function getState($code){
  $country = Db::name('country')
                ->where('iso_code_2', $code)
                ->find();
  $id = $country['country_id'];
  $statelist = Db::name('zone')
               ->where('country_id', $id)
               ->select();

  return $statelist;
}

/**
  *   返回省份 洲 HTML
 **/ 

public function getStateHtml(){
	$code = input('post.id');
	$htmlstr = '';
	$statelist = $this->getState($code);
    foreach ($statelist as $state) {
     	$htmlstr = $htmlstr.'<option value="'.$state['code'].'">'.$state['name'].'</option>';
    }
	return $htmlstr;
}

}