<!-- 个人信息展示（隐私控制） -->
<table id="about_this_user" width="100%" style="margin-top: 10px;">
    <tr>
        <th width="60">移动电话</th>
        <td width="120">
            <?php if(Model_User::privateChecker($private, 'mobile', $user['id'])): ?>
            <?= $user['Contact']['mobile'] ?>
            <?php else: ?>
            *无权查看*
            <?php endif; ?>
        </td>

        <th width="30">QQ</th>
        <td>
            <?php if(Model_User::privateChecker($private, 'qq', $user['id'])): ?>
            <?= $user['Contact']['qq'] ? $user['Contact']['qq'] : '未填写' ?>
            <?php else: ?>
            *无权查看*
            <?php endif; ?>
        </td>

        <th width="60">固定电话</th>
        <td>
            <?php if(Model_User::privateChecker($private, 'tel', $user['id'])): ?>
            <?= $user['Contact']['tel'] ? $user['Contact']['tel'] : '未填写' ?>
            <?php else: ?>
            *无权查看*
            <?php endif; ?>
        </td>
    </tr>
    <?php if($user['Contact']['address']): ?>
    <tr>
        <th width="60">居住地址</th>
        <td colspan="5">
            <?php if(Model_User::privateChecker($private, 'address', $user['id'])): ?>
            <?= $user['Contact']['address'] ?>
            <?php else: ?>
            *无权查看*
            <?php endif; ?>
        </td>
    </tr>
    <?php endif; ?>

    <?php if(count($user['Works']) > 0): ?>
    <tr>
        <th width="60">工作信息</th>
        <td colspan="5">
            <?php if(Model_User::privateChecker($private, 'work', $user['id'])): ?>
            <dl style="margin: 0">
            <?php foreach($user['Works'] as $w): ?>
                <dd id="work_<?= $w['id'] ?>">
                    <?= $w['company'] ?>(<?= $w['job'] ?>)
                    <?= $w['start_at'] ?> -
                    <?= $w['leave_at'] == '0000-00-00' ? '至今' : $w['leave_at'] ?>
                </dd>
            <?php endforeach; ?>
            </dl>
            <?php else: ?>
            *无权查看*
            <?php endif; ?>
        </td>
    </tr>
    <?php endif; ?>

    <?php if($user['intro']): ?>
    <tr>
        <th width="60">自我介绍</th>
        <td colspan="5">
            <div><?= nl2br($user['intro']) ?></div>
        </td>
    </tr>
    <?php endif; ?>
</table>