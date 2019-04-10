<?php
namespace app\visitor\model;
use think\Model;

/**
* 
*/
class VisitorFere extends Model
{
  
  // 开启自动写入时间戳字段
  protected $autoWriteTimestamp = true;
  
  protected $resultSetType='collection';
}