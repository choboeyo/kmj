<?php
// CSS 파일과 JS파일 추가 (TRUE 옵션을 준경우 옵션을 주지않은경우보다 상위에 위치한다.)
$this->site->add_css("/assets/css/admin.min.css");
$this->site->add_js("/assets/js/admin.min.js");
?>
<style>
    html,body {height:100%;}
</style>
<div class="frame-content">
<?=$contents?>
</div>
