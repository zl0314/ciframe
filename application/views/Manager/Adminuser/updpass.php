

<div class="main-cont">

  <div class="set-area">
    <form method="POST" action="">
      <div class="form">
        <div class="form-row">
          <label for="name" class="form-field">用户名</label>
          <div class="form-cont">
            <p><?php echo $this->session->userdata('user_name');?></p>
          </div>
        </div>
        <div class="form-row">
          <label for="currpass" class="form-field">当前密码</label>
          <div class="form-cont">
            <input id="currpass" type="password" name="currpass" class="input-txt" value="" />
          </div>
        </div>
        <div class="form-row">
          <label for="newpass" class="form-field">新密码</label>
          <div class="form-cont">
            <input id="newpass" type="password" name="newpass" class="input-txt" value="" />
          </div>
        </div>
        <div class="form-row">
          <label for="repeatpass" class="form-field">重复新密码</label>
          <div class="form-cont">
            <input id="repeatpass" type="password" name="repeatpass" class="input-txt" value="" />
          </div>
        </div>
        <div class="btn-area">
          <input type="submit" style="width:70px; height:25px;">
        </div>
      </div>
    </form>
  </div>
</div>
