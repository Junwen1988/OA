<?php
/**
 * 订单内容类
 * Created by sublime.
 * User: Zhangjunwen
 * Date: 2016/7/28
 * Time: 10:24
 */

namespace app\index\controller;
use think\Controller;
use think\Config;
use think\Db;
use think\Request;
use app\index\model\Product as ProductModel;

class Ordercon extends Base
{

	//更新订单中产品数量
	public function updateQty(){
		$postdata = input('post.');  
        foreach ($postdata['id'] as $k=>$v) {
        	$data  = ['id' => $v, 'qty'=> $postdata['qty'][$k] ];
        	$result = Db::name('ordercon')
	 	         ->update($data);
        }
	 	
	 		$data = ['status'=>'1','msg'=>'更新成功'];
	 		return $data;

	}

  //处理拆分订单
	public function splitOrder(){
		$postdata = input('post.'); 
		$ordernum = $postdata['ordernum'];
		$ids = $postdata['id'];
	
		//先获取订单信息
		$orderinfo = Db::name('orders')
		           ->where('ordernum',$ordernum)
		           ->find();
		$newsplit = $orderinfo['split']+1;
		$newordernum = $ordernum.'-'.$newsplit;

        //建立新订单
         $data = ['ordernum'=>$newordernum,
                  'firstname'=>$orderinfo['firstname'],
                  'lastname'=>$orderinfo['lastname'],
                  'telephone'=>$orderinfo['telephone'],
                  'email'=>$orderinfo['email'],
                  'zipcode'=>$orderinfo['zipcode'],
                  'city'=>$orderinfo['city'],
                  'prov'=>$orderinfo['prov'],
                  'country'=>$orderinfo['country'],
                  'address'=>$orderinfo['address'],
                  'address2'=>$orderinfo['address2'],
                  'postmode'=>$orderinfo['postmode'],
                  'paymode'=>$orderinfo['paymode'],
                  'weight'=>$orderinfo['weight'],
                  'shipping_fee'=>$orderinfo['shipping_fee'],
                  'customer_note'=>$orderinfo['customer_note'],
                  'ordertime'=>$orderinfo['ordertime'],
                  'orderstate'=>'wprocessing',
                  'orderalert'=>$orderinfo['orderalert'],
                  'customer_ip'=>$orderinfo['customer_ip'],
                  'customer_id'=>$orderinfo['customer_id'],
                  'order_note'=>$orderinfo['order_note'],
                  ];

        $result = Db::name('orders')->insert($data); 

        if($result==1){
           //更新订单中产品归属
           foreach ($ids as $v) {
           	$condata  = ['id' => $v, 'ordernum'=> $newordernum ];
        	Db::name('ordercon')
	 	         ->update($condata);
           }
           //更新主订单 split 
          Db::name('orders')
              ->where('ordernum', $ordernum)
              ->setField('split', $newsplit);

          $rdata = ['status'=>'1','msg'=>'拆分成功'];
	 	  return $rdata;

        }



	}

//新函数





}


?>