<?php
/**
 * 微信
 */
namespace app\weixin\controller;

use app\common\model\Member;
use app\common\service\Wechat;
use think\Controller;
use think\Cookie;
use think\Request;
use think\Session;
use think\Url;

class Weixin extends Controller
{

    public $openid = '';

    public $member;

    public function _initialize()
    {
        //授权获取用户openid
        if(Session::has('openid')){
            $this->openid = Session::get('openid');
        }elseif(Cookie::has('openid')){
            $this->openid = Cookie::get('openid');
            Session::set('openid',$this->openid);
        }else{
            $wechatService = new Wechat();
            if(Request::instance()->has('code')){
                $result = $wechatService->getOauthAccessToken();
                $this->openid = $result['openid'];
                if(empty($this->openid)){
                    header("Content-type: text/html; charset=utf-8");
                    die('获取用户基本信息失败!');
                }
                $user_token = $result['access_token'];//用户令牌

                $memberModel = new Member();
                $this->member = $member = $memberModel::getByWeixinopenid($this->openid);

                $result = $wechatService->getOauthUserinfo($user_token,$this->openid);//拉取用户信息

                $this->member['name'] = str_replace(array("'","\\"),array(''),$result['nickname']);
                //$this->member['truename']  = $this->member['wechaname'] ;
                $this->member['sex']       = $result['sex'];
                $this->member['avatar']  = $result['headimgurl'];
                $this->member['weixinopenid']  = $result['openid'];
                $this->member['weixininfo'] = json_encode($result);
                if(empty($member)){//添加用户
                    $this->member['add_time']  = time();
                    $memberModel->data($this->member)->save();
                    $this->member['id'] = $memberModel->id;
                }else{//更新用户
                    $memberModel->update($this->member);
                }
                Session::set('openid',$this->openid);
                Cookie::set('openid',$this->openid,30 * 86400); //保存一年
            }else{
                $url = $wechatService->getOauthRedirect(getFullUrl());
                $this->redirect($url);
            }
        }

        if(!empty($this->openid)){

        }else{
            header("Content-type: text/html; charset=utf-8");
            die('用户openid不存在');
        }
    }

}
