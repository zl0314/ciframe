<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
    <meta name="x5-fullscreen" content="true">
<meta http-equiv="x-rim-auto-match" content="none">
<meta name="full-screen" content="yes">
    <meta name="keywords" content="<?php echo $webset['keywords']?>" />
    <meta name="description" content="<?php echo $webset['description']?>" />
<meta name="Copyright" content="家门版权所有,未经允许,禁止转载,侵权必究" />
    <meta name="author" content="www.to-dream.com,拓之林" />
    <title> <?php echo !empty($webset['site_title']) ? $webset['site_title'] : ''; ?><?php echo !empty($pagetitle) ? '--'.$pagetitle : '';?></title>

<link href="/static/web/style/normalize.css" rel="stylesheet" type="text/css">
<link href="/static/web/style/global.css" rel="stylesheet" type="text/css">
<link href="/static/web/style/swiper-3.4.1.min.css" rel="stylesheet" type="text/css">
<link href="/static/web/style/layout.css" rel="stylesheet" type="text/css">
<link href="/static/web/style/style.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="/static/web/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/static/web/js/swiper-3.4.1.jquery.min.js"></script>

<script type="text/javascript" src="/static/web/js/layer.js"></script>
<script type="text/javascript" src="/static/web/js/global.js"></script>
<script type="text/javascript" src="/static/js/base64.js"></script>
<script type="text/javascript" src="/static/js/common.js"></script>
<script>
    var SITE_CLASS = '<?php echo $siteclass?>';
    var SITE_METHOD = '<?php echo $sitemethod?>';
    var SITE_URL = '<?php echo site_url('/')?>';
</script>