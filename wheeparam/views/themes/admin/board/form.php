<div class="page-header">
    <h1 class="page-title"><?=empty($brd_key)?'게시판 신규 등록': "[{$view['brd_title']}] 게시판 정보 수정";?></h1>
</div>
<?=validation_errors('<p class="alert alert-danger">')?>
<?=form_open(NULL, array("autocomplete"=>"off","data-form"=>"board-form",'class'=>'form-flex'))?>
<div data-ax-tbl>
    <div class="caption">게시판 기본 설정</div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>고유 키</div>
            <div data-ax-td-wrap>
                <input class="form-control form-control-inline" maxlength="20" name="brd_key" value="<?=element('brd_key', $view)?>" <?=$brd_key?'readonly':'required'?>>
                <p class="help-block">4-20자의 영어소문자</p>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-wrap>
                <?php if (empty($brd_key)) : ?>
                    <button type="button" class="btn btn-default ML10" id="btn-check-brd-key"><i class="fal fa-check"></i> 중복 확인</button>
                <?php endif;?>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>게시판 이름</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="brd_title" maxlength="30" value="<?=element('brd_title', $view)?>" required>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>게시판 형태</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_type">
                    <option value="list" <?=element('brd_type', $view)=='list'?'selected':''?>>목록형</option>
                    <option value="gallery" <?=element('brd_type', $view)=='gallery'?'selected':''?>>앨범형</option>
                    <option value="webzine" <?=element('brd_type', $view)=='webzine'?'selected':''?>>웹진형</option>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>키워드</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="brd_keywords" value="<?=element('brd_keywords', $view)?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>요약설명</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="brd_description" data-autosize><?=element('brd_description', $view)?></textarea>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>카테고리</div>
            <div data-ax-td-wrap>
                <label class="w-check">
                    <input type="checkbox" name="brd_use_category" value="Y" <?=element('brd_use_category', $view)=='Y'?'checked':''?>><span>기능 사용</span>
                </label>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>카테고리목록</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="brd_category" value="<?=element('brd_category', $view)?>" maxlength="255">
                <p class="help-block">카테고리를 세미콜론(;)으로 여러개를 입력해주세요. ex) 자유;정보;잡담</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>시간표시형식</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="brd_display_time">
                    <option value="sns"  <?=element('brd_use_anonymous',$view)=='sns'?'selected':''?>>SNS형식</option>
                    <option value="basic"  <?=element('brd_use_anonymous',$view)=='basic'?'selected':''?>>기본형</option>
                    <option value="full"  <?=element('brd_use_anonymous',$view)=='full'?'selected':''?>>전체표시</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="H10"></div>

<div data-ax-tbl>
    <div class="caption">페이지 기능</div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>페이지 기능</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="brd_page_limit">
                    <option value="Y" <?=element('brd_page_limit',$view,'Y')=='Y'?'selected':''?>>사용</option>
                    <option value="N" <?=element('brd_page_limit',$view,'Y')=='N'?'selected':''?>>미사용</option>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>페이지당글수</div>
            <div data-ax-td-wrap>
                <input type="number" min="0" class="form-control form-control-inline" name="brd_page_rows" value="<?=element('brd_page_rows', $view, 15)?>" required>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>모바일</div>
            <div data-ax-td-wrap>
                <input type="number" min="0" class="form-control form-control-inline" name="brd_page_rows_m" value="<?=element('brd_page_rows_m', $view, 10)?>" required>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>한번에 표시 페이지</div>
            <div data-ax-td-wrap>
                <input type="number" min="0" class="form-control" name="brd_fixed_num" value="<?=element('brd_fixed_num', $view, 10)?>" required>
            </div>
        </div>

        <div data-ax-td>
            <div data-ax-td-label>모바일</div>
            <div data-ax-td-wrap>
                <input type="number" min="0" class="form-control" name="brd_fixed_num_m" value="<?=element('brd_fixed_num_m', $view, 10)?>" required>
            </div>
        </div>
    </div>
</div>

<div class="H10"></div>

<div data-ax-tbl>
    <div class="caption">게시판 스킨 설정</div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>목록 스킨</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_skin_l">
                    <?php foreach($skin_list_l as $skin) : ?>
                        <option value="<?=$skin?>" <?=$skin==element('brd_skin_l',$view)?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>모바일</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_skin_l_m">
                    <?php foreach($skin_list_l as $skin) : ?>
                        <option value="<?=$skin?>" <?=$skin==element('brd_skin_l_m',$view)?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>글쓰기 스킨</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_skin_w">
                    <?php foreach($skin_list_w as $skin) : ?>
                        <option value="<?=$skin?>" <?=$skin==element('brd_skin_w',$view)?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>모바일</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_skin_w_m">
                    <?php foreach($skin_list_w as $skin) : ?>
                        <option value="<?=$skin?>" <?=$skin==element('brd_skin_w_m',$view)?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>글내용 보기 스킨</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_skin_v">
                    <?php foreach($skin_list_v as $skin) : ?>
                        <option value="<?=$skin?>" <?=$skin==element('brd_skin_v',$view)?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>모바일</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_skin_v_m">
                    <?php foreach($skin_list_v as $skin) : ?>
                        <option value="<?=$skin?>" <?=$skin==element('brd_skin_v_m',$view)?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>댓글 스킨</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_skin_c">
                    <?php foreach($skin_list_c as $skin) : ?>
                        <option value="<?=$skin?>" <?=$skin==element('brd_skin_c',$view)?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>모바일</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_skin_c_m">
                    <?php foreach($skin_list_c as $skin) : ?>
                        <option value="<?=$skin?>" <?=$skin==element('brd_skin_c_m',$view)?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="H10"></div>

<div data-ax-tbl>
    <div class="caption">게시판 권한 설정</div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>목록 보기</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_lv_list">
                    <?php for($i=0; $i<=10; $i++):?>
                        <option value="<?=$i?>" <?=$i==element('brd_lv_list', $view, 0)?'selected':''?>><?=$i?><?=$i==0?' (비회원)':''?></option>
                    <?php endfor;?>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>글 작성</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_lv_write">
                    <?php for($i=0; $i<=10; $i++):?>
                        <option value="<?=$i?>" <?=$i==element('brd_lv_write', $view, 0)?'selected':''?>><?=$i?><?=$i==0?' (비회원)':''?></option>
                    <?php endfor;?>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>글 내용 보기</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_lv_read">
                    <?php for($i=0; $i<=10; $i++):?>
                        <option value="<?=$i?>" <?=$i==element('brd_lv_read', $view, 0)?'selected':''?>><?=$i?><?=$i==0?' (비회원)':''?></option>
                    <?php endfor;?>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>답글 작성</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_lv_reply">
                    <?php for($i=0; $i<=10; $i++):?>
                        <option value="<?=$i?>" <?=$i==element('brd_lv_reply', $view, 0)?'selected':''?>><?=$i?><?=$i==0?' (비회원)':''?></option>
                    <?php endfor;?>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>댓글 작성</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_lv_comment">
                    <?php for($i=0; $i<=10; $i++):?>
                        <option value="<?=$i?>" <?=$i==element('brd_lv_comment', $view, 0)?'selected':''?>><?=$i?><?=$i==0?' (비회원)':''?></option>
                    <?php endfor;?>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>첨부파일다운</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_lv_download">
                    <?php for($i=0; $i<=10; $i++):?>
                        <option value="<?=$i?>" <?=$i==element('brd_lv_download', $view, 0)?'selected':''?>><?=$i?><?=$i==0?' (비회원)':''?></option>
                    <?php endfor;?>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="H10"></div>

<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>답글 기능</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="brd_use_reply">
                    <option value="Y" <?=element('brd_use_reply',$view)=='Y'?'selected':''?>>사용</option>
                    <option value="N" <?=element('brd_use_reply',$view)=='N'?'selected':''?>>미사용</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>댓글 기능</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="brd_use_comment">
                    <option value="Y" <?=element('brd_use_comment',$view)=='Y'?'selected':''?>>사용</option>
                    <option value="N" <?=element('brd_use_comment',$view)=='N'?'selected':''?>>미사용</option>
                </select>
            </div>
        </div>
        <div data-ax-td></div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>익명 기능</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="brd_use_anonymous">
                    <option value="Y" <?=element('brd_use_anonymous',$view)=='Y'?'selected':''?>>사용</option>
                    <option value="N" <?=element('brd_use_anonymous',$view)=='N'?'selected':''?>>미사용</option>
                    <option value="A" <?=element('brd_use_anonymous',$view)=='A'?'selected':''?>>항상 사용</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>비밀글 기능</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="brd_use_secret">
                    <option value="Y" <?=element('brd_use_secret',$view)=='Y'?'selected':''?>>사용</option>
                    <option value="N" <?=element('brd_use_secret',$view)=='N'?'selected':''?>>미사용</option>
                    <option value="A" <?=element('brd_use_secret',$view)=='A'?'selected':''?>>항상 사용</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>
                <div>
                    이름 * 처리
                    <p class="help-block">관리자<i class="fal fa-caret-right"></i>관*자</p>
                </div>
            </div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="brd_blind_nickname">
                    <option value="Y" <?=element('brd_blind_nickname',$view, 'N')=='Y'?'selected':''?>>사용</option>
                    <option value="N" <?=element('brd_blind_nickname',$view, 'N')=='N'?'selected':''?>>미사용</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="H10"></div>

<?php if($this->site->config('point_use') != 'Y') :?>
<p class="alert alert-info"><i class="fal fa-info-circle"></i> <?=$this->site->config('point_name')?> 기능을 사용 하는경우만 아래 옵션이 활성화 됩니다.</p>
<div class="H10"></div>
<?php endif;?>
<div data-ax-tbl>
    <div class="caption"><?=$this->site->config('point_name')?> 설정</div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>글쓰기<br><?=$this->site->config('point_name')?></div>
            <div data-ax-td-wrap>
                <input type="number" class="form-control text-right" name="brd_point_write" value="<?=element('brd_point_write', $view, 0)?>" <?=$this->site->config('point_use') != 'Y'?'readonly':''?>>
            </div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_point_write_flag">
                    <option value="1" <?=element('brd_point_write_flag', $view, 1)=='1'?'selected':''?>>증가</option>
                    <option value="-1" <?=element('brd_point_write_flag', $view, 1)=='-1'?'selected':''?>>차감</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>답글쓰기<br><?=$this->site->config('point_name')?></div>
            <div data-ax-td-wrap>
                <input type="number" class="form-control text-right" name="brd_point_reply" value="<?=element('brd_point_reply', $view, 0)?>" <?=$this->site->config('point_use') != 'Y'?'readonly':''?>>
            </div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_point_reply_flag">
                    <option value="1" <?=element('brd_point_reply_flag', $view, 1)=='1'?'selected':''?>>증가</option>
                    <option value="-1" <?=element('brd_point_reply_flag', $view, 1)=='-1'?'selected':''?>>차감</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>댓글쓰기<br><?=$this->site->config('point_name')?></div>
            <div data-ax-td-wrap>
                <input type="number" class="form-control text-right" name="brd_point_comment" value="<?=element('brd_point_comment', $view, 0)?>" <?=$this->site->config('point_use') != 'Y'?'readonly':''?>>
            </div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_point_comment_flag">
                    <option value="1" <?=element('brd_point_comment_flag', $view, 1)=='1'?'selected':''?>>증가</option>
                    <option value="-1" <?=element('brd_point_comment_flag', $view, 1)=='-1'?'selected':''?>>차감</option>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>글 내용 보기</div>
            <div data-ax-td-wrap>
                <input type="number" class="form-control text-right" name="brd_point_read" value="<?=element('brd_point_read', $view, 0)?>" <?=$this->site->config('point_use') != 'Y'?'readonly':''?>>
            </div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_point_read_flag">
                    <option value="1" <?=element('brd_point_read_flag', $view, -1)=='1'?'selected':''?>>증가</option>
                    <option value="-1" <?=element('brd_point_read_flag', $view, -1)=='-1'?'selected':''?>>차감</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>첨부파일<br>다운</div>
            <div data-ax-td-wrap>
                <input type="number" class="form-control text-right" name="brd_point_download" value="<?=element('brd_point_download', $view, 0)?>" <?=$this->site->config('point_use') != 'Y'?'readonly':''?>>
            </div>
            <div data-ax-td-wrap>
                <select class="form-control" name="brd_point_download_flag">
                    <option value="1" <?=element('brd_point_download_flag', $view, -1)=='1'?'selected':''?>>증가</option>
                    <option value="-1" <?=element('brd_point_download_flag', $view, -1)=='-1'?'selected':''?>>차감</option>
                </select>
            </div>
        </div>
        <div data-ax-td></div>
    </div>
</div>

<div class="text-center MT15">
    <button class="btn btn-primary"><i class="fal fa-save"></i> 저장하기</button>
</div>
<?=form_close()?>
<div class="H30"></div>

<script>
$(document).ready(function(){
    $('[name="brd_use_category"]').change(function() {
       var checked = $(this).prop('checked');

       if( checked ) $('[name="brd_category"]').removeAttr('disabled');
       else $('[name="brd_category"]').attr('disabled','disabled');
    }).change();

    $("select[name='brd_page_limit']").change(function(){
        if( $(this).find('option:selected').val() == 'Y' )
        {
            $("input[name='brd_page_rows'], input[name='brd_page_rows_m'], input[name='brd_fixed_num'], input[name='brd_fixed_num_m']").removeAttr('readonly');
        }
        else {
            $("input[name='brd_page_rows'], input[name='brd_page_rows_m'], input[name='brd_fixed_num'], input[name='brd_fixed_num_m']").attr('readonly', 'readonly');
        }
    }).change();

    $("select[name='brd_use_list_thumbnail']").change(function(){
        if( $(this).find('option:selected').val() == 'Y' )
        {
            $("input[name='brd_thumb_width'], input[name='brd_thumb_height']").removeAttr('readonly');
        }
        else {
            $("input[name='brd_thumb_width'], input[name='brd_thumb_height']").attr('readonly', 'readonly');
        }
    }).change();

    $("#btn-check-brd-key").click(brd_key_check);
});

var brd_key_check = function(){
    var $el = $("input[name='brd_key']");
    var value = $el.val().trim();
    var check = APP.BOARD.keyCheck(value);
    if( check === true )
    {
        alert('사용가능한 키 입니다.');
    }
    else {
        alert(check);
        $el.focus();
    }
}
</script>