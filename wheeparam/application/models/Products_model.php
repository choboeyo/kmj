<?php
/**
 * 상품과 관련된 MODEL
 */
class Products_model extends WB_Model
{
    public $option_id_filter = '/[\'\"\\\'\\\"]/';

    /**
     * 상품 분류를 가져옵니다.
     * @param false $viewHidden 숨김 처리된 목록도 가져옵니다.
     */
    function getCategoryList ($viewHidden = FALSE)
    {
        $cat_status = ["Y"];
        if($viewHidden) $cat_status[] = "H";

        $categoryList = $this->db
            //->select('PC.*')
            //->select("CONCAT(CASE WHEN `PC2`.`cat_id` IS NULL THEN '' ELSE LPAD(`PC2`.`cat_id`,3,'0') END,CASE WHEN `PC1`.`cat_id` IS NULL THEN '' ELSE LPAD(`PC1`.`cat_id`,3,'0') END,LPAD(`PC`.`cat_id`,3,'0')) AS `node_path`")
            //->select("CONCAT(CASE WHEN `PC2`.`cat_id` IS NULL THEN '' ELSE CONCAT(`PC2`.`cat_title`,' > ') END,CASE WHEN `PC1`.`cat_id` IS NULL THEN '' ELSE CONCAT(`PC1`.`cat_title`,' > ') END) AS `parent_names`")
            ->from('products_category_list AS PC')
            //->join('products_category AS PC1', 'PC1.cat_id=PC.cat_parent_id', 'left')
            //->join('products_category AS PC2', 'PC2.cat_id=PC1.cat_parent_id','left')
            ->where_in('PC.cat_status',$cat_status)
            ->order_by('PC.cat_sort ASC')
            ->get()
            ->result_array();

        return $this->categoryArrange($categoryList, 0);
    }

    /**
     * 재귀함수를 통해 상품분류의 자식들의 정리합니다.
     * @param $array
     * @param int $parent_id
     * @return array
     */
    private function categoryArrange($array, $parent_id=0) : array
    {
        $temp = [];
        foreach($array as $row) {
            if($row['cat_parent_id'] == $parent_id)
            {
                $t = $row;
                $t['children'] = $this->categoryArrange($array, $row['cat_id']);

                $temp[] = $t;
            }
        }

        return $temp;
    }

    /**
     * 상품 하나의 정보를 가져온다.
     * @param $prd_id
     * @return array|null
     */
    public function getItem($prd_id, $use_cache = TRUE)
    {
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => PROJECT));

        if($use_cache) {
            if($cache_data = $this->cache->get('prodcuts/'.$prd_id)) {
                return $cache_data;
            }
        }

        $product =  $this->db
            ->where('prd_idx', $prd_id)
            ->get('products')
            ->row_array();

        $product['prd_thumbnail'] = $product['prd_thumbnail'] * 1;

        // 추가입력정보 JSON decode 처리
        $product['prd_extra_info'] = $product['prd_extra_info'] 
            ? json_decode($product['prd_extra_info'], TRUE) 
            : [];

        $product['prd_item_options'] = $product['prd_item_options']
            ? json_decode($product['prd_item_options'], TRUE)
            : [];

        for($i=0; $i<3; $i++) {
            if(! isset($product['prd_item_options'][$i])) {
                $product['prd_item_options'][$i] = [
                  "title" => "",
                  "items" => []
                ];
            }
        }

        // 상품 필수 옵션 행 가져오기
        $product['options'] = [];
        $product['options2'] = [];

        $options = $this->db->where('prd_idx', $prd_id)->get('products_options')->result_array();
        foreach($options as $row)
        {
            $column = $row['opt_type'] === 'detail' ? 'options' : ( $row['opt_type'] === 'addition' ? 'options2' :'' );

            if(empty($column)) continue;

            $row['opt_add_price'] = $row['opt_add_price'] * 1;
            $row['opt_noti_qty'] = $row['opt_noti_qty'] * 1;
            $row['opt_stock_qty'] = $row['opt_stock_qty'] * 1;

            if($row['opt_type'] === 'detail') {
                $row['opt_name'] = explode(SEPERATE_CHARSET, $row['opt_code']);
            }

            unset($row['prd_idx'], $row['opt_idx']);

            $product[$column][] = $row;
        }

        $product['images'] = $this->db
            ->select('att_idx,att_filepath,att_origin')
            ->from('attach')
            ->where('att_target_type', 'PRODUCTS')
            ->where('att_target', $prd_id)
            ->order_by('att_sort ASC, att_idx DESC')
            ->get()
            ->result_array();

        foreach($product['images'] as $row) {
            $row['att_idx'] = $row['att_idx'] * 1;
        }

        $product['prd_thumbnail_path'] = "";

        if($product['prd_thumbnail'] > 0) {
            foreach($product['images'] as $image) {
                if($image['att_idx'] == $product['prd_thumbnail'] ) {
                    $product['prd_thumbnail_path'] = base_url($image['att_filepath']);
                }
                break;
            }
        }

        $product['category_info'] = $this->getCategory($product['cat_id']);
        $product['categoryArray'] = [];

        for($i=1; $i<=3; $i++) {
            if($product['category_info'] && isset($product['category_info']['lv'.$i.'_id']) && $product['category_info']['lv'.$i.'_id'] > 0) {
                $product['categoryArray'][] = [
                    "id" => $product['category_info']['lv'.$i.'_id'],
                    "name" =>$product['category_info']['lv'.$i.'_name'],
                    "count"=> $product['category_info']['lv'.$i.'_count']
                ];
            }
        }

        // 상품의 할인율 구하기
        $product['cust_price_rate'] = '';

        if($product['prd_cust_price'] > 0 && $product['prd_price'] > 0) {
            $product['cust_price_rate'] = floor(100 - ($product['prd_price'] / $product['prd_cust_price'] * 100)) . '%';
        }

        // 상품 필수 표기 정보 가공
        $shop_item_group = file_get_contents(FCPATH . 'assets/js/shop_item_group.json');
        $shop_item_group = json_decode($shop_item_group, TRUE);
        if(empty($product['prd_item_group'])) {
            $product['prd_item_group'] = "wear";
        }
        $_temp = $shop_item_group[$product['prd_item_group']]["items"];

        foreach($product['prd_extra_info'] as $key=>$row) {
            $_temp[$key]["content"] = $row;
        }

        $product['extra_info'] = $_temp;

        $this->cache->save('prodcuts/'.$prd_id, $product, 60*5);

        return $product;
    }

    /**
     * 임시로 생성한 상품 정보를 삭제한다.
     * 임시로 생성한 것이기때문에 업로드한 DB에서 FLAG 처리가 아닌 실제 삭제로 처리한다.
     * @return void
     */
    public function cleanTempItem()
    {
        // 생성된지 하루이상 지난 임시 아이템 목록을 가져온다.
        $yester_day = date('Y-m-d H:i:s', strtotime('-1 days'));
        $list = $this->db->select('prd_idx')->where('reg_datetime <=', $yester_day)->where('prd_status','T')->get('products')->result_array();

        // ID만 뽑아서 배열로 정리한다.
        $id_list = [];
        foreach($list as $row) {
            $id_list[] = $row['prd_idx'];
        }

        // 해당되는 리스트가 1개이상일때만 실행한다.
        if(count($id_list) > 0) {

            // 트랜젝션 모드 시작
            $this->db->trans_begin();

            // 상품목록에서 삭제
            $this->db->where_in('prd_idx', $id_list)->delete('products');

            // 이미지 첨부파일 삭제
            $img_list = $this->db->where_in('att_target', $id_list)->where('att_target_type','PRODUCTS')->get('attach')->result_array();

            foreach($img_list as $img) {
                if( is_file(FCPATH . $img['att_filepath'] ) ) {
                    @unlink(FCPATH . $img['att_filepath']);
                }
            }
            // 이미지 첨부 DB에서 삭제
            $this->db->where_in('att_target', $id_list)->where('att_target_type','PRODUCTS')->delete('attach');

            // 등록된 상품옵션 삭제
            $this->db->where_in('prd_idx', $id_list)->delete('products_options');

            // 트랜잭션 성공시 commit 처리, 트랜잭션 실패시 rollback 처리
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }
        }

    }

    /**
     * 해당 상품을 포함하는 부모카테고리 전부의 상품 수량을 업데이트 한다.
     *
     * @param string|int $cat_id 상품분류 번호
     * @return void
     */
    public function updateCategoryCount($cat_id)
    {
        // 해당 카테고리의 정보를 가져온다.
        if(! $category = $this->db->where('cat_id', $cat_id)->get('products_category_list')->row_array()) {
            return;
        }

        // 해당 카테고리에 직접적으로 등록된 상품 개수
        $cnt1 = (int)$this->db
            ->select('COUNT(*) AS cnt')
            ->where('cat_id', $category['cat_id'])
            ->where('prd_status', 'Y')
            ->get('products')
            ->row(0)
            ->cnt;

        $cnt2 = (int)$this->db
            ->select_sum('cat_product_count','sumval')
            ->like('node_path', $category['node_path'], 'after')
            ->where('LENGTH(node_path)', strlen($category['node_path']) +3 )
            ->where('cat_id <>', $cat_id)
            ->get('products_category_list')
            ->row(0)
            ->sumval;

        $this->db
            ->where('cat_id', $cat_id)
            ->set('cat_product_count', $cnt1 + $cnt2)
            ->update('products_category_list');

        // 만약 부모카테고리가 있는경우
        if($category['cat_parent_id'] > 0) {
            $this->updateCategoryCount($category['cat_parent_id']);
        }
    }

    public function getCategory($cat_id) {
        $row = $this->db
            ->select('t1.*')
            ->select('IFNULL(t1.cat_title,"") AS lv1_name, IFNULL(t1.cat_id,0) AS lv1_id, IFNULL(t1.cat_product_count,0) AS lv1_count')
            ->select('IFNULL(t2.cat_title,"") AS lv2_name, IFNULL(t2.cat_id,0) AS lv2_id, IFNULL(t2.cat_product_count,0) AS lv2_count')
            ->select('IFNULL(t3.cat_title,"") AS lv3_name, IFNULL(t3.cat_id,0) AS lv3_id, IFNULL(t3.cat_product_count,0) AS lv3_count')
            ->from('products_category AS t1')
            ->join('products_category AS t2','t2.cat_id=t1.cat_parent_id','left')
            ->join('products_category AS t3','t3.cat_id=t2.cat_parent_id','left')
            ->where('t1.cat_id', $cat_id)
            ->get()
            ->row_array();

        return $row;
    }

    /**
     * 상품의 실제 재고수량 (창고재고수량 - 주문대기수량) 을 가져온다.
     *
     * @param $product
     * @return int
     */
    public function getStockQty($product) : int
    {
        $jaego =(int)$product['prd_stock_qty'];

        // 주문에서 재고에서 빼지 않은것들 가져오기
        $sumval = (int)$this->db
            ->select_sum('cart_qty','sumval')
            ->from('shop_cart')
            ->where('prd_idx', $product['prd_idx'])
            ->where('opt_code','')
            ->where('cart_use_stock','N')
            ->where_in('cart_status',['주문','입금','준비'])
            ->get()
            ->row(0)->sumval;

        return $jaego - $sumval;
    }

    /**
     * 상품옵션의 실제 재고수량 (창고재고수량 - 주문대기수량)
     * @param string|int $prd_idx 상품 PK
     * @param $option
     *
     * @return int
     */
    public function getOptionStockQty($prd_idx, $option) : int
    {
        $jaego = (int)$option['opt_stock_qty'];

        // 주문에서 재고에서 빼지 않은것들 가져오기
        $sumval = (int)$this->db
            ->select_sum('cart_qty','sumval')
            ->from('shop_cart')
            ->where('prd_idx', $prd_idx)
            ->where('opt_code',$option['opt_code'])
            ->where('opt_type',$option['opt_type'])
            ->where('cart_use_stock','N')
            ->where_in('cart_status',['주문','입금','준비'])
            ->get()
            ->row(0)->sumval;

        return $jaego - $sumval;
    }

    /**
     * 상품 필수 선택 옵션을 가공해서 가져온다.
     *
     * @param $product
     */
    public function getOptionArray($product)
    {
        $returnArray = [];

        if($product['prd_use_options'] !== 'Y') return $returnArray;

        $subject_array = $product['prd_item_options'];

        if(empty($subject_array))
            return $returnArray;

        $subj_count = count($subject_array);

        foreach($product['options'] as $row)
        {
            $opt_code = explode(SEPERATE_CHARSET, $row['opt_code']);

            for($k=0; $k<$subj_count; $k++)
            {
                if(! (isset($returnArray[$k]) && is_array($returnArray[$k])))
                {
                    $returnArray[$k] = [
                        'title' => $subject_array[$k]['title'],
                        'items' => []
                    ];
                }

                $opt_temp = $opt_code[$k];
                $opt_parent = "";
                if($k>0) {
                    $opt_parent = $opt_code[$k-1];
                    $opt_temp = $opt_code[$k-1].SEPERATE_CHARSET.$opt_temp;
                }
                if($k>1) {
                    $opt_parent = $opt_code[$k-2].SEPERATE_CHARSET.$opt_code[$k-1];
                    $opt_temp = $opt_code[$k-2].SEPERATE_CHARSET.$opt_temp;
                }

                if(isset($opt_code[$k]) && $opt_code[$k] && !isset($returnArray[$k]['items'][$opt_temp]))
                {
                    $returnArray[$k]['items'][$opt_temp] = [
                        "code"=>$opt_code[$k],
                        "value"=>$opt_temp,
                        "parent"=>$opt_parent,
                        "price" => $row['opt_add_price'],
                        "stock" => $row['opt_stock_qty']
                    ];
                }
            }
        }

        return $returnArray;
    }

    /**
     * 상품이 품절됐는지 체크
     * @param $product
     * @return bool 상품이 품절됐는지 여부
     */
    public function isSoldOut($product)
    {
        // 상품자체의 품절 옵션이거나, 재고가 0 이하인경우 품절처리
        if($product['prd_sell_status'] === 'O' || $product['prd_stock_qty'] <= 0) {
            return true;
        }

        $count = 0;
        $options_count = 0;
        $soldout = FALSE;

        // 필수선택옵션을 사용하는경우
        if( $product['prd_use_options'] === 'Y' )
        {
            foreach($product['options'] as $row)
            {
                $stock_qty = $this->getOptionStockQty($product['prd_idx'], $row);

                if($stock_qty <= 0)
                    $count++;

                $options_count++;
            }

            // 모든 필수선택 옵션이 품절이면 상품 품절
            if($options_count == $count) {
                $soldout = TRUE;
            }
        }
        // 필수선택옵션을 사용하지 않는 경우
        else {
            // 재고수량 - 판매대기 수량을 가져온다.
            $stock_qty = $this->getStockQty($product);

            if($stock_qty <= 0) {
                $soldout = TRUE;
            }
        }

        return $soldout;
    }

    /**
     * 사용자가 후기를 작성할수 있는지를 체크한다.
     * 
     * @param string|int $prd_idx 상품 PK 
     * @return bool 상품 작성 가능 여부
     */
    public function isWritableReview($prd_idx)
    {
        // 로그인한 회원 PK를 가져온다.
        $mem_idx = $this->member->is_login();

        // 비로그인 상태면 작성불가
        if(! $mem_idx) {
            return FALSE;
        }

        $cnt = (int)$this->db
            ->select("COUNT(*) AS cnt")
            ->from('shop_cart')
            ->where('prd_idx', $prd_idx)
            ->where('mem_idx', $mem_idx)
            ->where('cart_status','완료')
            ->get()
            ->row(0)->cnt;

        return $cnt > 0;
    }

    /**
     * 특정상품의 리뷰 점수를 최신화한다.
     * @param $prd_idx
     */
    public function reviewScoreUpdate($prd_idx)
    {
        $t = $this->db
            ->select('SUM(rev_score) AS score_sum')
            ->select('COUNT(*) AS cnt')
            ->where('prd_idx', $prd_idx)
            ->where('rev_status', 'Y')
            ->get('products_review')
            ->row_array();

        $data['prd_review_average'] = $t['cnt'] > 0 ? ( $t['score_sum'] / $t['cnt'] ) : 0;
        $data['prd_review_count'] = $t['cnt'];

        $this->db->where('prd_idx', $prd_idx)->update('products', $data);

        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => PROJECT));
        $this->cache->delete('products/'.$prd_idx);
    }

    public function wishCountUpdate($prd_idx)
    {
        $t = $this->db
            ->select('COUNT(*) AS cnt')
            ->where('prd_idx', $prd_idx)
            ->get('products_wish')
            ->row_array();

        $data['prd_wish_count'] = $t['cnt'];
        $this->db->where('prd_idx', $prd_idx)->update('products', $data);

        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => PROJECT));
        $this->cache->delete('products/'.$prd_idx);
    }

    public function generateProductList($array)
    {
        foreach($array as &$row)
        {
            $row['thumbnail'] = (file_exists(FCPATH . $row['prd_thumbnail_path'])) ? $row['prd_thumbnail_path'] : '';
            $row['link'] = base_url('products/items/'.$row['prd_idx']);
            $row['cust_price_rate'] = '';

            if($row['prd_cust_price'] > 0 && $row['prd_price'] > 0) {
                $row['cust_price_rate'] = floor(100 - ($row['prd_price'] / $row['prd_cust_price'] * 100)) . '%';
            }
        }

        return $array;
    }
}