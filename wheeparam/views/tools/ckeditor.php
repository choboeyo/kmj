<textarea name="<?=$name?>" class="ckeditor" id="<?=$id?>" style="width:100%;height:<?=$height?>"><?=$contents?></textarea>
<?php if(PAGE_ADMIN) :?>
<script>
    CKEDITOR.on('instanceReady', function() {
        $('#main .main').niceScroll().resize();
    });
</script>
<?php endif;?>
