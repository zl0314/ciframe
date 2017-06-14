<div class="path">
  <p>
	  <style>
		  .return {
			  font-size:13px;
			  font-weight:bold;
			  background:#108A58;
			  color:#fff;
			  border-radius:3px;
			  cursor:pointer;
			  display:inline-block;
			  height: 25px;
			  line-height: 25px;
			  margin: 5px 10px;
			  padding: 0 10px;

		  }
	  </style>
<!--<span style="float:right;font-size:13px;font-weight:bold;background:#108A58;color:#fff; border-radius:3px;cursor:pointer;display:inline-block; height: 25px; line-height: 25px; margin: 5px 10px;padding: 0 10px" onclick="gotoWhere()">返回</span>-->
当前位置：<?php echo @$menus[ucfirst($siteclass)]['topmenu']?><span> / </span><?php echo @$menus[ucfirst($siteclass)][$sitemethod]['name']?>
	 <?php if($siteclass != 'Admin'):?>
	   <span style="color:#fff;margin-left:100px;" class="return" onclick="gotoWhere()">返回</span>
	<?php endif;?>
  </p>
</div>
<script>
function gotoWhere(){
	var in_iframe = window.frameElement;
	// console.log(in_iframe);
	if(in_iframe){
		window.history.go(-1);
	}else{
//		window.location.href='<?php //echo site_url('admin')?>//';
	}
}

</script>
<div class="main-cont wrap-first">
<!--  <h3 class="title"> --><?php //echo @$menus[$siteclass][$sitemethod]['name']?><!--</h3>-->