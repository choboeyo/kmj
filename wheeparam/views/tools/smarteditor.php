<textarea name="<?=$name?>" id="<?=$id?>" style="width:100%;height:<?=$height?>"><?=$contents?></textarea>
<script>
var oEditors = [];
nhn.husky.EZCreator.createInIFrame({
    oAppRef: oEditors,
    elPlaceHolder: '<?=$id?>',
    sSkinURI: '<?=$editor_url?>' +"/SmartEditor2Skin.html",
    htParams : {
        bUseToolbar : true,
        bUseVerticalResizer : true,
        bUseModeChanger : true,
        bSkipXssFilter : true,
        //aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
        fOnBeforeUnload : function(){
            //alert("완료!");
        }
    }, //boolean
    fOnAppLoad : function(){
        //예제 코드
        //oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
    },
    fCreator: "createSEditor2"
});
$("#<?=$id?>").parents('form').submit(function(e){
    var <?=$id?>_editor_data = oEditors.getById['<?=$id?>'].getIR();
    oEditors.getById['<?=$id?>'].exec('UPDATE_CONTENTS_FIELD', []);
    if(jQuery.inArray(document.getElementById('<?=$id?>').value.toLowerCase().replace(/^\s*|\s*$/g, ''), ['&nbsp;','<p>&nbsp;</p>','<p><br></p>','<div><br></div>','<p></p>','<br>','']) != -1){
        $("#<?=$id?>").val('');
    }
});
</script>