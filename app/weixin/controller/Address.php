<?php
/**
 * 会员收货地址
 */
namespace app\weixin\controller;

use think\Controller;
use think\Db;
use think\Request;
use app\common\model\Address as AddressModel;

class Address extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->user_id = 1;
    }

    public function get(){
        $addressModel = new AddressModel($this->user_id);
        $address = $addressModel->getList();
        return json($address);
    }

    public function del($address_id){
        $addressModel = new AddressModel($this->user_id);
        return $addressModel->where('user_id',$this->user_id)->where('address_id',$address_id)->delete();
    }

    public function save(){
        $addressModel = new AddressModel($this->user_id);
        $consignee = Request::instance()->request('consignee','','trim');
        if(empty($consignee)){
            return json(['code'=>-1,'msg'=>'收货人不能为空']);
        }
        $mobile = Request::instance()->request('mobile','','trim');
        if(preg_match("/^1[34578]\d{9}$/", $mobile) === false){
            return json(['code'=>-1,'msg'=>'手机号不正确']);
        }
        $area = Request::instance()->request('area','','trim');
        if(empty($area)){
            return json(['code'=>-1,'msg'=>'省、市、区/县不能为空']);
        }
        $address = Request::instance()->request('address','','trim');
        if(empty($address)){
            return json(['code'=>-1,'msg'=>'详细地址不能为空']);
        }
        $is_defalut = Request::instance()->request('is_defalut','','boolval');
        if($is_defalut){
            $addressModel->clearDefault();
        }
        list($province_id,$city_id,$county_id) = $this->getAreaId($area);

        $addressModel->data([
            'user_id' => $this->user_id,
            'consignee' => $consignee,
            'mobile' => $mobile,
            'province_id' => $province_id,
            'city_id' => $city_id,
            'county_id' => $county_id,
            'address' => $address,
            'area_info' => $area . ' ' . $address,
            'is_default' => $is_defalut,
            'add_time' => time()
        ])->save();

        if($addressModel->id > 0){
            return json(['code'=>1,'msg'=>'添加成功','address_id'=> $addressModel->id]);
        }else{
            return json(['code'=>-1,'msg'=>'添加失败']);
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
