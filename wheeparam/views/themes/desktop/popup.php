<?php
// CSS 파일과 JS파일 추가 (TRUE 옵션을 준경우 옵션을 주지않은경우보다 상위에 위치한다.)
$this->site->add_css('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css',TRUE);
$this->site->add_css("/assets/css/desktop.min.css", TRUE);

$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js', TRUE);
$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js', TRUE);
$this->site->add_js("/assets/js/desktop.min.js", TRUE);
?><!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <?=$this->site->display_meta()?>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<div class="container-fluid">
    <section id="contents" class="row">
        <?=$contents?>
    </section>
</div>
</body>
</html>



