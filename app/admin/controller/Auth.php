<?php

namespace app\admin\controller;

use app\admin\model\AuthRole;
use \app\common\tools\Tree;
use think\Loader;
use think\Request;

/**
 * 权限管理控制器
 * Class AuthController
 * Author Qinzhi
 */
class Auth extends Admin {

    public $authRole;
    
    public function __construct(){
        parent::__construct();
        $this->authRole = Loader::model('AuthRole');
    }

    public function index(){
        $authLists = $this->authRole->getAuthList();
        $tree = new Tree($authLists);
        $auth = $tree->leaf();
        $this->assign('auth',$auth);
        $this->assign('tree',$this->authRole->formatTree($authLists));
        return $this->fetch();
    }

    public function getAuth($id){
        return AuthRole::get($id);
    }

    public function add(){
        if(Request::instance()->isPost()){
            $pid = Request::instance()->request('p_id','','intval');
            $pauth = $this->authRole->getAuthByPid($pid);
            if($pid == 0) $level = 0;
            else{
                $auth = $this->authRole->getAuthByPid($pid);
                $level = $auth['level'] + 1;
            }
            $sort = !empty($pauth) ? ($pauth['sort'] + 1) : 0;
            $data = [
                'pid' => $pid,
                'level' => $level,
                'module' => Request::instance()->module(),
                'type' => Request::instance()->request('type'),
                'name' => Request::instance()->request('name','','trim'),
                'site' => Request::instance()->request('site','','trim'),
                'sort' => $sort,
            ];
            $insert_id = $this->authRole->save($data);
            if($insert_id === false){
                $this->error('权限添加失败','Auth/index');
                return;
            }
        }
        $this->redirect('Auth/index');
    }

    public function edit(){
        if(Request::instance()->isAjax()){
            parse_str(urldecode(Request::instance()->request('params')),$params);
            $pid = $params['p_id'];
            $auth = $this->authRole->getAuthByPid($pid);
            if($pid == 0) $level = 0;
            else $level = $auth['level'] + 1;
            $data = [
                'id' => $params['id'],
                'pid' => $params['p_id'],
                'level' => $level,
                'module' => Request::instance()->module(),
                'type' => $params['type'],
                'name' => trim($params['name']),
                'site' => trim($params['site'])
            ];
            $result = $this->authRole->save($data);
            if($result){
                $result = ['code'=>1,'msg'=>'保存成功'];
            }else{
                $result = ['code'=>0,'msg'=>'保存失败'];
            }
        }else{
            $result = ['code'=>0,'msg'=>'异常提交'];
        }

        return $result;
    }

    public function move($action,$id){
        if(Request::instance()->isAjax()){
            $result = $this->authRole->move($id,$action);
            if($result){
                $result = ['code'=>1,'msg'=>'移动成功'];
            }else{
                $result = ['code'=>0,'msg'=>'移动失败'];
            }

        }else{
            $result = ['code'=>0,'msg'=>'异常提交'];
        }
        return $result;
    }

    public function del($id){
        if(Request::instance()->isAjax()){
            $auth = $this->authRole->getAuthByPid($id);
            if(!empty($auth)){
                $result = ['code'=>0,'msg'=>'不能直接删除上级模块'];
            }else{
                if($this->authRole->delete($id)){
                    $result = ['code'=>1,'msg'=>'删除成功'];
                }else{
                    $result = ['code'=>0,'msg'=>'删除失败'];
                }
            }
        }else{
            $result = ['code'=>0,'msg'=>'异常提交'];
        }
        return $result;
    }

}