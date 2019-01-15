<?php

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'aname' => 'max:10',
        'pwd' => 'require|length:4,12',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'aname.max' => '名称最多不能超过10个字符！！',
        'pwd.length' => '密码长度应该在4-12位之间！！',
    ];
}
