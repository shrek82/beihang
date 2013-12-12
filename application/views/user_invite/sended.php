<!-- user_invite/sended:_body -->
<!-- user_invite/index:_body -->
<div id="big_right">
        <div id="plugin_title">我的邀请</div>

        <div class="tab_gray" id="user_topbar" style="margin-top:10px">
                <ul>
                        <?php if ($_MASTER): ?><li><a href="<?= URL::site('user_invite/index') ?>" style="width:80px">收到的邀请</a></li><?php endif;?>
                        <li><a href="<?= URL::site('user_invite/sended') ?>"  style="width:80px" class="cur" >发出的邀请</a></li>
                        <?php if ($_MASTER): ?><li><a href="<?= URL::site('user_invite/generate') ?>"  style="width:80px" >创建邀请</a></li><?php endif;?>
                </ul>
        </div>

        <?php if ($invites): ?>
                <table width="100%" id="event_table">
                        <thead>
                                <tr>
                                        <td >邀请内容及方式</td>
                                        <td style="text-align:center">邀请校友</td>
                                        <td style="text-align:center">校友身份</td>
                                        <td style="text-align:center">邀请时间</td>
                                        <td style="text-align:center">状态</td>

                                </tr>
                        </thead>
                        <tbody>
                                <?php foreach ($invites as $i): ?>
                                        <tr>
                                                <td width="150" style="height:25px"><?= $i['title'] ?></td>
                                                <td style="text-align:center"><?php if($i['RUser']):?><a href="/user_home?id=<?= $i['RUser']['id']?>" style="color:#5A93B9"><?= $i['RUser']['realname'] ?></a><?php else:?><?=$i['realname']?><?php endif;?></td>
                                                <td style="text-align:center"><?php if($i['RUser']):?><span style="<?php if($i['RUser']['role']=='校友(已认证)') :?>color:#007219<?php endif;?>"><?= $i['RUser']['role'] ?></span><?php else:?>未知<?php endif;?></td>
                                                <td style="text-align:center"><?= $i['create_date'] ?></td>
                                                <td style="text-align:center"><?= $i['is_accept'] ?'<span style="color:#007219">已接受邀请</span>':'<span style="color:#eee">等待中</style>?';?></td>


                                        </tr>
                                <?php endforeach; ?>
                        </tbody>
                </table>
                <?= $pager ?>
        <?php else: ?>
                <span class="nodata">您暂时还没有邀请任何校友。</span>
        <?php endif; ?>

</div>
