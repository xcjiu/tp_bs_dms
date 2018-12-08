<?php
namespace app\admin\controller;
use think\Db;
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
    $id = $this->request->get('id');
    $limit = $this->request->get('limit', 10);
    $offset = $this->request->get('offset', 0);
    if($username){
      $condition['username'] = $username;
    }
    if($id){
      $condition['id'] = $id;
    }
    if($condition){
      $data = Db::name('sysUser')->where($condition)->limit($offset, $limit)->select();
      $total = Db::name('sysUser')->where($condition)->count();
    }else{
      $data = Db::name('sysUser')->limit($offset, $limit)->select();
      $total = Db::name('sysUser')->count();
    }
    //此处不能只判断为ajax请求就请求数据，因为页面输出也是ajax请求过去的，刷新或搜索数据带上type=rereshData判断
    if($this->request->param('type')==='refreshData' && $this->request->isAjax()){
      return ['total'=>$total,'data'=>$data]; //注意，ajax请求自动转换成json对象，这里不需要进行转换
    }
    return builder('datalist')
      ->title('用户列表')
      ->actionBtn('新增', 'admin/sys_user/edit')
      ->actionBtn('禁用', 'admin/sys_user/forbidden', 'btn-warning')
      ->searchBtn()
      ->searchInput('id', '用户ID', 'center')
      ->searchInput('username', '用户名')
      ->searchSelect('status', '请选择状态', [1=>'正常', 2=>'待审核', 0=>'禁用'])
      //->datePickers()
      //->checkbox() 
      ->column('id', '用户ID')
      ->column('username', '用户名')
      ->column('email', '邮箱')
      ->column('phone', '电话')
      ->column('status', '状态', [1=>'正常', 0=>'禁用', 2=>'待审核'])
      ->column('create_time', '注册时间')
      ->column('update_time', '更新时间')
      ->columnBtn('编辑', 'admin/sys_user/edit', 'btn-info')
      ->columnBtn('禁用', 'admin/sys_user/forbidden', 'btn-warning')
      ->textAlign('center')
      ->fetch();
  }

  public function edit($id='')
  { 
    $title = $id ? '新增' : '编辑';
    return builder('form')
      ->input('用户名', 'username')
      ->input('密码', 'password')
      ->radio('性别', 'gender', [['text'=>'男', 'value'=>1, 'checked'=>true],['text'=>'女', 'value'=>0, 'checked'=>false]])
      ->select('状态', 'status', [['text'=>'正常', 'value'=>'1', 'selected'=>true],['text'=>'待审核', 'value'=>'2', 'selected'=>false],['text'=>'禁用', 'value'=>'0', 'selected'=>false]])
      ->fetch();
  }

  public function forbidden($id)
  {
    return 'true';
  }

}