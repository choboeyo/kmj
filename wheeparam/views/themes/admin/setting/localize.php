<div class="page-header">
    <h1 class="page-title">다국어 설정</h1>
</div>

<?=form_open_multipart("admin/setting/update", array("class"=>"form-flex"))?>
<input type="hidden" name="reurl" value="<?=base_url('admin/setting/localize')?>">
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h4 class="panel-title">다국어 기본설정</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label">기본 언어</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="setting[default_language]">
                            <option value="ko" <?=$this->site->config('default_language')=='ko'?'selected':''?>>한국어</option>
                            <option value="en" <?=$this->site->config('default_language')=='en'?'selected':''?>>English</option>
                            <option value="ja" <?=$this->site->config('default_language')=='ja'?'selected':''?>>일본어</option>
                            <option value="zh-hans" <?=$this->site->config('default_language')=='zh-hans'?'selected':''?>>중국어(간체)</option>
                            <option value="zh-hant" <?=$this->site->config('default_language')=='zh-hant'?'selected':''?>>중국어(번체)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">다국어 사용</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="setting[use_localize]">
                            <option value="Y" <?=$this->site->config('use_localize')=='Y'?'selected':''?>>사용</option>
                            <option value="N" <?=$this->site->config('use_localize')=='N'?'selected':''?>>미사용</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">사용할 언어</label>
                    <?php
                    $accept_lang = $this->site->config('accept_languages');
                    $accept_lang = explode(',', $accept_lang);
                    ?>
                    <div class="controls">
                        <label class="w-check"><input type="checkbox" value="ko" name="accept_language[]" <?=in_array('ko', $accept_lang)?'checked':''?>><span>한글</span></label>
                        <label class="w-check"><input type="checkbox" value="en" name="accept_language[]" <?=in_array('en', $accept_lang)?'checked':''?>><span>English</span></label>
                        <label class="w-check"><input type="checkbox" value="ja" name="accept_language[]" <?=in_array('ja', $accept_lang)?'checked':''?>><span>일본어</span></label>
                        <label class="w-check"><input type="checkbox" value="zh-hans" name="accept_language[]" <?=in_array('zh-hans', $accept_lang)?'checked':''?>><span>중국어(간체)</span></label>
                        <label class="w-check"><input type="checkbox" value="zh-hant" name="accept_language[]" <?=in_array('zh-hant', $accept_lang)?'checked':''?>><span>중국어(번체)</span></label>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button class="btn btn-primary">저장하기</button>
            </div>
        </div>

<?=form_close()?>

<script>
    $(function(){
        $('[name="accept_language[]"]').change(function(e){
            if( $(this).val() == $('[name="setting[default_language]"] option:selected').val() ) {
                $(this).prop('checked', true);
            }
        });
    });
</script>

<div class="H20"></div>

<?=form_open(NULL, array('autocomplete'=>'off'))?>
<input type="hidden" name="mode" value="u">
<div class="ax-button-group">
    <div class="left">
        <button class="btn btn-default" type="button" data-button="btn-add-localize">언어 스트링 추가</button>
    </div>
    <div class="right">
        <button class="btn btn-primary">저장하기</button>
    </div>
</div>


<ul class="nav nav-card-tabs">
    <?php foreach($tab_list as $row) :?>
        <li><a class="card<?=urldecode($active)==$row['keys']?' selected':''?>" href="<?=base_url('admin/setting/localize/'.$row['keys'])?>"><?=$row['keys']?></a></li>
    <?php endforeach;;?>
</ul>

<div data-ax5grid>
    <table>
        <thead>
        <tr>
            <th class="col-xs-2">KEY</th>
            <th class="col-xs-2">한글</th>
            <th class="col-xs-2">English</th>
            <th class="col-xs-2">일본어</th>
            <th class="col-xs-2">중국어(간체)</th>
            <th class="col-xs-2">중국어(번체)</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $row) :?>
            <tr>
                <td>
                    <input type="hidden" name="loc_key[]" value="<?=$row['loc_key']?>">
                    <span><?=$row['loc_key']?></span>
                </td>
                <td>
                    <input class="form-control" name="loc_value_ko[]" value="<?=$row['loc_value_ko']?>">
                </td>
                <td>
                    <input class="form-control" name="loc_value_en[]" value="<?=$row['loc_value_en']?>">
                </td>
                <td>
                    <input class="form-control" name="loc_value_ja[]" value="<?=$row['loc_value_ja']?>">
                </td>
                <td>
                    <input class="form-control" name="loc_value_zh-hans[]" value="<?=$row['loc_value_zh-hans']?>">
                </td>
                <td>
                    <input class="form-control" name="loc_value_zh-hant[]" value="<?=$row['loc_value_zh-hant']?>">
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<?=form_close()?>
<div class="H30"></div>
<script>
    $(function(){
        $('[data-button="btn-add-localize"]').click(function(){
            APP.MODAL.callback = function(){
                location.reload();
            };
            APP.MODAL.open({
                iframe : {
                    url : '/admin/setting/localize_form'
                },
                header : {
                    title : '언어 스트링 추가'
                }

            })
        });
    });
</script>