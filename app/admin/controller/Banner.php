<?php

namespace app\admin\Controller;

use app\admin\Model\BannerPosition;
use think\Request;

class Banner extends Admin {

    public function index(){
        return $this->fetch();
    }

    public function add(){

        return $this->fetch();
    }

    public function position(){
        $position = BannerPosition::all();
        $this->assign('position',$position);
        return $this->fetch('banner/position/index');
    }

    public function position_add(){
        if(Request::instance()->isPost()){
            $insert_id = (new BannerPosition(Request::instance()->post()))->allowField(true)->save();
            if($insert_id > 0){
                $this->redirect('banner/position');
            }else{
                $this->error('广告位添加失败','banner/position_add');
            }
        }else{
            return $this->fetch('banner/position/add');
        }
    }

    public function position_edit($id){
        if(Request::instance()->isPost()){
            $bannerPosition = new BannerPosition();
            if($bannerPosition->save(Request::instance()->post(),['id' => $id])){
                $this->redirect('banner/position');
            }else{
                $this->error('广告位更新失败',url('banner/position_edit',['id'=>$id]));
            }
        }else{
            $position = BannerPosition::get($id);
            $this->assign('position',$position);
            return $this->fetch('banner/position/edit');
        }
    }

    public function position_update(){
        if(Request::instance()->isAjax()){
            if(BannerPosition::save(Request::instance()->post())){
                $result = ['code'=>1,'msg'=>'更新成功'];
            }else{
                $result = ['code'=>0,'msg'=>'更新失败'];
            }
        }else{
            $result = ['code'=>0,'msg'=>'异常提交'];
        }
        return $result;
    }

    public function position_del($id){
        if(BannerPosition::destroy($id)){
            return ['code'=>1,'msg'=>'删除成功'];
        }else{
            return ['code'=>0,'msg'=>'删除失败'];
        }
    }
}