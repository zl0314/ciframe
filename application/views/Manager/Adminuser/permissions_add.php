<script>
$(function(){
  $('#skinlist li').click(function(){
    $('#skinlist li').removeClass('current');
    $(this).addClass('current');
    i = $(this).index();
    $('.form').hide();
    $('.form:eq('+i+')').show();
    });
});
</script>

<div class="path">
  <p>当前位置：权限管理<span>&gt;</span>添加权限</p>
</div>
<div class="main-cont">
  <h3 class="title">添加权限</h3>
  <div class="set-area">
    <form method="POST" action="">
      <input type="hidden" name="data[id]" value="<?php echo $vo['id'];?>" />
      <div class="form">
        <div class="form-row">
          <label for="username" class="form-field">名称</label>
          <div class="form-cont">
        
            <input id="username" type="text" name="data[name]" class="input-txt" value="<?php echo $vo['name'];?>" />
            
          </div>
        </div>
        <div class="form-row">
          <label for="password" class="form-field">动作</label>
          <div class="form-cont">
            <input id="text" type="text" name="data[action]" class="input-txt" value="<?php echo $vo['action'];?>" />
          </div>
        </div>
        <div class="form-row">
          <label for="password" class="form-field">父级</label>
          <div class="form-cont">
			<select name="data[parent_id]">
            	<option value="0">顶级</option>
                <?php foreach( $parents as $k => $v):?>
                <option <?php if( $vo['parent_id'] == $v['id']) echo 'selected="selected"';?> value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
                <?php endforeach;?>
            </select>
          </div>
        </div>
        <div class="form-row">
          <label for="nickname" class="form-field">权重</label>
          <div class="form-cont">
            <input id="nickname" type="text" name="data[weight]" class="input-txt" value="<?php echo $vo['weight'];?>" />
          </div>
        </div>
        
        <div class="btn-area">
          <input type="submit" style="width:70px; height:25px;">
        </div>
      </div>
    </form>
  </div>
</div>
