<?php
/**
 * 订单类
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
use app\index\model\Orders as Ordermodel;
use app\index\model\Product as ProductModel;
use app\index\controller\Gucang;
use app\index\controller\Epack;
class Order extends Base
{

    
    public function index()
    {

        //获取所有订单

         $orderlist = $this->getOrderList();
         $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft','alllist');
      $this->assign('meta_title','订单列表');
       return $this->fetch();
    }

   // 待处理订单

   public function wprocessing()
    {

        //获取所有订单

         $orderlist = $this->getOrderList('wprocessing');
         $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft','wprocessing');
      $this->assign('meta_title','待处理订单');
       return $this->fetch();
    }

   // 处理中订单
  
    public function processing()
    {

        //获取所有订单

         $orderlist = $this->getOrderList('processing');
         $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft','processing');
      $this->assign('meta_title','处理中订单');
       return $this->fetch();
    }

   //待付款订单
    public function pending()
    {

        //获取所有订单

         $orderlist = $this->getOrderList('pending');
         $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft','pending');
      $this->assign('meta_title','待付款订单');
       return $this->fetch();
    }

       //缺货订单
    public function outofstock()
    {

        //获取缺货订单

         $orderlist = $this->getOrderList('outofstock');
         $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft','onhold');
      $this->assign('meta_title','缺货订单');
       return $this->fetch();
    }


       //有争议订单
    public function dispute()
    {

        //获取争议订单

         $orderlist = $this->getOrderList('dispute');
         $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft','onhold');
      $this->assign('meta_title','争议中的订单');
       return $this->fetch();
    }


   //挂起的订单
    public function onhold()
    {

         $orderlist = $this->getOrderList('dispute', 'outofstock');
         $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft','onhold');
      $this->assign('meta_title','挂起的订单');
       return $this->fetch();
    }



   // 已完成订单
 public function completed()
    {

        //获取所有订单

         $orderlist = $this->getOrderList('completed');
         $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft','completed');
      $this->assign('meta_title','已完成订单');
       return $this->fetch();
    }
   
//搜索订单

    public function orderSearch(){
      $keyword = trim(input('get.keyword'));
      $stype = input('get.stype');
     
     $orders = new Ordermodel();
         if($stype == 'ordernum'){
         $orderlist = $orders
                        ->where('orderfrom', 'site')
                        ->where('ordernum', $keyword)
                        ->order('ordertime desc')
                        ->paginate(10);
                      }elseif($stype == 'email'){
        $orderlist = $orders
                        ->where('orderfrom', 'site')
                        ->where('email', $keyword)
                        ->order('ordertime desc')
                        ->paginate(10);
                      }elseif($stype == 'name'){
        $orderlist = $orders
                        ->where('orderfrom', 'site')
                        ->where('firstname', 'like', $keyword)
                        ->whereOr('lastname', 'like', $keyword)
                        ->order('ordertime desc')
                        ->paginate(10);               
                      }else{
         $orderlist = $orders
                        ->where('orderfrom', 'site')
                        ->order('ordertime desc')
                        ->paginate(10);               
                      }
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
      $this->assign('current','order');
      $this->assign('currentleft','');
      $this->assign('meta_title', '"'.$keyword.'" 的搜索结果');
       return $this->fetch();
    }


  //筛选订单

  public function orderFilter(){
     $datestr = input('get.reservation');
     $country = input('get.country');

     //日期转化
   if($datestr !=''){
     session('map', null);
     $datearr = explode("-", $datestr);
     $starttime_str = $datearr[0].'00:00:00';
     $endtime_str = $datearr[1].' 00:00:00';
     
     $starttime = strtotime($starttime_str);
     $endtime = strtotime($endtime_str);
    }
     $orders = new Ordermodel();

     if($country != ''){
      session('map', null);
      $map['country'] = $country;
     }

     if($datestr != ''){
       $map['ordertime'] = ['between time', [$starttime, $endtime]];
     }
 
     if(empty(session('map'))){
        session('map', $map);
     }else{
      $map = session('map');
     }
     
     $orderlist = $orders
                        ->where($map)
                        ->where('orderfrom', 'site')
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
      $this->assign('current','order');
      $this->assign('currentleft','');
      $meta_title = '';
      if($datestr !=''){
        $meta_title = '从 '.$datearr[0].' 到 '.$datearr[1];
      }
      if($country != ''){
        $meta_title = $meta_title.' 国家为 '.getGjname(uplow($country)).' 的订单';
      }
      $this->assign('meta_title', $meta_title);
       return $this->fetch();
  }


   //修改订单状态
   public function mark(){
      $id = input('get.id');
      $action = input('get.action');
      
      Db::name('orders')->update(['id'=>$id, 'orderstate'=>$action]);
      $this->redirect($_SERVER['HTTP_REFERER']);

   }
   
   /**
    *  更新订单状态 
    *
    **/

   public function updatestatus(){
     $id = input('get.id');
     $order = new Ordermodel();
     $orderinfo = $order::get($id);
     //如果订单在处理中则 获取并更新发货状态
     if($orderinfo['orderstate']=='processing'){
        if($orderinfo['shipstate']=='gc'){ //如果发货方式为 谷仓 则调用谷仓方法
           $gc = new Gucang();
           $result = $gc->getSingleorder($id);
          // print_r($result);
           return $result;


        }
        if($orderinfo['shipstate']=='epack'){ //如果发货方式为 E邮宝 则调用E邮宝方法
           $epack = new Epack();
           $result = $epack->getSingleorder($id);
           //print_r($result);
           return $result;
        }
        if($orderinfo['shipstate']=='4px'){ //如果发货方式为 递四方 则调用递四方方法
           $px4 = new Px4();
           $result = $px4->getSingleorder($id);
           return $result;
        }
         if($orderinfo['shipstate']=='oneworld'){ //如果发货方式为 易时达 则调用易时达方法
           $oneworld = new Oneworld();
           $result = $oneworld->getSingleorder($id);
           return $result;
        }
     }
     //如果订单没有处理 则到网站获取订单的最新状态 并更新订单系统
     if($orderinfo['orderstate']=='wprocessing'|| $orderinfo['orderstate']=='pending'){

     }

   }

   // 查看订单详细

   public function view(){
      $id = input('get.id');
      $order = new Ordermodel();
      $orderinfo = $order::get($id);
      $ordercon = $orderinfo->orders;
      foreach ($ordercon as $k => $v) {
        $arr = Db::name('product')
                  ->where("sku", $v['sku'])
                  ->find();
        $ordercon[$k]['cnname'] = $arr['cnname'];

      }
      
      $this->assign('order', $orderinfo);
      $this->assign('ordercon', $ordercon);
      $this->assign('current','order');
      $this->assign('currentleft','');
      if($orderinfo['shipstate']!=''){
           $this->assign('leftopen', $orderinfo['shipstate']);
         } 
      $this->assign('meta_title','订单（#'.$orderinfo['ordernum'].'）详细');
       return $this->fetch();

   }


  //修改订单信息

    public function updateOrderinfo(){
       $orderinfo = input('post.');
       $result = Ordermodel::update($orderinfo);
             if(false !== $result){
                 $data = ['status'=>'1','msg'=>'更新成功'];
                 return  $data; 
               // echo '{"status":"1","msg":"更新成功"}';
               }else{
                $data = ['status'=>'-1','msg'=>'更新失败'];
                 return  $data;
                 //  echo '{"status":"-1","msg":"更新失败"}';

               }
   }

//删除订单

    public function deleteOrder(){
       $id = input('get.id');
       if($id !=""){ 

      $order = Ordermodel::get($id);
       $ordernum = $order['ordernum'];
       Ordermodel::where("id", $id)->delete(); 
       if($ordernum != ''){
           Db::name('ordercon')
                 ->where('ordernum', $ordernum)
                 ->delete();
         }

       }
      $this->redirect($_SERVER['HTTP_REFERER']);
   }


//处理订单备注

    public function orderNote(){
       $note = input('post.note');
       $ordernum = input('post.ordernum');
       if($ordernum ==''){
           $data = ['status'=>'-1','msg'=>'订单号不能为空'];
           return  $data;
       }
       Ordermodel::where('ordernum', $ordernum)->update(['order_note'=>$note]);
        $data = ['status'=>'1','msg'=>'更新成功！'];
        return  $data;

   }


    //修改订单产品信息

    public function updateOrdercon(){
      $id = input('get.id');
      $order = Ordermodel::get($id);
      $ordercon = $order->orders;
      foreach ($ordercon as $k => $v) {
        $arr = Db::name('product')
                  ->where("sku", $v['sku'])
                  ->find();
        $ordercon[$k]['cnname'] = $arr['cnname'];

      }

      $productlist = ProductModel::all();
      $this->assign('productlist', $productlist);
      $this->assign('order', $order);
      $this->assign('ordercon', $ordercon);
      $this->assign('current','order');
      $this->assign('currentleft','');
      if($order['shipstate']!=''){
           $this->assign('leftopen', $order['shipstate']);
         } 
      $this->assign('meta_title','订单（#'.$order['ordernum'].'）产品信息');
      return $this->fetch();
   }

//添加订单产品

 public function addOrderPro(){
    $sku = input('post.sku');
    $qty = input('post.qty');
    $ordernum = input('post.ordernum');
    //根据SKU获取产品信息
    $product = Db::name('product')
               ->where("sku", $sku)
               ->find();
    //判断库存是否充足
    
    if($product['qty']<$qty){ //库存不足
      $data = ['status'=>'0','msg'=>'库存不足，请检查！'];
      return $data;
    }  

   //库存够 则添加
      //先查询订单中是否有该产品
      $result = Db::name('ordercon')
              ->where("ordernum", $ordernum)
              ->where("sku", $sku)
              ->find();
      //如果已存在 则更新
     if($result !=''){
        $qty_ = $result['qty'] + $qty;
        Db::name('ordercon')
              ->where("ordernum", $ordernum)
              ->where("sku", $sku)
              ->setField('qty', $qty_);
         $data = ['status'=>'1','msg'=>'更新成功！'];
         return $data;

     }else{   //如果不存在 则添加 

       $condata  = array('ordernum'=>$ordernum, 'weight'=>$product['weight'], 'sku' =>$sku, 'name'=>$product['name'],'qty'=>$qty);

       Db::name('ordercon')
       ->insert($condata);  
       
        $data = ['status'=>'1','msg'=>'添加成功！'.$qty];
        return $data; 
     }

    
          
 }

// end 添加订单产品   
 

 //删除订单产品

 public function deleteOrderPro(){
    $id = input('post.id');
    if($id == ''){ 
       return  $data = ['status'=>'9','msg'=>'非法操作'];
    }
    $result = Db::name('ordercon')->delete($id);
    if($result > 0){
         $data = ['status'=>'1','msg'=>'删除成功'];
    }else{
        $data = ['status'=>'-1','msg'=>'删除失败'];
    }
   return $data;
 }

  // 获取订单函数

   protected function getOrderList($status='', $status2='')
    {

        //获取所有订单
         $orders = new Ordermodel();
         if($status2 != ''){
         $orderlist = $orders
                        ->where('orderfrom', 'site')
                        ->where('orderstate', $status)
                        ->whereOr('orderstate', $status2)
                        ->order('ordertime desc')
                        ->paginate(10);
                      }elseif($status != ''){
        $orderlist = $orders
                        ->where('orderfrom', 'site')
                        ->where('orderstate', $status)
                        ->order('ordertime desc')
                        ->paginate(10);
                      }else{
         $orderlist = $orders
                        ->where('orderfrom', 'site')
                        ->order('ordertime desc')
                        ->paginate(10);               
                      }
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

        return $orderlist;

    }

  // 根据发货状态获取订单函数

   protected function getOrderListByshipstate($status='', $status2='')
    {

        //获取所有订单
         $orders = new Ordermodel();
         if($status2 != ''){
         $orderlist = $orders
                        ->where('orderfrom', 'site')
                        ->where('shipstate', $status)
                        ->whereOr('shipstate', $status2)
                        ->order('ordertime desc')
                        ->paginate(10);
                      }elseif($status != ''){
        $orderlist = $orders
                        ->where('orderfrom', 'site')
                        ->where('shipstate', $status)
                        ->order('ordertime desc')
                        ->paginate(10);
                      }else{
         $orderlist = $orders
                        ->where('orderfrom', 'site')
                        ->order('ordertime desc')
                        ->paginate(10);               
                      }
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

        return $orderlist;

    }


 /************************************ 根据快递类别 区分订单列表 **********************************/

   // 已发送到谷仓订单
 public function gc()
    {
      $orderlist = $this->getOrderListByshipstate('gc');
      $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft', 'gc');
      $this->assign('leftopen', 'gc');
      $this->assign('meta_title','已发送到谷仓订单');
       return $this->fetch();
    }

   // 已通过谷仓发货的订单
 public function gced()
    {
      $orderlist = $this->getOrderListByshipstate('gced');
      $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft', 'gced');
      $this->assign('leftopen', 'gc');
      $this->assign('meta_title','已通过谷仓发货的订单');
       return $this->fetch();
    }


       // 已发送到E邮宝订单
 public function epack()
    {
      $orderlist = $this->getOrderListByshipstate('epack');
      $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft', 'epack');
      $this->assign('leftopen', 'epack');
      $this->assign('meta_title','已发送到E邮宝订单');
       return $this->fetch();
    }

   // E邮宝发货完成订单
 public function epacked()
    {
      $orderlist = $this->getOrderListByshipstate('epacked');
      $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft', 'epacked');
      $this->assign('leftopen', 'epack');
      $this->assign('meta_title','E邮宝发货完成订单');
       return $this->fetch();
    }

        // 已发送到递四方订单
 public function px4()
    {
      $orderlist = $this->getOrderListByshipstate('4px');
      $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft', '4px');
      $this->assign('leftopen', '4px');
      $this->assign('meta_title','已发送到递四方订单');
       return $this->fetch();
    }

   // 递四方发货完成订单
 public function px4ed()
    {
      $orderlist = $this->getOrderListByshipstate('4pxed');
      $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft', '4pxed');
      $this->assign('leftopen', '4px');
      $this->assign('meta_title','递四方发货完成订单');
       return $this->fetch();
    }

 // 已发送到易时达订单
 public function oneworld()
    {
      $orderlist = $this->getOrderListByshipstate('oneworld');
      $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft', 'oneworld');
      $this->assign('leftopen', 'oneworld');
      $this->assign('meta_title','已发送到易时达订单');
       return $this->fetch();
    }

 // 易时达发货完成订单
 public function oneworlded()
    {
      $orderlist = $this->getOrderListByshipstate('oneworlded');
      $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft', 'oneworlded');
      $this->assign('leftopen', 'oneworld');
      $this->assign('meta_title','易时达发货完成订单');
       return $this->fetch();
    }



     // 已发送到万邑通订单
 public function wyt()
    {
      $orderlist = $this->getOrderListByshipstate('wyt');
      $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft', 'wyt');
      $this->assign('leftopen', 'wyt');
      $this->assign('meta_title','已发送到万邑通订单');
       return $this->fetch();
    }

 // 万邑通发货完成订单
 public function wyted()
    {
      $orderlist = $this->getOrderListByshipstate('wyted');
      $page = $orderlist->render();
     
      $this->assign('orderlist', $orderlist);
      $this->assign('page', $page);
      $this->assign('current','order');
      $this->assign('currentleft', 'wyted');
      $this->assign('leftopen', 'wyt');
      $this->assign('meta_title','万邑通发货完成订单');
       return $this->fetch();
    }

 /****************************** end ***************************************/


   public function test(){
  
     $orderlist = Ordermodel::all();
     foreach ($orderlist as $order) {
              print_r($order->orders);

     }
  


   }
   

}
