<script type="text/javascript">
    new Request({
        url: '/event/userjoin',
        type: 'post',
        data: 'id='+1,
        beforeSend:function(){
            alert(this.name);
        }
    }).send();
</script>
