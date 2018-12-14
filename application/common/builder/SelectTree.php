<?php
namespace app\common\builder;

/**
* 确认框构建器
*/
class SelectTree extends Builder
{
  protected $template = APP_PATH . "common/view/builder/selectTree.html";
  protected $templateData = [
    'title'      => '操作', //操作标题
    'id'         => '', //数据id
    'confirmUrl' => '', //后台地址，默认为空表示为控制器当前url地址
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
    $template = $template ?: $this->template;

    $this->templateData['confirmUrl'] = $this->templateData['confirmUrl'] ?: $this->request->path();
    $this->assign($this->templateData);

    return parent::fetch($template);
  }

}