{extend name="layout/base" /}
{block name="quote-css"}
<link href="__COMMON__/css/timeline.css" rel="stylesheet" type="text/css">
<link href="__CSS__/member.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
<div class="page page-order_create" id="page-integral_index">
    <div class="bar bar-nav bar-standard flex">
        <div class="flex-1 integral">积分: <span>999</span></div>
        <div><a class="button button-fill btn-exchange" href="javascript:;">积分兑换</a></div>
    </div>
    <div class="content">
        <div class="content-timeline">
            <ul class="timeline">
                <li class="timeline-node">
                    <h4>2016-11</h4>
                </li>
                <li>
                    <div class="timeline-datetime">
                        <span class="timeline-time">今天 8:19</span>
                    </div>
                    <div class="timeline-badge blue">
                        <i class="fa fa-tag"></i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-body expend">
                            <p class="text-center">兑换100积分</p>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <div class="timeline-datetime">
                        <span class="timeline-time">今天 3:10</span>
                    </div>
                    <div class="timeline-badge darkorange">
                        <i class="fa fa-map-marker font-120"></i>
                    </div>
                    <div class="timeline-panel bordered-orange">
                        <div class="timeline-body income">
                            <p class="text-center">购物返利100积分</p>
                        </div>
                    </div>
                </li>
                <li class="timeline-node">
                    <h4>没有数据了</h4>
                </li>
            </ul>
        </div>
    </div>
</div>
{/block}