<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"D:\phpstudy\WWW\Tp5\admin\tp_bs_dms\public/../application/common/view/builder/form.html";i:1544600577;}*/ ?>
<!--form-->
<form <?php if(!empty($files)) echo 'enctype="multipart/form-data"'; ?> id="builder-form" style="font-size: 18px;">
  <?php foreach($inputs as $input): ?>
  <div class="form-group">
    <label class="text-primary pl-1"><?php echo $input['title']; ?></label>
    <input type="<?php echo $input['type']; ?>" class="form-control" name="<?php echo $input['name']; ?>" placeholder="<?php echo $input['placeholder']; ?>" value="<?php echo $input['default']; ?>" <?php if(!empty($input['required'])) echo 'required'; ?>>
  </div>
  <?php endforeach; foreach($radios as $radio): ?>
  <div class="form-group">
    <label class="text-primary pl-1"><?php echo $radio['title']; ?>：</label>
    <?php foreach($radio['options'] as $value => $text): ?>
    <div class="form-check form-check-inline">
      <label class="form-check-label">
        <input class="form-check-input" type="radio" name="<?php echo $radio['name']; ?>" value="<?php echo $value; ?>" <?php if($radio['default']==$value) echo 'checked'; ?>><?php echo $text; ?>
      </label>
    </div>
    <?php endforeach; endforeach; foreach($checkboxs as $checkbox): ?>
  <div class="form-group">
    <label class="text-primary pl-1"><?php echo $checkbox['title']; ?>：</label>
    <?php foreach($checkbox['options'] as $value => $text): ?>
    <div class="form-check">
      <label class="form-check-label">
      <input class="form-check-input" type="checkbox" value="<?php echo $value; ?>" name="<?php echo $checkbox['name']; ?>" <?php if($checkbox['default']==$value) echo 'checked'; ?>><?php echo $text; ?>
      </label>
    </div>
    <?php endforeach; ?>
  </div>
  </div>
  <?php endforeach; foreach($selects as $select): ?>
  <div class="form-group">
    <label class="text-primary pl-1"><?php echo $select['title']; ?></label>
    <select class="form-control" name="<?php echo $select['name']; ?>">
      <option value=""><?php echo $select['placeholder']; ?></option>
      <?php foreach($select['options'] as $value => $text): ?>
      <option value="<?php echo $value; ?>" <?php if($select['default']==$value) echo 'selected'; ?>><?php echo $text; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php endforeach; foreach($files as $file): ?>
  <div class="form-group">
    <label class="text-primary pl-1"><?php echo $file['title']; ?></label>
    <input type="file" class="form-control-file" name="<?php echo $file['name']; ?>" <?php if(!empty($file['required'])) echo 'required'; ?>>
  </div>
  <?php endforeach; foreach($textareas as $textarea): ?>
  <div class="form-group">
    <label class="text-primary pl-1"><?php echo $textarea['title']; ?></label>
    <textarea type="file" class="form-control-file" name="<?php echo $textarea['name']; ?>" <?php if(!empty($textarea['required'])) echo 'required'; ?>></textarea>
  </div>
  <?php endforeach; ?>

  <button type="submit" class="btn btn-primary btn-lg btn-block">提 交</button>
</form>

<script>
var method = "<?php echo $method; ?>";
var url = domain + "<?php echo $submitUrl; ?>";
$("#builder-form").submit(function(event){
  event.preventDefault(); //阻止表单提交
  var data = method=="GET" ? $("#builder-form").serialize() : $("#builder-form").serializeArray();
  $.ajax({
    url: url,
    type: method,
    data: data,
    success: function(result,status,xhr){
      console.log(data);
      if(result.code == 1){ 
        $('#action-modal').modal('hide');
        Alert(result.msg, 'alert-success');
        $('#data-table').bootstrapTable('refresh');
      }else{
        $('#action-modal').modal('hide');
        Alert(result.msg, 'alert-danger');
        return false;
      }
    }
  });
});
</script>