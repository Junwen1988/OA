<?php
/**
 * 用户控制器
 * Created by sublime.
 * User: Zhangjunwen
 * Date: 2016/8/16
 * Time: 11:52
 */

namespace app\index\controller;
use think\Controller;
use think\Config;
use think\Db;
use think\Request;
class User extends Base
{
    public function index()
    {

       //获取所有用户
         $userlist = Db::name('admin')
                        ->order('id desc')
                        ->paginate(10);
        $page = $userlist->render();
        $this->assign('userlist', $userlist);
        $this->assign('page', $page);
        $this->assign('current','user');
        $this->assign('currentleft','list');
    	  $this->assign('meta_title','用户列表');
        return $this->fetch();
    }

   /**
     *  已冻结的用户列表
    **/

    public function stopuser()
    {

       //获取冻结的用户
        $userlist = Db::name('admin')
                        ->where('checkadmin', 'false')
                        ->order('id desc')
                        ->paginate(10);
        $page = $userlist->render();
        $this->assign('userlist', $userlist);
        $this->assign('page', $page);
        $this->assign('current','user');
        $this->assign('currentleft','stopuser');
        $this->assign('meta_title','已冻结用户列表');
        return $this->fetch();
    }

    /**
     *  添加管理员
    **/
    public function add(){
        if (Request::instance()->isPost()){
          $user  = input('post.');
          $user['create_at'] = time();
          //查询用户是否已存在
          $isin = Db::name('admin')->where('username', $user['username'])->find();
          if($isin['id'] !=''){ //如果存在
             $data = ['status'=>'-2','msg'=>'该用户名：'.$isin['username'].' 已存在'];
             return  $data;
          }else{ //不存在 则增加
             

             $password = encrypt($user['password']); 
             $userdata  = array( 'username' =>$user['username'], 
                                 'nickname'=>$user['nickname'],
                                 'checkadmin'=>$user['checkadmin'],
                                 'levelname'=>$user['levelname'],
                                 'password'=>$password,
                                 'creat_at' => time()
                                  );

             $result = Db::name('admin')->insert($userdata);
             if($result){
                 $data = ['status'=>'1','msg'=>'添加成功'];
                 return  $data;
               }else{
                 $data = ['status'=>'-1','msg'=>'添加失败'];
                 return  $data;
               }

          }

        }else{
        $this->assign('current','user');
        $this->assign('currentleft','add');
        $this->assign('meta_title','添加用户');
        return $this->fetch(); 
        }
      

    }


   /**
     *  查看管理员
    **/

   public function view($id=''){
      $user = Db::name('admin')->where('id', $id)->find();
      $this->assign('user', $user);
      $this->assign('current','user');
      $this->assign('currentleft','list');
      $this->assign('meta_title','查看用户');
      return $this->fetch(); 

   }
  
  
    /**
     *  更新管理员信息
    **/
    public function edit($id=''){
        if (Request::instance()->isPost()){
             $user  = input('post.');
             $userdata = ["nickname"=>$user['nickname'], "checkadmin"=>$user['checkadmin'], "levelname"=>$user['levelname']];
             if($user['password']!=''){
               $userdata['password'] = encrypt($user['password']); 
             }

         

             $result = Db::name('admin')->where('id', $user['id'])->update($userdata);
             if($result>0){
                 $data = ['status'=>'1','msg'=>'更新成功'];
                 return  $data;
               }elseif($result == 0){
                 $data = ['status'=>'-1','msg'=>'无更新'];
                 return  $data;
               }else{
                 $data = ['status'=>'-1','msg'=>'更新失败'];
                 return  $data;
               }
           
        }else{
        $user = Db::name('admin')->where('id', $id)->find(); 
        $this->assign('user', $user);    
        $this->assign('current','list');
        $this->assign('currentleft','');
        $this->assign('meta_title','修改用户信息');
        return $this->fetch(); 
        }
      

    }



   /**
     *  删除用户
    **/

   public function delete(){

      if (Request::instance()->isGet()){

          $id  = input('get.id');
          $result = Db::name('admin')->where('id', $id)->delete();
         if ($result>0) {
               $data = ['status'=>'1', 'msg'=>'已成功删除用户'];
               return $data;
             } else {
                $data = ['status'=>'-1', 'msg'=>'删除失败'];
                return $data;
           }
          
         
        }else{
         $this->redirect('public/404');
        }    
          
   }

  /**
    *  用户信息查看
   **/ 

  public function profile(){
    $userdata = session('userdata');
    $username = $userdata['username']; 
    $userinfo = Db::name('admin')->where('username', $username)->find();
    $this->assign('user', $userinfo);    
    $this->assign('current','system');
    $this->assign('currentleft','user');
    $this->assign('meta_title','用户信息');
    return $this->fetch(); 
  }


   /**
    *  密码修改
   **/ 

  public function changepassword(){
   
        $this->assign('current','system');
        $this->assign('currentleft','user');
        $this->assign('meta_title','修改密码');
        return $this->fetch(); 

}


   /**
    *  密码保存
   **/ 

  public function savepassword(){

      $postdata = input('post.');   
      $oldpassword = encrypt($postdata['oldpassword']);
      $newpassword = encrypt($postdata['password']);

      //核对原密码是否正确
      $userdata = session('userdata');
      $username = $userdata['username']; 
      $currentuser = Db::name('admin')->where('username', $username)->find();
      if($currentuser['password']==$oldpassword){
         $result = Db::name('admin')->where('username', $username)->setField('password', $newpassword);
         if($result){
           $data = ['status'=>'1', 'msg'=>'密码修改成功'];
            return $data;
         }
      }else{
        $data = ['status'=>'-1', 'msg'=>'原密码不正确'];
        return $data;
      }

}


}
