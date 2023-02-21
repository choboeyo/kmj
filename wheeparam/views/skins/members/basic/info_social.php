<div class="container skin-members-basic">
    <?=$asides_member?>

    <h2 class="members-title"><?=langs('회원/info/social')?></h2>

    <ul class="members-social-list">
        <li>
            <figure>
                <img src="<?=base_url('assets/images/social/icon_naver.png')?>" alt="네이버">
            </figure>
            <div class="text-wrap">
                <?php if(isset($mem['social']['naver'])) : ?>
                    <?=$mem['social']['naver']['soc_regtime']?>
                <?php else :?>
                    <a class="btn-social-link" href="<?=base_url('members/social-login/naver')?>"><?=langs('회원/button/link_social')?></a>
                <?php endif;?>
            </div>
        </li>

        <li>
            <figure>
                <img src="<?=base_url('assets/images/social/icon_kakao.png')?>" alt="카카오">
            </figure>
            <div class="text-wrap">
                <?php if(isset($mem['social']['kakao'])) : ?>
                    <?=$mem['social']['kakao']['soc_regtime']?>
                <?php else :?>
                    <a class="btn-social-link" href="<?=base_url('members/social-login/kakao')?>"><?=langs('회원/button/link_social')?></a>
                <?php endif;?>
            </div>
        </li>

        <li>
            <figure>
                <img src="<?=base_url('assets/images/social/icon_facebook.png')?>" alt="페이스북">
            </figure>
            <div class="text-wrap">
                <?php if(isset($mem['social']['facebook'])) : ?>
                    <?=$mem['social']['facebook']['soc_regtime']?>
                <?php else :?>
                    <a class="btn-social-link" href="<?=base_url('members/social-login/facebook')?>"><?=langs('회원/button/link_social')?></a>
                <?php endif;?>
            </div>
        </li>

        <li>
            <figure>
                <img src="<?=base_url('assets/images/social/icon_google.png')?>" alt="구글">
            </figure>
            <div class="text-wrap">
                <?php if(isset($mem['social']['google'])) : ?>
                    <?=$mem['social']['google']['soc_regtime']?>
                <?php else :?>
                    <a class="btn-social-link" href="<?=base_url('members/social-login/google')?>"><?=langs('회원/button/link_social')?></a>
                <?php endif;?>
            </div>
        </li>
    </ul>
</div>