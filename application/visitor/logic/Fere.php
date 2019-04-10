<?php
namespace app\visitor\logic;
use app\visitor\model\VisitorFere;

/**
* 
*/
class Fere
{
  /**
   * 成品列表
   * @param  array $params  接收到的条件数据
   * @return [type]         [description]
   */
  public static function fereRows($params)
  {
    $condition = [];
    if(!empty($params)){
      $offset = $params['offset'] ?: 0;
      $limit = $params['limit'] ?: 15;
      unset($params['offset']);
      unset($params['limit']);
      if($params['start_time'] && $params['end_time']){
        $condition['create_time'] = ['between', [strtotime($params['start_time']), strtotime($params['end_time']) + 86399]];
        unset($params['start_time']);
        unset($params['end_time']);
      }
      if(isset($params['_'])){ unset($params['_']); }//这个参数是数据表插件自带的，这里不需要
      foreach ($params as $key => $value) {
        if($value != ''){
          $condition[$key] = $value;
        }
      }
    }
    if($condition){
      $rows = VisitorFere::where($condition)->limit($offset, $limit)->select();
      $total = VisitorFere::where($condition)->count();
    }else{
      $rows = VisitorFere::limit($offset, $limit)->select();
      $total = VisitorFere::count();
    }
    return ['total'=>$total,'rows'=>$rows]; 
  }

  /**
   * 成品新增
   * @param  array $data 接收到的数据
   */
  public static function add($data)
  {
    if(empty($data['name'])){
      return '名字必须！';
    }
    $res = VisitorFere::create($data);
    if($res){
      return true;
    }
    return '新增失败！';
  }

  /**
   * 成品维修
   * @param  object $fere 要修改的数据模型
   * @param  array $data  要更改的数据
   * @return [type]       [description]
   */
  public static function edit($fere, $data)
  {
    $data['status'] = (int)($data['status']);
    foreach ($data as $key => $value) {
      if($value != ''){
        $fere->$key = $value;
      }
    }
    if($fere->save()){
      return true;
    }
    return '编辑失败！';
  }




}