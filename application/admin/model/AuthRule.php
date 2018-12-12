<?php
namespace app\admin\model;
use think\Config;

/**
* 
*/
class AuthRule extends Base
{
  // 开启自动写入时间戳字段
  protected $autoWriteTimestamp = true;
  
  protected $resultSetType='collection';

  /**
   * 导航模块
   * @return array
   */
  public static function modules()
  {
    $moduleY = Config::get('auth_module');
    $moduleData = [];
    if($moduleY){ //需要模块导航
      //顶部导航模块
      $moduleData = self::where('type', 0)->column('title', 'id');
      $moduleData[0] = '---';
    }
    return $moduleData;
  }

  /**
   * 侧边菜单项
   * @return array
   */
  public static function menus()
  {
    return self::where('type', 1)->column('title', 'id');
  }
}