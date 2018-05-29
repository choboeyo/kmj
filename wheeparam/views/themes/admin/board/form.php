<div class="page-header">
    <h1 class="page-title"><?=empty($brd_key)?'게시판 신규 등록': "[{$view['brd_title']}] 게시판 정보 수정";?></h1>
</div>
<?=validation_errors('<p class="alert alert-danger">')?>
<?=form_open(NULL, array("autocomplete"=>"off","data-form"=>"board-form",'class'=>'form-flex'))?>
<div class="row">
    <div class="col-sm-3">
        <ul class="nav nav-cards">
            <li role="presentation"><a class="card selected"><?=empty($brd_key)?'게시판 신규 등록': "게시판 정보 수정";?></a></li>
            <?php if($brd_key) : ?>
            <li role="presentation"><a class="card" href="<?=base_url('admin/board/category/'.$brd_key)?>">카테고리 설정</a></li>
            <?php endif;?>
        </ul>
    </div>
    <div class="col-sm-9">
        
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h4 class="panel-title">기본 설정</h4>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <label class="control-label">게시판 고유 키</label>
                    <div class="controls">
                        <input class="form-control form-control-inline" maxlength="20" name="brd_key" value="<?=element('brd_key', $view)?>" <?=$brd_key?'readonly':'required'?>>
                        <?php if (empty($brd_key)) : ?>
                            <button type="button" class="btn btn-default ML10" id="btn-check-brd-key"><i class="far fa-check"></i> 중복 확인</button>
                        <?php endif;?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">게시판 이름</label>
                    <div class="controls">
                        <input class="form-control" name="brd_title" value="<?=element('brd_title', $view)?>" required>
                    </div>
                    <label class="control-label">게시판 이름 (모바일)</label>
                    <div class="controls">
                        <input class="form-control" name="brd_title_m" value="<?=element('brd_title_m', $view)?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">게시판 키워드</label>
                    <div class="controls">
                        <input class="form-control" name="brd_keywords" value="<?=element('brd_keywords', $view)?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">요약 설명</label>
                    <div class="controls">
                        <textarea class="form-control" name="brd_description" rows="4"><?=element('brd_description', $view)?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">게시판 스킨</label>
                    <div class="controls">
                        <select class="form-control" name="brd_skin">
                            <?php foreach($skin_list as $skin) : ?>
                                <option value="<?=$skin?>" <?=$skin==element('brd_skin',$view)?'selected':''?>><?=$skin?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <label class="control-label">게시판 스킨 (모바일)</label>
                    <div class="controls">
                        <select class="form-control" name="brd_skin_m">
                            <?php foreach($skin_list as $skin) : ?>
                                <option value="<?=$skin?>" <?=$skin==element('brd_skin_m',$view)?'selected':''?>><?=$skin?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">검색 설정</label>
                    <div class="controls">
                        <label class="w-check">
                            <input type="checkbox" name="brd_search" value="Y" <?=element('brd_search', $view, 'Y')=='Y'?'checked':''?>><span>전체 검색시 게시판의 글 노출</span>
                        </label>
                    </div>
                    <label class="control-label">노출 순서</label>
                    <div class="controls">
                        <input type="number" class="form-control" name="brd_sort" value="<?=element('brd_sort', $view, 0)?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">카테고리 기능 사용</label>
                    <div class="controls">
                        <label class="w-check">
                            <input type="checkbox" name="brd_use_category" value="Y" <?=element('brd_use_category', $view)=='Y'?'checked':''?>><span>카테고리 기능 사용</span>
                        </label>
                    </div>
                </div>

            </div>
        </div>
        
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h4 class="panel-title">게시판 권한 설정</h4>
            </div>
            <div class="panel-body">
                <p class="alert alert-info"><i class="far fa-info-circle"></i> 레벨0은 비회원을 의미합니다.</p>
                <div class="form-group">
                    <label class="control-label">목록 보기</label>
                    <div class="controls">
                        <select class="form-control" name="brd_lv_list">
                            <?php for($i=0; $i<=10; $i++):?>
                                <option value="<?=$i?>" <?=$i==element('brd_lv_list', $view, 0)?'selected':''?>><?=$i?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <label class="control-label">글 내용 보기</label>
                    <div class="controls">
                        <select class="form-control" name="brd_lv_read">
                            <?php for($i=0; $i<=10; $i++):?>
                                <option value="<?=$i?>" <?=$i==element('brd_lv_read', $view, 0)?'selected':''?>><?=$i?></option>
                            <?php endfor;?>
                        </select>
                    </div>

                </div>

                <div class="form-group">
                    <label class="control-label">글 작성하기</label>
                    <div class="controls">
                        <select class="form-control" name="brd_lv_write">
                            <?php for($i=0; $i<=10; $i++):?>
                                <option value="<?=$i?>" <?=$i==element('brd_lv_write', $view, 0)?'selected':''?>><?=$i?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <label class="control-label">답변글 작성</label>
                    <div class="controls">
                        <select class="form-control" name="brd_lv_reply">
                            <?php for($i=0; $i<=10; $i++):?>
                                <option value="<?=$i?>" <?=$i==element('brd_lv_reply', $view, 0)?'selected':''?>><?=$i?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">댓글 작성</label>
                    <div class="controls">
                        <select class="form-control" name="brd_lv_comment">
                            <?php for($i=0; $i<=10; $i++):?>
                                <option value="<?=$i?>" <?=$i==element('brd_lv_comment', $view, 0)?'selected':''?>><?=$i?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <label class="control-label">첨부파일 다운로드</label>
                    <div class="controls">
                        <select class="form-control" name="brd_lv_download">
                            <?php for($i=0; $i<=10; $i++):?>
                                <option value="<?=$i?>" <?=$i==element('brd_lv_download', $view, 0)?'selected':''?>><?=$i?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">첨부파일 업로드</label>
                    <div class="controls">
                        <select class="form-control" name="brd_lv_upload">
                            <?php for($i=0; $i<=10; $i++):?>
                                <option value="<?=$i?>" <?=$i==element('brd_lv_upload', $view, 0)?'selected':''?>><?=$i?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <label class="control-label"></label>
                    <div class="controls"></div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h4 class="panel-title">글 목록 설정</h4>
            </div>
            <div class="panel-body">
                <p class="alert alert-info"><i class="far fa-info-circle"></i> 갤러리 형식의 게시판에서는 '목록에서 썸내일 불러오기' / '목록에서 첨부파일 불러오기'를 활성화 하세요.</p>
                <div class="form-group">
                    <label class="control-label">썸네일 생성하기</label>
                    <div class="controls">
                        <select class="form-control" name="brd_use_list_thumbnail">
                            <option value="Y" <?=element('brd_use_list_thumbnail',$view,'N')=='Y'?'selected':''?>>생성하기</option>
                            <option value="N" <?=element('brd_use_list_thumbnail',$view,'N')=='N'?'selected':''?>>생성하지 않기</option>
                        </select>
                    </div>

                    <label class="control-label">썸네일 너비</label>
                    <div class="controls">
                        <input type="number" class="form-control" name="brd_thumb_width" value="<?=element('brd_thumb_width',$view,300)?>" min="0">
                    </div>
                    <label class="control-label">썸네일 높이</label>
                    <div class="controls">
                        <input type="number" class="form-control" name="brd_thumb_height" value="<?=element('brd_thumb_height',$view,300)?>" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">첨부파일 목록 불러오기</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_list_file">
                            <option value="Y" <?=element('brd_use_list_file',$view,'N')=='Y'?'selected':''?>>불러오기</option>
                            <option value="N" <?=element('brd_use_list_file',$view,'N')=='N'?'selected':''?>>불러오지 않기</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">NEW 표시 시간</label>
                    <div class="controls">
                        <input type="number" class="form-control form-control-inline" name="brd_time_new" value="<?=element('brd_time_new', $view, 24)?>" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">HIT 표시 조회수</label>
                    <div class="controls">
                        <input type="number" class="form-control form-control-inline" name="brd_hit_count" value="<?=element('brd_hit_count', $view, 500)?>" min="0">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h4 class="panel-title">글 내용보기 설정</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label">내용보기 에서 글 목록</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_view_list">
                            <option value="Y" <?=element('brd_use_view_list',$view,'N')=='Y'?'selected':''?>>불러오기</option>
                            <option value="N" <?=element('brd_use_view_list',$view,'N')=='N'?'selected':''?>>불러오지 않기</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h4 class="panel-title">페이지네이션 기능</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label">기능 사용여부</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_page_limit">
                            <option value="Y" <?=element('brd_page_limit',$view,'Y')=='Y'?'selected':''?>>사용</option>
                            <option value="N" <?=element('brd_page_limit',$view,'Y')=='N'?'selected':''?>>미사용</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">페이지 당 글 수</label>
                    <div class="controls">
                        <input type="number" class="form-control form-control-inline" name="brd_page_rows" value="<?=element('brd_page_rows', $view, 15)?>">
                    </div>
                    <label class="control-label">페이지 당 글 수 (모바일)</label>
                    <div class="controls">
                        <input type="number" class="form-control form-control-inline" name="brd_page_rows_m" value="<?=element('brd_page_rows_m', $view, 10)?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">표시할 페이지 수</label>
                    <div class="controls">
                        <input type="number" class="form-control form-control-inline" name="brd_fixed_num" value="<?=element('brd_fixed_num', $view, 10)?>">
                    </div>
                    <label class="control-label">표시할 페이지 수 (모바일)</label>
                    <div class="controls">
                        <input type="number" class="form-control form-control-inline" name="brd_fixed_num_m" value="<?=element('brd_fixed_num_m', $view, 5)?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h4 class="panel-title">게시판 기능 설정</h4>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <label class="control-label">시간 표시 형식</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_display_time">
                            <option value="sns"  <?=element('brd_use_anonymous',$view)=='sns'?'selected':''?>>SNS형식</option>
                            <option value="basic"  <?=element('brd_use_anonymous',$view)=='basic'?'selected':''?>>기본형</option>
                            <option value="full"  <?=element('brd_use_anonymous',$view)=='full'?'selected':''?>>전체표시</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">익명 기능 사용</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_anonymous">
                            <option value="Y" <?=element('brd_use_anonymous',$view)=='Y'?'selected':''?>>사용</option>
                            <option value="N" <?=element('brd_use_anonymous',$view)=='N'?'selected':''?>>미사용</option>
                            <option value="A" <?=element('brd_use_anonymous',$view)=='A'?'selected':''?>>항상 사용</option>
                        </select>
                    </div>
                    <label class="control-label">비밀글 기능 사용</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_secret">
                            <option value="Y" <?=element('brd_use_secret',$view)=='Y'?'selected':''?>>사용</option>
                            <option value="N" <?=element('brd_use_secret',$view)=='N'?'selected':''?>>미사용</option>
                            <option value="A" <?=element('brd_use_secret',$view)=='A'?'selected':''?>>항상 사용</option>
                        </select>
                    </div>
                    <label class="control-label">답글 기능 사용</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_reply">
                            <option value="Y" <?=element('brd_use_reply',$view)=='Y'?'selected':''?>>사용</option>
                            <option value="N" <?=element('brd_use_reply',$view)=='N'?'selected':''?>>미사용</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">댓글 기능 사용</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_comment">
                            <option value="Y" <?=element('brd_use_comment',$view)=='Y'?'selected':''?>>사용</option>
                            <option value="N" <?=element('brd_use_comment',$view)=='N'?'selected':''?>>미사용</option>
                        </select>
                    </div>
                    <label class="control-label">위지윅 에디터 사용</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_wysiwyg">
                            <option value="Y" <?=element('brd_use_wysiwyg',$view)=='Y'?'selected':''?>>사용</option>
                            <option value="N" <?=element('brd_use_wysiwyg',$view)=='N'?'selected':''?>>미사용</option>
                        </select>
                    </div>
                    <label class="control-label">파일 첨부 사용</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_attach">
                            <option value="Y" <?=element('brd_use_attach',$view)=='Y'?'selected':''?>>사용</option>
                            <option value="N" <?=element('brd_use_attach',$view)=='N'?'selected':''?>>미사용</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">관리자 승인 글만 표시</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_assign">
                            <option value="Y" <?=element('brd_use_assign',$view,'N')=='Y'?'selected':''?>>사용</option>
                            <option value="N" <?=element('brd_use_assign',$view,'N')=='N'?'selected':''?>>미사용</option>
                        </select>
                        <p class="help-block"><i class="fal fa-exclamation-circle"></i> 승인된 글만 표시하는 기능을 사용하여, 사이트에 불리한 내용을 숨기거나 하는경우 소비자보호센터의 제재를 받을 수 있습니다.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h4 class="panel-title"><?=$this->site->config('point_name')?> 설정</h4>
            </div>
            <div class="panel-body">
                <?php if($this->site->config('point_use') == 'Y') :?>
                    <p class="alert alert-info"><i class="far fa-info-circle"></i> <?=$this->site->config('point_name')?> 지급은 +값, <?=$this->site->config('point_name')?> 차감은 -값을 입력합니다.<br><i class="far fa-info-circle"></i> 포인트 차감이 설정된 경우 강제로 회원전용 기능으로 전환합니다.</p>
                <?php else :?>
                    <p class="alert alert-info"><i class="far fa-info-circle"></i> <?=$this->site->config('point_name')?> 기능을 사용 하는경우만 아래 옵션이 활성화 됩니다.</p>
                <?php endif;?>
                <div class="form-group">
                    <label class="control-label">글쓰기</label>
                    <div class="controls">
                        <input type="number" class="form-control" name="brd_point_write" value="<?=element('brd_point_write', $view, 0)?>" <?=$this->site->config('point_use') != 'Y'?'readonly':''?>>
                    </div>
                    <label class="control-label">답글쓰기</label>
                    <div class="controls">
                        <input type="number" class="form-control" name="brd_point_reply" value="<?=element('brd_point_reply', $view, 0)?>" <?=$this->site->config('point_use') != 'Y'?'readonly':''?>>
                    </div>
                    <label class="control-label">댓글쓰기</label>
                    <div class="controls">
                        <input type="number" class="form-control" name="brd_point_comment" value="<?=element('brd_point_comment', $view, 0)?>" <?=$this->site->config('point_use') != 'Y'?'readonly':''?>>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">내용 보기</label>
                    <div class="controls">
                        <input type="number" class="form-control" name="brd_point_read" value="<?=element('brd_point_read', $view, 0)?>" <?=$this->site->config('point_use') != 'Y'?'readonly':''?>>
                    </div>
                    <label class="control-label">첨부파일 다운</label>
                    <div class="controls">
                        <input type="number" class="form-control" name="brd_point_download" value="<?=element('brd_point_download', $view, 0)?>" <?=$this->site->config('point_use') != 'Y'?'readonly':''?>>
                    </div>
                    <label class="control-label"></label>
                    <div class="controls"></div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h4 class="panel-title">검색엔진 최적화 설정</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label">통합 RSS</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_total_rss">
                            <option value="Y" <?=element('brd_use_total_rss',$view)=='Y'?'selected':''?>>포함</option>
                            <option value="N" <?=element('brd_use_total_rss',$view)=='N'?'selected':''?>>포함하지 않기</option>
                        </select>
                    </div>
                    <label class="control-label">게시판 RSS</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_rss">
                            <option value="Y" <?=element('brd_use_rss',$view)=='Y'?'selected':''?>>사용</option>
                            <option value="N" <?=element('brd_use_rss',$view)=='N'?'selected':''?>>미사용</option>
                        </select>
                    </div>
                    <label class="control-label">사이트맵 노출</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_sitemap">
                            <option value="Y" <?=element('brd_use_sitemap',$view)=='Y'?'selected':''?>>노출</option>
                            <option value="N" <?=element('brd_use_sitemap',$view)=='N'?'selected':''?>>감추기</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">네이버 신디케이션 연동</label>
                    <div class="controls">
                        <select class="form-control form-control-inline" name="brd_use_naver_syndi">
                            <option value="Y" <?=element('brd_use_naver_syndi', $view)=='Y'?'selected':''?>>사용</option>
                            <option value="N" <?=element('brd_use_naver_syndi', $view)=='N'?'selected':''?>>사용</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>


        <div class="H10"></div>
        <div class="text-center">
            <button class="btn btn-primary btn-lg">저장하기</button>
        </div>
    </div>
</div>
<?=form_close()?>
<div class="H30"></div>

<script>
$(document).ready(function(){
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