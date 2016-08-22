<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/27
 * Time: 14:40
 */
namespace app\admin\Model;

use think\Config;
use think\Request;

class AuthRole extends Common{

    public $breadcrumbs = array();

    public static $order = 'sort asc'; //排序

    public function move($id,$action){
        $auth = self::get($id);
        $authList = $this->get_auths_by_pid($auth['pid']);
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
                    $status1 = $this->where(array('id'=>$refer['id']))->setField(array('sort'=>$auth['sort']));
                    $status2 = $this->where(array('id'=>$auth['id']))->setField(array('sort'=>$tmp));
                    if($status1 && $status2){
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }
    }

    public function format_tree($AuthLists,$is_json = true){
        $tree[] = array('id'=>'','pid'=>0,'level'=>0,'name'=>'根节点');
        foreach($AuthLists as $auth){
            if($auth['level'] < 2){
                $tree[] = array(
                    'id' => $auth['id'],
                    'pId' => $auth['pid'],
                    'name' => $auth['name'],
                    'level' => $auth['level'],
                    'open' => true,
                );
            }
        }
        return $is_json?json_encode($tree):$tree;
    }

    public function get_menu(){
        return $this->where('type',2)->order($this::$order)->select();
    }

    public function get_breadcrumbs($id = ''){
        if(empty($this->breadcrumbs)){
            $request = Request::instance();
            $urlCase    =   Config::get('URL_CASE_INSENSITIVE');
            $controller_name = $request->controller();
            $action_name = $request->action();
            if($urlCase){
                $controller_name = str_replace('_',' ',$controller_name);
                $controller_name = ucwords($controller_name);
                $controller_name = str_replace(' ','',$controller_name);;
            }
            $path = str_replace('\\','/',$controller_name . DS . $action_name);
            $breadcrumbs = $this->where(array('site'=>$path))->find();
        }else{
            $breadcrumbs = $this->where(array('id'=>$id))->find();
        }
        if(!empty($breadcrumbs)){
            $this->breadcrumbs[] = $breadcrumbs;
            if($breadcrumbs['pid'] != 0){
                $this->get_breadcrumbs($breadcrumbs['pid']);
            }
        }else{
            return;
        }
    }

    public function get_auth_by_pid($pid,$sort = ''){
        $sort = $sort ?: $this::$order;
        return $this->where(array('pid'=>$pid))->order($sort)->find();
    }

    public function get_auths_by_pid($pid){
        return $this->where(array('pid'=>$pid))->order($this::$order)->select();
    }

    public function get_auth_by_id($id){
        return $this->where(array('id'=>$id))->find();
    }

    public function get_auths(){
        return $this->order($this::$order)->select();
    }
}