<!-- user_invite/index:_body -->
<div id="big_right">
        <div id="plugin_title">我的邀请</div>

        <div class="tab_gray" id="user_topbar" style="margin-top:10px">
                <ul>
                        <li><a href="<?= URL::site('user_invite/index') ?>" class="cur" style="width:80px">收到的邀请</a></li>
                        <li><a href="<?= URL::site('user_invite/sended') ?>"  style="width:80px">发出的邀请</a></li>
                        <li><a href="<?= URL::site('user_invite/generate') ?>"  style="width:80px" >创建邀请</a></li>
                </ul>
        </div>

        <?php if ($invites): ?>
                <table width="100%" id="event_table">
                        <thead>
                                <tr>
                                        <td >邀请内容及方式</td>
                                        <td style="text-align:center">邀请人</td>
                                        <td style="text-align:center">邀请时间</td>
                                        <td style="text-align:center">状态</td>

                                </tr>
                        </thead>
                        <tbody>
                                <?php foreach ($invites as $i): ?>
                                        <tr>
                                                <td width="200" style="height:25px"><?= $i['title'] ?></td>
                                                <td style="text-align:center"><?= $i['realname'] ?></td>
                                                <td style="text-align:center"><?= $i['create_date'] ?></td>
                                                <td style="text-align:center"><?= $i['is_accept'] ?'<span style="color:#007219">已接受邀请</span>':'<span style="color:#eee">为接受</style>?';?></td>


                                        </tr>
                                <?php endforeach; ?>
                        </tbody>
                </table>
                <?= $pager ?>
        <?php else: ?>
                <span class="nodata">您暂时还没有收到任何邀请哦~</span>
        <?php endif; ?>

</div>
