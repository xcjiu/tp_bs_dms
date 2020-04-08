<?php
namespace app\admin\model;

/**
* 
*/
class AuthRole extends Base
{
  // 开启自动写入时间戳字段
  protected $autoWriteTimestamp = true;
  protected $resultSetType='collection';

  public static function role()
  {
    return $this->hasMany('AuthAssignment', 'role_id', 'id');
  }
}
