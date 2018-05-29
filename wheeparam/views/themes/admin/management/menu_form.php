<?=form_open(NULL, array('class'=>'form-flex'))?>
<input type="hidden" name="mnu_idx" value="<?=$mnu_idx?>">
<input type="hidden" name="mnu_parent" value="<?=$mnu_parent?>">
<div class="form-group">
    <label class="control-label control-label-sm">메뉴 이름</label>
    <div class="controls">
        <input class="form-control" name="mnu_name" value="<?=element('mnu_name', $view)?>" required maxlength="30">
    </div>
</div>
<div class="form-group">
    <label class="control-label control-label-sm">메뉴 링크 타입</label>
    <div class="controls">
        <select class="form-control" id="menu-helper">
            <option value="">직접입력</option>
            <option value="#" <?=element('mnu_link',$view)=='#'?'selected':''?>>링크 없음</option>
            <option value="board">게시판</option>
            <option value="pages">일반페이지</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label control-label-sm">메뉴 링크</label>
    <div class="controls" id="menu-helper-input">
        <select class="form-control">
            <option value="">직접입력</option>
            <option value="#" <?=element('mnu_link',$view)=='#'?'selected':''?>>링크 없음</option>
            <option value="board">게시판</option>
            <option value="pages">일반페이지</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label class="control-label control-label-sm">PC</label>
    <div class="controls">
        <label class="w-check">
            <input type="checkbox" name="mnu_desktop" value="Y" <?=element('mnu_desktop',$view,'Y')=='Y'?'checked':''?>>
            <span>PC버젼 표시</span>
        </label>
    </div>
</div>

<div class="form-group">
    <label class="control-label control-label-sm">모바일</label>
    <div class="controls">
        <label class="w-check">
            <input type="checkbox" name="mnu_mobile" value="Y" <?=element('mnu_mobile',$view,'Y')=='Y'?'checked':''?>>
            <span>모바일버젼 표시</span>
        </label>
    </div>
</div>

<div class="form-group">
    <label class="control-label control-label-sm">새창</label>
    <div class="controls">
        <label class="w-check">
            <input type="checkbox" name="mnu_newtab" value="Y" <?=element('mnu_newtab',$view,'Y')=='N'?'checked':''?>>
            <span>새창으로 열기</span>
        </label>
    </div>
</div>


<div class="form-group">
    <label class="control-label control-label-sm">Active 값</label>
    <div class="controls" id="menu-helper-input">
        <label class="w-check">
            <input class="form-control" name="mnu_active_key" value="<?=element('mnu_active_key',$view)?>">
            <p class="form-control-static">현재 메뉴 Active상태를 판별하기 위한 키값</p>
        </label>
    </div>
</div>

<div class="text-center MT10">
    <button class="btn btn-primary"><i class="far fa-save"></i> 저장하기</button>
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