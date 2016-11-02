<?php
namespace app\weixin\controller;

use app\common\model\GoodsToImages;
use app\common\model\Products;
use think\Controller;
use app\common\model\Goods as GoodsModel;
use think\Request;

class Goods extends Controller
{

    public $limit = 10;
    
    public function lists(){
        $this->assign('limit',$this->limit);
        return $this->fetch();
    }

    public function getGoodsList($field = '',$sort='desc',$keyword = ''){
        if(!empty($field)){
            $params['field'] = $field;
            $params['sort'] = $sort;
        }
        $params['status'] = 1;//上架的商品
        $params['keyword'] = $keyword;
        $page = Request::instance()->request('page',1);
        $offset = ($page - 1) * $this->limit;
        $goodsModel = new GoodsModel();
        $goodsList = $goodsModel->getGoodsList($params,$offset,$this->limit);
        foreach ($goodsList as $key => &$val){
            $val['cover_image'] = get_img($val['cover_image']);
            $val['url'] = url('goods/detail',['id'=>$val['id']]);
        }
        $result['goodsNum'] = $goodsModel->getGoodsNum($params);
        $result['goodsList'] = $goodsList;
        return $result;
    }

    public function detail($id){
        $images = GoodsToImages::where('goods_id',$id)->select();
        $this->assign('images',$images);
        return $this->fetch();
    }

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