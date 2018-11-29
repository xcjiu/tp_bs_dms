<?php
namespace app\admin\controller;
use think\Controller;
use think\Cookie;
use think\Session;
use app\admin\logic\Login;

/**
* 后台控制器公共类
*/
class Base extends Controller
{
	protected $uid;

	public function _initialize()
	{
    $this->checkController(); //检查控制器是否存在，优化URL

    $this->uid = Login::isLogin();
		if($this->uid === false){ 
      $this->redirect('admin/login/index');
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


