<header>
    <h2 class="hide">[<?=$query?>] <?=langs("공통/search/search_result")?></h2>
</header>
<div class="row" id="page-search">
    <article class="search-header">
        <div class="container">
            <header>
                <h3 class="search-header-title">[<?=$query?>] <?=langs("공통/search/search_result")?></h3>
            </header>

            <?=form_open("", array("method"=>"get"), array("board_key"=> $board_key))?>
            <div class="form-control-search">
                <div class="input-group">
                    <input class="form-control" maxlength="255" name="query" placeholder="<?=langs('공통/search/search_placeholder')?>" value="<?=$query?>">
                    <span class="input-group-btn">
                        <button class="btn btn-default"><i class="far fa-search"></i> <?=langs('공통/search/search_submit')?></button>
                    </span>
                </div>
            </div>
            <?=form_close()?>
            <div class="H20"></div>
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach($search_result['title'] as $key=>$row) :?>
                <li <?=$board_key==$key?'class="active"':''?>><a href="<?=base_url("search?board_key={$key}&query=".urlencode($query))?>"><?=$search_result['title'][$key]?> <small>(<?=number_format($search_result['count'][$key])?>)</small></a></li>
                <?php endforeach;?>
            </ul>
        </div>
    </article>

    <div class="container">
        <?php
        // 통합검색에서 개별 카테고리 검색결과를 최대 4개씩 보여준다.
        foreach($search_result['list'] as $type=>$rows) :
            ?>
            <article class="search-result">
                <header>
                    <h2><?=$search_result['title'][$type]?> <?=langs("공통/search/search_result")?> (<span class="point-color"><?=$search_result['count'][$type]?></span>)</h2>
                    <span class="blue-line">&nbsp;</span>
                </header>
                <ul class="search-list media-list">
                    <?php foreach($search_result['list'][$type] as $row ):?>
                        <li class="media">
                            <?php if($row['post_thumbnail']):?>
                                <div class="media-left">
                                    <img src="<?=base_url($row['post_thumbnail'])?>">
                                </div>
                            <?php endif;?>
                            <div class="media-body">
                                <h4><a class="text-primary" href="<?=$row['link']?>"><?=$row['post_title']?></a></h4>
                                <h5><a class="text-success" href="<?=$row['link']?>"><?=$row['link']?></a></h5>
                                <p><?=cut_str(get_summary($row['post_content']),300)?></p>
                            </div>
                        </li>
                    <?php endforeach;?>
                </ul>
                <?php if($type != $board_key && $search_result['count'][$type] > 4 ):?>
                    <a class="more-btn" href="<?=base_url("search?board_key={$type}&query=".urlencode($query))?>"><?=langs('공통/search/search_more')?>  <i class="far fa-angle-double-right" aria-hidden="true"></i></a>
                <?php endif;?>
            </article>
            <?php
        endforeach;
        ?>

        <?php if($pagination) :?>
        <div class="text-center">
            <?php
            $param['first_link'] = '<i class="fa fa-caret-left"></i><i class="fa fa-caret-left "></i>';
            $param['prev_link'] = '<i class="fa fa-caret-left"></i>';
            $param['next_link'] = '<i class="fa fa-caret-right"></i>';
            $param['last_link'] = '<i class="fa fa-caret-right"></i><i class="fa fa-caret-right"></i>';
            echo $this->paging->create($param);
            ?>
        </div>
        <?php endif;?>
    </div>
</div>