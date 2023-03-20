<?php
// CSS 파일과 JS파일 추가 (TRUE 옵션을 준경우 옵션을 주지않은경우보다 상위에 위치한다.)
$this->site->add_css('https://fonts.googleapis.com/earlyaccess/notosanskr.css', TRUE);
$this->site->add_css("/assets/css/admin.min.css", TRUE);

$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js', TRUE);
$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js', TRUE);
$this->site->add_js("/assets/js/admin.min.js", TRUE);
$this->site->add_js('https://unpkg.com/devextreme-intl@19.1/dist/devextreme-intl.min.js', TRUE);
?><!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=$this->site->display_meta()?>
</head>
<body class="iframe">
<style>
    html,body {height:100%;}
</style>
<div class="frame-content">
<?=$contents?>
    <div class="clearfix"></div>
</div>
</body>
</html>