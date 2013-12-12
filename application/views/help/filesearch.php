<?php if (isset($err)): ?>
<?= $err ?>
<?php else: ?>

<?php if ($files): ?>
            <table width="100%" border="0"  cellpadding="0" cellspacing="0" id="file_table">
                <thead>            
                    <tr>
                        
                        <td>姓名</td>
                        <td>性别</td>
                        <td>学历</td>
                        <td>就读时间</td>
                        <td>专业名称</td>
                        <td>就读学院(系)</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
        <?php foreach ($files as $i => $user): ?>
                <tr style="<?= $i % 2 == 1 ? 'background-color:#F5FAFF' : '' ?>">
                    
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['sex'] ?></td>
                    <td><?= $user['education'] ?></td>
                    <td ><?= $user['begin_year'] ? $user['begin_year'] : '?' ?>~<?= $user['graduation_year'] ? $user['graduation_year'] : '?' ?>年</td>
                    <td><?= $user['speciality'] ? $user['speciality'] : '-' ?></td>
                    <td><?= $user['institute'] ? $user['institute'] : '-' ?></td>
                </tr>
        <?php endforeach; ?>
            </tbody>
        </table>
        <div style="text-align: center; margin:20px; color: #666">共 <?= count($files) ?>条查询结果</div>
<?php else: ?>
                    <div class="clear"></div>
                    <span class="nodata">很抱歉，没有您要查找的档案信息。</span>
<?php endif; ?>

<?php endif; ?>