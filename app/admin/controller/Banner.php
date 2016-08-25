<?php

namespace app\admin\Controller;

use app\admin\Model\BannerPosition;
use think\Request;
use think\Url;
use app\admin\Model\Banner as BannerModel;

class Banner extends Admin {

    public $banner;

    public function __construct()
    {
        parent::__construct();
        $this->banner = new BannerModel();
    }

    public function index(){
        $banner = $this->banner->getList();
        $this->assign('banner',$banner);
        return $this->fetch();
    }

    public function add(){
        if(Request::instance()->isPost()){
            $banner = [
                'position_id' => Request::instance()->post('position_id','','intval'),
                'name' => Request::instance()->post('name','','trim'),
                'intro' => Request::instance()->post('intro','','trim'),
                'image' => Request::instance()->post('image','','trim'),
                'link' => Request::instance()->post('sort','','trim'),
                'status' => Request::instance()->post('status','','intval'),
                'sort' => Request::instance()->post('sort','','intval')
            ];
            $time = Request::instance()->post('time');
            if(!empty($time)){
                list($start_time,$end_time) = explode(' - ',$time);
                $banner['start_time'] = strtotime($start_time);
                $banner['end_time'] = strtotime($end_time);
            }

            if($this->banner->data($banner)->save()){
                return $this->redirect('banner/index');
            }else{
                return $this->error('广告添加失败','banner/add');
            }
        }else{
            $position = BannerPosition::all();
            $this->assign('position',$position);
            return $this->fetch();
        }
    }

    public function edit($id){
        if(Request::instance()->isPost()){
            $banner = [
                'position_id' => Request::instance()->post('position_id','','intval'),
                'name' => Request::instance()->post('name','','trim'),
                'intro' => Request::instance()->post('intro','','trim'),
                'image' => Request::instance()->post('image','','trim'),
                'link' => Request::instance()->post('sort','','trim'),
                'status' => Request::instance()->post('status','','intval'),
                'sort' => Request::instance()->post('sort','','intval')
            ];
            $time = Request::instance()->post('time');
            if(!empty($time)){
                list($start_time,$end_time) = explode(' - ',$time);
                $banner['start_time'] = strtotime($start_time);
                $banner['end_time'] = strtotime($end_time);
            }
            if($this->banner->save($banner,['id' => $id])){
                return $this->redirect('banner/index');
            }else{
                return $this->error('广告编辑失败',Url::build('Banner/edit',['id'=>$id]));
            }
        }else{
            $banner = BannerModel::get($id);
            $this->assign('banner',$banner);
            $position = BannerPosition::all();
            $this->assign('position',$position);
            return $this->fetch();
        }
    }

    public function del($id){
        if(BannerModel::destroy($id)){
            return ['code'=>1,'msg'=>'删除成功'];
        }else{
            return ['code'=>0,'msg'=>'删除失败'];
        }
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
            if($bannerPosition->update(Request::instance()->post(),['id' => $id])){
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
            if(BannerPosition::update(Request::instance()->post())){
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