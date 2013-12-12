<div class="g one-whole">
<?php if ($error): ?>
<div style="padding: 15px;color:#f30;width:350px; line-height: 1.6em"><span><?=$error?></span></div>
<?php else: ?>
    <form id="sign_form" name="sign_form" action="<?= URL::site('event/sign') ?>" method="post" >
        <table width=400 border="0" cellspacing="3" cellpadding="0" style="margin-top:0px;">
            <tr>
                <td  style="width:18%;text-align: right;padding-right: 5px;">参加人数：</td>
                <td >
                        <?php $maxnum = $event['maximum_entourage'] ? $event['maximum_entourage'] : 10; ?>
                    <select name="num">
                        <?php for ($i = 1; $i <= $maxnum; $i++): ?>
                            <option value="<?= $i ?>" <?=isset($user_sign['num'])&&$user_sign['num']==$i?'selected':'';?>> <?= $i ?> </option>
    <?php endfor; ?>
                    </select>&nbsp;&nbsp;<span style="color:#999">人</span></td>
            </tr>

    <?php if ($event['need_tickets']): ?>
                <tr>
                    <td style="text-align: right;padding-right: 5px">门票：</td>
                    <td colspan="2">
                        <select name="tickets">
                            <?php for ($i = 1; $i <= $event['maximum_receive']; $i++): ?>
                                <option value="<?= $i ?>" <?=isset($user_sign['tickets'])&&$user_sign['tickets']==$i?'selected':'';?>><?= $i ?></option>
        <?php endfor; ?>
                        </select>
                        &nbsp;&nbsp;<span style="color:#999">张</span></td>
                </tr>
            <?php endif; ?>

            <?php if (!empty($event['receive_address'])): ?>
                <?php
                $receive_address = explode("\n", trim($event['receive_address']));
                ?>
                <tr>
                    <td style="text-align: right;padding-right: 5px">领票位置：</td>
                    <td colspan="2">
                        <select name="receive_address">
                            <?php foreach ($receive_address as $address): ?>
                                <option value="<?= $address ?>" <?=isset($user_sign['receive_address'])&&$user_sign['receive_address']==$address?'selected':'';?>><?= $address ?></option>
        <?php endforeach; ?>
                        </select></td>
                </tr>
    <?php endif; ?>

    <?php if ($event['category_label'] AND $categorys): ?>
                <tr>
                    <td style="text-align: right;padding-right: 5px"><?= $event['category_label'] ?>：</td>
                    <td colspan="2">
                        <select name="category_id">
                            <option value="0">请选择...</option>
                            <?php foreach ($categorys as $id => $c): ?>
                                <option value="<?= $c['id'] ?>" <?=isset($user_sign['category_id'])&&$user_sign['category_id']==$c['id']?'selected':'';?>><?= $c['name'] ?>&nbsp;&nbsp;(<?= $c['sign_num'] ? $c['sign_num'] : '0' ?>人)</option>
        <?php endforeach; ?>
                        </select></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td style="text-align: right;padding-right: 5px">备注：</td>
                <td colspan="2"><textarea name="remarks" style="width:300px;height:50px; color: #666" class="input_text" <?php if(!isset($user_sign['id'])):?>onFocus="this.value=''"  onblur="if(this.value==''){this.value='没啥说的，一定准时到场～'}"<?php endif;?>><?=isset($user_sign['remarks'])?$user_sign['remarks']:'没啥说的，一定准时到场～';?></textarea></td>
            </tr>
            <tr>
                <td style="text-align: right;padding-right: 5px"></td>
                <td colspan="2" style="color:#999"><span style="vertical-align: middle"><input type="checkbox" name="is_anonymous" id="is_anonymous" value="1"   <?=isset($user_sign['is_anonymous'])&&$user_sign['is_anonymous']?'checked':'';?>></span><label for="is_anonymous">匿名参加</label></td>
            </tr>
            <?php if(isset($user_sign['id'])):?>
            <tr>
                <td style="text-align: right;padding-right: 5px"></td>
                <td colspan="2" style="color:#999; padding-top: 6px"><a href="javascript:cancel_sign(<?=$event['id']?>)" style="color:#F78993">取消报名?</a></td>
            </tr>
            <?php endif;?>
        </table>
        <input type="hidden" name="event_id" value="<?=$event['id']?>">
        <input type="hidden" name="sign_action" value="<?=isset($user_sign['id'])?'update':'add';?>">
    </form>
<?php endif; ?>


</div>

<script type="text/javascript">
                function sign() {
                    $.post('/mobile/eventsign', $('#eventform').serialize(), function(response) {
                        console.log(response);
                    });
                }
</script>