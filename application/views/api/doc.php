<p class="title">请求地址</p>
http://zuaa.zju.edu.cn/api_
<p class="title">请求参数</p>
<table class="api_parameter" cellspacing="1" cellpadding="0">
    <tr>
        <th>参数</th>
        <th>必须</th>
        <th>类型</th>
        <th class="exp">说明</th>
    </tr>
    <tr>
        <td class="name">cat</td>
        <td class="optional">false</td>
        <td class="type">string</td>
        <td class="explain">指定新闻分类</td>
    </tr>
    <tr>
        <td class="name">limit</td>
        <td class="optional">false</td>
        <td class="type">int</td>
        <td class="explain">每页显示条数，默认显示15条</td>
    </tr>
    <tr>
        <td class="name">max_id</td>
        <td class="optional">false</td>
        <td class="type">int</td>
        <td class="explain">返回id小于max_id活动列表</td>
    </tr>
    <tr>
        <td class="name">since_id</td>
        <td class="optional">false</td>
        <td class="type">int</td>
        <td class="explain">返回id大于since_id活动列表</td>
    </tr>
    <tr>
        <td class="name">page</td>
        <td class="optional">false</td>
        <td class="type">int</td>
        <td class="explain">指定当前显示的页码</td>
    </tr>
    <tr>
        <td class="name">aa_id</td>
        <td class="optional">false</td>
        <td class="type">int</td>
        <td class="explain">指定某一校友会的新闻，缺省显示全部新闻</td>
    </tr>
    <tr>
        <td class="name">format</td>
        <td class="optional">flase</td>
        <td class="type">string</td>
        <td class="explain">返回值的格式。请指定为JSON或者XML，缺省值为XML</td>
    </tr>
</table>

<p class="title">输出文本</p>
<code lang="Xml">

</code>

<?php if ($data): ?>
    <?php if (isset($data[0])): ?>
        <?php $data = $data[0]; ?>
    <?php endif; ?>
    <p class="title">字段说明</p>
    <table class="api_fields" cellspacing="1" cellpadding="0">
        <tbody>
            <tr>
                <th>字段</th>
                <th>字段类型</th>
                <th class="exp">字段说明</th>
            </tr><?php foreach ($data AS $key => $value): ?>
            <tr>
                 <td class="name"><?= $key ?></td>
                 <td class="type"><?= is_int($value) ? 'int' : 'string'; ?></td>
                 <td class="explain"><?= $key ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
