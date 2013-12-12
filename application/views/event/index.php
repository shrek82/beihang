<!--left -->
<div id="main_left">
    <p><img src="/static/images/event_title.gif"></p>
    <div class="event_title">
        <?php $etype = Kohana::config('icon.etype'); ?>
        <div style="float: left">
            <select name="etype" onchange="location.href='?type='+this.value">
                <option value="">全部活动</option>
                <?php foreach ($etype['icons'] as $name => $ico): ?>
                    <option value="<?= urlencode($name) ?>" <?= $type == $name ? 'selected' : '' ?>><?= $name ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="float:right;padding:0px 15px">
            <input onclick="location.href='/event/form'" type="button" value="我要发起活动" class="button_blue"  />
        </div>

    </div>

    <div class="blue_tab" style="margin: 15px 20px">
        <ul>
            <li><a href="<?= URL::site('event') ?>"    class="<?= !$list ? 'cur' : '' ?>" style="width:80px">所有活动</a></li>
            <?php if($_UID>0):?>
            <li><a href="<?= URL::site('event?list=aa') ?>"    class="<?= $list=='aa' ? 'cur' : '' ?>" style="width:80px">我加入的组织</a></li>
            <li><a href="<?= URL::site('event?list=joined') ?>"    class="<?= $list=='joined' ? 'cur' : '' ?>" style="width:80px">我参加过的</a></li>
            <?php endif;?>
            <li><a href="<?= URL::site('event/tags') ?>"   style="width:60px">标签</a></li>
        </ul>
    </div>

    <!--活动列表开始-->
    <div id="event_list">
        <?php if (!$events): ?>
            <div class="nodata" style="padding:15px">很抱歉，暂时还没有任何活动。</div>
        <?php endif; ?>

        <?php foreach ($events as $i => $e): ?>
            <div class="event_one <?php if (0 === ($i + 1) % 2) {
            echo 'bg2';
        } ?>" <?php if ($e['votes'] > 0): ?>style="height:120px"<?php endif; ?>>
                <p class="p1">
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


                </p>
                <p class="p2">
                    <a href="<?=  Db_Event::getLink($e['id'],$e['aa_id'],$e['club_id'])?>" class="deep_blue_14"  target="_blank" style="<?=$e['is_fixed']?'color:#f30':'';?>"><?= Text::limit_chars($e['title'], 22, '...') ?></a> <?php if ($e['is_vcert']): ?><span style="vertical-align: middle">&nbsp;&nbsp;<img src="/static/images/ico_vcert.png" id="ico_vcert" title="校友会认证活动" ></span><?php endif; ?><br />
                    发起：<?php if ($e['aa_id'] == '-1'): ?><a href="<?= URL::site('aa') ?>"><?=$_CONFIG->base['orgname']?></a><?php else: ?><a href="<?= URL::site('aa_home?id=' . $e['aa_id']) ?>"><?= $e['Aa']['name'] ?></a> &raquo;<a href="<?= Db_Event::getLink($e['id'],$e['aa_id'],$e['club_id']) ?>"> <?= $e['Club']['name'] ? $e['Club']['name'] . '&raquo;' : '' ?></a><?php endif; ?><br />
                    地址：<?= Text::limit_chars($e['address'], 28) ?><br />
                    参与：<a href="<?= URL::site('event/view?id=' . $e['id'] . '&tab=event_slist') ?>" title="点击浏览名单"><?= $e['sign_num'] ?></a>人
                    讨论：<a href="<?= URL::site('event/view?id=' . $e['id'] . '&tab=event_comment') ?>" title="点击浏览讨论"><?= $e['cmt_num'] ?></a>条
    <?php if ($e['votes'] > 0): ?><br />体验：<?php for ($i = 1; $i <= $e['score']; $i++): ?><img src="/static/images/knewstuff.png" style="margin-right:2px;vertical-align: middle"><?php endfor; ?><?php for ($i = 1; $i <= 5 - $e['score']; $i++): ?><img src="/static/images/knewstuff2.png" style="margin-right:2px;vertical-align: middle"><?php endfor; ?><?php endif; ?>
                </p>
                <p class="p3"><?= date('Y-n-d', strtotime($e['start'])); ?><br>
                    <span style="color:#333"><?=Date::getWeek($e['start']);?></span>
                </p>
                <p class="p4">
                    <?php if (time() >= strtotime($e['start']) AND time() <= strtotime($e['finish'])): ?>
                        <span style="color:#4D7E05">进行中</span>
                    <?php elseif (time() <= strtotime($e['start'])): ?>
                        <span style="color:#4D7E05"><?= Date::span_str(strtotime($e['start'])) ?>后</span>
                    <?php else: ?>
                        <span style="color:#999">结束</span>
    <?php endif; ?>
                </p>

            </div>
<?php endforeach; ?>
        <div style="margin:15px 0"><?= $pager ?></div>
    </div>
    <!--//活动列表结束-->
</div>
<!--//left -->

<!--right -->
<div id="sidebar_right">
    <div style="">
        <p class="sidebar_title">快速查找</p>
        <div class="sidebar_box">
            <ul class="sidebar_menus">
                <li><a href="<?= URL::site('event?list=week') ?>" <?= $list == 'week' ? 'class="cur"' : ''; ?>>未来7天</a></li>
                <li><a href="<?= URL::site('event?list=today') ?>" <?= $list == 'today' ? 'class="cur"' : ''; ?>>今天</a></li>
                <li><a href="<?= URL::site('event?list=weeken') ?>" <?= $list == 'weeken' ? 'class="cur"' : ''; ?>>周末</a></li>
                <li><a href="<?= URL::site('event') ?>" <?= !$list ? 'class="cur"' : ''; ?>>所有活动</a></li>
            </ul>
        </div></div>
    <p class="sidebar_title" style="border-top-width:0">主题活动</p>
    <div class="sidebar_box">
        <?php if (!$static): ?>
            <span class="nodata">暂无主题活动</span>
            <?php else: ?>
            <ul class="sidebar_menus">
                    <?php foreach ($static as $e): ?>
                    <li>
                    <?php if (empty($e['redirect'])): ?><a href="<?= URL::site('event/static?id=' . $e['id']) ?>" ><?php else: ?><a href="<?= $e['redirect'] ?>" target="_blank" title="<?= $e['title'] ?>"><?php endif; ?><?= Text::limit_chars($e['title'], 16, '..') ?></a>
                    </li>
            <?php endforeach; ?>
            </ul>
<?php endif; ?>
    </div>

    <p class="sidebar_title" style="border-top-width:0">热门标签</p>
    <div class="sidebar_box">
        <?php foreach ($tags as $tag): ?>
            <a href="<?= URL::site('event?tag=' . urlencode($tag['name'])) ?>" ><?= $tag['name'] ?></a>&nbsp;&nbsp;
<?php endforeach; ?>
        <p class="more"><a href="<?= URL::site('event/tags') ?>">更多</a></p>
    </div>
    <p class="sidebar_title" style="border-top-width:0">最新活动掠影</p>
    <div class="sidebar_box">

        <?php if (!$event_pic): ?>
            <div class="nodata">暂无活动照片</div>
            <?php else: ?>
            <ul class="sidebar_photo img_border">
                <?php foreach ($event_pic as $p): ?>
                    <li><a href="<?= URL::site('album/picIndex?id=' . $p['id']) ?>" title="<?=$p['name']?>"  target="_blank"><img src="<?= URL::base() . $p['img_path'] ?>" width="80" height="60" /></a></li>
            <?php endforeach; ?>
            </ul>
<?php endif; ?>
        <div class="clear"></div>
    </div>

</div>
<!--//right -->

<div class="clear"></div>

