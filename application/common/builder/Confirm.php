<?php
namespace app\common\builder;
// +-----------------------------------------------------
// | 确认框构建器
// +-----------------------------------------------------
// | author: xcjiu
// +-----------------------------------------------------
// | github: https://github.com/xcjiu/tp_bs_dms
// +-----------------------------------------------------

class Confirm extends Builder
{
  protected $template = APP_PATH . "common/view/builder/confirm.html";
  protected $templateData = [
    'title'      => '操作', //操作标题
    'id'         => '', //数据id
    'content'    => '', //文本内容，用来说明
    'extendsParam'    => '', //额外参数
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
   * 文本说明
   * @param  string $content 说明文字
   * @return this
   */
  public function content($content='')
  {
    $this->templateData['content'] = $content;
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
   * 额外参数，有时会有多个其它参数的需求
   * @param  int $id    主键值
   * @return this
   */
  public function extendsParam($params)
  {
    if(is_array($params)){
      $params = json_encode($params);
    }
    $this->templateData['extendsParam'] = $params;
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