<?php

namespace app\common\model;

use think\Model;

class Config extends Model
{
    //数据库表名
    protected $table = 'webconfig';
    //主键
    protected $pk = 'id';
}
