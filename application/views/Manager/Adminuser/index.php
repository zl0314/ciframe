
  <div class="set-area">
    <form method="post" id="form1" action="#">
      <table class="table table-s1" width="100%" cellpadding="0" cellspacing="0" border="0">
        <colgroup>
        </colgroup>
        <thead class="tb-tit-bg">
          <tr>
            <th><div class="th-gap">ID</div></th>
            <th><div class="th-gap">用户名</div></th>
            <th><div class="th-gap">真实姓名</div></th>
            
            <th><div class="th-gap">添加</div></th>
            <th><div class="th-gap">操作</div></th>
          </tr>
        </thead>
        <tfoot class="td-foot-bg">
          <tr>
            <td colspan="5"><div class="pre-next"> <?php echo $page_html;?></div></td>
          </tr>
        </tfoot>
        <tbody>
        <?php if( $list):?>
          <?php foreach( $list as $k => $v ):?>
          <tr>
            <td><?php echo $v['user_id'];?></td>
            <td><?php echo $v['user_name'];?></td>
            <td><?php echo $v['nick_name'];?></td>
           
            <td><?php echo $v['addtime'];?></td>
            <td>
              <a class="icon-edit" title="编辑" href="<?php echo site_url(sprintf('Manager/'.$siteclass."/add/%s" , $v['user_id']));?>">编辑</a>

              <?php if( $v['user_name'] != 'admin'):?>
            	<a class="icon-edit" title="权限" href="<?php echo site_url(sprintf('Manager/'.$siteclass."/right/%s" , $v['user_id']));?>">权限</a>
                

            	<a class="icon-del" onclick="if( !confirm('您确定要删除？')){ return false;}"  title="删除" href="<?php echo site_url( sprintf('Manager/'.$siteclass."/del/%s" , $v['user_id']));?>">删除</a>
                
                <?php endif;?>
                </td>
          </tr>
          <?php endforeach;?>
       	
		<?php else:?>
        <tr>
            <td colspan="5"><div class="no-data">没有数据</div></td>
          </tr>
		<?php endif;?>
          
          
        </tbody>
      </table>
    </form>
  </div>
</div>
