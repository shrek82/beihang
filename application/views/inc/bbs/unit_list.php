<?php if(count($units) == 0): ?>
<p>没有找到任何信息。</p>
<?php else: ?>
<table id="butable" class="unit_table" width="100%">
    <tr>
        <th class="center" width="7%">分类</th>
        <th class="center" width="38%">标题</th>
        <th class="center" width="10%">发布人</th>
        <th class="center" width="10%">评论 / 点击</th>
        <th class="center" width="10%">发布于</th>
        <th class="center" width="15%">最新评论</th>
    </tr>
    <?php foreach($units as $u): ?>
    <tr>
        <td align="center" style="color:#999"><?= $u['Bbs']['name'] ?></td>
        <td style="font-size: 1.1em">
            <a href="<?= URL::site('bbs/view'.$u['type'].'?id='.$u['id']) ?>"><?= $u['title'] ?></a>
            <?php if($u['is_good']): ?>[优秀]<?php endif; ?>
            <?php if($u['is_fixed']): ?>[顶置]<?php endif; ?>
        </td>
        <td class="center"><a href="<?= URL::site('user_home?id='.$u['user_id']) ?>"><?= $u['User']['realname'] ?></a></td>
        <td class="center"><?= $u['reply_num'].' / '.$u['hit'] ?></td>
        <td class="center"><?= $u['create_at'] ?></td>
        <td class="center">
            <?php if($u['comment_at']): ?>
            <?= Date::span_str(strtotime($u['comment_at'])) ?>前
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
