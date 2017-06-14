<!-- 下面独有，上面公共 -->
<link rel="stylesheet" type="text/css" href="/static/web/style/loginMine.css">
</head>
<body class="nothingBottom">
    <!-- 顶部标签 -->
    <header id="header">
        <?php $this->load->view('topnav');?>
    </header>
    <article>
        <!-- 列表 -->
        <section class="connectUs">
            <ul>
                <li>
                    <a href="javaScript:;">
                        <span class="first" >公司名称</span>
                        <span class="second"><?php echo $webset['corporate_name'];?></span>
                    </a>
                </li>
                <li>
                    <a href="javaScript:;">
                        <span class="first">公司地址</span>
                        <span class="second"><?php echo $webset['address'];?></span>
                    </a>
                </li>
                <li>
                    <a href="javaScript:;">
                        <span class="first">公司网址</span>
                        <span class="second"><?php echo $webset['company_address'];?></span>
                    </a>
                </li>
                <li>
                    <a href="javaScript:;">
                        <span class="first">联系电话</span>
                        <span class="second"><?php echo $webset['service_line'];?></span>
                    </a>
                </li>
            </ul>
        </section>
    </article>
    <script type="text/javascript">
    </script>
