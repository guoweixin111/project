<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Config;


class ConfigController extends Controller
{

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view('/webconfig/webconfig');
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
        $validate = new \app\admin\validate\Config;
        if (!$validate->check($data)) {
            return $this ->error($validate->getError());
        }

        $file = $request ->file('pic');
        // dump($data);
        // dump($file);
        // die;
        $info = $file->move('Webconfig_Image');
        $filePath = $info->getSaveName();
        $data['pic'] = $filePath;
        try {
            Config::create($data,true);
        } catch (Exception $e) {
            return $this ->error('添加失败','/admin/config_add');
        }
            return $this ->success('添加成功','/admin/config_list');
    }


    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function configlist()
    {
        $data = Config::select();
        return view('webconfig/configlist',['data'=>$data]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //获取单条数据
        $data = Config::find($id);
        // dump($data);die;
        return view('webconfig/configedit',['data'=>$data]);
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
        $validate = new \app\admin\validate\Config;
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
                $info = $file->move('Webconfig_Image');
                //查询原来的数据
                $res = Config::find($id);
                // dump($data);
                // die;
                $oldname = 'Webconfig_Image/'.$res['pic'];
                // dump($res);die;
                // 删除原图片
                unlink($oldname);
                //获取新上传图片的路径
                $filePath = $info->getSaveName();
                $data['pic'] = $filePath;
                //如果不修改执行以下代码
            }else{
                $data = Config::find($id);
                $data['pic'] = $res['pic'];
            }
        }
        //修改其他数据
            try {
                Config::update($data,['id'=>$id]);
            } catch (Exception $e) {
                return $this ->error('修改失败');
            }
                return $this ->success('修改成功','/admin/config_list');
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
        $enable = Config::find($id);
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
             Config::update($status,['id'=>$id]);
        } catch (Exception $e) {
            return $this->error($s.'失败','/admin/config_list');
        }
            return $this->redirect('/admin/config_jinqi'); 
    }

    public function jinqi()
    {   
        // $date = User::select();
        $data = Config::select();
        // dump($data);
        // die;
        return view('webconfig/confighui',['data'=>$data]);
    } 

    //用户回收站的方法
    public function recycle(Request $request, $id)
    {
       $enable = Config::find($id);
       $status = [];
       if ($enable['status'] == 2) {
            $status['status'] = 1;
                $s = "启用";
       }else{
            $status['status'] = 2;
                $s = "禁用";
       }
       try {
             Config::update($status,['id'=>$id]);
        } catch (Exception $e) {
            return $this->error($s.'失败','/admin/config_jinqi');
        }
            return $this->redirect('/admin/config_list'); 
    }






}
