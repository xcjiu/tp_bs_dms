<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"D:\phpstudy\WWW\Tp5\admin\tp_bs_dms\public/../application/common/view/builder/datalist.html";i:1544606602;}*/ ?>
<!-- datepicker -->
<?php if($datePicker || $datePickers): ?>
<link rel="stylesheet" href="http://localhost/Tp5/admin/tp_bs_dms/public/static/datepicker/datepicker.css" crossorigin="anonymous">
<script src="http://localhost/Tp5/admin/tp_bs_dms/public/static/datepicker/moment.min.js" crossorigin="anonymous"></script>
<script src="http://localhost/Tp5/admin/tp_bs_dms/public/static/datepicker/datepicker.all.min.js" crossorigin="anonymous"></script>
<?php endif; ?>
<h5 class="table-list-title text-info">&nbsp;&nbsp;<?php echo $title; ?></h5>
<nav class="nav" id="toolbar">
  <!--操作按钮组-->
  <?php if(!(empty($actionBtn) || (($actionBtn instanceof \think\Collection || $actionBtn instanceof \think\Paginator ) && $actionBtn->isEmpty()))): ?>
  <ul class="nav nav-bar">
    <?php foreach($actionBtn as $btn): ?>
      <li class="nav-item"><button class="btn <?php echo $btn['color']; ?> btn-sm" data-toggle="modal" data-target="#action-modal" onclick="actionModal('<?php echo $btn['url']; ?>', '<?php echo $btn['title']; ?>', '<?php echo $btn['modalWidth']; ?>')"><?php echo $btn['title']; ?></button></li>&nbsp;&nbsp;
    <?php endforeach; ?>
  </ul>
  <?php endif; if($searchBtn): ?>
  &nbsp;&nbsp;
  <!--条件查询表单-->
  <form class="form-inline" id="search-form">
    <?php if(!(empty($searchInput) || (($searchInput instanceof \think\Collection || $searchInput instanceof \think\Paginator ) && $searchInput->isEmpty()))): foreach($searchInput as $input): ?>
      <div class="form-group">
        <input class="form-control form-control-sm" type="text" name="<?php echo $input['name']; ?>" placeholder="<?php echo $input['placeholder']; ?>" style="<?php echo $input['style']; ?>">
      </div>&nbsp;
      <?php endforeach; endif; if(!(empty($searchSelect) || (($searchSelect instanceof \think\Collection || $searchSelect instanceof \think\Paginator ) && $searchSelect->isEmpty()))): foreach($searchSelect as $option): ?>
      <div class="form-group">
        <select class="form-control form-control-sm" name="<?php echo $option['name']; ?>">
          <option value="" selected><?php echo $option['title']; ?></option>
          <?php foreach($option['dropdownData'] as $key => $op): ?>
          <option value=<?php echo $key; ?>><?php echo $op; ?></option>
          <?php endforeach; ?>
        </select>
      </div>&nbsp;
      <?php endforeach; endif; if(!(empty($datePicker) || (($datePicker instanceof \think\Collection || $datePicker instanceof \think\Paginator ) && $datePicker->isEmpty()))): ?>
    <?php echo $datePicker['title']; ?>&nbsp;
      <div class="c-datepicker-date-editor c-datepicker-single-editor J-datepicker-day mt10">
        <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
        <input type="text" autocomplete="off" name="<?php echo $datePicker['name']; ?>" placeholder="请选择日期" class="c-datepicker-data-input <?php echo $datePicker['format']=='YYYY-MM-DD'?'only-date':''; ?>" value="<?php echo $datePicker['default']; ?>">
      </div>
    <?php endif; if(!(empty($datePickers) || (($datePickers instanceof \think\Collection || $datePickers instanceof \think\Paginator ) && $datePickers->isEmpty()))): ?>
    <?php echo $datePickers['title']; ?>&nbsp;
      <div class="c-datepicker-date-editor J-datepicker-range-day mt10">
        <i class="c-datepicker-range__icon kxiconfont icon-clock"></i>
        <input placeholder="开始日期" name="<?php echo $datePickers['name']['start_name']; ?>" class="c-datepicker-data-input <?php echo $datePickers['format']=='YYYY-MM-DD'?'only-date':''; ?>" value="<?php echo $datePickers['default']['start_default']; ?>">
        <span class="c-datepicker-range-separator">至</span>
        <input placeholder="结束日期" name="<?php echo $datePickers['name']['end_name']; ?>" class="c-datepicker-data-input <?php echo $datePickers['format']=='YYYY-MM-DD'?'only-date':''; ?>" value="<?php echo $datePickers['default']['end_default']; ?>">
      </div>
    <?php endif; ?>
  </form>
  &nbsp;&nbsp;
  <!--搜索按钮-->
  <li class="nav-item" id="data-search"><button class="btn btn-info btn-sm"><i class="fa fa-search"></i></button></li>
  <?php endif; ?>
</nav>
<table id="data-table"></table>
<script>
$(function(){
  var primaryKey = "<?php echo $primaryKey; ?>"; //主键，默认是id,如果不是id字段则要在控制器中指定
  var columns = <?php echo json_encode($columns); ?>; //要显示的字段
  var checkbox = "<?php echo $checkbox; ?>";
  if(checkbox){columns[0].checkbox = true} //是否开启多选框
  columns.forEach(function(column, i){
    var formatter = column.formatter;
    if(typeof formatter === 'object'){
      column.formatter = function(value, row, index){ //字段值过滤器
        return formatter[value];
      }
    }
    if(formatter == 'datetime'){
      column.formatter = function(value, row, index){ //时间截转换器（YYYY-mm-dd HH:ii:ss）
        return moment(value*1000).format('YYYY-MM-DD HH:mm:ss');
      }
    }
    if(formatter == 'date'){
      column.formatter = function(value, row, index){ //时间截转换器（YYYY-mm-dd HH:ii:ss）
        return moment(value*1000).format('YYYY-MM-DD');
      }
    }
    columns[i] = column;
  });

  var columnBtn = <?php echo json_encode($columnBtn); ?> //数据操作项
  var columnBtnLen = columnBtn.length;
  if(columnBtnLen>0){ //添加操作项
    columns.push({
      field: '',
      title: '操作',
      align: 'center',
      formatter: function(value, row, index){
        var html = '';
        for(var i=0; i<columnBtnLen; i++){
          var url = columnBtn[i]["url"] + '?id=' + row[primaryKey];
          url = "'" + url + "'"; //引号多了放下面不好组装！
          var btnTitle = columnBtn[i]['title'];
          if(typeof columnBtn[i]['title'] === 'object'){ //根据字段值来动态展示按钮标题
            var column = columnBtn[i]['title']['column'];
            btnTitle = columnBtn[i]['title']['title'][row[column]];
          }
          html += '<button class="btn btn-sm '+columnBtn[i]['color']+' '+columnBtn[i]['DIYclass']+'" data-toggle="modal" data-target="#action-modal" onclick="actionModal(' + url + ",'" + btnTitle + "'" + ')" style="padding: .25rem .25rem;">'+btnTitle+'</button>&nbsp;';
        }
        return html;
      }
    })
  }

  /*加载数据*/
  $('#data-table').bootstrapTable({
    url: domain + "<?php echo $dataUrl; ?>",
    queryParams: function(params){ //提交条件参数
      return $('#search-form').serializeArray().concat([{name:'offset', value:params.offset}, {name:'limit', value:params.limit}]);
    },
    cache: false, //禁用AJAX数据缓存
    striped: true, //设置为隔行变色效果
    showToggle: true, //显示切换视图按钮
    undefinedText: '---', //当数据为undefined时显示的字符
    pagination: true, //显示分页条
    pageNumber: 1, //首页页码
    pageSize: 15, //页面数据条数
    pageList: [5, 10, 20, 50, 100], //设置分页下拉选项
    showRefresh: true, //显示刷新按钮
    showColumns: true, //显示字段列下拉框，可选择要显示的字段
    uniqueId: primaryKey, //对每一行指定唯一标识符
    paginationPreText: '上一页',
    paginationNextText: '下一页',
    //detailView: true, //是否显示单条数据的详细内容，有一个+号打开内容
    clickToSelect: true, //点击就选中，开启了选框的情况下
    checkboxHeader: true, //设置 false 将在列头隐藏全选复选框
    toolbar: '#toolbar', //自定义的工具条
    //buttonsToolbar: 'buttons-toolbar', //自定义的按钮工具条
    sidePagination:'server',
    queryParamsType : "limit",
    columns: columns,
    responseHandler: function(res){
      return {'total': res.total, 'rows': res.rows}
    }
  });

$('#data-search').click(function(){
  $('#data-table').bootstrapTable('refresh');
});

/*日期时间选择器*/
  function dateTool(type='single', format='YYYY-MM-DD', min='', max='', shortcut=true, shortcutOptions=[]){
    if(shortcutOptions.length<1){ 
      //默认快捷项，可以在控制器中自定义,注意单选与双选的不同
      shortcutOptions = type=='single' ? [{name: '今天',day: '0'},{name: '昨天',day: '-1'},{name: '三天前',day: '-3'},{name: '一周前',day: '-7'},{name: '30天前',day: '-30'}] : [{name: '今天',day: '0,0'},{name: '昨天',day: '-1,0'},{name: '三天前',day: '-3,0'},{name: '一周前',day: '-7,0'},{name: '30天前',day: '-30,0'}];
    }
    var dateObj = {
      hasShortcut: true, //显示快捷选择，对应shortcutOptions
      format: format, //日期时间格式
      shortcutOptions: shortcutOptions //快捷选择项
    };
    if(min != ''){ //最小日期时间
      dateObj.min = min;
    }
    if(max != ''){ //最大日期时间
      dateObj.max = max;
    }
    if(type == 'single'){ //单选择器
      $('.J-datepicker-day').datePicker(dateObj);//加载日期时间器
    }else{ //双选择器
      dateObj.isRange = true;
      $('.J-datepicker-range-day').datePicker(dateObj);
    }
  }

var datePickerObj = <?php echo json_encode($datePicker); ?>;
if('name' in datePickerObj){
  dateTool('single', datePickerObj.format, datePickerObj.min, datePickerObj.max, datePickerObj.shortcut, datePickerObj.shortcutOptions)
}
var datePickersObj = <?php echo json_encode($datePickers); ?>;
if('name' in datePickersObj){
  dateTool('two', datePickersObj.format, datePickersObj.min, datePickersObj.max, datePickersObj.shortcut, datePickersObj.shortcutOptions)
}

})

</script>