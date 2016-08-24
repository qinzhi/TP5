<?php

namespace app\admin\Controller;

use app\admin\model\Spec;
use think\Db;
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

        $value = !empty($_POST['value'])?$_POST['value']:'';
        $value = empty($value) ?: json_encode($value, JSON_UNESCAPED_UNICODE);
        $spec = [
            'name' => $name,
            'value' => $value,
            'remark' => Request::instance()->request('remark','','trim'),
            'create_time' => time(),
            'update_time' => time()
        ];

        if($insert_id = Db::name(Spec::TABLE_NAME)->insertGetId($spec)){
            $result = ['code'=>1,'msg'=>'添加成功','data'=> Spec::get($insert_id)];
        }else{
            $result = ['code'=>0,'msg'=>'添加失败'];
        }

        return $result;
    }

    public function edit($id){
        $name = Request::instance()->request('name','','trim');
        if(empty($name)){
            return ['code'=>0,'msg'=>'规格名称不能为空'];
        }
        $value = !empty($_POST['value'])?$_POST['value']:'';
        $value = empty($value) ?: json_encode($value, JSON_UNESCAPED_UNICODE);
        $spec = [
            'id' => $id,
            'name' => $name,
            'value' => $value,
            'remark' => Request::instance()->request('remark','','trim'),
            'update_time' => time()
        ];

        if(Spec::update($spec)){
            $result = ['code'=>1,'msg'=>'保存成功','data'=> Spec::get($id)];
        }else{
            $result = ['code'=>0,'msg'=>'保存失败'];
        }

        return $result;
    }

    public function del($ids){
        if(Spec::destroy($ids)){
            return ['code'=>1,'msg'=>'删除成功'];
        }else{
            return ['code'=>0,'msg'=>'删除失败'];
        }
    }

    public function get($id){
        if($spec = Spec::get($id)){
            $spec['value'] = json_decode($spec['value']);
        }
        return $spec;
    }

}