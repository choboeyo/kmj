<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**************************************************************
 * SHOP REST API
 *
 * @property Products_model $products_model
 * @property Shop_model $shop_model
 * @property CI_Session $session
 * @property Import_model $import_model
 * @property Ncloud $ncloud
 *************************************************************/
class Shop extends REST_Controller
{
    /**
     * 장바구니에서 상품 옵션수정을 눌렀을때 해당옵션의 데이타를 가져옵니다.
     * @return void
     */
    function cart_option_get()
    {
        $this->load->model('products_model');
        $this->load->model('shop_model');

        $is_direct = $this->get('is_direct', TRUE);
        $prd_idx = $this->get('prd_idx', TRUE);

        if(empty($prd_idx)) {
            $this->response(["message"=>"장바구니에서 상품이 올바르게 선택되지 않았습니다."] , 400);
        }

        if(! $product = $this->products_model->getItem($prd_idx))
        {
            $this->response(["message"=>"상품정보를 가져올 수 없습니다. 상품이 이미 삭제되었거나, 품절된 상품입니다."] , 400);
        }

        // 장바구니에서 자료 가져오기
        $cart_id = $this->session->userdata('ss_cart_id');
        $cart = $this->db
            ->where('od_id', $cart_id)
            ->where('prd_idx', $prd_idx)
            ->where('cart_direct', $is_direct)
            ->order_by('opt_type DESC, cart_id ASC')
            ->get('shop_cart')
            ->result_array();

        if(! $cart) {
            $this->response(["message"=>"장바구니에서 상품을 불러오는데 실패하였습니다."], 400);
        }

        // 판매가격 가져오기
        $row2 = $this->db
            ->select('cart_price, prd_name, cart_send_cost')
            ->from('shop_cart')
            ->where('od_id', $cart_id)
            ->where('prd_idx', $prd_idx)
            ->where('cart_direct', $is_direct)
            ->order_by('cart_id ASC')
            ->limit(1)
            ->get()
            ->row_array();

        $row2['cart_price'] = $row2['cart_price'] * 1;
        $row2['cart_send_cost'] = $row2['cart_send_cost'] * 1;

        foreach($cart as &$row)
        {
            // 숫자로 변환
            $row['cart_coupon'] = $row['cart_coupon'] * 1;
            $row['cart_price'] = $row['cart_price'] * 1;
            $row['cart_qty'] = $row['cart_qty'] * 1;
            $row['cart_sc_price'] = $row['cart_sc_price'] * 1;
            $row['cart_send_cost'] = $row['cart_send_cost'] * 1;
            $row['cart_sc_minimum'] = $row['cart_sc_minimum'] * 1;
            $row['opt_price'] = $row['opt_price'] * 1;


            $row['opt_stock_qty'] = 0;
            if(empty($row['opt_code']))
            {
                $row['opt_stock_qty'] = $this->products_model->getStockQty($product);
            }
            else {
                $option = $this->db
                    ->where('prd_idx', $prd_idx)
                    ->where('opt_code', $row['opt_code'])
                    ->get('products_options')
                    ->row_array();

                $row['opt_stock_qty'] = $this->products_model->getOptionStockQty($prd_idx, $option );
            }
        }

        $option1 = [];
        if($product['prd_use_options'] == 'Y' ) {
            $option1 = $this->products_model->getOptionArray($product);
        }

        $this->response([
            "list"=> $cart,
            "price" => $row2,
            "option1" => $option1,
            "option2" => $product['options2']
        ], 200);

    }

    /**
     * 장바구니 모두 비우기
     * @return void
     */
    function cart_all_delete()
    {
        $is_direct = $this->delete('is_direct', TRUE);

        $is_direct = ($is_direct === true || $is_direct === 'Y') ? 'Y' : 'N';

        $this->load->model('shop_model');

        try {
            $this->shop_model->deleteAllCart($is_direct);
        }
        catch (Exception $e) {
            $this->response(["message"=>$e->getMessage()],500);
        }
    }

    /**
     * 장바구니에 담긴 상품 삭제
     */
    function cart_delete()
    {
        $prd_idx = $this->delete('prd_idx', TRUE);
        $is_direct = $this->delete('is_direct', TRUE);

        $is_direct = ($is_direct === true || $is_direct === 'Y') ? 'Y' : 'N';

        if(empty($prd_idx)) {
            $this->response(["message"=>"잘못된 접근입니다."],400);
        }
        $this->load->model('shop_model');

        try {
            $this->shop_model->deleteCart($prd_idx, $is_direct);
        }
        catch (Exception $e) {
            $this->response(["message"=>$e->getMessage()],500);
        }

    }

    /**
     * 장바구니에 상품 추가
     */
    function cart_post()
    {
        $is_direct = $this->post('is_direct', TRUE) === 'Y';
        $prd_idx = $this->post('prd_idx', TRUE);
        $cart_qty = $this->post('cart_qty', TRUE);
        $opt_code = $this->post('opt_code', TRUE) ?? [];
        $opt_type = $this->post('opt_type', TRUE) ?? [];
        $opt_value = $this->post('opt_value', TRUE) ?? [];

        if(! is_array($prd_idx) ) {
            $this->response(["message"=>"잘못된 접근입니다."],400);
        }

        $this->load->model('shop_model');

        try {
            $this->shop_model->updateCart($is_direct,$prd_idx,$cart_qty, $opt_code, $opt_type, $opt_value );
        }
        catch (Exception $exception) {
            $this->response(["message"=>$exception->getMessage()], 400);
        }
    }

    /**
     * 장바구니 상품의 수량 변경
     */
    function cart_put()
    {
        $this->load->model('shop_model');
        $this->load->model('products_model');

        $is_direct = $this->put('is_direct', TRUE) === 'Y';
        $prd_idx = $this->put('prd_idx', TRUE);
        $cart_qty = $this->put('cart_qty', TRUE);
        $opt_code = $this->put('opt_code', TRUE) ?? [];
        $opt_type = $this->put('opt_type', TRUE) ?? [];
        $opt_value = $this->put('opt_value', TRUE) ?? [];

        if(! is_array($prd_idx) ) {
            $this->response(["message"=>"잘못된 접근입니다."],400);
        }

        // 보관 기관이 지난 상품 삭제
        try {
            $this->shop_model->updateCart($is_direct,$prd_idx,$cart_qty, $opt_code, $opt_type, $opt_value, TRUE );
        }
        catch (Exception $exception) {
            $this->response(["message"=>$exception->getMessage()], 400);
        }

    }

    /**
     * 결제 전 주문서 임시저장
     */
    function payment_prepare_post()
    {
        $is_direct = $this->session->userdata('ss_direct');
        $cart_id = $this->session->userdata('ss_cart'.( $is_direct ? '_direct':'_id'));
        $data['od_mobile'] = $this->site->device === DEVICE_MOBILE ? 'Y' : 'N';

        $pg = $this->site->config('shop_pg_service');

        $data['od_settle_case'] = $this->post('pay_method', TRUE);
        $data['od_id'] = $this->session->userdata('ss_order_id');
        $data['od_test'] = $this->site->config('shop_pay_test') == 'Y' ? 'Y' : 'N';
        $data['od_ip'] = ip2long($this->input->ip_address());
        $data['od_pg'] = $pg;
        $data['od_receipt_price'] = str_replace(",","",$this->post('total_price', TRUE));
        $data['od_cart_price'] = str_replace(",","",$this->post('total_sell_price', TRUE));
        $data['od_send_cost'] = str_replace(",","",$this->post('send_cost', TRUE));

        // 동일한 주문번호가 있는지 체크한다.
        $cnt = (int)$this->db->select('COUNT(*) AS cnt')->from('shop_order')->where('od_id', $data['od_id'])->get()->row(0)->cnt;
        if($cnt > 0) {
            // 있으면 삭제한다.
            $this->db->where('od_id', $data['od_id'])->delete('shop_order');
        }
        $data['mem_idx'] = $this->member->is_login();

        // DEFAULT 값이 필요한 필드
        $data['od_memo'] = $this->post('od_memo', TRUE) ?? '';
        $data['od_name'] = trim($this->post('od_name', TRUE) ?? '');
        $data['od_email'] = trim($this->post('od_email', TRUE) ?? '');
        $data['od_tel'] = trim($this->post('od_tel', TRUE) ?? '');
        $data['od_hp'] = trim($this->post('od_hp', TRUE) ?? '');
        $data['od_zonecode'] = $this->post('od_zonecode', TRUE) ?? '';
        $data['od_addr1'] = trim($this->post('od_addr1', TRUE) ?? '');
        $data['od_addr2'] = trim($this->post('od_addr2', TRUE) ?? '');
        $data['od_shop_memo'] = '';
        $data['od_title'] = $this->post('prd_name', TRUE) ?? '';
        $data['od_time'] = date('Y-m-d H:i:s');

        if(!$this->db->insert('shop_order', $data))
        {
            $this->response(["주문서 생성도중 오류가 발생하였습니다."], 400);
        }

        if($pg === 'inicis') {
            $return['pg'] = 'html5_inicis.' . ($data['od_test'] == 'Y' ? 'INIpayTest' : $this->site->config('shop_inicis_mid'));

        } else if($pg === 'kcp') {
            $return['pg'] = 'kcp.' . ($data['od_test'] == 'Y' ? 'T0000':  $this->site->config('shop_kcp_site_key'));
        }
        $return['name'] = $data['od_title'];
        if($data['od_test'] == 'Y') {
            $return['name'] = "[TEST] ".$return['name'];
        }
        $return['pay_method'] = $data['od_settle_case'];
        $return['merchant_uid'] = $data['od_id'];
        $return['amount'] = $data['od_receipt_price'] * 1;
        $return['buyer_email'] = $data['od_email'];
        $return['buyer_name'] = $data['od_name'];
        $return['buyer_tel'] = $data['od_hp'];
        $return['buyer_addr'] = trim($data['od_addr1'] . " " . $data['od_addr2']);
        $return['buyer_postcode'] = $data['od_zonecode'];

        $this->response($return, 200);
    }

    function payment_error($message, $is_mobile = FALSE)
    {
        if($is_mobile) {
            alert($message);
            exit;
        }
        else {
            $this->response(["message"=>$message], 400);
            exit;
        }
    }
    /**
     * 결제후 검증처리
     * @param string $is_mobile
     */
    function payment_verify_get($is_mobile="")
    {
        $this->load->model('products_model');
        $this->load->model("shop_model");

        $is_mobile = strtolower($is_mobile) === 'mobile';
        $is_direct = $this->session->userdata('ss_direct') === 'Y';
        $imp_uid = $this->get('imp_uid', TRUE) ?? "";
        $od_id = $this->get('merchant_uid', TRUE) ?? "";
        $tmp_cart_id = $this->session->userdata('ss_cart'. ($is_direct?'_direct':'_id'));


        // 결제 검증을 위해 IAMPORT 에서 결제 데이타를 가져온다.
        $this->load->model('import_model');

        if(empty($od_id))
        {
            $this->payment_error('결제 검증에 실패하였습니다.1');
        }

        // 주문내역 테이블에서 임시저장된 주문서를 가져온다.
        if(! $order = $this->db->where('od_id', $od_id)->get('shop_order')->row_array())
        {
            if(! empty($imp_uid)) {
                $this->import_model->CancelPayment($imp_uid, $od_id);
            }
            $this->payment_error('결제 검증에 실패하였습니다.2');
        }

        // 무통장 거래의 경우, 결제금액 검증을 처리하지 않는다.
        if($order['od_settle_case'] != 'bank')
        {
            // imp_uid가 넘어오지 않은 경우
            if(empty($imp_uid)) {
                $this->payment_error('결제 검증에 실패하였습니다 : 주문서 정보를 찾을 수 없습니다.');
            }

            // Import 의 결제 데이타를 가져온다.
            if(!$paymentData = $this->import_model->getPaymentData($imp_uid))
            {
                $this->import_model->CancelPayment($imp_uid, $od_id);
                $this->payment_error('결제 검증에 실패하였습니다 : 결제 정보를 찾을 수 없습니다.');
            }

            if(! isset($paymentData['imp_uid'])) {
                $this->import_model->CancelPayment($imp_uid, $od_id);
                $this->payment_error('결제 검증에 실패하였습니다 : 결제 정보를 찾을 수 없습니다.');
            }

            // 실제 결제금액과 주문서상 금액이 일치하는지 확인
            if($paymentData['amount'] != $order['od_receipt_price'])
            {
                $this->import_model->CancelPayment($imp_uid, $od_id);
                $this->payment_error('결제 검증에 실패하였습니다 : 주문서상 금액과 실제 결제금액이 다릅니다.');
            }

            $data['od_misu'] = 0;
        }
        else {
            $data['od_misu'] = $order['od_receipt_price'];
        }

        // 장바구니 재고 검사
        // 장바구니 상품 재고 검사
        $result = $this->db
            ->select('C.prd_idx,C.cart_qty,C.prd_name,C.opt_code,C.opt_type,C.cart_option, IFNULL(PO.opt_stock_qty,0) AS opt_stock')
            ->from('shop_cart AS C')
            ->join('products_options AS PO','PO.prd_idx=C.prd_idx AND PO.opt_code=C.opt_code', 'left')
            ->where('od_id', $tmp_cart_id)
            ->where('cart_select', 'Y')
            ->get()
            ->result_array();

        $error = "";
        foreach($result as $row)
        {
            $product = $this->products_model->getItem($row['prd_idx']);

            $it_stock_qty = !empty($row['opt_code'])
                ? (int)$this->products_model->getOptionStockQty($row['prd_idx'],["opt_code"=>$row['opt_code'], "opt_type"=>$row['opt_type'], "opt_stock_qty"=>$row['opt_stock']])
                : (int)$this->products_model->getStockQty($product);

            // 장바구니 수량이 재고수량보다 많다면 오류
            if ($row['cart_qty'] > $it_stock_qty)
                $error .= "{$row['cart_option']} 의 재고수량이 부족합니다. 현재고수량 : $it_stock_qty 개\\n\\n";
        }

        if ($error != "")
        {
            $error .= "다른 고객님께서 {$order['od_name']}님 보다 먼저 주문하신 경우입니다. 불편을 끼쳐 죄송합니다.";

            $this->import_model->CancelPayment($imp_uid, $od_id);
            $this->payment_error($error);
        }

        // 장바구니 금액과 주문금액이 다른지 체크
        $sum = $this->db
            ->select('SUM(IF(opt_type = "addition", (opt_price * cart_qty), ((cart_price + opt_price) * cart_qty))) AS od_price', FALSE)
            ->select('COUNT(distinct opt_code) as cart_count')
            ->from('shop_cart')
            ->where('od_id', $tmp_cart_id)
            ->where('cart_select', 'Y')
            ->get()
            ->row_array();

        $tot_ct_price = $sum['od_price'];
        $cart_count = $sum['cart_count'];
        $tot_od_price = $tot_ct_price;

        if($tot_ct_price != $order['od_cart_price']) {
            $this->import_model->CancelPayment($imp_uid, $od_id);
            $this->payment_error('결제 검증에 실패하였습니다 : 주문서 금액이 다릅니다 ');
        }

        // 배송비 체크
        $send_cost = $this->shop_model->getSendCost($tmp_cart_id, 'Y');

        if($send_cost != $order['od_send_cost']) {
            $this->import_model->CancelPayment($imp_uid, $od_id);
            $this->payment_error('결제 검증에 실패하였습니다 : 배송비 금액이 다릅니다 : '.$send_cost.":".$order['od_send_cost']);
        }

        $data['od_status'] = '주문';
        $data['od_receipt_time'] = date('Y-m-d H:i:s');

        if($data['od_misu'] == 0) {
            $data['od_status'] = '입금';
        }
        $data['imp_uid'] = $imp_uid;
        $data['od_cart_count'] = $cart_count;

        // 트랜젝션 시작
        $this->db->trans_begin();

        // 주문서 정보 변경
        $this->db
            ->where('od_id', $od_id)
            ->update('shop_order', $data);
        
        // 장바구니 상태 변경
        $this->db
            ->set('od_id', $od_id)
            ->set('cart_status', $data['od_status'])
            ->where('od_id', $tmp_cart_id)
            ->where('cart_select', 'Y')
            ->update('shop_cart');

        $this->session->set_userdata('od_id', $od_id);

        // 주문/결제완료시 SMS 또는 카카오 알림톡 발송
        if($this->site->config('shop_sms_type') !== 'NONE')
        {
            $_order = $this->db->where('od_id', $od_id)->get('shop_order')->row_array();

            // 무통장입금의 경우에는 입금계좌안내를 발송한다.
            $sms_data['phone'] = $order['od_hp'];
            $sms_data['content'] =  $this->site->config($order['od_settle_case'] != 'bank'?'shop_sms_order_complete_cc':'shop_sms_bank_info_cc');
            $sms_data['code'] =  $this->site->config($order['od_settle_case'] != 'bank'?'shop_sms_order_complete_c':'shop_sms_bank_info_c');

            $table_column = "oc";
            if($order['od_settle_case'] == 'bank')
            {
                $table_column = "ip";
            }

            // 필요한단어들을 대체한다.
            $sms_data['content'] = str_replace("#{주문번호}", $_order['od_id'], $sms_data['content']);
            $sms_data['content'] = str_replace("#{주문자}", $_order['od_name'], $sms_data['content']);
            $sms_data['content'] = str_replace("#{주문금액}", number_format($_order['od_receipt_price']), $sms_data['content']);
            $sms_data['content'] = str_replace("#{주문상품}", $_order['od_title'], $sms_data['content']);
            $sms_data['content'] = str_replace("#{계좌번호}", $this->site->config('shop_bank_account'), $sms_data['content']);

            $this->load->library('ncloud');
            try {
                if(! empty($sms_data['content'])){
                    $this->ncloud->send($sms_data);
                }

                $this->db
                    ->where('od_id', $od_id)
                    ->set("od_{$table_column}_send", "Y")
                    ->set("od_{$table_column}_datetime", date('Y-m-d H:i:s'))
                    ->update('shop_order');
            }
            catch (Exception $e) {}
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->import_model->CancelPayment($imp_uid, $od_id);

            $this->payment_error('주문서 저장도중 오류가 발생하였습니다.');
        }
        else {
            $this->db->trans_commit();

            if($is_mobile) {
                redirect(base_url('/shop/order-complete'));
            }
            else {
                $this->response(["result"=>TRUE,"message"=>"정상"], 200);
            }
        }
    }
}