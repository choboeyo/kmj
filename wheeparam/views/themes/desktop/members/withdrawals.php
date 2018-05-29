<div class="container MT30 MB30">
    <div class="col-sm-3">
        <?=$asides_member?>
    </div>

    <div class="col-sm-9">
        <?=validation_errors('<p class="alert alert-danger">')?>
        <?=$form_open?>
        <p class="alert alert-info"><?=langs('회원/msg/withdrawals_info_message')?></p>
        <article class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title"><?=langs('회원/info/withdrawals')?></h2>
            </header>
            <div class="panel-body">

                <div class="form-group">
                    <label for="current-password"><?=langs('회원/info/old_password')?></label>
                    <input type="password" id="current-password" class="form-control" name="current_password">
                </div>

            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-danger" onclick="return confirm('<?=langs('회원/msg/withdrawals_procced')?>');"><i class="fa fa-sign-out"></i> <?=langs('회원/info/withdrawals')?></button>
            </div>
        </article>
        <?=$form_close?>
    </div>
</div>