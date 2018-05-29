<div class="ax-button-group">
    <div class="left">
        <h2>사이트맵 설정</h2>
    </div>
</div>

<p class="alert alert-info">
    <i class="far fal fas fa-info-circle"></i> 추가로 sitemap.xml 에서 인덱싱하고싶은 URL을 관리하는 페이지입니다.<br>
    <i class="far fal fas fa-info-circle"></i> 메인페이지와 게시판은 자동으로 로드하므로 추가할 필요가 없습니다.
</p>

<?=form_open("/admin/management/sitemap_update")?>
<div class="ax-button-group">
    <div class="left">
        <button type="button" class="btn btn-default" data-button="sitemap-form">추가하기</button>
    </div>
    <div class="right">
        <button class="btn btn-primary">저장하기</button>
    </div>
</div>

<div data-ax5grid>
    <table>
        <thead>
        <tr>
            <th>URL</th>
            <th>중요도</th>
            <th>갱신주기</th>
            <th>관리</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $row) :?>
        <tr>
            <td>
                <input type="hidden" name="sit_idx[]" value="<?=$row['sit_idx']?>">
                <input class="form-control" name="sit_loc[]" value="<?=$row['sit_loc']?>" required>
            </td>
            <td><input type="number" min="0" max="1" step="0.1" class="form-control" name="sit_priority[]" value="<?=$row['sit_priority']?>" required></td>
            <td>
                <select class="form-control" name="sit_changefreq[]">
                    <option value="daily" <?=$row['sit_changefreq']=='daily'?'selected':''?>>daily</option>
                    <option value="weekly" <?=$row['sit_changefreq']=='weekly'?'selected':''?>>weekly</option>
                    <option value="monthly" <?=$row['sit_changefreq']=='monthly'?'selected':''?>>monthly</option>
                </select>
            </td>
            <td>
                <a href="<?=base_url('admin/management/sitemap_delete/'.$row['sit_idx'])?>" onclick="return confirm('삭제하시겠습니까?');" class="btn btn-danger">삭제</a>
            </td>
        </tr>
        <?php endforeach;?>
        <?php if(count($list) <= 0 ):?>
        <tr>
            <td class="empty" colspan="4">등록된 자료가 없습니다.</td>
        </tr>
        <?php endif;?>
        </tbody>
    </table>
</div>
<?=form_close()?>
<script>
    $(function(){
        $('[data-button="sitemap-form"]').click(function(){
            APP.MODAL.callback = function(){
                location.reload();
            };
            APP.MODAL.open({
                iframe : {
                    url : '/admin/management/sitemap_form'
                },
                header  : {
                    title : '사이트맵 등록하기'
                },
                width:600,
                height:300

            });
        });
    });
</script>