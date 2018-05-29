<?php
// CSS 파일과 JS파일 추가 (TRUE 옵션을 준경우 옵션을 주지않은경우보다 상위에 위치한다.)
$this->site->add_css('//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-kr.css', TRUE);
$this->site->add_css('//fonts.googleapis.com/css?family=Roboto:400,100,700', TRUE);
$this->site->add_css('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css',TRUE);
$this->site->add_css("/assets/css/admin.min.css", TRUE);

$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js', TRUE);
$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js', TRUE);
$this->site->add_js("/assets/js/admin.min.js", TRUE);
?>
<script>var menuActive="<?=$this->active?>";</script>

<div id="wrap" class="application">
    <div id="nav-bar">
        <a class="logo" href="<?=base_url('admin')?>">ADMINISTRATOR</a>

        <button class="btn-menu-toggle" type="button" class=""><i class="far fa-bars"></i></button>
        <a class="btn-logout" href="<?=base_url('members/logout')?>?reurl=<?=current_full_url()?>" title="사용자 로그아웃"><i class="far fa-sign-out"></i></a>
    </div>

    <div class="background-container">
        <div class="bg-1"></div>
        <div class="bg-2"></div>
    </div>
    <div id="content">
        <nav id="left-panel">
            <ul id="main-navigation">
                <li>
                    <a href="#" class="parent"><i class="far fa-wrench"></i> 사이트 관리</a>
                    <ul>
                        <li data-active="management/popup"><a href="<?=base_url('admin/management/popup')?>">팝업 관리</a></li>
                        <li data-active="management/banner"><a href="<?=base_url('admin/management/banner')?>">배너 관리</a></li>
                        <li data-active="management/menu"><a href="<?=base_url('admin/management/menu')?>">메뉴 관리</a></li>
                        <li data-active="management/faq"><a href="<?=base_url('admin/management/faq')?>">FAQ 관리</a></li>
                        <li data-active="management/faq_setting"><a href="<?=base_url('admin/management/faq_setting')?>">FAQ 환경설정</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="parent"><i class="far fa-users"></i>&nbsp;회원 관리</a>
                    <ul>
                        <li data-active="members/lists"><a href="<?=base_url('admin/members/lists')?>">회원 목록</a></li>
                        <li data-active="members/add"><a href="<?=base_url('admin/members/add')?>">회원 등록</a></li>
                        <li data-active="members/log"><a href="<?=base_url('admin/members/log')?>">회원 로그인 기록</a></li>
                        <?php if( $this->site->config('point_use') == 'Y' ) :?>
                        <li data-active="members/points"><a href="<?=base_url('admin/members/points')?>"><?=$this->site->config('point_name')?> 관리</a></li>
                        <?php endif;?>
                    </ul>
                </li>

                <?php if(USE_BOARD OR IS_TEST) : ?>
                    <li>
                        <a href="#" class="parent"><i class="far fa-th-large"></i>&nbsp;게시판 관리</a>
                        <ul>
                            <li data-active="board/lists"><a href="<?=base_url('admin/board/lists')?>">게시판 관리</a></li>
                            <li class="divider"></li>
                            <?php
                            $board_list = $this->db->select('B.brd_key,B.brd_title,BPN.new_cnt')->from('board AS B')->join('board_post_new AS BPN','BPN.brd_key=B.brd_key','left')->order_by('B.brd_title')->get()->result_array();
                            foreach($board_list as $row): ?>
                                <li data-active="board/<?=$row['brd_key']?>"><a href="<?=base_url('admin/board/posts/'.$row['brd_key'])?>"><?=$row['brd_title']?><?=$row['new_cnt']>0?" <span class='badge pull-right'>{$row['new_cnt']}</span>":''?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                <?php endif;?>

                <li>
                    <a href="#" class="parent"><i class="far fa-chart-bar"></i>&nbsp;방문 통계</a>
                    <ul>
                        <li data-active="statics/visit"><a href="<?=base_url('admin/statics/visit')?>">사용자 접속 로그</a></li>
                        <li data-active="statics/keyword"><a href="<?=base_url('admin/statics/keyword')?>">키워드별 통계</a></li>
                        <li data-active="statics/times"><a href="<?=base_url('admin/statics/times')?>">방문 시간별 통계</a></li>
                        <li data-active="statics/referrer"><a href="<?=base_url('admin/statics/referrer')?>">유입 경로별 통계</a></li>
                        <li data-active="statics/device"><a href="<?=base_url('admin/statics/device')?>">PC/MOBILE 통계</a></li>
                        <li data-active="statics/browser"><a href="<?=base_url('admin/statics/browser')?>">브라우져별 통계</a></li>
                        <li data-active="statics/os"><a href="<?=base_url('admin/statics/os')?>">OS별 통계</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="parent"><i class="far fa-cog"></i>&nbsp;환경 설정</a>
                    <ul>
                        <li data-active="setting/basic"><a href="<?=base_url('admin/setting/basic')?>">사이트 기본 설정</a></li>
                        <li data-active="setting/localize"><a href="<?=base_url('admin/setting/localize')?>">다국어 설정</a></li>
                        <li data-active="setting/apis"><a href="<?=base_url('admin/setting/apis')?>">소셜/API 설정</a></li>
                        <li data-active="setting/agreement"><a href="<?=base_url('admin/setting/agreement')?>">약관 설정</a></li>
                        <li data-active="setting/member"><a href="<?=base_url('admin/setting/member')?>">회원 설정</a></li>
                        <li data-active="management/sitemap"><a href="<?=base_url('admin/management/sitemap')?>">사이트맵 설정</a></li>
                        <?php if($this->member->is_super()) :?>
                            <li data-active="setting/admin"><a href="<?=base_url('admin/setting/admin')?>">관리자 설정</a></li>
                        <?php endif;?>
                        <li data-active="tools/index"><a href="<?=base_url('admin/tools')?>">기타 도구</a></li>
                    </ul>
                </li>

            </ul>
        </nav>

        <section id="main" role="main">
            <div class="main"><?=$contents?></div>
        </section>
    </div>

</div>


