<?php

namespace app\common\model;

use think\Model;
use app\common\model\Type;

class Goods extends Model
{
    //数据库表名
    protected $table = 'date_goods';
    //主键
    protected $pk = 'id';

    //多对一：belongsTo('关联模型类名','关联外键','关联主键'）方法
    public function type()
    {
    	return $this ->belongsTo('Type','type_id','id');
    }
}
