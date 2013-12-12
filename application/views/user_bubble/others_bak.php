<!-- user_bubble/others:_body -->
<div class="span-19 last">

    <?php if(count($bubbles) == 0): ?>
    <div>可以通过关注校友来获得他们的最新状态。</div>
    <?php endif; ?>

    <table width="100%">
    <?php foreach($bubbles as $bubble): ?>
        <tr>
            <td width="50">
                <?= View::factory('inc/user/avatar',
                        array('id'=>$bubble['user_id'],'size'=>48)); ?>
            </td>
            <td style="border-bottom: 1px dotted #eee;">
                <a href="<?= URL::site('user_home?id'.$bubble['user_id']) ?>"><?= $bubble['User']['realname'] ?></a>
                <span class="quiet"><?= Date::span_str(strtotime($bubble['blow_at'])) ?>前：</span>
                <br />
                <?= $bubble['content'] ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
    <?= $pager ?>
</div>