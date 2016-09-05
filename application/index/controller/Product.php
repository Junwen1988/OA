<?php
/**
 * 产品控制器
 * Created by sublime.
 * User: Zhangjunwen
 * Date: 2016/6/24
 * Time: 11:04
 */

namespace app\index\controller;
use think\Controller;
use think\Config;
use think\Db;
use think\Request;
use app\index\model\Product as ProductModel;
class Product extends Base
{
    public function index()
    {

       //获取所有产品
         $product = new ProductModel();
         $prolist = $product
                        ->order('id desc')
                        ->paginate(10);
        $page = $prolist->render();
        $this->assign('prolist', $prolist);
        $this->assign('page', $page);
        $this->assign('current','product');
        $this->assign('currentleft','list');
    	$this->assign('meta_title','产品列表');
        return $this->fetch();
    }

    /**
     *  添加产品
    **/
    public function add(){
        if (Request::instance()->isPost()){
          $product  = input('post.');
          $product['create_at'] = time();
          //查询SKU是否已存在
          $isin = ProductModel::getBySku($product['sku']);
          if($isin['id'] !=''){ //如果存在
             $data = ['status'=>'-2','msg'=>'该SKU：'.$isin['sku'].'已存在'];
             return  $data;
          }else{ //不存在 则增加
               
             $result = ProductModel::create($product);
             if($result['id']!=''){
                 $data = ['status'=>'1','msg'=>'产品添加成功'];
                 return  $data;
               }else{
                 $data = ['status'=>'-1','msg'=>'添加失败'];
                 return  $data;
               }

          }

        }else{
        $this->assign('current','product');
        $this->assign('currentleft','add');
        $this->assign('meta_title','添加产品');
        return $this->fetch(); 
        }
      

    }


   /**
     *  查看产品
    **/

   public function view($id=''){
      $product = ProductModel::get($id); 
      $this->assign('product', $product);
      $this->assign('current','product');
      $this->assign('currentleft','');
      $this->assign('meta_title','查看产品');
      return $this->fetch(); 

   }
  
  
    /**
     *  更新产品
    **/
    public function edit($id=''){
        if (Request::instance()->isPost()){
             $product  = input('post.'); 
             $product['update_at'] = time(); 
             $result = ProductModel::update($product);
             if(false !== $result){
                 $data = ['status'=>'1','msg'=>'产品更新成功'];
                 return  $data;
               }else{
                 $data = ['status'=>'-1','msg'=>'更新失败'];
                 return  $data;
               }
    
        }else{
        $product = ProductModel::get($id); 
        $this->assign('product', $product);    
        $this->assign('current','product');
        $this->assign('currentleft','');
        $this->assign('meta_title','修改产品');
        return $this->fetch(); 
        }
      

    }



   /**
     *  删除产品
    **/

   public function delete(){

      if (Request::instance()->isGet()){

          $id  = input('get.id');
          $product = ProductModel::get($id);
         if ($product) {
               $product->delete();
               $data = ['status'=>'1', 'msg'=>'删除产品成功'];
               return $data;
             } else {
                $data = ['status'=>'-1', 'msg'=>'该产品不存在，删除失败'];
                return $data;
           }
          
         
        }else{
         $this->redirect('public/404');
        }    
          
   }

   

}
