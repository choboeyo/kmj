<ul class="members-tab">
    <li <?=$this->active=="members/info"?'class="active"':""?>><a href="<?=base_url('members/info')?>"><?=langs('회원/info/profile')?></a></li>
    <li <?=$this->active=="members/info/social"?'class="active"':""?>><a href="<?=base_url('members/info/social')?>"><?=langs('회원/info/social')?></a></li>

    <?php if(USE_SHOP):?>
    <li <?=$this->active=="members/my-order"?'class="active"':""?>><a href="<?=base_url('members/my-order')?>">주문내역</a></li>
    <li <?=$this->active=="members/my-reviews"?'class="active"':""?>><a href="<?=base_url('members/my-reviews')?>">리뷰</a></li>
    <li <?=$this->active=="members/my-qna"?'class="active"':""?>><a href="<?=base_url('members/my-qna')?>">상품문의</a></li>
    <li <?=$this->active=="members/my-wishlist"?'class="active"':""?>><a href="<?=base_url('members/my-wishlist')?>">찜보관함</a></li>
    <?php else :?>
    <li <?=$this->active=="members/password_change"?'class="active"':""?>><a href="<?=base_url('members/password_change')?>"><?=langs('회원/info/password_change')?></a></li>
    <li <?=$this->active=="members/withdrawals"?'class="active"':""?>><a href="<?=base_url('members/withdrawals')?>"><?=langs('회원/info/withdrawals')?></a></li>
    <li <?=$this->active=="members/modify"?'class="active"':""?>><a href="<?=base_url('members/modify')?>"><?=langs('회원/info/modify')?></a></li>
    <?php endif;?>

</ul>