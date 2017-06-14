  <div class="box">
    <div class="btn-group clear">
      <?php $i = 1; foreach( $sys_info as $k => $v ):?>
      <p><?php echo $i;?>、<?php echo lang('sys_info_'.$k);?>：<?php echo $v;?></p>
      <?php $i++ ; endforeach;?>
    </div>
  </div>
</div>

