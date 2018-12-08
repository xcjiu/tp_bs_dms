<?php
namespace app\admin\controller;
use app\admin\logic\Auth;
use think\Config;

/**
* 
*/
class Index extends Base
{
	
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

		return $this->fetch();
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

