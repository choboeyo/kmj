<div class="page-header">
    <h1 class="page-title">쇼핑몰 문자 발송 설정</h1>
</div>

<?=form_open("admin/setting/update", array('class'=>"form-flex"))?>
<input type="hidden" name="reurl" value="<?=base_url('admin/setting/shop-sms')?>">
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>문자 구분</div>
            <div data-ax-td-wrap>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_sms_type]" value="SMS" <?=$this->site->config('shop_sms_type')==='SMS'?'checked':''?>>
                    <span>SMS</span>
                </label>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_sms_type]" value="KAKAO" <?=$this->site->config('shop_sms_type')==='KAKAO'?'checked':''?>>
                    <span>카카오알림톡</span>
                </label>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_sms_type]" value="NONE" <?=$this->site->config('shop_sms_type')==='NONE'?'checked':''?>>
                    <span>사용안함</span>
                </label>
            </div>
        </div>
    </div>
    <div class="caption">NCloud 설정</div>
    <div data-ax-tr data-visible="SMS">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>SMS ServiceID</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_nc_s_sid]" value="<?=$this->site->config('shop_nc_s_sid')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr data-visible="SMS">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>SMS 발신번호</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_nc_s_callback]" value="<?=$this->site->config('shop_nc_s_callback')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr data-visible="KAKAO">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>카카오톡채널</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_nc_k_plusFriend]" value="<?=$this->site->config('shop_nc_k_plusFriend')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr data-visible="KAKAO">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>BizMessage ServiceID</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_nc_k_sid]" value="<?=$this->site->config('shop_nc_k_sid')?>">
            </div>
        </div>
    </div>

    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>NCloud AccessKey</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_nc_k_accessKey]" value="<?=$this->site->config('shop_nc_k_accessKey')?>">
            </div>
        </div>
    </div>

    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>NCloud Access Secret</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_nc_k_accessSecret]" value="<?=$this->site->config('shop_nc_k_accessSecret')?>">
            </div>
        </div>
    </div>
</div>

<p class="help-block">
    무통장입금을 제외한 나머지 결제방식은 [주문완료후], [발송완료안내] 만 사용합니다.<br>
    무통장입금시 [입금계좌안내],[입금확인안내],[발송완료안내] 가 사용됩니다.
</p>

<div data-ax-tbl class="MT10" style="width:600px;">
    <div class="caption">주문완료후 발송</div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>주문완료시</div>
            <div data-ax-td-wrap>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_sms_order_complete]" value="Y" <?=$this->site->config('shop_sms_order_complete')==='Y'?'checked':''?>>
                    <span>사용</span>
                </label>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_sms_order_complete]" value="N" <?=$this->site->config('shop_sms_order_complete')==='N'?'checked':''?>>
                    <span>미사용</span>
                </label>
            </div>
        </div>
    </div>
    <div data-ax-tr data-visible="KAKAO">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>템플릿코드</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_sms_order_complete_c]" value="<?=$this->site->config('shop_sms_order_complete_c')?>">
                <p class="help-block">[NCLOUD Console] &gt; [Simple &amp; Easy Notification Service] &gt; [Biz Message] &gt; [AlimTalk Template]에 등록되어 있어야 합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>내용</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[shop_sms_order_complete_cc]" rows="10" style="height:auto;"><?=$this->site->config('shop_sms_order_complete_cc')?></textarea>
                <p class="help-block">사용가능 변수<br>#{주문번호}<br>#{주문자}<br>#{주문금액}<br>#{주문상품}</p>
            </div>
        </div>
    </div>
</div>

<div data-ax-tbl class="MT10" style="width:600px;">
    <div class="caption">입금계좌안내 발송</div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>입금계좌안내</div>
            <div data-ax-td-wrap>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_sms_bank_info]" value="Y" <?=$this->site->config('shop_sms_bank_info')==='Y'?'checked':''?>>
                    <span>사용</span>
                </label>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_sms_bank_info]" value="N" <?=$this->site->config('shop_sms_bank_info')==='N'?'checked':''?>>
                    <span>미사용</span>
                </label>
            </div>
        </div>
    </div>
    <div data-ax-tr data-visible="KAKAO">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>템플릿코드</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_sms_bank_info_c]" value="<?=$this->site->config('shop_sms_bank_info_c')?>">
                <p class="help-block">[NCLOUD Console] &gt; [Simple &amp; Easy Notification Service] &gt; [Biz Message] &gt; [AlimTalk Template]에 등록되어 있어야 합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>내용</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[shop_sms_bank_info_cc]" rows="10" style="height:auto;"><?=$this->site->config('shop_sms_bank_info_cc')?></textarea>
                <p class="help-block">사용가능 변수<br>#{주문번호}<br>#{주문자}<br>#{주문금액}<br>#{주문상품}<br>#{계좌번호}</p>
            </div>
        </div>
    </div>
</div>

<div data-ax-tbl class="MT10" style="width:600px;">
    <div class="caption">입금확인안내 발송</div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>입금확인안내</div>
            <div data-ax-td-wrap>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_sms_pay_complete]" value="Y" <?=$this->site->config('shop_sms_pay_complete')==='Y'?'checked':''?>>
                    <span>사용</span>
                </label>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_sms_pay_complete]" value="N" <?=$this->site->config('shop_sms_pay_complete')==='N'?'checked':''?>>
                    <span>미사용</span>
                </label>
            </div>
        </div>
    </div>
    <div data-ax-tr data-visible="KAKAO">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>템플릿코드</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_sms_pay_complete_c]" value="<?=$this->site->config('shop_sms_pay_complete_c')?>">
                <p class="help-block">[NCLOUD Console] &gt; [Simple &amp; Easy Notification Service] &gt; [Biz Message] &gt; [AlimTalk Template]에 등록되어 있어야 합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>내용</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[shop_sms_pay_complete_cc]" rows="10" style="height:auto;"><?=$this->site->config('shop_sms_pay_complete_cc')?></textarea>
                <p class="help-block">사용가능 변수<br>#{주문번호}<br>#{주문자}<br>#{주문금액}</p>
            </div>
        </div>
    </div>
</div>

<div data-ax-tbl class="MT10" style="width:600px;">
    <div class="caption">발송완료안내 발송</div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>발송완료안내</div>
            <div data-ax-td-wrap>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_sms_delivery]" value="Y" <?=$this->site->config('shop_sms_delivery')=='Y'?'checked':''?>>
                    <span>사용</span>
                </label>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_sms_delivery]" value="N" <?=$this->site->config('shop_sms_delivery')=='N'?'checked':''?>>
                    <span>미사용</span>
                </label>
            </div>
        </div>
    </div>
    <div data-ax-tr data-visible="KAKAO">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>배송조회버튼</div>
            <div data-ax-td-wrap>
                <div>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_sms_delivery_button]" value="Y" <?=$this->site->config('shop_sms_delivery_button')==='Y'?'checked':''?>>
                        <span>사용</span>
                    </label>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_sms_delivery_button]" value="N" <?=$this->site->config('shop_sms_delivery_button')==='N'?'checked':''?>>
                        <span>미사용</span>
                    </label>
                </div>
                <p class="help-block">템플릿 등록시 버튼에 꼭 배송조회 버튼을 추가하셔야합니다. (버튼 추가후 타입에 배송조회 선택, 이름은 '배송조회'로 입력)</p>
            </div>
        </div>
    </div>
    <div data-ax-tr data-visible="KAKAO">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>템플릿코드</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_sms_delivery_c]" value="<?=$this->site->config('shop_sms_delivery_c')?>">
                <p class="help-block">[NCLOUD Console] &gt; [Simple &amp; Easy Notification Service] &gt; [Biz Message] &gt; [AlimTalk Template]에 등록되어 있어야 합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>내용</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[shop_sms_delivery_cc]" rows="10" style="height:auto;"><?=$this->site->config('shop_sms_delivery_cc')?></textarea>
                <p class="help-block">사용가능 변수<br>#{주문번호}<br>#{주문자}<br>#{주문상품}<br>#{택배사}<br>#{운송장번호}</p>
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
        $('[name="setting[shop_sms_type]"]').change(function() {
            var selected =$('[name="setting[shop_sms_type]"]:checked').val();

            $('[data-visible]').hide();
            $('[data-visible="'+selected+'"]').show();
        }).change();
    })
</script>

