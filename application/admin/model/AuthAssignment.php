<?php
namespace app\admin\model;

/**
* 
*/
class AuthAssignment extends Base
{
  // 开启自动写入时间戳字段
  protected $autoWriteTimestamp = true;

  protected $resultSetType='collection';

  public function role()
  {
    return $this->belongsTo('AuthRole', 'role_id', 'id');
  }
}