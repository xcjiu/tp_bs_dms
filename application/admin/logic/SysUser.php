<?php
namespace app\admin\logic;
use app\admin\model\SysUser as UserModel;
use app\admin\model\AuthAssignment;
use app\admin\model\AuthRole;
use app\admin\model\SysUserLog;
use app\admin\model\SysOpLog;

/**
* 
*/
class SysUser
{
  
  /**
   * 用户数据表查询
   * @param  array $params  查询条件
   * @return array 返回查询条数和数据组
   */
  public static function getRows($params)
  {
    $condition = [];
    if(!empty($params)){
      $offset = $params['offset'] ?: 0;
      $limit = $params['limit'] ?: 15;
      unset($params['offset']);
      unset($params['limit']);
      $id = $params['id'];
      $username = $params['username'];
      if((int)$id){
        $condition['id'] = $id;
      }
      if($username){
        $condition['username'] = $username;
      }
    }
    if($condition){
      $rows = UserModel::where($condition)->field('password,token',true)->limit($offset, $limit)->select();
      $total = UserModel::where($condition)->count();
    }else{
      $rows = UserModel::field('password,token',true)->limit($offset, $limit)->select();
      $total = UserModel::count();
    }
    if($rows){
      $uids = array_column($rows, 'id');
      $role_ids = AuthAssignment::column('role_id', 'user_id');
      $roles = AuthRole::where('status', 1)->column('name', 'id');
      foreach ($rows as &$value) {
        $role_id = isset($role_ids[$value['id']]) ? $role_ids[$value['id']] : 0;
        $value['role'] = $role_id ? (isset($roles[$role_id]) ? $roles[$role_id] : '') : '';
      }
    }
    return ['total'=>$total,'rows'=>$rows]; 
  }

  /**
   * 添加用户
   * @param array $data 接收到的数据
   * @return  int | string 成功返回新增的用户ID,否则返回错误信息
   */
  public static function add($data)
  {
    $username = trim($data['username']);
    $password = trim($data['password']);
    if(!$username || !$password){
      return '用户名或密码不能为空！';
    }
    $email = $data['email'];
    $phone = $data['phone'];
    if($email && !preg_match("/^[\w\-]+\@[\w\-]+\.[a-z]{2,3}$/",$email)){
      return '邮箱格式不正确！';
    }
    if($phone && !preg_match("/^1[3456789]\d{9}$/", $phone)){
      return '请输入正确的手机号码！';
    }
    if(UserModel::get(['username'=>$username])){
      return '该账号已存在！';
    }
    $password = password_hash($password, PASSWORD_DEFAULT);
    $status = (int)$data['status'];
    $role_id = $data['role_id'];
    unset($data['role_id']);
    $data['portrait'] = 'images/portrait/user3.jpg';
    $data['token'] = '';
    $data['password'] = $password;
    $res = UserModel::create($data);
    if( $res && AuthAssignment::create(['user_id'=>$res->id, 'role_id'=>$role_id]) ){
      return $res->id;
    }
    return '新增失败！';
  }

  /**
   * 编辑用户
   * @param   obj   $user 用户数据对象
   * @param  array  $data 接收的字段数据
   * @return bool | string  成功返回true
   */
  public static function edit($user, $data)
  {
    $email = $data['email'];
    $phone = $data['phone'];
    if($email && !preg_match("/^[\w\-]+\@[\w\-]+\.[a-z]{2,3}$/",$email)){
      return '邮箱格式不正确！';
    }
    if($phone && !preg_match("/^1[3456789]\d{9}$/", $phone)){
      return '请输入正确的手机号码！';
    }
    if(!empty($data['password'])){
      $data['password'] = password_hash(trim($data['password']), PASSWORD_DEFAULT);
    }
    $data['status'] = (int)($data['status']);
    $role_id = $data['role_id'];
    unset($data['role_id']);
    foreach ($data as $key => $value) {
      if($value != ''){
        $user->$key = $value;
      }
    }

    if($user->save() || $user->assignment->save(['role_id' => $role_id])){
      return true;
    }
    return '编辑失败！';
  }

  //后台用户登录日志
  public static function getLogRows($params)
  {
    $condition = [];
    if(!empty($params)){
      $offset = $params['offset'] ?: 0;
      $limit = $params['limit'] ?: 15;
      $id = $params['user_id'];
      $username = $params['username'];
      $start_time = strtotime($params['start_time']);
      $end_time = strtotime($params['end_time']) + 86399;
      if($id){
        $condition['user_id'] = $id;
      }
      if($username){
        $condition['username'] = $username;
      }
      if($start_time && $end_time){
        $condition['create_time'] = ['between', [$start_time, $end_time]];
      }
    }
    if($condition){
      $rows = SysUserLog::with('user')->where($condition)->limit($offset, $limit)->select();
      $total = SysUserLog::with('user')->where($condition)->count();
    }else{
      $rows = SysUserLog::with('user')->limit($offset, $limit)->select();
      $total = SysUserLog::count();
    }
    if($rows){
      foreach ($rows as &$value) {
        $value['username'] = $value['user']['username'];
      }
    }
    return ['total'=>$total, 'rows'=>$rows];
  }


  //后台用户操作日志
  public static function getOpLogRows($params)
  {
    $condition = [];
    if(!empty($params)){
      $offset = $params['offset'] ?: 0;
      $limit = $params['limit'] ?: 15;
      $id = $params['user_id'];
      //$username = $params['username'];
      $start_time = strtotime($params['start_time']);
      $end_time = strtotime($params['end_time']) + 86399;
      if($id){
        $condition['user_id'] = $id;
      }
      /*if($username){
        $condition['username'] = $username;
      }*/
      if($start_time && $end_time){
        $condition['create_time'] = ['between', [$start_time, $end_time]];
      }
    }
    if($condition){
      $rows = SysOpLog::with('user')->where($condition)->limit($offset, $limit)->select();
      $total = SysOpLog::with('user')->where($condition)->count();
    }else{
      $rows = SysOpLog::with('user')->limit($offset, $limit)->select();
      $total = SysOpLog::count();
    }
    if($rows){
      foreach ($rows as &$value) {
        $value['username'] = $value['user']['username'];
      }
    }
    return ['total'=>$total, 'rows'=>$rows];
  }


}