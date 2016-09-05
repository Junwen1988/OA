<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:29:"../templates/index/index.html";i:1472874475;s:31:"../templates/2columns-left.html";i:1472876196;s:31:"../templates/public/header.html";i:1473067645;s:31:"../templates/index/sidebar.html";i:1473072805;s:31:"../templates/public/footer.html";i:1472875814;}*/ ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $meta_title; ?>-管理系统</title>
<link type="text/css" href="__CSS__/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="__CSS__/flat-ui.min.css" rel="stylesheet">
<link type="text/css" href="__CSS__/style.css" rel="stylesheet">
<link rel="shortcut icon" href="__IMG__/favicon.ico">
<script type="text/javascript" src="__JS__/jQuery.min.js"></script>
<script type="text/javascript" src="__JS__/flat-ui.min.js"></script>
<script type="text/javascript" src="__JS__/common.js"></script>
<!--[if lt IE 9]>
  <script src="__JS__/vendor/html5shiv.js"></script>
  <script src="__JS__/vendor/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="__JS__/echarts.min.js"></script>

</head>

<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器， 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
    以获得更好的体验！</p>
<![endif]-->

 <!-- Static navbar -->
    <div class="navbar navbar-inverse navbar-embossed navbar-fixed-top" role="navigation">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-3 col-lg-2"> </div>
        <div class="col-md-9 col-lg-10">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">菜单</span>
          </button>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">系统首页</a></li>
            <li><a href="#about">新闻公告</a></li>
            <li><a href="#about">客户管理 <span class="navbar-new">1</span></a></li>
            <li><a href="#about">订单管理</a></li>
            <li><a href="#about">下载中心</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="">Hi~ <?php echo $userdata['nickname']; ?> </a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">系统设置 <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">个人资料</a></li>
                <li><a href="#">修改密码</a></li>
                <li><a href="#">退出系统</a></li>
               
              </ul>
            </li>
            <li><a href="">帮助</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
      </div>
      </div>
    </div>


<div class="container-fluid mt80">
<div class="row">
 <div class="col-md-3 col-lg-2">
   
 <div class="panel panel-inverse" >
  <div class="panel-heading"><span class="glyphicon glyphicon-list"></span> 快捷菜单</div>
  <div class="left-menu-box">
  <div class="nav nav-pills nav-stacked">
    <li class="active "><a  href="Tongji/index">本月统计 <span class="badge">14</span></a></li>
    <li><a  href="#">日程管理</a></li>
    <li><a  href="">流程管理</a></li>
    <li><a  href="">我的消息</a></li>
  </div>
</div>
</div>


 </div>
 <!--end left sidebar-->
 <div class="col-md-9">
   


 </div>
</div>
</div>


<div class="footer">
<div class="container">
 	<p class="copyright text-center"><strong>Coil Master 管理系统 </strong> 仅供内部使用 2014~2016 （最佳分辨率1920*1080 请勿在低于1366*768分辨率下使用本系统）</p>	
</div>
</div>







</body>
</html>
