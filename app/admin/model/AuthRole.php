<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/27
 * Time: 14:40
 */
namespace app\admin\Model;

use think\Request;

class AuthRole extends Common{

    public $breadcrumbs = array();

    public static $order = 'sort asc'; //排序

    public function move($id,$action){
        $auth = self::get($id);
        $authList = $this->getAuthListByPid($auth['pid']);
        for($i=0,$len=count($authList);$i<$len;$i++){
            if($authList[$i]['id'] == $auth['id']){
                if($i == 0 && $action == 'up' ){//上移失败
                    return false;
                }elseif($i == $len-1 && $action == 'down'){//下移失败
                    return false;
                }else{
                    if($action == 'up'){
                        $refer = $authList[$i - 1];
                    }elseif($action == 'down'){
                        $refer = $authList[$i + 1];
                    }else{
                        return false;
                    }
                    $tmp = $refer['sort'];
                    $status1 = $this->where(['id'=>$refer['id']])->setField(array('sort'=>$auth['sort']));
                    $status2 = $this->where(['id'=>$auth['id']])->setField(array('sort'=>$tmp));
                    if($status1 && $status2){
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }
    }

    public function formatTree($lists){
        $tree[] = array('id'=>'','pid'=>0,'level'=>0,'name'=>'根节点');
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

    public function getMenu(){
        return $this->where('type',2)->order($this::$order)->select();
    }

    public function getBreadcrumbs($id = ''){
        if(empty($this->breadcrumbs)){
            $request = Request::instance();

            $controller_name = $request->controller();
            $controller_name = str_replace('_',' ',$controller_name);
            $controller_name = ucwords($controller_name);
            $controller_name = str_replace(' ','',$controller_name);
            $action_name = $request->action();

            $path = str_replace('\\','/',$controller_name . DS . $action_name);
            $breadcrumbs = $this->where(array('site'=>$path))->find();
        }else{
            $breadcrumbs = $this->where(array('id'=>$id))->find();
        }
        if(!empty($breadcrumbs)){
            $this->breadcrumbs[] = $breadcrumbs;
            if($breadcrumbs['pid'] != 0){
                $this->getBreadcrumbs($breadcrumbs['pid']);
            }
        }else{
            return false;
        }
    }

    public function getAuthByPid($pid){
        return $this->where(['pid'=>$pid])->find();
    }

    public function getAuthListByPid($pid){
        return $this->where(['pid'=>$pid])->order($this::$order)->select();
    }

    public function getAuthById($id){
        return $this->where(['id'=>$id])->find();
    }

    public function getAuthList(){
        return $this->order($this::$order)->select();
    }
}