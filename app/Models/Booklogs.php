<?php
/**
 * Created by lazy make:model.
 */

namespace App\Models;


use Bootstrap\Core\LazyFile;
use Bootstrap\Core\LazyModel;

class Booklogs
{
    protected $table = 'lazybook_booklogs';
    protected $model = null;

    public function __construct()
    {
        $this->model || $this->model = LazyModel::getInstance();
        if(!$this->checkTable()){
            throw new \Exception("{$this->table} is not exists");
        }
    }

    public function checkTable()
    {
        return $this->model->query("SELECT table_name FROM information_schema.TABLES WHERE table_name ='{$this->table}'");
    }

    public function syncBooklog()
    {
        $path = STORAGE_PATH.DS.'files';
        $file = [];
        $time = time();
        LazyFile::searchDir($path, $file, IS_WIN);
        $file = array_map(function($item) use ($path, $time){
            return '('.$time.','."'".str_replace('\\', '/', substr($item, strlen($path)))."')";
        },$file);

        if($file){
            $sql = "INSERT IGNORE INTO {$this->table} (`created_at`,`url`) values ".implode(',', $file);
            return $this->model->exec($sql);
        }
    }

    public function getBooklog(array $fields, array $wheres)
    {
        if($fields && !in_array('*', $fields)){
            $field = " `".implode("`,`", $fields)."` ";
        }else{
            $field = ' * ';
        }
        if($wheres){
            $where = ' WHERE '. implode(' AND ', $wheres);
        }else{
            $where = '';
        }
        $sql = "SELECT {$field} FROM {$this->table} {$where}";
        return $this->model->query($sql, 2);
    }
}