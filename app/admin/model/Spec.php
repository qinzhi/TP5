<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/27
 * Time: 14:40
 */
namespace app\admin\Model;

use traits\model\SoftDelete;

class Spec extends Common{

    use SoftDelete;
    protected static $deleteTime = 'delete_time';

    protected $insert = ['create_time','update_time'];

    protected $update = ['update_time'];

    public function setCreateTimeAttr(){
        return time();
    }

    public function setUpdateTimeAttr(){
        return time();
    }

    public function getSpecById($id,$fields='*'){
        return $this->field($fields)->find($id);
    }

}