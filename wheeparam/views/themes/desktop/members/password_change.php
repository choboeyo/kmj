<div class="container MT30 MB30">
    <div class="col-sm-3">
        <?=$asides_member?>
    </div>

    <div class="col-sm-9">
        <?=validation_errors('<p class="alert alert-danger">')?>
        <?=$form_open?>
        <article class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title"><?=langs('회원/info/password_change')?></h2>
            </header>
            <div class="panel-body">

                <div class="form-group">
                    <label for="current-password"><?=langs('회원/info/old_password')?></label>
                    <input type="password" id="current-password" class="form-control" name="old_password">
                </div>

                <hr>

                <div class="form-group">
                    <label for="new-password"><?=langs('회원/info/new_password')?></label>
                    <input type="password" id="new-password" class="form-control" name="new_password">
                </div>

                <div class="form-group">
                    <label for="new-password-confirm"><?=langs('회원/info/new_password_confirm')?></label>
                    <input type="password" id="new-password-confirm" class="form-control" name="new_password_confirm">
                </div>

            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-primary"><i class="fa fa-check"></i> <?=langs('회원/info/password_change')?></button>
            </div>
        </article>
        <?=$form_close?>
    </div>
</div>