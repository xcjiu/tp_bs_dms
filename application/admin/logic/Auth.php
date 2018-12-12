<?php
namespace app\admin\logic;
use think\Request;
use think\Session;
use app\admin\model\AuthAssignment;
use app\admin\model\AuthRule;

/**
* 权限逻辑类
*/
class Auth
{
  /**
   * 权限数据表查询
   * @param  array $params  查询条件
   * @return array 返回查询条数和数据组
   */
  public static function getAuthRows($params)
  {
    $condition = [];
    if(!empty($params)){
      $offset = $params['offset'] ?: 0;
      $limit = $params['limit'] ?: 15;
      unset($params['offset']);
      unset($params['limit']);
      unset($params['_']); //这个参数是数据表插件自带的，这里不需要
      foreach ($params as $key => $value) {
        if($value != ''){
          $condition[$key] = $value;
        }
      }
    }
    if($condition){
      $rows = AuthRule::where($condition)->limit($offset, $limit)->select();
      $total = AuthRule::where($condition)->count();
    }else{
      $rows = AuthRule::limit($offset, $limit)->select();
      $total = AuthRule::count();
    }
    if($total){
      foreach ($rows as &$value) {
        $icon = $value['icon'] ?: 'fa-location-arrow';
        $value['icon'] = '<i class="fa '. $icon .'"></i>';
      }
    }
    return ['total'=>$total,'rows'=>$rows]; 
  }

  /**
   * 权限新增
   * @param array $data 接收到的字段数据
   * @return  string | bool 
   */
  public static function addAuth($data)
  {
    if(!$data['title'] || !$data['link']){
      return '标题或链接为必填字段！';
    }
    if( (new AuthRule)->allowField(true)->save($data) ){
      return true;
    }
    return '新增失败！';
  }

  /**
   * 权限编辑
   * @param  int $id   主键id
   * @param  array $data 要编辑的字段值
   * @return string | bool
   */
  public static function editAuth($id, $data)
  {
    if(!$data['title'] || !$data['link']){
      return '标题或链接为必填字段！';
    }
    foreach ($data as $key => $value) {
      if($value === ''){
        unset($data[$key]);
      }
    }
    if( (new AuthRule)->allowField(true)->save($data, ['id'=>$id]) ){
      return true;
    }
    return '编辑失败！';
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
        $auth = AuthRule::where('status', 1)->where('id', 'in', $role->auth_ids)->field('id,type,module,pid,link,title,icon,sort')->order('sort asc')->select();
      }
    }else{
      $auth = AuthRule::where('link','admin/index/index')->field('id,type,module,pid,link,title,icon,sort')->select(); //如果没有分配权限，则只显示首页
    }
    return $auth;
  }

  /**
   * 对用户权限进行分级，顶部导航模块，侧边菜单，具体操作项
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