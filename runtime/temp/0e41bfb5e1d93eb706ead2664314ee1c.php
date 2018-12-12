<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"D:\phpstudy\WWW\Tp5\admin\tp_bs_dms\public/../application/common/view/builder/confirm.html";i:1544604022;}*/ ?>
<!--confirm-->
<div id="confirm-box">
  <h3 class="text-center text-danger">确定<?php echo $title; ?>吗？</h3>
  <br />
  <button class="btn btn-primary pull-left ml-5 pl-3 pr-3">取 消</button>
  <button class="btn btn-success pull-right mr-5 pl-3 pr-3">确 定</button>
</div>

<script>
  $('#confirm-box').children('.pull-left').click(function(){
    $('#action-modal').modal('hide');
  });
  $('#confirm-box').children('.pull-right').click(function(){
    $.ajax({
      url: domain + "<?php echo $confirmUrl; ?>",
      type: 'POST',
      data: {id:"<?php echo $id; ?>", confirm: true},
      success: function(result){
        if(result.code == 1){
          $('#action-modal').modal('hide');
          Alert(result.msg, 'alert-success');
          $('#data-table').bootstrapTable('refresh');
        }else{
          $('#action-modal').modal('hide');
          Alert(result.msg, 'alert-danger');
        }
      }
    });
  });
</script>