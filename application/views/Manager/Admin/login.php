<!DOCTYPE html>
<html lang='zh-cn'>
<head>
<meta charset='utf-8'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<title><?php echo ADMIN_TITLE?></title>
<script type="text/javascript" src="/static/js/jquery.min.js"></script>
<link href="/static/web/style/layer.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/static/web/js/layer.js"></script>
<script src="/static/js/common.js"></script>
<style>
input, button, select, textarea {
	font-family:inherit;
	font-size:inherit;
	line-height:inherit
}
a {
	color:#03c;
	text-decoration:none;
	-webkit-transition:all .5s cubic-bezier(0.175, .885, .32, 1);
	transition:all .5s cubic-bezier(0.175, .885, .32, 1)
}
a:hover, a:focus {
	color:#1a53ff;
	text-decoration:none
}
a:focus {
	outline:thin dotted #333;
	outline:5px auto -webkit-focus-ring-color;
	outline-offset:-2px
}
a.disabled, a.disabled:hover, a.disabled:focus, a[disabled], a[disabled]:hover, a[disabled]:focus {
	color:#aaa;
	text-decoration:none;
	cursor:default
}
*, :before, :after {
	-webkit-box-sizing:border-box;
	-moz-box-sizing:border-box;
	box-sizing:border-box
}
.form-condensed .table-form td, .form-condensed .table-form th {
	padding:5px;
	font-size:12px;
}
.form-condensed .table-form {
	margin-bottom:0;
}
body {
	background: url(/static/admin/bgimg/dl_bg.jpg) no-repeat center top;
	background-size:cover;
}
.panel>.panel-heading {
	color:#333;
	background-color:#f5f5f5;
	border-color:#ddd
}
.panel>.panel-heading+.panel-collapse .panel-body {
	border-top-color:#ddd
}
.panel>.panel-footer+.panel-collapse .panel-body {
	border-bottom-color:#ddd
}
table {
	max-width:100%;
	background-color:transparent
}
th {
	text-align:left
}
.form-control:-moz-placeholder {
color:gray
}
.form-control::-moz-placeholder {
color:gray
}
.form-control:-ms-input-placeholder {
color:gray
}
.form-control::-webkit-input-placeholder {
color:gray
}
.form-control {
	display:block;
	width:100%;
	height:34px;
	padding:6px 10px;
	font-size:14px;
	line-height:1.42857143;
	color:#222;
	vertical-align:middle;
	background-color:#fff;
	border:1px solid #ccc;
	border-radius:2px;
	-webkit-box-shadow:inset 0 1px 1px rgba(0, 0, 0, .075);
	-moz-box-shadow:inset 0 1px 1px rgba(0, 0, 0, .075);
	box-shadow:inset 0 1px 1px rgba(0, 0, 0, .075);
	-webkit-transition:border-color ease-in-out .15s, box-shadow ease-in-out .15s;
	transition:border-color ease-in-out .15s, box-shadow ease-in-out .15s
}
.form-control:focus {
	border-color:#4d90fe;
	outline:0;
	-webkit-box-shadow:inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(77, 144, 254, .6);
	-moz-box-shadow:inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(77, 144, 254, .6);
	box-shadow:inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(77, 144, 254, .6)
}
.radio-inline, .checkbox-inline {
	display:inline-block;
	padding-left:20px;
	margin-bottom:0;
	font-weight:400;
	vertical-align:middle;
	cursor:pointer
}
.btn {
	display:inline-block;
	padding:6px 12px;
	margin-bottom:0;
	font-family:"Helvetica Neue", Helvetica, Tahoma, Arial, sans-serif;
	font-size:14px;
	font-weight:400;
	line-height:1.42857143;
	text-align:center;
	white-space:nowrap;
	vertical-align:middle;
	cursor:pointer;
	-webkit-user-select:none;
	-moz-user-select:none;
	-ms-user-select:none;
	-o-user-select:none;
	user-select:none;
	background-image:none;
	border:1px solid transparent;
	border-radius:2px;
	-webkit-box-shadow:inset 0 1px 0 rgba(255, 255, 255, .15), 0 1px 1px rgba(0, 0, 0, .075);
	-moz-box-shadow:inset 0 1px 0 rgba(255, 255, 255, .15), 0 1px 1px rgba(0, 0, 0, .075);
	box-shadow:inset 0 1px 0 rgba(255, 255, 255, .15), 0 1px 1px rgba(0, 0, 0, .075);
	-webkit-transition:all .8s cubic-bezier(0.175, .885, .32, 1);
	transition:all .8s cubic-bezier(0.175, .885, .32, 1)
}
.btn:focus {
	outline:thin dotted #333;
	outline:5px auto -webkit-focus-ring-color;
	outline-offset:-2px
}
.btn:hover, .btn:focus {
	color:#141414;
	text-decoration:none
}
.btn:active, .btn.active {
	background-image:none;
	outline:0;
	-webkit-box-shadow:inset 0 3px 5px rgba(0, 0, 0, .125);
	-moz-box-shadow:inset 0 3px 5px rgba(0, 0, 0, .125);
	box-shadow:inset 0 3px 5px rgba(0, 0, 0, .125)
}
#container {
	margin: 10% auto 0 auto
}
/*========登录===========*/

.login{width:560px; height:440px; position:absolute; left:50%; top:50%; margin:-220px 0 0 -280px;}
.dl_logo{height:80px; text-align:left;}
.dl_bottom{ width:430px; margin:0 auto; background:url(/static/admin/bgimg/dl_bg.png) repeat; padding:35px 0;}
.dl_bottom table{margin:0 auto; font-family:"微软雅黑"; font-size:14px; color:#fff;}
.dl_bottom table tr td.dl_bt{font-size:25px; font-weight:bold;}
.dl_wbk{width:282px; height:35px; background:url(/static/admin/bgimg/wbk_1.jpg) no-repeat left center; border:0; text-align:left; padding:0 10px; font-size:14px; color:#999999; font-family:"微软雅黑";}
.dl_but{width:78px; height:34px; background:url(/static/admin/bgimg/dl_but.jpg) no-repeat; border:0; cursor:pointer;}

</style>
</head>
<body>
<div id='container'>
	<div class="dl_bottom">
		<form method='post' action="" class='form-condensed' onsubmit="return checkForm()">
    	<table width="80%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="60" align="left" valign="middle" class="dl_bt">登录</td>
          </tr>
          <tr>
            <td height="50" align="left" valign="middle">
			<input class='form-control' type='text' value="" placeholder="用户名" name='username' id='username' class="dl_wbk" />
            </td>
          </tr>
          <tr>
            <td height="50" align="left" valign="middle">
				<input class='form-control' type='password' placeholder="密码" name='password' id="password" class="dl_wbk" />
            </td>
          </tr>
            <tr>
                <td height="50" align="left" valign="middle">
                    <input class='form-control' type='text' maxlength="4" placeholder="验证码" name='captcha' id="captcha" class="dl_wbk" style="width:80%;float:left" />
                    <img src="<?=site_url('Api/Captcha')?>" id="captcha" style="float:right" />
                </td>
            </tr>

          <tr>
            <td height="60" align="left" valign="middle">
            	<button type='submit' id='submit' class='dl_but'>&nbsp;</button>
            </td>
          </tr>
        </table>
        </form>
    </div>
</div>
<script>
    function checkForm(){
        var username = password = captcha = '';
        username = $('#username').val();
        password = $('#password').val();
        captcha = $('#captcha').val();
        if(username == ''){
            layer_tip_mini('用户名不能为空');
            return false;
        }
        if(password == ''){
            layer_tip_mini('密码不能为空');
            return false;
        }
        if(captcha == ''){
            layer_tip_mini('验证码不能为空');
            return false;
        }
        return true;
    }
$('#username').focus();
function _init() {
	
	if( window.parent.location.href != window.location.href) {
		window.parent.location.href = window.location.href;
	}
}
$(function(){
	_init();
})
</script>
</body>
</html>
