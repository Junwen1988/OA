<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:28:"../templates/public/404.html";i:1467102898;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>404错误-管理系统</title>
 <link type="text/css" rel="stylesheet" href="__CSS__/semantic.min.css">
 <link type="text/css" rel="stylesheet" href="__CSS__/style.css">
 <script type="text/javascript" src="__JS__/jQuery.min.js"></script>
 <script type="text/javascript" src="__JS__/semantic.min.js"></script>
  <style type="text/css">
       body, html{ height: 100%; margin: 0; padding: 0; }
       body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
   
  </style>
</head>
<body>

<div class="ui  middle aligned center aligned grid">
  <div class="column error404">
      <div style="font-size:2em;"><i style="font-size:10em;" class="icon frown"></i></div>
      <div style="margin-top:-120px;">
      <h1 style="font-size:5em;">404</h1>
      <p style="font-size:2em;">您无权操作或功能尚未开放，敬请期待...</p>
      <p><a class="massive  ui button blue" href="<?php echo url('index/index'); ?>">回首页</a> <a class="massive  ui button gray" href="javascript:history.go(-1);">返回</a></p>
      </div>
  </div>
</div>

</body>

</html>
