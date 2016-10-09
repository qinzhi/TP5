<?php
namespace app\weixin\controller;

use app\common\model\Products;
use think\Controller;

class Goods extends Controller
{
    public function getProductList($goods_id){
        $products = Products::where('goods_id',$goods_id)->select();
        if(count($products) == 1){
            $arr = [
                'is_single' => true,
                'products' => $products
            ];
        }else if(count($products) > 1){
            $properties = [];
            foreach ($products as $key=>$product){
                $spec_arr = json_decode($product['spec_array'],true);
                foreach ($spec_arr as $k=>$spec){
                    if(!isset($properties[$spec['name']])) $properties[$spec['name']] = [];
                    if(in_array($spec['value'],$properties[$spec['name']]) === false)
                        $properties[$spec['name']][] = $spec['value'];
                }
            }
            $arr = [
                'is_single' => false,
                'properties' => $properties,
                'products' => $products
            ];
        }
        return json($arr);
    }
}