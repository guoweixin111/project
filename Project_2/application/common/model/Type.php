<?php

namespace app\common\model;

use think\Model;

class Type extends Model
{
    //数据库表名
    protected $table = 'date_type';
    //主键
    protected $pk = 'id';

    //get获取器
    public function getTypenameAttr()
    {
    	//$this是我们当前正在遍历的数据
    	$n = substr_count($this->path,',')-1;
		$space = str_repeat('--',$n*4);
		$name = $space.$this['name'];
		return $name;
    }
}
