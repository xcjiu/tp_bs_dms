

/*----------------------header导航栏相关处理-------------------------*/
//顶部导航菜单选择
$(function(){
  $('#module > li.nav-item').hover(function(){
    $(this).addClass('active');
  },function(){
    if(!$(this).hasClass('nav-chose')){$(this).removeClass('active');}
  });
})


//顶部导航菜单点击
function module_click(e){
  $(e).addClass('active nav-chose');
  $(e).siblings().removeClass('active nav-chose');
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

//用户详情，换肤，退出等功能操作项
$('#module-action > li.nav-item').hover(function(){
    $(this).addClass('shadow');
},function(){
    $(this).removeClass('shadow');
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

})
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
/*---------------------end-header导航栏相关处理-------------------------*/


/*----------------------侧边菜单栏相关处理------------------------------*/
//打开对应的菜单页面
function open_page(url, el){
  var attrId = $(el).attr('content-id');
  var id = $("#" + attrId);
  if($(el).children('span').children('i.pull-right').length>0){ //父级菜单下拉项不请求信息
    return false;
  }
  if(id && id.length>0){ //已存在此内容标签
    var currentTab = $('a[href="#'+attrId+'"]');
    var currentLeftWidth = currentTab.offset().left - $('#tab-backward').offset().left;
    var currentRightWidth = $('#tab-forward').offset().left - currentTab.offset().left;
    if(currentLeftWidth < 0){ //当再次点击的菜单标签已被左隐藏时，让其显示为可见的位置
      $('#main-tab').animate({'left': '+=' + (Math.abs(currentLeftWidth) + 150) + 'px'}, 'fast');
    }
    if(currentRightWidth < currentTab.innerWidth()){ //当再次点击的菜单标签已右隐藏时，让其显示为可见的位置
      $('#main-tab').animate({'left': '-=' + (Math.abs(currentRightWidth) + 188) + 'px'}, 'fast');
    }
    id.addClass('active show');
    id.siblings().removeClass('active show');
    currentTab.addClass('active show');
    currentTab.parent('li').siblings().children('a').removeClass('active show');
  }else{
    $.ajax({
      url:domain + url,
      data: {}, 
      success: function(data, status, xhr){ //ajax异步请求内容页面
        if(status==='success'){
          var title = $(el).html();
          var html = '<div class="tab-pane fade active show" id="'+attrId+'" role="tabpanel" aria-labelledby="contact-tab">'+data+'</div>';
          var liTitle = '<li class="nav-item"><a class="nav-link p-6 pull-left active show" data-toggle="tab" href="#'+attrId+'" role="tab" aria-controls="'+attrId+'" aria-selected="false">' + title + '<i class="fa fa-times" onclick="close_current_tab(this)"></i></a></li>';
          $('#main-tab').find('a').removeClass('active show');
          $('#main-content').children('div').removeClass('active show');
          $('#main-content').append(html);
          $('#main-tab').append(liTitle);
          main_tab_animate('open_page'); //主页面标签导航盒子动态宽度变化
        }
      },
      error: function(xhr, status, error){
        //alert(status + " " + xhr.status + ' ' + error); //弹出错误信息
        var errorMsg = '<h1 style="color:red;padding:25px;">' + status + ": " + xhr.status + ' ' + error + '</h1>';
        var title = $(el).html();
        var html = '<div class="tab-pane fade active show" id="'+attrId+'" role="tabpanel" aria-labelledby="contact-tab">'+errorMsg+'</div>';
        var liTitle = '<li class="nav-item"><a class="nav-link p-6 pull-left active show" data-toggle="tab" href="#'+attrId+'" role="tab" aria-controls="'+attrId+'" aria-selected="false">' + title + '<i class="fa fa-times" onclick="close_current_tab(this)"></i></a></li>';
        $('#main-tab').find('a').removeClass('active show');
        $('#main-content').children('div').removeClass('active show');
        $('#main-content').append(html);
        $('#main-tab').append(liTitle);
        main_tab_animate('open_page'); //主页面标签导航盒子动态宽度变化
      }
    });
  }
}

$(function(){

//菜单焦点样式
$('#left-menu').find('li.nav-item').hover(function(){
    $(this).addClass('active');
    $(this).siblings().not('.menu-chose').removeClass('active');
}, function(){
    if(!$(this).hasClass('menu-chose')){
      $(this).removeClass('active');
    }
});

//点击菜单项
$('#left-menu').find('li.nav-item').click(function(event){ 
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
  }
  $(this).siblings().children('ul').slideUp('fast');
  event.stopPropagation(); //阻止冒泡事件
});

})
/*---------------------end-侧边菜单栏相关处理---------------------------*/


/*----------------------标签操作相关-------------------------*/
function close_current_tab(e){
  var currentTab = $(e).parent('a');
  var liEl = currentTab.parent('li');
  var tabContent = $(currentTab.attr('href'));
  var prevTab = liEl.prev().children('a');
  var prevTabContent = tabContent.prev();
  if(currentTab.hasClass('active')){ //如果是选中标签关闭，则需要前一个标签替换为选中状态
    prevTab.addClass('active show');
    prevTabContent.addClass('active show');
  }
  liEl.remove();
  tabContent.remove();
  animate_tabs('forward');
}

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
  alertBox.children('span').html(msg);
  alertBox.addClass('show ' + bgcolor);
  setTimeout("$('.alert').removeClass('show')", timeout);
}

//模态内容加载器
function actionModal(url)
{
  url = domain + url;
  $.get(url, function(data, status, xhr){
    $('#action-modal').find('.modal-body').html(data);
  });
}



