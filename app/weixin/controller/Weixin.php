<?php
/**
 * 微信
 */
namespace app\weixin\controller;

use app\common\model\Cart;
use app\common\model\Member;
use app\common\service\Wechat;
use app\common\tools\Str;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Request;
use think\Session;
use think\Url;

class Weixin extends Controller
{

    public $openid;

    public $member;

    public $wechatService;

    public function _initialize()
    {

        $memberModel = new Member();

        $this->wechatService = new Wechat();
        //Session::delete('openid');
        //Cookie::delete('openid');
        //授权获取用户openid
        if(Session::has('openid')){
            $this->openid = Session::get('openid');
        }elseif(Cookie::has('openid')){
            $this->openid = Cookie::get('openid');
            Session::set('openid',$this->openid);
        }else{
            if(Request::instance()->has('code')){
                $result = $this->wechatService->getOauthAccessToken();
                $this->openid = $result['openid'];
                if(empty($this->openid)){
                    header("Content-type: text/html; charset=utf-8");
                    die('获取用户基本信息失败!');
                }
                $user_token = $result['access_token'];//用户令牌

                $this->member = $member = Db::name('member')->where('openid',$this->openid)->find();

                $result = $this->wechatService->getOauthUserinfo($user_token,$this->openid);//拉取用户信息

                $this->member['nickname'] = str_replace(array("'","\\"),array(''),$result['nickname']);
                //$this->member['truename']  = $this->member['wechaname'] ;
                $this->member['sex']       = $result['sex'];
                $this->member['avator']  = $result['headimgurl'];
                $this->member['openid']  = $result['openid'];
                $this->member['wxinfo'] = json_encode($result,JSON_UNESCAPED_UNICODE);
                if(empty($member)){//添加用户
                    $this->member['add_time']  = time();
                    $this->member['status'] = 1;
                    $memberModel->data($this->member)->save();
                    $this->member['id'] = $memberModel->id;
                }else{//更新用户
                    $memberModel->update($this->member);
                }
                Session::set('openid',$this->openid);
                Cookie::set('openid',$this->openid,30 * 86400); //保存一年
            }else{
                $url = $this->wechatService->getOauthRedirect(get_full_url());
                $this->redirect($url);
            }
        }

        if(!empty($this->openid)){
            if(empty($this->member)) $this->member = Db::name('member')->where('openid',$this->openid)->find();
        }else{
            header("Content-type: text/html; charset=utf-8");
            die('用户openid不存在');
        }

        if(empty($this->member)){
            Session::delete('openid');
            Cookie::delete('openid');
            self::_initialize();
            die('用户不存在');
        }elseif($this->member['status'] == 0){
            die('你的用户已被禁止访问');
        }

        $this->assign('openid',$this->openid);
        $this->assign('member',$this->member);
    }

    public static function getWeixinSign(){
        $wx = array(
            'appid' => config('weixin.app_id'),
            'timestamp' => time(),
            'nonceStr' => Str::getRandChar(8),
            'jsApiList' => ["scanQRCode","hideMenuItems"]
        );

        $wechatService = new Wechat();
        $ticket = $wechatService->getJsTicket();

        if(Request::instance()->has('url')){
            $url = urldecode(Request::instance()->request('url'));
        }else{
            $url = get_full_url();
        }

        $data = array(
            'noncestr' =>$wx['nonceStr'],
            'timestamp' => $wx['timestamp'],
            'jsapi_ticket' => $ticket,
            'url' => $url,
        );
        ksort($data);
        $data = urldecode(http_build_query($data));
        $wx['signature'] = sha1($data);//签名

        return $wx;
    }

    public function getCartNum(){
        $cartModel = new Cart();
        return $cartModel->getNum($this->member['id']);
    }

}
