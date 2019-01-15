<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\common\model\Login;
use app\common\model\User;
use think\captcha\Captcha;

class LoginController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function login()
    {
        $date = User::find();
        return view('login/login',['date'=>$date]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
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
            return $this ->error('验证码错误！！','/home/login');
        }
        
        $name = $data['name'];
        $pwd = $request ->post('pwd','null','md5');
        $res =  User::where('name','=',$name)->where('pwd','=',$pwd)->find();
        if (empty($res)) {
            return $this ->error('密码不正确！！','/home/login');
        }
            //保存一个数据用来验证管理员是否登录
            session('loginHome',true);
            //保存登录的管理员信息
            session('home',$res);
            return $this ->redirect('/home/index');
    }

    public function login_out()
    {
        session('loginHome',false);
        // Session::delete('home','name');
        
        return $this ->redirect('/home/index');
    }

    /**
     * 加载用户注册的页面
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function register()
    {
        return view('/register/register');
    }

    public function doreister(Request $request)
    {
        $data = $request ->post();
        $code = $data['code'];
        $captcha = new Captcha();
        if( !$captcha->check($code))
        {
            // 验证失败
            return $this ->error('验证码错误！！','/home/register');
        }
        
        // $name = $data['name'];
        // $pwd = $request ->post('pwd','null','md5');
        // $res =  User::where('name','=',$name)->where('pwd','=',$pwd)->find();
        // if (empty($res)) {
        //     return $this ->error('密码不正确！！','/home/register');
        // }
        //判断密码是否为空
        if ($data['pwd'] == '') {
            return $this -> error('初始密码不能为空！！','/home/register');
        }
        //判断确认密码是否为空
        if ($data['repwd'] == '') {
            return $this -> error('确认密码不能为空！！','/home/register');
        }
        //判断两次密码是否一致
        if ($data['repwd'] != $data['pwd']) {
            return $this -> error('两次密码不一致，请重新输入！！','/home/register');
        }
        try {

            User::create($data,true);    //true 过滤字段

        } catch (Exception $e) {

            return $this -> error('注册用户失败！','/home/register');

        }
            //保存一个数据用来验证管理员是否登录
            session('loginHome',true);
            //保存登录的管理员信息
            session('home',$res);
            return $this -> redirect('添加用户'.$data['name'].'成功！','/home/index');
            
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

    //登录时检测用户是否存在
    public function search_name()
    {
        $name = $_GET['name'];
        // dump($name);
        $data = User::where('name','=',$name)->find();
        // dump($data);
        if (empty($data)) {
            return json_encode(['status' => 400]);
        }
            return json_encode(['status' => 200]);

    }
    
    //注册用户时检测用户是否存在
    public function register_name()
    {
        $name = $_GET['name'];
        $data = User::where('name','=',$name)->find();
        // dump($data);
        // die;
        if (empty($data)) {
            return json_encode(['status' => 400]);
        }
            return json_encode(['status' => 200]);

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
