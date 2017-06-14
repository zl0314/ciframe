<!DOCTYPE html>
<html lang='zh-cn'>
<head>
<meta charset='utf-8'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>消息提示</title>
<style>
body {
	margin:0
}
body {
	font-family:"Helvetica Neue", Helvetica, Tahoma, Arial, sans-serif;
	font-size:14px;
	line-height:1.42857143;
	color:#141414;
	background-color:#fff
}
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
	font-size:12px
}
.form-condensed .table-form {
	margin-bottom:0
}
body {
	background-color: #036
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
.table {
	width:100%;
	margin-bottom:20px
}
.table th, .table td {
	padding:8px;
	line-height:1.42857143;
	vertical-align:top;
	border-bottom:1px solid #ddd;
	-webkit-transition:all .3s cubic-bezier(0.175, .885, .32, 1);
	transition:all .3s cubic-bezier(0.175, .885, .32, 1)
}
.table>thead>tr>th {
	vertical-align:bottom;
	background-color:#f1f1f1;
	border-bottom:1px solid #ddd
}
.table-form>tbody>tr>th {
	text-align:right;
	vertical-align:middle;
	border-bottom:0
}
.table-form>tbody>tr>th.text-left, .table-form>tbody>tr>tr.text-left th {
	text-align:left;
	vertical-align:middle;
	border-bottom:0
}
.table-form>tbody>tr>td {
	vertical-align:middle;
	border-bottom:0
}
.table-form>tbody>tr>th, .table-form>tbody>tr>td {
	border-bottom:0
}
.table th.text-middle, .table td.text-middle, .text-middle {
	vertical-align:middle
}
.table-form>tbody>tr>td.text-bottom {
	vertical-align:bottom
}
.table-form>tbody>tr>td .form-control {
	vertical-align:middle;
	margin:0
}
.table-form>tbody>tr>td .checkbox, .table-form>tbody>tr>td input[type="radio"]+label {
	margin-right:10px
}
.table-form>tbody>tr>td>.row {
	margin-right:0
}
.table-form>tbody>tr>td>.row>[class*="col-"] {
padding-right:0;
line-height:34px
}
.table-form>tbody>tr>td .checkbox {
	margin:0
}
.table-form>tbody>tr>td>.radio:first-child {
	margin-top:0
}
.table-form>tbody>tr>td>.radio:last-child {
	margin-bottom:0
}
.table-form>tbody>tr>td .btn+.btn {
	margin-left:8px
}
.table-form>tbody>tr>td .btn-group .btn+.btn {
	margin-left:-1px
}
.table-form tr>td>.input-group {
	width:100%
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
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
	cursor:not-allowed;
	background-color:#e5e5e5
}
.radio input[type=radio], .radio-inline input[type=radio], .checkbox input[type=checkbox], .checkbox-inline input[type=checkbox] {
	float:left;
	margin-left:-20px
}
.radio-inline+.radio-inline, .checkbox-inline+.checkbox-inline {
	margin-top:0;
	margin-left:10px
}
input[type=radio][disabled], input[type=checkbox][disabled], .radio[disabled], .radio-inline[disabled], .checkbox[disabled], .checkbox-inline[disabled], fieldset[disabled] input[type=radio], fieldset[disabled] input[type=checkbox], fieldset[disabled] .radio, fieldset[disabled] .radio-inline, fieldset[disabled] .checkbox, fieldset[disabled] .checkbox-inline {
	cursor:not-allowed
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
.btn.disabled, .btn[disabled], fieldset[disabled] .btn {
	pointer-events:none;
	cursor:not-allowed;
	filter:alpha(opacity=65);
	-webkit-box-shadow:none;
	-moz-box-shadow:none;
	box-shadow:none;
	opacity:.65
}
.btn {
	color:#141414;
	text-shadow:0 1px 0 #fff;
	background-color:#f0f0f0;
	border-color:#d1d1d1;
	border-color:#ccc
}
.btn:hover, .btn:focus, .btn:active, .btn.active, .open .dropdown-toggle.btn {
	color:#141414;
	background-color:#dbdbdb;
	border-color:#9e9e9e;
	-webkit-box-shadow:0 2px 1px rgba(0, 0, 0, .1);
	-moz-box-shadow:0 2px 1px rgba(0, 0, 0, .1);
	box-shadow:0 2px 1px rgba(0, 0, 0, .1)
}
.btn:active, .btn.active, .open .dropdown-toggle.btn {
	background-image:none;
	-webkit-box-shadow:inset 0 1px 2px rgba(0, 0, 0, .1);
	-moz-box-shadow:inset 0 1px 2px rgba(0, 0, 0, .1);
	box-shadow:inset 0 1px 2px rgba(0, 0, 0, .1)
}
.btn.disabled, .btn[disabled], fieldset[disabled] .btn, .btn.disabled:hover, .btn[disabled]:hover, fieldset[disabled] .btn:hover, .btn.disabled:focus, .btn[disabled]:focus, fieldset[disabled] .btn:focus, .btn.disabled:active, .btn[disabled]:active, fieldset[disabled] .btn:active, .btn.disabled.active, .btn[disabled].active, fieldset[disabled] .btn.active {
	background-color:#f0f0f0;
	border-color:#bdbdbd
}
.btn-primary {
	color:#fff;
	text-shadow:0 -1px 0 rgba(0, 0, 0, .2);
	background-color:#1a4f85;
	border-color:#103152
}
.btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open .dropdown-toggle.btn-primary {
	color:#fff;
	background-color:#133b63;
	border-color:#0c243c;
	-webkit-box-shadow:0 2px 1px rgba(0, 0, 0, .1);
	-moz-box-shadow:0 2px 1px rgba(0, 0, 0, .1);
	box-shadow:0 2px 1px rgba(0, 0, 0, .1)
}
.btn-primary:active, .btn-primary.active, .open .dropdown-toggle.btn-primary {
	background-image:none;
	-webkit-box-shadow:inset 0 1px 2px rgba(0, 0, 0, .1);
	-moz-box-shadow:inset 0 1px 2px rgba(0, 0, 0, .1);
	box-shadow:inset 0 1px 2px rgba(0, 0, 0, .1)
}
.btn-primary.disabled, .btn-primary[disabled], fieldset[disabled] .btn-primary, .btn-primary.disabled:hover, .btn-primary[disabled]:hover, fieldset[disabled] .btn-primary:hover, .btn-primary.disabled:focus, .btn-primary[disabled]:focus, fieldset[disabled] .btn-primary:focus, .btn-primary.disabled:active, .btn-primary[disabled]:active, fieldset[disabled] .btn-primary:active, .btn-primary.disabled.active, .btn-primary[disabled].active, fieldset[disabled] .btn-primary.active {
	background-color:#1a4f85;
	border-color:#164270
}
#container {
	margin: 10% auto 0 auto
}
#login-panel {
	margin: 0 auto;
	
	width: 540px;
	
	min-height: 280px;
	background-color: #fff;
	border: 1px solid #dfdfdf;
	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;
	-moz-box-shadow:0px 0px 30px rgba(0, 0, 0, 0.75);
	-webkit-box-shadow:0px 0px 30px rgba(0, 0, 0, 0.75);
	box-shadow:0px 0px 30px rgba(0, 0, 0, 0.75)
}
#login-panel .panel-head {
	min-height: 70px;
	background-color: #edf3fe;
	border-bottom: 1px solid #dfdfdf;
	position: relative
}
#login-panel .panel-head h4 {
	margin: 0 0 0 20px;
	padding: 0;
	line-height: 70px;
	font-size: 14px
}
#login-panel .panel-actions {
	float: right;
	position: absolute;
	right: 15px;
	top: 18px;
	padding: 0
}
#login-panel .panel-actions .dropdown {
	display: inline-block;
	margin-right: 2px
}
#mobile {
	font-size: 28px;
	padding: 1px 12px;
	line-height: 28px
}
#mobile i {
	font-size: 28px;
}
#login-panel .panel-content {
	padding-left: 150px;
	min-height: 161px
}
#login-panel .panel-content table {
	border: none;
	width: 300px;
	margin: 20px auto;
}
#login-panel .panel-content .button-s {
	width: 80px
}
#login-panel .panel-content .button-c {
	width: 88px;
	margin-right: 0
}
#login-panel .panel-foot {
	text-align: center;
	padding: 15px;
	line-height: 2em;
	background-color: #e5e5e5;
	border-top: 1px solid #dfdfdf
}
#poweredby {
	float: none;
	color: #eee;
	text-align: center;
	margin: 10px auto
}
#poweredby a {
	color: #fff
}
#keeplogin label {
	font-weight: normal
}
.popover {
	max-width: 500px
}
.popover-content {
	padding: 0;
	width: 297px
}
.btn-submit {
	min-width: 70px
}
</style>
</head>
<body style="background:none">
<div id='container'>
  <div id='login-panel' style="min-height:200px;box-shadow:none;">
    <div class='panel-head'>
      <h4>信息提示</h4>
    </div>
    <div class="panel-content" id="login-form" style="text-align:center;margin:0;margin-left:0;padding-left:0;padding-top:20px;min-height:70px;">
      <div class="form-condensed" style="width:100%">
      <?php echo $err;?>
      </div>
    </div>
   
    <?php if ($url):?><div id='demoUsers' class="panel-foot"> <span><span id="sec_jump"><?php echo $sec/1000?></span>秒后浏览器自动跳转...</span> 
    
    <a href="<?php echo $url;?>">点击进行跳转</a>
    </div><?php endif?>
  </div>
</div>
 <script>
	var sec = '<?php echo $sec?>';
	var url = '<?php echo $url?>';
	<?php if($url):?>
		setTimeout('jump()', sec);
	<?php else:?>
<?php endif;?>
	function jump(){
		var itemsec = document.getElementById('sec_jump').innerHTML;
		if(itemsec > 1){
			document.getElementById('sec_jump').innerHTML = itemsec - 1;
			setTimeout('jump()', 1000);
		}else{
			if(url){
				window.location.href=url;
			}
		}
	}
    </script>
</body>
</html>

<?php exit;?>