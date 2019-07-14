<?=form_open_multipart()?>
    <?=validation_errors('<p class="alert alert-danger">')?>

    <?php if(! $this->member->is_login()) :?>
    <input placeholder="작성자" name="qna_name" required>
    <input type="password" placeholder="비밀번호" name="qna_password" required>
    <?php endif;?>

    <input placeholder="연락처" data-regex="phone-number" name="qna_phone" value="<?=$this->member->info('phone')?>" required>
    <input placeholder="E-mail" data-regex="email-address" name="qna_email" value="<?=$this->member->info('email')?>" required>

    <?php if(count($qna_category) > 0) :?>
    <select name="qnc_idx">
        <?php foreach($qna_category as $row):?>
        <option value="<?=$row['qnc_idx']?>"><?=$row['qnc_title']?></option>
        <?php endforeach;?>
    </select>
    <?php endif;?>

    <input placeholder="질문 제목" name="qna_title" required>
    <textarea placeholder="질문 내용" name="qna_content" rows="10" required></textarea>

    <div data-container="file-input"></div>
    <script id="tmpl-file-input" type="text/x-jquery-tmpl">
        <input type="file" name="userfile[]">
    </script>
    <button type="button" data-button="add-file-input"><i class="fal fa-plus"></i> 파일 추가</button>

    <button>질문 등록하기</button>

<?=form_close()?>

<script>
    $(function() {
        $('[data-button="add-file-input"]').click(function() {
            $('[data-container="file-input"]').append( $('#tmpl-file-input').tmpl() );
        });
    });
</script>
