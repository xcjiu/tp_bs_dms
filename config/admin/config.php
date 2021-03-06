<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
$urlPath = \think\Request::instance()->url(true);
$pot = strrpos($urlPath, '/admin/');
$host = $_SERVER['HTTP_HOST'];
$rootPath = substr($urlPath, 0, $pot); //确保域名访问和路径访问下资源加载都正常
return [
    // +----------------------------------------------------------------------
    // | 后台应用设置
    // +----------------------------------------------------------------------

    
    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写
        'auto_rule'    => 1,
        // 模板路径
        'view_path'    => '', //APP_PATH . 'admin/view/',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '<{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}>',
        // 标签库标签开始标记
        'taglib_begin' => '<{',
        // 标签库标签结束标记
        'taglib_end'   => '}>',
    ],
    
    'view_replace_str'       => [
        '__BOOTSTRAP__' => $rootPath . '/static/bootstrap',
        '__ADMIN__' =>  $rootPath . '/static/admin',
        '__FONTAWESOME__' => $rootPath . '/static/fontAwesome',
        '__BOOTSTRAP-TABLE__' => $rootPath . '/static/bootstrap-table',
        '__DATEPICKER__' => $rootPath . '/static/datepicker',
        '__ECHARTS__' => $rootPath . '/static/echarts',
        '__GOODSIMG__' => $rootPath . '/static/goodImg'
    ],
    
    // +----------------------------------------------------------------------
    // | 是否显示顶部模块菜单，对于功能较少的可以选择不需要模块菜单进行分类, true为显示， false为不需要
    // +----------------------------------------------------------------------
    'auth_module' => false,

    // +----------------------------------------------------------------------
    // | 是否允许多处登录, true为允许， false为不允许
    // +----------------------------------------------------------------------
    'login_more' => true,

];
