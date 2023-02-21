<?php
$status[''] = '전체보기';
$status['주문'] = '주문완료';
$status['입금'] = '입금완료';
$status['준비'] = '상품준비중';
$status['배송'] = '배송중';
$status['완료'] = '배송완료';

$settle[''] = '전체보기';
$settle['card'] = '신용카드';
$settle['bank'] = '무통장입금';
$settle['trans'] = '계좌이체';
$settle['vbank'] = '가상계좌';
$settle['phone'] = '휴대폰';
$settle['naverpay'] = '네이버페이';
$settle['samsung'] = '삼성페이';
$settle['kakaopay'] = '카카오페이';
?>
<form>
    <div data-ax-tbl>

        <div data-ax-tr>
            <div data-ax-td class="W350">
                <div data-ax-td-label>주문일자</div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="startdate" data-toggle="datepicker" data-chained-datepicker="[name='enddate']" value="<?=$startdate?>">
                </div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="enddate" data-toggle="datepicker" value="<?=$enddate?>">
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>주문상태</div>
                <div data-ax-td-wrap>
                    <?php foreach($status as $key=>$label):?>
                    <label class="toggle-box" style="margin-right:-1px;">
                        <input type="radio" name="od_status" value="<?=$key?>" <?=$key==$od_status?'checked':''?>>
                        <span><?=$label?></span>
                    </label>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>결제수단</div>
                <div data-ax-td-wrap>
                    <?php foreach($settle as $key=>$label):?>
                        <label class="toggle-box" style="margin-right:-1px;">
                            <input type="radio" name="od_settle_case" value="<?=$key?>" <?=$key==$od_settle_case?'checked':''?>>
                            <span><?=$label?></span>
                        </label>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>기타</div>
                <div data-ax-td-wrap>
                    <label class="toggle-box">
                        <input type="checkbox" name="is_misu" value="Y" <?=$is_misu=='Y'?'checked':''?>>
                        <span>미수금만 보기</span>
                    </label>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="W600">
                <div data-ax-td-label>주문자</div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="query" value="<?=$query?>" placeholder="주문번호,주문자,휴대폰 검색">
                </div>
            </div>
        </div>
    </div>
    <div class="text-center MT10">
        <button type="submit" class="btn btn-primary">검색적용</button>
    </div>
</form>

<div class="H10"></div>

<?=form_open('admin/orders/multi_save')?>
<input type="hidden" name="reurl" value="<?=current_full_url()?>">
<div class="ax-button-group">
    <div class="left">
        <div style="display:flex;align-items: center">
            <span>선택한 주문에</span>
            <select class="form-control W150 ML5 MR5" id="send-type">
                <option value="oc">주문완료 안내</option>
                <option value="ip">입금계좌 안내</option>
                <option value="ic">입금확인 안내</option>
                <option value="sc">발송완료 안내</option>
            </select>
            <button type="button" class="btn btn-default" data-button="send-sms">발송하기</button>
        </div>
    </div>
    <div class="right">
        <button type="submit" class="btn btn-primary" onclick="return confirm('입력하신 배송정보를 일괄 저장하시겠습니까?')">배송정보 저장</button>
    </div>
</div>

<div class="grid">
    <table style="table-layout: fixed">
        <colgroup>
            <col class="W40" />
            <col class="W150" />
            <col />
            <col class="W100" />
            <col class="W120" />
            <col class="W100" />
            <col class="W80" />
            <col class="W80" />
            <col class="W80" />
            <col class="W80" />
            <col class="W80" />
            <col class="W120" />
            <col class="W120" />
            <col class="W150" />
            <col class="W40" />
            <col class="W40" />
            <col class="W40" />
            <col class="W40" />
        </colgroup>
        <thead>
        <tr>
            <th colspan="3">주문정보</th>
            <th colspan="2">주문자 정보</th>
            <th colspan="6">결제 정보</th>
            <th colspan="3">배송 정보</th>
            <th colspan="4">안내 발송</th>
        </tr>
        <tr>
            <th class="W40">
                <label class="w-check">
                    <input type="checkbox" data-checkbox="list" data-checkbox-all value="">
                    <span class="ML5"></span>
                </label>
            </th>
            <th class="W165">주문번호<br>주문일시</th>
            <th>주문상품</th>
            <th class="W100">주문자</th>
            <th class="W120">연락처</th>
            <th class="W100">결제수단</th>
            <th class="W80">상품금액<br>배송비</th>
            <th class="W80">주문금액</th>
            <th class="W80 text-danger">취소금액<br>환불금액</th>
            <th class="W80 text-primary">결제금액</th>
            <th class="W80 text-danger">미수금액</th>
            <th class="W120">주문상태</th>
            <th class="W120">배송회사</th>
            <th class="W150">운송장번호</th>
            <th class="W40">주문완료</th>
            <th class="W40">입금안내</th>
            <th class="W40">입금확인</th>
            <th class="W40">발송완료</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $row):?>
        <tr>
            <td class="text-center">
                <label class="w-check">
                    <input type="checkbox" data-checkbox="list" name="chk_idx[]" value="<?=$row['od_id']?>">
                    <span></span>
                </label>
            </td>
            <td class="text-center"><a href="<?=base_url('admin/orders/view/'.$row['od_id'])?>"><?=$row['od_num']?></a><br><?=date('Y.m.d H:i:s', strtotime($row['od_receipt_time']))?></td>
            <td><?=$row['od_title']?>
                <button type="button" class="btn btn-default btn-sm ML5" data-button="toggle-detail" data-id="<?=$row['od_id']?>"><i class="fas fa-chevron-down"></i></button>
            </td>
            <td class="text-center"><?=$row['od_name']?></td>
            <td class="text-center"><?=$row['od_hp']?></td>
            <td class="text-center"><?=$settle[$row['od_settle_case']]?></td>
            <td class="text-right"><?=number_format($row['od_cart_price'])?><br><?=number_format($row['od_send_cost'])?></td>
            <td class="text-right"><?=number_format($row['od_receipt_price'])?></td>
            <td class="text-right text-danger"><?=number_format($row['od_cancel_price'])?><br><?=number_format($row['od_refund_price'])?></td>
            <td class="text-right text-primary"><?=number_format($row['od_receipt_price']-$row['od_refund_price']-$row['od_cancel_price']-$row['od_misu'])?></td>
            <td class="text-right text-danger"><?=number_format($row['od_misu'])?></td>
            <td class="text-center">
                <select name="od_status[<?=$row['od_id']?>]" class="form-control">
                    <?php if($row['od_status']=='주문'):?>
                    <option value="주문" <?=$row['od_status']=='주문'?'selected':''?>>주문완료</option>
                    <?php endif;?>
                    <?php if($row['od_settle_case'] === 'bank' || $row['od_status'] =='입금'):?>
                    <option value="입금" <?=$row['od_status']=='입금'?'selected':''?>>입금완료</option>
                    <?php endif;?>
                    <?php if($row['od_status'] != '배송완료') :?>
                    <option value="준비" <?=$row['od_status']=='준비'?'selected':''?>>상품준비중</option>
                    <?php endif;?>
                    <option value="배송" <?=$row['od_status']=='배송'?'selected':''?>>배송중</option>
                    <option value="완료" <?=$row['od_status']=='완료'?'selected':''?>>배송완료</option>
                </select>
            </td>
            <td>
                <select class="form-control" name="od_delivery_company[<?=$row['od_id']?>]">
                    <option value="">없음</option>
                    <option value="자체배송" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='자체배송'?'selected':''?>>자체배송</option>
                    <option value="경동택배" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='경동택배'?'selected':''?>>경동택배</option>
                    <option value="대신택배" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='대신택배'?'selected':''?>>대신택배</option>
                    <option value="동부택배" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='동부택배'?'selected':''?>>동부택배</option>
                    <option value="로젠택배" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='로젠택배'?'selected':''?>>로젠택배</option>
                    <option value="우체국" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='우체국'?'selected':''?>>우체국</option>
                    <option value="이노지스택배" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='이노지스택배'?'selected':''?>>이노지스택배</option>
                    <option value="한진택배" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='한진택배'?'selected':''?>>한진택배</option>
                    <option value="롯데택배" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='롯데택배'?'selected':''?>>롯데택배</option>
                    <option value="CJ대한통운" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='CJ대한통운'?'selected':''?>>CJ대한통운</option>
                    <option value="CVSnet편의점택배" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='CVSnet편의점택배'?'selected':''?>>CVSnet편의점택배</option>
                    <option value="KG옐로우캡택배" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='KG옐로우캡택배'?'selected':''?>>KG옐로우캡택배</option>
                    <option value="KGB택배" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='KGB택배'?'selected':''?>>KGB택배</option>
                    <option value="KG로지스" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='KG로지스'?'selected':''?>>KG로지스</option>
                    <option value="건영택배" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='건영택배'?'selected':''?>>건영택배</option>
                    <option value="호남택배" <?=element('od_delivery_company',$row, $this->site->config('shop_delivery_company'))==='호남택배'?'selected':''?>>호남택배</option>
                </select>
            </td>
            <td>
                <input class="form-control" name="od_delivery_num[<?=$row['od_id']?>]" value="<?=$row['od_delivery_num']?>">
            </td>
            <td class="text-center text-primary"><?=$row['od_oc_send']=='Y'?'●':''?></td>
            <td class="text-center text-primary"><?=$row['od_ip_send']=='Y'?'●':''?></td>
            <td class="text-center text-primary"><?=$row['od_ic_send']=='Y'?'●':''?></td>
            <td class="text-center text-primary"><?=$row['od_sc_send']=='Y'?'●':''?></td>
        </tr>
        <tr class="detail-tr" id="order-<?=$row['od_id']?>">
            <td colspan="2"></td>
            <td colspan="16" data-container="detail"></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?=form_close()?>

<div class="H10"></div>

<div class="ax-button-group">

    <div class="right"><?=$pagination?></div>
</div>

<p class="help-block text-danger">※ 주문상태를 변경하여도 알림문자(SMS/카카오알림톡)는 자동으로 발송되지 않습니다.</p>
<p class="help-block text-danger">※ 배송회사/운송장번호를 입력하더라도 주문상태가 자동으로 [배송중] 으로변경되지 않습니다. 주문상태를 직접 [배송중]으로 변경하시고 저장해야 합니다.</p>
<p class="help-block text-danger">※ [주문완료] 상태는 무통장입금일 경우만 선택가능하며, 다른 결제수단은 기본적으로 결제 완료후 [입금완료] 상태가 됩니다.</p>
<p class="help-block text-danger">※ [주문취소] 상태로 변경하여도 자동으로 환불이나 결제취소처리가 되지 않습니다. PG사 결제 취소는 주문번호를 클릭하여 상세보기에서 처리하셔야 합니다.</p>

<style>
    .toggle-box {margin-bottom:0;}
    .pagination {text-align: right}
    .detail-tr {display:none;}
    .detail-tr.opened {display:table-row}
</style>

<script>
    $(function() {
        $('[data-button="toggle-detail"]').click(function(e) {
            $(this).find('.fas').toggleClass('fa-chevron-down fa-chevron-up');
            var od_id = $(this).data('id');
            $('#order-'+od_id).toggleClass('opened');

            if( $('#order-'+od_id).hasClass('opened'))
            {
                $.ajax({
                    url: base_url + '/admin/orders/order-items/' + od_id,
                    type: 'GET',
                    success:function(res) {
                        $('[data-container="detail"]', $('#order-'+od_id)).html(res)
                    }
                })
            }
        });
        $('[data-button="send-sms"]').click(function(e) {
            var idxs = [];
            $('[name="chk_idx[]"]').each(function(){
                if($(this).prop('checked')) {
                    idxs.push( $(this).val() )
                }
            })
            
            if(idxs.length === 0) {
                alert('안내문자를 발송할 주문서를 먼저 선택해주세요');
                return;
            }

            var send_type = $('#send-type option:selected').val();
            var send_text = $('#send-type option:selected').text();

            if(! confirm('선택한 ' + idxs.length + '개의 주문에 ['+send_text+'] 문자를 발송하시겠습니까?')) return;

            $.ajax({
                url: base_url + '/admin/ajax/orders/send_sms',
                type: 'POST',
                data: {
                    idxs: idxs,
                    type: send_type
                },
                success:function() {
                    alert('발송이 완료되었습니다.');
                   location.reload();
                }
            })
        })
    })
</script>