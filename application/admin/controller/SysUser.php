<?php
namespace app\admin\controller;
use think\Db;
use app\admin\model\SysUser as UserModel;
/**
* 
*/
class SysUser extends Base
{
  /**
   * 用户列表
   * @return [type] [description]
   */
  public function index()
  {
    $condition = [];
    $username = $this->request->get('username','');
    $id = $this->request->get('id','');
    $limit = $this->request->get('limit', 10);
    $offset = $this->request->get('offset', 0);
    if($username){
      $condition['username'] = $username;
    }
    if($id){
      $condition['id'] = $id;
    }
    if($condition){
      $data = UserModel::where($condition)->limit($offset, $limit)->select();
      $total = UserModel::where($condition)->count();
    }else{
      $data = UserModel::limit($offset, $limit)->select();
      $total = UserModel::count();
    }
    //此处不能只判断为ajax请求就请求数据，因为页面输出也是ajax请求过去的，刷新或搜索数据带上type=rereshData判断
    if($this->request->param() && $this->request->isAjax()){
      return ['total'=>$total,'rows'=>$data]; //注意，ajax请求自动转换成json对象，这里不需要进行转换
    }
    return builder('datalist')
      ->title('用户列表')
      ->actionBtn('新增', 'admin/sys_user/add')
      ->searchBtn()
      ->searchInput('id', '用户ID')
      ->searchInput('username', '用户名')
      ->column('id', '用户ID')
      ->column('username', '用户名')
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

  public function add()
  {
    if($this->request->post() && $this->request->isAjax()){
      $username = $this->request->post('username','');
      $password = password_hash($this->request->post('password',''), PASSWORD_DEFAULT);
      $status = (int)$this->request->post('status', 1);
      $res = UserModel::insert(['username'=>$username, 'password'=>$password, 'status'=>$status, 'create_time'=>date('Y-m-d H:i:s')]);
      if($res){
        $this->success('新增成功！','index');
      }else{
        $this->error('新增失败！');
      }
    }
      
    return builder('form')
      ->input('username', '用户名')
      ->input('password', '密码', '输入不少于6位数的密码', 'password')
      ->select('status', '状态', ['1'=>'正常', '2'=>'待审核', '0'=>'禁用'], '1')
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
    if($this->request->isAjax() && $id){
      $user = UserModel::get($id);
      $postData = $this->request->post();
      //return ['code'=>1, 'msg'=>'test', 'data'=>$postData];
      if($postData){
        $user->username = $this->request->post('username', '');
        $password = $this->request->post('password', '');
        if(!empty($password)){
          $user->password = password_hash($password, PASSWORD_DEFAULT);
        }
        $user->status = (int)$this->request->post('status', '');
        $res = $user->save();
        if($res){
          $this->success('编辑成功！','index');
        }else{
          $this->error('编辑失败！');
        }
      }
      
      
      return builder('form')
      ->input('username', '用户名', $user->username)
      ->input('password', '密码', '', '留空表示不修改密码', 'password', false)
      ->select('status', '状态', ['1'=>'正常', '2'=>'待审核', '0'=>'禁用'], $user->status)
      ->method('POST')
      ->fetch();
    }
  }

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

}