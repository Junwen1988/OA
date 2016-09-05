<?php
/**
 * 订单类
 * Created by sublime.
 * User: Zhangjunwen
 * Date: 2016/6/16
 * Time: 17:45
 */

namespace app\index\controller;
class Tongji extends Base
{
    public function index()
    {
    	$this->assign('current','tongji');
    	$this->assign('meta_title','数据统计');
        return $this->fetch();
    }

   

}
