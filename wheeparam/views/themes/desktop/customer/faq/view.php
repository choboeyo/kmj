<div class="container">
    <div class="page-header">
        <h1 class="page-title"><?=$this->site->config('faq_title')?></h1>
        <p><?=$this->site->config('faq_description')?></p>
    </div>
    <div class="row">

        <div class="col-sm-4 col-md-3 col-lg-2">
            <!-- START:: FAQ 분류 목록 -->
            <ul class="nav nav-pills nav-stacked">
                <li class="<?=($current_category=="")?'active':''?>"><a href="<?=base_url('customer/faq')?>">전체보기 (<?=$total_count?>)</a></li>
                <?php foreach($faq_category_list as $cat) : ?>
                    <li class="<?=$cat['active']?>"><a href="<?=$cat['link']?>"><?=$cat['title']?> (<?=$cat['count']?>)</a></li>
                <?php endforeach;?>
            </ul>
            <!-- END:: FAQ 분류 목록 -->
        </div>

        <div class="col-sm-8 col-md-9 col-lg-10">

            <!-- START:: FAQ 내용-->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title"><?=$view['faq_title']?></h4>
                </div>
                <div class="panel-body">
                    <?=display_html_content($view['faq_content'], 700)?>
                </div>
                <div class="panel-footer">
                    <a class="btn btn-default" href="<?=$link_list?>">목록보기</a>
                </div>
            </div>
            <!-- END:: FAQ 내용-->


            <div class="panel panel-default">
                <!-- START:: FAQ 실제 목록-->
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>번호</th>
                        <th>제목</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($faq_list['list'] as $row) :?>
                        <tr>
                            <td><?=$row['nums']?></td>
                            <td><a href="<?=$row['link']?>"><?=$row['faq_title']?></a></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                <!-- END:: FAQ 실제 목록-->
            </div>
        </div>
    </div>
</div>