<?php
/**
 * Author       : Aaron Zhang
 * createTime   : 2017/6/02 14:11
 * Description  : 自动创建控制器 |模型
 *
 */

//模板文件
if(!empty($argv['1'])){

    //文件名
    $filename = !empty( $argv[2] ) ? $argv[2] : '';
    //表名
    $table = !empty( $argv[3] ) ? $argv[3] : '';
    //主键
    $primary = !empty( $argv[4] ) ? $argv[4] : 'id';
    //文件描述
    $description = !empty( $argv[6] ) ? $argv[6] : 'File description here';
    //路径 位于 application文件夹下的路基与
    $path = !empty( $argv[5] ) ? $argv[5] : '';
    $source_path = $path;
    $path = !empty($path) ? $path . '_' : '';

    //模板文件
    $templage_file = 'create_template/' . $path . $argv[1];
    if($argv['1'] != 'help'){
        //获取模板文件
        if(!file_exists($templage_file) ){
            exit('template file dose not exists~');
        }else{
            $template = file_get_contents('create_template/' . $path . $argv[1]);
        }
    }
    //目标文件
    $target = '';

    //是否有添加时间字段
    $has_addtime = 'false';
    //排序字段
    $listorder_field = 'listorder';

    $table_data = array();

    if($argv['1'] == 'help'){
        echo 'USAGE : php create.php [controller|model] filename table[It will valid when param1=controller ] primary[Same as param table] path description ';
    }else if( $argv[1] == 'controller'){

        if(empty($filename)){
            exit('filename can not be empty');
        }
        if(empty($table)){
            exit('table can not be empty');
        }
        if(empty($primary)){
            exit('primary can not be empty');
        }

        $source_path = empty($source_path) ? '' : $source_path . '/';
        $target = 'application/controllers/' . $source_path . ucwords($filename) . '.php';
        if(file_exists($target)){
            exit('file exists ' . $target);
        }

        //连接Mysql
        define('DB','test');
        define('TB',$table);

        $conn = mysqli_connect("localhost","root","zlflrhl");
        mysqli_select_db($conn, DB);
        mysqli_query($conn, "SET NAMES UTF8");

        $query = mysqli_query($conn, 'show FIELDS from ' . TB);

        while($fields = mysqli_fetch_assoc($query)){
            $data[$fields['Field']] = array(
                'field' => ucwords($fields['Field']),
            );
            //主键
            if($fields['Key'] == 'PRI'){
                $data[$fields['Field']]['is_primary'] = true;
            }
            //必须填写
            if($fields['Null'] == 'NO' && $fields['Key'] != 'PRI'){
                $data[$fields['Field']]['is_require'] = true;
            }
            //添加时间
            if($fields['Field'] == 'addtime'){
                $has_addtime = 'true';
            }
            //排序字段
            if(strpos($fields['Field'], 'listorder')){
                $listorder_field = $fields['Field'];
            }

            //文本框类型判断
            $type = 'text';
            if($fields['Key'] == 'PRI'){
                $type = 'hidden';
            }else if($fields['Type'] == 'datetime'){
                $type = 'time';
            }else if(strrpos($fields['Field'], 'pic') !== false || strrpos($fields['Field'], 'thumb') !== false){
                $type = 'image';
            }else if( $fields['Type'] == 'text' ){
                $type = 'textarea';
            }
            //类型
            $data[$fields['Field']]['type'] = $type;

            //是否是编辑器
            if($fields['Type'] == 'text'){
                $data[$fields['Field']]['editor'] = true;
            }
            $table_data = var_export($data, true);
        }

    }else if($argv['1'] == 'model'){
        $target = 'application/models/'. ucwords($filename) . '_model.php';
    }

   if($target && $template){
       //替换内容
       $file_content = str_replace(array(
           '{class}',
           '{table}',
           '{primary}',
           '{addtime}',
           '{table_data}',
           '{listorder_field}',
           '{time}',
           '{description}'
       ), array(
           ucwords($filename),
           $table,
           $primary,
           $has_addtime,
           $table_data,
           $listorder_field,
           date('Y-m-d H:i:s'),
           $description
       ), $template);

       //判断是否写入文件
       if(!file_exists($target)){
           $res = file_put_contents($target, $file_content);
           exit('file create success '.$target);
       }else{
           echo 'file exists';
       }
   }

}

