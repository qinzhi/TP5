<?php

namespace app\admin\Controller;

use app\admin\Model\Attr;
use think\Db;
use app\admin\Model\Model;
use think\Request;

class GoodsAttr extends Admin {

    public $attr;

    public function __construct(){
        parent::__construct();
        $this->attr = new Attr();;
    }

    public function getModels(){
        $model = Model::all();
        return $model;
    }

    public function gets($id){
        $attrs = $this->attr->where(['model_id'=>$id])->select();
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
            $model_id = Db::name(Model::TABLE_NAME)->insertGetId(['name'=>$name]);

            if(!empty($_POST['type'])){
                $attr_name = $_POST['attr_name'];
                $type = $_POST['type'];
                $value = $_POST['value'];
                for($i=0,$len=count($attr_name);$i<$len;$i++){
                    $attr = array(
                        'model_id' => $model_id,
                        'type' =>   $type[$i],
                        'name' =>   $attr_name[$i],
                        'value' =>  $value[$i],
                        'sort' => $i
                    );
                    $this->attr->save($attr);
                }
            }
            return $this->success('添加成功',url('goodsAttr/index'));
        }

        return $this->fetch('goods/attr/add');

    }

    public function edit($id){
        if(Request::instance()->isPost()){
            $name = Request::instance()->request('name','','trim');
            Model::update(['name'=>$name,'id'=>$id]);

            $del_id = Request::instance()->request('del_id');
            if(!empty($del_id)){
                Attr::destroy($del_id);
            }
            if(!empty($_POST['type'])){
                $attr_id = $_POST['attr_id'];
                $attr_name = $_POST['attr_name'];
                $type = $_POST['type'];
                $value = $_POST['value'];
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