<?php
/**
 * Created by lazy make:model.
 */

namespace App\Models;


use Bootstrap\Core\LazyModel;

class Users
{
    protected $table = 'lazybook_users';
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

    public function hasOne(array $wheres)
    {
        if(empty($wheres)){
            return false;
        }
        $where = ' WHERE '. implode(' AND ', $wheres);
        $sql = "SELECT 1 FROM {$this->table} {$where}";
        return $this->model->hasOne($sql);
    }
    
    public function getOne(array $fields, array $wheres)
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