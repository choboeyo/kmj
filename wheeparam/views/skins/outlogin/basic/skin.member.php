<?php
// 로그인 완료후 스킨
$member_photo = $this->member->info('photo') ? $this->member->info('photo') : '/assets/images/common/common_user.png';
?>
<style>
    #outlogin-basic { border:1px solid #ccc; margin-top:0px; padding:5px; margin-bottom:15px; }
    #outlogin-basic .member-photo {width:60px;height:60px;display:block;margin:auto; cursor:pointer; border:1px solid #eee; padding:10px;}
    #outlogin-basic .member-info { margin:0; padding:0; }
    #outlogin-basic .member-action-list { margin:0;padding:0;list-style:none;font-size:0; }
    #outlogin-basic .member-action-list > li { display:inline-block; }
    #outlogin-basic .member-action-list > li > a {display:inline-block; font-size:12px;}
    #outlogin-basic .member-action-list > li + li:before { display:inline-block; content:''; width:1px; height:8px; background:#ccc; margin:0px 5px; }
</style>
<aside class="media" id="outlogin-basic">
    <div class="media-left">
        <img class="member-photo" src="<?=base_url($member_photo)?>" onclick="APP.MEMBER.POP_CHANGE_PHOTO();">
    </div>
    <div class="media-body">
        <dl class="member-info">
            <dt class="sr-only">닉네임</dt>
            <dd><?=$this->member->info('nickname')?></dd>
        </dl>
        <dl class="member-info">
            <dt class="sr-only">포인트</dt>
            <dd><?=number_format($this->member->info('point'))?></dd>
        </dl>
        <ul class="member-action-list">
            <?php if($this->member->is_super()) :?>
            <li><a href="<?=base_url('admin')?>">관리자</a></li>
            <?php endif;?>
            <li><a href="<?=base_url('members/info')?>">내 정보</a></li>
            <li><a href="<?=base_url('members/logout')?>">로그아웃</a></li>
        </ul>
    </div>
</aside>
