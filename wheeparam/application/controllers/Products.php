<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 상품관련 페이지
 *
 * @property Products_model $products_model
 * @property Shop_model $shop_model
 */
class Products extends WB_Controller
{
    /**
     * 생성자
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('products_model');
    }

    /**
     * 상품 상세보기 페이지
     * @param $prd_idx
     */
    function items($prd_idx)
    {
        $this->data['view'] = $this->products_model->getItem($prd_idx);

        // 만약 상품의 표시상태가 표시중이 아니라면
        if($this->data['view']['prd_status'] !== 'Y' && ( $this->input->get('preview', TRUE) !== 1 )) {
            alert('존재하지 않는 상품이거나, 삭제된 상품입니다.');
            exit;
        }

        // 상품의 조회수를 올린다.
        if( ! $this->session->userdata('products_'.$prd_idx) OR (int)$this->session->userdata('products_'.$prd_idx) + 60*60*24 < time() )
        {
            $this->db->where('prd_idx', $prd_idx)->set('prd_hit', 'prd_hit+1', FALSE)->update('products');
            $this->data['view']['prd_hit'] += 1;
            $this->session->set_userdata('products_'.$prd_idx, time());
        }

        // 찜여부를 가져온다.
        $mem_idx=$this->member->is_login();
        if($mem_idx)
        {
            $cnt =(int)$this->db
                ->select('COUNT(*) AS cnt')
                ->where('prd_idx', $prd_idx)
                ->where('mem_idx',$mem_idx)
                ->get('products_wish')
                ->row(0)
                ->cnt;
        }
        else {
            $cnt = 0;
        }

        $this->data['view']['is_wish'] = $cnt > 0;



        // 상품구매를 위한 폼을 만든다.
        $hiddenVars = [];
        $attrs =[];

        $attrs['data-form'] = "product-cart";
        $attrs['data-wrap'] = "quick-cart";

        $item_ct_qty = 1;
        if($this->data['view']['prd_buy_min_qty'] > 1) {
            $item_ct_qty = $this->data['view']['prd_buy_min_qty'];
        }

        $hiddenVars['prd_idx[]'] = $prd_idx;
        $hiddenVars['prd_name[]'] = $this->data['view']['prd_name'];
        $hiddenVars['prd_price[]'] = $this->data['view']['prd_price'];
        $hiddenVars['prd_stock[]'] = $this->products_model->getStockQty($this->data['view']);
        $hiddenVars["is_direct"] = 'N';
        $this->data['options'] = $this->products_model->getOptionArray($this->data['view']);

        $this->data['form_open'] = form_open(NULL, $attrs, $hiddenVars);
        $this->data['form_close'] = form_close();

        $this->theme = $this->site->get_layout();
        $this->skin = $this->site->config('skin_shop'.($this->site->viewmode===DEVICE_MOBILE?'_m':''));
        $this->skin_type = "shop";
        $this->view = "item";
    }

    /**
     * 상품의 리뷰 불러오기
     *
     * @param $prd_idx
     * @param int $page
     */
    function reviews($prd_idx)
    {
        $this->data['sort_type'] = $this->input->get('sort_type', TRUE, 'score');
        $this->data['score_filter'] = $this->input->get('score_filter', TRUE, '');

        // 별점별 개수 구해오기
        $this->data['score_list'] = ["5"=>0,"4"=>0, "3"=>0,"2"=>0,"1"=>0];
        $score_list = $this->db
            ->select('FLOOR(rev_score) AS score, COUNT(*) AS cnt')
            ->from('products_review')
            ->where('prd_idx', $prd_idx)
            ->where('rev_status','Y')
            ->group_by('FLOOR(rev_score)')
            ->order_by('rev_score DESC')
            ->get()
            ->result_array();

        foreach($score_list as $row) {
            if(isset($this->data['score_list'][$row['score']])) {
                $this->data['score_list'][$row['score']] = $row['cnt'] * 1;
            }
        }

        // 내가 리뷰를 쓸 자격이 있는지 확인한다.
        $this->load->model('shop_model');
        $this->data['review_auth'] = $this->shop_model->checkReviewAuth($prd_idx);

        // 리뷰 목록을 불러온다.
        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['page_rows'] = 5;
        $this->data['prd_idx'] = $prd_idx;
        $this->data['load_images'] = TRUE;

        $result = $this->shop_model->getProductReviews($this->data);
        $this->data['list'] = $result['list'];
        $this->data['totalCount'] = $result['totalCount'];

        foreach($this->data['list'] as $row)
        {
            if($row['reg_datetime'] != $row['upd_datetime']) {
                $row['rev_content'] .= PHP_EOL.PHP_EOL."({$row['upd_datetime']}) 수정됨";
            }
        }

        // 페이지네이션 세팅
        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['page_rows'];
        $paging['total_rows'] = $this->data['totalCount'];

        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        // 목록스킨을 정의한다.
        $suffix = $this->site->viewmode === DEVICE_MOBILE ? '_m' : '';
        $skin = $this->site->config('skin_shop'.$suffix);

        $this->theme = FALSE;
        $this->skin = $skin;
        $this->skin_type = "shop";
        $this->view = "item.review.php";
    }

    /**
     * 상품 리뷰 작성하기
     * @param $prd_idx
     */
    function reviews_write($prd_idx, $rev_idx="")
    {
        $this->load->model('shop_model');

        $this->site->add_js('https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js');
        $this->site->add_css('https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css');

        if(empty($rev_idx)) {
            $reviewAuth = $this->shop_model->checkReviewAuth($prd_idx);

            if(! $reviewAuth) {
                error_response("상품을 구매하신 분만 리뷰를 작성할 수 있습니다.", 400);
                return;
            }

        }


        $this->data['view'] = [];
        $mem_idx = $this->member->is_login();

        // 수정일 경우
        if(! empty($rev_idx)) {
            if(! $this->data['view'] = $this->db
                ->where('rev_idx', $rev_idx)
                ->where('prd_idx', $prd_idx)
                ->get('products_review')
                ->row_array())
            {
                error_response("수정하는 리뷰를 찾을수 없습니다. 이미 삭제되었을 수 있습니다.", 400);
                return;
            }

            if($this->data['view'] != 'Y') {
               // error_response("수정하는 리뷰를 찾을수 없습니다. 이미 삭제되었을 수 있습니다.", 400);
               // return;
            }

            if($this->data['view']['mem_idx'] != $mem_idx) {
                error_response("해당 리뷰를 수정할 권한이 없습니다.", 400);
                return;
            }

            $this->data['view']['images'] = $this->db
                ->where('att_target_type','PRODUCTS_REVIEW')
                ->where('att_target', $this->data['view']['rev_idx'])
                ->get('attach')
                ->result_array();

            $hiddenVars['rev_idx'] = $this->data['view']['rev_idx'];
        }

        $this->data['prd_idx'] = $prd_idx;
        $this->data['order_list'] = $this->shop_model->getNoReviewOrders($prd_idx, element('od_id', $this->data['view'], ''));

        // 목록스킨을 정의한다.
        $suffix = $this->site->viewmode === DEVICE_MOBILE ? '_m' : '';
        $skin = $this->site->config('skin_shop'.$suffix);

        $hiddenVars['prd_idx'] = $prd_idx;
        $this->data['form_open'] = form_open(NULL, ['data-form'=>"item-review-write"], $hiddenVars);
        $this->data['form_close'] = form_close();




        $this->theme = FALSE;
        $this->skin = $skin;
        $this->skin_type = "shop";
        $this->view = "item.review.write.php";
    }

    /**
     * 상품의 문의 내용 불러오기
     *
     * @param $prd_idx
     * @param int $page
     */
    function qna($prd_idx)
    {
        // 내가 리뷰를 쓸 자격이 있는지 확인한다.
        $this->load->model('shop_model');

        // 리뷰 목록을 불러온다.
        $this->data['page'] = $this->input->get('page', TRUE, 1);
        $this->data['page_rows'] = 5;
        $this->data['prd_idx'] = $prd_idx;
        $this->data['load_images'] = TRUE;

        $result = $this->shop_model->getProductQna($this->data);
        $this->data['list'] = $result['list'];
        $this->data['totalCount'] = $result['totalCount'];

        // 페이지네이션 세팅
        $paging['page'] = $this->data['page'];
        $paging['page_rows'] = $this->data['page_rows'];
        $paging['total_rows'] = $this->data['totalCount'];

        $this->load->library('paging', $paging);
        $this->data['pagination'] = $this->paging->create();

        // 목록스킨을 정의한다.
        $suffix = $this->site->viewmode === DEVICE_MOBILE ? '_m' : '';
        $skin = $this->site->config('skin_shop'.$suffix);

        $this->theme = FALSE;
        $this->skin = $skin;
        $this->skin_type = "shop";
        $this->view = "item.qna.php";
    }

    /**
     * 상품 문의 작성하기
     * @param $prd_idx
     */
    function qna_write($prd_idx)
    {
        $this->load->model('shop_model');

        if(! $this->member->is_login()) {
            error_response("상품 문의는 회원만 작성할 수 있습니다.", 400);
            return;
        }
        $this->data['prd_idx'] = $prd_idx;

        // 목록스킨을 정의한다.
        $suffix = $this->site->viewmode === DEVICE_MOBILE ? '_m' : '';
        $skin = $this->site->config('skin_shop'.$suffix);

        $hiddenVars['prd_idx'] = $prd_idx;
        $this->data['form_open'] = form_open(NULL, ['data-form'=>"item-qna-write"], $hiddenVars);
        $this->data['form_close'] = form_close();

        $this->theme = FALSE;
        $this->skin = $skin;
        $this->skin_type = "shop";
        $this->view = "item.qna.write.php";
    }

    /**
     * 분류별 상품보기
     * @param $cat_id
     */
    function category($cat_id="")
    {
        if(empty($cat_id))
        {
            alert('존재하지 않는 상품분류 이거나, 삭제된 상품 분류 입니다.');
            exit;
        }

        if(! $this->data['category'] = $this->db->where('cat_id', $cat_id)->get('products_category_list')->row_array())
        {
            alert('존재하지 않는 상품분류 이거나, 삭제된 상품 분류 입니다.');
            exit;
        }

        // 목록스킨을 정의한다.
        $suffix = $this->site->viewmode === DEVICE_MOBILE ? '_m' : '';
        $skin = $this->site->config('skin_shop_list'.$suffix);

        if(! empty($this->data['category']['cat_skin'.$suffix])) {
            $skin = $this->data['category']['cat_skin'.$suffix];
        }

        // 해당 스킨파일이 실제로 존재하는지 확인한다.
        if(! is_file(VIEWPATH . DIR_SKIN . DIRECTORY_SEPARATOR . 'shop_list' . DIRECTORY_SEPARATOR . $skin . DIRECTORY_SEPARATOR . "list.php" )) {
            alert('설정한 스킨파일을 찾을 수 없습니다.');
            exit;
        }

        // 정렬순서
        $sort = strtolower($this->input->get('sort', TRUE, 'new'));

        if( ! in_array($sort, ['new','sales','row_price','high_price','score','review_count'])) {
            $sort = 'new';
        }
        
        // 해당 리스트에 존재하는 모든 상품목록을 가져온다.
        $this->db
            ->select('P.*, IFNULL(PA.att_filepath, "") AS prd_thumbnail_path, PCL.parent_names, PCL.cat_title')
            ->from('products AS P')
            ->join('attach AS PA','PA.att_idx=P.prd_thumbnail','left')
            ->join('products_category_list AS PCL','PCL.cat_id=P.cat_id','left')
            ->where('P.prd_status','Y')
            ->like('PCL.node_path', $this->data['category']['node_path'], 'after' );

        switch ($sort) {
            case "new" :
                $this->db->order_by('P.prd_sort ASC, P.prd_idx DESC');
                break;
            case "sales":
                $this->db->order_by('P.prd_sell_count DESC,P.prd_sort ASC, P.prd_idx DESC');
                break;
            case "row_price":
                $this->db->order_by('P.prd_price ASC,P.prd_sort ASC, P.prd_idx DESC');
                break;
            case "high_price":
                $this->db->order_by('P.prd_price DESC,P.prd_sort ASC, P.prd_idx DESC');
                break;
            case "score":
                $this->db->order_by('P.review_average DESC,P.prd_sort ASC, P.prd_idx DESC');
                break;
            case "review_count":
                $this->db->order_by('P.review_count DESC,P.prd_sort ASC, P.prd_idx DESC');
                break;
        }

        $result = $this->db->get();
        $this->data['list'] = $result->result_array();

        $this->data['list'] = $this->products_model->generateProductList($this->data['list']);
        /*
        foreach($this->data['list'] as &$row)
        {
            $row['thumbnail'] = (file_exists(FCPATH . $row['prd_thumbnail_path'])) ? $row['prd_thumbnail_path'] : '';
            $row['link'] = base_url('products/items/'.$row['prd_idx']);
            $row['cust_price_rate'] = '';

            if($row['prd_cust_price'] > 0 && $row['prd_price'] > 0) {
                $row['cust_price_rate'] = floor(100 - ($row['prd_price'] / $row['prd_cust_price'] * 100)) . '%';
            }
        }*/

        $this->theme = $this->site->get_layout();
        $this->skin = $skin;
        $this->skin_type = "shop_list";
        $this->view = "list";
    }

    /**
     * 진열장 상품 보기
     * @param $dsp_key
     * @return void
     */
    function display($dsp_key="")
    {
        $this->load->model('shop_model');
        $this->load->model('products_model');

        if(empty($dsp_key)) {
            show_404();
            exit;
        }

        if(! $this->data['display_info'] = $this->db
            ->where('dsp_key', $dsp_key)
            ->get('products_display')
            ->row_array())
        {
            show_404();
            exit;
        }


        $this->data['list'] = $this->shop_model->getDisplayList($dsp_key, $this->data['display_info']['dsp_idx']);
        $this->data['list'] = $this->products_model->generateProductList($this->data['list']);

        // 목록스킨을 정의한다.
        $suffix = $this->site->viewmode === DEVICE_MOBILE ? '_m' : '';
        $skin = $this->data['display_info']['dsp_skin'. $suffix];

        $this->theme = $this->site->get_layout();
        $this->skin = $skin;
        $this->skin_type = "shop_list";
        $this->view = "list";
    }

    /**
     * 목록에서 장바구니, 구매하기 버튼을 클릭시 상품의 옵션을 가져와서 표시한다.
     */
    function quick_buy($prd_idx="")
    {
        $hiddenVars = [];
        $attrs =[];

        $attrs['data-form'] = "product-cart";
        $attrs['data-wrap'] = "quick-cart";

        // 목록스킨을 정의한다.
        $suffix = $this->site->viewmode === DEVICE_MOBILE ? '_m' : '';
        $skin = $this->site->config('skin_shop_list'.$suffix);

        if(! empty($this->data['category']['cat_skin'.$suffix])) {
            $skin = $this->data['category']['cat_skin'.$suffix];
        }

        if(empty($prd_idx)) {
            error_response("상품 정보를 불러올 수 없습니다.", 400);
            return;
        }

        if(! $this->data['view'] = $this->products_model->getItem($prd_idx))
        {
            error_response("상품 정보를 불러올 수 없습니다.", 400);
            return;
        }

        // 상품 품절체크
        if($this->data['view']['prd_sell_status'] === 'O') {
            error_response("품절된 상품입니다.", 400);
            return;
        }
        else if($this->data['view']['prd_sell_status'] === 'D') {
            error_response("일시적으로 판매가 중지된 상품입니다.", 400);
            return;
        }
        $this->data['is_soldout'] = $this->products_model->isSoldOut($this->data['view']);
        if($this->data['is_soldout'] ) {
            error_response("품절된 상품입니다.", 400);
            return;
        }


        $item_ct_qty = 1;
        if($this->data['view']['prd_buy_min_qty'] > 1) {
            $item_ct_qty = $this->data['view']['prd_buy_min_qty'];
        }

        $hiddenVars['prd_idx[]'] = $prd_idx;
        $hiddenVars['prd_name[]'] = $this->data['view']['prd_name'];
        $hiddenVars['prd_price[]'] = $this->data['view']['prd_price'];
        $hiddenVars['prd_stock[]'] = $this->products_model->getStockQty($this->data['view']);
        $hiddenVars["opt_type[{$prd_idx}][]"] = 'detail';
        $hiddenVars["opt_code[{$prd_idx}][]"] = "";
        $hiddenVars["opt_value[{$prd_idx}][]"] = "";
        $hiddenVars["opt_price[{$prd_idx}][]"] = 0;
        $hiddenVars["cart_qty[{$prd_idx}][]"] = $item_ct_qty;
        $hiddenVars["is_direct"] = 'N';

        $this->data['options'] = $this->products_model->getOptionArray($this->data['view']);

        $this->data['form_open'] = form_open(NULL, $attrs, $hiddenVars);
        $this->data['form_close'] = form_close();


        $this->theme = FALSE;
        $this->skin = $skin;
        $this->skin_type = "shop_list";
        $this->view = "quick_buy";

    }
}