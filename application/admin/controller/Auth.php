<?php
namespace app\admin\controller;
use app\admin\model\AuthRule;
use app\admin\logic\Auth as AuthLogic;

/**
* 权限数据管理类
*/
class Auth extends Base
{
  /**
   * 权限菜单管理
   * @return [type] [description]
   */
  public function index()
  {
    $builder = builder('datalist')
    ->title('权限菜单')
    ->actionBtn('新增', 'admin/auth/add', 'btn-info', 'modal-lg')
    ->searchBtn()
    ->searchInput('title', '权限标题')
    ->searchInput('link', '权限链接')
    ->searchSelect('type', '权限类型', [0=>'顶部导航', 1=>'侧边菜单', 2=>'具体操作']);

    $moduleData = AuthRule::modules();
    if($moduleData){ 
      //顶部导航模块
      $builder = $builder->searchSelect('module', '所属模块', $moduleData);
    }
    
    $params = $this->request->param();
    if($params && $this->request->isAjax()){
      return AuthLogic::getAuthRows($params); //注意，ajax请求自动转换成json对象，这里不需要进行转换
    }
    return $builder->column('id', 'ID')
    ->column('title', '标题')
    ->column('link', '权限链接')
    ->column('type', '类型', [0=>'顶部导航', 1=>'侧边菜单', 2=>'具体操作'])
    ->column('module', '所属模块', $moduleData)
    ->column('pid', '父级ID')
    ->column('sort', '排序')
    ->column('icon', '图标')
    ->column('status', '状态', [0=>'禁用', 1=>'正常'])
    ->column('description', '说明')
    ->column('create_time', '创建时间')
    ->column('update_time', '更新时间')
    ->columnBtn('编辑', 'admin/auth/edit')
    ->columnBtn(['column'=>'status', 'title'=>['1'=>'禁用', '0'=>'启用']], 'admin/auth/forbidden', 'btn-warning')
    ->columnBtn('删除', 'admin/auth/delete', 'btn-danger')
    ->textAlign('left')
    ->fetch();
  }

  /**
   * 权限新增
   */
  public function add()
  {
    $postData = $this->request->post();
    if($this->request->isAjax() && $postData){   
      $res = AuthLogic::addAuth($postData);
      if($res === true){
        $this->success('新增成功！', 'index');
      }else{
        $this->error($res);
      }
    }
    return builder('form')
    ->input('title', '菜单标题')
    ->input('link', '权限链接（格式参照：admin/index/index）')
    ->select('type', '选择类型', [0=>'顶部导航', 1=>'侧边菜单', 2=>'具体操作'], 1)
    ->select('module', '所属模块（如果不需要模块导航则不选）', AuthRule::modules(), 0)
    ->select('pid', '父级菜单（默认为一级菜单）', AuthRule::menus())
    ->input('sort', '排序', 99)
    ->input('icon', '图标（请参照图标库：http://www.fontawesome.com.cn/faicons）', 'fa-location-arrow')
    ->select('status', '状态（默认正常）', [0=>'禁用', 1=>'正常'], 1)
    ->method('POST')
    ->fetch();
  }

  /**
   * 权限删除
   * @return [type] [description]
   */
  public function delete()
  {
    $id = $this->request->param('id', '');
    $confirm = $this->request->post('confirm');
    if(!$id){
       $this->error('缺少id参数');
    }
    if($confirm){ //确认过后再操作
      if(AuthRule::destroy($id)){
        $this->success('删除成功！');
      }else{
        $this->error('操作失败！');
      }
    }
    return builder('confirm')
    ->title('删除')
    ->id($id)
    ->fetch(); //先来个确认弹框
  }

  /**
   * 编辑权限
   * @return [type] [description]
   */
  public function edit()
  {
    $id = $this->request->param('id', '');
    $auth = AuthRule::get($id);
    $postData = $this->request->post();
    if($id && $postData){   
      $res = AuthLogic::editAuth($id, $postData);
      if($res === true){
        $this->success('编辑成功！', 'index');
      }else{
        $this->error($res);
      }
    }
    return builder('form')
    ->input('title', '菜单标题', $auth->title)
    ->input('link', '权限链接', $auth->link)
    ->select('type', '选择类型', [0=>'顶部导航', 1=>'侧边菜单', 2=>'具体操作'], $auth->type)
    ->select('module', '所属模块（如果不需要模块导航则不选）', AuthRule::modules(), $auth->module)
    ->select('pid', '父级菜单（默认为一级菜单）', AuthRule::menus(), $auth->pid)
    ->input('sort', '排序', $auth->sort)
    ->input('icon', '图标（请参照图标库：http://www.fontawesome.com.cn/faicons）', $auth->icon)
    ->select('status', '状态（默认正常）', [0=>'禁用', 1=>'正常'], $auth->status)
    ->method('POST')
    ->fetch();
  }

  /**
   * 权限禁用
   * @return [type] [description]
   */
  public function forbidden()
  {
    $id = $this->request->param('id', '');
    $confirm = $this->request->post('confirm');
    if(!$id){
      $this->error('缺少id参数');
    }
    $title = '';
    $auth = AuthRule::get($id);
    if($auth){
      $title = $auth->status ? '禁用' : '启用';
      if($confirm){ //确认过后再操作
        $auth->status = $auth->status ? 0 : 1;
        if($auth->save()){
          $this->success('操作成功！');
        }
        $this->error('操作失败！');
      }
    }
    return builder('confirm')
    ->title($title)
    ->id($id)
    ->fetch(); //先来个确认弹框
  }

}