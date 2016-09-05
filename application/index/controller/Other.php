<?php
/**
 * 公共类
 * Created by sublime.
 * User: Zhangjunwen
 * Date: 2016/6/16
 * Time: 17:45
 */

namespace app\index\controller;
use think\Controller;
use think\Config;
use think\Db;
use think\Request;
class Other extends Controller
{
    

   public function login(){

        //检测COOKIES SESSION
   	     if(!empty(session('userdata'))){
            $this->redirect(url('index/index'));
   	     }
   	     if(!empty(cookie('userdata'))){
                    $admin = cookie('userdata');
                    $admin = decrypt($admin);
                    $userdata = $this->get_userdata($admin);
                    if(!empty($userdata)){ //Cookies正确 
                         //设置SESSION
                    $userdata2 = array('username' => $userdata['username'], 'level'=>$userdata['levelname'], 'nickname'=>$userdata['nickname'] );
                    session('userdata', $userdata2);
                    $this->redirect(url('index/index'));
                    }
   	     }

        //处理登录请求

        if (Request::instance()->isPost()){

            $data = input('post.');

            //查询上次请求登录时间 和 错误次数

            $lastdata = Db::name('admin')
                          ->where('username', $data['username'])
                          ->find();

            if(!empty($lastdata)){

            	  $ip = Request::instance()->ip();
                
                //上次请求时间与本次之间的时间间隔

                 $lasterrortime = $lastdata['lasterrortime'];
                 $timecountmin = (time() - $lasterrortime)/60 ;

            	if($lastdata['error'] >5 && $timecountmin<15){ // 15分钟内登录次数超过5次
            		echo '{"status":"-1"}'; // 禁止登录
            		exit;
            	}else{  //处理登录
                     //超过15分钟后清零
                     if($lastdata['error'] >5 && $timecountmin>15){ 
                          Db::name('admin')
                            ->where('username', $data['username'])
                            ->update(['error'=>'1', 'lasterrortime'=>time(), 'errorip'=>$ip]);
            	     }
            		
            		// 创建加密密码
            		$password = encrypt($data['password']);
                    //到数据库检验用户名密码是否正确
                    $result = Db::name('admin')
                            ->where('username', $data['username'])
                            ->find();
                    if($password == $result['password']){ // 密码正确 登录成功

                     $userdata = array('username' => $result['username'], 'level'=>$result['levelname'], 'nickname'=>$result['nickname'] );
                     $userdataencrypt = encrypt($result['username']); 
                      //设置SESSION
                      session('userdata', $userdata);

                      //设置COOKIES
                      if(isset($data['keeplogin']) && $data['keeplogin']=='on'){
                         cookie('userdata', $userdataencrypt, 3600 * 24 * 7);
                      }
                       
                      //更新用户登录信息
                          Db::name('admin')
                            ->where('username', $data['username'])
                            ->update(['loginip'=>$ip,'error'=>'0','logintime'=>time()]);
                      //跳转到首页
                      echo '{"status":"1"}';
                      exit;

                    }else{ // 密码错误

                    //增加错误次数 更新最后请求时间
                    $errortimes = $result['error'] + 1; 
                    $result = Db::name('admin')
                            ->where('username', $data['username'])
                            ->update(['error'=>$errortimes, 'lasterrortime'=>time(), 'errorip'=>$ip]);

                    echo '{"status":"-2"}';
                    exit;

                    }

            	}
            	  
            }else{
              echo '{"status":"0"}';
              exit;
            }

            
            //错误次数没达到 执行登录操作
            	            	            
   	    }else{
   	       return $this->fetch();
	
   	    }

   }


  /**
   *  退出登录
  **/
   
   public function logout(){
   	  session('userdata', null);
   	  cookie('userdata', null);
   	  $this->redirect(url('other/login'));
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


}