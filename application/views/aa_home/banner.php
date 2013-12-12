<?php if (count($banner) > 1): ?>
    <div id="idContainer2" class="container">
        <table id="idSlider2" border=0 cellSpacing=0 cellPadding=0>
            <tbody>
                <tr>
                    <?php foreach ($banner as $ban): ?>
                        <td class="td_f"><?php if ($ban['url']): ?><a href="<?= $ban['url'] ?>" title="<?= $ban['title'] ?>"><img src="<?= $ban['filename'] ?>"></a><?php else: ?><img src="<?= $ban['filename'] ?>"><?php endif; ?></td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
        <UL id="idNum" class="banner_num"  style="<?= count($banner) >= 5 ? 'left:580px' : ''; ?>"></UL>
    </div>
    <script type="text/javascript" >
        var total_banner =<?= count($banner); ?>;
        readyScript.banner=scrollbanner;
    </script>
<?php elseif (count($banner) == 1): ?>
    <div>
        <?php foreach ($banner as $ban): ?>
            <?php if ($ban['url']): ?>
                <a href="<?= $ban['url'] ?>" title="<?= $ban['title'] ?>"><img src="<?= $ban['filename'] ?>" style="width: 676px;height: 160px; -webkit-border-radius: 6px;-moz-border-radius: 6px; border-width: 0"></a>
            <?php else: ?>
                <img src="<?= $ban['filename'] ?>" style="width: 676px;height: 160px; -webkit-border-radius: 6px;-moz-border-radius: 6px;border-width: 0">
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div><img src="/static/images/default_banner.jpg" style="width: 676px;height: 135px;"/></div>
    <?php endif; ?>

