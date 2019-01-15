<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Goods;
use app\common\model\Type;
use app\tools\Cattree;

class GoodsController extends Controller
{
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {   
        //查询date_type表中的数据
        $data = Type::select();
        $c = new Cattree($data);
        $data = $c ->getTree();
        // dump($data);die;
        return view('goods/goodsadd',['data'=>$data]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $request->post();

        //验证器
        $validate = new \app\admin\validate\Goods;
        if (!$validate->check($data)) {
            return $this ->error($validate->getError());
        }

        $file = $request->file('pic');
        $info = $file->move('Product_Image');
        $filePath = $info->getSaveName();
        //缩放图片
        // $image = \think\Image::open('Product_Image/'.$filePath);
        // $newName = str_replace('\\', '/suo_', $filePath);
        // $image ->thumb(50, 50)->save('Product_Image/'.$newName);
        $data['pic'] = $filePath;
        try {
            Goods::create($data,true);
        } catch (Exception $e) {
            return $this ->error('添加失败','/admin/goods_add');
        }
            return $this ->success('添加成功','/admin/goods_list');
    }

     /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function goodslist()
    {   
        //用户搜索
        //按照商品名搜索
        $search = [];
        if (!empty($_GET['name'])) {
            $search[] = ['name','like',"%{$_GET['name']}%"];
        }
        //按照商品状态搜索
        if (!empty($_GET['status'])) {
            $search[] = ['status','=',"{$_GET['status']}"];
        }
        $data = Goods::where($search)->paginate(4)->appends($_GET);
        return view('goods/goodslist',['data'=>$data]);
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
        $data = Goods::find($id);
        // dump($data);die;
        return view('goods/goodsedit',['data'=>$data]);
    }

    /**
     * 修改商品信息（包括图片）
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request ->post();

         //验证器
        $validate = new \app\admin\validate\Goods;
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
                $info = $file->move('Product_Image');
                //查询原来的数据
                $res = Goods::find($id);
                // dump($data);
                // die;
                $oldname = 'Product_Image/'.$res['pic'];
                // dump($res);die;
                // 删除原图片
                unlink($oldname);
                //获取新上传图片的路径
                $filePath = $info->getSaveName();
                $data['pic'] = $filePath;
                //如果不修改执行以下代码
            }else{
                $data = Goods::find($id);
                $data['pic'] = $res['pic'];
            }
        }
        //修改其他数据
            try {
                Goods::update($data,['id'=>$id]);
            } catch (Exception $e) {
                return $this ->error('修改失败');
            }
                return $this ->success('修改成功','/admin/goods_list');
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
        $data = Goods::find($id);
        //获取删除图片的路径
        $pic = 'Product_Image/'.$data['pic'];
        $data = Goods::destroy($id);
        //删除图片
        unlink($pic);
        if ($data) {
            return $this -> success('删除商品成功！','/admin/goods_list');
        }
            return $this -> error('删除商品失败！','/admin/goods_list');
    }
}
