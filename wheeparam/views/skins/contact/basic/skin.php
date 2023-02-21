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
<div class="contact-skin-basic">

    <?=$form_open?>
    <legend class="sr-only">상담문의 신청하기</legend>
    <fieldset>
        <div class="contact-form-group">
            <input class="contact-input" id="contact-form-name" name="con_name" placeholder="이름">
            <label for="contact-form-name">이름 <span class="required">(필수입력)</span></label>
        </div>
        <div class="contact-form-group">
            <input class="contact-input" id="contact-form-email" name="con_email" placeholder="이메일">
            <label for="contact-form-email">E-mail <span class="required">(필수입력)</span></label>
        </div>
        <div class="contact-form-group">
            <input class="contact-input" id="contact-form-phone" name="con_phone" placeholder="연락처" data-regex="tel-number">
            <label for="contact-form-phone">연락처 <span class="required">(필수입력)</span></label>
        </div>
        <!--S: EXTRA 필드 사용예제 -->
        <div class="contact-form-group">
            <input type="hidden" name="extra_name[region]" value="지역">
            <select class="contact-select" id="contact-form-region" name="extra[region]">
                <option value="서울">서울</option>
                <option value="대전">대전</option>
                <option value="대구">대구</option>
                <option value="부산">부산</option>
            </select>
            <label for="contact-form-region">지역</label>
        </div>
        <div class="contact-form-group">
            <input type="hidden" name="extra_name[company]" value="회사명">
            <input class="contact-input" id="contact-form-company" name="extra[company]" placeholder="회사명을 입력하세요">
            <label for="contact-form-company">회사명</label>
        </div>
        <!--E: EXTRA 필드 사용예제 -->
        <div class="contact-form-group">
            <textarea class="contact-input" id="contact-form-content" name="con_content" placeholder="문의 내용 입력" rows="8"></textarea>
            <label for="contact-form-content">문의 내용 <span class="required">(필수입력)</span></label>
        </div>

        <?=validation_errors('<p class="alert alert-danger">',"</p>")?>
    </fieldset>

    <div class="text-center">
        <button type="submit" class="contact-form-submit">문의하기</button>
    </div>

    <?=$form_close;?>
</div>
