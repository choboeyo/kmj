<ul class="nav nav-stacked nav-pills">
    <li <?=$this->active=="members/info"?'class="active"':""?>><a href="<?=base_url('members/info')?>"><?=langs('회원/info/profile')?></a></li>
    <li <?=$this->active=="members/modify"?'class="active"':""?>><a href="<?=base_url('members/modify')?>"><?=langs('회원/info/modify')?></a></li>
    <li <?=$this->active=="members/info/social"?'class="active"':""?>><a href="<?=base_url('members/info/social')?>"><?=langs('회원/info/social')?></a></li>
    <li <?=$this->active=="members/password_change"?'class="active"':""?>><a href="<?=base_url('members/password_change')?>"><?=langs('회원/info/password_change')?></a></li>
    <li <?=$this->active=="members/withdrawals"?'class="active"':""?>><a href="<?=base_url('members/withdrawals')?>"><?=langs('회원/info/withdrawals')?></a></li>
</ul>