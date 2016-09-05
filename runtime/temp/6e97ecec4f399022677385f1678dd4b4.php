<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:29:"../templates/other/login.html";i:1472874558;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>用户登录</title>
  <link type="text/css" href="__CSS__/bootstrap.min.css" rel="stylesheet">
  <link type="text/css" href="__CSS__/flat-ui.min.css" rel="stylesheet">
  <link type="text/css" href="__CSS__/style.css" rel="stylesheet">
  <link rel="shortcut icon" href="__IMG__/favicon.ico">
  <script type="text/javascript" src="__JS__/jQuery.min.js"></script>
  <script type="text/javascript" src="__JS__/flat-ui.min.js"></script>
  <!--[if lt IE 9]>
      <script src="__JS__/vendor/html5shiv.js"></script>
      <script src="__JS__/vendor/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<div class="container login-container">
<div class="login mt50">
        <div class="login-screen">
          <div class="login-icon">
            <img src="__IMG__/login/icon.png" alt="Welcome to Mail App">
            <h4>Welcome to <small>Mail App</small></h4>
          </div>
          <form name="loginform" id="loginform" class="ui large form" action="<?php echo url('Other/login'); ?>" method="post">
          <div class="login-form">
            <div class="control-group">
              <input type="text" name="username" class=" form-control login-field username required" value="" placeholder="用户名" id="login-name">
              <label class="form-control-feedback fui-user" for="login-name"></label>
            </div>

            <div class="control-group">
              <input type="password" name="password" class="login-field form-control password required" value="" placeholder="密码" id="login-pass">
              <label class="form-control-feedback fui-lock" for="login-pass"></label>
            </div>

            <a id="submit" class="btn btn-primary btn-large btn-block" href="#">登录</a>
            <a class="login-link" href="#">忘记密码?</a>
          </div>
        </div>
        </form>
      </div>
</div>
<script>

$("body").bind('keyup',function(event) {  
    if(event.keyCode==13){  
        var noempty = 1;
         $(".required").each(function(){
              if($(this).val()==''){
                 $(this).parent(".control-group").addClass("has-error");
                 $(this).removeClass('login-field');
                 noempty = 0;
              }
         });
        
        if(noempty == 1){

            //普通登录

            
            //通过检查 Ajax登录
            $.post("<?php echo url('other/login'); ?>", $("#loginform").serialize(), function(data){
              if(data.status == '1'){
                  location.href = '<?php echo url('index/index'); ?>';
               }
               if(data.status == '-1'){
                  $(".message").html('密码错误次数过多，已被禁止登录！请15分钟后再试.').fadeIn();
               }
               if(data.status == '0'){
                  $(".message").html('用户不存在或已被禁用！').fadeIn();
               }
               if(data.status == '-2'){
                  $(".message").html('密码错误！请输入正确密码.').fadeIn();
               }
            }, "json");
          
           // location.href="<?php echo url('index/index'); ?>";



        }else{
          return false;
        }
    }     
});   

    $("#submit").click(function(){
         var noempty = 1;
         $(".required").each(function(){
              if($(this).val()==''){
                 $(this).parent(".control-group").addClass("has-error");
                 $(this).removeClass('login-field');
                 noempty = 0;
              }
         });
        
        if(noempty == 1){

            //普通登录

           //$("#loginform").submit();
            
            //通过检查 Ajax登录
            $.post("<?php echo url('other/login'); ?>", $("#loginform").serialize(), function(data){
              if(data.status == '1'){
                  location.href = '<?php echo url('index/index'); ?>';
               }
               if(data.status == '-1'){
                  $(".message").html('密码错误次数过多，已被禁止登录！请15分钟后再试.').fadeIn();
               }
               if(data.status == '0'){
                  $(".message").html('用户不存在或已被禁用！').fadeIn();
               }
               if(data.status == '-2'){
                  $(".message").html('密码错误！请输入正确密码.').fadeIn();
               }
            }, "json");
          
           // location.href="<?php echo url('index/index'); ?>";



        }else{
          return false;
        }
    });

    $(".required").focus(function(){
        $(this).parent(".control-group").removeClass("has-error");
        $(this).addClass('login-field');

    });
  </script>
</body>

</html>
