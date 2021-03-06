<?php
/**
 * Author       : Aaron Zhang
 * createTime   : 2017/6/02 14:11
 * Description  : 自动创建控制器 |模型
 *
 * @usage
 * php create.php controller(自动创建控制器) Power(文件名) 以下参数可选 [ power(表名) id(主键)]
 */

//定义数据库表前缘
define('PB','ci_');

//模板文件
if(!empty($argv['1'])){
    //读取数据库配置文件,得到数据库连接信息
    $config = file_get_contents('application/config/database.php');
    $array = explode(PHP_EOL, $config);
    $db_host = $db_user_name = $db_password = $database = '';
    $pattern = '/\'(hostname|username|password|database)\'\s=>\s\'(.*)\',/';
    $res = preg_match_all($pattern, $config, $m);

    if(!empty($m[2])){
        $db_host        = $m[2][0];
        $db_user_name   = $m[2][1];
        $db_password    = $m[2][2];
        $database       = $m[2][3];
    }
    //文件名
    $filename = !empty( $argv[2] ) ? $argv[2] : '';
    //表名
    $table = !empty( $argv[3] ) ? $argv[3] : '';
    //主键
    $primary = !empty( $argv[4] ) ? $argv[4] : 'id';

    $description = 'File description here';

    //模板文件
    $templage_file = 'create_template/' . $argv[1];

    if($argv['1'] != 'help'){
        //获取模板文件
        if(!file_exists($templage_file) ){
            exit('template file dose not exists~');
        }else{
            $template = file_get_contents('create_template/' . $argv[1]);
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
        echo 'USAGE : php create.php [controller|model|Manager_controller] filename table[It will valid when param1=controller ] primary[Same as param table] ';
    }else if( $argv[1] == 'Manager_controller'){

        if(empty($filename)){
            exit('filename can not be empty');
        }
        if(empty($table)){
            $table = strtolower($filename);
        }
        if(empty($primary)){
            $primary = 'id';
        }

        $source_path = empty($source_path) ? '' : $source_path . '/';
        $target = 'application/controllers/Manager/' . ucfirst($filename) . '.php';
        if(file_exists($target)){
            exit('file exists ' . $target);
        }

        //连接Mysql
        define('DB',$database);
        define('TB', PB . $table);

        $conn = mysqli_connect($db_host,$db_user_name,$db_password);
        mysqli_select_db($conn, $database);
        mysqli_query($conn, "SET NAMES UTF8");
        $query = mysqli_query($conn, 'show FIELDS from ' . TB) or die(mysqli_error($conn));

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
            if($fields['Type'] == 'text' || $fields['Field'] == 'content'){
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


