<?php

namespace app\admin\Controller;

use app\admin\model\Spec;
use think\Request;

class GoodsSpec extends Admin {

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $specs = Spec::all();
        $this->assign('specs',$specs);
        return $this->fetch('goods/spec');
    }

    public function add(){
        $name = Request::instance()->request('name','','trim');
        if(empty($name)){
            return ['code'=>0,'msg'=>'规格名称不能为空'];
        }
        $value = Request::instance()->request('value');
        $value = empty($value) ?: Json::encode($value) ;
        $spec = [
            'name' => $name,
            'value' => $value,
            'remark' => Request::instance()->request('name','','remark'),
        ];

        if($insert_id = Spec::save($spec)){
            $result = ['code'=>1,'msg'=>'添加成功','data'=> Spec::get($insert_id)];
        }else{
            $result = ['code'=>0,'msg'=>'添加失败'];
        }

        return $result;
    }

    public function edit(){
        $name = Request::instance()->request('name','','trim');
        if(empty($name)){
            return ['code'=>0,'msg'=>'规格名称不能为空'];
        }
        $value = Request::instance()->request('value');
        $value = empty($value) ?: Json::encode($value) ;
        $id = Request::instance()->request('id');
        $spec = [
            'id' => $id,
            'name' => $name,
            'value' => $value,
            'remark' => Request::instance()->request('name','','remark')
        ];

        if(Spec::save($spec)){
            $result = ['code'=>1,'msg'=>'保存成功','data'=> Spec::get($id)];
        }else{
            $result = ['code'=>0,'msg'=>'保存失败'];
        }

        return $result;
    }

    public function del(){
        $spec_id = Request::instance()->request('ids');
        if(Spec::destroy($spec_id)){
            return ['code'=>1,'msg'=>'删除成功'];
        }else{
            return ['code'=>0,'msg'=>'删除失败'];
        }
    }

    public function get(){
        $id = Request::instance()->request('id');
        if($spec = Spec::get($id)){
            $spec['value'] = Json::decode($spec['value']);
        }
        return $spec;
    }

}