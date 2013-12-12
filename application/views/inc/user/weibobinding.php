<?php
//版本：1.0
?>
<script type="text/javascript">
    var voteFacebox;
    function eventVote(eid){
	var box = new Facebox({
	    title: '活动评分',
	    width:'600',
	    url: '/event/vote?eid='+eid,
	    submitValue: '发表',
	    ok: function(){
		new ajaxForm('apply_form', {callback:function(){
			box.close();
			location.reload();
		    }}).send();
	    }
	});
	voteFacebox=box;
	box.show();
    }

    function checkjoinevent(){
        new Request({
            url: '<?= URL::site('/event/userjoin') ?>',
            success: function(eid){
                if(eid > 0){
		    eventVote(eid);
                }
            }
        }).send();
    }

setTimeout(function(){checkjoinevent();},3000);

</script>