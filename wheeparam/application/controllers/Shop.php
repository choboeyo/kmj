<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 쇼핑몰 관련 페이지
 *
 * @property Products_model $products_model
 * @property Shop_model $shop_model
 */
class Shop extends WB_Controller
{
    function __construct()
    {
        parent::__construct();

        if(! USE_SHOP) {
            alert('쇼핑몰 사용 설정이 되어있지 않습니다.');
            exit;
        }

        $this->load->model('products_model');
        $this->load->model('shop_model');
    }

    /**
     * 장바구니
     * @return void
     */
    function cart($direct="")
    {
        // 바로구매여부 확인
        $this->data['is_direct'] = strtolower($direct) === 'direct';

        $cartData = $this->shop_model->getCartList($this->data['is_direct']);

        $this->data['list'] = $cartData['list'];
        $this->data['total_price'] = $cartData['total_price'];
        $this->data['total_sell_price'] = $cartData['total_sell_price'];
        $this->data['send_cost'] = $cartData['send_cost'];
        $this->data['prev_cat_id'] = $cartData['prev_cat_id'];

        $hiddenVars['is_direct'] = $this->data['is_direct'] ? 'Y':'N';
        $this->data['form_open'] = form_open("shop/order", ["data-form"=>"shop-cart"], $hiddenVars);
        $this->data['form_close'] = form_close();

        $this->theme = $this->site->get_layout();
        $this->skin = $this->site->config('skin_shop'.($this->site->viewmode===DEVICE_MOBILE?'_m':''));
        $this->skin_type = "shop";
        $this->view = "cart";
    }


    /**
     * 장바구니 수량변경 페이지
     */
    function cart_modify()
    {
        $this->load->model('products_model');
        $this->load->model('shop_model');

        $this->data['is_direct'] = $this->input->get('is_direct', TRUE, 'N');
        $this->data['prd_idx'] = $this->input->get('prd_idx', TRUE);


        if(empty($this->data['prd_idx'])) {
            set_status_header(400);
            echo json_encode(["message"=>"장바구니에서 상품이 올바르게 선택되지 않았습니다."]);
            exit();
        }

        if(! $product = $this->products_model->getItem($this->data['prd_idx']))
        {
            set_status_header(400);
            echo json_encode(["message"=>"상품정보를 가져올 수 없습니다. 상품이 이미 삭제되었거나, 품절된 상품입니다."]);
            exit();
        }

        // 장바구니에서 자료 가져오기
        $cart_id = $this->session->userdata('ss_cart_id');
        $cart = $this->db
            ->select('C.*, IFNULL(PO.opt_stock_qty,0) AS opt_stock')
            ->from('shop_cart AS C')
            ->join('products_options AS PO','PO.prd_idx=C.prd_idx AND PO.opt_code=C.opt_code','left')
            ->where('C.od_id', $cart_id)
            ->where('C.prd_idx', $this->data['prd_idx'])
            ->where('C.cart_direct', $this->data['is_direct'])
            ->order_by('C.opt_type DESC, C.cart_id ASC')
            ->get()
            ->result_array();

        if(! $cart) {
            set_status_header(400);
            echo json_encode(["message"=>"상품정보를 가져올 수 없습니다. 상품이 이미 삭제되었거나, 품절된 상품입니다."]);
            exit();
        }

        // 판매가격 가져오기
        $row2 = $this->db
            ->select('cart_price, prd_name, cart_send_cost')
            ->from('shop_cart')
            ->where('od_id', $cart_id)
            ->where('prd_idx', $this->data['prd_idx'])
            ->where('cart_direct', $this->data['is_direct'])
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
                    ->where('prd_idx', $this->data['prd_idx'])
                    ->where('opt_code', $row['opt_code'])
                    ->get('products_options')
                    ->row_array();

                $row['opt_stock_qty'] = $this->products_model->getOptionStockQty($this->data['prd_idx'], $option );
            }
        }

        $option1 = [];
        /*
        if($product['prd_use_options'] == 'Y' ) {
            $option1 = $this->products_model->getOptionArray($product);
        }*/

        $this->data['list'] = $cart;
        $this->data['price'] = $row2;
        $this->data['option1'] = $option1;
        $this->data['option2'] = $product['options2'];

        // 목록스킨을 정의한다.
        $suffix = $this->site->viewmode === DEVICE_MOBILE ? '_m' : '';
        $skin = $this->site->config('skin_shop'.$suffix);

        $hiddenVars['is_direct'] = $this->data['is_direct'];
        $hiddenVars['prd_idx[]'] = $this->data['prd_idx'];
        $hiddenVars['cart_price'] = $row2['cart_price'];
        $hiddenVars['cart_send_cost'] = $row2['cart_send_cost'];

        $this->data['form_open'] = form_open(NULL, ["data-form"=>"shop-cart-modify"], $hiddenVars);
        $this->data['form_close'] = form_close();

        $this->theme = FALSE;
        $this->skin = $skin;
        $this->skin_type = "shop";
        $this->view = "cart.modify.php";
    }


    /**
     * 주문서 페이지
     */
    function order($direct="")
    {
        // 2시간이 지난 임시주문서는 삭제한다.
        $date = date('Y-m-d H:i:s', strtotime('-2 hours'));
        $this->db->where('od_time <', $date)->where('od_status','')->delete('shop_order');

        $is_direct = $this->input->post('is_direct', TRUE) === 'Y';
        $prd_idx_array = $this->input->post('prd_idx', TRUE);

        if(strtolower($direct) === "direct") {
            $is_direct= true;
        }

        if(!$is_direct) {
            if(empty($prd_idx_array)) {
                alert('잘못된 접근입니다.', base_url('shop/cart'));
                exit;
            }
        } else {
            $prd_idx_array = [];
        }

        $hiddenVars = [];
        try {
            $this->shop_model->buyCart($is_direct, $prd_idx_array);
        }
        catch (Exception $e) {
            alert($e->getMessage());
        }

        // 결제수단
        $pay_method_array = [
            'shop_card_pay_use' => ["label"=>"신용카드","value"=>"card"],
            'shop_bank_use' => ["label"=>"무통장입금","value"=>"bank"],
            'shop_iche_use' => ["label"=>"실시간계좌이체","value"=>"trans"],
            'shop_vbank_use' => ["label"=>"가상계좌","value"=>"vbank"],
            'shop_hp_pay_use' => ["label"=>"휴대폰소액결제","value"=>"phone"],
            'shop_use_global_naverpay' => ["label"=>"네이버페이","value"=>"naverpay"],
            'shop_use_samsungpay' => ["label"=>"삼성페이","value"=>"samsung"],
            'shop_kakaopay_use' => ["label"=>"카카오페이","value"=>"kakaopay"],
        ];
        $this->data['pay_methods'] = [];
        foreach($pay_method_array as $key=>$pay) {
            if($this->site->config($key) === 'Y') {
                $this->data['pay_methods'][] = $pay;
            }
        }

        if(count($this->data['pay_methods']) === 0) {
            alert('설정된 결제수단이 없습니다. 관리자설정에서 사용할 결제수단을 설정해주세요.', base_url('shop/cart'));
            exit;
        }

        $this->session->set_userdata('ss_direct', $is_direct ? 'Y' : 'N');
        $tmp_cart_id = $this->session->userdata('ss_cart' . ( $is_direct ? '_direct' : '_id'));
        
        // 장바구니가 비어있으면 리턴
        if($this->shop_model->getCartCount($tmp_cart_id) === 0) {
            alert('장바구니가 비어있습니다.', base_url('shop/cart'));
            exit;
        }

        // 장바구니 금액 업데이트
        $this->shop_model->updateCartPrice($tmp_cart_id);

        // 모바일인지 체크
        $this->data['is_mobile'] = $this->site->viewmode === DEVICE_MOBILE;

        // 새로운 주문번호 생성하고 세션에 저장
        $this->data['od_id'] = get_uniqid();
        $this->session->set_userdata('ss_order_id', $this->data['od_id']);
        $s_cart_id = $tmp_cart_id;

        $hiddenVars['imp_code'] = $this->site->config('shop_portone_imp_code');
        // 구매선택한 장바구니 목록 불러오기
        $_temp = $this->shop_model->getCartList($is_direct, TRUE);
        $this->data['cart_list'] = $_temp['list'];
        $this->data['total_price'] = $_temp['total_price'];
        $this->data['total_sell_price'] = $_temp['total_sell_price'];
        $this->data['send_cost'] = $_temp['send_cost'];

        if(count($this->data['cart_list']) === 0) {
            alert('주문 선택한 상품이 없습니다.', base_url('shop/cart'));
            exit;
        }

        // 테스트 결제인지 여부 체크
        $is_test_pay = $this->site->config('shop_pay_test') === 'Y';
        $hiddenVars['od_id'] = $this->data['od_id'];

        $hiddenVars['total_price'] = $this->data['total_price'];
        $hiddenVars['total_sell_price'] = $this->data['total_sell_price'];
        $hiddenVars['send_cost'] = $this->data['send_cost'];

        // 주문상품 이름 만들어주기
        $hiddenVars['prd_name'] = $this->data['cart_list'][0]['prd_name'];
        if(count($this->data['cart_list']) > 1) {
            $hiddenVars['prd_name'] .= " 외 " . (count($this->data['cart_list']) - 1) .'건';
        }

        $this->data['form_open'] = form_open(NULL,["data-form"=>"shop-order"], $hiddenVars);
        $this->data['form_close'] = form_close() ;
        $this->data['form_close'].= '<script>var IMP = window.IMP;IMP.init("'.$hiddenVars['imp_code'].'");</script>';

        $this->site->add_js('//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js');
        $this->site->add_js('https://cdn.iamport.kr/v1/iamport.js');
        $this->theme = $this->site->get_layout();
        $this->skin = $this->site->config('skin_shop'.($this->site->viewmode===DEVICE_MOBILE?'_m':''));
        $this->skin_type = "shop";
        $this->view = "order";
    }

    /**
     * 주문완료 페이지
     */
    function order_complete()
    {
        $od_id = $this->session->userdata('ss_order_id');

        if(empty($od_id)) {
            alert('주문서 세션이 만료되었습니다.', base_url('/'));
            exit;
        }

        if(! $this->data['order'] = $this->db->where('od_id', $od_id)->get('shop_order')->row_array())
        {
            alert('주문서 세션이 만료되었습니다.', base_url('/'));
            exit;
        }

        $this->theme = $this->site->get_layout();
        $this->skin = $this->site->config('skin_shop'.($this->site->viewmode===DEVICE_MOBILE?'_m':''));
        $this->skin_type = "shop";
        $this->view = "order_complete";
    }
}