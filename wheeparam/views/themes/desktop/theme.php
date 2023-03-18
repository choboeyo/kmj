<?php
// CSS 파일과 JS파일 추가 (TRUE 옵션을 준경우 옵션을 주지않은경우보다 상위에 위치한다.)
$this->site->add_css("https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700&family=Noto+Sans+KR:wght@300&display=swap", TRUE);
$this->site->add_css("/assets/css/desktop.min.css", TRUE);

$this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js', TRUE);
$this->site->add_js("/assets/js/desktop.min.js", TRUE);
?><!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=$this->site->display_meta()?>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<ul class="sr-only">
    <li><a href="#contents">컨텐츠 바로가기</a></li>
    <li><a href="#main-navigation">메인 메뉴 바로가기</a></li>
</ul>

<!-- S: 헤더 영역 -->
<header id="header" class="wb-header" data-fit-aside>

    <div class="container header-inner">
        <a class="brand-logo" href="<?=base_url()?>"><?=$this->site->config('site_title')?></a>

        <nav class="main-navigation-wrap">

            <ul class="main-navigation">
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

                <?php
                /** S: 쇼핑몰 사용설정이 되어있는경우 상품분류를 가져와 메뉴로 출력한다. */
                if(USE_SHOP):
                    $shop_menu = $this->products_model->getCategoryList(FALSE, TRUE);

                    foreach($shop_menu as $menu1):
                ?>
                <?php if(count($menu1['children'])>0):?>
                <li class="dropdown">
                    <a href="<?=base_url('products/category/'.$menu1['cat_id'])?>" class="dropdown-toggle" data-toggle="dropdown"><?=$menu1['cat_title']?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                    <?php foreach($menu1['children'] as $menu2) :?>
                        <li><a href="<?=base_url('products/category/'.$menu2['cat_id'])?>"><?=$menu2['cat_title']?></a></li>
                    <?php endforeach;?>
                    </ul>
                </li>
                <?php else :?>
                <li>
                    <a href='<?=base_url('products/category/'.$menu1['cat_id'])?>'><?=$menu1['cat_title']?></a>
                </li>
                <?php endif;?>
                <?php
                    endforeach;
                endif;
                /** E: 상품분류 가져오기 끝*/
                ?>
            </ul>

            <ul class="member-navigation">
                <?php if(USE_SHOP) :?>
                    <li class="dropdown"><a href="<?=base_url('shop/cart')?>"><i class="fas fa-shopping-cart"></i>&nbsp;장바구니</a></li>
                <?php endif;?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$this->member->info('nickname')?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">

                        <?php if( $this->member->is_login() ) :?>
                        <li><a href="<?=base_url('members/info')?>"><i class="fas fa-user"></i>&nbsp;내 정보</a></li>
                            <?php if(USE_SHOP) :?>
                            <li><a href="<?=base_url('members/my-order')?>"><i class="fas fa-bags-shopping"></i>&nbsp;내 주문내역</a></li>
                            <li><a href="<?=base_url('members/my-wishlist')?>"><i class="fas fa-heart"></i>&nbsp;찜보관함</a></li>
                            <?php endif;?>
                        <li class="divider"></li>
                        <li><a href="<?=base_url('members/logout')?>"><i class="fas fa-sign-out"></i>&nbsp;로그아웃</a></li>
                        <?php else :?>
                        <li><a href="<?=base_url('members/login?reurl='.current_full_url(TRUE))?>">로그인</a></li>
                        <li><a href="<?=base_url('members/register')?>">회원가입</a></li>
                        <?php endif;?>
                    </ul>
                </li>
            </ul>
        </nav>

        <button type="button" class="navbar-toggle collapsed" data-button="menu-toggle">
            <span class="sr-only">메인 메뉴 토글</span>
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">

            </div>

            <div class="collapse navbar-collapse" id="main-navigation">


                <form class="navbar-form navbar-left" id="search_box" action="<?=base_url('search')?>">
                    <div class="form-group">
                        <input class="wb-input" placeholder="<?=langs('공통/search/search_placeholder')?>" name="query">
                    </div>
                    <button class="wb-btn"><?=langs('공통/search/search_submit')?></button>
                </form>
            </div>
    </nav>
</header>
<!-- E: 헤더 영역 -->

<div class="container">
    <div class="wb-main-layout" data-fit>
        <aside id="aside-left" class="wb-aside-left">
            <?=outlogin('basic')?>
        </aside>

        <main id="contents" class="wb-main">
            <?=$contents?>
        </main>
    </div>
</div>

<!-- S: 푸터 영역 -->
<footer id="footer" class="wb-footer" data-fit-aside>
    <div class="container">
        <!-- S: 하단 메뉴 -->
        <nav class="bottom-nav">
            <ul class="bottom-navigation">
                <li class="--nav-item"><a class="--anchor" href="<?=base_url('agreement/site')?>">사이트 이용약관</a></li>
                <li class="--nav-item"><a class="--anchor strong" href="<?=base_url('agreement/privacy')?>">개인정보 취급방침</a></li>
                <li class="--nav-item"><a class="--anchor" href="<?=base_url('customer/faq')?>">자주 묻는 질문</a></li>
                <li class="--nav-item"><a class="--anchor" href="<?=base_url('customer/qna')?>">1:1문의</a></li>
            </ul>
        </nav>
        <!-- E: 하단 메뉴 -->

        <!-- S: 사업자 정보 -->
        <div class="site-info">
            <dl>
                <dt class="sr-only">사업자명</dt>
                <dd><?=$this->site->config('company_name')?></dd>
            </dl>
            <dl>
                <dt>대표자</dt>
                <dd><?=$this->site->config('company_ceo')?></dd>
            </dl>
            <dl>
                <dt>TEL.</dt>
                <dd><a href="tel:<?=$this->site->config('company_tel')?>"><?=$this->site->config('company_tel')?></a></dd>
            </dl>
            <dl>
                <dt>FAX.</dt>
                <dd><?=$this->site->config('company_fax')?></dd>
            </dl>
            <hr class="break">
            <dl>
                <dt>사업자등록번호</dt>
                <dd><?=$this->site->config('company_biznum')?></dd>
            </dl>
            <dl>
                <dt>통신판매업등록번호</dt>
                <dd><?=$this->site->config('company_shopnum')?></dd>
            </dl>
            <dl>
                <dt>개인정보관리책임자</dt>
                <dd><?=$this->site->config('company_privacy_name')?> <a href="mailto:<?=$this->site->config('company_privacy_email')?>"><?=$this->site->config('company_privacy_email')?></a></dd>
            </dl>
            <hr class="break">
            <dl>
                <dt class="sr-only">주소</dt>
                <dd><address><?=$this->site->config('company_address')?></address></dd>
            </dl>
        </div>
        <!-- E: 사업자 정보 -->

        <p class="copyright">Copyright &copy; <?=$this->site->config('company_name')?> All rights reserved.</p>
    </div>
</footer>
<!-- E: 푸터 영역 -->
</body>
</html>