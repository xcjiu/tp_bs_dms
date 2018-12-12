<?php
namespace app\common\builder;
use think\Session;

/**
* 数据页面构建器
*/
class Datalist extends Builder
{
  protected $template = APP_PATH . "common/view/builder/datalist.html";
  protected $templateData = [
    'title'        => '', //页面标题
    'actionBtn'    => [], //操作按钮组，如新增，禁用等
    'searchBtn'    => false, //查询按钮, 有查询按钮说明有查询条件
    'searchInput'  => [], //条件查询输入框组
    'searchSelect' => [], //条件查询下拉框组
    'datePicker'   => [], //日期时间单选择器
    'datePickers'  => [], //日期时间双选择器
    'primaryKey'   => 'id', //字段主键名，默认为id
    'columns'      => [], //要显示的字段组
    'columnBtn'    => [], //每一条数据的操作项
    'checkbox'     => false, //是否开启多选框
    'dataUrl'      => '', //页面数据加载地址，为空表示为当前控制器加载，如果控制器中代码较多可以指定逻辑方法来加载数据
  ];

  /**
   * 设置页面标题
   * @param  string $title 标题名称
   * @return this
   */
  public function title($title='')
  {
    $this->templateData['title'] = $title;
    return $this;
  }

  /**
   * 操作按钮
   * @param  string $title  操作名称
   * @param  string $url 操作目标，即要操作的方法url
   * @param  string $color  按钮颜色类名，可参考bootstrap4按钮颜色样式，如:btn-success
   * @param  string $modalWidth  模态框大小，可参考bootstrap4的Modal，如:modal-lg
   * @return this
   */
  public function actionBtn($title, $url, $color='btn-info', $modalWidth='')
  {
    if( !in_array($url, Session::get('userAuths')) ){ //确认权限
      $this->templateData['actionBtn'][] = ['title'=>$title, 'url'=>$url, 'color'=>$color, 'modalWidth'=>$modalWidth];
    }
    return $this;
  }

  /**
   * 显示查询按钮
   * @return this
   */
  public function searchBtn()
  {
    $this->templateData['searchBtn'] = true;
    return $this;
  }

  /**
   * 条件查询输入框
   * @param  string $name        name属性值
   * @param  string $title       标题说明，不填则默认无标题说明
   * @param  string $placeholder 框内提示文字
   * @param  string $style input框样式，可以自定义修改默认的大小,格式为css样式格式
   * @return this
   */
  public function searchInput($name, $placeholder, $title='', $style='')
  {
    $this->templateData['searchInput'][] = ['name'=>$name, 'placeholder'=>$placeholder, 'title'=>$title, 'style'=>$style];
    return $this;
  }

  /**
   * 条件查询下拉框
   * @param  string $name        name属性值,一般为要查询的字段名
   * @param  string $title       下拉框提示语
   * @param  string $placeholder 框内提示文字
   * @param  string $style input框样式，可以自定义修改默认的大小,格式为css样式格式
   * @return this
   */
  public function searchSelect($name, $title='请选择', $dropdownData=[], $style='')
  {
    $this->templateData['searchSelect'][] = ['name'=>$name, 'title'=>$title, 'dropdownData'=>$dropdownData, 'style'=>$style];
    return $this;
  }

  /**
   * 日期时间单选择器
   * @param  string  $name            name属性名
   * @param  string  $title           标题
   * @param  string  $default         默认值
   * @param  string  $format          日期格式，YYYY-MM-DD 或 YYYY-MM-DD HH:mm:ss
   * @param  string  $min             限制最小日期时间，为空则不限制
   * @param  string  $max             限制最大日期时间，为空则不限制
   * @param  boolean $shortcut        是否显示快捷选择，对应shortcutOptions快捷项
   * @param  array   $shortcutOptions 快捷选择项，多维数组。如：[['name'=>'今天','day'=>'0'], ['name'=>'三天前', 'day'=>'-3']]
   * @return this
   */
  public function datePicker($name='date', $title='日期', $default='', $format='YYYY-MM-DD', $min='', $max='', $shortcut=true, $shortcutOptions=[])
  {
    $format = $format ?: 'YYYY-MM-DD';
    $this->templateData['datePicker'] = ['name'=>$name, 'title'=>$title, 'default'=>$default, 'format'=>$format, 'min'=>$min, 'max'=>$max, 'shortcut'=>$shortcut, 'shortcutOptions'=>$shortcutOptions];
    return $this;
  }

   /**
   * 日期时间双选择器
   * @param  array   $name            name属性名, 双选择器有两个name属性，设置start_name和end_name
   * @param  string  $title           标题
   * @param  array  $default          默认值, 设置开始值和结束值
   * @param  string  $format          日期格式，YYYY-MM-DD 或 YYYY-MM-DD HH:mm:ss
   * @param  string  $min             限制最小日期时间，为空则不限制
   * @param  string  $max             限制最大日期时间，为空则不限制
   * @param  boolean $shortcut        是否显示快捷选择，对应shortcutOptions快捷项
   * @param  array   $shortcutOptions 快捷选择项，多维数组。如：[['name'=>'今天','day'=>'0,0'], ['name'=>'三天前', 'day'=>'-3,0']]
   * @return this
   */
  public function datePickers($name=[], $title='日期', $default=[], $format='YYYY-MM-DD', $min='', $max='', $shortcut=true, $shortcutOptions=[])
  {
    $name = $name ?: ['start_name'=>'start_time', 'end_name'=>'end_time']; 
    $format = $format ?: 'YYYY-MM-DD';
    $default = $default ?: ['start_default'=>date('Y-m-01'), 'end_default'=>date('Y-m-d')];
    $this->templateData['datePickers'] = ['name'=>$name, 'title'=>$title, 'default'=>$default, 'format'=>$format, 'min'=>$min, 'max'=>$max, 'shortcut'=>$shortcut, 'shortcutOptions'=>$shortcutOptions];
    return $this;
  }


  /**
   * 设置主键名，默认为id
   * @param  string $keyName 主键字段名
   * @return this
   */
  public function primaryKey($keyName='id')
  {
    $this->templateData['primaryKey'] = $primaryKey;
    return $this;
  }

  /**
   * 字段设置
   * @param  string $field     字段名
   * @param  [type] $title     字段显示标题
   * @param  string $formatter 内容自定义
   * @param  string $align 内容对齐方式
   * @return this
   */
  public function column($field, $title, $formatter='', $align='center')
  {
    $this->templateData['columns'][] = ['field'=>$field, 'title'=>$title, 'formatter'=>$formatter, 'align'=>$align];
    return $this;
  }

  /**
   * 数据操作项按钮组
   * @param  string | array   $title 操作标题, 如果要根据指定字段值来动态展示按钮，则参数为数组，如：
   * $title = ['column'=>'status', 'title'=>['1'=>'禁用', '0'=>'启用', '2'=>'启用']]，column指定的字段名, title标题名称规则
   * 这会根据数组中的规则来动态展示操作按钮标题
   * @param  string $url   操作方法，即操作url
   * @param  string $color 按钮颜色类，参考bootstrap4的按钮颜色，默认：btn-info
   * @param  string $DIYclass 自定义类名，方便查找指定类名的操作项或改变该按钮的样式
   * @return this
   */
  public function columnBtn($title, $url, $color='btn-info', $DIYclass='')
  {
    if( in_array($url, Session::get('userAuths')) ){ //确认权限
      $this->templateData['columnBtn'][] = ['title'=>$title, 'url'=>$url, 'color'=>$color, 'DIYclass'=>$DIYclass];
    }
    return $this;
  }

  /**
   * 表格内容对齐方式
   * @param  string $align 
   * @return this
   */
  public function textAlign($align='center')
  {
    if($this->templateData['columns']){
      foreach ($this->templateData['columns'] as &$value) {
        $value['align'] = $align;
      }
    }
    return $this;
  }

  /**
   * 是否显示数据列选框
   * @param  boolean $check  设置true为显示，false为不显示
   * @return this
   */
  public function checkbox($check=true)
  {
    $this->templateData['checkbox'] = $check;
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

    $this->templateData['dataUrl'] = $this->templateData['dataUrl'] ?: $this->request->path();
    $this->assign($this->templateData);

    return parent::fetch($template);
  }
  
}