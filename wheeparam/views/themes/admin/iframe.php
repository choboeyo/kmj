<?php
// CSS 파일과 JS파일 추가 (TRUE 옵션을 준경우 옵션을 주지않은경우보다 상위에 위치한다.)
$this->site->add_css('//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-kr.css', TRUE);
$this->site->add_css('//fonts.googleapis.com/css?family=Roboto:400,100,700', TRUE);
$this->site->add_css('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css',TRUE);
$this->site->add_css("/assets/css/admin.min.css", TRUE);

$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js', TRUE);
$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js', TRUE);
$this->site->add_js("/assets/js/admin.min.js", TRUE);
?>
<style>
    html,body {height:100%;}
</style>
<div class="frame-content">
<?=$contents?>
</div>
