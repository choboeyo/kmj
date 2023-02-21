<div class="review-popup-layer">
    <div class="review-popup-inner">
        <?=$form_open?>
        <div class="review-form-group">
            <label>문의 내용</label>
            <textarea class="review-write-input" rows="10" name="qa_content"></textarea>
        </div>
        <div class="review-form-group">
            <label class="qna-secret">
                <input type="checkbox" name="qa_secret" value="Y">
                <span>비밀글 처리</span>
            </label>
        </div>
        <div class="review-action">
            <button type="submit" class="btn-review-submit">작성하기</button>
            <button type="button" class="btn-review-close" onclick="APP.SHOP.closeQnaWrite()">닫기</button>
        </div>
        <?=$form_close?>
    </div>
</div>