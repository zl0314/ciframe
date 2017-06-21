<script type="text/template" id="<?php echo $spicname?>_demo">
    <li class="imgbox post file" style="list-type:none;width:134px;height:185px;margin-left:5px;">
        <a class="item_close" href="javascript:void(0)" onclick="delscroll(this)" title="删除">
        </a>
        <span  class="item_box"style="width:121px;height:176px;">
<img src="" style="width:121px;height:174px;">
</span>
        <input type="hidden" name="data[<?php echo $spicname?>][]" class="">
    </li>
</script>

<!-- 轮播图 -->

<div class="form-row">
    <label class="form-field"><?php echo $spictitle;?>：</label>
    <div class="form-cont">
        <span class="help-inline"><button class="btn select_img" id="<?php echo $spicname?>"  type="button">&nbsp;</button></span><span class="help-inline" style="margin-left:35px;">推荐大小为宽<?php echo $spicwidth?>，高<?php echo $spicheight?></span>
    </div>
    <ul class="ipost-list ui-sortable" id="<?php echo $spicname?>_fileList"  style="width:1000px;height:100%;margin-left:90px;margin-top:15px;clear:both;">
        <?php
        $scrollpic = !empty($this->data['scrollpic']) ? $this->data['scrollpic'] : ( !empty($vo['scrollpic']) ? $vo['scrollpic'] : null);
        if (!empty($scrollpic)){
            $scrollpic = json_decode($scrollpic, 1);
            if(!empty($scrollpic))foreach($scrollpic as $r){
                ?>
                <li class="imgbox post file" style="list-type:none;height:185px;width:134px;margin-left:5px;">
                    <a class="item_close" href="javascript:void(0)" onclick="delscroll(this)" title="删除">
                    </a>
                    <span class="item_box" style="width:121px;height:176px;">
<img src="<?php echo $r?>"   style="width:121px;height:174px;">
</span>
                    <input type="hidden"  name="data[<?php echo $spicname?>][]" value="<?php echo $r?>" class="config_scroll">
                </li>
                <?php
            }
        }
        ?>
    </ul>
</div>
<!-- //轮播图 -->

<script>
    var SITE_URL = 'http://'+document.domain;
    var uploadify_id = '<?php echo $spicname?>';
</script>
<script type="text/javascript" src="/static/js/jquery_ui_custom.js"></script>
<link rel="stylesheet" type="text/css" href="/static/uploadify/uploadify.css">
<script type="text/javascript" src="/static/uploadify/jquery.uploadify.js"></script>
<link rel="stylesheet" type="text/css" href="/static/uploadify/uploadify_t.css" media="all" />

<script>

    function addoneing<?php echo $spicname?>(m,n){
        //n = n.split(',');
        //n = n[0];
        m = $.trim(m);
        $('#<?php echo $spicname?>_fileList').append($('#<?php echo $spicname?>_demo').html());
        var li = $('#<?php echo $spicname?>_fileList').children('li:last');
        li.find('img').attr('src',m);
        li.find("input").val(m);
    }

    function imgSelectorInit<?php echo $spicname?>() {
        $('#imgSelectorChoice').delegate('img', 'click', function () {
            var el = $(this);
            if (!el.hasClass('selected'))
                buildSelectedImg(el);
        }).delegate('img', 'mouseenter', function () {
            $(this).addClass('hovered');
        }).delegate('img', 'mouseleave', function () {
            $(this).removeClass('hovered');
        });
    }
    function ipostSortInit<?php echo $spicname?>() {
        $('.ipost-list').sortable({
            items: '> .post',
            containment: 'parent',
            appendTo: 'parent',
            tolerance: 'pointer',
            placeholder: 'holder',
            forceHelperSize: true,
            forcePlaceholderSize: true,
            axis: 'x',
            opacity: 0.8,
            cursor: 'ns-resize'
        });
    }

    <?php
    $this->config->load('upload_config');
    $config = $this->config->item('default');
    $max_size = $config['max_size'] ? $config['max_size'] : 2000;
    ?>
    imgSelectorInit<?php echo $spicname?>();
    ipostSortInit<?php echo $spicname?>();
    $('#<?php echo $spicname?>').click( function(){
        $('#<?php echo $spicname?>').uploadify({
            height        : 31,
            'fileSizeLimit' : '<?php echo ini_get('upload_max_filesize')?>B',
            fileSizeLimit	  : '<?php echo $max_size;?>KB',
            fileTypeExts : '*.gif; *.jpg; *.png; *.jpeg',
            swf           : SITE_URL+'/static/uploadify/uploadify.swf',
            uploader      : '<?php echo site_url('publicpicprocess/upload/scrollpic?filedata=Filedata&create_thumb='.$create_thumb.'&thumb_config='.$thumb_config)?>',
            width         : 98,
            multi         : true,
            buttonText	  : '<span class="uploadify-button-text"><i class="icon-plus-sign"></i></span>',
            buttonClass   : 'btn pl_add btn-primary',
            onUploadSuccess    : function (a, b, c, d, e){
                /*var newl = $('#ltarea').html();
                 $('#fileList').append(newl);
                 newl = $('#fileList').children('li:last');
                 newl.find('.thumb').attr('href',b).find('img').attr('src',b);
                 n = a.name;
                 n = n.split('.');
                 newl.find('.tittext').val(n[0]);
                 */
                addoneing<?php echo $spicname?>(b);
            },
        });
    });

    setTimeout(function(){
        $("#<?php echo $spicname?>").trigger('click');
    },2000)

    function delscroll(obj){
        if(confirm('确认要删除吗？')){
            var pic = $(obj).parent().find('img').attr('src');
            delpic(pic);
            $(obj).parent().remove();
        }
    }
</script>
<style>
    #<?php echo $spicname?> { width:98px;height:31px; background:#368EE0; background-image:url(/static/uploadify/upload_btn.png)}
    .help-inline{display:inline;float:left;}
    a.item_close{
        background-image: url("/static/uploadify/image_upload.png");
        background-position: -100px -182px;
        display: block;
        height: 14px;
        overflow: hidden;
        position: absolute;
        right: 6px;
        text-indent: -9999px;
        top: 6px;
        width: 14px;
    }
    a.item_close:hover{
        text-decoration:none;
        background-position: -100px -200px;
    }
</style>