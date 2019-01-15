<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\User;
use app\common\model\Admin;
use think\captcha\Captcha;

class LoginController extends Controller
{   

    /**
     * 显示后台登录页面
     *
     * @return \think\Response
     */
    public function login()
    {   
        $date = Admin::find();
        return view('login/login',['date'=>$date]);
    }

    /**
     * 执行后台登录的方法
     *
     * @return \think\Response
     */
    public function dologin(Request $request)
    {   
        $data = $request ->post();
        $code = $data['code'];
        // dump($data);
        // die;
        // $date = Admin::find();
        // // dump($date);die;
        // if ($date['level']<3) {
        //     return $this ->error('非超级管理员，无权登录..','/admin/login');
        // }

        $captcha = new Captcha();
        if( !$captcha->check($code))
        {
            // 验证失败
            return $this ->error('验证码错误！！','/admin/login');
        }
        
        $name = $data['aname'];
        $pwd = $request ->post('pwd','null','md5');
        $res =  Admin::where('aname','=',$name)->where('pwd','=',$pwd)->find();
        if (empty($res)) {
            return $this ->error('密码不正确！！','/admin/login');
        }
            //保存一个数据用来验证管理员是否登录
            session('loginAdmin',true);
            //保存登录的管理员信息
            session('admin',$res);
            return $this ->success('登录成功，正在加载首页！！','/admin/index');
    }

    /**
     * 退出登录.
     *
     * @return \think\Response
     */
    public function login_out()
    {
        session('loginAdmin',false);
        return $this ->error('正在退出中,请稍等...','/admin/login');
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        return view('default/index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

    /**
     * 显示验证码的方法
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function code()
    {   
        $config = [
        // 验证码字体大小
        'fontSize' => 18,
        // 验证码位数
        'length' => 4,
        //验证码图片高度
        'imageH' => 41,
        //验证码图片宽度
        'imageW' => 150,
        //是否画混淆曲
        'useCurve' => false, 
        // 关闭验证码杂点
        'useNoise' => false,
        // 背景颜色
        'bg' => [255, 218, 185],
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }
}
