<?php

namespace app\admin\validate;

use think\Validate;

class Config extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'webname' => 'max:15',
        'keyword' => 'max:25',

    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'webname.max' => '网站配置名称最多不能超过15个字符！！',
        'keyword.max' => '网站关键字最多不能超过25个字符！！',

    ];
}
