<?php
/**
 * 测试类
 * Created by sublime.
 * User: Zhangjunwen
 * Date: 2016/6/21
 * Time: 18:45
 */

namespace app\index\controller;
use think\Controller;
use think\Config;
use think\Db;
use think\Request;
class Test extends Controller
{
	public function index(){
		echo encrypt('123456');
		
	}
}