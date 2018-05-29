<div class="page-header">
    <h1 class="page-title">기타 도구</h1>
</div>

<div class="panel panel-dark">
    <div class="panel-heading">
        <h4 class="panel-title">기타 도구</h4>
    </div>
    <div class="panel-body form-flex">
        <div class="form-group">
            <label class="control-label">통계자료 최적화</label>
            <div class="controls">
                <p class="form-control-static">
                    <button class="btn btn-primary" data-button="optimize-statics" data-loading-text="최적화 중입니다..." ><i class="far fa-play-circle"></i> 최적화 실행하기</button>
                </p>

            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    $('[data-button="optimize-statics"]').click(function(){

        var $btn = $(this).button('loading');

        $.ajax({
            url : '/ajax/tools/optimize_statics',
            type : 'get',
            success:function(res) {
                toastr.success('통계자료 최적화를 완료하였습니다.');
            },
            complete : function(){
                $btn.button('reset');
            }
        });
    });
});
</script>