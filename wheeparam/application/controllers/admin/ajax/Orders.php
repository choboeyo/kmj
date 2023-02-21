<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

/**
 * @property Products_model $products_model
 * @property Shop_model $shop_model
 * @property Import_model $import_model
 */
class Orders extends REST_Controller
{
    /**
     * 주문서 정보 저장
     * @param $od_id
     */
    function index_post($od_id)
    {
        $data['od_name'] = trim($this->input->post('od_name', TRUE));
        $data['od_hp'] = trim($this->input->post('od_hp', TRUE));
        $data['od_tel'] = trim($this->input->post('od_tel', TRUE));
        $data['od_email'] = trim($this->input->post('od_email', TRUE));
        $data['od_zonecode'] = trim($this->input->post('od_zonecode', TRUE));
        $data['od_addr1'] = trim($this->input->post('od_addr1', TRUE));
        $data['od_addr2'] = trim($this->input->post('od_addr2', TRUE));
        $data['od_shop_memo'] = trim($this->input->post('od_shop_memo', TRUE));
        $data['od_cart_price'] = trim($this->input->post('od_cart_price', TRUE));
        $data['od_send_cost'] = trim($this->input->post('od_send_cost', TRUE));
        $data['od_refund_price'] = trim($this->input->post('od_refund_price', TRUE));
        $data['od_cancel_price'] = trim($this->input->post('od_cancel_price', TRUE));
        $data['od_misu'] = trim($this->input->post('od_misu', TRUE));
        $data['od_receipt_price'] = trim($this->input->post('od_receipt_price', TRUE));
        $data['od_delivery_company'] = trim($this->input->post('od_delivery_company', TRUE));
        $data['od_delivery_num'] = trim($this->input->post('od_delivery_num', TRUE));
        $data['od_status'] = $this->input->post('od_status', TRUE);

        $this->db->where('od_id', $od_id)->update('shop_order', $data);

        // 카트의 상태도 동시에 변경한다, 단 카트 상품이 취소,반품,품절인건 변경하지않는다.
        $this->db
            ->where('od_id', $od_id)
            ->where_not_in('cart_status',['취소','반품','품절'])
            ->set('cart_status', $data['od_status'])
            ->update('shop_cart');
    }

    /**
     * 사용자에게 안내문자 발송
     */
    function send_sms_post()
    {
        $idxs = $this->post('idxs', TRUE);
        $type = $this->post('type', TRUE);

        if(count($idxs) == 0) {
            $this->response(["문자를 발송할 주문을 먼저 선택하세요"], 400);
        }

        if(!in_array($type, ['oc','ip','ic','sc'])) {
            $this->response(["문자 발송 타입이 잘못되었습니다."], 400);
        }

        $column = "";
        switch ($type) {
            case "oc":
                $column = "shop_sms_order_complete";
                break;
            case "ip":
                $column = "shop_sms_bank_info";
                break;
            case "ic":
                $column = "shop_sms_pay_complete";
                break;
            case "sc":
                $column = "shop_sms_delivery";
                break;
        }

        // 문자 내용을 가져온다.
        $content = $this->site->config($column.'_cc');
        $code =  $this->site->config($column.'_c');

        $this->load->library('ncloud');

        // 주문서 목록을 가져온다.
        $list = $this->db
            ->where_in('od_id', $idxs)
            ->get('shop_order')
            ->result_array();

        // 각 주문서별로 처리한다.
        $update_array = [];
        foreach($list as $order)
        {
            $sms_data['phone'] = $order['od_hp'];
            $sms_data['content'] = $content;
            $sms_data['code'] =  $code;
            
            // 필요한 단어들 대체
            $sms_data['content'] = str_replace("#{주문번호}", $order['od_id'], $sms_data['content']);
            $sms_data['content'] = str_replace("#{주문자}", $order['od_name'], $sms_data['content']);
            $sms_data['content'] = str_replace("#{주문금액}", number_format($order['od_receipt_price']), $sms_data['content']);
            $sms_data['content'] = str_replace("#{주문상품}", $order['od_title'], $sms_data['content']);
            $sms_data['content'] = str_replace("#{계좌번호}", $this->site->config('shop_bank_account'), $sms_data['content']);
            $sms_data['content'] = str_replace("#{택배사}", $order['od_delivery_company'], $sms_data['content']);
            $sms_data['content'] = str_replace("#{운송장번호}", $order['od_delivery_num'], $sms_data['content']);

            try {
                if(! empty($sms_data['content'])){
                    $this->ncloud->send($sms_data);
                }

                $update_array[] = [
                    "od_id" => $order['od_id'],
                    "od_{$type}_send" => "Y",
                    "od_{$type}_datetime" => date('Y-m-d H:i:s')
                ];
            }
            catch (Exception $e) {
            }
        }

        if(count($update_array) >0) {
            $this->db->update_batch('shop_order', $update_array, 'od_id');
        }
    }

    /**
     * 주문서 상세보기 주문서 정보 가져오기
     * @param $od_id
     */
    function view_get($od_id)
    {
        $row = $this->db->where('od_id', $od_id)->get('shop_order')->row_array();

        $row['od_cancel_price'] *=1;
        $row['od_cart_count'] *=1;
        $row['od_cart_price'] *=1;
        $row['od_misu'] *=1;
        $row['od_receipt_price'] *=1;
        $row['od_refund_price'] *=1;
        $row['od_send_cost'] *=1;
        $row['od_paid_price'] = $row['od_receipt_price'] - $row['od_refund_price'] - $row['od_cancel_price'] - $row['od_misu'];

        $this->response($row, 200);
    }

    /**
     * PG사 결제정보 가져오기
     * @param $imp_uid
     */
    function import_get($imp_uid)
    {
        $this->load->model('import_model');

        $row = $this->import_model->getPaymentData($imp_uid);

        $this->response($row);
    }

    /**
     * PG사 결제 취소
     * @param $imp_uid
     */
    function import_delete($imp_uid)
    {
        $this->load->model('import_model');
        $merchant_uid = str_replace(",","", $this->delete('merchant_uid', TRUE));
        $amount = str_replace(",","", $this->delete('amount', TRUE));
        $reason = trim( $this->delete('reason', TRUE));

        if(empty($merchant_uid)) {
            $this->response(["message"=>"잘못된 접근입니다."], 400);
        }
        if(empty($amount)) {
            $this->response(["message"=>"취소할 금액을 정확하게 입력하세요"], 400);
        }
        if(empty($reason)) {
            $this->response(["message"=>"취소 사유를 입력하세요"], 400);
        }


        $this->import_model->CancelPayment($imp_uid, $merchant_uid, $reason, $amount);

        $this->response([],200);
    }

    /**
     * 주문서 상세보기에서 주문 상세 품목들 가져오기
     * @param $od_id
     */
    function items_get($od_id)
    {
        $list = $this->db->where('od_id', $od_id)->order_by('cart_id')->get('shop_cart')->result_array();

        foreach($list as &$row) {
            $row['cart_qty'] *= 1;
            $row['cart_price'] *= 1;
            $row['cart_send_cost'] *= 1;
        }

        $this->response($list, 200);
    }

    /**
     * 주문서 상세보기에서 주문서 상세 품목들 상태저장
     */
    function items_post()
    {
        $cart_list = $this->post('cartList', TRUE);

        $update_array = [];
        foreach($cart_list as $row)
        {
            $update_array[] = [
              "cart_id" => $row['cart_id'],
              "cart_status" => $row['cart_status'],
              "cart_qty" => $row['cart_qty']
            ];
        }

        if(count($update_array) > 0) {
            $this->db->update_batch('shop_cart', $update_array, 'cart_id');
        }
    }
}