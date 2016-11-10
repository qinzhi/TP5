{extend name="layout/base" /}
{block name="css"}
<link href="__CSS__/weixin.css" rel="stylesheet" />
{/block}
{block name="content"}
<div class="row no-margin">
    <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
        <div class="widget flat no-margin">
            <div class="widget-header widget-fruiter">
                <div class="pull-right">
                    <a class="btn btn-success" id="goods_save" href="javascript:void(0);">保 存</a>
                </div>
            </div><!--Widget Header-->
            <div class="widget-body plugins_news-">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-8">
                        <table class="table-form">
                            <colgroup>
                                <col width="150px"><col>
                            </colgroup>
                            <tbody>
                            <tr>
                                <th>标题：</th>
                                <td>
                                    <div class="form-group has-feedback no-margin">
                                        <input id="name" name="name" class="input-sm Lwidth400" type="text" pattern="required" maxlength="64">
                                        <span class="note control-label margin-left-10">*</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>摘要：</th>
                                <td>
                                    <div class="form-group has-feedback no-margin">
                                        <span class="input-icon icon-right Lwidth400">
                                            <textarea id="intro" name="intro" class="form-control" maxlength="120"></textarea>
                                            <i class="fa fa-rocket darkorange"></i>
                                        </span>
                                        <span class="note control-label margin-left-10">选填，如果不填写会默认抓取正文前54个字</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>作者：</th>
                                <td>
                                    <div class="form-group has-feedback no-margin">
                                        <input id="name" name="name" class="input-sm Lwidth400" type="text" pattern="required" maxlength="8">
                                        <span class="note control-label margin-left-10">*</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>图片封面：</th>
                                <td>
                                    <div class="form-group has-feedback no-margin">
                                        <div class="input-group input-group-sm Lwidth400">
                                            <input type="text" name="image" id="image" class="form-control" readonly>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-success">选择图片</button>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label class="no-padding">
                                        <input type="checkbox" name="source_url_chk" id="source_url_chk" value="1">
                                        <span class="text" style="font-weight: bold">原文链接：</span>
                                    </label>
                                </th>
                                <td>
                                    <div class="checkbox checkbox-inline no-margin no-padding">
                                        <input type="text" name="source_url" id="source_url" class="form-control Lwidth400" disabled>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>正文：</th>
                                <td class="no-padding-top no-padding-bottom"><?php create_editor('detail');?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-6 col-md-4">
                        <div class="news-preview">
                            <ul class="con-list">
                                <li class="con-item con-item-first">
                                    <span class="title">11111111</span>
                                    <div class="imgpic">
                                        <img class="pic_cover" src="http://www.sogreatwell.com/templates/default/images/pic.jpg">
                                    </div>
                                </li>
                                <li class="con-item">
                                    <span class="title">标题</span>
                                    <div class="imgpic">
                                        <img class="pic_cover" src="http://www.sogreatwell.com/templates/default/images/pic.jpg">
                                    </div>
                                    <div class="news-edit">
                                        <div class="news-edit_op">
                                            <a class="edit" title="编辑"><i class="fa fa-pencil"></i></a>
                                            <a class="delete" title="删除"><i class="fa fa-trash-o"></i></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="con-item con-item-add">
                                    <span class="add_news">+</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!--Widget Body-->
        </div><!--Widget-->
    </div>
</div>
{/block}
{block name="js"}
<script>
    $(function () {
        $('#source_url_chk').change(function () {
            if(this.checked){
                $('#source_url').attr('disabled',false);
            }else{
                $('#source_url').val('').attr('disabled',true);
            }
        });
    });
</script>
{/block}