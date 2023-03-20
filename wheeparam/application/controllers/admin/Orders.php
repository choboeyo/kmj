<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 주문 관련 메뉴
 *
 * @property Products_model $products_model
 */
class Orders extends WB_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->theme = "admin";

        $this->load->model('products_model');
        $this->load->model('shop_model');
    }

    public function order_items($od_id)
    {
        $_temp = $this->shop_model->getCartListByOrder($od_id);

        $this->data['list'] = $_temp['list'];

        $this->theme_file = 'blank';
        $this->view = "orders/order_items";
    }

    public function multi_save()
    {
        $this->load->model('shop_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('reurl', '리다이렉트','trim');

        if($this->form_validation->run() != FALSE)
        {
            $od_delivery_num = $this->input->post('od_delivery_num', TRUE);
            $od_status = $this->input->post('od_status', TRUE);
            $od_delivery_company = $this->input->post('od_delivery_company', TRUE);

            $temp = [];

            $k_array = ["od_delivery_num","od_status","od_delivery_company"];
            foreach($k_array as $field) {
                foreach($$field as $od_id=>$value) {
                    if(! isset($temp[$od_id])) {
                        $temp[$od_id] = ["od_id"=> $od_id];
                    }

                    $temp[$od_id][$field] = $value;
                }
            }

            $update_array = [];
            $update2_array = [];

            foreach($temp as $row) {
                $update_array[] = $row;

                $cart = $this->db->where('od_id', $row['od_id'])->get('shop_cart')->result_array();
                foreach($cart as $cart_row) {
                    if(!in_array($cart_row['cart_status'], ['취소','반품','품절'])) {
                        $update2_array[] = [
                            "cart_status" => $row['od_status'],
                            "cart_id" => $cart_row['cart_id']
                        ];
                    }
                    $this->shop_model
                        ->stock_change($row['od_status'], $cart_row);
                }
            }


            $this->db->trans_begin();

            if(count($update_array) > 0) {
                $this->db->update_batch('shop_order', $update_array, "od_id");
            }
            if(count($update2_array) > 0) {
                $this->db->update_batch('shop_cart', $update2_array, "cart_id");
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                alert('배송정보가 저장에 실패하였습니다.');
                exit;
            }
            else
            {
                $this->db->trans_commit();

                alert('배송정보가 저장되었습니다.', $this->input->post('reurl', TRUE));
            }

        }
        else {
            echo 'Not allow direct access';
        }
    }

    public function index()
    {
        $this->data['od_status'] = $this->input->get('od_status', TRUE, '');
        $this->data['od_settle_case'] = $this->input->get('od_settle_case', TRUE, '');
        $this->data['is_misu'] = $this->input->get('is_misu', TRUE, '');
        $this->data['startdate'] = $this->input->get('startdate', TRUE, '');
        $this->data['enddate'] = $this->input->get('enddate', TRUE, '');
        $this->data['query'] = $this->input->get('query', TRUE, '');

        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['page_rows'] = 10;
        $start = ($this->data['page'] - 1) * $this->data['page_rows'];

        $this->db
            ->select("SQL_CALC_FOUND_ROWS *",FALSE)
            ->from('shop_order')
            ->where('od_status <>', '')
            ->order_by('od_id DESC')
            ->limit($this->data['page_rows'], $start);

        if(! empty($this->data['startdate'])) $this->db->where('od_receipt_time >=', $this->data['startdate']." 00:00:00");
        if(! empty($this->data['enddate'])) $this->db->where('od_receipt_time <=', $this->data['enddate']." 23:59:59");
        if(! empty($this->data['od_status'])) $this->db->where('od_status', $this->data['od_status']);
        if(! empty($this->data['od_settle_case'])) $this->db->where('od_settle_case', $this->data['od_settle_case']);
        if($this->data['is_misu']=='Y') $this->db->where('od_misu >', 0);
        if(! empty($this->data['query'])) {
            $this->db->group_start();
            $this->db->where('od_name', $this->data['query']);
            $this->db->or_like('od_hp', $this->data['query']);
            $this->db->or_like('od_tel', $this->data['query']);
            $this->db->where('od_id', $this->data['query']);
            $this->db->group_end();
        }

        $result = $this->db->get();
        $this->data['list'] = $result->result_array();
        $this->data['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;

        foreach($this->data['list'] as &$row) {
            $row['od_num'] = substr($row['od_id'], 0, 8) . '-' . substr($row['od_id'],8);
        }

        // 페이지네이션 세팅
        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['page_rows'];
        $paging['total_rows'] = $this->data['totalCount'];

        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();


        $this->view = $this->active="orders/index";
    }

    public function ranks()
    {
        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['page_rows'] = $this->input->get('page_rows', TRUE, 50);

        $this->data['list'] = $this->db
            ->select('SQL_CALC_FOUND_ROWS P.prd_idx, P.prd_name,P.prd_sell_count, PCL.parent_names, PCL.cat_title', FALSE)
            ->from('products AS P')
            ->join('products_category_list AS PCL','PCL.cat_id=P.cat_id','left')
            ->where('P.prd_status','Y')
            ->where('P.prd_sell_count >', 0)
            ->order_by('prd_sell_count DESC')
            ->limit($this->data['page_rows'], ($this->data['page'] - 1) * $this->data['page_rows'])
            ->get()
            ->result_array();

        foreach($this->data['list'] as $i=>&$row) {
            $row['num'] = (($this->data['page'] - 1) * $this->data['page_rows']) + $i + 1;
        }

        $this->data['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;
        // 페이지네이션 세팅
        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['page_rows'];
        $paging['total_rows'] = $this->data['totalCount'];

        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->active = $this->view = "orders/rank";
    }

    public function wish()
    {
        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['page_rows'] = $this->input->get('page_rows', TRUE, 50);

        $this->data['list'] = $this->db
            ->select('SQL_CALC_FOUND_ROWS P.prd_idx, P.prd_name,P.prd_wish_count, PCL.parent_names, PCL.cat_title', FALSE)
            ->from('products AS P')
            ->join('products_category_list AS PCL','PCL.cat_id=P.cat_id','left')
            ->where('P.prd_status','Y')
            ->where('P.prd_wish_count > ','0')
            ->order_by('prd_wish_count DESC')
            ->limit($this->data['page_rows'], ($this->data['page'] - 1) * $this->data['page_rows'])
            ->get()
            ->result_array();

        foreach($this->data['list'] as $i=>&$row) {
            $row['num'] = (($this->data['page'] - 1) * $this->data['page_rows']) + $i + 1;
        }

        $this->data['totalCount'] = (int)$this->db->query("SELECT FOUND_ROWS() AS cnt")->row(0)->cnt;
        // 페이지네이션 세팅
        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['page_rows'];
        $paging['total_rows'] = $this->data['totalCount'];

        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        $this->active = $this->view = "orders/wish";
    }

    public function view($od_id)
    {
        $this->data['od_id'] = $od_id;
        $this->site->add_js('/assets/js/vue' . ( IS_TEST ? '' :'.min') . '.js');
        $this->active = "orders/index";
        $this->view = "orders/view";
    }
}