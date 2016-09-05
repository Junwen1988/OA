<?php
/**
 * 客户控制器
 * Created by sublime.
 * User: Zhangjunwen
 * Date: 2016/8/20
 * Time: 10:25
 */

namespace app\index\controller;
use think\Controller;
use think\Config;
use think\Db;
use think\Request;
use app\index\model\Orders as Ordermodel;

class Customer extends Base
{
    public function index()
    {

       //获取所有客户
        $customerlist = Db::name('customer')
                        ->order('id desc')
                        ->paginate(10);
        $page = $customerlist->render();
        $this->assign('customerlist', $customerlist);
        $this->assign('page', $page);
        $this->assign('current','customer');
        $this->assign('currentleft','list');
    	$this->assign('meta_title','客户列表');
        return $this->fetch();
    }

   /**
     *  未审核客户
    **/
   public function nocheckcustomer()
    {

       //获取所有未审核客户
        $customerlist = Db::name('customer')
                        ->where('status', '0')
                        ->order('id desc')
                        ->paginate(10);
        $page = $customerlist->render();
        $this->assign('customerlist', $customerlist);
        $this->assign('page', $page);
        $this->assign('current','customer');
        $this->assign('currentleft','nocheckcustomer');
        $this->assign('meta_title','未审核的客户');
        return $this->fetch('customer/index');
    }

  /**
     *  黑名单客户
    **/
   public function blackcustomer()
    {

       //获取所有拉黑客户
        $customerlist = Db::name('customer')
                        ->where('status', '-1')
                        ->order('id desc')
                        ->paginate(10);
        $page = $customerlist->render();
        $this->assign('customerlist', $customerlist);
        $this->assign('page', $page);
        $this->assign('current','customer');
        $this->assign('currentleft','blackcustomer');
        $this->assign('meta_title','未审核的客户');
        return $this->fetch('customer/index');
    }

   /**
     *  添加客户
    **/
    public function add(){
        if (Request::instance()->isPost()){
          $customer  = input('post.');
          $customer['create_at'] = time();
          //查询客户是否已存在
          $isin = Db::name('customer')->where('email', $customer['email'])->find();
          if($isin['id'] !=''){ //如果存在
             $data = ['status'=>'-2','msg'=>'该客户：'.$isin['email'].' 已存在'];
             return  $data;
          }else{ //不存在 则增加
             

             $customerdata  = array('firstname' =>$customer['firstname'], 
                                  'lastname'=>$customer['lastname'],
                                  'email'=>$customer['email'],
                                  'telephone'=>$customer['telephone'],
                                  'country'=>$customer['country'],
                                  'prov'=>$customer['prov'],
                                  'city'=>$customer['city'],
                                  'address'=>$customer['address'],
                                  'address2'=>$customer['address2'],
                                  'zipcode'=>$customer['zipcode'],
                                  'level'=>$customer['level'],
                                  'status'=>true,
                                  'creat_at' => time()
                                  );

             $result = Db::name('customer')->insert($customerdata);
             if($result){
                 $data = ['status'=>'1','msg'=>'添加成功'];
                 return  $data;
               }else{
                 $data = ['status'=>'-1','msg'=>'添加失败'];
                 return  $data;
               }

          }

        }else{
            
        $country = Db::name('country')->select();
        $this->assign('country', $country);
        $this->assign('current','customer');
        $this->assign('currentleft','add');
        $this->assign('meta_title','添加客户');
        return $this->fetch(); 
        }
      

    }


    /**
     *  查看客户
    **/

   public function view($id=''){
      $customer = Db::name('customer')->where('id', $id)->find();
      $country = Db::name('country')
                ->where('iso_code_2', $customer['country'])
                ->find();
      $country_id = $country['country_id'];
      $provname = Db::name('zone')
               ->where('country_id', $country_id)
               ->where('code', $customer['prov'])
               ->find();
      $customer['provname'] = $provname['name'];
      $orders = new Ordermodel();
      $orderlist = $orders
                        ->where('email', $customer['email'])
                        ->order('ordertime desc')
                        ->paginate(10);               
                      
        foreach ($orderlist as $k=>$order) {
            $ordercon = $order->orders;
            foreach ($ordercon as $kk => $con) {
                $arr = Db::name('product')
                  ->where("sku", $con['sku'])
                  ->find();
                $cnname = $arr['cnname'];
                $ordercon[$kk]['cnname'] = $cnname;
            }
            $orderlist[$k]['con'] = $ordercon;

        }
      $page = $orderlist->render();
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('customer', $customer);
      $this->assign('current','customer');
      $this->assign('currentleft','list');
      $this->assign('meta_title','查看客户信息');
      return $this->fetch(); 

   }


 /**
     *  更新管理员信息
    **/
    public function edit($id=''){
        
      $customer = Db::name('customer')->where('id', $id)->find();

      $country = Db::name('country')
                ->where('iso_code_2', $customer['country'])
                ->find();
      $country_id = $country['country_id'];
      $statelist = Db::name('zone')
               ->where('country_id', $country_id)
               ->select();
      $this->assign('statelist', $statelist);
      $provname = Db::name('zone')
               ->where('country_id', $country_id)
               ->where('code', $customer['prov'])
               ->find();
      $customer['provname'] = $provname['name'];
      $this->assign('customer', $customer);
      $this->assign('current','customer');
      $this->assign('currentleft','list');
      $this->assign('meta_title','修改客户信息');
      return $this->fetch(); 
      

    }

    /**
     *  保存客户信息
    **/
    
    public function save(){
      if (Request::instance()->isPost()){

          $customer  = input('post.');
          $id = $customer['id'];
          if($id != ''){
            $result = Db::name('customer')
                    ->update($customer);    
          }
          
          if($result){
                 $data = ['status'=>'1','msg'=>'更新成功'];
                 return  $data;
          }else{
             $data = ['status'=>'-1','msg'=>'更新失败'];
             return  $data;
          }     

      }else{

          $data = ['status'=>'-1','msg'=>'错误的请求'];
          return  $data;

      }
    }


     /**
     *  删除客户
    **/

   public function delete(){

      if (Request::instance()->isGet()){

          $id  = input('get.id');
          $result = Db::name('customer')->where('id', $id)->delete();
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
       *  统计
      **/

   public function tongji(){
      $this->assign('current','customer');
      $this->assign('currentleft','tongji');
      $this->assign('meta_title','客户统计');
      return $this->fetch(); 
   }



}