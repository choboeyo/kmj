<?php
$this->site->add_css('https://fonts.googleapis.com/earlyaccess/notosanskr.css', TRUE);
$this->site->add_css("/assets/css/admin.min.css", TRUE);

$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js', TRUE);
$this->site->add_js("/assets/js/admin.min.js", TRUE);
$this->site->add_js('https://unpkg.com/devextreme-intl@19.1/dist/devextreme-intl.min.js', TRUE);
?><!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <?=$this->site->display_meta()?>
    <?=$this->site->display_css()?>
</head>
<body>
<script>var menuActive="<?=$this->active?>";</script>
<nav id="nav">
    <ul class="nav-menu" data-main-navigation>
        <li><a class="logo" href="<?=base_url('/admin')?>"><i class="fal fa-home"></i></a></li>
        <li class="dropdown">
            <span data-toggle="dropdown"><i class="fal fa-wrench"></i>사이트 관리</span>
            <ul class="dropdown-menu">
                <li data-active="management/popup"><a href="<?=base_url('admin/management/popup')?>">팝업 관리</a></li>
                <li data-active="management/banner"><a href="<?=base_url('admin/management/banner')?>">배너 관리</a></li>
                <li data-active="management/menu"><a href="<?=base_url('admin/management/menu')?>">메뉴 관리</a></li>
                <li data-active="management/faq"><a href="<?=base_url('admin/management/faq')?>">FAQ 관리</a></li>
                <li data-active="management/qna"><a href="<?=base_url('admin/management/qna')?>">Q&A 관리</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <span data-toggle="dropdown"><i class="fal fa-users"></i>회원 관리</span>
            <ul class="dropdown-menu">
                <li data-active="members/lists"><a href="<?=base_url('admin/members/lists')?>">회원 목록</a></li>
                <li data-active="members/add"><a href="<?=base_url('admin/members/add')?>">회원 등록</a></li>
                <li data-active="members/log"><a href="<?=base_url('admin/members/log')?>">회원 로그인 기록</a></li>
                <?php if( $this->site->config('point_use') == 'Y' ) :?>
                    <li data-active="members/points"><a href="<?=base_url('admin/members/points')?>"><?=$this->site->config('point_name')?> 관리</a></li>
                <?php endif;?>
            </ul>
        </li>
        <li class="dropdown">
            <span data-toggle="dropdown"><i class="fal fa-th-large"></i>게시판 관리</span>
            <ul class="dropdown-menu">
                <li data-active="board/lists"><a href="<?=base_url('admin/board/lists')?>">게시판 관리</a></li>
                <li class="divider"></li>
                <?php
                $board_list = $this->boardlib->getNewPostBoards();
                foreach($board_list as $row): ?>
                    <li data-active="board/<?=$row['brd_key']?>"><a href="<?=base_url('admin/board/posts/'.$row['brd_key'])?>"><?=$row['brd_title']?><?=$row['new_cnt']>0?" <span class='badge'>{$row['new_cnt']}</span>":''?></a></li>
                <?php endforeach;?>
            </ul>
        </li>
        <li class="dropdown">
            <span data-toggle="dropdown"><i class="fal fa-chart-bar"></i>방문 통계</span>
            <ul class="dropdown-menu">
                <li data-active="statics/visit"><a href="<?=base_url('admin/statics/visit')?>">사용자 접속 로그</a></li>
                <li data-active="statics/keyword"><a href="<?=base_url('admin/statics/keyword')?>">키워드별 통계</a></li>
                <li data-active="statics/times"><a href="<?=base_url('admin/statics/times')?>">방문 시간별 통계</a></li>
                <li data-active="statics/referrer"><a href="<?=base_url('admin/statics/referrer')?>">유입 경로별 통계</a></li>
                <li data-active="statics/device"><a href="<?=base_url('admin/statics/device')?>">PC/MOBILE 통계</a></li>
                <li data-active="statics/browser"><a href="<?=base_url('admin/statics/browser')?>">브라우져별 통계</a></li>
                <li data-active="statics/os"><a href="<?=base_url('admin/statics/os')?>">OS별 통계</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <span data-toggle="dropdown"><i class="fal fa-cog"></i>환경 설정</span>
            <ul class="dropdown-menu">
                <li data-active="setting/basic"><a href="<?=base_url('admin/setting/basic')?>">사이트 기본 설정</a></li>
                <li data-active="setting/localize"><a href="<?=base_url('admin/setting/localize')?>">다국어 설정</a></li>
                <li data-active="setting/apis"><a href="<?=base_url('admin/setting/apis')?>">소셜/API 설정</a></li>
                <li data-active="setting/agreement"><a href="<?=base_url('admin/setting/agreement')?>">약관 설정</a></li>
                <li data-active="setting/member"><a href="<?=base_url('admin/setting/member')?>">회원 설정</a></li>
                <li data-active="management/sitemap"><a href="<?=base_url('admin/management/sitemap')?>">사이트맵 설정</a></li>
                <?php if($this->member->is_super()) :?>
                    <li data-active="setting/admin"><a href="<?=base_url('admin/setting/admin')?>">관리자 설정</a></li>
                <?php endif;?>
            </ul>
        </li>
    </ul>

    <div class="nav-right">

    </div>
</nav>

<article id="contents">
    <?=$contents?>
</article>

</body>
</html>