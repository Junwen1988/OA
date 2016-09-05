<?php
/**
 * 首页类
 * Created by sublime.
 * User: Zhangjunwen
 * Date: 2016/6/16
 * Time: 17:45
 */

namespace app\index\controller;
use think\Db;
use think\Request;

class Index extends Base
{
    public function index()
    {
    	$this->assign('current','home');
      $this->assign('currentleft','tongji');
    	$this->assign('meta_title','系统首页');
        return $this->fetch();
    }

   public function test(){
   	  $request = Request::instance();
   	  echo $request->ip();
   } 

}
