<div class="container MT30 MB30">

    <div class="col-sm-3">
        <?=$asides_member?>
    </div>

    <div class="col-sm-9">
        <article class="panel panel-default">
            <header class="panel-heading">
                <h1 class="panel-title"><?=$mem['mem_nickname']?> <?=langs('회원/info/social')?></h1>
            </header>
            <table class="table">
                <thead>
                <tr>
                    <th class="text-center"></th>
                    <th class="text-center"><?=langs('회원/info/social')?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-center"><img src="<?=base_url('assets/images/social/icon_naver.png')?>" alt="네이버"></td>
                    <td class="text-center">
                        <?php if(isset($mem['social']['naver'])) : ?>
                            <?=$mem['social']['naver']['soc_regtime']?>
                        <?php else :?>
                            <a class="btn btn-default" href="<?=base_url('members/social-login/naver')?>"><?=langs('회원/button/link_social')?></a>
                        <?php endif;?>
                    </td>
                </tr>
                <tr>
                    <td class="text-center"><img src="<?=base_url('assets/images/social/icon_kakao.png')?>" alt="카카오"></td>
                    <td class="text-center">
                        <?php if(isset($mem['social']['kakao'])) : ?>
                            <?=$mem['social']['kakao']['soc_regtime']?>
                        <?php else :?>
                            <a class="btn btn-default" href="<?=base_url('members/social-login/kakao')?>"><?=langs('회원/button/link_social')?></a>
                        <?php endif;?>
                    </td>
                </tr>
                <tr>
                    <td class="text-center"><img src="<?=base_url('assets/images/social/icon_facebook.png')?>" alt="페이스북"></td>
                    <td class="text-center">
                        <?php if(isset($mem['social']['facebook'])) : ?>
                            <?=$mem['social']['facebook']['soc_regtime']?>
                        <?php else :?>
                            <a class="btn btn-default" href="<?=base_url('members/social-login/facebook')?>"><?=langs('회원/button/link_social')?></a>
                        <?php endif;?>
                    </td>
                </tr>
                <tr>
                    <td class="text-center"><img src="<?=base_url('assets/images/social/icon_google.png')?>" alt="구글"></td>
                    <td class="text-center">
                        <?php if(isset($mem['social']['google'])) : ?>
                            <?=$mem['social']['google']['soc_regtime']?>
                        <?php else :?>
                            <a class="btn btn-default" href="<?=base_url('members/social-login/google')?>"><?=langs('회원/button/link_social')?></a>
                        <?php endif;?>
                    </td>
                </tr>
                </tbody>
            </table>

        </article>
    </div>
</div>