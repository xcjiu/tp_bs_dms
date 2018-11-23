<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件, 一些公共函数

/**
 * 错误信息提示框
 * @param  string $msg 错误提示信息
 * @return html
 */
function alertError($msg)
{
	$showEvent = "<script> var showerror = document.getElementById('show-error'); function showEvent(){setTimeout(function(){showerror.style.display='none';}, 3000);} document.getElementById('close-error-btn').onclick=function(){showerror.style.display='none';}; showerror.onmouseover=showEvent(); showerror.onmouseout=showEvent();</script>";
	$html = '<h2 id="show-error" style="background-color:#fff2e8;z-index:999999;width:45%;padding:10px 0px 20px 0px;border:1px solid #ccc;box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19)!important;position:absolute;top:100px;left:50%;margin-left:-23%;text-align:center;color:red;"><span id="close-error-btn" style="display:block;width: 100%;cursor:pointer;margin-top:-10px;text-align:right;" >X&nbsp;</span>'.(string)$msg.'</h2>';
	return $html;
}