<?php
namespace app\admin\logic;
use app\admin\model\SysUser as UserModel;

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
      if(isset($params['_'])){ unset($params['_']); }//这个参数是数据表插件自带的，这里不需要
      foreach ($params as $key => $value) {
        if($value != ''){
          $condition[$key] = $value;
        }
      }
    }
    if($condition){
      $rows = UserModel::where($condition)->field('password,token',true)->limit($offset, $limit)->select();
      $total = UserModel::where($condition)->count();
    }else{
      $rows = UserModel::field('password,token',true)->limit($offset, $limit)->select();
      $total = UserModel::count();
    }
    return ['total'=>$total,'rows'=>$rows]; 
  }

  /**
   * 添加用户
   * @param array $data 接收到的数据
   */
  public static function add($data)
  {
    $username = $data['username'];
    $password = $data['password'];
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
    $password = password_hash($password, PASSWORD_DEFAULT);
    $status = (int)$data['status'];
    if( (new UserModel())->allowField(true)->save($data) ){
      return true;
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
      $data['password'] = password_hash($password, PASSWORD_DEFAULT);
    }
    $data['status'] = int($data['status']);
    foreach ($data as $key => $value) {
      if($value != ''){
        $user->$key = $value;
      }
    }
    if($user->save()){
      return true;
    }
    return '编辑失败！';
  }



}