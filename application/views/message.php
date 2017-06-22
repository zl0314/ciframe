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
            font-family:"Helvetica Neue", Helvetica, Tahoma, Arial, sans-serif;
            font-size:14px;
            line-height:1.42857143;
            color:#141414;
            background-color:#fff
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


        #login-panel .panel-foot {
            text-align: center;
            padding: 15px;
            line-height: 2em;
            background-color: #e5e5e5;
            border-top: 1px solid #dfdfdf
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