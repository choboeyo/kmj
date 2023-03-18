<div class="review-popup-layer">
    <div class="review-popup-inner">
        <?=$form_open?>
        <?php if(empty(element('od_id', $view))):?>
        <div class="review-form-group">
            <label>주문내역 선택</label>
            <select class="review-write-input" name="od_id">
                <?php foreach($order_list as $order):?>
                <option value="<?=$order['od_id']?>" <?=element('od_id', $view,'')==$order['od_id']?'selected':''?>>[<?=$order['od_id']?>] <?=count($order['buy_option'])>0?implode(" , ", $order['buy_option']):$order['prd_name']?></option>
                <?php endforeach;?>
            </select>
        </div>
        <?php else :?>
        <input type="hidden" name="od_id" value="<?=element('od_id', $view)?>">
        <?php endif;?>

        <div class="review-form-group">
            <label>평점</label>
            <select class="review-write-input" name="rev_score">
                <?php for($k=5; $k>0; $k--):?>
                    <option value="<?=$k?>" <?=element('rev_score',$view,5)==$k?'selected':''?>><?php for($i=0; $i<$k; $i++):?>★<?php endfor;?><?php for($i=$k; $i<5; $i++):?>☆<?php endfor;?></option>
                <?php endfor;?>
            </select>
        </div>
        <div class="review-form-group">
            <label>리뷰 작성</label>
            <textarea class="review-write-input" rows="10" name="rev_content"><?=element('rev_content',$view)?></textarea>
        </div>
        <div class="review-form-group">
            <label>이미지 업로드</label>
            <ul class="review-image-list">
                <?php if(count(element('images', $view, []))>0):?>
                <?php foreach($view['images'] as $image):?>
                    <li>
                        <div class="inner">
                            <figure>
                                <img src="<?=base_url($image['att_filepath'])?>" alt="">
                            </figure>
                        </div>
                    </li>
                <?php endforeach;?>
                <?php endif;?>
                <li>
                    <div class="inner">
                        <label class="upload-toggle" for="review-image-upload-input"><i class="fas fa-plus"></i></label>
                    </div>
                </li>
            </ul>
            <input type="file" id="review-image-upload-input" name="userfile" accept="image/*" multiple>
        </div>
        <div class="review-action">
            <button type="submit" class="btn-review-submit">작성하기</button>
            <button type="button" class="btn-review-close" onclick="APP.SHOP.closeReviewWrite()">닫기</button>
        </div>
        <?=$form_close?>
    </div>
</div>

<script>
    /**
     * 이미지 업로드 처리
     */
    $(function() {
        $('#review-image-upload-input').change(function(e) {
            e.preventDefault();

            var value = $(this).val();
            if(value === '') {
                return false;
            }
            var files = $(this)[0].files;

            if(files.length === 0) {
                return false;
            }

            for(var i=0; i<files.length; i++) {
                var formData  = new FormData();
                formData.append('userfile', files[i]);

                $.ajax({
                    url: base_url + '/ajax/products/reviews_images',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        var $container = $('.review-image-list');
                        var $li = $('<li>');
                        var $div = $('<div>').addClass('inner');
                        var $input = $('<input>').attr({type:'hidden', name:'images[]', value:res.att_idx})
                        var $figure = $('<figure>');
                        var $img = $('<img>').attr('src', res.file_path)

                        $figure.append($img);
                        $div.append($figure, $input);
                        $li.append($div)
                        $container.prepend($li);
                    }
                })
            }
        })
    })
</script>
