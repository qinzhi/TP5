<?php
namespace app\index\controller;

use app\admin\model\RechargeCard;
use app\common\controller\Member;
use app\common\controller\Message;
use app\common\model\IntegralLog;
use app\common\service\Wechat;
use think\Config;
use think\Controller;
use think\Db;
use think\Loader;
use think\Log;
use think\Request;

class Weixin extends Controller
{

    public $appid;//应用id

    public $appsecret;//应用秘钥

    public $token;//令牌

    public $request;

    public $openid;

    public $wx_member;

    public $member;

    public $wechatService;

    /**
     * 粉丝关注送积分
     */
    const  SUBSCRIBE_INTEGRAL = 200;

    public function __construct()
    {
        parent::__construct();

        $this->appid = Config::get('weixin.app_id');
        $this->appsecret = Config::get('weixin.app_secret');
        $this->token = Config::get('weixin.token');

        $this->wechatService = new Wechat();
        $this->request = Request::instance();
    }

    public function index()
    {
        //$this->wechatService->valid();return;
        if($this->wechatService->valid(true) === false){
            //明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
            die('valid no success');
        };

        $type = $this->wechatService->getRev()->getRevType();

        $data = $this->wechatService->getRevData();
        Log::record('data: '. json_encode($data));

        $this->openid = $data['FromUserName'];

        $this->wx_member = $this->wechatService->getUserInfo($this->openid);

        $this->member = Db::name('member')->where('openid',$this->openid)->find();
        switch($type) {
            case Wechat::MSGTYPE_TEXT:
                $content = $data['Content'];
                if($content == '签到'){
                    //call_user_func_array(array($this,'checkin'),array($this->wechatService));
                }
                break;
            case Wechat::MSGTYPE_EVENT:
                $event = $data['Event'];

                $eventKey = $data['EventKey'];
                switch ($event){
                    case Wechat::EVENT_SCAN:     //扫描带参数二维码(用户已关注时的事件推送)
                        if(is_numeric($eventKey) === true){ //粉丝二维码(推荐用户)
                            $this->saveMember($this->wx_member);//保存用户
                        }else{

                        }
                        break;
                    case Wechat::EVENT_SUBSCRIBE:   //用户未关注时，进行关注后的事件推送
                        $this->saveMember($this->wx_member);//保存用户
                        break;
                    case Wechat::EVENT_UNSUBSCRIBE:     //用户取消关注
                        $this->unsubscribe();
                        break;
                    case Wechat::EVENT_MENU_CLICK:      //自定义菜单事件
                        break;
                    case Wechat::EVENT_MENU_VIEW:   //点击菜单跳转链接
                        break;
                }
                break;
            case Wechat::MSGTYPE_IMAGE:
                break;
            default:
                $this->wechatService->text("help info")->reply();
        }
        exit;
    }

    /**
     * 保存用户
     * @param $member
     * @param int $parent_id
     */
    public function saveMember($member,$parent_id = 0){
        $data = [
            'nickname' => $member['nickname'],
            'sex' => $member['sex'],
            'avator' => $member['headimgurl'],
            'is_subscribe' => $member['subscribe'],
            'subscribe_time' => $member['subscribe_time'],
            'openid' => $member['openid'],
            'wxinfo' => json_encode($member,JSON_UNESCAPED_UNICODE),
        ];

        if(!empty($this->member)){
            Db::name('member')
                ->where('id', $this->member['id'])
                ->update($data);
        }else{
            $data['add_time'] = time();
            if($parent_id > 0){
                $data['parent_id'] = $parent_id;
            }

            $member_id = Db::name('member')->insertGetId($data);

            $this->member = Db::name('member')->where('id',$member_id)->find();

        }

    }

    /**
     * 取消关注公众号
     */
    private function unsubscribe(){
        Db::name('member')->where('id',$this->member['id'])->setField('is_subscribe',0);
    }

}