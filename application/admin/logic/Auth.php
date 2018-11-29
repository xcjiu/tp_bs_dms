<?php
namespace app\admin\logic;
use app\admin\model\AuthAssignment;
use app\admin\model\AuthRule;

/**
* 权限逻辑类
*/
class Auth
{
  
  function __construct()
  {
    # code...
  }

  /**
   * 获取用户的角色
   * @param  int $uid 用户id
   * @return object | bool  返回对象，如果要获得数组可以在取得结果后 ->toArray(), 如果用户没有分配角色则返回false;
   */
  public static function userRole($uid)
  {
    $group = AuthAssignment::get(['user_id'=>$uid]);
    return $group ? $group->role : false;
  }

  /**
   * 获取用户所有权限
   * @param  int $uid 用户id
   * @return array 
   */
  public static function userAuth($uid)
  {
    $role = self::userRole($uid);
    if($role){
      if($role->name === '超级管理员'){
        $auth = AuthRule::field('id,type,module,pid,link,title,icon,sort')->order('sort asc')->select();
      }else{
        $auth = AuthRule::where('id', 'in', $role->auth_ids)->field('id,type,module,pid,link,title,icon,sort')->order('sort asc')->select();
      }
    }else{
      $auth = AuthRule::where('link','admin/index/index')->field('id,type,module,pid,link,title,icon,sort')->order('sort asc')->select(); //如果没有分配权限，则只显示首页
    }
    return $auth;
  }

  /**
   * 对权限进行分级，顶部导航模块，侧边菜单，具体操作项
   * @param  int $uid 用户id
   * @return array 
   */
  public static function auths($uid)
  {
    $auth = self::userAuth($uid)->toArray();
    $auths = [];
    foreach ($auth as $key => $value) {
      $auths[$value['type']][] = $value;
    }
    $topAuth = empty($auths[0]) ? [] : $auths[0];
    $menuAuth = empty($auths[1]) ? [] : $auths[1];
    $actionAuth = empty($auths[2]) ? [] : $auths[2];
    return ['topAuth'=>$topAuth, 'menuAuth'=>$menuAuth, 'actionAuth'=>$actionAuth];
  }



}