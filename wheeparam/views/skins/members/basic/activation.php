<?=form_open()?>
<input type="hidden" name="activation" value="1">
<div class="container skin-members-basic">
    <article class="panel panel-default">
        <header class="panel-heading">
            <h1 class="panel-title"><?=langs('회원/info/activation')?></h1>
        </header>
        <div class="panel-body">
            <p class="alert alert-info"><?=langs('회원/msg/activation_info')?></p>
        </div>
        <div class="panel-footer text-center">
            <button class="btn btn-primary"><?=langs('회원/info/activation')?></button>
            <a href="<?=base_url('members/logout')?>" class="btn btn-default"><?=langs('회원/signout')?></a>
        </div>
    </article>
</div>
<?=form_close()?>