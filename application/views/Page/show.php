</head>
<body class="nothingBottom">
<!-- 顶部标签 -->
<header id="header">
    <?php $this->load->view('topnav');?>
</header>
<!-- 正文区 -->
<section class="bsbg wrap15">
    <?php echo getPagesContent($one)?>
</section>