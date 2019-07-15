<?php
// CSS 파일과 JS파일 추가 (TRUE 옵션을 준경우 옵션을 주지않은경우보다 상위에 위치한다.)
$this->site->add_css('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css',TRUE);
$this->site->add_css("/assets/css/desktop.min.css", TRUE);

$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js', TRUE);
$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js', TRUE);
$this->site->add_js("/assets/js/desktop.min.js", TRUE);
?><!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <?=$this->site->display_meta()?>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <?=$this->site->display_css()?>
</head>
<body>
<div class="container-fluid">
    <header id="header" class="row">
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navigation" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?=base_url()?>"><?=$this->site->config('site_title')?></a>
                </div>

                <div class="collapse navbar-collapse" id="main-navigation">
                    <ul class="nav navbar-nav">
                        <?php $menu = $this->site->menu(); // 메뉴를 가져온다. ?>

                        <?php foreach($menu as $menu1) : ?>

                            <?php if( count($menu1['children']) >0 ) : // 1차메뉴가 하위메뉴가 있다면 ?>
                                <li class="dropdown <?=$menu1['active']?'active':''?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$menu1['mnu_name']?> <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <?php foreach($menu1['children'] as $menu2) :?>

                                            <?php if( count($menu2['children']) > 0 ) : // 2차메뉴가 하위메뉴가 있다면?>
                                                <li class="<?=$menu2['active']?'active':''?>">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$menu2['mnu_name']?></a>
                                                    <ul class="dropdown-menu">
                                                        <?php foreach($menu2['children'] as $menu3) :?>
                                                            <li class="<?=$menu3['active']?'active':''?>">
                                                                <a href="<?=$menu3['mnu_link']?>" <?=$menu3['mnu_newtab']=='Y'?'target="_blank"':''?>><?=$menu3['mnu_name']?></a>
                                                            </li>
                                                        <?php endforeach;?>
                                                    </ul>
                                                </li>
                                            <?php else : // 2차메뉴가 하위메뉴가 없다면?>
                                                <li class="<?=$menu2['active']?'active':''?>">
                                                    <a href="<?=$menu2['mnu_link']?>" <?=$menu2['mnu_newtab']=='Y'?'target="_blank"':''?>><?=$menu2['mnu_name']?></a>
                                                </li>
                                            <?php endif;?>

                                        <?php endforeach;?>
                                    </ul>
                                </li>

                            <?php else : // 1차메뉴가 하위메뉴가 없다면 ?>
                                <li class="<?=$menu1['active']?'active':''?>">
                                    <a href='<?=$menu1['mnu_link']?>' <?=$menu1['mnu_newtab']=='Y'?'target="_blank"':''?>><?=$menu1['mnu_name']?></a>
                                </li>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </ul>

                    <?php if( $this->member->is_login() ) :?>
                        <!--START:: 회원전용 메뉴-->
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$this->member->info('nickname')?> <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?=base_url('members/info')?>">내 정보</a></li>
                                    <li><a href="<?=base_url('members/info/social')?>">소셜 연동 설정</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?=base_url('members/logout')?>">로그아웃</a></li>
                                </ul>
                            </li>
                        </ul>
                        <!--END:: 회원전용 메뉴-->
                    <?php else :?>
                        <!--START:: 비회원 전용 메뉴-->
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="<?=base_url('members/login?reurl='.current_full_url(TRUE))?>">로그인</a></li>
                            <li><a href="<?=base_url('members/register')?>">회원가입</a></li>
                        </ul>
                        <!--END:: 비회원전용 메뉴-->
                    <?php endif;?>
                    <form class="navbar-form navbar-left" id="search_box" action="<?=base_url('search')?>">
                        <div class="form-group">
                            <input class="form-control" placeholder="<?=langs('공통/search/search_placeholder')?>" name="query">
                        </div>
                        <button class="btn btn-default"><?=langs('공통/search/search_submit')?></button>
                    </form>
                </div>
            </nav>
        </header>
    </div>
    <section id="contents">
        <div class="container">

            <div class="col-sm-3">
                <?=outlogin('basic')?>
            </div>
            <div class="col-sm-9">
                <?=$contents?>
            </div>

        </div>
    </section>

    <footer id="footer">
        <div class="container">
            <ul>
                <li><a href="<?=base_url('agreement/site')?>">사이트 이용약관</a></li>
                <li><a class="font-bold" href="<?=base_url('agreement/privacy')?>">개인정보 취급방침</a></li>
                <li><a class="font-bold" href="<?=base_url('customer/faq')?>">자주 묻는 질문</a></li>
                <li><a class="font-bold" href="<?=base_url('customer/qna')?>">1:1문의</a></li>
            </ul>
        </div>
    </footer>
</div>
</body>
</html>



