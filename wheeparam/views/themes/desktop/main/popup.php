<style>
    #contents {
        padding:0px;
    }
    .pop-content {
        height:<?=$view['pop_height']?>px;
    }
    .pop-footer {
        padding:0px 15px;
        background:#282828;
        height:30px;
        line-height:30px;
        text-align:right;
    }
    .pop-footer > a {
        color:#fff;
    }

    .pop-footer > a + a {
        margin-left:30px;
    }
</style>
<div class="pop-content">
    <?=$view['pop_content']?>
</div>
<div class="pop-footer">
    <a href="javascript:;" data-toggle="btn-popup-close" data-idx="<?=$view['pop_idx']?>" data-type="Y" data-cookie="1"><?=langs('팝업/button/close_with_cookie')?></a>
    <a href="javascript:;" data-toggle="btn-popup-close" data-idx="<?=$view['pop_idx']?>" data-type="Y"><?=langs('팝업/button/close')?></a>
</div>

