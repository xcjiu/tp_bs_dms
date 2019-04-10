<?php
namespace app\visitor\controller;
use think\Controller;
use app\visitor\logic\Fere as FereLogic;
use app\visitor\model\VisitorFere;

/**
* 体验数据
*/
class Fere extends Controller
{
  /**
  * 成品管理
   * @return [type] [description]
  */
  public function index()
  {
    $params = $this->request->param();
    if($this->request->isAjax() && $params){
      return FereLogic::fereRows($params);
    }
    return builder('datalist')
    ->title('成品管理')
    ->actionBtn('新增', 'visitor/fere/add')
    ->searchBtn()
    ->searchInput('name', '输入名字')
    ->searchSelect('status', '选择性别', [0=>'女', 1=>'男', 2=>'男女通吃'])
    ->searchSelect('style', '选择动力类型', [1=>'充气', 2=>'电动'])
    ->datePickers('', '生产日期')
    ->column('name', '名字')
    ->column('sex', '性别', [0=>'女', 1=>'男', 2=>'男女通吃'])
    ->column('nick', '昵称')
    ->column('status', '状态', [0=>'关闭', 1=>'启动'])
    ->column('style', '动力类型', [1=>'充气', 2=>'电动'])
    ->column('material', '原材料')
    ->column('create_time', '出厂日期')
    ->column('update_time', '维修日期')
    ->columnBtn('维修', 'visitor/fere/edit', 'btn-info')
    ->columnBtn(['column'=>'status', 'title'=>[1=>'关闭', 0=>'启动']], 'visitor/fere/forbidden', 'btn-warning')
    ->fetch();
  }

  /**
   * 成品新增
   */
  public function add()
  {
    $data = $this->request->post();
    if($data && $this->request->isAjax()){
      $result = FereLogic::add($data); 
      if( $result === true ){
        $this->success('新增成功！', 'index');
      }else{
        $this->error($result);
      }
    }
    return builder('form')
    ->input('name', '起个好听的名字（必须）')
    ->input('nick', '昵称', '', '请输入', 'text', false)
    ->input('material', '原材料', '', '请输入', 'text', false)
    ->select('sex', '性别', [0=>'女', 1=>'男', 2=>'男女通吃'], 0)
    ->select('style', '动力类型', [1=>'充气', 2=>'电动'], 1)
    ->select('status', '状态', [0=>'关闭', 1=>'启动'], 1)
    ->method('POST')
    ->fetch();
  }

  /**
   * 成品维修
   * @return [type] [description]
   */
  public function edit()
  {
    $id = $this->request->param('id', '');
    if(!$id){
      $this->error('缺少id参数');
    }
    $fere = VisitorFere::get($id);
    $data = $this->request->post();
    if($data){   
      $result = FereLogic::edit($fere, $data);
      if($result === true){
        $this->success('编辑成功！', 'index');
      }else{
        $this->error($result);
      }
    }
    return builder('form')
    ->input('name', '名字', $fere->name, '', 'text', false, true)
    ->input('nick', '昵称', $fere->nick, '请输入', 'text', false)
    ->input('material', '原材料', $fere->material, '请输入', 'text', false)
    ->input('id', '', $id, '', 'hidden', false)
    ->select('sex', '性别', [0=>'女', 1=>'男', 2=>'男女通吃'], $fere->sex)
    ->select('style', '动力类型', [1=>'充气', 2=>'电动'], $fere->style)
    ->select('status', '状态', [0=>'关闭', 1=>'启动'], $fere->status)
    ->method('POST')
    ->fetch();
  }

  /**
   * 状态开关
   */
  public function forbidden()
  { 
    $title = '';
    $id = $this->request->param('id', '');
    $fere = VisitorFere::get($id);
    if($fere){
      $title = $fere->status ? '关闭' : '启动';
      if($this->request->param('confirm')){
        $fere->status = $fere->status ? 0 : 1;
        $res = $fere->save();
        if($res){
          $this->success('操作成功');
        }else{
          $this->error('操作失败！');
        }
      }
    }
    return builder('confirm')
    ->title($title)
    ->id($id)
    ->fetch();
  }

  /**
   * 生产统计
   * @return [type] [description]
   */
  public function proTotal()
  {
    return builder('charts')
    ->theme('dark')   //light, dark, vintage, macarons, infographic, shine, roma
    ->title('生产统计')
    ->name('生产数据')
    ->type('pie')
    ->fetch();
  }

  public function charts()
  {
    $data = [];
    $fereSex = VisitorFere::field('count(*) as total, sex')->group('sex')->select();
    if($fereSex){
      $genders = [0=>'女', 1=>'男', 2=>'男女通吃'];
      foreach ($fereSex as $value) {
        array_push($data, ['name'=>$genders[$value['sex']], 'value'=>$value['total']]);
      }
      unset($fereSex);
    }
    $chartsPie = builder('charts')
    ->type('pie')
    ->title('性别比率')
    ->name('性别统计')
    ->unit('人')
    ->data($data)
    ->fetch();

    $data2 = [];
    $chartsBar = builder('charts')
    ->id('2')
    ->bg('')
    ->type('line')
    ->legendData(['one', 'two', 'three'])
    ->title('每天生产统计')
    ->name('统计数据')
    ->unit('人')
    ->data($data2)
    ->fetch();

    $chartsLine = '';
    $this->assign(['chartsPie'=>$chartsPie, 'chartsBar'=>$chartsBar, 'chartsLine'=>$chartsLine]);
    return $this->fetch();
  }


}