<?php
namespace app\common\builder;
use app\admin\model\AuthRule;

/**
* 确认框构建器
*/
class SelectTree extends Builder
{
  protected $template = APP_PATH . "common/view/builder/selectTree.html";
  protected $templateData = [
    'title'      => '操作', //操作标题
    'id'         => '', //数据id
    'submitUrl' => '', //后台地址，默认为空表示为控制器当前url地址
    'checkHtml'  => ''
  ];

  /**
   * 标题
   * @param  string $title 提示标题
   * @return this
   */
  public function title($title='')
  {
    $this->templateData['title'] = $title;
    return $this;
  }

  /**
   * 数据ID
   * @param  int $id    主键值
   * @return this
   */
  public function id($id)
  {
    $this->templateData['id'] = $id;
    return $this;
  }

  public function checkHtml($html)
  {
    $this->templateData['checkHtml'] = (string)$html;
    return $this;
  }


   /**
   * 加载视图
   * @param  string $template 视图模板
   * @param  array  $vars     [description]
   * @param  array  $replace  [description]
   * @param  array  $config   [description]
   * @return [type]           [description]
   */
  public function fetch($template = '', $vars = [], $replace = [], $config = [])
  {
    $menu = AuthRule::where('status', 1)->where('type', 1)->field('id,title,pid')->order('sort asc')->select();
    $module = AuthRule::where('status', 1)->where('type', 0)->column('title', 'id');
    $actions = AuthRule::where('status', 1)->where('type', 2)->column('title', 'id');
    $template = $template ?: $this->template;
    $this->templateData['submitUrl'] = $this->templateData['submitUrl'] ?: $this->request->path();

    $this->templateData['checkHtml'] = '<h3 class="text-info">侧边栏菜单权限</h3>' . AuthTree($menu);
    $this->assign($this->templateData);

    return parent::fetch($template);
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