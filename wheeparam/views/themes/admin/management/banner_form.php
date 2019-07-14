<?=form_open_multipart(NULL ,array('class'=>'form-flex','autocomplete'=>'off'))?>
<input type="hidden" name="bng_key" value="<?=$bng_key?>">
<input type="hidden" name="ban_idx" value="<?=element('ban_idx', $view)?>">
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>배너 이름</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="ban_name" value="<?=element('ban_name', $view)?>" required maxlength="50">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>배너 표시/숨김</div>
            <div data-ax-td-wrap>
                <label class="w-radio"><input type="radio" name="ban_status" value="Y" <?=element('ban_status',$view,'Y')=='Y'?'checked':''?>><span>표시</span></label>
                <label class="w-radio"><input type="radio" name="ban_status" value="H" <?=element('ban_status',$view,'Y')=='H'?'checked':''?>><span>숨김</span></label>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>배너 파일</div>
            <div data-ax-td-wrap>
                <?=thumb_img(element('ban_filepath', $view),'img-responsive MB5')?>
                <input type="file" class="form-control" name="userfile">
                <?php if($banner_group['bng_width'] > 0) :?>
                    <p class="help-block">권장 너비 : <?=$banner_group['bng_width']?>px</p>
                <?php endif;?>
                <?php if($banner_group['bng_height'] > 0) :?>
                    <p class="help-block">권장 높이 : <?=$banner_group['bng_height']?>px</p>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

<?php if($banner_group['bng_ext1_use'] == 'Y' OR $banner_group['bng_ext2_use'] == 'Y' OR $banner_group['bng_ext3_use'] == 'Y' OR $banner_group['bng_ext4_use'] == 'Y' OR $banner_group['bng_ext5_use'] == 'Y') :?>
    <div class="H10"></div>
<div data-ax-tbl>
    <?php endif;?>

    <?php for($i=1; $i<=5; $i++):

        if( $banner_group["bng_ext{$i}_use"] === 'Y' ) :
            ?>
            <div data-ax-tr>
                <div data-ax-td class="width-100">
                    <div data-ax-td-label><?=$banner_group["bng_ext{$i}"]?></div>
                    <div data-ax-td-wrap>
                        <input class="form-control form-control-inline" name="ban_ext<?=$i?>" value="<?=element('ban_ext'.$i, $view)?>">
                    </div>
                </div>
            </div>
        <?php
        endif;
    endfor;
    ?>
    <?php if($banner_group['bng_ext1_use'] == 'Y' OR $banner_group['bng_ext2_use'] == 'Y' OR $banner_group['bng_ext3_use'] == 'Y' OR $banner_group['bng_ext4_use'] == 'Y' OR $banner_group['bng_ext5_use'] == 'Y') :?>
</div>
<?php endif;?>

<div class="H10"></div>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>클릭시 이동</div>
            <div data-ax-td-wrap>
                <label class="w-radio"><input type="radio" name="ban_link_use" value="Y" <?=element('ban_link_use',$view,'N')=='Y'?'checked':''?>><span>사용</span></label>
                <label class="w-radio"><input type="radio" name="ban_link_use" value="N" <?=element('ban_link_use',$view,'N')=='N'?'checked':''?>><span>미사용</span></label>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>이동 URL</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="ban_link_url" value="<?=element('ban_link_url', $view)?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>이동 방식</div>
            <div data-ax-td-wrap>
                <label class="w-radio"><input type="radio" name="ban_link_type" value="Y" <?=element('ban_link_type',$view,'N')=='Y'?'checked':''?>><span>새 탭으로</span></label>
                <label class="w-radio"><input type="radio" name="ban_link_type" value="N" <?=element('ban_link_type',$view,'N')=='N'?'checked':''?>><span>현재창에서</span></label>
            </div>
        </div>
    </div>
</div>

<div class="H10"></div>
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>표시 기간</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="ban_timer_use">
                    <option value="Y" <?=element('ban_timer_use', $view,'N')=='Y'?'selected':''?>>시간지정</option>
                    <option value="N" <?=element('ban_timer_use', $view,'N')=='N'?'selected':''?>>항상표시</option>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>표시 시작시간</div>
            <div data-ax-td-wrap>
                <div data-toggle="datetime-picker" data-type="datetime" data-name="ban_timer_start" data-value="<?=element('ban_timer_start', $view, set_value('pop_start'))?>"></div>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>표시 종료시간</div>
            <div data-ax-td-wrap>
                <div data-toggle="datetime-picker" data-type="datetime" data-name="ban_timer_end" data-value="<?=element('ban_timer_end', $view, set_value('pop_end'))?>"></div>
            </div>
        </div>
    </div>
</div>

<div class="text-center MT10">
    <button class="btn btn-primary">저장하기</button>
</div>
<?=form_close()?>

<script>
    $(function(){


        $('input[name="ban_link_use"]').change(function(){
            if( $('input[name="ban_link_use"]:checked').val() == 'Y' ) {
                $('input[name="ban_link_url"]').removeAttr('disabled');
                $('input[name="ban_link_type"]').removeAttr('disabled');
            }
            else {
                $('input[name="ban_link_url"]').attr('disabled','disabled');
                $('input[name="ban_link_type"]').attr('disabled','disabled');
            }
        }).change();

        $('[data-toggle="datetime-picker"]').each(function() {
            var name = $(this).data('name'),
                value = $(this).data('value') && $(this).data('value') != '0000-00-00 00:00:00' ? new Date($(this).data('value')) : (new Date()).dateFormat('yyyy-MM-dd 00:00'),
                type = $(this).data('type');

            $(this).dxDateBox({
                type: type,
                value: value,
                displayFormat: type == 'datetime' ? "yyyy-MM-dd HH:mm" : 'yyyy-MM-dd',
                applyButtonText:'적용',
                cancelButtonText:'취소'
            });
            $(this).find('input').attr('name', name);
        });


        $('select[name="ban_timer_use"]').change(function(){
            if( $(this).find('option:selected').val() == 'Y' )
            {
                $('[data-toggle="datetime-picker"]').dxDateBox('option', 'disabled', false);
                //$('[name="ban_timer_start"],[name="ban_timer_end"]').removeAttr('disabled');
            }
            else {
                $('[data-toggle="datetime-picker"]').dxDateBox('option', 'disabled', true);
                //$('[name="ban_timer_start"],[name="ban_timer_end"]').val('').attr('disabled','disabled');
            }
        }).change();
    });
</script>