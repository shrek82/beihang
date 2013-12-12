<?php if ($_SESS->get('prompt')): ?>
<script type="text/javascript">showPrompt('<?= $_SESS->get('prompt') ?>',2000); </script>
<?= $_SESS->delete('prompt') ?>
<?php endif; ?>
<div id="footer">
    <div class="copyright">
    Copyright © <?=$_CONFIG->base['domain_name']?> All rights reserved. Support By <?=$_CONFIG->base['sitename']?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var statrTime;
        $.each(readyScript, function(name, script) {
            statrTime = new Date().getTime();
            script();
            candylog('run '+name +':'+ (new Date().getTime() - statrTime) / 1000 + 's');
        });
    });
</script>