<?php
namespace app\index\controller;

use app\common\service\Wechat;
use think\Config;
use think\Controller;
use think\Log;
use think\Request;

class Weixin extends Controller
{

    public $appid;//应用id

    public $appsecret;//应用秘钥

    public $token;//令牌

    public $request;

    public $openid;//微信用户id

    public function __construct()
    {
        parent::__construct();

        $this->appid = Config::get('weixin.app_id');
        $this->appsecret = Config::get('weixin.app_secret');
        $this->token = Config::get('weixin.token');

        $this->request = Request::instance();
    }

    public function index()
    {
        $wechatService = new Wechat();

        if($wechatService->valid(true) === false){
            //明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
            die('valid no success');
        };

        $type = $wechatService->getRev()->getRevType();

        $data = $wechatService->getRevData();
        Log::record('data: '. json_encode($data));

        switch($type) {
            case Wechat::MSGTYPE_TEXT:
                $content = $data['Content'];
                if($content == '签到'){
                    $wechatService->text('签到成功')->reply();
                    break;
                }
                $wechatService->text("hello, I'm wechat")->reply();
                break;
            case Wechat::MSGTYPE_EVENT:
                $eventKey = $data['EventKey'];
                if($eventKey == '签到'){
                    $wechatService->text('签到成功')->reply();
                }
                break;
            case Wechat::MSGTYPE_IMAGE:
                break;
            default:
                $wechatService->text("help info")->reply();
        }
        exit;
    }
}