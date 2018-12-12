<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:85:"D:\phpstudy\WWW\Tp5\admin\tp_bs_dms\public/../application/admin\view\index\index.html";i:1542945906;s:75:"D:\phpstudy\WWW\Tp5\admin\tp_bs_dms\application\admin\view\layout\base.html";i:1544177823;s:44:"../application/admin/view/layout/header.html";i:1543548488;s:47:"../application/admin/view/layout/left_menu.html";i:1543549291;s:42:"../application/admin/view/layout/main.html";i:1543548149;s:44:"../application/admin/view/layout/footer.html";i:1544441075;}*/ ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="http://localhost/Tp5/admin/tp_bs_dms/public/static/bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
    <!-- FontAwesome CSS 所有图标请参照http://www.fontawesome.com.cn/faicons/-->
    <link rel="stylesheet" href="http://localhost/Tp5/admin/tp_bs_dms/public/static/fontAwesome/css/font-awesome.min.css" crossorigin="anonymous">
    <!--bootstrap-table CSS-->
    <link rel="stylesheet" href="http://localhost/Tp5/admin/tp_bs_dms/public/static/bootstrap-table/bootstrap-table.min.css" crossorigin="anonymous">
    <!-- admin-main CSS -->
    <link rel="stylesheet" href="http://localhost/Tp5/admin/tp_bs_dms/public/static/admin/css/main.css" crossorigin="anonymous">
    <!-- jQuery  Bootstrap JS -->
    <script src="http://localhost/Tp5/admin/tp_bs_dms/public/static/bootstrap/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="http://localhost/Tp5/admin/tp_bs_dms/public/static/bootstrap/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!--bootstrap-table JS-->
    <script src="http://localhost/Tp5/admin/tp_bs_dms/public/static/bootstrap-table/bootstrap-table.min.js" crossorigin="anonymous"></script>
    <script src="http://localhost/Tp5/admin/tp_bs_dms/public/static/bootstrap-table/bootstrap-table-zh-CN.min.js" crossorigin="anonymous"></script>
    <!-- admin-main JS -->
    <script src="http://localhost/Tp5/admin/tp_bs_dms/public/static/admin/js/main.js" crossorigin="anonymous"></script>
    <script>
        /*初始化，方便后续使用*/
        var domain = "<?php echo $domain; ?>"; //根域名, 后续用到的所有访问地址都用到这个拼接，domain + '模块名/控制器名/方法名'，如 url = domain + 'admin/sys_user/edit'

        $(document).ajaxError(function(event,xhr,options){ //ajax请求失败时的提示，这样就不用每个ajax请求去判断是否成功
            Alert("Error！" + xhr.status + ' ' + xhr.statusText + "<br>" + 'request_url：' + options.url);
        });
    </script>
    <title><?php echo isset($title)?$title: 'dms_后台管理'; ?></title>
  </head>
  <body>
    
    <!--header-->
  	<nav class="navbar navbar-expand-sm navbar-light fixed-top bg-theme1-top pl-0" bg-theme-int='1' id="header">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse bg-theme1-top pl-0" id="navbarToggler">
    	  <a class="text-center" href="" id="header-brand"><!-- <img src="http://localhost/Tp5/admin/tp_bs_dms/public/static/admin/images/logo.png" width="200" height="48" alt="Brand"> -->Brand</a>

        <!--顶部模块菜单加载-->
        <ul class="navbar-nav mr-auto" id="module">
        <?php if(!(empty($topAuth) || (($topAuth instanceof \think\Collection || $topAuth instanceof \think\Paginator ) && $topAuth->isEmpty()))): foreach($topAuth as $module): ?>
          <li class="nav-item <?php if($module['title']=='首页'): ?>active nav-chose<?php endif; ?>" onclick="module_click(this)">
            <a class="nav-link text-white pt-0 pb-0 btn-lg" href="javascript:void(0)" ><?php echo $module['title']; ?></a>
          </li>
          <?php endforeach; endif; ?>
        </ul>
        
        <!--用户详情、换肤、退出功能项-->
        <ul class="navbar-nav" id="module-action">
          <li class="nav-item pr-2">
            <a href="javacript:void(0)" class="nav-link pt-0 pb-0 btn text-white" id="user-info">
              <img class="rounded-circle" src="http://localhost/Tp5/admin/tp_bs_dms/public/static/admin/images/user3.jpg" width="38" height="38" alt="用户头像">
            </a>
          </li>
          <li class="nav-item pr-2"><a class="nav-link btn text-white" id="change-theme" href="#">换肤</a></li>
          <li class="nav-item pr-2"><a class="nav-link btn text-white" href="<?php echo url('admin/login/logout'); ?>" id="logout">退出</a></li>
        </ul>
      </div>
  	</nav>
    <!--end-header-->

  <div>
    <!--user profile card-->
    <div class="card bg-white text-center shadow hide" id="user-info-card">
      <div class="card-header">
        <img class="rounded-circle" width="150" height="150" src="http://localhost/Tp5/admin/tp_bs_dms/public/static/admin/images/user3.jpg" alt="Card image cap">
        <h3>username</h3>
        <p>注册时间：2018-08-08 08:00:00</p>
      </div>
      <div class="card-footer">
        <button class="btn btn-sm btn-primary pull-left">个人中心</button>
        <button class="btn btn-sm btn-success pull-right">编辑资料</button>
      </div>
    </div>

    <!--change theme card-->
    <div class="card bg-wihte text-center shadow hide" id="change-theme-card">
      <ul class="list-group list-group-flush">
        <li class="list-group-item p-0"><button class="btn bg-theme1-top text-white w-100">风格一</button></li>
        <li class="list-group-item p-0"><button class="btn bg-theme2-top text-white w-100">风格二</button></li>
        <li class="list-group-item p-0"><button class="btn bg-theme3-top text-white w-100">风格三</button></li>
        <li class="list-group-item p-0"><button class="btn bg-theme4-top text-white w-100">风格四</button></li>
        <li class="list-group-item p-0"><button class="btn bg-theme5-top text-white w-100">风格五</button></li>
        <li class="list-group-item p-0"><button class="btn bg-theme6-top text-white w-100">风格六</button></li>
      </ul>
    </div>
  </div>

    <!--left_menu-->
<div class="bg-theme1-menu" id="left-menu">
  <div class="w-100">
    <ul class="nav flex-column text-left p-0 m-0 w-100 pull-left" id="menu-list">
      <li class="nav-item text-center" id="menu-title">
        <span class="text-white">菜 单 栏</span>
        <a class="nav-link btn btn-sm ml-3 text-warning" href="<?php echo url('admin/login/logout'); ?>">退出</a>
      </li>
      <!--菜单加载-->
      <?php echo $menuAuth; if(empty($menuAuth) || (($menuAuth instanceof \think\Collection || $menuAuth instanceof \think\Paginator ) && $menuAuth->isEmpty())): ?>
        <li class="nav-item <?php if($menu['title']=='首页'): ?>active menu-chose<?php endif; ?>">
          <a class="nav-link" href="#"><i class="fa fa-tachometer">&nbsp;&nbsp;</i><span>首页</span></a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</div>
<!--end-left_menu-->

     <!--tabs bar absolute-->
 <nav class="bg-light p-0" id="tabs-bar">
    <!--menu animate-->
    <button class="btn btn-info menu-btn pull-left border" data-toggle="tooltip" data-placement="bottom" title="收起菜单" onclick="menu_animate(this)"><i class="fa fa-align-justify"></i></button>

    <a class="btn pull-left" id="tab-backward" title="左侧隐藏标签" href="javascript:void(0)" onclick="animate_tabs('backward')">
      <i class="fa fa-backward"></i>
    </a>
    <div class="nav-tab-box h-100">
      <ul class="nav nav-tabs pull-left h-100" id="main-tab">
        <li class="nav-item active" id="home-tab">
        <a class="nav-link p-6 active pull-left" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
          首页
        </a>
        </li>
      </ul>
    </div>
    
    <a class="btn dropdown-toggle pull-right" href="#" id="navtabsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
    <a class="btn pull-right" id="tab-forward" title="右侧隐藏标签" href="javascript:void(0)" onclick="animate_tabs('forward')">
      <i class="fa fa-forward"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="navtabsDropdown">
      <button class="dropdown-item btn text-primary" onclick="close_tabs()">关闭所有</button>
      <div class="dropdown-divider"></div>
      <button class="dropdown-item btn text-primary" onclick="stay_current_tab()">只留当前</button>
    </div>
  </nav>  


<div class="bg-light" id="main">
  <!--main content-->
  <div class="container-fluid p-2 pull-left">
    <div class="tab-content" id="main-content">
      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
      home
      </div>
    </div>
  </div>
</div>

    <div class="fixed-bottom text-center bg-theme1-top m-0 text-white" id="footer">
Copyright © 2017-2018 tp_bs_dms. 保留所有权利。
<button class="btn btn-primary pull-right">&times;</button>
</div>

<!--alert msg 信息提示框-->
<div class="alert alert-dismissible fade shadow" id="alert" role="alert" style="width:800px;margin:0 auto;margin-top: 12%;z-index: 999999;">
  <span>message</span>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<!-- Modal 模态框-->
<div class="modal fade" id="action-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-info" id="exampleModalLabel">Title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">关闭</span>
        </button>
      </div>
      <div class="modal-body">
        content
      </div>
    </div>
  </div>
</div>


</body>
</html>
  