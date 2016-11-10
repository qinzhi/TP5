<div class="widget widget-wechat widget-wechat_news flat">
    <div class="widget-header">
        <div class="widget-buttons">
            <a class="label label-success" href="{:url('weixin/lib_add_news')}" target="_blank"><i class="fa fa-plus"></i> 新建图文消息</a>
        </div>
    </div>
    <div class="widget-body">
        {volist name="itemList" id="vo"}
            <div class="news-list">
                <div class="news-item">
                    <div class="news-content">
                        <div class="news-con-info">
                            <em class="news_date">昨天 15:16</em>
                        </div>
                        <div class="news-con-list">
                            <h4 class="news_title">测试</h4>
                            <div class="news_thumb" style="background-image:url('{$vo['content']['news_item'][0]['thumb_url']}')"> </div>
                            <p class="news_abstract">摘要</p>
                        </div>
                    </div>
                    <div class="news_mask">
                        <span>已选择</span>
                    </div>
                </div>
            </div>
        {/volist}
    </div>
</div>