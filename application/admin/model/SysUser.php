<?php
namespace app\admin\model;

/**
* 
*/
class SysUser extends Base
{
	// 开启自动写入时间戳字段
	protected $autoWriteTimestamp = 'datetime';

  /**
   * 一对一关联角色
   * @return [type] [description]
   */
  public function assignment()
  {
    return $this->hasOne('AuthAssignment', 'user_id')->setEagerlyType(0);
  }
}