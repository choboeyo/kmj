<?=form_open(NULL, array('class'=>'form-flex'))?>
<input type="hidden" name="mnu_idx" value="<?=$mnu_idx?>">
<input type="hidden" name="mnu_parent" value="<?=$mnu_parent?>">
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>메뉴 이름</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="mnu_name" value="<?=element('mnu_name', $view)?>" required maxlength="30">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>링크 구분</div>
            <div data-ax-td-wrap>
                <select class="form-control" id="menu-helper">
                    <option value="">직접입력</option>
                    <option value="#" <?=element('mnu_link',$view)=='#'?'selected':''?>>링크 없음</option>
                    <option value="board">게시판</option>
                    <option value="pages">일반페이지</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-wrap id="menu-helper-input">
                <select class="form-control">
                    <option value="">직접입력</option>
                    <option value="#" <?=element('mnu_link',$view)=='#'?'selected':''?>>링크 없음</option>
                    <option value="board">게시판</option>
                    <option value="pages">일반페이지</option>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>표시 설정</div>
            <div data-ax-td-wrap>
                <label class="w-check">
                    <input type="checkbox" name="mnu_desktop" value="Y" <?=element('mnu_desktop',$view,'Y')=='Y'?'checked':''?>>
                    <span>PC버젼 표시</span>
                </label>
                <label class="w-check">
                    <input type="checkbox" name="mnu_mobile" value="Y" <?=element('mnu_mobile',$view,'Y')=='Y'?'checked':''?>>
                    <span>모바일버젼 표시</span>
                </label>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>기타 옵션</div>
            <div data-ax-td-wrap>
                <label class="w-check">
                    <input type="checkbox" name="mnu_newtab" value="Y" <?=element('mnu_newtab',$view,'Y')=='N'?'checked':''?>>
                    <span>새탭으로 열기</span>
                </label>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>Active KEY</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="mnu_active_key" value="<?=element('mnu_active_key',$view)?>">
                <p class="form-control-static">개발자가 설정하는 값입니다.</p>
            </div>
        </div>
    </div>
</div>

<div class="text-center MT15">
    <button class="btn btn-primary"><i class="fal fa-save"></i> 저장하기</button>
</div>
<?=form_close()?>

<script>
    var mnu_link = "<?=element('mnu_link', $view)?>";
    var board_link = <?=json_encode($board_list)?>;
    var pages_link = [
        { url : '/customer/faq', name : 'FAQ'},
        { url : '/customer/qna', name : 'Q&A'},
        { url : '/contact', name : 'Contact'},
        { url : '/agreement/site', name:'이용약관'},
        { url : '/agreement/privacy', name:'개인정보취급방침'}
    ];
    $(function(){
        $("#menu-helper").change(function(){
            var $this = $(this);
            $("#menu-helper-input").empty();
            if( $this.val() == '' )
            {
                var input = $("<input>").addClass('form-control').attr('name', "mnu_link").attr('required','required').val( mnu_link );
                $("#menu-helper-input").append( input);
            }
            else if ( $this.val() == '#' )
            {
                var input = $("<input>").addClass('form-control').attr('name', "mnu_link").val( '#' ).attr('readonly','readonly');
                $("#menu-helper-input").append( input);
            }
            else if ( $this.val() == 'board' || $this.val() == 'pages' ) {
                var data_list = [];
                if( $this.val() == 'board' ) {
                    data_list = board_link;
                }
                else if ($this.val() == 'pages') {
                    data_list = pages_link;
                }
                var select = $("<select>").addClass('form-control').attr('name', 'mnu_link');
                for(var i=0; i<data_list.length; i++ )
                {
                    var option = $("<option>").attr('value', data_list[i].url ).text( data_list[i].name );
                    select.append(option);
                }
                $("#menu-helper-input").append( select );
            }
        }).change();
    });
</script>