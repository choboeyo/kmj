<?php
/************************************************************************
 * 문의하기 스킨
 * ----------------------------------------------------------------------
 *
 * $form_open   : <form> 여는 태그를 생성합니다.
 * $form_close  : </form> 닫는 태그를 생성합니다.
 * 
 * 필수로 필요한 input
 * con_name : 이름
 * con_email : 이메일
 * con_phone : 연락처
 * con_content : 내용
 *
 * 추가로 입력할필드를 넣어야 할때
 * extra[추가입력] 형식으로 작성
 * 예)  extra[분류]   extra[지역]
 *
 * 에러 표시
 * validation_errors( '여는 태그', '닫는 태그' );
 *************/
?>

<?=validation_errors('<p class="alert alert-danger">',"</p>")?>

<?=$form_open?>
<div class="form-group">
    <input class="form-control" name="con_name" placeholder="이름">
</div>
<div class="form-group">
    <input class="form-control" name="con_email" placeholder="이메일">
</div>
<div class="form-group">
    <input class="form-control" name="con_phone" placeholder="연락처">
</div>
<div class="form-group">
    <input type="hidden" name="extra_name[region]" value="지역">
    <input class="form-control" name="extra[region]" placeholder="지역을 입력하세요">
</div>
<div class="form-group">
    <input type="hidden" name="extra_name[company]" value="회사명">
    <input class="form-control" name="extra[company]" placeholder="회사명을 입력하세요">
</div>
<div class="form-group">
    <textarea class="form-control" name="con_content" placeholder="문의 내용 입력" rows="8"></textarea>
</div>
<div class="text-center">
    <button class="btn btn-primary">문의하기</button>
</div>
<?=$form_close;?>