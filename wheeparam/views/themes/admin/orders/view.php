<div id="order-view">

    <div class="ax-button-group">
        <div class="left">
            <h4>주문 상품 목록</h4>
        </div>
    </div>
    <div class="grid">
        <table>
            <thead>
            <tr>
                <th>상품명</th>
                <th>옵션명</th>
                <th class="W120">품목상태</th>
                <th class="W120">판매가격</th>
                <th class="W120">판매수량</th>
                <th class="W120">소계</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item,index) in cartList" :key="`cart-${index}`">
                <td>{{item.prd_name}}</td>
                <td>{{item.cart_option}}</td>
                <td class="text-center">
                    <select class="form-control" v-model="item.cart_status">
                        <option value="주문">주문완료</option>
                        <option value="입금">입금완료</option>
                        <option value="준비">배송준비중</option>
                        <option value="배송">배송중</option>
                        <option value="완료">배송완료</option>
                        <option value="취소">취소</option>
                        <option value="반품">반품</option>
                        <option value="품절">품절</option>
                    </select>
                </td>
                <td class="text-right">{{item.cart_price}}</td>
                <td>
                    <input type="number" class="form-control" v-model.number="item.cart_qty">
                </td>
                <td class="text-right">{{(item.cart_price*item.cart_qty).numberFormat()}}</td>
            </tr>
            </tbody>
        </table>
        <p class="help-block">※ 주문상품별로 판매수량과 품목상태를 변경할 수 있습니다. 단, 주문서의 총 상품금액은 변동되지 않으므로 수동으로 변경하셔야 합니다.</p>
        <div class="text-center">
            <button type="button" class="btn btn-primary" @click="saveItems">품목별 상태저장</button>
        </div>
    </div>

    <div class="H15"></div>

    <div style="display:flex;margin:0 -1rem">
        <div style="width:50%; padding:0 1rem">

            <div class="ax-button-group">
                <h4>상품 주문정보</h4>
            </div>
            <div data-ax-tbl>
                <div data-ax-tr>
                    <div data-ax-td>
                        <div data-ax-td-label>주문번호</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" :value="order.od_id" readonly>
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td class="width-100">
                        <div data-ax-td-label>주문상태</div>
                        <div data-ax-td-wrap>
                            <select class="form-control W200" v-model="order.od_status">
                                <option value="주문">주문완료</option>
                                <option value="입금">입금완료</option>
                                <option value="준비">상품준비중</option>
                                <option value="배송">배송중</option>
                                <option value="배송완료">배송완료</option>
                            </select>
                            <p class="help-block">주문서의 주문상태를 변경시 주문상품들의 상태를 모두 주문서의 상태와 동일하게 변경합니다.<br>단, 주문상품이 [품절],[취소],[환불]인 경우는 제외합니다.</p>
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td class="width-100">
                        <div data-ax-td-label>주문상품</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" :value="order.od_title" readonly>
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td>
                        <div data-ax-td-label>주문자</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" v-model.trim="order.od_name" required>
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td>
                        <div data-ax-td-label>휴대폰</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" v-model.trim="order.od_hp" required>
                        </div>
                    </div>
                    <div data-ax-td>
                        <div data-ax-td-label>전화번호</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" v-model.trim="order.od_tel">
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td class="width-100">
                        <div data-ax-td-label>E-mail</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" v-model.trim="order.od_email">
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td>
                        <div data-ax-td-label>우편번호</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" v-model.trim="order.od_zonecode">
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td class="width-100">
                        <div data-ax-td-label>주소</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" v-model.trim="order.od_addr1">
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td class="width-100">
                        <div data-ax-td-label>상세주소</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" v-model.trim="order.od_addr2">
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td class="width-100">
                        <div data-ax-td-label>요청사항</div>
                        <div data-ax-td-wrap>
                            <textarea class="form-control" :value="order.od_memo" data-autosize="textarea" readonly></textarea>
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td class="width-100">
                        <div data-ax-td-label>관리자 메모</div>
                        <div data-ax-td-wrap>
                            <textarea class="form-control" v-model.trim="order.od_shop_memo" data-autosize="textarea"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="H10"></div>
            <div data-ax-tbl>
                <div class="caption">결제금액 정보</div>
                <div data-ax-tr>
                    <div data-ax-td>
                        <div data-ax-td-label>총 상품금액</div>
                        <div data-ax-td-wrap>
                            <input type="number" class="form-control" v-model.number="order.od_cart_price">
                        </div>
                    </div>
                    <div data-ax-td>
                        <div data-ax-td-label>배송비</div>
                        <div data-ax-td-wrap>
                            <input type="number" class="form-control" v-model.number="order.od_send_cost">
                        </div>
                    </div>

                    <div data-ax-td>
                        <div data-ax-td-label>주문금액</div>
                        <div data-ax-td-wrap>
                            <input type="number" class="form-control" :value="order.od_receipt_price" readonly>
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td>
                        <div data-ax-td-label>환불금액</div>
                        <div data-ax-td-wrap>
                            <input type="number" class="form-control" v-model.number="order.od_refund_price">
                        </div>
                    </div>
                    <div data-ax-td>
                        <div data-ax-td-label>취소금액</div>
                        <div data-ax-td-wrap>
                            <input type="number" class="form-control" v-model.number="order.od_cancel_price">
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td>
                        <div data-ax-td-label>미수금</div>
                        <div data-ax-td-wrap>
                            <input type="number" class="form-control" v-model.number="order.od_misu" readonly>
                        </div>
                    </div>
                    <div data-ax-td>
                        <div data-ax-td-label>결제금액</div>
                        <div data-ax-td-wrap>
                            <input type="number" class="form-control" v-model.number="order.od_paid_price">
                        </div>
                    </div>
                </div>
            </div>
            <div class="H10"></div>
            <div data-ax-tbl>
                <div class="caption">배송정보</div>
                <div data-ax-tr>
                    <div data-ax-td>
                        <div data-ax-td-label>택배사</div>
                        <div data-ax-td-wrap>
                            <select class="form-control" v-model="order.od_delivery_company">
                                <option value="">택배사 선택</option>
                                <option value="자체배송" >자체배송</option>
                                <option value="경동택배">경동택배</option>
                                <option value="대신택배">대신택배</option>
                                <option value="동부택배">동부택배</option>
                                <option value="로젠택배">로젠택배</option>
                                <option value="우체국">우체국</option>
                                <option value="이노지스택배">이노지스택배</option>
                                <option value="한진택배">한진택배</option>
                                <option value="롯데택배">롯데택배</option>
                                <option value="CJ대한통운">CJ대한통운</option>
                                <option value="CVSnet편의점택배">CVSnet편의점택배</option>
                                <option value="KG옐로우캡택배">KG옐로우캡택배</option>
                                <option value="KGB택배">KGB택배</option>
                                <option value="KG로지스">KG로지스</option>
                                <option value="건영택배">건영택배</option>
                                <option value="호남택배">호남택배</option>
                            </select>
                        </div>
                    </div>
                    <div data-ax-td>
                        <div data-ax-td-label>송장번호</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" v-model.trim="order.od_delivery_num">
                        </div>
                    </div>

                </div>
            </div>
            <div class="H10"></div>
            <div class="text-center">
                <button type="button" @click="submitOrder" class="btn btn-primary">주문정보 저장</button>
            </div>
        </div>

        <div style="width:50%; padding:0 1rem">
            <template v-if="order.imp_uid" >
                <div class="ax-button-group">
                    <div class="left">
                        <h4>PG사 결제 정보</h4>
                    </div>
                </div>
                <div data-ax-tbl>
                    <div data-ax-tr>
                        <div data-ax-td>
                            <div data-ax-td-label>결제금액</div>
                            <div data-ax-td-wrap>
                                <input class="form-control" readonly :value="(iamport.amount*1)-iamport.cancel">
                            </div>
                        </div>
                        <div data-ax-td>
                            <div data-ax-td-wrap>
                                <a class="btn btn-default" target="_blank" :href="iamport.receipt_url">영수증 조회</a>
                            </div>
                        </div>
                    </div>
                    <div data-ax-tr>
                        <div data-ax-td>
                            <div data-ax-td-label>취소금액</div>
                            <div data-ax-td-wrap>
                                <input class="form-control" v-model.trim="cancelAmount" @blur="onCancelAmountBlur">
                            </div>
                        </div>
                        <div data-ax-td>
                            <div data-ax-td-wrap>
                                <button type="button" class="btn btn-default" :disabled="cancelAmount<=0" @click="cancelPayment">PG사 취소 요청</button>
                            </div>
                        </div>
                    </div>
                    <div data-ax-tr>
                        <div data-ax-td class="width-100">
                            <div data-ax-td-label>취소사유</div>
                            <div data-ax-td-wrap>
                                <input class="form-control" v-model.trim="cancelReason">
                            </div>
                        </div>
                    </div>
                    <template v-if="iamport.cancel_history.length>0">
                        <div data-ax-tr>
                            <div data-ax-td>
                                <div data-ax-td-label>취소된금액</div>
                                <div data-ax-td-wrap>
                                    <input class="form-control" readonly :value="iamport.cancel">
                                </div>
                            </div>
                        </div>
                        <div data-ax-tr>
                            <div data-ax-td class="width-100">
                                <div data-ax-td-label>결제취소내역</div>
                                <div data-ax-td-wrap>
                                    <ul style="list-style:none;padding:0;margin:0;">
                                        <template v-for="(item,index) in iamport.cancel_history">
                                            <li :key="`cancel-${index}`">[{{item.amount}} 원] {{item.reason}} <a :href="item.receipt_url" class="btn btn-xs btn-default">영수증</a></li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-if="iamport.pay_method==='card'">
                        <div data-ax-tr>
                            <div data-ax-td>
                                <div data-ax-td-label>카드명</div>
                                <div data-ax-td-wrap>
                                    <input class="form-control" readonly :value="iamport.card_name">
                                </div>
                            </div>
                        </div>
                        <div data-ax-tr>
                            <div data-ax-td>
                                <div data-ax-td-label>카드번호</div>
                                <div data-ax-td-wrap>
                                    <input class="form-control" readonly :value="iamport.card_number">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <p class="help-block">실제 결제금액의 취소 처리는 이곳에서 처리하셔야 합니다.</p>
                <div class="H30"></div>
            </template>
            <div data-ax-tbl>
                <div class="caption">SMS/알림톡 발송정보</div>
                <div data-ax-tr>
                    <div data-ax-td>
                        <div data-ax-td-label>주문완료</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" :value="order.od_oc_send==='Y'?order.od_oc_datetime:''" readonly>
                        </div>
                    </div>
                    <div data-ax-td>
                        <div data-ax-td-wrap>
                            <button type="button" class="btn btn-default" @click="send_sms('oc')"><i class="fas fa-envelope"></i> 발송하기</button>
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td>
                        <div data-ax-td-label>입금계좌</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" :value="order.od_ip_send==='Y'?order.od_ip_datetime:''" readonly>
                        </div>
                    </div>
                    <div data-ax-td>
                        <div data-ax-td-wrap>
                            <button type="button" class="btn btn-default" @click="send_sms('ip')"><i class="fas fa-envelope"></i> 발송하기</button>
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td>
                        <div data-ax-td-label>입금확인</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" :value="order.od_ic_send==='Y'?order.od_ic_datetime:''" readonly>
                        </div>
                    </div>
                    <div data-ax-td>
                        <div data-ax-td-wrap>
                            <button type="button" class="btn btn-default" @click="send_sms('ic')"><i class="fas fa-envelope"></i> 발송하기</button>
                        </div>
                    </div>
                </div>
                <div data-ax-tr>
                    <div data-ax-td>
                        <div data-ax-td-label>발송완료</div>
                        <div data-ax-td-wrap>
                            <input class="form-control" :value="order.od_sc_send==='Y'?order.od_sc_datetime:''" readonly>
                        </div>
                    </div>
                    <div data-ax-td>
                        <div data-ax-td-wrap>
                            <button type="button" class="btn btn-default" @click="send_sms('sc')"><i class="fas fa-envelope"></i> 발송하기</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
new Vue({
    el: '#order-view',
    data () {
        return {
            isCalc: false,
            isLoaded: false,
            order: {
                od_id: '',
                imp_uid: '',
                od_status: '',
                od_settle_case:'',
                od_receipt_time:'',
                od_time:'',
                od_name: '',
                od_email: '',
                od_tel: '',
                od_hp:'',
                od_zonecode: '',
                od_addr1:'',
                od_addr2: '',
                od_title: '',
                od_memo: '',
                od_cart_count: 0,
                od_cart_price: 0,
                od_send_cost:0,
                od_receipt_price: 0,
                od_cancel_price:0,
                od_refund_price:0,
                od_paid_price:0,
                od_misu:0,
                od_shop_memo:'',
                od_test:'N',
                od_mobile:'N',
                od_pg:'',
                od_delivery_company:'',
                od_delivery_num:'',
                od_oc_send:'N',
                od_ip_send:'N',
                od_ic_send:'N',
                od_sc_send:'N',
                od_oc_datetime:'',
                od_ip_datetime:'',
                od_ic_datetime:'',
                od_sc_datetime:'',
            },
            cartList: [],
            iamport:{
                amount:0,
                cancel_history:[]
            },
            cancelAmount: 0,
            cancelReason:''
        }
    },
    mounted() {
      this.getInfo();
      this.getCartList();
    },
    watch: {
      'order.od_cart_price'() {
          this.calcSumPrice()
      },
      'order.od_send_cost'() {
          this.calcSumPrice()
      },
      'order.od_refund_price' () {
          this.calcMinusPrice()
      },
      'order.od_cancel_price' () {
          this.calcMinusPrice()
      },
      'order.od_paid_price' () {
          this.calcMinusPrice()
      }
    },
    methods: {
        calcSumPrice() {
            if(this.isCalc || !this.isLoaded) return;
            this.isCalc = true;
            this.order.od_receipt_price = (this.order.od_cart_price * 1) + (this.order.od_send_cost * 1)
            this.order.od_misu = (this.order.od_receipt_price * 1) - (this.order.od_refund_price * 1) - (this.order.od_cancel_price * 1) - (this.order.od_paid_price * 1)
            this.isCalc = false;
        },
        calcMinusPrice () {
            if(this.isCalc|| !this.isLoaded) return;
            this.isCalc = true;
            this.order.od_misu = (this.order.od_receipt_price * 1) - (this.order.od_refund_price * 1) - (this.order.od_cancel_price * 1) - (this.order.od_paid_price * 1)
            this.isCalc = false;
        },
        getInfo () {
            const vm = this;
            this.isLoaded = false;

            $.ajax({
                url: base_url+'/admin/ajax/orders/view/<?=$od_id?>',
                type: 'GET',
                success:function(res) {
                    vm.isCalc = true;
                    for(var key in res) {
                        if(typeof vm.order[key] !== 'undefined') {
                            vm.order[key] = res[key]
                        }
                    }

                    if(vm.order.imp_uid.length > 0 ){
                        vm.getImport();
                    }
                    vm.isCalc = false;
                    vm.$nextTick(function() {
                        vm.isLoaded = true;
                    })
                }
            })
        },
        getImport() {
            const vm = this;

            $.ajax({
                url: base_url+'/admin/ajax/orders/import/' + vm.order.imp_uid,
                type: 'GET',
                success:function(res) {
                    vm.iamport = res;
                    vm.iamport.cancel = vm.iamport.cancel_amount * 1
                    vm.cancelAmount = 0
                    vm.cancelReason = "";
                }
            })
        },
        getCartList() {
            var vm = this;
            $.ajax({
                url:base_url+'/admin/ajax/orders/items/<?=$od_id?>',
                type:'GET',
                success: function(res) {
                    vm.cartList = res;
                }
            })
        },
        onCancelAmountBlur () {
            if(this.cancelAmount * 1 > this.iamport.amount* 1 - this.iamport.cancel_amount*1) {
                this.cancelAmount = this.iamport.amount* 1 - this.iamport.cancel_amount*1
            }
        },
        cancelPayment() {
            if(this.cancelAmount*1 <= 0) {
                alert('취소금액을 정확하게 입력하세요');
                return;
            }

            if( this.cancelReason.trim().length === 0) {
                alert('취소사유를 입력하셔야 합니다.');
                return;
            }

            var vm = this;

            $.ajax({
                url: base_url +'/admin/ajax/orders/import/' + vm.order.imp_uid,
                type: 'DELETE',
                data: {
                    merchant_uid: vm.order.od_id,
                    amount: vm.cancelAmount,
                    reason: vm.cancelReason
                },
                success: function() {
                    vm.getImport();
                }
            })
        },
        send_sms(send_type) {
            var idxs = [<?=$od_id?>];
            if(! confirm('문자를 발송하시겠습니까?')) return;

            var vm = this;

            $.ajax({
                url: base_url + '/admin/ajax/orders/send_sms',
                type: 'POST',
                cache: false,
                async: false,
                data: {
                    idxs: idxs,
                    type: send_type
                },
                success:function() {
                    $.ajax({
                        url: base_url+'/admin/ajax/orders/view/<?=$od_id?>',
                        type: 'GET',
                        cache: false,
                        async: false,
                        success:function(res) {
                            vm.order['od_' + send_type + '_send'] = res['od_' + send_type + '_send'];
                            vm.order['od_' + send_type + '_datetime'] = res['od_' + send_type + '_datetime'];
                        }
                    })

                    alert('발송이 완료되었습니다.');

                }
            })
        },
        saveItems () {
            var vm= this;
            $.ajax({
                url:base_url+'/admin/ajax/orders/items/<?=$od_id?>',
                type: 'POST',
                data: {
                    cartList: vm.cartList
                },
                success: function() {
                    vm.getInfo();
                    vm.getCartList()
                    alert('저장되었습니다.');
                }
            })
        },
        submitOrder () {
            let formData ={
                od_name: this.order.od_name,
                od_hp: this.order.od_hp,
                od_tel: this.order.od_tel,
                od_email: this.order.od_email,
                od_zonecode: this.order.od_zonecode,
                od_addr1: this.order.od_addr1,
                od_addr2: this.order.od_addr2,
                od_shop_memo: this.order.od_shop_memo,
                od_cart_price: this.order.od_cart_price,
                od_send_cost: this.order.od_send_cost,
                od_refund_price: this.order.od_refund_price,
                od_cancel_price: this.order.od_cancel_price,
                od_misu: this.order.od_misu,
                od_receipt_price: this.order.od_receipt_price,
                od_delivery_company: this.order.od_delivery_company,
                od_delivery_num: this.order.od_delivery_num,
                od_status: this.order.od_status
            }
            var vm = this;
            $.ajax({
                url: base_url +'/admin/ajax/orders/index/<?=$od_id?>',
                type: 'POST',
                data: formData,
                success: function() {
                    alert('저장되었습니다.');
                    vm.getInfo();
                    vm.getCartList();
                }
            })
        }
    }
})
</script>