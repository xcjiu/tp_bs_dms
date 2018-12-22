<?php
namespace app\common\builder;

/**
* 表单构建器
*/
class Form extends Builder
{
  protected $template = APP_PATH . "common/view/builder/form.html";
  protected $templateData = [
    'method'    => 'GET', //表单提交方法
    'inputs'    => [], //input框组
    'selects'   => [], //下拉框组
    'radios'    => [], //单选组
    'checkboxs' => [], //多选组
    'files'     => [], //文件选择组
    'textareas' => [], //文本框组
    'submitUrl' => '', //表单提交给后台地址，默认为空表示为控制器当前url地址
    'extraHtml' => '', //额外自定义html代码
    'extraStyle'=> '', //自定认样式，格式<style>...</style>
    'extraJs'   => '', //额外自定义js代码, 格式<script>...</script>
  ];

  /**
   * 表单提交方法
   * @param  string $method 方法名称，构建器默认GET，方法默认POST
   * @return this
   */
  public function method($method='POST')
  {
    $this->templateData['method'] = strtoupper($method);
    return $this;
  }

  /**
   * 表单信息提交的url后台地址
   * @param  string $url  url地址，格式为 模块/控制器/方法/参数 如：'admin/index/edit?id=1'
   * @return this
   */
  public function submitUrl($url='')
  {
    $this->templateData['submitUrl'] = $url;
    return $this;
  }

  /**
   * 添加自定义html
   * @param  string $html 自定义html
   * @return this
   */
  public function extraHtml($html='')
  {
    $this->templateData['extraHtml'] = (string)$html;
    return $this;
  }

  /**
   * 添加自定义样式
   * @param  string $style 自定义样式
   * @return this
   */
  public function extraStyle($style='')
  {
    $this->templateData['extraStyle'] = (string)$style;
    return $this;
  }

  /**
   * 添加自定义js
   * @param  string $Js 自定义Js
   * @return this
   */
  public function extraJs($js='')
  {
    $this->templateData['extraJs'] = (string)$js;
    return $this;
  }

  /**
   * 添加input框
   * @param  string  $title       标题说明
   * @param  string  $name        name属性名称
   * @param  string  $default     默认值
   * @param  string  $placeholder 框内提示文字
   * @param  string  $type        input类型
   * @param  boolean $required    是否必须，默认true为必须
   * @param  boolean $readOnly    是否只读，默认false为否
   * @return this
   */
  public function input($name, $title, $default='', $placeholder='请输入', $type="text", $required=true, $readOnly=false)
  {
    $this->templateData['inputs'][] = ['title'=>$title, 'name'=>$name, 'default'=>$default, 'type'=>$type, 'placeholder'=>$placeholder, 'required'=>$required, 'readOnly'=>$readOnly];
    return $this;
  }

  /**
   * 添加下拉框
   * @param  string  $title       标题说明
   * @param  string  $name        name属性名称
   * @param  array   $options     选项组,['value1'=>'text1', 'value2'=>'text2'], text显示内容，value选项值 
   * @param  void    $default     默认选中项，填写$option数组中的value选项值
   * @param  string  $placeholder 框内提示文字
   * @param  boolean $required    是否必须，默认true为必须
   * @return this
   */
  public function select($name, $title, $options=[], $default='', $placeholder='请选择', $required=true)
  {
    $this->templateData['selects'][] = ['title'=>$title, 'name'=>$name, 'options'=>$options, 'default'=>$default, 'placeholder'=>$placeholder, 'required'=>$required];
    return $this;
  }

  /**
   * 添加单选组
   * @param  string  $title       标题说明
   * @param  string  $name        name属性名称
   * @param  array   $options     单选组, 参照['value'=>'text'], text显示内容，value选项值
   * @param  void    $default     默认选中项，填写$option数组中的value选项值
   * @param  boolean $required    是否必须，默认true为必须
   * @return this
   */
  public function radio($name, $title, $options=[], $default='', $required=true)
  {
    $this->templateData['radios'][] = ['title'=>$title, 'name'=>$name, 'options'=>$options, 'default'=>$default, 'required'=>$required];
    return $this;
  }

  /**
   * 添加多选组
   * @param  string  $title       标题说明
   * @param  string  $name        name属性名称
   * @param  array   $options     多选组, 参照['value1'=>'text1', 'value2'=>'text2'], text显示内容，value选项值
   * @param  void    $default     默认选中项，填写$option数组中的value选项值
   * @param  boolean $required    是否必须，默认true为必须
   * @return this
   */
  public function checkbox($name, $title, $options=[], $default='', $required=true)
  {
    $this->templateData['checkboxs'][] = ['title'=>$title, 'name'=>$name, 'options'=>$options, 'default'=>$default, 'required'=>$required];
    return $this;
  }

  /**
   * 添加文件上传组
   * @param  string  $title       标题说明
   * @param  string  $name        name属性名称
   * @param  boolean $required    是否必须，默认true为必须
   * @return this
   */
  public function file($name, $title, $required=true)
  {
    $this->templateData['files'][] = ['title'=>$title, 'name'=>$name, 'required'=>$required];
    return $this;
  }

  /**
   * 文本框
   * @param  string $name    name属性
   * @param  string $title   标题说明
   * @param  boolean $required 是否必须
   * @return this
   */
  public function textarea($name, $title, $default='', $required=false)
  {
    $this->templateData['textareas'][] = ['title'=>$title, 'name'=>$name, 'default'=>$default, 'required'=>$required];
    return $this;
  }

  /**
   * 设置表单请求的后台地址url, 默认为当前
   * @param  string $url [description]
   * @return [type]      [description]
   */
  public function formUrl($url='')
  {
    $this->templateData['url'] = $url;
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

    $this->templateData['submitUrl'] = $this->templateData['submitUrl'] ?: $this->request->path();
    $this->assign($this->templateData);

    return parent::fetch($template);
  }
  
}