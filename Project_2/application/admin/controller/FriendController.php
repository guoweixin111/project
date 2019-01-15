<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Friend;


class FriendController extends Controller
{

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view('friendlink/friendadd');
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
        $validate = new \app\admin\validate\Friend;
        if (!$validate->check($data)) {
            return $this ->error($validate->getError());
        }

        $file = $request->file('logo');
        $info = $file->move('Friend_Image');
        $filePath = $info->getSaveName();
        $data['logo'] = $filePath;
        try {
            Friend::create($data,true);
        } catch (Exception $e) {
            return $this ->error('添加失败','/admin/friend_add');
        }
            return $this ->success('添加成功','/admin/friend_list');
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function friendlist()
    {
        //友情链接搜索
        //按照友情链接名搜索
        $search = [];
        if (!empty($_GET['linkname'])) {
            $search[] = ['linkname','like',"%{$_GET['linkname']}%"];
        }
        $search[] = ['status','=','1'];

        $data = Friend::where($search)->paginate(4)->appends($_GET);
        return view('friendlink/friendlist',['data'=>$data]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = Friend::find($id);
        // dump($data);
        return view('friendlink/friendedit',['data'=>$data]);
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
        $validate = new \app\admin\validate\Friend;
        if (!$validate->check($data)) {
            return $this ->error($validate->getError());
        }

        $file = $request ->file();
        // dump($data);
        // dump($file);
        // die;
        //判断是否修改商品图片
        if (!empty($_FILES['logo']['name'])) {
            //如果修改执行以下代码
            if (is_array($file)) {
                $file = $request->file('logo');
                $info = $file->move('Friend_Image');
                // dump($file);
                // die;
                //查询原来的数据
                $res = Friend::find($id);
                // dump($res);
                // die;
                $oldname = 'Friend_Image/'.$res['logo'];
                // dump($oldname);die;
                // 删除原图片
                unlink($oldname);
                //获取新上传图片的路径
                $filePath = $info->getSaveName();
                $data['logo'] = $filePath;
                //如果不修改执行以下代码
            }else{
                $data = Friend::find($id);
                $data['logo'] = $res['logo'];
            }
        }
        //修改其他数据
            try {
                Friend::update($data,['id'=>$id]);
            } catch (Exception $e) {
                return $this ->error('修改失败');
            }
                return $this ->success('修改成功','/admin/friend_list');
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
        $data = Friend::find($id);
        // halt($data);
        //获取删除图片的路径
        $pic = 'Friend_Image/'.$data['logo'];
        $data = Friend::destroy($id);
        //删除图片
        unlink($pic);
        if ($data) {
            return $this -> success('删除友情链接成功！','/admin/friend_list');
        }
            return $this -> error('删除友情链接失败！','/admin/friend_list');
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
        $enable = Friend::find($id);
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
             Friend::update($status,['id'=>$id]);
        } catch (Exception $e) {
            return $this->error($s.'失败','/admin/friend_list');
        }
            return $this->redirect('/admin/friend_jinqi'); 
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
        if (!empty($_GET['linkname'])) {
            $search[] = ['linkname','like',"%{$_GET['linkname']}%"];
        }
        $search[] = ['status','=','2'];
        // $date = User::select();
        $data = Friend::where($search)->paginate(3)->appends($_GET);
        // dump($date);
        // die;
        return view('friendlink/friendhui',['data'=>$data]);
    }

    //用户回收站的方法
    public function recycle(Request $request, $id)
    {
       $enable = Friend::find($id);
       $status = [];
       if ($enable['status'] == 2) {
            $status['status'] = 1;
                $s = "启用";
       }else{
            $status['status'] = 2;
                $s = "禁用";
       }
       try {
             Friend::update($status,['id'=>$id]);
        } catch (Exception $e) {
            return $this->error($s.'失败','/admin/friend_jinqi');
        }
            return $this->redirect('/admin/friend_list'); 
    }

    
    //后台添加友情链接时，检测链接名是否存在
    public function search_friendname()
    {
        $name = $_GET['linkname'];
        $data = Friend::where('linkname','=',$name)->find();
        if (empty($data)) {
            return json_encode(['status' => 400]);
        }
            return json_encode(['status' => 200]);

    }


}
