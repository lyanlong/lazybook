<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/25 0025
 * Time: 17:28
 */
header("content-type:text/html;charset=utf-8");
defined('ROOT') or define('ROOT', __DIR__);
defined('DB_FILE') or define('DB_FILE', __DIR__ . '/../db/test.db');
defined('ROOT_ACCOUNT') or define('ROOT_ACCOUNT', ['admin', 'root', 1, 'liyanlong']);

spl_autoload_register('autoload_class_file');
set_error_handler('auto_error');
set_exception_handler('auto_exception');

function auto_error($errno, $errstr, $errfile, $errline, $errcontext)
{
    if ($errno == E_WARNING) {
        throw new Exception(json_encode(func_get_args(), JSON_UNESCAPED_UNICODE));
    } elseif ($errno == E_USER_WARNING) {
        throw new Exception($errstr);
    }
}

function auto_exception(Throwable $e)
{
    if (!in_array($_SESSION['username'], ROOT_ACCOUNT)) {
        echo "systerm error";
    } else {
        if ($e instanceof Error) {
            echo "catch Error: " . $e->getCode() . '   ' . $e->getMessage() . '<br>';
        } else {
            echo "catch Exception: " . $e->getCode() . '   ' . $e->getMessage() . '<br>';
        }
    }
}


function autoload_class_file($class)
{
    $classfile = str_replace('\\', '/', __DIR__ . '/../' . $class . '.php');
    if (file_exists($classfile)) {
        require $classfile;
    } else {
        trigger_error("{$class} is not found", E_USER_ERROR);
    }
}

// ('test335', 123, 3453),('test335', 123, 3453)
function faker_test_data($num = 100)
{
    $data = '';
    for ($i = 0; $i < $num; $i++) {
        $name = 'test_' . mt_rand(1000, 9999);
        $time = time();
        $data .= "('{$name}', $time, $time),";
    }
    $data = rtrim($data, ',');
    return $data;
}

try {
    $_SESSION['username'] = 'liyanlong';
    $db = \test\Dbsqlite::connection(DB_FILE);

    //1. 建表
    $sql = "CREATE TABLE `test` (
      `id` INTEGER PRIMARY KEY AUTOINCREMENT,
      `name` varchar(50),
      `create_time` int(10),
      `update_time` int(10)
    )
";
    $c_table = $db->createTable($sql);
    var_dump($c_table);

    //2. 插入数据
    $test_name = 'test' . mt_rand(100, 999);
    $test_time = time();
    if ($db->add("insert into test(`name`, `create_time`, `update_time`) values " . faker_test_data(10))) {
//        print_r($db->lastInsertId());
    } else {
        print_r('insert fail' . json_encode($db->errorInfo(), JSON_UNESCAPED_UNICODE));
    }

//    3. 【示例3：更新数据】：
//    $res = $db->query("update test set name='up_test_8' where id > 3");
//    if($res){
//        echo '成功更新数据【'.$res->rowCount().'】条<br/>';
//    }

//    4. 【示例4：删除数据】：
//    $res = $db->exec("delete from test where id = 3");
//    if($res){
//        echo '删除数据成功<br/>';
//    }
//    $res = $db->query("delete from test where id > 3");
//    if($res){
//        echo '成功删除数据【'.$res->rowCount().'】条<br/>';
//    }

//    5. 查询数据
    $data = $db->getRows("select * from test");
    echo '<pre>';
    print_r($data);


    //6. 删表
//    $sql = "DROP TABLE `test`;";
//    var_dump($db->query($sql));

} catch (Exception $e) {
    print_r($e->getMessage());
}