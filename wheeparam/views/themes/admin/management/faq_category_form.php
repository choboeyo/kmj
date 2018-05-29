<div class="H10"></div>
<?=form_open(NULL,array("class"=>"form-flex","autocomplete"=>"off")) ?>
<?=validation_errors('<p class="alert alert-danger">') ?>
    <input type="hidden" name="mode" value="<?=element('fac_idx', $view)?'UPDATE':'INSERT'?>">
    <div class="form-group">
        <label class="control-label">그룹 이름</label>
        <div class="controls">
            <input type="text" class="form-control" name="fac_title" value="<?=element('fac_title', $view) ?>" required maxlength="50" autofocus>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">고유 키</label>
        <div class="controls">
            <input type="text" maxlength="20" name="fac_idx" value="<?=element('fac_idx', $view) ?>" class="form-control" <?= element('fac_idx', $view) ? 'readonly="readonly"' : 'required="required"' ?>>
            <p class="help-block"><?= base_url("faq/" . element('fac_idx', $view)) ?></p>
        </div>
    </div>
    <div class="H10"></div>
    <div class="text-center">
        <button class="btn btn-primary"><i class="far fa-check-circle"></i> 확인</button>
        <button type="button" class="btn btn-default" onclick="parent.APP.MODAL.close();">닫기</button>
    </div>
<?=form_close()?>

<?php if(!$is_edit) : ?>
<script>
    $(function () {
        $("input[name='fac_idx']").on('blur', function () {
            var $this = $(this);
            if ($this.val() == '') return;

            var keyRegex = /^[a-z]+[a-z0-9]{4,20}$/g;
            if (!keyRegex.test($this.val())) {
                alert('고유키는 영어 소문자로 시작하는 4~20자 영문자 또는 숫자여야 합니다.');
                $this.val('');
                $this.focus();
                return false;
            }

            if( ! faq.category.exist($this.val() ))
            {
                alert('이미 사용중인 고유키 입니다.');
                $this.val('');
                $this.focus();
                return false;
            }
        }).keyup(function () {
            var $this = $(this);
            $this.next('p.help-block').text(base_url + "faq/" + $this.val());
        });
    });
</script>
<?php endif;?>
