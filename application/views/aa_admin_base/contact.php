<!-- aa_admin_base/contact:_body -->
<div id="admin950">
    <fieldset style="margin: 0;border-width: 0">

        <form method="post" action="<?= URL::query() ?>" id="aa_contact">

            <div><label>联系人：</label><br />
                <input type="text" name="contacts" value="<?= $contact['contacts'] ?>"   class="input_text"/>
            </div>

            <div><label>电话(例 办公:0571-8808111)：</label><br />
                <?php
                $tel_arr = explode(';', $contact['tel']);
                if (count($tel_arr) > 0):
                    foreach ($tel_arr as $tel):
                        ?>
                        <div>
                            <input size="8" type="text" name="tel_key[]" value="<?= substr($tel, 0, strpos($tel, ':')) ?>"  class="input_text"/> -
                            <input type="text" name="tel_num[]" value="<?= substr($tel, strpos($tel, ':') + 1) ?>" class="input_text"/>
                        </div>
                    <?php endforeach;
                endif; ?>
                <div id="clone_box">
                    <input size="8" type="text" name="tel_key[]" value="" class="input_text"/> -
                    <input type="text" name="tel_num[]" value="" class="input_text"/>
                </div>
            </div>
            <Br/>
            <Br/>
            <div><label>传真：</label><br />
                <input type="text" name="fax" value="<?= $contact['fax'] ?>" class="input_text"/>
            </div>

            <div><label>邮件：</label><br />
                <input size="60" type="text" name="email" value="<?= $contact['email'] ?>" class="input_text"/>
            </div>

            <div><label>网站(例 http://136.com)：</label><br />
                <input size="60" type="text" name="website" value="<?= $contact['website'] ?>" class="input_text"/>
            </div>

            <div><label>新浪微博地址：</label><br />
                <input size="60" type="text" name="weibo" value="<?= $contact['weibo'] ?>" class="input_text"/>
            </div>

            <div><label>办公地址：</label><br />
                <input size="80" type="text" name="address" value="<?= $contact['address'] ?>"  class="input_text"/>
            </div>

            <div><label>邮政编码：</label><br />
                <input type="text" name="zip" value="<?= $contact['zip'] ?>"  size="80" class="input_text"/>
            </div>

            <div><input onclick="new ajaxForm('aa_contact',{callback:function(){okAlert('联系方式修改成功!')}}).send()" type="button" id="submit_button" value="保存设置"  class="button_blue"/></div>
        </form>
    </fieldset>
</div>