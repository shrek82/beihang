<?php
/**
  +-----------------------------------------------------------------
 * 名称：俱乐部活动首页
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-11 下午4:10
  +-----------------------------------------------------------------
 */
?>
<?php
$etype = Kohana::config('icon.etype');
$week = array('0' => '星期日', '1' => '星期一', '2' => '星期二', '3' => '星期三', '4' => '星期四', '5' => '星期五', '6' => '星期六');
?>

<div id="main_left">
    <div style="text-align: right;padding: 10px">
        <input type="button" onclick="location.href='<?= URL::site('event/form?aa=' . $_CLUB['aa_id'].'&club='.$_ID) ?>'" value="+发起活动" class="button_blue"  />
    </div>


    <div class="blue_tab" style="margin:15px 20px; margin-top: -25px">
        <ul>
            <li><a href="<?= URL::site('aa_home/event?id=' . $_ID) ?>"    <?= !$join ? 'class="cur"' : ''; ?> style="width:80px">所有活动</a></li>
            <li><a href="<?= URL::site('aa_home/event?id=' . $_ID . '&join=yes') ?>" <?= $join ? 'class="cur"' : ''; ?>    style="width:80px">我参加的活动</a></li>
        </ul>
    </div>

    <?php if ($pager->total_items > 0): ?>
        <table id="bench_list" width="100%"  border="0"  cellpadding="0" cellspacing="0">

            <?php foreach ($events AS $key => $e): ?>
                <tr class="<?=(($key+1)%2)==0?'even_tr':'odd_tr';?>">
                    <td width="80" style="text-align:center">
                        <?php if ($e['custom_icon']): ?>
                            <img src="<?= $e['custom_icon'] ?>" style="width:60px;height:60px">
                        <?php elseif ($e['small_img_path']): ?>
                            <img src="<?= $e['small_img_path'] ?>" style="width:60px;height:60px">
                        <?php else: ?>
                            <?php if ($e['type']): ?>
                                <img src="<?= $etype['url'] . $etype['icons'][$e['type']] ?>" />
                            <?php else: ?>
                                <img src="<?= $etype['url'] . 'undefined.png' ?>" />
            <?php endif; ?>
        <?php endif; ?>
                    </td>
                    <td style=" line-height: 1.6em;padding:15px 0;width:430px">
                        <a href="<?= Db_Event::getLink($e['id'],$e['aa_id'],$e['club_id']) ?>" class="title" style="font-weight:bold;font-size:14px;<?= $e['is_club_fixed'] ? 'color:#f30':'';?>"><?= Text::limit_chars($e['title'], 25) ?></a>
                        <br />
                        地点：<?= $e['address'] ?><br>
                        时间：<?= date('Y年m月d日 H点i分', strtotime($e['start'])); ?><br>
                        参与：<a href="<?= URL::site('event/view?id=' . $e['id'] . '&tab=event_slist') ?>" title="点击浏览名单"><?= $e['sign_num'] ?></a>人
                        讨论：<a href="<?= URL::site('event/view?id=' . $e['id'] . '&tab=event_comment') ?>" title="点击浏览讨论"><?= $e['cmt_num'] ?></a>条
                    </td>
                    <td style="text-align:center; color: #444;width:100px">
                            <?= date('Y-n-d', strtotime($e['start'])); ?><br>
                        <span style="color:#333"><?php
                    $dateArr = explode("-", date('Y-n-d', strtotime($e['start'])));
                    echo $week[date("w", mktime(0, 0, 0, $dateArr[1], $dateArr[2], $dateArr[0]))];
                    ?></span>
                    </td>
                    <td class="quiet"  style="text-align: center">
                        <?php if (time() >= strtotime($e['start']) AND time() <= strtotime($e['finish'])): ?>
                            <span style="color:#4D7E05">进行中</span>
                        <?php elseif (time() <= strtotime($e['start'])): ?>
                            <span style="color:#4D7E05"><?= Date::span_str(strtotime($e['start'])) ?>后</span>
                        <?php else: ?>
                            <span style="color:#f60">已结束</span>
        <?php endif; ?>
                    </td>

                </tr>
        <?php endforeach; ?>
        </table>
        <?= $pager ?>
    <?php else: ?>
        <div class="nodata">暂时还没有活动</div>
<?php endif; ?>

</div>

<div id="main_right">
    <p class="column_tt">快速查找</p>

    <div class="aa_block">
        <ul class="aa_conlist">
            <li><a href="<?= URL::site('club_home/event?id=' . $_ID . '&list=week') ?>" <?= $list == 'week' ? 'class="cur"' : ''; ?>>未来7天</a></li>
            <li><a href="<?= URL::site('club_home/event?id=' . $_ID . '&list=today') ?>" <?= $list == 'today' ? 'class="cur"' : ''; ?>>今天</a></li>
            <li><a href="<?= URL::site('club_home/event?id=' . $_ID . '&list=weeken') ?>" <?= $list == 'weeken' ? 'class="cur"' : ''; ?>>周末</a></li>
            <li><a href="<?= URL::site('club_home/event?id=' . $_ID) ?>" <?= !$list ? 'class="cur"' : ''; ?>>所有活动</a></li>
        </ul>
    </div>

</div>