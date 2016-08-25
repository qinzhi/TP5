<?php
/**
 * 表情包
 */
namespace app\common\tools;

class Emotions
{
    public function QQ($content)
    {
        if (strstr($content, "/:")) {
            if (preg_match("/\/::\)/", $content)) {
                $content = "笑话";
            } elseif (preg_match("/\/::~/", $content)) {
                $content = "撇嘴";
            } elseif (preg_match("/\/::B/", $content)) {
                $content = "色";
            } elseif (preg_match("/\/:,@f/", $content)) {
                $content = "奋斗";
            } elseif (preg_match("/\/:heart/", $content)) {
                $content = "爱心";
            } elseif (preg_match("/\/:showlove/", $content)) {
                $content = "嘴唇";
            } elseif (preg_match("/\/:cake/", $content)) {
                $content = "蛋糕";
            } elseif (preg_match("/\/:gift/", $content)) {
                $content = "礼物";
            } elseif (preg_match("/\/:handclap/", $content)) {
                $content = "鼓掌";
            } elseif (preg_match("/\/:rose/", $content)) {
                $content = "玫瑰";
            } elseif (preg_match("/\/:kiss/", $content)) {
                $content = "献吻";
            } elseif (preg_match("/\/:love/", $content)) {
                $content = "爱情";
            } elseif (preg_match("/\/:ok/", $content)) {
                $content = "OK";
            } elseif (preg_match("/\/:lvu/", $content)) {
                $content = "爱你";
            } elseif (preg_match("/\/:jj/", $content)) {
                $content = "勾引";
            } elseif (preg_match("/\/:@\)/", $content)) {
                $content = "抱拳";
            } elseif (preg_match("/\/:share/", $content)) {
                $content = "握手";
            } elseif (preg_match("/\/:hug/", $content)) {
                $content = "拥抱";
            } elseif (preg_match("/\/::\-O/", $content)) {
                $content = "哈欠";
            } elseif (preg_match("/\/:xx/", $content)) {
                $content = "敲打";
            } elseif (preg_match("/\/:\-\-b/", $content)) {
                $content = "冷汗";
            } elseif (preg_match("/\/::X/", $content)) {
                $content = "闭嘴";
            } elseif (preg_match("/\/:no/", $content)) {
                $content = "NO";
            } elseif (preg_match("/\/::@/", $content)) {
                $content = "发怒";
            } elseif (preg_match("/\/::\(/", $content)) {
                $content = "难过";
            } elseif (preg_match("/\/::Q/", $content)) {
                $content = "抓狂";
            } elseif (preg_match("/\/::T/", $content)) {
                $content = "吐";
            } elseif (preg_match("/\/::d/", $content)) {
                $content = "白眼";
            } elseif (preg_match("/\/::!/", $content)) {
                $content = "惊恐";
            } elseif (preg_match("/\/::L/", $content)) {
                $content = "流汗";
            } elseif (preg_match("/\/::\-S/", $content)) {
                $content = "咒骂";
            } elseif (preg_match("/\/:,@@/", $content)) {
                $content = "晕";
            } elseif (preg_match("/\/::8/", $content)) {
                $content = "疯了";
            } elseif (preg_match("/\/:,@!/", $content)) {
                $content = "衰";
            } elseif (preg_match("/\/:!!!/", $content)) {
                $content = "骷髅";
            } elseif (preg_match("/\/:dig/", $content)) {
                $content = "抠鼻";
            } elseif (preg_match("/\/:pd/", $content)) {
                $content = "菜刀";
            } elseif (preg_match("/\/:pig/", $content)) {
                $content = "猪头";
            } elseif (preg_match("/\/:fade/", $content)) {
                $content = "凋谢";
            } elseif (preg_match("/\/:break/", $content)) {
                $content = "心碎";
            } elseif (preg_match("/\/:li/", $content)) {
                $content = "闪电";
            } elseif (preg_match("/\/:bome/", $content)) {
                $content = "炸弹";
            } elseif (preg_match("/\/:kn/", $content)) {
                $content = "刀";
            } elseif (preg_match("/\/:shit/", $content)) {
                $content = "便便";
            } elseif (preg_match("/\/::\+/", $content)) {
                $content = "酷";
            } elseif (preg_match("/\/:,@o/", $content)) {
                $content = "傲慢";
            } elseif (preg_match("/\/:X-\)/", $content)) {
                $content = "阴险";
            } elseif (preg_match("/\/:v/", $content)) {
                $content = "胜利";
            } elseif (preg_match("/\/:turn/", $content)) {
                $content = "回头";
            } elseif (preg_match("/\/:ladybug/", $content)) {
                $content = "瓢虫";
            } elseif (preg_match("/\/:,@x/", $content)) {
                $content = "嘘";
            } elseif (preg_match("/\/::,@/", $content)) {
                $content = "悠闲";
            } elseif (preg_match("/\/:8-\)/", $content)) {
                $content = "得意";
            } elseif (preg_match("/\/:#-0/", $content)) {
                $content = "激动";
            } elseif (preg_match("/\/:kotow/", $content)) {
                $content = "磕头";
            } elseif (preg_match("/\/:@x/", $content)) {
                $content = "吓";
            } elseif (preg_match("/\/:P-\(/", $content)) {
                $content = "委屈";
            } elseif (preg_match("/\/:B-\)/", $content)) {
                $content = "坏笑";
            } elseif (preg_match("/\/:&-\(/", $content)) {
                $content = "糗大了";
            } elseif (preg_match("/\/:\?/", $content)) {
                $content = "疑问";
            } elseif (preg_match("/\/::$/", $content)) {
                $content = "害羞";
            } elseif (preg_match("/\/::P/", $content)) {
                $content = "调皮";
            } elseif (preg_match("/\/::D/", $content)) {
                $content = "呲牙";
            } elseif (preg_match("/\/::O/", $content)) {
                $content = "惊讶";
            } elseif (preg_match("/\/:,@-D/", $content)) {
                $content = "愉快";
            } elseif (preg_match("/\/:,@P/", $content)) {
                $content = "偷笑";
            } elseif (preg_match("/\/::</", $content)) {
                $content = "流泪";
            } elseif (preg_match("/\/:weak/", $content)) {
                $content = "弱";
            } elseif (preg_match("/\/:<@/", $content)) {
                $content = "左哼哼";
            } elseif (preg_match("/\/:@>/", $content)) {
                $content = "右哼哼";
            } elseif (preg_match("/\/:wipe/", $content)) {
                $content = "擦汗";
            } elseif (preg_match("/\/:@@/", $content)) {
                $content = "拳头";
            } elseif (preg_match("/\/:bad/", $content)) {
                $content = "差劲";
            } elseif (preg_match("/\/:shake/", $content)) {
                $content = "发抖";
            } elseif (preg_match("/\/:moon/", $content)) {
                $content = "月亮";
            } elseif (preg_match("/\/::Z/", $content)) {
                $content = "睡";
            } elseif (preg_match("/\/:bye/", $content)) {
                $content = "再见";
            } elseif (preg_match("/\/:beer/", $content)) {
                $content = "啤酒";
            } elseif (preg_match("/\/::g/", $content)) {
                $content = "饥饿";
            } elseif (preg_match("/\/:eat/", $content)) {
                $content = "吃饭";
            } elseif (preg_match("/\/:coffee/", $content)) {
                $content = "咖啡";
            } elseif (preg_match("/\/:sun/", $content)) {
                $content = "太阳";
            } elseif (preg_match("/\/:hiphot/", $content)) {
                $content = "街舞";
            } elseif (preg_match("/\/:footb/", $content)) {
                $content = "足球";
            } elseif (preg_match("/\/:oo/", $content)) {
                $content = "乒乓";
            } elseif (preg_match("/\/:basketb/", $content)) {
                $content = "篮球";
            } elseif (preg_match("/\/:jump/", $content)) {
                $content = "跳跳";
            } elseif (preg_match("/\/:circle/", $content)) {
                $content = "转圈";
            } elseif (preg_match("/\/:skip/", $content)) {
                $content = "跳绳";
            } elseif (preg_match("/\/:<&/", $content)) {
                $content = "左太极";
            } elseif (preg_match("/\/:&>/", $content)) {
                $content = "右太极";
            } elseif (preg_match("/\/:strong/", $content)) {
                $content = "强";
            } elseif (preg_match("/\/::>/", $content)) {
                $content = "憨笑";
            } elseif (preg_match("/\/:<L>/", $content)) {
                $content = "飞吻";
            } elseif (preg_match("/\/::-\|/", $content)) {
                $content = "尴尬";
            } elseif (preg_match("/\/:oY/", $content)) {
                $content = "投降";
            } elseif (preg_match("/\/:>-\|/", $content)) {
                $content = "鄙视";
            } elseif (preg_match("/\/::\|/", $content)) {
                $content = "发呆";
            } elseif (preg_match("/\/:\<W\>/", $content)) {
                $content = "西瓜";
            } elseif (preg_match("/\/:\|\-\)/", $content)) {
                $content = "困";
            } elseif (preg_match("/\/:/", $content)) {
                $content = "怄火";
            }
        }
        return $content;
    }

    public static function get_qq()
    {
        return array(
            array('title' => '微笑', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/0.gif'),
            array('title' => '撇嘴', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/1.gif'),
            array('title' => '色', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/2.gif'),
            array('title' => '发呆', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/3.gif'),
            array('title' => '得意', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/4.gif'),
            array('title' => '流泪', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/5.gif'),
            array('title' => '害羞', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/6.gif'),
            array('title' => '闭嘴', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/7.gif'),
            array('title' => '睡', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/8.gif'),
            array('title' => '大哭', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/9.gif'),
            array('title' => '尴尬', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/10.gif'),
            array('title' => '发怒', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/11.gif'),
            array('title' => '调皮', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/12.gif'),
            array('title' => '呲牙', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/13.gif'),
            array('title' => '惊讶', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/14.gif'),
            array('title' => '难过', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/15.gif'),
            array('title' => '酷', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/16.gif'),
            array('title' => '冷汗', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/17.gif'),
            array('title' => '抓狂', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/18.gif'),
            array('title' => '吐', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/19.gif'),
            array('title' => '偷笑', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/20.gif'),
            array('title' => '可爱', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/21.gif'),
            array('title' => '白眼', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/22.gif'),
            array('title' => '傲慢', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/23.gif'),
            array('title' => '饥饿', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/24.gif'),
            array('title' => '困', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/25.gif'),
            array('title' => '惊恐', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/26.gif'),
            array('title' => '流汗', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/27.gif'),
            array('title' => '憨笑', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/28.gif'),
            array('title' => '大兵', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/29.gif'),
            array('title' => '奋斗', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/30.gif'),
            array('title' => '咒骂', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/31.gif'),
            array('title' => '疑问', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/32.gif'),
            array('title' => '嘘', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/33.gif'),
            array('title' => '晕', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/34.gif'),
            array('title' => '折磨', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/35.gif'),
            array('title' => '衰', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/36.gif'),
            array('title' => '骷髅', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/37.gif'),
            array('title' => '敲打', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/38.gif'),
            array('title' => '再见', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/39.gif'),
            array('title' => '擦汗', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/40.gif'),
            array('title' => '抠鼻', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/41.gif'),
            array('title' => '鼓掌', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/42.gif'),
            array('title' => '糗大了', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/43.gif'),
            array('title' => '坏笑', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/44.gif'),
            array('title' => '左哼哼', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/45.gif'),
            array('title' => '右哼哼', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/46.gif'),
            array('title' => '哈欠', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/47.gif'),
            array('title' => '鄙视', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/48.gif'),
            array('title' => '委屈', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/49.gif'),
            array('title' => '快哭了', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/50.gif'),
            array('title' => '阴险', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/51.gif'),
            array('title' => '亲亲', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/52.gif'),
            array('title' => '吓', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/53.gif'),
            array('title' => '可怜', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/54.gif'),
            array('title' => '菜刀', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/55.gif'),
            array('title' => '西瓜', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/56.gif'),
            array('title' => '啤酒', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/57.gif'),
            array('title' => '篮球', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/58.gif'),
            array('title' => '乒乓', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/59.gif'),
            array('title' => '咖啡', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/60.gif'),
            array('title' => '饭', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/61.gif'),
            array('title' => '猪头', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/62.gif'),
            array('title' => '玫瑰', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/63.gif'),
            array('title' => '凋谢', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/64.gif'),
            array('title' => '示爱', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/65.gif'),
            array('title' => '爱心', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/66.gif'),
            array('title' => '心碎', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/67.gif'),
            array('title' => '蛋糕', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/68.gif'),
            array('title' => '闪电', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/69.gif'),
            array('title' => '炸弹', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/70.gif'),
            array('title' => '刀', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/71.gif'),
            array('title' => '足球', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/72.gif'),
            array('title' => '瓢虫', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/73.gif'),
            array('title' => '便便', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/74.gif'),
            array('title' => '月亮', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/75.gif'),
            array('title' => '太阳', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/76.gif'),
            array('title' => '礼物', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/77.gif'),
            array('title' => '拥抱', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/78.gif'),
            array('title' => '强', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/79.gif'),
            array('title' => '弱', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/80.gif'),
            array('title' => '握手', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/81.gif'),
            array('title' => '胜利', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/82.gif'),
            array('title' => '抱拳', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/83.gif'),
            array('title' => '勾引', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/84.gif'),
            array('title' => '拳头', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/85.gif'),
            array('title' => '差劲', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/86.gif'),
            array('title' => '爱你', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/87.gif'),
            array('title' => 'NO', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/88.gif'),
            array('title' => 'OK', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/89.gif'),
            array('title' => '爱情', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/90.gif'),
            array('title' => '飞吻', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/91.gif'),
            array('title' => '跳跳', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/92.gif'),
            array('title' => '发抖', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/93.gif'),
            array('title' => '怄火', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/94.gif'),
            array('title' => '转圈', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/95.gif'),
            array('title' => '磕头', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/96.gif'),
            array('title' => '回头', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/97.gif'),
            array('title' => '跳绳', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/98.gif'),
            array('title' => '挥手', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/99.gif'),
            array('title' => '激动', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/100.gif'),
            array('title' => '街舞', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/101.gif'),
            array('title' => '献吻', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/102.gif'),
            array('title' => '左太极', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/103.gif'),
            array('title' => '右太极', 'gifurl' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/104.gif'),
        );
    }

}

?>