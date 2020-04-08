<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\logic\Login as LoginLogic;

/**
* 系统登录类
*/
class Login extends Controller
{
	/**
	 * 登录页面
	 * 
	 */
	public function index($top='')
	{	
    $setup = new Setup(); //先安装
    if(!$setup->isSetup()) {
      $this->redirect('admin/setup/index');
    }

    $loginNum = LoginLogic::getLoginNum(); //登录失败次数,从0开始记
		$this->assign('login_num', $loginNum);
		$data = $this->request->post();
		if($this->request->isAjax() && $data){
			if($loginNum<3){ //登录错误超过三次后要输入验证码验证
				unset($data['captcha']);
			}
			$loginResult = LoginLogic::login($data);
			if($loginResult===true){
				$this->success('登录成功！', 'admin/index/index');
			}else{
				LoginLogic::setLoginNum($loginNum); //记录登录错误次数
				$this->error($loginResult,'',$loginNum);
			}
		}
    if($top){ //最外层跳转，当退出登入或session过期时防止登入页面出现在小框架内部
      $top = $this->request->root() . '/admin/login/index';
    }
    $this->assign('topLogin', $top);
		return $this->fetch();
	}

	/**
   * 退出登录
   * @return 跳转至登录界面
   */
  public function logout(){
    LoginLogic::logout();
    $this->redirect('admin/login/index?top=true');
  }

  
}