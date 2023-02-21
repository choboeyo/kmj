<div class="page-header">
    <h1 class="page-title">쇼핑몰 설정</h1>
</div>

<?=form_open_multipart("admin/setting/update", array('class'=>"form-flex"))?>
<input type="hidden" name="reurl" value="<?=base_url('admin/setting/shop')?>">

<div data-ax-tbl>
    <div class="caption">스킨 설정</div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>쇼핑몰 스킨</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="setting[skin_shop]">
                    <?php foreach($skin_list as $skin):?>
                        <option value="<?=$skin?>" <?=$skin==$this->site->config('skin_shop')?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>모바일</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="setting[skin_shop_m]">
                    <?php foreach($skin_list as $skin):?>
                        <option value="<?=$skin?>" <?=$skin==$this->site->config('skin_shop_m')?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>상품 목록</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="setting[skin_shop_list]">
                    <?php foreach($skin_l_list as $skin):?>
                    <option value="<?=$skin?>" <?=$skin==$this->site->config('skin_shop_list')?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-label>모바일</div>
            <div data-ax-td-wrap>
                <select class="form-control" name="setting[skin_shop_list_m]">
                    <?php foreach($skin_l_list as $skin):?>
                        <option value="<?=$skin?>" <?=$skin==$this->site->config('skin_shop_list_m')?'selected':''?>><?=$skin?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
</div>

<div data-ax-tbl class="MT15">
    <div class="caption">포트원 결제 설정</div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>가맹점 식별코드</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_portone_imp_code]" value="<?=$this->site->config('shop_portone_imp_code')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>REST API Key</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_portone_api_key]" value="<?=$this->site->config('shop_portone_api_key')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>REST API Key</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_portone_api_secret]" value="<?=$this->site->config('shop_portone_api_secret')?>">
            </div>
        </div>
    </div>
</div>

<div data-ax-tbl class="MT15">
    <div class="caption">결제 설정</div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>결제 대행사</div>
            <div data-ax-td-wrap>
                <div>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_pg_service]" value="kcp" <?=$this->site->config('shop_pg_service')==='kcp'?'checked':''?>>
                        <span>NHN KCP</span>
                    </label>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_pg_service]" value="inicis" <?=$this->site->config('shop_pg_service')==='inicis'?'checked':''?>>
                        <span>KG이니시스</span>
                    </label>
                </div>
                <p class="help-block">쇼핑몰에서 사용할 결제대행사를 선택합니다.
                </p>
            </div>
        </div>
    </div>

    <div data-ax-tr data-pg-visible="inicis">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>KG이니시스<br>상점아이디</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_inicis_mid]" value="<?=$this->site->config('shop_inicis_mid')?>">
                <p class="help-block">KG이니시스로 부터 발급 받으신 상점아이디(MID) 10자리를 입력해주세요.</p>
            </div>
        </div>
    </div>

    <div data-ax-tr data-pg-visible="kcp">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>KCP SITE CODE</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[shop_kcp_site_code]" value="<?=$this->site->config('shop_kcp_site_code')?>" maxlength="5">
                <p class="help-block">NHN KCP 에서 받은 영대문자, 숫자 혼용 총 5자리 SITE CODE 를 입력하세요.</p>
            </div>
        </div>
    </div>

    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>결제 모드</div>
            <div data-ax-td-wrap>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_pay_test]" value="Y" <?=$this->site->config('shop_pay_test')==='Y'?'checked':''?>>
                    <span>테스트 결제</span>
                </label>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_pay_test]" value="N" <?=$this->site->config('shop_pay_test')==='N'?'checked':''?>>
                    <span>실결제</span>
                </label>
                <p class="help-block">PG사의 결제 테스트를 하실 경우에 체크하세요. 결제단위 최소 1,000원</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>무통장 입금</div>
            <div data-ax-td-wrap>
                <div>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_bank_use]" value="Y" <?=$this->site->config('shop_bank_use')==='Y'?'checked':''?>>
                        <span>사용</span>
                    </label>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_bank_use]" value="N" <?=$this->site->config('shop_bank_use')==='N'?'checked':''?>>
                        <span>미사용</span>
                    </label>
                </div>
                <p class="help-block">주문시 무통장으로 입금을 가능하게 할것인지를 설정합니다.<br>사용할 경우 은행계좌번호를 반드시 입력하여 주십시오.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>무통장 입금 계좌번호</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[shop_bank_account]" rows="5"><?=$this->site->config('shop_bank_account')?></textarea>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>계좌이체사용</div>
            <div data-ax-td-wrap>
                <div>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_iche_use]" value="Y" <?=$this->site->config('shop_iche_use')==='Y'?'checked':''?>>
                        <span>사용</span>
                    </label>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_iche_use]" value="N" <?=$this->site->config('shop_iche_use')==='N'?'checked':''?>>
                        <span>미사용</span>
                    </label>
                </div>
                <p class="help-block">주문시 실시간 계좌이체를 가능하게 할것인지를 설정합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>가상계좌사용</div>
            <div data-ax-td-wrap>
                <div>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_vbank_use]" value="Y" <?=$this->site->config('shop_vbank_use')==='Y'?'checked':''?>>
                        <span>사용</span>
                    </label>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_vbank_use]" value="N" <?=$this->site->config('shop_vbank_use')==='N'?'checked':''?>>
                        <span>미사용</span>
                    </label>
                </div>
                <p class="help-block">주문별로 유일하게 생성되는 일회용 계좌번호입니다. 주문자가 가상계좌에 입금시 상점에 실시간으로 통보가 되므로 업무처리가 빨라집니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr data-pg-visible="kcp">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>NHN KCP<br>가상계좌<br>입금통보URL</div>
            <div data-ax-td-wrap>
                NHN KCP 가상계좌 사용시 다음 주소를 NHN KCP 관리자 > 상점정보관리 > 정보변경 > 공통URL 정보 > 공통URL 변경후에 넣으셔야 상점에 자동으로 입금 통보됩니다.
                <input class="form-control MT10" value="https://service.iamport.kr/kcp_payments/notice_vbank" readonly>
            </div>
        </div>
    </div>

    <div data-ax-tr data-pg-visible="inicis">
        <div data-ax-td class="width-100">
            <div data-ax-td-label>KG이니시스<br>가상계좌<br>입금통보URL</div>
            <div data-ax-td-wrap>
                KG이니시스 가상계좌 사용시 다음 주소를 KG이니시스 관리자 > 거래내역 > 가상계좌 > 입금통보방식선택 > URL 수신 설정에 넣으셔야 상점에 자동으로 입금 통보됩니다.
                <input class="form-control MT10" value="https://service.iamport.kr/inicis_payments/notice_vbank" readonly>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>휴대폰결제</div>
            <div data-ax-td-wrap>
                <div>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_hp_pay_use]" value="Y" <?=$this->site->config('shop_hp_pay_use')==='Y'?'checked':''?>>
                        <span>사용</span>
                    </label>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_hp_pay_use]" value="N" <?=$this->site->config('shop_hp_pay_use')==='N'?'checked':''?>>
                        <span>미사용</span>
                    </label>
                </div>
                <p class="help-block">주문시 휴대폰 결제를 가능하게 할것인지를 설정합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>카드결제사용</div>
            <div data-ax-td-wrap>
                <div>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_card_pay_use]" value="Y" <?=$this->site->config('shop_card_pay_use')==='Y'?'checked':''?>>
                        <span>사용</span>
                    </label>
                    <label class="toggle-box">
                        <input type="radio" name="setting[shop_card_pay_use]" value="N" <?=$this->site->config('shop_card_pay_use')==='N'?'checked':''?>>
                        <span>미사용</span>
                    </label>
                </div>
                <p class="help-block">주문시 신용카드 결제를 가능하게 할것인지를 설정합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>카카오페이<br>사용</div>
            <div data-ax-td-wrap>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_kakaopay_use]" value="Y" <?=$this->site->config('shop_kakaopay_use')=='Y'?'checked':''?>>
                    <span>사용</span>
                </label>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_kakaopay_use]" value="N" <?=$this->site->config('shop_kakaopay_use')=='N'?'checked':''?>>
                    <span>미사용</span>
                </label>
                <p class="help-block">체크시 카카오페이를 사용합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>네이버페이<br>사용</div>
            <div data-ax-td-wrap>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_use_global_naverpay]" value="Y" <?=$this->site->config('shop_kakaopay_use')=='Y'?'checked':''?>>
                    <span>사용</span>
                </label>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_use_global_naverpay]" value="N" <?=$this->site->config('shop_kakaopay_use')=='N'?'checked':''?>>
                    <span>미사용</span>
                </label>
                <p class="help-block">체크시 네이버페이를 사용합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>삼성페이<br>사용</div>
            <div data-ax-td-wrap>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_use_samsungpay]" value="Y" <?=$this->site->config('shop_use_samsungpay')=='Y'?'checked':''?>>
                    <span>사용</span>
                </label>
                <label class="toggle-box">
                    <input type="radio" name="setting[shop_use_samsungpay]" value="N" <?=$this->site->config('shop_use_samsungpay')=='N'?'checked':''?>>
                    <span>미사용</span>
                </label>
                <p class="help-block">체크시 삼성페이를 사용합니다.</p>
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
        $('[name="setting[shop_pg_service]"]').change(function() {
            var selected =$('[name="setting[shop_pg_service]"]:checked').val();

            $('[data-pg-visible]').hide();
            $('[data-pg-visible="'+selected+'"]').show();
        }).change();
    })
</script>
