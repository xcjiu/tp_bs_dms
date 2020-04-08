<?php
// 应用公共文件, 一些公共函数
// 
function curl_get_https($url){ //参数已做拼接
  $curl = curl_init(); // 启动一个CURL会话
  curl_setopt($curl, CURLOPT_URL, $url);
  //curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//绕过ssl验证 https
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $tmpInfo = curl_exec($curl);     //返回api的json对象
  //关闭URL请求
  curl_close($curl);
  return json_decode($tmpInfo,true);   //返回数组 
  //返回json对象
  //return $tmpInfo;
}



