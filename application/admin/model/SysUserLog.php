<?php
namespace app\admin\model;

/**
* 
*/
class SysUserLog extends Base
{
  // 开启自动写入时间戳字段
  protected $autoWriteTimestamp = true;
  // 关闭自动写入update_time字段
  protected $updateTime = false;
  
  protected $resultSetType='collection';
}