<?php
/**
 * 首页类
 * Created by sublime.
 * User: Zhangjunwen
 * Date: 2016/6/16
 * Time: 17:45
 */

namespace app\index\controller;
use think\Controller;
use think\Config;
use think\Db;
class Base extends Controller
{

	public function _empty()
    {
        return $this->fetch('public/404');
    }

    public function _initialize()
    {
         if(empty(session('userdata'))){
            if(empty(cookie('userdata'))){
               $this->redirect(url('other/login'));                
                }else{

                    $admin = cookie('userdata');
                    $admin = decrypt($admin);
                    $userdata = $this->get_userdata($admin);
                    if(!empty($userdata)){ //Cookies正确 
                         //设置SESSION
                    $userdata2 = array('username' => $userdata['username'], 'level'=>$userdata['levelname'], 'nickname'=>$userdata['nickname'] );
                    session('userdata', $userdata2);

                    $userdata = session('userdata');  
                    $this->assign('userdata', $userdata);

                    }else{
                      $this->redirect(url('other/login'));                
                    }


                       
               }
            }else{
                       $userdata = session('userdata');  
                       $this->assign('userdata', $userdata);   

            }

    
    }


   

    /**
     *  根据用户名获取用户信息 并写入SESSION
     **/

    protected function get_userdata($admin=''){
            $userdata = Db::name('admin')
                          ->where('username',$admin)
                          ->find();
            return $userdata;
    }


    /**
     * 设置要返回的页面url cookie
     */
    protected function set_forward()
    {
        cookie('__FORWARD__', $_SERVER['REQUEST_URI']);
    }

    /**
     * 获取要返回的页面url cookie
     */
    protected function get_forward()
    {
        return cookie('__FORWARD__');
    }


   

}
