<script>
function reloadpage(){
	setTimeout(function(){
		window.location.reload(true);
	},1000)
}
//列表批量排序
function listorder(order_field){
	$('#Form').attr('action', '<?php echo site_url('Manager/'.$siteclass.'/public_listorder')?>');
	$('#Form').submit();
}


//列表批量删除
function delitem(id){
	var ids = [];
	$('td').find('input[type="checkbox"]:checked').each(function(){
		ids[ids.length] = $(this).val();
	});
	
	if(id=='a' && ids.length > 0){
		if(ids.length <=0 ){
			alert('请选择删除项目');
			return;
		}
	
		if(confirm('确定删除这些信息吗？')){
			$('#Form').attr('action', '<?php echo site_url('Manager/'.$siteclass.'/delete')?>');
			$('#Form').submit();
		}
	}else if(id != 'a'){
		if(confirm('确定删除这些信息吗？')){
//			_goto('<?php //echo site_url('Manager/'.$siteclass.'/delete?id=')?>//'+id);
			$('#Form').attr('action', '<?php echo site_url('Manager/'.$siteclass.'/delete?id=')?>'+id);
			$('#Form').submit();
		}
	}else{
		alert('请选择删除信息');
	}
	
}

//全选操作
function selallck(o){
	if($(o).prop('checked')){
		$('td').find('input[type="checkbox"]').prop('checked',true);
	}else{
		$('td').find('input[type="checkbox"]').prop('checked',false);
	}
}
//删除图片
function delpic(pic){
	console.log(typeof(pic));
	if(typeof('pic') == 'object' && pic.length <= 0){
		return false;
	}
	if(pic == '' && typeof('pic') == 'string'){
		return false;
	}
	if(typeof(pic) == 'undefined'){
		return false;
	}
	$.post('<?php echo site_url('Manager/Publicpicprocess/delete')?>', { pic:pic }, function(data) {});
}
var allow = 1;
var allow_size = typeof(allow_size) == 'undefined' ? '<?php echo str_replace('M','', ini_get('upload_max_filesize'))*1024*1024?>' : allow_size;

function fileSelected(t){
	var oid = 'Filedata_e';
	if(typeof(t) != 'undefined'){
		oid = t;
	}
	var oFile = document.getElementById(t).files[0];
	console.log(oFile.size);
	if(parseInt(oFile.size) > allow_size ){
		allow = 0;
		var size = allow_size / 1024 / 1024;
		alert('大小不能超过'+( Math.round(size*10)/10)+'M');
		return false;
	}
	return true;
}

//ajax上传图片 id 输入框ID ，upload指定POST文件对象
function ajaxUpload ( id, upload ) {
  if(typeof('upload') == 'undefined'){
      upload = 'default';
  }
  if(typeof(size) == 'undefined'){
	  size = allow_size;
  }
  
new AjaxUpload($("#"+id+"_button"),{
		action: "<?php echo site_url('Manager/Publicpicprocess/upload');?>/"+upload,
		type:"POST",
		data:{ },
		autoSubmit:true,
		responseType:'html',//"json",
		name:upload,
		onChange: function(file, ext){
			var o = this._input;
			var oid = $(o).attr('id');
			if (!(ext && /^(jpg|jpeg|JPG|JPEG|PNG|gif|doc|pdf|docx)$/i.test(ext))) {
				alert('文件格式不正确');
				return false;
			}else{
				fileSelected(oid);
				if(allow == 1){
					$('#upload_img_tr').show();
					$('#uploading').show();
				}
				if(allow == 0){
					return false;
				}
			}
			return true;
		},
		onComplete: function(file, resp){
		  if( typeof(resp['error']) != 'undefined' ){
			console.log(resp);
		  }else{
			console.log(resp);
			if(resp.indexOf('uploads') < 0){
				alert(resp);
			}else{
				$('#'+id).val(resp);
				$('#preview_'+id).attr('src', resp);
				//alert('图片上传成功');
			}
			if(typeof(upload_callback) == 'function'){
				upload_callback(resp);
			}
		  }
		}
	});
}
$(".ajaxUploadBtn").trigger('click');


//ajax上传文件 id 输入框ID ，upload指定POST文件对象
function ajaxUploadFile ( id, upload ) {
  if(typeof('upload') == 'undefined'){
      upload = 'default';
  }
  new AjaxUpload($("#"+id+"_button"),{
    action: "<?php echo site_url('Manager/Publicpicprocess/uploadFile');?>/"+upload,
    type:"POST",
    data:{ },
    autoSubmit:true,
    responseType:'html',//"json",
    name:upload,
    onChange: function(file, ext){ },
    onComplete: function(file, resp){
      if( typeof(resp['error']) != 'undefined' ){
        console.log(resp);
      }else{
        console.log(resp);
        $('#'+id).val(resp);
        if(typeof(upload_callback) == 'function'){
        	upload_callback(resp);
        }
        alert('文件上传成功');
      }
    }
  });
}



$(function(){
	var tdl = $('table').find('thead').find('th').length;
	$('table').find('tfoot').find('td').attr('colspan', tdl);

	var no_data_td = $('table').find('tbody').find('td').length;
	if(no_data_td == 1){
		$('table').find('tbody').find('td').attr('colspan', tdl);
	}
})
</script>

<script>
var curpage = '<?php echo !empty($GLOBALS['curpage']) ? $GLOBALS['curpage'] : ''?>';
var perpage = '<?php echo !empty($GLOBALS['perpage']) ? $GLOBALS['perpage'] : ''?>';

$(function(){
	$('input[type="button"]').addClass('input-button');
	$('.form-cont').find('p').css({
		'color' : 'red',
		'float' : 'right',
		'margin-top' : '3px',
		'font-weight' : 'bold',
		'font-size' : '14px',
		'margin-left' : '15px'
	});
})


</script>


<style>
	.td-foot-bg a {
		margin:0 8px;
	}
</style>
</body>
</html>