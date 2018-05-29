<div data-container="file-input">
    <div class="form-inline" style="margin-bottom:10px;">
        <input type="file" name="userfile[]" class="form-control">
    </div>
</div>
<button type="button" class="btn btn-default" data-toggle="btn-add-file-input">파일 추가</button>

<script>
$(function(){
    $('[data-toggle="btn-add-file-input"]').on('click', function(){
        var div = $("<div>").addClass('form-inline').css('margin-bottom','10px');
        var input = $("<input>").attr('type','file').attr('name','userfile[]').addClass('form-control');
        div.append(input);
        $('[data-container="file-input"]').append(div);
    });
});
</script>