<div class="page-header">
    <h1 class="page-title">배너 관리</h1>
</div>

<div class="row">
    <div class="col-sm-4">

        <div class="ax-button-group">
            <div class="left">
                <h4>배너 분류</h4>
            </div>
            <div class="right">
                <button type="button" class="btn btn-default" data-button="btn-banner-group-form" data-idx=""><i class="far fa-plus-circle"></i> 배너그룹 추가</button>
            </div>
        </div>

        <div data-ax5grid>
            <table>
                <thead>
                <tr>
                    <th>분류이름</th>
                    <th class="W175">관리</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($banner_group_list['list'] as $row) :?>
                    <tr>
                        <td class="<?=isset($bng_key)&&$bng_key==$row['bng_key']?'active':''?>"><?=$row['bng_name']?></td>
                        <td class="text-center W200">
                            <a href="<?=base_url('admin/management/banner/'.$row['bng_key'])?>" class="btn btn-default btn-xs"><i class="far <?=isset($bng_key)&&$bng_key==$row['bng_key']?'fa-folder-open':'fa-folder'?>"></i> 관리</a>
                            <button type="button" class="btn btn-default btn-xs" data-button="btn-banner-group-form" data-idx="<?=$row['bng_idx']?>"><i class="far fa-pencil"></i> 수정</button>
                            <button type="button" class="btn btn-danger btn-xs" data-button="btn-banner-group-delete" data-idx="<?=$row['bng_idx']?>"><i class="far fa-trash"></i> 삭제</button>
                        </td>
                    </tr>
                <?php endforeach;?>
                <?php if(count($banner_group_list['list']) == 0) :?>
                    <tr>
                        <td colspan="3" class="empty">등록된 배너 그룹이 없습니다.</td>
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
                    <button type="button" class="btn btn-default" data-button="btn-banner-form" data-bng-key="<?=$bng_key?>" data-idx=""><i class="far fa-plus-circle"></i> 배너 추가</button>
                </div>
            </div>

            <p class="alert alert-info">한장만 표시되는 배너의 경우 가장 첫번째 순서의 배너를 가져옵니다.</p>

            <div data-ax5grid>
                <table>
                    <thead>
                    <tr>
                        <th class="W50">순서</th>
                        <th class="W250">썸네일</th>
                        <th>이름</th>
                        <th class="W150">관리</th>
                    </tr>
                    </thead>
                    <tbody id="banner-list">
                    <?php foreach($banner_list['list'] as $row) :?>
                        <tr>
                            <td class="text-center">
                                <i class="far  fa-bars"></i>
                                <input type="hidden" name="ban_idx[]" value="<?=$row['ban_idx']?>">
                            </td>
                            <td><?=thumb_img($row['ban_filepath'],'img-thumbnail','style="max-width:250px"')?></td>
                            <td><?=$row['ban_name']?></td>
                            <td class="text-center W150">
                                <button type="button" class="btn btn-default btn-sm" data-button="btn-banner-form" data-bng-key="<?=$bng_key?>" data-idx="<?=$row['ban_idx']?>"><i class="far fa-pencil"></i> 수정</button>
                                <button type="button" class="btn btn-danger btn-sm" data-button="btn-banner-delete" data-idx="<?=$row['ban_idx']?>"><i class="far fa-trash"></i> 삭제</button>
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

            <script>
                $(function(){

                    $("#banner-list").sortable({
                        handle : 'i.far.fa-bars',
                        update: function(){
                            var sort_array = [];
                            $("#banner-list input[name='ban_idx[]']").each(function(){
                                sort_array.push( $(this).val() );
                            });
                            $.post('/admin/management/banner_sort',{sort_idx:sort_array});
                        }
                    });
                });
            </script>
        <?php endif;?>
    </div>
</div>

<script>
    $(function(){

        $('[data-button="btn-banner-group-form"]').click(function(){
            var bng_idx = $(this).data('idx');

            APP.MODAL.open({
                iframe : {
                    url : '/admin/management/banner_group_form',
                    param : {
                        bng_idx : bng_idx
                    }
                },
                width: 500,
                height: 650,
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

            location.href="/admin/management/banner_group_delete/" + idx;
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
                    url : '/admin/management/banner_form',
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

            location.href="/admin/management/banner_delete/" + idx;
        });
    });
</script>