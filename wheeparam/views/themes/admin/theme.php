<?php
$this->site->add_css('https://fonts.googleapis.com/earlyaccess/notosanskr.css', TRUE);
$this->site->add_css("/assets/css/admin.min.css", TRUE);

$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js', TRUE);
$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js', TRUE);
$this->site->add_js("/assets/js/admin.min.js", TRUE);
$this->site->add_js('https://unpkg.com/devextreme-intl@19.1/dist/devextreme-intl.min.js', TRUE);
?><!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <?=$this->site->display_meta()?>
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
                <li data-active="management/history"><a href="<?=base_url('admin/management/history')?>">연혁 관리</a></li>
                <li data-active="management/history"><a href="<?=base_url('admin/management/contact')?>">문의 관리</a></li>
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
        <?php if(USE_SHOP):?>
        <li class="dropdown">
            <span data-toggle="dropdown"><i class="fal fa-gift"></i>상품 관리</span>
            <ul class="dropdown-menu">
                <li data-active="products/categories"><a href="<?=base_url('admin/products/categories')?>">상품 분류 관리</a></li>
                <li data-active="products/items"><a href="<?=base_url('admin/products/items')?>">상품 관리</a></li>
                <li class="divider"></li>
                <li data-active="products/displays"><a href="<?=base_url('admin/products/displays')?>">상품 진열장 관리</a></li>
                <li class="divider"></li>
                <li data-active="products/reviews"><a href="<?=base_url('admin/products/reviews')?>">상품 리뷰 관리</a></li>
                <li data-active="products/qna"><a href="<?=base_url('admin/products/qna')?>">상품 문의 관리</a></li>
                <li class="divider"></li>
                <li data-active="products/stocks"><a href="<?=base_url('admin/products/stocks')?>">상품 재고 관리</a></li>
                <li data-active="products/options-stocks"><a href="<?=base_url('admin/products/options-stocks')?>">상품 옵션 재고 관리</a></li>
                <li data-active="products/labels"><a href="<?=base_url('admin/products/labels')?>">상품 라벨 일괄 관리</a></li>
            </ul>
        </li>
            <li class="dropdown">
                <span data-toggle="dropdown"><i class="fal fa-gift"></i>주문 관리</span>
                <ul class="dropdown-menu">
                    <li data-active="orders/index"><a href="<?=base_url('admin/orders')?>">주문 관리</a></li>
                    <!--<li data-active="orders/statics"><a href="<?=base_url('admin/orders/statics')?>">주문 통계</a></li>-->
                    <li data-active="orders/ranks"><a href="<?=base_url('admin/orders/ranks')?>">상품 판매 순위</a></li>
                    <li data-active="orders/wish"><a href="<?=base_url('admin/orders/wish')?>">상품 찜 순위</a></li>
                </ul>
            </li>
        <?php endif;?>
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
                <?php if(USE_SHOP):?>
                <li class="divider"></li>
                <li data-active="statics/sms-send"><a href="<?=base_url('admin/statics/sms-send')?>">문자발송기록</a></li>
                <?php endif?>
            </ul>
        </li>
        <li class="dropdown">
            <span data-toggle="dropdown"><i class="fal fa-cog"></i>환경 설정</span>
            <ul class="dropdown-menu">
                <li data-active="setting/basic"><a href="<?=base_url('admin/setting/basic')?>">사이트 기본 설정</a></li>
                <?php if(USE_SHOP):?>
                    <li data-active="setting/shop"><a href="<?=base_url('admin/setting/shop')?>">쇼핑몰 환경 설정</a></li>
                    <li data-active="setting/shop-delivery"><a href="<?=base_url('admin/setting/shop-delivery')?>">쇼핑몰 배송 설정</a></li>
                    <li data-active="setting/shop-sms"><a href="<?=base_url('admin/setting/shop-sms')?>">쇼핑몰 문자 발송</a></li>
                <?php endif;?>
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
