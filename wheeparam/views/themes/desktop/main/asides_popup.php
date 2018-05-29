<style>
    .pop-layer { position:fixed; top:50%; left:50%; z-index:1000; background:#fff;  }
    .pop-layer .pop-content { border:1px solid #ddd; }
    .pop-layer .pop-footer { width:100%; background:#282828; height:30px; text-align:right; padding:0px 15px;}
    .pop-layer .pop-footer > a { display:inline-block; line-height:30px; color:#fff;}
    .pop-layer .pop-footer > a:hover { color:#d0d0d0; }
    .pop-layer .pop-footer > a + a { margin-left:30px; }
</style>
<?php
foreach($popup_list['list'] as $pop) :

    if( get_cookie('popup_'.$pop['pop_idx']) ) continue;

    if($pop['pop_type'] == 'N') :
        ?>
        <style>
            #popup-<?=$pop['pop_idx']?> { width:<?=$pop['pop_width']?>px; height:<?=$pop['pop_height']+30?>px; margin-left:-<?=$pop['pop_width']/2?>px; margin-top:-<?=$pop['pop_height']/2?>px; }
            #popup-<?=$pop['pop_idx']?> .pop-content { height:<?=$pop['pop_height']?>px; }
        </style>
        <div id="popup-<?=$pop['pop_idx']?>" class="pop-layer">
            <div class="pop-content">
                <?=$pop['pop_content']?>
            </div>
            <div class="pop-footer">
                <a href="javascript:;" data-toggle="btn-popup-close" data-idx="<?=$pop['pop_idx']?>" data-type="N" data-cookie="1"><?=langs('팝업/button/close_with_cookie')?></a>
                <a href="javascript:;" data-toggle="btn-popup-close" data-idx="<?=$pop['pop_idx']?>" data-type="N"><?=langs('팝업/button/close')?></a>
            </div>
        </div>
        <?php
    else :
        ?>
        <script>
            APP.POPUP({
                url : '/main/popup/<?=$pop['pop_idx']?>',
                width : <?=$pop['pop_width']?>,
                height : <?=(int)$pop['pop_height']?> + 30,
                title : 'popup_<?=$pop['pop_idx']?>'
            })
        </script>
        <?php
    endif;

endforeach;?>