<?php
use app\common\builder\Builder;

/*-----------------------应用公共文件, 一些公共函数------------------------*/

//页面构建器函数
function builder($type='')
{
  return Builder::init($type);
}