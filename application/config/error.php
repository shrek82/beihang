<?php
// Unique error identifier
$error_id = uniqid('error');
?>
<style type="text/css">
    #kohana_error { background: #ddd; font-size: 1em; font-family:sans-serif; text-align: left; color: #111; }
    #kohana_error h1,
    #kohana_error h2 { margin: 0; padding: 1em; font-size: 1em; font-weight: normal; background: #911; color: #fff; }
    #kohana_error h1 a,
    #kohana_error h2 a { color: #fff; }
    #kohana_error h2 { background: #222; }
    #kohana_error h3 { margin: 0; padding: 0.4em 0 0; font-size: 1em; font-weight: normal; }
    #kohana_error p { margin: 0; padding: 0.2em 0; }
    #kohana_error a { color: #1b323b; }
    #kohana_error pre { overflow: auto; white-space: pre-wrap; }
    #kohana_error table { width: 100%; display: block; margin: 0 0 0.4em; padding: 0; border-collapse: collapse; background: #fff; }
    #kohana_error table td { border: solid 1px #ddd; text-align: left; vertical-align: top; padding: 0.4em; }
    #kohana_error div.content { padding: 0.4em 1em 1em; overflow: hidden; }
    #kohana_error pre.source { margin: 0 0 1em; padding: 0.4em; background: #fff; border: dotted 1px #b7c680; line-height: 1.2em; }
    #kohana_error pre.source span.line { display: block; }
    #kohana_error pre.source span.highlight { background: #f0eb96; }
    #kohana_error pre.source span.line span.number { color: #666; }
    #kohana_error ol.trace { display: block; margin: 0 0 0 2em; padding: 0; list-style: decimal; }
    #kohana_error ol.trace li { margin: 0; padding: 0; }
    .js .collapsed { display: none; }
</style>

<div id="kohana_error">
    <h1><span class="type"></span> <span class="message">很抱歉，程序错误，请与管理员联系或返回，谢谢！</span></h1>
</div>

