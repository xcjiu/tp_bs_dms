

/*----------------------header导航栏相关处理-------------------------*/
//顶部导航菜单点击
function module_click(e, module){
  $(e).addClass('active nav-chose');
  $(e).siblings().removeClass('active nav-chose');
  $.get(domain + 'admin/index/getMenu?module=' + module, function(data, status){
    $('#menu-list').html(data);
  });
}

//侧边菜单栏关闭打开动态操作按钮
function menu_animate(e){
  var menu = $('#left-menu');
  menu.animate({
    width:'toggle'
  }, 'slow');
  if(menu.width() > 60){
    $('#main').animate({'left': 0, 'width': '+=200px'},'slow');
    $('#tabs-bar').animate({'left': 0, 'width': '+=200px'},'slow');
    $(e).attr('title','打开菜单');
  }else{
    $('#tabs-bar').animate({'left':  '200px', 'width': '-=200px'},'slow');
    $('#main').animate({'left':  '200px', 'width': '-=200px'},'slow');
    $(e).attr('title','收起菜单');
  }
}

$(function(){
  open_main_content('admin/index/home');//加载首页，登入成功后
  //顶部导航菜单选择
  $('#module > li.nav-item').hover(function(){
    $(this).addClass('active');
  },function(){
    if(!$(this).hasClass('nav-chose')){$(this).removeClass('active');}
  });

  //用户详情，换肤，退出等功能操作项
  $('#module-action > li.nav-item').hover(function(){
      $(this).addClass('shadow bg-info');
  },function(){
      $(this).removeClass('shadow bg-info');
  });
  $('#module-action > li.nav-item').click(function(){
      $(this).removeClass('shadow');
  });

  //用户详情卡
  $('#user-info').hover(function(){
    var userCard = $('#user-info-card');
    if(userCard.hasClass('hide')){
      var position = $(this).offset();
      var left = (position.left + $(this).innerWidth() - userCard.innerWidth()) + 'px';
      var top = (position.top + $(this).height()) + 'px';
      userCard.siblings().addClass('hide').removeClass('card-chose');
      userCard.css({'left': left, 'top': top}); //根据坐标自动对齐位置
      userCard.removeClass('hide');
    }
  }, function(){
    setTimeout("\
      if(!$('#user-info-card').hasClass('card-chose')){\
        $('#user-info-card').addClass('hide');\
      }\
    ", 500);
  });
  $('#user-info-card').hover(function(){
    $(this).addClass('card-chose');
  }, function(){
    $(this).addClass('hide').removeClass('card-chose');
  });

  //换肤选项卡
  $('#change-theme').hover(function(){
    var changeCard = $('#change-theme-card');
    if(changeCard.hasClass('hide')){
      var position = $(this).offset();
      var left = (position.left + $(this).innerWidth() - changeCard.innerWidth()) + 'px';
      var top = (position.top + $(this).outerHeight(true)) + 'px';
      changeCard.siblings().addClass('hide').removeClass('card-chose');
      changeCard.css({'left': left, 'top': top}); //根据坐标自动对齐位置
      changeCard.removeClass('hide')
    }
  }, function(){
    setTimeout("\
      if(!$('#change-theme-card').hasClass('card-chose')){\
        $('#change-theme-card').addClass('hide');\
      }\
    ", 500);
  });
  $('#change-theme-card').hover(function(){
    $(this).addClass('card-chose');
  }, function(){
    $(this).addClass('hide').removeClass('card-chose');
  });

  //换肤卡焦点
  $('#change-theme-card').find('button').hover(function(){
    $(this).toggleClass('shadow');
  });

  //换肤
  function themeColor(newThemeInt=1){
    var oldThemeInt = $('#header').attr('bg-theme-int');
    var old_bg_top_bottom = 'bg-theme' + oldThemeInt +'-top';
    var old_bg_left_menu = 'bg-theme' + oldThemeInt +'-menu';
    var new_bg_top_bottom = 'bg-theme' + newThemeInt +'-top';
    var new_bg_left_menu = 'bg-theme' + newThemeInt +'-menu';
    $('#header').attr('bg-theme-int', newThemeInt);
    $('#header').removeClass(old_bg_top_bottom).addClass(new_bg_top_bottom);
    $('#navbarToggler').removeClass(old_bg_top_bottom).addClass(new_bg_top_bottom);
    $('#footer').removeClass(old_bg_top_bottom).addClass(new_bg_top_bottom);
    $('#left-menu').removeClass(old_bg_left_menu).addClass(new_bg_left_menu);
  }
  $('#change-theme-card').find('button').click(function(){
    var theme = $(this).text();
    switch(theme){
      case '风格一':
        themeColor(1);
        break;
      case '风格二':
        themeColor(2);
        break;
      case '风格三':
        themeColor(3);
        break;
      case '风格四':
        themeColor(4);
        break;
      case '风格五':
        themeColor(5);
        break;
      case '风格六':
        themeColor(6);
        break;
    }
  });

  //更多操作项
  $('#more-action').hover(function(){
    var moreActionCard = $('#more-action-card');
    if(moreActionCard.hasClass('hide')){
      var position = $(this).offset();
      var left = (position.left + $(this).innerWidth() - moreActionCard.innerWidth()) + 'px';
      var top = (position.top + $(this).outerHeight(true)) + 'px';
      moreActionCard.siblings().addClass('hide').removeClass('card-chose');
      moreActionCard.css({'left': left, 'top': top}); //根据坐标自动对齐位置
      moreActionCard.removeClass('hide');
    }
  }, function(){
    setTimeout("\
      if(!$('#more-action-card').hasClass('card-chose')){\
        $('#more-action-card').addClass('hide');\
      }\
    ", 500);
  });
  $('#more-action-card').hover(function(){
    $(this).addClass('card-chose');
  }, function(){
    $(this).addClass('hide').removeClass('card-chose');
  });
  //更多操作焦点
  $('#more-action-card').find('button').hover(function(){
    $(this).toggleClass('shadow');
  });

  //清缓存
  $('#clear-chache').click(function(){
    window.location.reload(); //强制刷新
  });

  //全屏
  $('#full-screen').click(function(){
    var docElm = document.documentElement;
    //W3C
    if (docElm.requestFullscreen) {
      docElm.requestFullscreen();
    }
    //FireFox
    else if (docElm.mozRequestFullScreen) {
      docElm.mozRequestFullScreen();
    }
    //Chrome等
    else if (docElm.webkitRequestFullScreen) {
      docElm.webkitRequestFullScreen();
    }
    //IE11
    else if (docElm.msRequestFullscreen) {
      docElm.msRequestFullscreen();
    }
  });

  //锁屏操作
  $('#lock-screen').click(function(){
    //var lockHtml = '锁屏密码：<input type="text" class="form-control" autofocus="autofocus"><br><button class="btn btn-info btn-block lock-screen-btn">确 定</button>';
    var lockHtml = '<div class="input-group mb-3">\
      <input type="text" class="form-control" placeholder="锁屏密码" aria-label="" aria-describedby="basic-addon2" required>\
      <div class="input-group-append">\
        <button class="btn btn-info" type="button" onclick="lockscreen(this)">锁 屏</button>\
      </div>\
    </div>';
    $('#action-modal .modal-title').html('锁屏操作');
    $('#action-modal .modal-dialog').removeClass('modal-lg').addClass('modal-sm');
    $('#action-modal').find('.modal-body').html(lockHtml);
    $('#action-modal').modal('show');
  });
});

//锁屏事件
function lockscreen(e){
  var alertBg = 'alert-danger';
  var psd = $(e).parent().prev().val();
  if(psd == ''){
    Alert('请输入密码！', alertBg);
    return false;
  }
  $.post(domain + 'admin/index/lockscreen', {type:'lock', psd: psd}, function(data, status){
    if(data.code == 1){
      $('#action-modal').modal('hide');
      $('#lock-screen-page').removeClass('hide');
      alertBg = 'alert-success';
    }
    Alert(data.msg, alertBg);
  });
}
//解屏事件
function unlockscreen(e){
  var alertBg = 'alert-danger';
  var psd = $(e).prev().val();
  if(psd == ''){
    Alert('请输入密码！', alertBg);
    return false;
  }
  $.post(domain + 'admin/index/lockscreen', {type: 'unlock', psd: psd}, function(data, status){
    if(data.code == 1){
      $('#lock-screen-page').addClass('hide');
      alertBg = 'alert-success';
    }      
    Alert(data.msg, alertBg);
  });
}

/*---------------------end-header导航栏相关处理-------------------------*/


/*----------------------侧边菜单栏相关处理------------------------------*/
//打开对应的菜单页面
function open_page(url, el){
  topLogin(url);
  if($(el).children('span').children('i.pull-right').length>0){ //父级菜单下拉项不请求信息
    return false;
  }
  var title = $(el).html();
  var openTab = $('#main-tab').find('a[open-url="'+url+'"]');
  if(openTab.length > 0){ //标签已存在  
    $('#main-tab').find('a').removeClass('active show');
    openTab.addClass('active show');
  }else{
    var liTitle = '<li class="nav-item"><a class="nav-link p-6 pull-left content-tab-click active show"\
     data-toggle="tab" href="javascript:void(0);" role="tab" aria-selected="false" open-url="' + url + '">\
     ' + title + '<i class="fa fa-times close-current-tab"></i></a></li>';
    $('#main-tab').find('a').removeClass('active show');
    $('#main-tab').append(liTitle);
    main_tab_animate('open_page'); //主页面标签导航盒子动态宽度变化
  }
  open_main_content(url); //加载内容区页面
}

//加载内容区页面
function open_main_content(url)
{
  $.ajax({
    url:domain + url,
    data: {}, 
    success: function(data, status, xhr){ //ajax异步请求内容页面
      if(status==='success'){
        if(data.code==0){
          Alert(data.msg, 'alert-danger');
          topLogin(data.url);
        }
        var html = '<div class="tab-pane fade active show" role="tabpanel" aria-labelledby="contact-tab">'+data+'</div>';
        $('#main-content').html(html);
      }
    },
    error: function(xhr, status, error){
      var errorMsg = '<h1 style="color:red;padding:25px;">' + status + ": " + xhr.status + ' ' + error + '</h1>';
      var html = '<div class="tab-pane fade active show" role="tabpanel" aria-labelledby="contact-tab">'+errorMsg+'</div>';
      $('#main-content').html(html);
    }
  });
}

$(function(){

//菜单焦点样式
//$('#left-menu').find('li.nav-item').hover(function(){
$('#left-menu').on('hover', 'li.nav-item', function(){
    $(this).addClass('active');
    $(this).siblings().not('.menu-chose').removeClass('active');
}, function(){
    if(!$(this).hasClass('menu-chose')){
      $(this).removeClass('active');
    }
});

//点击菜单项
//$('#left-menu').find('li.nav-item').click(function(event){ 
$('#left-menu').on('click','li.nav-item', function(event){ 
  var ul_item = $(this).children('ul');
  $(this).addClass('active menu-chose');
  $(this).siblings().removeClass('active menu-chose');
  if(ul_item){ 
    var dropdownIcon = $(this).children('a').children('span');
    dropdownIcon.toggleClass('fa-down');
    if(dropdownIcon.hasClass('fa-down')){ //菜单下拉图标切换
      icon = 'fa-chevron-down';
    }else{
      icon = 'fa-chevron-right';
    }
    dropdownIcon.html('<i class="fa '+icon+' pull-right pt-1"></i>');
    ul_item.slideToggle('fast');
    $(this).siblings().children('a').children('span').removeClass('fa-down');
    $(this).siblings().children('a').children('span').html('<i class="fa fa-chevron-right pull-right pt-1"></i>');
  }
  $(this).siblings().children('ul').slideUp('fast');
  event.stopPropagation(); //阻止冒泡事件
});

//动态绑定标签的点击事件，注意：因为是动态标签，不要直接加onclick属性添加事件，那样无法处理好冒泡事件
$('#main-tab').on('click', '.content-tab-click', function(){
  var url = $(this).attr('open-url');
  open_main_content(url);
});

})
/*---------------------end-侧边菜单栏相关处理---------------------------*/


/*----------------------标签操作相关-------------------------*/
//动态绑定关闭标签的点击事件，注意：因为是动态标签，不要直接加onclick属性添加事件，那样无法处理好冒泡事件
$(function(){
  $('#main-tab').on('click', '.close-current-tab', function(event){
    var currentTab = $(this).parent('a');
    var liEl = currentTab.parent('li');
    var prevTab = liEl.prev().children('a');
    if(currentTab.hasClass('active')){ //如果是选中标签关闭，则需要前一个标签替换为选中状态并加载相应内容
      var url = prevTab.attr('open-url');
      prevTab.addClass('active show');
      open_main_content(url);
    }
    liEl.remove();
    animate_tabs('forward');
    event.stopPropagation();//阻止冒泡事件
  })
});

//主页面标签导航盒子动态宽度变化
function main_tab_animate(type=''){ 
  var firstTab = $('#main-tab').children('li:first').children('a');
  var firstBetweenWidth = firstTab.offset().left - $('#tab-backward').offset().left;
  var lastTab = $('#main-tab').children('li:last').children('a');
  var lastTabWidth = lastTab.outerWidth();
  var lastBetweenWidth = $('#tab-forward').offset().left - lastTab.offset().left - lastTabWidth;//根据元素坐标值来计算间距
  var animateWidth = 120;
  if(lastTabWidth > animateWidth){
    animateWidth = lastTabWidth;
  }
  if(lastBetweenWidth <= 0){ //当没有间距时隐藏一些标签
    $('#main-tab').animate({'left': '-=' + animateWidth + 'px'}, 'fast');
  }
  if(type=='resize'){ //浏览器窗口变化
    if((lastTab.offset().left + lastTabWidth - firstTab.offset().left) < $('#main-tab').parent('.nav-tab-box').innerWidth()){
      $('#main-tab').animate({'left': '0px'}, 'fast');
    }else if(lastBetweenWidth > lastTabWidth){ //当最后间距可以容纳更多标签时
      $('#main-tab').animate({'left': '+=' + lastTabWidth + 'px'}, 'fast');
    }
  }
}


//标签按钮点击
function animate_tabs(type)
{
  if(type == 'forward'){
    var firstTab = $('#main-tab').children('li:first').children('a');
    var firstBetweenWidth = firstTab.offset().left - $('#tab-backward').offset().left;
    if(firstBetweenWidth<0){
      if(firstBetweenWidth > -300){ //如果打开的宽度大于间距的宽度则左边距为0
        $('#main-tab').animate({'left': '0px'}, 'fast');
      }else{
        $('#main-tab').animate({'left': '+=300px'}, 'fast');
      }
    }
  }else if(type=='backward'){
    var lastTab = $('#main-tab').children('li:last').children('a');
    var lastBetweenWidth = $('#tab-forward').offset().left - lastTab.offset().left - lastTab.outerWidth();
    if(lastBetweenWidth<0){
      $('#main-tab').animate({'left': '-=300px'}, 'fast');
    }
  }
}

//关闭所有标签, 只保留首页面
function close_tabs(){
  $('#main-tab').children('li:not("#home-tab")').remove();
  $('#main-content').children('div:not("#home")').remove();
  $('#home-tab > a').addClass('active show');
  $('#home').addClass('active show');
  $('#main-tab').animate({'left': '0px'}, 'fast');
}

//保留当前标签，删除其它标签和内容，除首页
function stay_current_tab(){
  $('#main-tab').find('a.active').parent('li').siblings().not("#home-tab").remove();
  $('#main-content').children('div:not("#home, .active")').remove();
  $('#main-tab').animate({'left': '0px'}, 'fast');
}
/*---------------------end-标签操作相关-------------------------*/


/*----------------------footer---------------------------*/
$(function(){

//关闭底部栏
$('#footer > button').click(function(){
  $('#footer').remove();
  $('#left-menu').css('height','calc(100% - 50px)');
  $('#main').css('height','calc(100% - 90px)');
$('#footer > button').tooltip('close');
});

//窗口发生变化时标签盒子动态变化
$(window).resize(function(){ 
  main_tab_animate('resize');
});

})

//信息提示框，msg信息内容，timeout自动消失时间，默认3秒
function Alert(msg, bgcolor='alert-danger', timeout=3000)
{
  var alertBox = $('#alert');
  alertBox.removeClass('hide');
  alertBox.children('span').html(msg);
  alertBox.addClass('show ' + bgcolor);
  setTimeout("$('.alert').removeClass('" + bgcolor + "').addClass('hide')", timeout);
}

//模态内容加载器
function actionModal(url, title, widthClass='')
{
  topLogin(url);
  url = domain + url;
  var error = false;
  $.get(url, function(data, status, xhr){
    if(data.code === 0){
      Alert(data.msg, 'alert-danger');
      topLogin(data.url);
      return false;
    }else{
      $('#action-modal').modal('show');
      $('#action-modal .modal-title').html(title + '操作');
      $('#action-modal .modal-dialog').removeClass('modal-lg modal-sm');
      if(widthClass != ''){ //modal框大小样式 modal-lg modal-sm, 默认中等大小
        $('#action-modal .modal-dialog').addClass(widthClass);
      }
      $('#action-modal').find('.modal-body').html(data);
    } 
  });
}

//当用户session过期或被禁用时，需要直接跳转至登录页面重新登入
function topLogin(url)
{
  if(url.indexOf('admin/login/index') > -1){ //跳转至登录界面
    setTimeout("top.location.href = domain + 'admin/login/index?/top=true'", 2500);
    return false;
  }
}



