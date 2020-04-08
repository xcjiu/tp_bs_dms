<?php
namespace app\common\builder;
// +-----------------------------------------------------
// | 图表构建器
// +-----------------------------------------------------
// | author: xcjiu
// +-----------------------------------------------------
// | github: https://github.com/xcjiu/tp_bs_dms
// +-----------------------------------------------------

class Charts extends Builder
{
  protected $template = APP_PATH . "common/view/builder/charts.html";
  protected $templateData = [
    'theme' => 'light', //主题,不填则默认主题，light, dark, vintage, macarons, infographic, shine, roma
    'bg'    => '#292a34', //背景色
    'id'    => '', //图表id, 在有多个图表放在同一页面下时要做的id区分，如果只一个图表则不用设置
    'title' => '', //图表标题
    'type'  => 'bar', //图表类型，默认bar, 另外有line, pie
    'name'  => '数据说明', //数据标题说明
    'Xname' => 'X轴说明', //数据标题说明
    'Yname' => 'Y轴说明', //数据标题说明
    'legendData' => [], //每条数据的文字说明
    'data'  => [], //要呈现的数据
    'unit'  => '', //数据单位
    'extendsParam'    => '', //额外参数
    'confirmUrl' => '', //后台地址，默认为空表示为控制器当前url地址
  ];

  /**
   * 每条数据的文字说明
   * @param  array  $data [description]
   * @return [type]       [description]
   */
  public function legendData(array $data)
  {
    $this->templateData['legendData'] = $data;
    return $this;
  }

  /**
   * 图表主题
   * @param  string $theme 主题名称
   * @return this
   */
  public function theme($theme='')
  {
    $this->templateData['theme'] = $theme;
    return $this;
  }

  /**
   * 图表背景色
   * @param  string $bg 背景色
   * @return this
   */
  public function bg($bg='')
  {
    if(empty($bg)){
      $bg = '#292a34';
    }
    $this->templateData['bg'] = $bg;
    return $this;
  }

  /**
   * 图表ID
   * @param  int | string $id
   * @return this
   */
  public function id($id='')
  {
    $this->templateData['id'] = $id;
    return $this;
  }


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
   * 图表类型
   * @param  string $type 图表类型
   * @return this
   */
  public function type($type='bar')
  {
    $this->templateData['type'] = $type;
    return $this;
  }

  /**
   * 数据标题说明
   * @param  string $name 数据标题说明
   * @return this
   */
  public function name($name='')
  {
    $this->templateData['name'] = $name;
    return $this;
  }

  /**
   * x轴说明
   * @param  string $Xname 数据标题说明
   * @return this
   */
  public function Xname($Xname='')
  {
    $this->templateData['Xname'] = $Xname;
    return $this;
  }

  /**
   * 数据标题说明
   * @param  string $Yname 数据标题说明
   * @return this
   */
  public function Yname($Yname='')
  {
    $this->templateData['Yname'] = $Yname;
    return $this;
  }

  /**
   * 数据单位
   * @param  string $type 数据单位
   * @return this
   */
  public function unit($unit='')
  {
    $this->templateData['unit'] = $unit;
    return $this;
  }

  /**
   * 要呈现的数据
   * @param  string $type 要呈现的数据
   * @return this
   */
  public function data(array $data)
  {
    $this->templateData['data'] = $data;
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
    
    $this->assign($this->templateData);

    return parent::fetch($template);
  }
  
}