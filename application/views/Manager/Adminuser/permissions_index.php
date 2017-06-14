
<div class="path">
  <p>当前位置：管理员管理<span>&gt;</span>管理员列表</p>
</div>
<div class="main-cont">
  <h3 class="title"> 管理员列表</h3>
  <div class="set-area">
    <form method="post" id="form1" action="#">
      <table class="table table-s1" width="100%" cellpadding="0" cellspacing="0" border="0">
        <colgroup>
        <col class="w90">
       
        <col class="w160">
        <col class="w220">
        <col class="w170">
        <col class="">
        </colgroup>
        <thead class="tb-tit-bg">
          <tr>
            <th><div class="th-gap">ID</div></th>
           
            <th><div class="th-gap">名字</div></th>
            <th><div class="th-gap">真实姓名</div></th>
            <th><div class="th-gap">权限</div></th>
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
            <td><?php echo $v['id'];?></td>
            <td><?php echo $v['name'];?></td>
           
            <td><?php echo $v['action'];?></td>
            <td><?php echo $v['weight'];?></td>
            <td>
            	<a class="icon-add" title="添加" href="<?php echo site_url( "admin/permissions_add");?>?parent_id=<?php echo $v['id'];?>">添加</a> 
                
            </td>
          </tr>
          <?php foreach( $v['childs'] as $key => $val ):?>
          <tr>
          	<td>&nbsp;</td>
            
            <td><?php echo $val['id'];?>.<?php echo $val['name'];?></td>
            <td><?php echo $val['action'];?></td>
            <td><?php echo $val['weight'];?></td>
            <td>
            	<a class="icon-edit" title="添加" href="<?php echo site_url( "admin/permissions_add");?>?id=<?php echo $val['id'];?>">编辑</a> 
                
            </td>
          </tr>
          <?php endforeach;?>
          <?php endforeach;?>
          <?php else:?>
          <tr>
            <td colspan="6"><div class="no-data">没有数据</div></td>
          </tr>
          <?php endif;?>
        </tbody>
      </table>
    </form>
  </div>
</div>
