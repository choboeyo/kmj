<div class="container skin-members-basic">
    <?=$asides_member?>

    <h2 class="members-title"><?=langs('회원/info/password_change')?></h2>

    <?=$form_open?>
    <fieldset>
        <legend class="sr-only">비밀번호 변경</legend>
        <div class="members-form-group">
            <input type="password" id="current-password" class="members-input" name="old_password">
            <label for="current-password"><?=langs('회원/info/old_password')?> <span class="required">(필수입력)</span></label>
        </div>
        <hr class="members-form-divider">


        <div class="members-form-group">

            <input type="password" id="new-password" class="members-input" name="new_password">
            <label for="new-password"><?=langs('회원/info/new_password')?> <span class="required">(필수입력)</span></label>
        </div>

        <div class="members-form-group">

            <input type="password" id="new-password-confirm" class="members-input" name="new_password_confirm">
            <label for="new-password-confirm"><?=langs('회원/info/new_password_confirm')?> <span class="required">(필수입력)</span></label>
        </div>


    </fieldset>

    <?=validation_errors('<p class="validation-error">')?>

    <button class="members-btn primary"><i class="fa fa-check"></i> <?=langs('회원/info/password_change')?></button>

    <?=$form_close?>
</div>