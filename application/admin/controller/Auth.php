<?php
namespace app\admin\controller;
use app\admin\model\AuthRule;
use app\admin\model\AuthRole;
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
        $this->action_op_log('新增权限：' . json_encode($postData)); //操作日志
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
    ->input('sort', '排序', 99, '', 'number')
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
        $this->action_op_log('权限删除ID：' . $id); //操作日志
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
    $params = $this->request->post();
    if( $params && $this->request->isAjax() ){   
      $res = AuthLogic::editAuth($id, $params);
      if($res === true){
        $this->action_op_log("权限编辑：id: $id; " . json_encode($params)); //操作日志
        $this->success('编辑成功！', 'index');
      }else{
        $this->error($res);
      }
    }    
    $auth = AuthRule::get($id);
    return builder('form')
    ->input('title', '菜单标题', $auth->title)
    ->input('link', '权限链接', $auth->link)
    ->select('type', '选择类型', [0=>'顶部导航', 1=>'侧边菜单', 2=>'具体操作'], $auth->type)
    ->select('module', '所属模块（如果不需要模块导航则不选）', AuthRule::modules(), $auth->module)
    ->select('pid', '父级菜单（默认为一级菜单）', AuthRule::menus(), $auth->pid)
    ->input('sort', '排序', $auth->sort, '', 'number')
    ->input('icon', '图标（请参照图标库：http://www.fontawesome.com.cn/faicons）', $auth->icon ?: 'fa-location-arrow')
    ->input('id', '', $id, '', 'hidden', false)
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
          $this->action_op_log("权限".$title." id: $id"); //操作日志
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

  /**
   * 角色管理
   * @return [type] [description]
   */
  public function role()
  {
    $params = $this->request->param();
    if($params && $this->request->isAjax()){
      return AuthLogic::getRoles($params);
    }
    return builder('datalist')
    ->title('角色管理')
    ->actionBtn('新增', 'admin/auth/addRole')
    ->searchBtn()
    ->searchInput('name', '角色名称')
    ->column('id', '角色ID')
    ->column('name', '角色名称')
    //->column('auth_ids', '拥有的权限id组')
    ->column('status', '状态', [0=>'禁用', 1=>'启用'])
    ->column('description', '说明')
    ->column('create_time', '创建时间', 'datetime')
    ->column('update_time', '更新时间', 'datetime')
    ->columnBtn('编辑', 'admin/auth/editRole')
    ->columnBtn(['column'=>'status', 'title'=>['1'=>'禁用', '0'=>'启用']], 'admin/auth/forbiddenRole', 'btn-warning')
    ->columnBtn('删除', 'admin/auth/deleteRole', 'btn-danger')    
    ->fetch();
  }

  /**
   * 角色新增
   */
  public function addRole()
  {
    $params = $this->request->post();
    if($params && $this->request->isAjax()){
      $result = AuthLogic::addRole($params);
      if( $result === true ){
        $this->action_op_log("角色新增：" . json_encode($params)); //操作日志
        $this->success('新增成功！');
      }else{
        $this->error($result);
      }
    }
    return builder('form')
    ->input('name', '角色名称')
    ->select('status', '状态', [0=>'禁用', 1=>'正常'], 1)
    ->textarea('description', '说明', '', false)
    ->method('POST')
    ->fetch();
  }

  /**
   * 角色编辑
   * @return [type] [description]
   */
  public function editRole()
  {
    $id = $this->request->param('id', '');
    $params = $this->request->post();
    if($params && $this->request->isAjax()){
      $result = AuthLogic::editRole($id, $params);
      if( $result === true ){
        $this->action_op_log("角色编辑：" . json_encode($params)); //操作日志
        $this->success('编辑成功！');
      }else{
        $this->error($result);
      }
    }
    $role = AuthRole::get($id);
    return builder('form')
    ->input('name', '角色名称', $role->name)
    ->select('status', '状态', [0=>'禁用', 1=>'正常'], $role->status)
    ->textarea('description', '说明', $role->description, false)
    ->input('id', '', $id, '', 'hidden', false)
    ->method('POST')
    ->fetch();
  }

  /**
   * 角色禁用
   * @return [type] [description]
   */
  public function forbiddenRole()
  {
    $id = $this->request->param('id', '');
    $confirm = $this->request->post('confirm');
    if(!$id){
      $this->error('缺少id参数');
    }
    $title = '';
    $role = AuthRole::get($id);
    if($role){
      $title = $role->status ? '禁用' : '启用';
      if($confirm){ //确认过后再操作
        $role->status = $role->status ? 0 : 1;
        if($role->save()){
          $this->action_op_log("角色" . $title . " id: $id"); //操作日志
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

  /**
   * 角色删除
   * @return [type] [description]
   */
  public function deleteRole()
  {
    $id = $this->request->param('id', '');
    $confirm = $this->request->post('confirm');
    if(!$id){
       $this->error('缺少id参数');
    }
    if($confirm){ //确认过后再操作
      if(AuthRole::destroy($id)){
        $this->action_op_log("角色删除：id: $id"); //操作日志
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
   * 按角色的分配权限操作
   * @return [type] [description]
   */
  public function assignmentAuth()
  {
    $id = (int)$this->request->param('id', '');
    $auth_id = (int)$this->request->param('auth_id', '');
    $auth_id = $auth_id ?: (int)$this->request->param('extendsParam', '');
    $confirm = $this->request->post('confirm');
    if($this->request->isAjax() && $confirm && $id && $auth_id){
      $authRole = AuthRole::get($id);
      if($authRole){
        $authIds = $authRole->auth_ids;
        if(empty($authIds)){
          $authRole->auth_ids = (string)$auth_id;
        }else{
          $aids = explode(',', $authIds);
          if(in_array($auth_id, $aids)){
            $key = array_search($auth_id, $aids);
            unset($aids[$key]);
            $authRole->auth_ids = implode(',', $aids);
          }else{
            $authRole->auth_ids .= ",$auth_id";
          }
        }
        if($authRole->save()){
          $this->action_op_log("按角色分配权限：id: $id; authIds: " . (string)$auth_id); //操作日志
          $this->success('操作成功！');
        }
      }
      $this->error('操作失败！');
    }
    $title = $this->request->get('check')==1 ? '解除该权限' : '添加该权限';
    return builder('confirm')
    ->title($title)
    ->id($id)
    ->extendsParam($auth_id)
    ->fetch();
  }

  /**
   * 权限分配，针对所有角色进行
   * @return [type] [description]
   */
  public function authAssignment()
  {
    $rolesName = AuthLogic::rolesName();
    $params = $this->request->param();
    if($params && $this->request->isAjax()){
      return AuthLogic::authAssignment($params);
    }
    
    $builder = builder('datalist')
    ->actionBtn('按单个角色进行分配权限', 'admin/auth/roleAssignment', 'btn-info', 'modal-lg')
    ->title('权限分配')
    ->column('title','权限名称', '', 'left');
    if($rolesName){
      foreach ($rolesName as $id => $name) {
        $builder = $builder->column('role' . $id, $name);
      }
    }
    return $builder->fetch();
  }

  /**
   * 按单个角色分配权限
   * @return [type] [description]
   */
  public function roleAssignment()
  {
    $postData = $this->request->post();
    if($this->request->isAjax() && $postData){
      $role_id = (int)$this->request->post('role_id', 0);
      if(empty($role_id)){
        $this->error('请选择角色');
      }
      $checked = $this->request->post('checked', '');
      if(empty($checked)){
        $this->error('请至少选择一项权限！');
      }
      $role = AuthRole::get($role_id);
      $role->auth_ids = $checked;
      if($role->save()){
        $this->action_op_log("按单个角色分配权限：" . json_encode($postData)); //操作日志
        $this->success('操作成功！','authAssignment');
      }
      $this->error('操作失败！');
    }
    $menu = AuthRule::where('status', 1)->where('type', 1)->field('id,title,pid')->order('sort asc')->select();
    if(\think\Config::get('auth_module')){
      $modules = AuthRule::where('status', 1)->where('type', 0)->column('title', 'id');
    }else{
      $modules = '';
    }
    $roles = AuthRole::where('status', 1)->column('name', 'id');
    $actions = AuthRule::where('status', 1)->where('type', 2)->column('title', 'id');
    $this->assign(['roles'=>$roles, 'modules'=>$modules, 'actions'=>$actions]);
    $this->assign('menuAuth', '<h3 class="text-info">侧边栏菜单权限</h3>' . AuthTree($menu));
    return $this->fetch();
  }


}





/**
 * 权限树
 * @return [type] [description]
 */
function AuthTree($data, $pid=0) 
{
  $html = '';
  foreach ($data as $key => $value) {
    if($value['pid'] == $pid) {
      $html .= '<li><label><input type="checkbox" name="selects" value="'.$value['id'].'">&nbsp;'.$value['title'].'</label>';
      $html .= AuthTree($data,$value['id']);
      $html = $html.'</li>';
    }
  }
  return $html ? '<ul>'.$html.'</ul>' : $html;
}