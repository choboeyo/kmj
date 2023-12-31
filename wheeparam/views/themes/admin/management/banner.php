<div class="row">
    <div class="col-sm-4">

        <div class="ax-button-group">
            <div class="left">
                <h4>배너 분류</h4>
            </div>
            <div class="right">
                <button type="button" class="btn btn-default" data-button="btn-banner-group-form" data-idx=""><i class="fal fa-plus-circle"></i> 배너그룹 추가</button>
            </div>
        </div>

        <div class="grid">
            <table>
                <thead>
                <tr>
                    <th class="W20"></th>
                    <th>분류이름</th>
                    <th class="W80">관리</th>
                </tr>
                </thead>
                <tbody data-toggle="sortable" data-key="bng_idx" data-sort="bng_sort" data-table="banner_group">
                <?php foreach($banner_group_list['list'] as $row) :?>
                    <tr class="<?=isset($bng_key)&&$bng_key==$row['bng_key']?'active':''?>">
                        <td class="text-center">
                            <span class="move-grip"></span>
                            <input type="hidden" name="bng_idx[]" value="<?=$row['bng_idx']?>">
                        </td>
                        <td><i class="fal <?=isset($bng_key)&&$bng_key==$row['bng_key']?'fa-folder-open':'fa-folder'?>"></i>&nbsp;<a href="<?=base_url('admin/management/banner/'.$row['bng_key'])?>"><?=$row['bng_name']?></a></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-default btn-xs MR5" data-button="btn-banner-group-form" data-idx="<?=$row['bng_idx']?>"><i class="fal fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger btn-xs" data-button="btn-banner-group-delete" data-idx="<?=$row['bng_idx']?>"><i class="fal fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach;?>
                <?php if(count($banner_group_list['list']) == 0) :?>
                    <tr>
                        <td colspan="4" class="empty">등록된 배너 그룹이 없습니다.</td>
                    </tr>
                <?php endif;?>
                </tbody>
            </table>
        </div>

    </div>
    <div class="col-sm-8">
        <?php if($bng_key) :?>

            <div class="ax-button-group">
                <div class="left">
                    <h4>[<?=$banner_group['bng_name']?>] 배너 관리</h4>
                </div>
                <div class="right">
                    <button type="button" class="btn btn-default" data-button="btn-banner-form" data-bng-key="<?=$bng_key?>" data-idx=""><i class="fal fa-plus-circle"></i> 배너 추가</button>
                </div>
            </div>

            <p class="alert alert-info">한장만 표시되는 배너의 경우 가장 첫번째 순서의 배너를 가져옵니다.</p>

            <div class="grid">
                <table>
                    <thead>
                    <tr>
                        <th class="W20"></th>
                        <th class="W250">썸네일</th>
                        <th>이름</th>
                        <th class="W150">관리</th>
                    </tr>
                    </thead>
                    <tbody data-toggle="sortable" data-key="ban_idx" data-sort="ban_sort" data-table="banner">
                    <?php foreach($banner_list['list'] as $row) :?>
                        <tr>
                            <td class="text-center">
                                <span class="move-grip"></span>
                                <input type="hidden" name="ban_idx[]" value="<?=$row['ban_idx']?>">
                            </td>
                            <td><?=thumb_img($row['ban_filepath'],'img-thumbnail','style="max-width:250px"')?></td>
                            <td><?=$row['ban_name']?></td>
                            <td class="text-center W150">
                                <button type="button" class="btn btn-default btn-xs MR5" data-button="btn-banner-form" data-bng-key="<?=$bng_key?>" data-idx="<?=$row['ban_idx']?>"><i class="fal fa-pencil"></i></button>
                                <button type="button" class="btn btn-danger btn-xs" data-button="btn-banner-delete" data-idx="<?=$row['ban_idx']?>"><i class="fal fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    <?php if(count($banner_list['list']) == 0) :?>
                        <tr>
                            <td colspan="4" class="empty">등록된 배너가 없습니다.</td>
                        </tr>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
        <?php endif;?>
    </div>
</div>

<script>
    $(function(){

        $('[data-button="btn-banner-group-form"]').click(function(){
            var bng_idx = $(this).data('idx');

            APP.MODAL.open({
                iframe : {
                    url : base_url + '/admin/management/banner_group_form',
                    param : {
                        bng_idx : bng_idx
                    }
                },
                width: 500,
                height: 400,
                header : {
                    title : '배너 그룹 정보'
                }
            });
        });

        $('[data-button="btn-banner-group-delete"]').click(function(){
            var idx=  $(this).data('idx');
            if( typeof idx == 'undefined' || ! idx) {
                alert('잘못된 접근입니다.');
                return;
            }

            if(! confirm('선택하신 배너 그룹을 삭제하시겠습니까?')) {
                return;
            }

            location.href = base_url + "/admin/management/banner_group_delete/" + idx;
        });

        $('[data-button="btn-banner-form"]').click(function(){
            var ban_idx = $(this).data('idx');
            var bng_key = $(this).data('bng-key');

            if(typeof bng_key =='undefined' || ! bng_key) {
                alert('잘못된 접근입니다.');
                return;
            }

            APP.MODAL.open({
                iframe : {
                    url : base_url + '/admin/management/banner_form',
                    param : {
                        bng_key : bng_key,
                        ban_idx : ban_idx
                    }
                },
                width: 500,
                height: 650,
                header : {
                    title : '배너정보'
                }
            });
        });

        $('[data-button="btn-banner-delete"]').click(function(){
            var idx=  $(this).data('idx');
            if( typeof idx == 'undefined' || ! idx) {
                alert('잘못된 접근입니다.');
                return;
            }

            if(! confirm('선택하신 배너를 삭제하시겠습니까?')) {
                return;
            }

            location.href = base_url + "/admin/management/banner_delete/" + idx;
        });
    });
</script>