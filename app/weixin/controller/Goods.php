<?php
namespace app\weixin\controller;

use app\common\Model\Products;
use think\Controller;
use think\Db;

class Goods extends Controller
{
    public function getProductList($goods_id){
        $products = Products::where('goods_id',$goods_id)->select();
        if(count($products) == 1){
            $products['group'] = [
                'is_single' => true,
            ];
        }else if(count($products) > 1){
            $sub = [];
            foreach ($products as $key=>$product){
                $spec_arr = json_decode($product['spec_array'],true);
                $sub[$product['id']] = '-'. 0;
                foreach ($spec_arr as $k=>$spec){
                    $sub[$product['id']] .= '-'. $k;
                    $box[$spec['name']][] = $spec['value'];
                }
                $sub[$product['id']] = substr($sub[$product['id']],1);
            }
            $products['group'] = [
                'is_single' => false,
                'box' => $box,
                'sub' => array_flip($sub)
            ];
        }
        return json($products);
    }
}