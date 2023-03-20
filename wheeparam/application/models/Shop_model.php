<?php
/**
 * 쇼핑몰과 관련된 MODEL
 * 
 * @property Products_model $products_model
 * @property CI_Session $session
 */
class Shop_model extends WB_Model {
    private $cart_keep_term = 15;
    private $cart_stock_limit = 3;
    
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('products_model');
    }

    /**
     * 장바구니 목록을 가져옵니다.
     * @param bool $direct
     * @param bool $is_order 주문용 장바구니 가져오는것인지?
     */
    function getCartList($direct = FALSE, $is_order= FALSE)
    {
        $this->setCartId($direct);

        $s_cart_id = $this->session->userdata('ss_cart_'. ( $direct ? 'direct' : 'id' ) );

        if(! $is_order) {

            // 선택필드 초기화
            $this->db
                ->set('cart_select','N')
                ->where('od_id', $s_cart_id)
                ->update('shop_cart');


            // 장바구니의 금액을 최신으로 업데이트
            $this->updateCartPrice($s_cart_id, TRUE, TRUE);
        }


        // 장바구니 목록 불러오기
        if($is_order) {
            $this->db->where('C.cart_select', 'Y');
        }
        $return['list'] = $this->db
            ->select('C.cart_id, C.prd_idx, C.opt_code, C.opt_type, C.prd_name, C.cart_price, C.cart_qty, C.cart_status, C.cart_send_cost,C.cart_sc_type')
            ->select('P.cat_id, P.prd_buy_min_qty, P.prd_buy_max_qty')
            ->select('PA.att_filepath')
            ->from('shop_cart AS C')
            ->join('products AS P', 'P.prd_idx=C.prd_idx', 'left')
            ->join('attach AS PA', 'P.prd_thumbnail=PA.att_idx','left')
            ->where('C.od_id', $s_cart_id)
            ->group_by('C.prd_idx')
            ->order_by('C.cart_id')
            ->get()
            ->result_array();

        $return['total_sell_price'] = 0;
        $return['send_cost'] = $this->getSendCost($s_cart_id, $is_order?'Y':'N');
        $return['prev_cat_id'] = "";

        foreach($return['list'] as $i=>&$row)
        {
            $row['thumbnail'] = '';
            if(!empty($row['att_filepath']) && file_exists(FCPATH . $row['att_filepath'])) {
                $row['thumbnail'] = $row['att_filepath'];
            }
            $row['link'] = base_url('products/items/'.$row['prd_idx']);

            // 합계끔액 계산
            $sum = $this->db
                ->select('SUM(IF(opt_type = "addition", (opt_price * cart_qty), ((cart_price + opt_price) * cart_qty))) as price', FALSE)
                ->select('SUM(cart_qty) as qty')
                ->from('shop_cart')
                ->where('prd_idx', $row['prd_idx'])
                ->where('od_id', $s_cart_id)
                ->get()
                ->row_array();

            if ($i==0) { // 계속쇼핑
                $return['prev_cat_id'] = $row['cat_id'];
            }

            switch($row['cart_send_cost'])
            {
                case 1:
                    $row['send_cost'] = '착불';
                    break;
                case 2:
                    $row['send_cost'] = '무료';
                    break;
                default:
                    $row['send_cost'] = '선불';
                    break;
            }

            // 조건부무료
            if($row['cart_sc_type'] == '조건부무료') {
                $sendcost = $this->getItemSendCost($row['prd_idx'], $sum['price'], $sum['qty'], $s_cart_id);

                if($sendcost == 0)
                    $row['send_cost'] = '무료';
            }

            // @todo 옵션항목이 있는경우 옵션항목 가져오기,
            $row['cart_option_array'] = [];
            if(! empty($row['opt_code'])) {
                $row['cart_option_array'] = $this->getCartOptions($row['prd_idx'], $s_cart_id);
            }

            $row['sell_price'] = $sum['price'];
            $row['sell_qty'] = $sum['qty'];

            $return['total_sell_price'] += $row['sell_price'];
            $row['cart_price'] = $row['cart_price'] * 1;
            $row['cart_qty'] = $row['cart_qty'] * 1;
            $row['cart_send_cost'] = $row['cart_send_cost'] * 1;
            $row['sell_price'] = $row['sell_price'] * 1;
            $row['sell_qty'] = $row['sell_qty'] * 1;
            $row['prd_buy_min_qty'] = $row['prd_buy_min_qty'] * 1;
            $row['prd_buy_max_qty'] = $row['prd_buy_max_qty'] * 1;
            if($row['prd_buy_min_qty'] === 0) {
                $row['prd_buy_min_qty'] = 1;
            }
            if($row['prd_buy_max_qty'] === 0) {
                $row['prd_buy_max_qty'] = 20;
            }
        }

        $return['total_price'] = $return['total_sell_price'] + $return['send_cost']; // 총계 = 주문상품금액합계 + 배송비

        return $return;
    }

    function getCartListByOrder($od_id)
    {
        $return['list'] = $this->db
            ->select('C.cart_id, C.prd_idx, C.opt_code, C.opt_type, C.prd_name, C.cart_price, C.cart_qty, C.cart_status, C.cart_send_cost,C.cart_sc_type')
            ->select('PA.att_filepath')
            ->from('shop_cart AS C')
            ->join('products AS P', 'P.prd_idx=C.prd_idx', 'left')
            ->join('attach AS PA', 'P.prd_thumbnail=PA.att_idx','left')
            ->where('C.od_id', $od_id)
            ->group_by('C.prd_idx')
            ->order_by('C.cart_id')
            ->get()
            ->result_array();

        foreach($return['list'] as &$row)
        {
            $row['thumbnail'] = '';
            if(!empty($row['att_filepath']) && file_exists(FCPATH . $row['att_filepath'])) {
                $row['thumbnail'] = $row['att_filepath'];
            }
            $row['link'] = base_url('products/items/'.$row['prd_idx']);

            // 합계끔액 계산
            $sum = $this->db
                ->select('SUM(IF(opt_type = "addition", (opt_price * cart_qty), ((cart_price + opt_price) * cart_qty))) as price', FALSE)
                ->select('SUM(cart_qty) as qty')
                ->from('shop_cart')
                ->where('prd_idx', $row['prd_idx'])
                ->where('od_id', $od_id)
                ->get()
                ->row_array();

            switch($row['cart_send_cost'])
            {
                case 1:
                    $row['send_cost'] = '착불';
                    break;
                case 2:
                    $row['send_cost'] = '무료';
                    break;
                default:
                    $row['send_cost'] = '선불';
                    break;
            }

            // 조건부무료
            if($row['cart_sc_type'] == '조건부무료') {
                $sendcost = $this->getItemSendCost($row['prd_idx'], $sum['price'], $sum['qty'], $od_id);

                if($sendcost == 0)
                    $row['send_cost'] = '무료';
            }

            // @todo 옵션항목이 있는경우 옵션항목 가져오기,
            $row['cart_option_array'] = [];
            if(! empty($row['opt_code'])) {
                $row['cart_option_array'] = $this->getCartOptions($row['prd_idx'], $od_id);
            }
            $row['sell_price'] = $sum['price'];
            $row['sell_qty'] = $sum['qty'];
        }

        return $return;
    }

    function getCartCount($cart_id)
    {
        return (int)$this->db
            ->select('COUNT(cart_id) AS cnt')
            ->from('shop_cart')
            ->where('od_id', $cart_id)
            ->get()
            ->row(0)
            ->cnt;
    }

    function getInicisAppScheme()
    {
        $user_agent = $this->input->server('HTTP_USER_AGENT');

        $iPod = stripos($user_agent,"iPod");
        $iPhone  = stripos($user_agent,"iPhone");
        $iPad    = stripos($user_agent,"iPad");

        if( $iPod || $iPhone || $iPad ){    //IOS 의 앱브라우저에서 ISP결제시 리다이렉트 safari로 돌아가는 문제가 있음
            if( preg_match('/NAVER\(inapp;/', $user_agent) ){       //네이버
                return 'app_scheme=naversearchapp://&';
            }
            else if( preg_match('/CriOS/', $user_agent) ){          //크롬
                return 'app_scheme=googlechromes://&';
            }
            else if( preg_match('/DaumDevice/', $user_agent) ){      //다음
                return 'app_scheme=daumapps://&';
            }
            else if( preg_match('/KAKAOTALK/', $user_agent) ){          //카카오톡
                return 'app_scheme=kakaotalk://&';
            }
            else if( preg_match('/(FBAN|FBAV)/', $user_agent) ){        //페이스북
                return 'app_scheme=fb://&';
            }
        }

        return '';
    }

    /**
     * 보관기간이 지난 상품 삭제
     */
    function cartClean()
    {
        $stocktime = 0;

        if($this->cart_stock_limit > 0) {
            if($this->cart_stock_limit > $this->cart_keep_term * 24)
                $this->cart_stock_limit = $this->cart_keep_term * 24;

            $stocktime = time() - (3600 * $this->cart_stock_limit);

            $this->db
                ->where('cart_select','Y')
                ->where('cart_status','쇼핑')
                ->where('UNIX_TIMESTAMP(cart_select_time) <', $stocktime)
                ->set('cart_select', 'N')
                ->update('shop_cart');
        }

        // 설정 시간이상 경과된 상품 삭제
        $statustime = time() - (86400 * $this->cart_keep_term);

        $this->db
            ->where('cart_status','쇼핑')
            ->where('UNIX_TIMESTAMP(cart_regtime) <', $statustime)
            ->delete('shop_cart');
    }

    /**
     * 장바구니 초기화
     */
    function refershCart()
    {
        // 보관기간이 지난 상품 삭제
        $this->cartClean();

        $s_cart_id = preg_replace('/[^a-z0-9_\-]/i', '', $this->session->userdata('ss_cart_id'));

        // 선택필드 초기화
        if( $s_cart_id ){
            $this->db->where('od_id', $s_cart_id)->set('cart_select','N')->update('shop_cart');
        }
    }

    /**
     * CART ID 설정
     *
     * @param bool $direct
     */
    function setCartId($direct = FALSE)
    {
        // 바로구매일 경우
        if( $direct )
        {
            $tmp_cart_id = $this->session->userdata('ss_cart_direct');
            if(! $tmp_cart_id) {
                $tmp_cart_id = get_uniqid();
                $this->session->set_userdata('ss_cart_direct', $tmp_cart_id);
            }
        }
        // 장바구니 일경우
        else
        {
            $tmp_cart_id = preg_replace('/[^a-z0-9_\-]/i', '', get_cookie('ck_guest_cart_id'));
            if($tmp_cart_id) {
                $this->session->set_userdata('ss_cart_id', $tmp_cart_id);
            } else {
                $tmp_cart_id = get_uniqid();
                $this->session->set_userdata('ss_cart_id', $tmp_cart_id);
                set_cookie('ck_guest_cart_id', $tmp_cart_id, ($this->cart_keep_term * 86400));
            }

            // 보관된 회원바구니 자료 CART ID 변경
            $mem_idx = $this->member->is_login();
            if($mem_idx > 0 && $tmp_cart_id)
            {
                $this->db
                    ->where('mem_idx', $mem_idx)
                    ->where('cart_direct', 'N')
                    ->where('cart_status','쇼핑')
                    ->set('od_id', $tmp_cart_id)
                    ->update('shop_cart');
            }
        }
    }

    /**
     * 장바구니 삭제
     *
     * @param array|string|int $prd_idx
     */
    function deleteCart($prd_idx="", $is_direct="N")
    {
        $this->cartClean();

        if(empty($prd_idx)) {
            return;
        }
        $s_cart_id = $this->session->userdata('ss_cart_id');

        if(is_array($prd_idx)) {
            $this->db->where_in('prd_idx', $prd_idx);
        } else {
            $this->db->where('prd_idx', $prd_idx);
        }

        $this->db->where('cart_direct', $is_direct);

        if(! $this->db
            ->where('od_id', $s_cart_id)
            ->delete('shop_cart')) {
            throw new Exception('DB 입력도중 오류가 발생하였습니다.');
        }
    }

    /**
     * 장바구니 전체 삭제
     * @param $is_direct
     * @return void
     */
    function deleteAllCart($is_direct="N")
    {
        $this->cartClean();
        $s_cart_id = $this->session->userdata('ss_cart_id');
        $this->db->where('cart_direct', $is_direct);

        if(! $this->db
            ->where('od_id', $s_cart_id)
            ->delete('shop_cart')) {
            throw new Exception('DB 입력도중 오류가 발생하였습니다.');
        }
    }

    /**
     * @param array $prd_idx_array 장바구니에 업데이트할 상품 PK 배열
     * @param array $opt_code_array 장바구니에 업데이트할 옵션 코드 배열
     * @param array $opt_type_array 장바구니에 업데이트할 옵션 타입 배열
     * @param array $cart_qty_array 장바구니에 업데이트할 수량 배열
     * @param array $opt_value_array 장바구니에 업데이트할 수량 배열
     * @param bool $is_direct 바로구매인지 여부
     * @param bool $isModify 장바구니 수량변경 처리인지
     * @throws Exception
     */
    function updateCart(bool $is_direct, array $prd_idx_array, array $cart_qty_array, array $opt_code_array = [], array $opt_type_array = [],array $opt_value_array = [], bool $isModify = FALSE)
    {
        $this->cartClean();

        $this->setCartId($is_direct);

        $tmp_cart_id = $this->session->userdata('ss_cart_'. ( $is_direct ? 'direct' : 'id' ) );

        // 브라우저에서 쿠키를 허용하지 않은 경우라고 볼 수 있음.
        if (!$tmp_cart_id)
        {
            throw new Exception('더 이상 작업을 진행할 수 없습니다.\n\n브라우저의 쿠키 허용을 사용하지 않음으로 설정한것 같습니다.\n\n브라우저의 인터넷 옵션에서 쿠키 허용을 사용으로 설정해 주십시오.\n\n그래도 진행이 되지 않는다면 쇼핑몰 운영자에게 문의 바랍니다.');;
        }

        $tmp_cart_id = preg_replace('/[^a-z0-9_\-]/i', '', $tmp_cart_id);

        // [상품 구입권한 체크 : 다음버젼]

        $count = count($prd_idx_array);

        if($count < 1) {
            throw new Exception('장바구니에 담을 상품을 선택해주세요');
        }

        $this->load->model('products_model');

        $mem_idx = $this->member->is_login();

        $ct_count = 0;
        for($i=0; $i<$count; $i++)
        {
            $prd_idx = $prd_idx_array[$i];
            $opt_count = isset($opt_code_array[$prd_idx]) && is_array($opt_code_array[$prd_idx]) ? count($opt_code_array[$prd_idx]) : 0;

            // 상품정보
            if(! $product = $this->products_model->getItem($prd_idx))
            {
                throw new Exception('상품정보가 존재하지 않습니다.');
            }

            $opt_list = [];
            $options_all = array_merge($product['options'], $product['options2']);
            $lst_count = 0;

            for($k=0; $k<count($options_all); $k++) {
                $opt_list[$options_all[$k]['opt_type']][$options_all[$k]['opt_code']]['id'] = $options_all[$k]['opt_code'];
                $opt_list[$options_all[$k]['opt_type']][$options_all[$k]['opt_code']]['use'] = $options_all[$k]['opt_status'] === 'Y';
                $opt_list[$options_all[$k]['opt_type']][$options_all[$k]['opt_code']]['price'] = $options_all[$k]['opt_add_price'];
                $opt_list[$options_all[$k]['opt_type']][$options_all[$k]['opt_code']]['stock'] = $options_all[$k]['opt_stock_qty'];
                $opt_list[$options_all[$k]['opt_type']][$options_all[$k]['opt_code']]['subject'] = $options_all[$k]['opt_subject'];

                // 선택옵션 개수
                if( $options_all[$k]['opt_type'] === 'detail')
                    $lst_count++;
            }

            if($lst_count > 0 && !trim($opt_code_array[$prd_idx][$i]) && $opt_type_array[$prd_idx][$i] == 0)
            {
                throw new Exception('상품의 필수 선택 옵션을 선택해 주십시오.');
            }

            for($k=0; $k<$opt_count; $k++) {
                $post_ct_qty = isset($cart_qty_array[$prd_idx][$k]) ? (int) $cart_qty_array[$prd_idx][$k] : 0;
                if ($post_ct_qty < 1) {
                    throw new Exception('수량은 1 이상 입력해주세요.');
                }
            }

            // 바로구매에 있던 장바구니 자료를 지운다.
            if($i == 0 && $is_direct) {
                $this->db->where('od_id' ,$tmp_cart_id)->where('cart_direct', 'Y')->delete('shop_cart');
            }

            // 최소, 최대 수량 체크
            if($product['prd_buy_min_qty'] > 0 || $product['prd_buy_max_qty'] > 0) {
                $sum_qty = 0;
                for($k=0; $k<$opt_count; $k++) {
                    if(isset($opt_type_array[$prd_idx][$k]) && $opt_type_array[$prd_idx][$k] == 0){
                        $post_ct_qty = isset($cart_qty_array[$prd_idx][$k]) ? (int) $cart_qty_array[$prd_idx][$k] : 0;
                        $sum_qty += $post_ct_qty;
                    }
                }

                if($product['prd_buy_min_qty'] > 0 && $sum_qty < $product['prd_buy_min_qty']) {
                    throw new Exception($product['prd_name'].'의 필수 선택옵션 개수 총합 '.number_format($product['prd_buy_min_qty']).'개 이상 주문해 주십시오.');
                }

                if($product['prd_buy_max_qty'] > 0 && $sum_qty > $product['prd_buy_max_qty']) {
                    throw new Exception( $product['prd_name'].'의 선택옵션 개수 총합 '.number_format($product['prd_buy_max_qty']).'개 이하로 주문해 주십시오.');
                }

                // 기존에 장바구니에 담긴 상품이 있는 경우에 최대 구매수량 체크
                if($product['prd_buy_max_qty'] > 0) {
                    if($isModify) {
                        $check_count = $sum_qty;
                    }
                    else {
                        $exist_sum = (int) $this->db
                            ->select_sum('cart_qty', 'sumval')
                            ->where('od_id', $tmp_cart_id)
                            ->where('prd_idx', $prd_idx)
                            ->where('opt_type', 'detail')
                            ->where('cart_status','쇼핑')
                            ->get('shop_cart')
                            ->row(0)->sumval;

                        $check_count = $sum_qty + $exist_sum;
                    }

                    if(($check_count) > $product['prd_buy_max_qty']) {
                        throw new Exception( $product['prd_name'].'의 선택옵션 개수 총합 '.number_format($product['prd_buy_max_qty']).'개 이하로 주문해 주십시오.');
                    }
                }
            }

            // 수량 업데이트라면 기존 자료 삭제
            if($isModify) {
                $this->db
                    ->where('od_id', $tmp_cart_id)
                    ->where('prd_idx', $prd_idx)
                    ->delete('shop_cart');

            }

            // 장바구니에 INSERT
            // 바로구매일 경우 장바구니가 체크된것으로 강제 설정
            $cart_select = 'N';
            $cart_select_time = '0000-00-00 00:00:00';

            if($is_direct) {
                $cart_select = 'Y';
                $cart_select_time = date('Y-m-d H:i:s');
            }

            $insertArray = [];
            for($k=0; $k<$opt_count; $k++) {
                $opt_code = $opt_code_array[$prd_idx][$k] ?? '';
                $opt_type = $opt_type_array[$prd_idx][$k] ?? '';
                $opt_value = $opt_value_array[$prd_idx][$k] ?? '';


                // 선택옵션정보가 존재하는데 선택된 옵션이 없으면 건너뜀
                if($lst_count && $opt_code == '')
                    continue;

                $opt_list_type_id_use = element('use', element($opt_code, element($opt_type, $opt_list, [])));
                $opt_stock_qty = element('stock', element($opt_code, element($opt_type, $opt_list, [])));

                // 구매할 수 없는 옵션은 건너뜀
                if($opt_code && ! $opt_list_type_id_use)
                    continue;

                $opt_price = $opt_list[$opt_type][$opt_code]['price'] ?? 0;
                $cart_qty = isset($cart_qty_array[$prd_idx][$k]) ? (int) $cart_qty_array[$prd_idx][$k] : 0;
                $opt_subject = $opt_list[$opt_type][$opt_code]['subject'] ?? '';

                // 구매가격이 음수인지 체크
                if($opt_type === 'detail') {
                    if((int)$opt_price < 0)
                        throw new Exception('구매금액이 음수인 상품은 구매할 수 없습니다.');
                } else {
                    if((int)$product['prd_price'] + (int)$opt_price < 0)
                        throw new Exception('구매금액이 음수인 상품은 구매할 수 없습니다.');
                }

                // 기존에 같은 상품이 있으면 수량을 업데이트 한다.
                $row2 = $this->db
                    ->select('cart_id,opt_type,cart_qty')
                    ->from('shop_cart')
                    ->where('od_id', $tmp_cart_id)
                    ->where('prd_idx', $prd_idx)
                    ->where('opt_code', $opt_code)
                    ->where('cart_status', '쇼핑')
                    ->get()
                    ->row_array();

                if(isset($row2['cart_id']) && $row2['cart_id']) {
                    // 재고체크
                    $tmp_ct_qty = $row2['cart_qty'];
                    if(!$opt_code)
                        $tmp_it_stock_qty = $this->products_model->getStockQty($product);
                    else
                        $tmp_it_stock_qty = $this->products_model->getOptionStockQty($prd_idx ,["opt_code"=>$opt_code, "opt_type"=>$opt_type,"opt_stock_qty"=>$opt_stock_qty]);

                    if ($tmp_ct_qty + $cart_qty > $tmp_it_stock_qty)
                    {
                        throw new Exception($opt_value .' 의 재고수량이 부족합니다.\n\n현재 재고수량 : '.number_format($tmp_it_stock_qty) . " 개");
                    }

                    $this->db
                        ->where('cart_id', $row2['cart_id'])
                        ->set("cart_qty", $row2['cart_qty'] + $cart_qty)
                        ->update('shop_cart');

                    continue;
                }
                
                // 포인트 처리

                // 배송비 결제
                $cart_send_cost = 0;
                if($product['prd_sc_type'] == '무료') {
                    $cart_send_cost = 2;
                }
                else if (in_array($product['prd_sc_type'], ["조건부무료","유료","수량별"]) && $product['prd_sc_method'] == '착불')
                {
                    $cart_send_cost = 1;
                }

                $insertArray[] = [
                    "od_id" => $tmp_cart_id,
                    "mem_idx" =>$mem_idx,
                    "prd_idx" => $prd_idx,
                    "prd_name" => $product['prd_name'],
                    "cart_sc_type" => $product['prd_sc_type'],
                    "cart_sc_method" => $product['prd_sc_method'],
                    "cart_sc_price" => $product['prd_sc_price'],
                    "cart_sc_minimum" => $product['prd_sc_minimum'],
                    "cart_sc_qty" =>  $product['prd_sc_qty'],
                    "cart_status" => "쇼핑",
                    "cart_price" => $product['prd_price'],
                    "cart_option" => $opt_value,
                    "cart_qty" => $cart_qty,
                    "opt_code" => $opt_code,
                    "opt_subject"=>$opt_subject,
                    "opt_type" => $opt_type,
                    "opt_price" => $opt_price,
                    "cart_regtime" => date('Y-m-d H:i:s'),
                    "cart_ip" => ip2long($this->input->ip_address()),
                    "cart_send_cost" => $cart_send_cost,
                    "cart_direct" => $is_direct ?'Y' :'N',
                    "cart_select" => $cart_select,
                    "cart_select_time" => $cart_select_time
                ];
                $ct_count++;
            }

            if($ct_count > 0) {
                $this->db->insert_batch('shop_cart', $insertArray);
            }
        }
    }

    /**
     * 장바구니에서 선택한 상품들을 구매하기 처리
     */
    function buyCart(bool $is_direct=FALSE, array $prd_idx_array=[])
    {
        $this->load->model('products_model');

        // 보관기간이 지난 상품 삭제
        $this->cartClean();

        $this->setCartId($is_direct);

        $tmp_cart_id = $this->session->userdata('ss_cart_'. ( $is_direct ? 'direct' : 'id' ) );

        // 브라우저에서 쿠키를 허용하지 않은 경우라고 볼 수 있음.
        if (!$tmp_cart_id)
        {
            throw new Exception('더 이상 작업을 진행할 수 없습니다.\n\n브라우저의 쿠키 허용을 사용하지 않음으로 설정한것 같습니다.\n\n브라우저의 인터넷 옵션에서 쿠키 허용을 사용으로 설정해 주십시오.\n\n그래도 진행이 되지 않는다면 쇼핑몰 운영자에게 문의 바랍니다.');;
        }

        $tmp_cart_id = preg_replace('/[^a-z0-9_\-]/i', '', $tmp_cart_id);

        if(! $is_direct) {
            if(count($prd_idx_array) <= 0) {
                throw new Exception("주문하실 상품을 하나 이상 선택해 주십시오.");
            }
        }


        // 선택필드 초기화
        $this->db
            ->where('od_id', $tmp_cart_id)
            ->set('cart_select', 'N')
            ->update('shop_cart');

        if($is_direct) {
            $prd_idx_array = [];

            $temp = $this->db
                ->select('prd_idx')
                ->where('od_id', $tmp_cart_id)
                ->where('cart_direct', 'Y')
                ->group_by('prd_idx')
                ->get('shop_cart')
                ->result_array();

            foreach($temp as $row) {
                $prd_idx_array[] = $row['prd_idx'];
            }
        }

        foreach($prd_idx_array as $prd_idx)
        {

            if(empty($prd_idx))
                continue;

            if(! $product = $this->products_model->getItem($prd_idx))
            {
                continue;
            }

            // 주문 상품의 재고체크
            $list = $this->db
                ->select('C.cart_qty, C.prd_name, C.cart_option, C.opt_code, C.opt_type, IFNULL(PO.opt_stock_qty,0) AS opt_stock')
                ->from('shop_cart AS C')
                ->join('products_options AS PO','PO.prd_idx=C.prd_idx AND PO.opt_code=C.opt_code', 'left')
                ->where('C.od_id', $tmp_cart_id)
                ->where('C.prd_idx', $prd_idx)
                ->get()
                ->result_array();

            foreach($list as $row)
            {
                $sum_qty = (int)$this->db
                    ->select_sum('cart_qty', 'sumval')
                    ->from('shop_cart')
                    ->where('od_id <>', $tmp_cart_id)
                    ->where('prd_idx', $prd_idx)
                    ->where('opt_code', $row['opt_code'])
                    ->where('opt_type', $row['opt_type'])
                    ->where('cart_use_stock', 'N')
                    ->where('cart_status','쇼핑')
                    ->where('cart_select','Y')
                    ->get()
                    ->row(0)
                    ->sumval;

                $ct_qty = $row['cart_qty'];

                if(!$row['opt_code']) {
                    $it_stock_qty = $this->products_model->getStockQty($product);
                }
                else
                {
                    $it_stock_qty = $this->products_model->getOptionStockQty($prd_idx, ["opt_code"=>$row['opt_code'], "opt_type"=>$row['opt_type'], "opt_stock_qty"=>$row['opt_stock']]);
                }

                if ($ct_qty + $sum_qty > $it_stock_qty)
                {
                    $item_option = $row['prd_name'];
                    if($row['opt_code'])
                        $item_option .= '('.$row['cart_option'].')';

                    throw new Exception($item_option." 의 재고수량이 부족합니다.\\n\\n현재 재고수량 : " . number_format($it_stock_qty - $sum_qty) . " 개");
                }
            }

            // cart_select 를 Y 로변경
            if(! $this->db
                ->where('od_id', $tmp_cart_id)
                ->where('prd_idx', $prd_idx)
                ->set('cart_select', 'Y')
                ->set('cart_select_time', date('Y-m-d H:i:s'))
                ->update('shop_cart'))
            {
                throw new Exception('주문서 작성도중 오류가 발생하였습니다');
            }
        }
    }
    
    /**
     * 장바구니를 불러오기전, 장바구니에 담겨진 금액을 업데이트 합니다.
     *
     * @param $s_cart_id
     * @param $is_ct_select_condition
     * @param $is_price_update
     * @return void
     */
    function updateCartPrice($s_cart_id, $is_ct_select_condition=false, $is_price_update=false)
    {
        if( !$s_cart_id ){
            return;
        }

        $this->db
            ->from('shop_cart')
            ->where('od_id', $s_cart_id);

        if( $is_ct_select_condition ){
            $this->db->where('cart_select','N');
        }
        $list = $this->db->get()->result_array();

        $check_need_update = false;

        foreach($list as $row)
        {
            // 상품 고유번호가 없다면 지나간다
            if(! $row['prd_idx']) continue;

            if(! $product = $this->products_model->getItem($row['prd_idx'])) {
                continue;
            }

            $update_querys = array();

            if($product['prd_price'] != $row['cart_price']) {
                $update_querys['cart_price'] = $product['prd_price'];
            }

            if($row['opt_code']) {
                $option_row = $this->db->where('prd_idx', $row['prd_idx'])->where('opt_code', $row['opt_code'])->get('products_options')->row_array();

                if($option_row['opt_type']) {
                    $this_io_type = $option_row['opt_type'];
                }
                if( $option_row['opt_code'] && $option_row['opt_add_price'] !== $row['opt_price'] ){
                    // 장바구니 테이블 옵션 가격과 상품 옵션테이블의 옵션 가격이 다를경우
                    $update_querys['opt_price'] =$option_row['opt_add_price'];
                }
            }

            if( $update_querys ){
                $check_need_update = true;
            }

            // 장바구니에 담긴 금액과 실제 상품 금액에 차이가 있고, $is_price_update 가 true 인 경우 장바구니 금액을 업데이트 합니다.
            if( $is_price_update && $update_querys ){
                $this->db->where('cart_id', $row['cart_id'])->update('shop_cart', $update_querys);
            }
        }

        // 장바구니에 담긴 금액과 실제 상품 금액에 차이가 있다면
        if( $check_need_update ){
            return false;
        }

        return true;
    }

    /**
     * 장바구니의 특정 상품에 대한 배송비를 계산하기
     * @param $it_id
     * @param $price
     * @param $qty
     * @param $cart_id
     * @return float|int|mixed
     */
    function getItemSendCost($prd_idx, $price, $qty, $cart_id)
    {
        $ct = $this->db
            ->select('prd_idx, cart_sc_type, cart_sc_method, cart_sc_price, cart_sc_minimum, cart_sc_qty')
            ->from('shop_cart')
            ->where('prd_idx', $prd_idx)
            ->where('od_id', $cart_id)
            ->order_by('cart_id')
            ->limit(1)
            ->get()
            ->row_array();

        if(!$ct['prd_idx']) return 0;

        $sendcost = 0;

        if($ct['cart_sc_type'] === '무료') {
            $sendcost = 0;
        }
        else if ($ct['cart_sc_type'] === '조건부무료' )
        {
            if($price >= $ct['it_sc_minimum'])
                $sendcost = 0;
            else
                $sendcost = $ct['cart_sc_price'];
        }
        else if ($ct['cart_sc_type'] === '유료')
        {
            $sendcost = $ct['cart_sc_price'];
        }
        else if ($ct['cart_sc_type'] === '수량별') {
            if(!$ct['cart_sc_type'])
                $ct['cart_sc_type'] = '유료';

            $q = ceil((int)$qty / (int)$ct['cart_sc_qty']);
            $sendcost = (int)$ct['cart_sc_price'] * $q;
        }
        else {
            $sendcost = -1;
        }

        return $sendcost;
    }

    /**
     * 장바구니 배송비 계산하기
     * @param $s_cart_id
     * @param string $selected
     * @return void
     */
    function getSendCost($cart_id, string $selected='Y')
    {
        $send_cost = 0;
        $total_price = 0;
        $total_send_cost = 0;
        $diff = 0;

        $list = $this->db
            ->distinct()
            ->select('prd_idx')
            ->from('shop_cart')
            ->where('od_id', $cart_id)
            ->where('cart_send_cost', '0')
            ->where_in('cart_status', ['쇼핑', '주문', '입금', '준비', '배송', '완료'])
            ->where('cart_select', $selected)
            ->get()
            ->result_array();

        foreach($list as $row)
        {
            $sum = $this->db
                ->select('SUM(IF(opt_type = "addition", (opt_price * cart_qty), ((cart_price + opt_price) * cart_qty))) as price', FALSE)
                ->select('SUM(cart_qty) as qty')
                ->from('shop_cart')
                ->where('prd_idx', $row['prd_idx'])
                ->where('od_id', $cart_id)
                ->where_in('cart_status', ['쇼핑', '주문', '입금', '준비', '배송', '완료'])
                ->where('cart_select', $selected)
                ->get()
                ->row_array();

            $send_cost = $this->getItemSendCost($row['prd_idx'], $sum['price'], $sum['qty'], $cart_id);

            if($send_cost > 0)
                $total_send_cost += $send_cost;


            if($this->site->config('shop_delivery_type') == '차등' && $send_cost == -1) {
                $total_price += $sum['price'];
                $diff++;
            }
        }

        $send_cost = 0;
        if($this->site->config('shop_delivery_type') == '차등' && $total_price >= 0 && $diff > 0) {
            // 금액별차등 : 여러단계의 배송비 적용 가능
            $temp = json_decode($this->site->config('shop_delivery_cost'), TRUE);
            $send_cost = 0;

            for($k=0; $k<count($temp); $k++) {
                // 총 판매금액이 배송비 상한가 보다 작으면
                if($total_price < $temp[$k]['price']) {
                    $send_cost = $temp[$k]['sc_cost'];
                    break;
                }
            }
        }

        return ($total_send_cost + $send_cost);
    }

    function getCartOptions($prd_idx, $cart_id)
    {
        $result = $this->db
            ->select('cart_option, cart_qty, opt_price, opt_type')
            ->from('shop_cart')
            ->where('prd_idx', $prd_idx)
            ->where('od_id', $cart_id)
            ->order_by('opt_type DESC, cart_id ASC')
            ->get()
            ->result_array();

        return $result;
    }


    /**
     * 내가 상품의 리뷰를 쓸 권한이 있는지 체크한다.
     * @param $prd_idx
     */
    function checkReviewAuth($prd_idx)
    {
        // 내가 리뷰를 쓸 자격이 있는지 확인한다.
        $review_auth = FALSE;
        $mem_idx = $this->member->is_login();
        // 로그인이 되어있어야 하고
        if( $mem_idx )
        {
            // 구매이력이 있어야 하고 리뷰를 작성한적 없어야 한다.
            $cnt = (int)$this->db
                ->select('COUNT(*) AS cnt')
                ->from('shop_cart AS SC')
                ->join('products_review AS PR','PR.od_id=SC.od_id AND PR.prd_idx=SC.prd_idx AND PR.mem_idx=SC.mem_idx', 'left')
                ->where('SC.prd_idx', $prd_idx)
                ->where('SC.mem_idx', $mem_idx)
                ->where_in('SC.cart_status', ['완료','배송'])
                ->where('PR.od_id',NULL)
                ->get()
                ->row(0)
                ->cnt;

            if($cnt > 0) {
                $review_auth = TRUE;
            }
        }

        return $review_auth;
    }

    /**
     * 상품의 리뷰를 가져온다.
     *
     * @param $param
     */
    function getProductReviews($param)
    {
        $param['prd_idx'] = element('prd_idx', $param, 0);
        $param['page'] = element('page', $param, 1);
        $param['page_rows'] = element('page_rows', $param, 5);
        $param['sort_type'] = element('sort_type', $param, 'score');
        $param['score_filter'] = element('score_filter', $param, '');
        $param['mem_idx'] = element('mem_idx', $param, '');
        $param['load_images'] = element('load_images', $param, FALSE);
        $param['view_hidden'] = element('view_hidden', $param, FALSE);

        $start = ($param['page'] - 1) * $param['page_rows'];
        $table_prefix = $this->db->dbprefix;
        $join_query = 'SELECT od_id,prd_name, GROUP_CONCAT(cart_option SEPARATOR "'.SEPERATE_CHARSET.'") AS buy_option FROM '.$table_prefix.'shop_cart WHERE cart_status IN ("완료","배송") GROUP BY od_id';

        $this->db
            ->select("SQL_CALC_FOUND_ROWS R.*, O.prd_name, O.buy_option, IFNULL(M.mem_nickname, '') AS nickname", FALSE)
            ->select('IFNULL(PA.att_filepath, "") AS thumbnail')
            ->from('products_review AS R')
            ->join("({$join_query}) AS O",'O.od_id=R.od_id','left')
            ->join('member AS M','M.mem_idx=R.mem_idx','left')
            ->join('products AS P','P.prd_idx=R.prd_idx','left')
            ->join('attach AS PA','PA.att_idx=P.prd_thumbnail','left')
            ->limit($param['page_rows'], $start);

        if($param['view_hidden']) {
            $this->db->where_in('R.rev_status', ['Y','H']);
        } else {
            $this->db->where('R.rev_status', 'Y');
        }

        if(! empty($param['prd_idx'])) {
            $this->db->where('R.prd_idx', $param['prd_idx']);
        }
        if(! empty($param['mem_idx'])) {
            $this->db->where('R.mem_idx', $param['mem_idx']);
        }

        if($param['sort_type'] === 'score') {
            $this->db->order_by('R.rev_score DESC, R.rev_idx DESC');
        } else if($param['sort_type'] === 'regtime') {
            $this->db->order_by('R.rev_idx DESC');
        } else {
            $this->db->order_by('R.rev_score DESC, R.rev_idx DESC');
        }

        if(!empty($param['score_filter']))
        {
            $this->db->where('FLOOR(R.rev_score)', $param['score_filter']);
        }


        $result = $this->db->get();
        $return['list'] = $result->result_array();
        $return['totalCount'] = (int)$this->db->query('SELECT FOUND_ROWS() AS cnt')->row(0)->cnt;

        foreach($return['list'] as &$row)
        {
            $buy_option = [];

            if(!empty($row['buy_option'])) {
                $buy_option = explode(SEPERATE_CHARSET, $row['buy_option']);
            }
            $row['buy_option'] = $buy_option;
            $row['images'] = [];

            if($param['load_images']) {
                $row['images'] = $this->db
                    ->select('att_filepath')
                    ->where('att_target_type','PRODUCTS_REVIEW')
                    ->where('att_target', $row['rev_idx'])
                    ->where('att_is_image', 'Y')
                    ->get('attach')
                    ->result_array();
            }
        }

        return $return;
    }

    /**
     * 상품의 리뷰를 가져온다.
     *
     * @param $param
     */
    function getProductQna($param)
    {
        $param['prd_idx'] = element('prd_idx', $param, 0);
        $param['page'] = element('page', $param, 1);
        $param['page_rows'] = element('page_rows', $param, 5);
        $param['mem_idx'] = element('mem_idx', $param, '');

        $start = ($param['page'] - 1) * $param['page_rows'];

        $this->db
            ->select("SQL_CALC_FOUND_ROWS Q.*, P.prd_name, IFNULL(M.mem_nickname, '') AS nickname,, IFNULL(M2.mem_nickname, '') AS a_nickname", FALSE)
            ->select('IFNULL(PA.att_filepath, "") AS thumbnail')
            ->from('products_qa AS Q')
            ->join("products AS P",'P.prd_idx=Q.prd_idx','left')
            ->join('attach AS PA','PA.att_idx=P.prd_thumbnail','left')
            ->join('member AS M','M.mem_idx=Q.mem_idx','left')
            ->join('member AS M2','M2.mem_idx=Q.qa_a_mem_idx','left')
            ->where('Q.qa_status','Y')
            ->order_by('Q.qa_idx DESC')
            ->limit($param['page_rows'], $start);

        if(! empty($param['prd_idx'])) {
            $this->db->where('Q.prd_idx', $param['prd_idx']);
        }
        if(! empty($param['mem_idx'])) {
            $this->db->where('Q.mem_idx', $param['mem_idx']);
        }

        $mem_idx = $this->member->is_login();

        $result = $this->db->get();
        $return['list'] = $result->result_array();
        $return['totalCount'] = (int)$this->db->query('SELECT FOUND_ROWS() AS cnt')->row(0)->cnt;

        foreach($return['list'] as &$row)
        {
            // 비밀 문의일경우
            // 관리자페이지이거나, 작성자가 아니면 비밀글로 처리
            if($row['qa_secret'] === 'Y' && !PAGE_ADMIN && $row['mem_idx'] != $mem_idx ) {
                $row['qa_content'] = "";
                $row['qa_answer_content'] = "";
                $row['is_secret'] = TRUE;
            } else {
                $row['is_secret'] = FALSE;
            }
        }

        return $return;
    }


    /**
     * 내 주문내역중 리뷰를 작성하지 않은 상품 목록을 가져온다.
     * @param $prd_idx
     */
    function getNoReviewOrders($prd_idx, $od_id="")
    {
        $mem_idx = $this->member->is_login();

        if(! $mem_idx) {
            return [];
        }
        $table_prefix = $this->db->dbprefix;
        $join_query = 'SELECT `od_id`,`prd_name`,`mem_idx`,`prd_idx`, GROUP_CONCAT(cart_option SEPARATOR "'.SEPERATE_CHARSET.'") AS buy_option FROM '.$table_prefix.'shop_cart WHERE cart_status IN ("완료","배송") AND prd_idx = '.$prd_idx.' GROUP BY od_id';

        if(! empty($rev_idx)) {
            $this->db->group_start();
                $this->db->where('SC.od_id', $od_id);
                $this->db->or_where('R.od_id IS NULL',NULL, FALSE);
            $this->db->group_end();
        } else {
            $this->db->where('R.od_id IS NULL',NULL, FALSE);
        }

        $list = $this->db
            ->select("SC.od_id, SC.prd_name, SC.mem_idx, SC.prd_idx")
            ->select('GROUP_CONCAT(cart_option SEPARATOR "'.SEPERATE_CHARSET.'") AS buy_option', FALSE)
            ->from('shop_cart AS SC')
            ->join('products_review AS R','SC.od_id=R.od_id AND SC.prd_idx=R.prd_idx AND SC.mem_idx=R.mem_idx AND R.rev_status = "Y"','left')
            ->where('SC.prd_idx', $prd_idx)
            ->where('SC.mem_idx', $mem_idx)
            ->group_by('SC.od_id')
            ->get()
            ->result_array();

        foreach($list as &$row)
        {
            $buy_option = [];

            if(!empty($row['buy_option'])) {
                $buy_option = explode(SEPERATE_CHARSET, $row['buy_option']);
            }
            $row['buy_option'] = $buy_option;
        }


        return $list;
    }

    /**
     * 진열장에서 상품 진열 목록을 가져옵니다.
     * @return void
     */
    function getDisplayList($dsp_key, $dsp_idx=0)
    {
        if(empty($dsp_idx)) {
            if(! $tmp = $this->db->where('dsp_key', $dsp_key)->get('products_display')->row_array())
            {
                return [];
            }

            $dsp_idx = $tmp['dsp_idx'];
        }

        $this->db
            ->select('SQL_CALC_FOUND_ROWS P.*, IFNULL(A.att_filepath, "") AS prd_thumbnail_path, PCL.parent_names, PCL.cat_title', FALSE)
            ->from('products AS P')
            ->join('products_category_list AS PCL','PCL.cat_id=P.cat_id','left')
            ->join('products_display_items AS PDI',"PDI.dsp_idx='{$dsp_idx}' AND PDI.prd_idx=P.prd_idx", 'left')
            ->join('attach AS A','A.att_idx=P.prd_thumbnail', 'left')
            ->where('P.prd_status','Y')
            ->where('PDI.dsp_idx IS NOT NULL',NULL, FALSE)
            ->order_by('PDI.dspi_sort ASC, P.prd_idx DESC');

        $result = $this->db->get();

        return  $result->result_array();;
    }

    function stock_change($will_status, $cart_row)
    {
        // 배송으로 교체하거나, 취소, 반품, 품절로 교체할때 처리한다.
        if($will_status=='배송' ||  $will_status=='반품')
        {
            if($will_status == '배송')
            {
                // 주문->배송, 준비->배송, 입금->배송 이 아닌경우 리턴
                if($cart_row['cart_status'] !='주문' && $cart_row['cart_status'] !='준비' && $cart_row['cart_status'] !='입금')
                {
                    return;
                }

                // 일단 상품의 재고를 깎는다
                $this->db
                    ->where('prd_idx', $cart_row['prd_idx'])
                    ->set("`prd_stock_qty`", "`prd_stock_qty`-{$cart_row['cart_qty']}", FALSE)
                    ->update('products');

                // 상품 옵션이 있는경우 옵션의 재고를 깎는다.
                if(! empty($cart_row['opt_code'])) {
                    $this->db
                        ->where('prd_idx', $cart_row['prd_idx'])
                        ->where('opt_code', $cart_row['opt_code'])
                        ->set("`opt_stock_qty`", "`opt_stock_qty`-{$cart_row['cart_qty']}", FALSE)
                        ->update('products_options');
                }

                $this->db->where('cart_id', $cart_row['cart_id'])->set('cart_use_stock','Y');
            }
            else if($will_status == '반품')
            {
                // 배송->반품,완료->빤품 이 아닌경우 리턴
                if($cart_row['cart_status'] !='배송' && $cart_row['cart_status'] !='완료')
                {
                    return;
                }

                // 일단 상품의 재고를 올린다.
                $this->db
                    ->where('prd_idx', $cart_row['prd_idx'])
                    ->set("`prd_stock_qty`", "`prd_stock_qty`+{$cart_row['cart_qty']}", FALSE)
                    ->update('products');

                // 상품 옵션이 있는경우 옵션의 재고를 올린다..
                if(! empty($cart_row['opt_code'])) {
                    $this->db
                        ->where('prd_idx', $cart_row['prd_idx'])
                        ->where('opt_code', $cart_row['opt_code'])
                        ->set("`opt_stock_qty`", "`opt_stock_qty`+{$cart_row['cart_qty']}", FALSE)
                        ->update('products_options');
                }
                $this->db->where('cart_id', $cart_row['cart_id'])->set('cart_use_stock','N');
            }
        }
    }
}