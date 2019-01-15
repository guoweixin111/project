<?php

namespace app\http\middleware;

class CheckAdmin
{
	use \traits\controller\Jump;
    public function handle($request, \Closure $next)
    {	
    	//判断session里面是否有数据
    	if (empty(session('loginAdmin'))) {
    		return $this ->error('请先去登录好嘛！！','/admin/login');
    	}
    	 	return $next($request);
    }
}
