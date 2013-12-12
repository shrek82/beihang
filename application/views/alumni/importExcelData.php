<!-- alumni/importExcelData:_body -->
<br><br>
正在处理id <?=$start_id?> ~ <?=$end_id?><br><br>
共计处理临时档案数据：<?=$alumni_temp_num?> 条;<br><br>
共计新增档案数据：<?=$add_num?> 条;<br><br>
共计更新档案数据：<?=$update_num?> 条;<br><br>
共计错误临时档案数据：<?=$error_num?> 条;<br>
<br>
<script type="text/javascript">
    setTimeout(function(){
           window.location.href='/alumni/importExcelData?start_id=<?=$end_id?>&end_id=<?=$end_id+$auto_add?>';
    },2000);
</script>
