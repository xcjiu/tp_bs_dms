<?php
namespace app\admin\controller;
use app\admin\logic\Auth;
use think\Config;
use think\Session;

/**
* 
*/
class Index extends Base
{
	/**
   * 后台框架初始化
   * 判断权限和是否已登录
   */
	public function index()
	{
    $auths = Auth::auths($this->uid);
    //如果不需要顶部模块则设置为空
    if(!Config::get('auth_module')){
      $auths['topAuth'] = '';
    }
    $pids = array_unique(array_column($auths['menuAuth'], 'pid'));
    $auths['menuAuth'] = menu_tree($auths['menuAuth'], 0, $pids);

    //权限变量输出
    $this->assign($auths); 
    $this->assign('domain', $this->request->root(true) . "/");//获取当前包含域名的ROOT地址
    if($auths['actionAuth']){ //操作权限
      Session::set('userAuths', array_column($auths['actionAuth'], 'link'));
    }
		return $this->fetch('layout/base');
	}

  //首页
  public function home()
  {
    return $this->fetch('home');
  }

  //锁屏或解屏操作
  public function lockscreen()
  {
    if($this->request->isAjax()){
      $type = $this->request->param('type','');     
      $password = $this->request->param('psd', '');
      if(!$password){
        $this->error('请输入密码！');
      }
      if($type === 'lock'){ //锁屏
        Session::set('lockScreen' . $this->uid, true); 
        Session::set('lockscreenPsd' . $this->uid, md5($password));
        $this->success('操作成功！');
      }else if($type === 'unlock'){ //解屏
        $lockscreenPsd = Session::get('lockscreenPsd' . $this->uid);
        if($lockscreenPsd === md5($password)){
          Session::set('lockScreen' . $this->uid, false);
          $this->success('解屏成功！');        
        }else{
          $this->error('密码错误！');
        }
      }
    }
    $this->error('操作有误！');
  }



  public function test()
  {
    return Auth::userRole(1);
  }
}









/**
 * 侧边栏菜单树,递归
 */
function menu_tree($data, $pid=0, $pids=[]){
  $html = '';
  foreach ($data as $key => $value) {
    $active = '';
    $contentId = 'tab-content-' . $value['id'];
    if($value['title']=='首页'){ //首选项
      $active = 'active menu-chose';
      $contentId = 'home';
    }
    if(empty($value['icon'])){
      $value['icon'] = 'fa-location-arrow';
    }
    $dropdownIcon = ''; //下拉图标
    if(in_array($value['id'], $pids)){
      $dropdownIcon = '<span><i class="fa fa-chevron-right pull-right pt-1"></i></span>';
    }
    if ($value['pid'] == $pid) {
      $link = $value['link'];
      $link = "'$link'";
      $html .= '<li class="nav-item '. $active .'">
      <a class="nav-link" href="javascript:void(0)" content-id="'. $contentId .'"
      onclick="open_page('.$link.', this)">
      <i class="fa '.$value['icon'].'"></i>
      &nbsp;&nbsp;'.$value['title'].$dropdownIcon.'</a>';
      $html .= menu_tree($data, $value['id'], $pids);
      $html = $html.'</li>';
    }
  }
  return ($pid && $html) ? '<ul class="text-left pl-2 sub-menu hide">'.$html.'</ul>' : $html;
}

