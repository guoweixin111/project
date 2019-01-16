<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\common\model\Type;
use app\common\model\Goods;
use app\tools\Cattree;



class IndexController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        session_start();
        $type = Type::select();
        $c = new Cattree($type);
        $type = $c ->getTree();
        $goods = Goods::select();
        return view('default/index',['type'=>$type,'goods'=>$goods]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function goodslist()
    {    
        $search = [];
        if(!empty($id)){
            $select = Type::where('pid','=',$id)->column('id');
            $select[] = (int)$id;
            $search[] = ['type_id', 'in' ,$select];
        }
        session_start();
        $type = Type::select();
        $goods = Goods::where($search)->select();

        return view('goods/goodslist',['type'=>$type,'goods'=>$goods]);
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
}
