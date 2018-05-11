<?php
/**
 * Created by lazy make:model.
 */

namespace App\Models;

use Bootstrap\Core\LazyFile;
use Bootstrap\Core\LazyModel;
use Bootstrap\Core\LazySession;

class Menus
{
    protected $table = 'lazybook_menus';
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

    public function addChildMenu($pid, $name)
    {
        $model = LazyModel::getInstance();
        $maxId = $model->query("SELECT sortid  FROM {$this->table} ORDER BY sortid DESC LIMIT 1");
        if(0 == $pid){//add root menu
            $url = $name;
        }else{
            $purl = $model->query("SELECT url FROM {$this->table} WHERE id={$pid}");
            $url = $purl[0]['url'].'/'.$name;
        }

        $fields = [time(), time(), $name, $url, LazySession::getValue('email'), 1, $pid, $maxId[0]['sortid'] + 1, 0];
        $sql = "INSERT INTO {$this->table} (`created_at`,`updated_at`, `name`, `url`, `author`, `status`, `pid`, `sortid`, `type`) VALUES ('".implode("','", $fields)."')";
        if($model->exec($sql)){
            $dirname = STORAGE_PATH.DS.'files'.DS.$purl[0]['url'].'/'.$name;
            LazyFile::makedir($dirname, 0777);
            return true;
        }
        return false;
    }

    /**
     * @param int $pid
     * @return array 返回指定层级的所有节点菜单
     */
    public function getLevelMenu($pid = 0)
    {
        $sql = "SELECT * FROM {$this->table} WHERE `pid` = $pid";
        return LazyModel::getInstance()->query($sql);
    }

    /**
     * @return array 返回以无限分类的数据架构的所有层级的节点菜单
     */
    public function getMenuTree(array $where)
    {
        $where = ' WHERE ' . implode(' AND ', $where);
        $sql = "SELECT id,name,pid,url FROM {$this->table} {$where}";
        $data = LazyModel::getInstance()->query($sql);
        return array_values($this->_treeNode($data, 0));
    }

    //生成无限分类节点数据结构
    private function _treeNode($data, $parentId = 0)
    {
        // 用于保存整理好的分类节点
        $node = [];
        // 循环所有分类
        foreach ($data as $key => $value) {
            // 如果当前分类的父id等于要寻找的父id则写入$node数组,并寻找当前分类id下的所有子分类
            if ($parentId == $value ['pid']) {
                $node [$key] = $value;
                $node [$key] ['children'] = array_values($this->_treeNode($data, $value ['id']));
            }
        }
        return $node;
    }

}