{extend name="layout/base" /}
{block name="content"}
<div class="row no-margin">
    <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
        <div class="widget flat no-margin plugins_goods-">
            <div class="widget-header widget-fruiter">
                <form class="pull-left goods_list_top" method="get">
                    <select class="input-sm Lwidth120" name="search[status]">
                        <option value="">订单状态</option>
                        <option value="1">已取消</option>
                        <option value="1">未支付</option>
                        <option value="1">已支付</option>
                        <option value="0">已发货</option>
                        <option value="0">已完成</option>
                    </select>
                    <input name="search[keywords]" type="text" class="input-sm" placeholder="订单编号"/>
                    <button class="btn btn-success" id="search" type="submit">搜索</button>
                </form>
            </div><!--Widget Header-->
            <div class="widget-body no-padding">
                <table class="table table-hover table-middle">
                    <colgroup>
                        <col width="60px">
                        <!--<col width="130px">
                        <col width="80px">
                        <col width="120px">
                        <col width="120px">
                        <col width="120px">
                        <col width="70px">-->
                        <col>
                    </colgroup>
                    <thead class="bordered-success">
                    <tr role="row">
                        <th class="padding-left-16">选择</th>
                        <th>订单编号</th>
                        <th>支付金额</th>
                        <th>下单时间</th>
                        <th>支付时间</th>
                        <th>买家昵称</th>
                        <th>订单状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {volist name="orderList" id="vo"}
                    <tr>
                        <td class="padding-left-16">
                            <div class="checkbox checkbox-inline no-margin no-padding">
                                <label class="no-padding">
                                    <input type="checkbox" class="order_id" name="id[]" value="{$vo.id}" autocomplete="off">
                                    <span class="text"></span>
                                </label>
                            </div>
                        </td>
                        <td>{$vo.order_sn}</td>
                        <td>{$vo.pay_price}</td>
                        <td>{$vo.add_time|date='Y-m-d H:i',###}</td>
                        <td><?php if($vo['pay_time']) echo date('Y-m-d H:i',$vo['pay_time']);?></td>
                        <td>{$vo.nickname}</td>
                        <td>{$vo.status_text}</td>
                        <td>
                            <a class="btn btn-default btn-sm purple btn-edit" href="{:url('order/detail',['order_sn'=>$vo['order_sn']])}" title="订单详情">订单详情</a>
                        </td>
                    </tr>
                    {/volist}
                    </tbody>
                </table>
                <div class="row DTTTFooter padding-left-16">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="searchable_info" role="alert" aria-live="polite"
                             aria-relevant="all">当前第1页/共1页
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_bootstrap" id="searchable_paginate">
                            {/*$orderList->render()*/}
                        </div>
                    </div>
                </div>
            </div><!--Widget Body-->
        </div><!--Widget-->
    </div>
</div>
{/block}
{block name="js"}
<script type="application/javascript">
    $(function () {

    });
</script>