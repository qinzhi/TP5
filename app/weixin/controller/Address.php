<?php
/**
 * 会员收货地址
 */
namespace app\weixin\controller;

use think\Db;
use think\Log;
use think\Request;
use app\common\model\Address as AddressModel;

class Address extends Weixin
{

    public $limit = 20; //最多添加20条收货地址

    public $member_id;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->member_id = $this->member['id'];
    }

    public function index(){
        $addressList = $this->getList();
        $this->assign('addressList',$addressList);
        return $this->fetch();
    }
    
    public function add(){
        if(Request::instance()->isPost()){
            $result = $this->save();
            if($result['code'] == 1){
                $this->redirect('address/index');
            }else{
                $this->error($result['msg']);
            }
        }else{
            return $this->fetch();
        }
    }

    public function edit($id){
        if(Request::instance()->isPost()){
            $result = $this->save();
            if($result['code'] == 1){
                $this->redirect('address/index');
            }else{
                $this->error($result['msg']);
            }
        }else{
            $addressModel = new AddressModel($this->member_id);
            $address = $addressModel->getAddressById($id)->member_id;
            $address['area'] = trim(str_replace($address['address'],'',$address['area_info']));
            $this->assign('address',$address);
            return $this->fetch();
        }
    }

    public function getList(){
        $addressModel = new AddressModel($this->member_id);
        $address = $addressModel->getList($this->limit);
        return $address;
    }

    public function del($address_id){
        $addressModel = new AddressModel($this->member_id);
        $address = $addressModel->getDefault();
        $result = $addressModel->where('member_id',$this->member_id)->where('id',$address_id)->delete();
        if(isset($address->member_id) && $address->member_id['id'] == $address_id){
            $addressModel->setDefault();
        }
        if($result){
            return ['code' => 1,'msg' => '删除成功'];
        }else{
            return ['code' => 1,'msg' => '删除失败'];
        }
    }

    public function save(){
        $addressModel = new AddressModel($this->member_id);
        $id = Request::instance()->request('id/d');
        $consignee = Request::instance()->request('consignee','','trim');
        if(empty($consignee)){
            return ['code'=>-1,'msg'=>'收货人不能为空'];
        }
        $mobile = Request::instance()->request('mobile','','trim');
        if(preg_match("/^1[34578]\d{9}$/", $mobile) === false){
            return ['code'=>-1,'msg'=>'手机号不正确'];
        }
        $area = Request::instance()->request('area','','trim');
        if(empty($area)){
            return ['code'=>-1,'msg'=>'省、市、区/县不能为空'];
        }
        $address = Request::instance()->request('address','','trim');
        if(empty($address)){
            return ['code'=>-1,'msg'=>'详细地址不能为空'];
        }
        $is_default = Request::instance()->request('is_default',0,'boolval');
        if($is_default){
            $addressModel->clearDefault();
        }else{
            $defaultAddress = $addressModel->getDefault();
            if(empty($defaultAddress->member_id)){
                $is_default = 1;
            }
        }
        list($province_id,$city_id,$county_id) = $this->getAreaId($area);

        $data = [
            'consignee' => $consignee,
            'mobile' => $mobile,
            'province_id' => $province_id,
            'city_id' => $city_id,
            'county_id' => $county_id,
            'address' => $address,
            'area_info' => $area . ' ' . $address,
            'is_default' => $is_default
        ];
        if($id > 0){//更新
            $result = $addressModel->where('id', $id)->where('member_id',$this->member_id)->update($data);
            if($result){
                return ['code'=>1,'msg'=>'更新成功','address_id'=> $id];
            }else{
                return ['code'=>-1,'msg'=>'更新失败'];
            }
        }else{//添加
            $num = $addressModel->getNum();
            if($num < $this->limit){
                $data['member_id'] = $this->member_id;
                $data['add_time'] = time();
                $addressModel->data($data)->save();
                if($addressModel->id > 0){
                    return ['code'=>1,'msg'=>'添加成功','address_id'=> $addressModel->id];
                }else{
                    return ['code'=>-1,'msg'=>'添加失败'];
                }
            }else{
                return ['code'=>-1,'msg'=>'最多添加20条收货地址'];
            }
        }
    }

    public function setDefault($id){
        $addressModel = new AddressModel($this->member_id);
        $result = $addressModel->setDefaultById($id);
        if($result){
            return ['code'=>1,'msg'=>'设置默认地址成功'];
        }else{
            return ['code'=>-1,'msg'=>'设置默认地址失败'];
        }
    }

    private function getAreaId($area){
        list($province,$city,$county) = explode(' ',$area);
        $province = Db::name('area')->where('name|shortname','eq',$province)->find();
        $city = Db::name('area')->where('parentid',$province['id'])->where('name|shortname','eq',$city)->find();
        $county = Db::name('area')->where('parentid',$city['id'])->where('name|shortname','eq',$county)->find();
        return [$province['id'],$city['id'],$county['id']];
    }
}
