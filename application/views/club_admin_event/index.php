<!-- aa_admin_event/index:_body -->
<div id="admin950">
    <?php
    $etype = Kohana::config('icon.etype');
    ?>
    <table border="0" width="100%">
        <tr>
            <td width="63%" style="border-bottom:0; text-align: right">
                <a href="<?= URL::site('admin_event/index') ?>" style="<?= empty($_GET) ? 'font-weight:bold' : '' ?>"></a>
            </td>
            <td width="37%" style="border-bottom:0;text-align:right; padding: 0px 5px">
                <form style="display:inline" action="" method="get">
                    <input type="text" name="q" value="<?= @$_GET['q'] ?>" class="input_text" style="width:200px" /><input type="submit" value="搜索"  class="button_blue"/>
                    <input type="hidden" name="id" value="<?= @$_ID ?>" />
                </form>
            </td>
        </tr>
    </table>

    <?php if ($pager->total_items > 0): ?>
        <table width="100%" id="bench_list" border="0"  cellpadding="0" cellspacing="0">
            <tr>
                <th colspan="2" style="text-align:left">基本信息</th>
                <th width="40" class="center">状态</th>
                <th width="60" class="center">App推荐</th>
                <th width="60" class="center">顶置</th>
                <th width="60" class="center">暂停</th>
                <th width="60" class="center">屏蔽</th>
            </tr>
            <?php foreach ($events AS $key => $e): ?>
                <tr class="<?= (($key + 1) % 2) == 0 ? 'even_tr' : 'odd_tr'; ?>">
                    <td width="50" style="padding:10px">
                        <?php $type_icon = $e['type'] ? $etype['icons'][$e['type']] : 'undefined.png'; ?>
                        <div style="height:48px; width:48px; background: #fff url(<?= $etype['url'] . $type_icon ?>) no-repeat center top;"></div>
                    </td>
                    <td style="height:100px;line-height: 1.6em">
                        <strong style="font-size: 1.1em">
                            <a href="<?= Db_Event::getLink($e['id'],$e['aa_id'],$e['club_id'])?>" style="font-weight:bold;font-size:14px;<?= $e['is_club_fixed'] ? 'color:#f30':'';?>"><?=Text::limit_chars($e['title'],25)?></a>
                        </strong><br />
                        发起：<?= $e['realname'] ?><br>
                        地址：<?= $e['address'] ?><br>
                        发布：<?= $e['publish_at'] ?>
                    </td>
                    <td class="quiet" width="150" style="text-align: center">
                        <?php if (time() >= strtotime($e['start']) AND time() <= strtotime($e['finish'])): ?>
                            <span style="color:#4D7E05">进行中</span>
                        <?php elseif (time() <= strtotime($e['start'])): ?>
                            <span style="color:#4D7E05"><?= Date::span_str(strtotime($e['start'])) ?>后</span>
                        <?php else: ?>
                            <span style="color:#f60">已结束</span>
                        <?php endif; ?>
                    </td>

                    <td class="center">
                        <input type="checkbox" onclick="setBool(<?= $e['id'] ?>,'is_recommended')" <?= $e['is_recommended'] == true ? 'checked' : '' ?> />
                    </td>

                    <td class="center">
                        <input type="checkbox" onclick="setBool(<?= $e['id'] ?>,'is_club_fixed')" <?= $e['is_club_fixed'] == true ? 'checked' : '' ?> />
                    </td>


                    <td class="center">
                        <input type="checkbox" onclick="setBool(<?= $e['id'] ?>,'is_suspend')" <?= $e['is_suspend'] == true ? 'checked' : '' ?> />
                    </td>
                    <td class="center">
                        <input type="checkbox" onclick="setBool(<?= $e['id'] ?>,'is_closed')" <?= $e['is_closed'] == true ? 'checked' : '' ?> />
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?= $pager ?>

<script type="text/javascript">
    function setBool(cid,field){
            new Request({
                type:'post',
                url: '<?= URL::site('club_admin_event/set?id=' . $_ID) ?>',
                data: 'cid='+cid+'&bool_field='+field
            }).send();
        }
</script>
    <? else: ?>
        <span class="nodata">暂时还没有任何活动</span>
    <?php endif; ?>
</div>