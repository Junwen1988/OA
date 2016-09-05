<?php
/**
 * 计划任务类
 * Created by sublime.
 * User: Zhangjunwen
 * Date: 2016/8/15
 * Time: 09:45
 */

namespace app\index\controller;
use think\Controller;
use think\Config;
use think\Db;
use think\Request;
class Cronjob extends Base
{
   
/**
  *   计划任务列表
 **/ 

public function index(){
   $cronjob_list = Db::name('cronjob')
                   ->order('creat_at desc')
                   ->paginate(15);
   $page = $cronjob_list->render();
   $this->assign('page', $page);
   $this->assign('cronjoblist', $cronjob_list);
   $this->assign('current','cronjob');
   $this->assign('currentleft','index');
   $this->assign('meta_title','任务列表');
   return $this->fetch();
}

/**
  *    等待执行任务列表
 **/ 

public function wait(){
   $cronjob_list = Db::name('cronjob')
                   ->where('status', '0')
                   ->order('creat_at desc')
                   ->paginate(15);
   $page = $cronjob_list->render();
   $this->assign('page', $page);
   $this->assign('cronjoblist', $cronjob_list);
   $this->assign('current','cronjob');
   $this->assign('currentleft','wait');
   $this->assign('meta_title','等待执行任务列表');
   return $this->fetch();
}


/**
  *   执行失败任务列表
 **/ 

public function failt(){
   $cronjob_list = Db::name('cronjob')
                   ->where('status', '-1')
                   ->order('creat_at desc')
                   ->paginate(15);
   $page = $cronjob_list->render();
   $this->assign('page', $page);
   $this->assign('cronjoblist', $cronjob_list);
   $this->assign('current','cronjob');
   $this->assign('currentleft','failt');
   $this->assign('meta_title','失败任务列表');
   return $this->fetch();
}



/**
  *   执行完成任务列表
 **/ 

public function completed(){
   $cronjob_list = Db::name('cronjob')
                   ->where('status', '1')
                   ->order('creat_at desc')
                   ->paginate(15);
   $page = $cronjob_list->render();
   $this->assign('page', $page);
   $this->assign('cronjoblist', $cronjob_list);
   $this->assign('current','cronjob');
   $this->assign('currentleft','completed');
   $this->assign('meta_title','已执行成功任务列表');
   return $this->fetch();
}


/**
  *    删除任务
 **/ 

public function delete(){
   $id = input('get.id');
   if($id == ''){
      $this->redirect('public/404');          
   }else{

   $result =  Db::name('cronjob')
       ->where('id', $id)
       ->delete();
  if($result){
    $data = ['status'=>'1', 'msg'=>'删除成功'];
    return $data;
  }else{
    $data = ['status'=>'-1', 'msg'=>'删除失败'];
    return $data;
  } 
}

}


/**
  *    任务
 **/ 


}
