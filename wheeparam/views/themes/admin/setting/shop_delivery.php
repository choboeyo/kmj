<div class="page-header">
    <h1 class="page-title">쇼핑몰 배송 설정</h1>
</div>

<?=form_open_multipart("admin/setting/update", array('class'=>"form-flex"))?>
<input type="hidden" name="reurl" value="<?=base_url('admin/setting/shop-delivery')?>">
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>배송업체</div>
            <div data-ax-td-wrap>
                <select name="setting[shop_delivery_company]" class="form-control">
                    <option value="">없음</option>
                    <option value="자체배송" <?=$this->site->config('shop_delivery_company')==='자체배송'?'selected':''?>>자체배송</option>
                    <option value="경동택배" <?=$this->site->config('shop_delivery_company')==='경동택배'?'selected':''?>>경동택배</option>
                    <option value="대신택배" <?=$this->site->config('shop_delivery_company')==='대신택배'?'selected':''?>>대신택배</option>
                    <option value="동부택배" <?=$this->site->config('shop_delivery_company')==='동부택배'?'selected':''?>>동부택배</option>
                    <option value="로젠택배" <?=$this->site->config('shop_delivery_company')==='로젠택배'?'selected':''?>>로젠택배</option>
                    <option value="우체국" <?=$this->site->config('shop_delivery_company')==='우체국'?'selected':''?>>우체국</option>
                    <option value="이노지스택배" <?=$this->site->config('shop_delivery_company')==='이노지스택배'?'selected':''?>>이노지스택배</option>
                    <option value="한진택배" <?=$this->site->config('shop_delivery_company')==='한진택배'?'selected':''?>>한진택배</option>
                    <option value="롯데택배" <?=$this->site->config('shop_delivery_company')==='롯데택배'?'selected':''?>>롯데택배</option>
                    <option value="CJ대한통운" <?=$this->site->config('shop_delivery_company')==='CJ대한통운'?'selected':''?>>CJ대한통운</option>
                    <option value="CVSnet편의점택배" <?=$this->site->config('shop_delivery_company')==='CVSnet편의점택배'?'selected':''?>>CVSnet편의점택배</option>
                    <option value="KG옐로우캡택배" <?=$this->site->config('shop_delivery_company')==='KG옐로우캡택배'?'selected':''?>>KG옐로우캡택배</option>
                    <option value="KGB택배" <?=$this->site->config('shop_delivery_company')==='KGB택배'?'selected':''?>>KGB택배</option>
                    <option value="KG로지스" <?=$this->site->config('shop_delivery_company')==='KG로지스'?'selected':''?>>KG로지스</option>
                    <option value="건영택배" <?=$this->site->config('shop_delivery_company')==='건영택배'?'selected':''?>>건영택배</option>
                    <option value="호남택배" <?=$this->site->config('shop_delivery_company')==='호남택배'?'selected':''?>>호남택배</option>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>배송비 유형</div>
            <div data-ax-td-wrap>
                <select class="form-control W200" name="setting[shop_delivery_type]">
                    <option value="무료" <?=$this->site->config('shop_delivery_type')==='무료'?'selected':''?>>무료배송</option>
                    <option value="차등" <?=$this->site->config('shop_delivery_type')==='차등'?'selected':''?>>금액별 차등적용</option>
                </select>
                <p class="help-block">금액별차등으로 설정한 경우, 주문총액이 배송비상한가 미만일 경우 배송비를 받습니다.<br>
                    무료배송으로 설정한 경우, 배송비상한가 및 배송비를 무시하며 착불의 경우도 무료배송으로 설정합니다.<br>
                    상품별로 배송비 설정을 한 경우 상품별 배송비 설정이 우선 적용됩니다.<br>
                    예를 들어 무료배송으로 설정했을 때 특정 상품에 배송비가 설정되어 있으면 주문시 배송비가 부과됩니다</p>
            </div>
        </div>
    </div>
    <div data-ax-tr class="delivery-cost-setting">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>배송비 설정</div>
            <div data-ax-td-wrap>
                <?php
                $_temp = json_decode($this->site->config('shop_delivery_cost'), TRUE);
                ?>

                <div class="grid W400">
                    <table>
                        <thead>
                        <tr>
                            <th colspan="2">구매금액</th>
                            <th colspan="2">배송비</th>
                        </tr>
                        </thead>
                        <tbody data-container="cost-set">
                        <?php foreach($_temp as $row) :?>
                        <tr>
                            <td><input class="form-control text-right" name="shop_delivery_cost[price][]" data-number-format data-number-only value="<?=number_format($row['price'])?>"></td>
                            <td class="W100">원 까지</td>
                            <td><input class="form-control text-right" name="shop_delivery_cost[sc_cost][]" data-number-format data-number-only value="<?=number_format($row['sc_cost'])?>"></td>
                            <td class="W60">원</td>
                        </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-default MT10" data-button="add-cost-set"><i class="fas fa-plus"></i> 범위 추가</button>
                <p class="help-block">사용하지 않는 행은 저장시 자동으로 삭제합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>배송정보</div>
            <div data-ax-td-wrap>
                <?=get_editor('setting[shop_delivery_info]', $this->site->config('shop_delivery_info'))?>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>교환/반품</div>
            <div data-ax-td-wrap>
                <?=get_editor('setting[shop_refund_info]', $this->site->config('shop_refund_info'))?>
            </div>
        </div>
    </div>
</div>
<div class="text-center MT10">
    <button type="submit" class="btn btn-primary">저장하기</button>
</div>
<?=form_close()?>


<script>
    $(function() {
        $('[name="setting[shop_delivery_type]"]').change(function() {
            var value = $(this).find('option:selected').val();
            var isCostSet = value === '차등';

            if(isCostSet) {
                $('.delivery-cost-setting').show();
            } else {
                $('.delivery-cost-setting').hide();
            }
        }).change();

        $('[data-button="add-cost-set"]').click(function(e) {
            var $cont = $('[data-container="cost-set"]');
            var $tr = $('<tr>'),
                $td1 = $('<td>'),
                $td2 = $('<td>').addClass('W100').text('원 까지'),
                $td3 = $('<td>'),
                $td4 = $('<td>').addClass('W60').text('원')

            var $input1 = $('<input>')
                .addClass('form-control text-right')
                .attr({
                    "data-number-format":true,
                    "data-number-only":true,
                    "name":"shop_delivery_cost[price][]"
                })

            var $input2 = $('<input>')
                .addClass('form-control text-right')
                .attr({
                    "data-number-format":true,
                    "data-number-only":true,
                    "name":"shop_delivery_cost[sc_cost][]"
                })

            $td1.append($input1)
            $td3.append($input2)

            $tr.append($td1, $td2, $td3, $td4)

            $cont.append($tr);

        });
    })
</script>
