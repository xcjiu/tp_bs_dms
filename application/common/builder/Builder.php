<?php
namespace app\common\builder;
use think\Controller;
/**
* 构建器基类
*/
class Builder extends Controller
{
  public static function init($type)
  {
    if (empty($type)) {
        throw new \Exception('未指定构建器！');
    } else {
        $type = ucfirst(strtolower($type));
    }
    $builder = 'app\\common\builder\\' . $type;
    if (!class_exists($builder)) {
        throw new \Exception($type . '构建器不存在');
    }
    return new $builder;
  }
}