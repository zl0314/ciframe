
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

  <div class="set-area">
    <form method="POST" action="">
      <input type="hidden" name="data[user_id]" value="<?php echo $vo['user_id'];?>" />
      <div class="form">
        <div class="form-row">
          <label for="username" class="form-field">登录账号</label>
          <div class="form-cont">
            <?php if( !empty($vo['user_id'])):?>
            <?php echo $vo['user_name'];?>
            <?php else:?>
            <input id="user_name" type="text" name="data[user_name]" class="input-txt" value="<?php echo $vo['user_name'];?>" />
            <?php endif;?>
          </div>
        </div>
        <div class="form-row">
          <label for="password" class="form-field">登录密码</label>
          <div class="form-cont">
            <input id="password" type="password" name="data[password]" class="input-txt" value="" />
          </div>
        </div>
		<div class="form-row">
          <label for="password" class="form-field">重复登录密码</label>
          <div class="form-cont">
            <input id="password2" type="password" name="data[password2]" class="input-txt" value="" />
          </div>
        </div>
        <div class="form-row">
          <label for="nickname" class="form-field">真实姓名</label>
          <div class="form-cont">
            <input id="nick_name" type="text" name="data[nick_name]" class="input-txt" value="<?php echo $vo['nick_name'];?>" />
          </div>
        </div>

        <input type="hidden" value="<?php echo $vo['is_root'];?>" name="data[is_root]" />
        <div class="btn-area">
          <input type="submit" style="width:70px; height:25px;">
        </div>
      </div>
    </form>
  </div>
</div>
