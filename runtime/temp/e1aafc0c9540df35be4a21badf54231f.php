<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"D:\phpstudy\WWW\Tp5\admin\tp_bs_dms\public/../application/admin\view\login\index.html";i:1544524817;}*/ ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="http://localhost/Tp5/admin/tp_bs_dms/public/static/bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
    <title>后台登录</title>
    <!--backgound canvas style-->
    <style> 
      body, html {position: absolute;top: 0;bottom: 0;left: 0;right: 0;margin: 0;padding: 0;}
      #background {position: fixed;top: 0;left: 0;z-index: -100;}
      .card {box-shadow:0 8px 12px 0 rgba(0,0,0,0.16),0 4px 10px 0 rgba(0,0,0,0.12)!important;}
      .hide {display:none;}
    </style>
  </head>
  <body>
  <!--动态气泡背景-->
  <canvas id="background"></canvas>

    <div class="container">
      <div class="row" style="margin-top:20%;">
        <div class="col-12 col-md-6 col-lg-5 offset-md-3">
          <form method="POST" action="" class="text-center card bg-transparent p-2">
            <h3 class="mb-4 text-white">后台登录</h3>
              <div class="input-group input-group-lg mb-4">
                <div class="input-group-prepend">
                  <div class="input-group-text">账 号</div>
                </div>
                 <input type="text" class="form-control" name="username" value="" placeholder="请输入账号" required>
              </div>
              <div class="input-group input-group-lg mb-4">
                <div class="input-group-prepend">
                  <div class="input-group-text">密 码</div>
                </div>
                <input type="password" class="form-control" name="password" value="" placeholder="请输入密码" required> 
              </div>
              <div class="input-group input-group-lg mb-2 ml-4 text-white">
                <label class="">
                  <input type="checkbox" class="form-check-input" name="remember" value="0" style="width:20px;height: 20px;">&nbsp;&nbsp;一周免登录
                </label>
                <!-- <div class="col-6">
                  <a href="#" >忘记密码</a>
                </div> -->
              </div>
              <!--验证码-->
              <?php if($login_num>=3): ?>
              <div class="input-group input-group-lg mb-4 <{gt name='login_num' value=2}>hide<{gt}>" id="verify-img">
                <input type="hidden" name="login_num" value="{$login_num}">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <img src="<?php echo captcha_src(); ?>" id="verify-code" onclick="changeVerify(this)"/>
                  </div>
                </div>
                <input type="text" id="captcha" class="form-control" name="captcha" placeholder="验证码">
              </div>
              <?php endif; ?>
              <button type="button" class="btn btn-block btn-lg btn-primary" id="submit-btn">登 录</button>
              <!--alert error msg-->
              <div class="alert alert-danger alert-dismissible show hide">
                <span id="alert-msg"></span>
              </div>
          </form>
        </div>
      </div>
    </div>
    <!-- backgoud canvas JS -->
    <script src="http://localhost/Tp5/admin/tp_bs_dms/public/static/admin/js/background.min.js" crossorigin="anonymous"></script>
    <!-- jQuery  Bootstrap JS -->
    <script src="http://localhost/Tp5/admin/tp_bs_dms/public/static/bootstrap/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="http://localhost/Tp5/admin/tp_bs_dms/public/static/bootstrap/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
    var topLogin = "<?php echo $topLogin; ?>";
    if(topLogin != ''){
      top.location.href = topLogin;
    }
    
      //验证码刷新
      function changeVerify(e){ 
        e.src = "<?php echo captcha_src(); ?>?id=" + Math.random();
      }

      //信息提示框
      function showMsg(msg)
      {
        $('#alert-msg').html(msg);
        $('.alert').removeClass('hide');
        setTimeout("$('.alert').addClass('hide')",2000); //2秒后自动消失
      }

      $(function(){
        $('#submit-btn').click(function(){ //AJax提交
          var username = $("input[name='username']").val();
          var password = $("input[name='password']").val();
          var captcha = $("input[name='captcha']").val();
          var remember = $("input[name='remember']").is(":checked") ? 1 : 0;
          if(username=='' || password==''){
            showMsg('请输入账号或密码！');
            return false;
          }
          if($('#verify-img').hasClass('hide')!==true){ 
            if(captcha==''){
              $('#verify-code').attr('src', "<?php echo captcha_src(); ?>?id=" + Math.random());
              showMsg('请输入验证码！');
              return false;
            }
          }
          $.post(
            window.location.href, 
            {
              username: username,
              password: password,
              captcha: captcha,
              remember: remember
            },
            function(data, status){
              if(status=='success'){
                if(data.code==0){ 
                  showMsg(data.msg); //输出错误信息
                  if(data.data>2){ //登录失败3次后要验证码登录，防机器暴力登录
                    $('#verify-code').attr('src', "<?php echo captcha_src(); ?>?id=" + Math.random()); //登录失败后要刷新一下验证码
                  }
                }
                if(data.code===1){
                  window.location.href = data.url;
                }
              }
          });
        });
      });
      //回车Enter提交
      document.onkeydown = function (event) {
        var e = event || window.event;
        if (e && e.keyCode == 13) { //回车键的键值为13
            $("#submit-btn").click(); //调用登录按钮的登录事件
        }
      };


    </script>
  </body>
</html>