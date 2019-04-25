<?php
namespace app\admin\logic;
use think\Config;
use app\admin\model\AuthAssignment;
use app\admin\model\AuthRule;
use app\admin\model\AuthRole;


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
      $title = $params['title'];
      $link = $params['link'];
      $type = $params['type'];
      if($title){
        $condition['title'] = $title;
      }
      if($link){
        $condition['link'] = $link;
      }
      if($type){
        $condition['type'] = $type;
      }
    }
    if($condition){
      $rows = AuthRule::where($condition)->order('type,link asc')->limit($offset, $limit)->select();
      $total = AuthRule::where($condition)->count();
    }else{
      $rows = AuthRule::order('type,link asc')->limit($offset, $limit)->select();
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
    if(AuthRule::where('link', $data['link'])->where('title', $data['title'])->find()){
      return '该权限已存在！';
    }
    if( (new AuthRule)->allowField(true)->save($data) ){
      return true;
    }
    return '新增失败！';
  }

  /**
   * 权限编辑
   * @param  int $auth   要操作的数据对象
   * @param  array $data 要编辑的字段值
   * @return string | bool
   */
  public static function editAuth($id, $data)
  {
    if(!$data['title'] || !$data['link']){
      return '标题或链接为必填字段！';
    }
    $auth = AuthRule::get($id);
    $editAuth = AuthRule::where('title', $data['title'])->where('link', $data['link'])->find();
    if($editAuth && $editAuth->id != $auth->id){
      return '该权限已存在！';
    }
    foreach ($data as $key => $value) {
      if($value != ''){
        $auth->$key = $value;
        unset($data[$key]);
      }
    }
    if( $auth->save() ){
      return true;
    }
    return '无更新！';
  }

  /**
   * 角色数据表查询
   * @param  array $params  查询条件
   * @return array 返回查询条数和数据组
   */
  public static function getRoles($params)
  {
    $condition = [];
    if(!empty($params)){
      $offset = $params['offset'] ?: 0;
      $limit = $params['limit'] ?: 15;
      unset($params['offset']);
      unset($params['limit']);
      $name = $params['name'];
      if($name){
        $condition['name'] = $name;
      }
    }
    if($condition){
      $rows = AuthRole::where($condition)->limit($offset, $limit)->select();
      $total = AuthRole::where($condition)->count();
    }else{
      $rows = AuthRole::limit($offset, $limit)->select();
      $total = AuthRole::count();
    }
    return ['total'=>$total,'rows'=>$rows]; 
  }

  /**
   * 角色新增
   * @param array $params 接收的参数
   * @return  成功返回true 否则返回错误信息
   */
  public static function addRole(array $params)
  {
    $name = $params['name'];
    $status = $params['status'];
    if(!$name){
      return '请填写角色名称！';
    }
    if(AuthRole::where('name', $name)->find()){
      return '该角色已经存在！';
    }
    if( AuthRole::create(['name'=>$name, 'status'=>$status, 'description'=>$params['description']]) ){
      return true;
    }
    return '新增失败！';
  }

  /**
   * 角色编辑
   * @param  int $id   主键ID
   * @param  array $data 要编辑的字段值
   * @return string | bool
   */
  public static function editRole($id, $data)
  {
    if(!$data['name']){
      return '请填写角色名称！';
    }
    $role = AuthRole::get($id);

    if( ($role->name != $data['name']) && AuthRole::get(['name'=>$data['name']]) ){
      return '该角色已存在！';
    }
    $role->name = $data['name'];
    $role->status = (int)$data['status'];
    $role->description = $data['description'];
    if( $role->save() ){
      return true;
    }
    return '无更新！';
  }

  /**
   * 权限分配列表
   * @param  array  $params    [description]
   * @param  array  $rolesName [description]
   * @return [type]            [description]
   */
  public static function authAssignment(array $params)
  {
    $offset = $params['offset'] ?: 0;
    $limit = $params['limit'] ?: 15;
    $auths = AuthRule::order('type,link asc')->limit($offset, $limit)->select()->toArray();
    $total = 0;
    $rows = [];
    $authIds = AuthRole::where('status', 1)->order('id asc')->column('auth_ids', 'id');
    if($auths && $authIds){
      $total = AuthRule::count();
      $rolesRow = [];
      $type = [0=>'顶部导航', 1=>'侧边菜单', 2=>'具体操作'];
      foreach ($auths as $key => $value) {
        $rows[$key]['title'] = $value['title'] . "（" .$type[$value['type']]. "）";
        $rows[$key] = array_merge($rows[$key], $rolesRow);
        $auth_id = $value['id'];
        foreach ($authIds as $k => $v) {
          if(in_array($value['id'], explode(',', $v))){
            $rows[$key]['role'.$k] = '<a href="javascript:void(0);" onclick="actionModal(' ."'admin/auth/assignmentAuth?id=$k&auth_id=$auth_id&check=1','权限分配'" . ')"><i class="fa fa-check"></i></a>';
          }else{
            $rows[$key]['role'.$k] = '<a href="javascript:void(0);" onclick="actionModal(' ."'admin/auth/assignmentAuth?id=$k&auth_id=$auth_id&check=0','权限分配'" . ')"><i class="fa fa-ellipsis-h"></i></a>';
          }
        }
      }
    }
    return ['total'=>$total, 'rows'=>$rows];
  }

  /**
   * 所有角色名称
   * @return array 返回角色id对应的名称数组
   */
  public static function rolesName()
  {
    return AuthRole::where('status', 1)->order('id asc')->column('name', 'id');
  }

  /**
   * 获取用户的角色
   * @param  int $uid 用户id
   * @return object | bool  返回对象，如果要获得数组可以在取得结果后 ->toArray(), 如果用户没有分配角色则返回false;
   */
  public static function userRole($uid)
  {
    $group = AuthAssignment::get(['user_id'=>$uid]);
    return $group ? AuthRole::get($group->role_id) : false;
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