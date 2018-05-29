<div class="page-header">
    <h1 class="page-title">메뉴 관리</h1>
</div>

<?=form_open("admin/management/menu_multi_update")?>
<div class="ax-button-group">
    <div class="left">
        <button type="button" class="btn btn-default" onclick="menu_form(0);"><i class="far fa-plus-circle"></i> 대메뉴 등록</button>
    </div>
    <div class="right">
        <button class="btn btn-primary"><i class="far fa-save"></i> 저장하기</button>
    </div>
</div>

<div data-ax5grid>
    <table>
        <thead>
        <tr>
            <th colspan="3">메뉴 이름</th>
            <th>메뉴 링크</th>
            <th>메뉴 순서</th>
            <th>새창보기</th>
            <th>PC보기</th>
            <th>모바일보기</th>
            <th>Active 값</th>
            <th>관리</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($menu_list as $row):?>
            <tr>
                <td colspan="3">
                    <input  type="hidden" name="mnu_idx[]" value="<?=$row['mnu_idx']?>" required>
                    <input class="form-control" name="mnu_name[]" value="<?=$row['mnu_name']?>" required>
                </td>
                <td>
                    <input class="form-control" name="mnu_link[]" value="<?=$row['mnu_link']?>" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="mnu_order[]" value="<?=$row['mnu_order']?>">
                </td>
                <td>
                    <select class="form-control" name="mnu_newtab[]">
                        <option value="N" <?=$row['mnu_newtab']=='N'?'selected':''?>>아니오</option>
                        <option value="Y" <?=$row['mnu_newtab']=='Y'?'selected':''?>>새창열기</option>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="mnu_desktop[]">
                        <option value="Y" <?=$row['mnu_desktop']=='Y'?'selected':''?>>표시</option>
                        <option value="N" <?=$row['mnu_desktop']=='N'?'selected':''?>>미표시</option>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="mnu_mobile[]">
                        <option value="Y" <?=$row['mnu_mobile']=='Y'?'selected':''?>>표시</option>
                        <option value="N" <?=$row['mnu_mobile']=='N'?'selected':''?>>미표시</option>
                    </select>
                </td>
                <td>
                    <input class="form-control" name="mnu_active_key[]" value="<?=$row['mnu_active_key']?>">
                </td>
                <td>
                    <button type="button" class="btn btn-default" onclick="menu_form('<?=$row['mnu_idx']?>');"><i class="far fa-plus-circle"></i> 하위메뉴 등록</button>
                    <a class="btn btn-danger" href="<?=base_url('admin/management/menu_delete/'.$row['mnu_idx'])?>" onclick="return confirm('메뉴를 삭제하시겠습니까?');">삭제</a>
                </td>
            </tr>
            <?php foreach($row['children'] as $row2) :?>
                <tr>
                    <td class="text-right">└</td>
                    <td colspan="2">
                        <input  type="hidden" name="mnu_idx[]" value="<?=$row2['mnu_idx']?>" required>
                        <input class="form-control" name="mnu_name[]" value="<?=$row2['mnu_name']?>" required>
                    </td>
                    <td>
                        <input class="form-control" name="mnu_link[]" value="<?=$row2['mnu_link']?>" required>
                    </td>
                    <td>
                        <input type="number" class="form-control" name="mnu_order[]" value="<?=$row2['mnu_order']?>">
                    </td>
                    <td>
                        <select class="form-control" name="mnu_newtab[]">
                            <option value="N" <?=$row2['mnu_newtab']=='N'?'selected':''?>>아니오</option>
                            <option value="Y" <?=$row2['mnu_newtab']=='Y'?'selected':''?>>새창열기</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" name="mnu_desktop[]">
                            <option value="Y" <?=$row2['mnu_desktop']=='Y'?'selected':''?>>표시</option>
                            <option value="N" <?=$row2['mnu_desktop']=='N'?'selected':''?>>미표시</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" name="mnu_mobile[]">
                            <option value="Y" <?=$row2['mnu_mobile']=='Y'?'selected':''?>>표시</option>
                            <option value="N" <?=$row2['mnu_mobile']=='N'?'selected':''?>>미표시</option>
                        </select>
                    </td>
                    <td>
                        <input class="form-control" name="mnu_active_key[]" value="<?=$row2['mnu_active_key']?>">
                    </td>
                    <td>
                        <button type="button" class="btn btn-default" onclick="menu_form('<?=$row2['mnu_idx']?>');"><i class="far fa-plus-circle"></i> 하위메뉴 등록</button>
                        <a class="btn btn-danger" href="<?=base_url('admin/management/menu_delete/'.$row2['mnu_idx'])?>" onclick="return confirm('메뉴를 삭제하시겠습니까?');">삭제</a>
                    </td>
                </tr>
                <?php foreach($row2['children'] as $row3) :?>
                    <tr>
                        <td></td>
                        <td class="text-right">└</td>
                        <td>
                            <input type="hidden" name="mnu_idx[]" value="<?=$row3['mnu_idx']?>" required>
                            <input class="form-control" name="mnu_name[]" value="<?=$row3['mnu_name']?>" required>
                        </td>
                        <td>
                            <input class="form-control" name="mnu_link[]" value="<?=$row3['mnu_link']?>" required>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="mnu_order[]" value="<?=$row3['mnu_order']?>">
                        </td>
                        <td>
                            <select class="form-control" name="mnu_newtab[]">
                                <option value="N" <?=$row3['mnu_newtab']=='N'?'selected':''?>>아니오</option>
                                <option value="Y" <?=$row3['mnu_newtab']=='Y'?'selected':''?>>새창열기</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="mnu_desktop[]">
                                <option value="Y" <?=$row3['mnu_desktop']=='Y'?'selected':''?>>표시</option>
                                <option value="N" <?=$row3['mnu_desktop']=='N'?'selected':''?>>미표시</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="mnu_mobile[]">
                                <option value="Y" <?=$row3['mnu_mobile']=='Y'?'selected':''?>>표시</option>
                                <option value="N" <?=$row3['mnu_mobile']=='N'?'selected':''?>>미표시</option>
                            </select>
                        </td>
                        <td>
                            <input class="form-control" name="mnu_active_key[]" value="<?=$row3['mnu_active_key']?>">
                        </td>
                        <td>
                            <a class="btn btn-danger" href="<?=base_url('admin/management/menu_delete/'.$row3['mnu_idx'])?>" onclick="return confirm('메뉴를 삭제하시겠습니까?');">삭제</a>
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php endforeach;?>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?=form_close()?>
<script>
    function menu_form(mnu_parent, mnu_idx)
    {
        mnu_parent = typeof mnu_parent != 'undefied' && mnu_parent ? mnu_parent : 0;
        mnu_idx = typeof mnu_idx != 'undefined' && mnu_idx ? mnu_idx : null;
        APP.MODAL.close = function(){
            location.reload();
        };
        APP.MODAL.open({
            iframe : {
                url : '/admin/management/menu_form',
                param : {
                    mnu_parent : mnu_parent
                }
            },
            header : {
                title : '메뉴 정보 입력'
            },
            width : 450,
            height : 600
        });
    }
</script>