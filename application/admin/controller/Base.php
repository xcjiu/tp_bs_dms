<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use app\admin\logic\Auth as AuthLogic;
use app\admin\logic\Login;

/**
* 后台控制器公共类
*/
class Base extends Controller
{
  protected $uid;
  /**
   * 初始化
   * 优化URL访问和判断是否登录状态等
   */
	public function _initialize()
	{
    $this->checkController(); //检查控制器是否存在，优化URL
    $user = Login::isLogin();
		if(!$user){ 
      $this->redirect('admin/login/index?top=true');
    }
    $this->uid = $user->id;
    $lockScreen = Session::get('lockScreen'. $this->uid)===true ? '' : 'hide';
    $this->assign('lockScreen', $lockScreen); //是否锁屏状态
    $this->assign('sysUser', $user); //用户基本信息
    if(!Session::get('userAuths')){ //用户具体操作的权限
      Session::set('userAuths', array_column(AuthLogic::auths($this->uid), 'link'));
    }
	}


/**
 * 空操作
 * @param  string $name 方法名称
 * @return string
 */
public function _empty($name)
{
  return '<h1 style="text-align:center;color:red;padding:30px;">'.$name.' 方法不存在！</h1>';
    
}

protected function checkController()
{
  //当前模块名称
  $module = $this->request->module();
  //当前请求的控制器
  $controller = $this->request->controller();
  if(!is_file(APP_PATH . $module . DS . $controller)){
    return '<h1 style="text-align:center;color:red;padding:30px;">'. $controller. ' 控制器不存在！</h1>' ;
  }
}


    
}


