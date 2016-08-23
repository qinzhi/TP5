<?php

namespace app\admin\Controller;

use think\Db;
use app\admin\Model\Model;
use think\Request;

class GoodsAttr extends Admin {

    public $attr;

    public function __construct(){
        parent::__construct();
        $this->attr = Db::name('attr');
    }

    public function getModels(){
        $model = Model::select();
        return $model;
    }

    public function gets(){
        $model_id = Request::instance()->get('id');
        $attrs = $this->attr->where(array('model_id'=>$model_id))->select();
        return $attrs;
    }

    public function index(){
        $models = $this->getModels();
        $this->assign('models',$models);
        return $this->fetch('goods/attr/index');
    }

    public function add(){
        if(Request::instance()->isPost()){
            $name = Request::instance()->request('name','','trim');
            $model_id = M('Model')->add(array('name'=>$name));

            $attr_name = Request::instance()->request('attr_name');
            if(!empty($attr_name)){
                $type = Request::instance()->request('type');
                $value = Request::instance()->request('value');
                for($i=0,$len=count($attr_name);$i<$len;$i++){
                    $attr = array(
                        'model_id' => $model_id,
                        'type' =>   $type[$i],
                        'name' =>   $attr_name[$i],
                        'value' =>  $value[$i],
                        'sort' => $i
                    );
                    $this->attr->add($attr);
                }
            }
            $this->success('添加成功',url('goodsAttr/index'));
            return;
        }

        $this->display('goods/attr/add');

    }

    public function edit($id){
        if(Request::instance()->isPost()){
            $name = Request::instance()->request('name','','trim');
            Model::update(['name'=>$name,'id'=>$id]);

            $del_id = Request::instance()->request('del_id');
            if(!empty($del_id)){
                $this->attr->delete($del_id);
            }

            $attr_id = Request::instance()->request('attr_id');
            if(!empty($attr_id)){
                $attr_name = Request::instance()->request('attr_name');
                $type = Request::instance()->request('type');
                $value = Request::instance()->request('value');
                $attr = [];
                for($i=0,$len=count($attr_id);$i<$len;$i++){
                    $attr[$i] = [
                        'model_id' => $id,
                        'type' =>   $type[$i],
                        'name' =>   $attr_name[$i],
                        'value' =>  $value[$i],
                        'sort' => $i
                    ];
                    if($attr_id[$i] > 0){
                        $attr[$i]['id'] = $attr_id[$i];
                    }
                }
                $this->attr->saveAll($attr);
            }
            $this->success('保存成功','goodsAttr/index');
        }else{
            $model = Model::get($id);
            $model['attr'] = $this->attr->where('model_id',$id)->order('sort asc')->select();
            $this->assign('model',$model);
            return $this->fetch('goods/attr/edit');
        }
    }

    public function del($ids){
        if(Model::destroy($ids)){
            return ['code'=>1,'msg'=>'删除成功'];
        }else{
            return ['code'=>0,'msg'=>'删除失败'];
        }
    }

}