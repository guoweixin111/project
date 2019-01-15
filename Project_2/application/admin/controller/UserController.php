<?php

namespace app\admin\controller;


use think\Controller;
use think\Request;
use app\common\model\Admin;
use app\common\model\User;

class UserController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        return view('User/index');
    }

    /**
     * 显示添加用户的页面.
     *
     * @return \think\Response
     */
     public function useradd()
    {
        return view('User/useradd');
    } 

    /**
     * 显示用户列表的页面.
     *
     * @return \think\Response
     */
     public function userlist()
    {   
        //用户搜索
        //按照用户名搜索
        $search = [];
        if (!empty($_GET['name'])) {
            $search[] = ['name','like',"%{$_GET['name']}%"];
        }
        //按照用户等级搜索
        if (!empty($_GET['level'])) {
            $search[] = ['level','=',"{$_GET['level']}"];
        }
        $search[] = ['status','=','1'];
        // $date = User::select();
        $data = User::where($search)->paginate(4)->appends($_GET);
        // dump($date);
        // die;
        return view('User/userlist',['data'=>$data]);
    } 

    /**
     * 执行添加用户的方法
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
       $data = $request -> post();
       //验证器
       $validate = new \app\admin\validate\User;
       if (!$validate->check($data)) {
            return $this ->error($validate->getError());
        }

       //判断密码是否为空
        if ($data['pwd'] == '') {
            return $this -> error('初始密码不能为空！！','/admin/user_add');
        }
        //判断确认密码是否为空
        if ($data['repwd'] == '') {
            return $this -> error('确认密码不能为空！！','/admin/user_add');
        }
        //判断两次密码是否一致
        if ($data['repwd'] != $data['pwd']) {
            return $this -> error('两次密码不一致，请重新输入！！','/admin/user_add');
        }
        try {

            User::create($data,true);    //true 过滤字段

        } catch (Exception $e) {

            return $this -> error('添加用户失败！','/admin/user_add');

        }
            return $this -> success('添加用户'.$data['name'].'成功！','/admin/user_list');
    }

    /**
     * 显示编辑用户资料页面.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {   
        //获取单条数据
        $data = User::find($id);
        // dump($data);die;
        return view('user/useredit',['data'=>$data]);
    }

    /**
     * 显示编辑用户密码页面.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function pwd($id)
    {   
        //获取单条数据
        $data = User::find($id);
        // dump($data['pwd']);die;
        return view('user/userpwd',['data'=>$data]);
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
        $data = $request->post();
        //验证器
        $validate = new \app\admin\validate\User;
        if (!$validate->check($data)) {
            return $this ->error($validate->getError());
        }
        try {
             User::update($data,['id'=>$id]);
        } catch (Exception $e) {
            return $this->error('修改用户信息失败！','/admin/user_edit');
        }
            return $this->success('修改用户信息成功！','/admin/user_list');
    }

    /**
     * 修改用户密码
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function updatepwd(Request $request, $id)
    {
        $date = User::find($id);

        $data = $request->post();
        // dump($data);
        // die;
       
        //判断原密码是否输入正确
        if ($date['pwd'] != md5($_POST['oldpwd'])) {
            return $this -> error('原密码输入错误！！');
        }
         // dump($data['pwd']);
        // dump(md5($_POST['oldpwd']));
        // die;
        //判断新密码是否为空
        if ($data['pwd'] == '') {
            return $this -> error('新密码输入不能为空！！');
        }
        //判断新密码与旧密码不能相同
        if ($date['pwd'] == md5($_POST['pwd'])) {
            return $this -> error('新密码与原密码不能相同！！');
        }
        try {
             User::update($data,['id'=>$id]);
        } catch (Exception $e) {
            return $this->error('修改用户密码失败！','/admin/user_pwd');
        }
            return $this->success('修改用户密码成功！','/admin/user_list');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        // dump($id);
        $data = User::destroy($id);

        if ($data) {
            return $this -> success('删除用户成功！','/admin/user_list');
        }
        return $this -> error('删除用户失败！','/admin/user_list');
    }

    /**
     * 修改用户禁用启用状态的方法
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function jq(Request $request, $id)
    {
        $enable = User::find($id);
        // $enable = $request->post();

       // dump($enable);
       // die;
       $status = [];
       if ($enable['status'] == 0) {
            $status['status'] = 1;
                $s = "启用";
       }else{
            $status['status'] = 0;
                $s = "禁用";
       }
       try {
             User::update($status,['id'=>$id]);
        } catch (Exception $e) {
            return $this->error($s.'失败','/admin/user_list');
        }
            return $this->redirect('/admin/user_jinqi'); 
    }

    /**
     * 显示用户列表的页面.
     *
     * @return \think\Response
     */
     public function jinqi()
    {   
        //用户搜索
        //按照用户名搜索
        $search = [];
        if (!empty($_GET['name'])) {
            $search[] = ['name','like',"%{$_GET['name']}%"];
        }
        //按照用户等级搜索
        if (!empty($_GET['level'])) {
            $search[] = ['level','=',"{$_GET['level']}"];
        }
        $search[] = ['status','=','0'];
        // $date = User::select();
        $data = User::where($search)->paginate(3)->appends($_GET);
        // dump($date);
        // die;
        return view('User/user_hui',['data'=>$data]);
    } 

    //用户回收站的方法
    public function recycle(Request $request, $id)
    {
        $enable = User::find($id);
        // $enable = $request->post();

       // dump($enable);
       // die;
       $status = [];
       if ($enable['status'] == 0) {
            $status['status'] = 1;
                $s = "启用";
       }else{
            $status['status'] = 0;
                $s = "禁用";
       }
       try {
             User::update($status,['id'=>$id]);
        } catch (Exception $e) {
            return $this->error($s.'失败','/admin/user_jinqi');
        }
            return $this->redirect('/admin/user_list'); 
    }

    //检测管理员是否存在
    public function search_name()
    {
        $name = $_GET['aname'];
        $data = Admin::where('aname','=',$name)->find();
        if (empty($data)) {
            return json_encode(['status' => 400]);
        }
            return json_encode(['status' => 200]);

    }

    //检测用户名是否存在
    public function search_username()
    {
        $name = $_GET['name'];
        $data = User::where('name','=',$name)->find();
        if (empty($data)) {
            return json_encode(['status' => 400]);
        }
            return json_encode(['status' => 200]);

    }

}
