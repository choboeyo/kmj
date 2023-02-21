<div class="container skin-members-basic">
    <?=$asides_member?>

    <h2 class="members-title"><?=langs('회원/info/withdrawals')?></h2>

    <p class="members-help-block"><?=langs('회원/msg/withdrawals_info_message')?></p>

    <?=$form_open?>
    <fieldset>
        <div class="members-form-group">
            <input type="password" id="current-password" class="members-input" name="current_password">
            <label for="current-password"><?=langs('회원/info/old_password')?></label>
        </div>
    </fieldset>
    <?=validation_errors('<p class="validation-error">')?>

    <button class="members-btn primary" onclick="return confirm('<?=langs('회원/msg/withdrawals_procced')?>');"><i class="fa fa-sign-out"></i> <?=langs('회원/info/withdrawals')?></button>

    <?=$form_close?>
</div>