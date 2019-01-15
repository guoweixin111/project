<?php

namespace app\admin\validate;

use think\Validate;

class Friend extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'linkname' => 'max:8',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'linkname.max' => '请输入8位以内的链接名！！',
    ];
}
