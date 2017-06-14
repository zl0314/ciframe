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
function checkPrivileges(a, b){	
	var c = document.getElementsByName("privileges["+a+"][]");
	var checkd = b=='on' ? true : false;
	for (var i=0; i<c.length; i++){
		c[i].checked = checkd;
	}
}

function checkALLPrivileges(b){	 
	<?php foreach($classmenu as $k => $r):?>
	checkPrivileges('<?php echo $r?>', b);
	<?php endforeach;?>
}
function checkALLPrivileges2(id, b){
	var checkd = b=='on' ? true : false;
	$('#'+id+'_p').find('input').attr('checked', checkd);
}
</script>
<link type="text/css" rel="stylesheet" href="/static/admin/admincp.css" media="screen" />

  <div class="set-area">
    
<form method="post" action="" >
<input type="hidden" name="user_id" value="<?php echo $vo['user_id'];?>" />
  <div class="table_div">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
      <tbody>
	  	 <tr class="tr1">
          <td width="22%" height="35" align="right"><p class="b">管理员ID：</p></td>
          <td colspan="2" align="left"><?php echo $vo['user_id'];?></td>
        </tr>
        <tr>
          <td height="35" align="right"><p class="b">管理员登录名：</p></td>
          <td colspan="2" align="left"><?php echo $vo['user_name'];?> (<?php echo $vo['nick_name'];?>)</td>
        </tr>
      </tbody>
    </table>
  </div>
  
  
   <div class="table_div">
    <div class="title" style="margin-bottom:10px;margin-top:15px;height:40px; font-weight:bold; font-size:15px;line-height:40px;">全局权限&nbsp;&nbsp;
   (<a href="javascript:;" onclick="checkALLPrivileges('on');">全选</a> / <a href="javascript:;" onclick="checkALLPrivileges('off');">反选</a>)</div>
    
   <?php foreach($right_menus as $mk => $mr):
       ?>

   <div class="title" style="margin-top:15px;height:40px; font-weight:bold; font-size:15px;line-height:40px; border:1px solid #d8dee3"><?php echo $mr['name']?>权限&nbsp;&nbsp;
   (<a href="javascript:;" onclick="checkALLPrivileges2('<?php echo $mr['id']?>','on');">全选</a> / <a href="javascript:;" onclick="checkALLPrivileges2('<?php echo $mr['id']?>','off');">反选</a>)
   </div>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" style="margin-top:-15px;border-top:none;" id="<?php echo $mr['id']?>_p">
      <tbody>
        <?php 
        $i = 0;
        foreach($mr['lists'] as $k => $v):?>
        <?php $i++?>
        <tr class="<?php if ($i%2==0):?>tr1<?php endif;?>">
          <td width="22%" height="35" align="right"><p class="b"><span style="color:green;"><?php echo $v['name']?></span> (<a href="javascript:;" onclick="checkPrivileges('<?php echo $k?>','on')">全选</a>/<a href="javascript:;" onclick="checkPrivileges('<?php echo $k?>','off')">反选</a>)：</p></td>
         <?php 
         $checkstr = '';
         $ap = !empty($admin_privileges[$k]) ? $admin_privileges[$k] : array();
         $ap = (isset($ap) && is_array($ap)) ? $ap : array();
         ?>
          <td colspan="2" align="left">
          <?php foreach($v['method'] as $methodk => $methodv):?>
          <?php 
          $checkstr = '';
          if(in_array($methodk, $ap)){
          	$checkstr = ' checked';
          }
          ?>
		  <input type="checkbox" name="privileges[<?php echo $k?>][]" value="<?php echo $methodk?>" <?php echo $checkstr?> /> <?php echo $methodv['name']?> &nbsp;
		  <?php endforeach;?>
		  </td>
        </tr>
		<?php endforeach;?>
		
      </tbody>
    </table>
    
    <?php endforeach;?>
	
  </div>
  
  
  <div class="table_div align_c">
    <input type="submit" id="SubmitBtn" value="确 定" class="button100" />
	<input type="button" value="返 回" class="button100" onclick="window.location.href='<?php echo site_url('Manager/adminuser')?>'" />
    <input type="hidden" name="formhash" value="<?php echo formhash()?>" />
    <input type="hidden" name="dosubmit" value="1" />
  </div>
</form>
  </div>
</div>