<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Admin;


class AdminController extends Controller
{
    /**
     * 显示添加管理员的页面.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view('admin/adminadd');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $request ->post();
        //验证器
        $validate = new \app\admin\validate\Admin;
        if (!$validate->check($data)) {
            return $this ->error($validate->getError());
        }
        $file = $request->file('pic');
        $info = $file->move('Admin_Image');
        $filePath = $info->getSaveName();
        $data['pic'] = $filePath;
        try {
            Admin::create($data,true);
        } catch (Exception $e) {
            return $this ->error('添加失败','/admin/admin_add');
        }
            return $this ->success('添加成功','/admin/admin_list');
    }

     /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function adminlist()
    {
        //管理员搜索
        //按照管理员名搜索
        $search = [];
        if (!empty($_GET['aname'])) {
            $search[] = ['aname','like',"%{$_GET['aname']}%"];
        }
        //按照商品状态搜索
        if (!empty($_GET['level'])) {
            $search[] = ['level','=',"{$_GET['level']}"];
        }
        $search[] = ['status','=','1'];

        $data = Admin::where($search)->paginate(4)->appends($_GET);
        return view('admin/adminlist',['data'=>$data]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = Admin::find($id);
        return view('admin/adminedit',['data'=>$data]);
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
        $data = $request ->post();
        //验证器
        $validate = new \app\admin\validate\Admin;
        if (!$validate->check($data)) {
            return $this ->error($validate->getError());
        }
        $file = $request ->file();
        // dump($file);
        //判断是否修改商品图片
        if (!empty($_FILES['pic']['name'])) {
            //如果修改执行以下代码
            if (is_array($file)) {
                $file = $request->file('pic');
                $info = $file->move('Admin_Image');
                //查询原来的数据
                $res = Admin::find($id);
                // dump($data);
                // die;
                $oldname = 'Admin_Image/'.$res['pic'];
                // dump($res);die;
                // 删除原图片
                unlink($oldname);
                //获取新上传图片的路径
                $filePath = $info->getSaveName();
                $data['pic'] = $filePath;
                //如果不修改执行以下代码
            }else{
                $data = Admin::find($id);
                $data['pic'] = $res['pic'];
            }
        }
        //修改其他数据
            try {
                Admin::update($data,['id'=>$id]);
            } catch (Exception $e) {
                return $this ->error('修改失败');
            }
                return $this ->success('修改成功','/admin/admin_list');
    }

    /**
     * 显示修改管理员的页面.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function pwd($id)
    {
        $data = Admin::find($id);
        return view('admin/adminpwd',['data'=>$data]);
    }

    /**
     * 执行修改管理员密码
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function updatepwd(Request $request, $id)
    {
        $date = Admin::find($id);

        $data = $request->post();
        //验证器
        $validate = new \app\admin\validate\Admin;
        if (!$validate->check($data)) {
            return $this ->error($validate->getError());
        }
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
             Admin::update($data,['id'=>$id]);
        } catch (Exception $e) {
            return $this->error('修改管理员密码失败！','/admin/admin_pwd');
        }
            return $this->success('修改管理员密码成功！','/admin/admin_list');
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
        $data = Admin::find($id);
        //获取删除图片的路径
        $pic = 'Admin_Image/'.$data['pic'];
        $data = Admin::destroy($id);
        //删除图片
        unlink($pic);
        if ($data) {
            return $this -> success('删除管理员成功！','/admin/admin_list');
        }
            return $this -> error('删除管理员失败！','/admin/admin_list');
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
        $enable = Admin::find($id);
        // $enable = $request->post();
        // dump($enable);
        // die;
        $status = [];
        if ($enable['status'] == 1) {
            $status['status'] = 2;
                $s = "启用";
        }else{
            $status['status'] = 1;
                $s = "禁用";
        }
        try {
             Admin::update($status,['id'=>$id]);
        } catch (Exception $e) {
            return $this->error($s.'失败','/admin/admin_list');
        }
            return $this->redirect('/admin/admin_jinqi'); 
    }

    /**
     * 显示管理员回收站的页面.
     *
     * @return \think\Response
     */
     public function jinqi()
    {   
        //管理员搜索
        //按照管理员名搜索
        $search = [];
        if (!empty($_GET['aname'])) {
            $search[] = ['aname','like',"%{$_GET['aname']}%"];
        }
        //按照用户等级搜索
        if (!empty($_GET['level'])) {
            $search[] = ['level','=',"{$_GET['level']}"];
        }
        $search[] = ['status','=','2'];
        // $date = User::select();
        $data = Admin::where($search)->paginate(3)->appends($_GET);
        // dump($date);
        // die;
        return view('admin/adminhui',['data'=>$data]);
    } 

    //用户回收站的方法
    public function recycle(Request $request, $id)
    {
       $enable = Admin::find($id);
       $status = [];
       if ($enable['status'] == 2) {
            $status['status'] = 1;
                $s = "启用";
       }else{
            $status['status'] = 2;
                $s = "禁用";
       }
       try {
             Admin::update($status,['id'=>$id]);
        } catch (Exception $e) {
            return $this->error($s.'失败','/admin/admin_jinqi');
        }
            return $this->redirect('/admin/admin_list'); 
    }

    //添加管理员时，检测用户名是否存在
    public function search_aname()
    {
        $name = $_GET['aname'];
        $data = Admin::where('aname','=',$name)->find();
        if (empty($data)) {
            return json_encode(['status' => 400]);
        }
            return json_encode(['status' => 200]);

    }



}
