<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/27
 * Time: 14:40
 */
namespace app\admin\model;

use think\Db;
use traits\model\SoftDelete;

class GoodsCategory extends Common{

    use SoftDelete;

    /**
     * 删除时间
     * @var string
     */
    protected static $deleteTime = 'delete_time';

    public static $order = 'sort asc'; //排序

    const TABLE_SEO = 'goods_category_to_seo';

    public function move($id,$action){
        $category = self::get($id);
        $cotegories = $this->getCategoriesByPid($category['pid']);
        for($i=0,$len=count($cotegories);$i<$len;$i++){
            if($cotegories[$i]['id'] == $category['id']){
                if($i == 0 && $action == 'up' ){//上移失败
                    return false;
                }elseif($i == $len-1 && $action == 'down'){//下移失败
                    return false;
                }else{
                    if($action == 'up'){
                        $refer = $cotegories[$i - 1];
                    }elseif($action == 'down'){
                        $refer = $cotegories[$i + 1];
                    }else{
                        return false;
                    }
                    $tmp = $refer['sort'];
                    $status1 = $this->where(array('id'=>$refer['id']))->setField(array('sort'=>$category['sort']));
                    $status2 = $this->where(array('id'=>$category['id']))->setField(array('sort'=>$tmp));
                    if($status1 && $status2){
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }
    }

    public function formatTree($lists,$is_init = true){
        if($is_init) $tree[] = array('id'=>'','pid'=>0,'level'=>0,'name'=>'根节点');
        foreach($lists as $list){
            if($list['level'] < 2){
                $tree[] = array(
                    'id' => $list['id'],
                    'pId' => $list['pid'],
                    'name' => $list['name'],
                    'level' => $list['level'],
                    'open' => true,
                );
            }
        }
        return $tree;
    }

    /**
     * 通过父Id获取单个分类
     * @param $pid
     * @return mixed
     */
    public function getCategoryByPid($pid){
        return $this->alias('t')
                        ->join(self::TABLE_SEO . ' t1','t1.category_id=t.id')
                            ->where(array('pid'=>$pid))->find();
    }


    /**
     * 通过分类Id获取分类
     * @param $id
     * @return mixed
     */
    public function getCategoryById($id){
        return $this->field('t.*,t1.title,t1.keywords,t1.descript')
                        ->alias('t')
                            ->join(self::TABLE_SEO . ' t1','t1.category_id=t.id')
                                ->where(['t.id'=>$id])->find();
    }

    /**
     * 通过父Id获取子分类
     * @param $pid
     * @return mixed
     */
    public function getCategoriesByPid($pid){
        return $this->where(['pid'=>$pid])->order($this::$order)->select();
    }

    /**
     * 获取所有分类
     * @return mixed
     */
    public function getCategories(){
        return $this->order($this::$order)->select();
    }

    /**
     * 添加分类
     * @param $category
     * @param $seo
     * @return bool|mixed
     */
    public function addCategory($category,$seo){
        $status = $this->save($category);
        if($status == false){
            return false;
        }else{
            $seo['category_id'] = $this->getData('id');
            Db::name(self::TABLE_SEO)->insert($seo);
            return true;
        }
    }

    /**
     * 通过分类Id更新分类
     * @param $category
     * @param $seo
     * @param $id
     * @return bool
     */
    public function updateCategoryById($category,$seo,$id){
        $result = $this->save($category,['id'=>$id]);
        if($result === false){
            return $result;
        }else{
            Db::name(self::TABLE_SEO)->where(['category_id'=>$id])->update($seo);
            return true;
        }
    }

}