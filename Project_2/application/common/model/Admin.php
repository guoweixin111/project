<?php

namespace app\common\model;

use think\Model;

class Admin extends Model
{
    //数据库表名
    protected $table = 'date_admin';

    //表主键
   	protected $pk = 'id';

   	//密码修改器
   	public function setPwdAttr($v)
   	{
   		return md5($v);
   	}
}
