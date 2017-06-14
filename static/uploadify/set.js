$(function(){
	$('[up="up"]').each(function(){
		var jo = $(this);
		var id = uuid();
		if($.trim($(this).find('button').attr('id'))==''){
			$(this).find('button').attr('id',id);
		}else{
			id = $(this).find('button').attr('id');
		}
		
		$('#'+id).uploadify({
	        height        : 20,
	        swf           : '/uploadify/uploadify.swf',
	        uploader      : '/uploadify.html',
	        width         : 70,
	        multi         : false ,
	        buttonText	  : '上传图片',
	        buttonClass   : 'btn',
	        onUploadSuccess    : function (a, b, c, d, e){   
	        	jo.find('img').attr('src',b);
	        	jo.find('input[type="hidden"]').val(b);
	        }
	    });
	});
	
	$('[cp="cp"]').each(function(){
		
		var id = uuid();
		$(this).after('<button class="btn" type="button" id="'+id+'">点击复制</button>');
		var txt = $.trim($(this).text());
		$('#'+id).zclip({
			path:'/uploadify/ZeroClipboard.swf',
			copy:txt
		});

	});
});
