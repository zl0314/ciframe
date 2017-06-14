<form action="<?=site_url(MANAGER_PATH.'/'.$siteclass .'/create')?>" method="post" id="contentForm">

    <div class="form-row" id="">
        <label for="table" class="form-field">表名</label>
        <div class="form-cont">
            <input id="table" type="text" name="data[table]" required class="input-txt" value="" />
        </div>
        <span style="display:block;"></span>
    </div>

    <div class="form-row" id="">
        <label for="primary" class="form-field">主键ID</label>
        <div class="form-cont">
            <input id="primary" type="text" name="data[primary]" required class="input-txt" value="" />
        </div>
        <span style="display:block;"></span>
    </div>

    <div class="form-row" id="">
        <label for="description" class="form-field">文件描述</label>
        <div class="form-cont">
            <input id="description" type="text" required name="data[description]" class="input-txt" value="" />
        </div>
        <span style="display:block;"></span>
    </div>
    <input type="submit" value="保 存" class="input-btn" style="width:70px; height:25px;">
</form>