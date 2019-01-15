<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Type;
use app\tools\Cattree;

class TypeController extends Controller
{
    /**
     * 显示分类列表页
     *
     * @return \think\Response
     */
    public function typelist()
    {
        //分类搜索
        //按照分类名搜索
        if (!empty($_GET['name'])) {
            $s = 1;
            $search = [];
            $search[] = ['name','like',"%{$_GET['name']}%"];
            $res = Type::where($search)->select();
        }else{
            $s = 0;
            $data = Type::select();
            $c = new Cattree($data);
            $res = $c -> getTree();
        }
        return view('type/typelist',['res'=>$res,'s'=>$s]);
    }

    /**
     * 显示创建（添加）分类页的方法.
     *
     * @return \think\Response
     */
    public function create($id='')
    {   
        // echo $id;die;
        //查询数据
        $data = Type::select();
        $c = new Cattree($data);
        $res = $c -> getTree();
        return view('type/typeadd',['data'=>$res,'id'=>$id]);
    }

    /**
     * 执行添加分类的方法
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $request ->post();
        //验证器
        $validate = new \app\admin\validate\Type;
        if (!$validate->check($data)) {
            return $this ->error($validate->getError());
        }
        // dump($data);
       
        try {
             Type::create($data,true);
        } catch (Exception $e) {
            return $this -> error('添加分类失败哦！','/admin/type_add');
        }
            return $this -> success('恭喜,添加分类成功！！','/admin/type_list');
    }

    /**
     * 显示编辑(修改)分类的方法.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //查询数据
        $data = Type::find($id);
        return view('type/typeedit',['data'=>$data]);
    }

    /**
     * 执行修改分类的方法
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request ->post();

        //验证器
        $validate = new \app\admin\validate\Type;
        if (!$validate->check($data)) {
            return $this ->error($validate->getError());
        }

        try {
            Type::update($data,['id'=>$id]);
        } catch (Exception $e) {
            return $this -> error('对不起，修改失败！','/admin/type_edit');
        }
            return $this -> success('恭喜，修改成功哦！','/admin/type_list');
    }

    /**
     * 删除分类的方法
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $res = Type::where(['pid'=>$id])->find();
        if ($id == $res['pid']) {
            return $this -> error('该分类下有子分类，不能删除！！');
        }
        $data = Type::destroy($id);
        if ($data) {
            return $this ->success('恭喜，删除分类成功！！','/admin/type_list');
        }
            return $this ->error('对不起，删除分类失败！！','/admin/type_list');

    }
}
