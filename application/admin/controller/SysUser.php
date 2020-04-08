<?php
namespace app\admin\controller;
use think\Db;
use app\admin\model\SysUser as UserModel;
use app\admin\logic\SysUser as UserLogic;
use app\admin\model\AuthRole;
/**
* 后台用户管理
*/
class SysUser extends Base
{
  /**
   * 用户列表
   * @return [type] [description]
   */
  public function index()
  {
    $params = $this->request->get();
    if($params && $this->request->isAjax()){
      return UserLogic::getRows($params); //注意，ajax请求自动转换成json对象，这里不需要进行转换
    }
    return builder('datalist')
    ->title('用户列表')
    ->actionBtn('新增', 'admin/sys_user/add')
    ->searchBtn()
    ->searchInput('id', '用户ID')
    ->searchInput('username', '用户名')
    ->column('id', '用户ID')
    ->column('username', '用户名')
    ->column('role', '角色')
    ->column('email', '邮箱')
    ->column('phone', '电话')
    ->column('status', '状态', [1=>'正常', 0=>'禁用', 2=>'待审核'])
    ->column('create_time', '注册时间')
    ->column('update_time', '更新时间')
    ->columnBtn('编辑', 'admin/sys_user/edit', 'btn-info')
    ->columnBtn(['column'=>'status', 'title'=>['1'=>'禁用', '0'=>'启用', '2'=>'启用']], 'admin/sys_user/forbidden', 'btn-warning')
    ->textAlign('center')
    ->fetch();
  }

  /**
   * 用户新增
   */
  public function add()
  {
    $data = $this->request->post();
    if($data && $this->request->isAjax()){
      $result = UserLogic::add($data); 
      if( (int)$result > 0 ){
        $this->action_op_log("新增用户ID: " .$result);//操作日志
        $this->success('新增成功！','index');
      }else{
        $this->error($result);
      }
    }
    $role = AuthRole::where('status', 1)->column('name', 'id');
    return builder('form')
    ->input('username', '用户名（必须）')
    ->input('password', '密码（必须）', '', '输入密码', 'password')
    ->input('email', '电子邮箱', '', '请输入', 'text', false)
    ->input('phone', '手机号码', '', '请输入', 'text', false)
    ->select('status', '状态', ['1'=>'正常', '2'=>'待审核', '0'=>'禁用'], '1')
    ->select('role_id', '角色', $role, 2)
    ->method('POST')
    ->fetch();
  }

  /**
   * 用户编辑
   * @param  string $id [description]
   * @return [type]     [description]
   */
  public function edit()
  { 
    $id = $this->request->param('id', '');
    if(!$id){
      $this->error('缺少id参数');
    }
    $user = UserModel::get($id);
    $role = AuthRole::where('status', 1)->column('name', 'id');
    $role_id = $user->assignment->role_id;
    $roleName = $role[$role_id];
    if(!$user->username==='dmsadmin' && $roleName==='超级管理员'){
      $this->error('您无权操作！');
      return false;
    }
    $data = $this->request->post();
    if($data){   
      $result = UserLogic::edit($user, $data);
      if($result === true){
        $this->action_op_log("编辑用户ID: " .$id);//操作日志
        $this->success('编辑成功！', 'index');
      }else{
        $this->error($result);
      }
    }
    return builder('form')
    ->input('password', '密码', '', '留空表示不修改密码', 'password', false)
    ->input('email', '电子邮箱', $user->email, '请输入', 'text', false)
    ->input('phone', '手机号码', $user->phone, '请输入', 'text', false)
    ->input('id', '', $id, '', 'hidden', false)
    ->select('status', '状态', ['1'=>'正常', '2'=>'待审核', '0'=>'禁用'], $user->status)
    ->select('role_id', '角色', $role, $role_id)
    ->method('POST')
    ->fetch();
  }

  /**
   * 用户禁用
   */
  public function forbidden()
  { 
    $title = '';
    $id = $this->request->param('id', '');
    $user = UserModel::get($id);
    if($user){
      $title = $user->status ? '禁用' : '启用';
      if($this->request->param('confirm')){
        $user->status = $user->status ? 0 : 1;
        $res = $user->save();
        if($res){
          $this->action_op_log("禁用用户ID: " .$id);//操作日志
          $this->success('操作成功');
        }else{
          $this->error('操作失败！');
        }
      }
    }
    return builder('confirm')
    ->title($title)
    ->id($id)
    ->fetch();
  }

  /**
   * 登录日志
   * @return [type] [description]
   */
  public function loginlog()
  {
    $params = $this->request->get();
    if($params && $this->request->isAjax()){
      return UserLogic::getLogRows($params); //注意，ajax请求自动转换成json对象，这里不需要进行转换
    }
    return builder('datalist')
    ->searchBtn()
    ->searchInput('user_id', '输入用户ID')
    ->searchInput('username', '输入用户名')
    ->datePickers('', '日志时间')
    ->column('user_id', '用户ID')
    ->column('username', '用户名')
    ->column('type', '登录类型', [1=>'登入', 2=>'退出'])
    ->column('ip', '操作IP')
    ->column('create_time', '记录时间')
    ->fetch();
  }

  /**
   * 个人中心
   * @return [type] [description]
   */
  public function detail()
  {
    $user = UserModel::get($this->uid);
    if($this->request->post()){
      $email = $this->request->post('email', '');
      $phone = $this->request->post('phone', '');
      if($email && !preg_match("/^[\w\-]+\@[\w\-]+\.[a-z]{2,3}$/",$email)){
        $this->error('邮箱格式不正确！');
      }
      if($phone && !preg_match("/^1[3456789]\d{9}$/", $phone)){
        $this->error('请输入正确的手机号码！');
      }
      $user->email = $email ?: $this->email;
      $user->phone = $phone ?: $this->phone;
      if($user->save()){
        $this->success('操作成功！');
      }else{
        $this->error('无操作');
      }
    }
    
    return builder('form')
    ->input('username', '账号', $user->username, '', 'text', true, true)
    ->input('email', '电子邮箱', $user->email)
    ->input('phone', '手机号码', $user->phone)
    ->method('POST')
    ->fetch();
  }

  /**
   * 修改密码
   * @return [type] [description]
   */
  public function editpsd()
  {      
    $user = UserModel::get($this->uid);
    $password = $this->request->post('password', '');
    $oldpsd = $this->request->post('oldpsd', '');
    
    if($password){
      if($oldpsd && !password_verify($oldpsd, $user->password)){
        $this->error('旧密码错误！');
      }
      $user->password = password_hash($password, PASSWORD_DEFAULT);
      if($user->save()){
        $this->success('操作成功！');
      }else{
        $this->error('无操作');
      }
    }
    
    return builder('form')
    ->input('oldpsd', '旧密码', '', '请输入', 'password')
    ->input('password', '新密码', '', '请输入', 'password')
    ->method('POST')
    ->fetch();
  }

  /**
   * 编辑头像
   * @return [type] [description]
   */
  public function portrait()
  {
    if($_FILES){
      $file = $_FILES['files'];
      $fileName = $file['name'];
      $fileSize = $file['size'];
      $fileTemp = $file['tmp_name'];
      $prefix = substr($fileName, strrpos($fileName, '.') + 1);
      if(!in_array($prefix, ['jpg','jpeg','gif','png'])){
        $this->error('图片格式错误！请上传 jpg,jpeg,gif,png 格式的图片');
      }
      if($fileSize > 2*1024*1024){
        $this->error('请上传小于2MB大小的图片');
      }
      $newFile = 'images/portrait/sysUser' . $this->uid . '.' . $prefix;
      $user = UserModel::get($this->uid);
      if( $user->portrait != $newFile ) { //不相同才更新数据库
        $user->portrait = $newFile;
        $res = $user->save();
        if(!$res){
          $this->error('数据保存失败！');
        }
      } 
      if( move_uploaded_file($fileTemp, ROOT_PATH . '/public/static/admin/' . $newFile) ){
        $this->success('更改成功！');
      }else{
        $this->error('文件上传失败！');
      }
    }
    return builder('form')
    ->file('portrait', '选择头像文件')
    ->method('POST')
    ->fetch();
  }


  /**
   * 操作日志
   * @return [type] [description]
   */
  public function oplog()
  {
    $params = $this->request->get();
    if($params && $this->request->isAjax()){
      return UserLogic::getOpLogRows($params); //注意，ajax请求自动转换成json对象，这里不需要进行转换
    }
    return builder('datalist')
    ->searchBtn()
    ->searchInput('user_id', '输入用户ID')
    //->searchInput('username', '输入用户名')
    ->datePickers('', '日志时间')
    ->column('user_id', '用户ID')
    ->column('username', '用户名')
    ->column('op_title', '操作名称')
    ->column('remark', '具体内容')
    ->column('ip', '操作IP')
    ->column('create_time', '记录时间')
    ->fetch();
  }


}