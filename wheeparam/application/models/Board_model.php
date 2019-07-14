<?php
/**
 * Class Board_model
 * ----------------------------------------------------------
 * 게시판 관련 모델
 */
class Board_model extends WB_Model
{

    function __construct()
    {
        parent::__construct();

        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => PROJECT));
    }

    /**
     * 게시글 목록을 가져온다.
     * @param $board
     * @param array $param
     * @return mixed
     */
    function post_list($board, $param=array())
    {
        // 공지사항 목록 가져오기
        $param['select'] = "P.*, PC.bca_name, M.mem_photo";
        $param['where']['P.brd_key'] = $board['brd_key'];
        $param['where_in']['post_status'] = array('Y','B');
        $param['order_by'] = "post_num DESC, post_reply ASC, post_idx ASC";
        $param['from'] = "board_post AS P";
        $param['where']['post_notice'] = "Y";
        $param['join'][] = array("board_category AS PC","PC.bca_idx=P.bca_idx","left");
        $param['join'][] = array("member AS M", "M.mem_userid=P.mem_userid","left");
        $param['limit'] = FALSE;

        $notice_list = array();
        $notice_list = $this->get_list($param);

        // 일반 글 목록 가져오기
        $param['where']['post_notice'] = "N";
        $param['page_rows'] = $board['brd_page_rows'];
        $param['page'] = element('page', $param, 1);
        $param['limit'] = ($board['brd_page_limit'] == 'Y');

        if( element('category', $param) ) {
            $category_filter = array( $param['category'] );
            // 해당 카테고리의 하위 아이템도 같이 불러오기 위함
            $sub_cate_list = $this->db->where('bca_parent',  $param['category'])->where('brd_key', $board['brd_key'])->get('board_category')->result_array();
            foreach($sub_cate_list as $c) $category_filter[] = $c['bca_idx'];

            $param['where_in']['P.bca_idx'] = $category_filter;
        }
        if( element('scol', $param) && element('stxt', $param) )
        {
            if( $param['scol'] == 'title' ) {
                $param['sc'] = "post_title";
                $param['st'] = $param['stxt'];
            }
            else if ( $param['scol'] == 'nickname' )
            {
                $param['where']['M.mem_nickname'] = $param['stxt'];
            }
        }
        // 게시판에 관리자 승인기능이 있는경우 승인된 게시물만 가져온다.
        if ( $board['brd_use_assign'] == 'Y' && ! PAGE_ADMIN )
        {
            $param['where']['post_assign'] = 'Y';
        }
        $list = $this->get_list($param);

        $list['list'] = array_merge($notice_list['list'], $list['list']);
        $param = $this->get_param();

        foreach($list['list'] as &$row)
        {
            $row = $this->post_process($board, $row, $param, $board['brd_use_list_file'] == 'Y', $board['brd_use_list_thumbnail'] == 'Y');
        }

        return $list;
    }

    /**
     * 글 내용중 외부서버의 이미지를 내부서버로 복제한다.
     * @param $content
     */
    function copy_external_image($content, $user_agent)
    {
        // 외부서버의 이미지를 내부 서버로 복제한다.
        preg_match_all('/<img(.*)src="([^ "]*)"([^>]*)>/',$content, $matches_img);

        if(isset($matches_img[2]) && count($matches_img[2]) > 0)
        {
            foreach($matches_img[2] as $img) {
                $img = preg_replace('/\?.*/', '', $img);

                $img_server = parse_url($img);
                $cdn_server = parse_url(base_url());

                // 만약 같은서버에 올려진 파일이라면 지나간다.
                if (isset($img_server['host']) && $img_server['host'] === $cdn_server['host']) {
                    continue;
                }

                // 파일의 확장자를 구한다.
                $read_img = $img;
                $fileinfo = pathinfo($read_img);
                $ext = (isset($fileinfo['extension']) && $fileinfo['extension']) ? $fileinfo['extension'] : "";

                // curl로 파일을 복사해온다.
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $read_img);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_REFERER, $read_img);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $img_content = curl_exec ($ch);
                $curl_info = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

                make_dir(DIR_UPLOAD . DIRECTORY_SEPARATOR . "editor", TRUE);
                $file_path_add = "./";
                $new_file_src =  DIR_UPLOAD . "/editor/" .date('Y/m') ."/" .md5(time().$img) . ($ext?".".$ext:"");
                $new_url = base_url($new_file_src);

                $fh = fopen($file_path_add.$new_file_src, 'w');
                fwrite($fh, $img_content);
                fclose($fh);
                curl_close($ch);

                $imagesize = getimagesize($file_path_add.$new_file_src);

                if( $imagesize) {
                    // 기존 html의 경로 바꾸기
                    $content = str_replace( $img, $new_url, $content );
                }
            }
        }

        return $content;
    }

    /**
     * 댓글 목록 가져오기
     * @param $brd_key
     * @param $post_idx
     */
    function comment_list($brd_key, $post_idx, $board_admin=FALSE, $mem_userid="")
    {
        $board = $this->get_board($brd_key, TRUE);

        $comment_table = $this->db->dbprefix('board_comment');

        $param['select']= "{$comment_table}.*,member.mem_photo";
        $param['from'] = "board_comment";
        $param['join'][] = array("member", "member.mem_userid=board_comment.mem_userid","left");
        $param['where']['brd_key'] = $brd_key;
        $param['where']['post_idx'] = $post_idx;
        $param['where_in']['cmt_status'] = array('Y','B');
        $param['order_by'] = "cmt_num DESC,cmt_reply ASC, cmt_idx ASC";

        $list = $this->get_list($param);
        foreach($list['list'] as &$row)
        {
            $row['cmt_datetime'] = display_datetime( $row['cmt_regtime'], $board['brd_display_time']);
            $row['link']['delete'] = base_url("board/{$brd_key}/comment/{$post_idx}/{$row['cmt_idx']}/delete");
            $row['link']['blind'] = base_url("board/{$brd_key}/comment/{$post_idx}/{$row['cmt_idx']}/blind");
            $row['auth'] = $board_admin || ( $row['mem_userid'] >0 && $mem_userid == $row['mem_userid'] ) || $row['mem_userid']==0;
            $row['ip'] = display_ipaddress(long2ip($row['cmt_ip']), '1001');
        }

        return $list;
    }

    /**
     * 패러미터 정보를 가져온다.
     * @return string
     */
    function get_param()
    {
        // 링크를 위한 자료정리
        $queryParam = array();
        if( $this->input->get('category', TRUE) ) $queryParam['category'] = $this->input->get('category', TRUE);
        if( $this->input->get('page', TRUE) )  $queryParam['page'] = $this->input->get('page', TRUE);
        if( $this->input->get('scol', TRUE) )  $queryParam['scol'] = $this->input->get('scol', TRUE);
        if( $this->input->get('stxt', TRUE) )  $queryParam['stxt'] = $this->input->get('stxt', TRUE);

        $param = "";
        if( $queryParam && is_array($queryParam) )
        {
            $param = http_build_query($queryParam);
            $param = "?".  $param;
        }
        return $param;
    }

    /**
     * 게시글에 포함된 첨부파일 목록을 가져온다.
     * @param $brd_key
     * @param $post_idx
     * @return array
     */
    function get_attach_list($brd_key, $post_idx)
    {
        if(empty($brd_key) OR empty($post_idx)) return array();
        $file_list =  $this->db->where('att_target_type', 'BOARD')->where('att_target', $post_idx)->get('attach')->result_array();
        foreach($file_list as &$f)
        {
            $f['link'] = base_url("board/{$brd_key}/download/{$post_idx}/{$f['att_idx']}");
        }
        return $file_list;
    }

    /**
     * 게시글 하나를 가져온다.
     * @param $brd_key
     * @param $post_idx
     * @param bool $get_raw_data
     * @return mixed
     */
    function get_post($brd_key, $post_idx, $get_raw_data=FALSE)
    {
        $this->db->select('board_post.*, member.mem_photo');
        $this->db->from('board_post');
        $this->db->join("member", "member.mem_userid=board_post.mem_userid","left");
        $this->db->where('post_idx', $post_idx);
        $this->db->where_in('post_status', array('Y','N'));
        $this->db->where('brd_key', $brd_key);
        $result =$this->db->get();

        $post = $result->row_array();

        if( ! $get_raw_data ) {
            $board = $this->get_board($brd_key,FALSE);
            $post = $this->post_process($board, $post, '',TRUE, TRUE);

            $np = $this->get_np($brd_key, $post_idx, $post['post_num'], $post['post_reply']);
            $post['prev'] = ( isset($np['prev']) && isset($np['prev']['post_idx']) )?  $np['prev'] : NULL;
            $post['next'] = ( isset($np['next']) && isset($np['next']['post_idx']) )?  $np['next'] : NULL;
        }

        return $post;
    }

    /**
     * 게시글의 내용을 정리한다.
     * @param $board
     * @param $post
     * @param $param
     * @param bool $extra
     * @param bool $files
     * @return mixed
     */
    function post_process($board, $post, $param, $files=FALSE, $thumbnail=FALSE)
    {
        $post['post_notice'] = ($post['post_notice']=='Y');
        $post['link'] = base_url("board/{$board['brd_key']}/{$post['post_idx']}".$param);
        $post['link_modify'] = base_url("board/{$board['brd_key']}/write/{$post['post_idx']}");
        $post['link_delete'] = base_url("board/{$board['brd_key']}/delete/{$post['post_idx']}");
        $post['is_new'] = ((time() - strtotime($post['post_regtime']) ) <= ($board['brd_time_new'] * 60 * 60));
        $post['is_hot'] = ($post['post_hit'] >= $board['brd_hit_count']) ;
        $post['is_secret'] = ($post['post_secret'] == 'Y');
        $post['post_datetime'] = display_datetime($post['post_regtime'], $board['brd_display_time']);

        if( $files)
        {
            $post['file'] = $this->get_attach_list($board['brd_key'], $post['post_idx']);
        }

        $post['post_thumbnail'] = "";
        if( $thumbnail )
        {
            if(! isset($post['file']))
            {
                $post['file'] = $this->get_attach_list($board['brd_key'], $post['post_idx']);
            }

            $post['post_thumbnail'] = get_post_thumbnail($post, $board['brd_thumb_width'], $board['brd_thumb_height']);
        }

        return $post;
    }

    /**********************************************************
     * 첨부파일 삭제
     * @param $bfi_idx
     * @return mixed
     *********************************************************/
    function attach_remove($att_idx)
    {
        if(empty($att_idx)) return false;
        $this->db->where("att_idx", $att_idx);
        $result = $this->db->get('attach');
        $attach = $result->row_array();
        if(! $attach) return false;
        if( file_exists(FCPATH. $attach['att_filepath']) )
        {
            @unlink(FCPATH.$attach['att_filepath']);
        }
        $this->db->where("att_idx", $att_idx);
        $this->db->delete("attach");
    }

    /**
     * 해당 게시판에 관리자 권한이 있는지 확인한다.
     * @param $brd_key
     * @param $member_idx
     * @return bool
     */
    function is_admin($brd_key, $member_idx)
    {
        if( empty($member_idx) OR $member_idx == 0 )
        {
            return FALSE;
        }

        $result = (int)$this->db->select('COUNT(*) AS cnt')->where('ath_type','BOARD')->where('ath_key', $brd_key)->where('mem_idx', $member_idx)->get('member_auth')->row(0)->cnt;
        return ( $result > 0  );
    }

    /**
     * 해당 게시글의 이전글과 다음글을 가져온다.
     * @param $brd_key
     * @param $post_idx
     * @param $post_num
     * @param $post_depth
     * @return mixed
     */
    function get_np($brd_key, $post_idx,$post_num,$post_reply)
    {
        $param = $this->get_param();

        $this->db->group_start();
        $this->db->or_group_start();
        $this->db->where("post_num =", (int)$post_num);
        $this->db->where('post_reply >', $post_reply);
        $this->db->where('post_idx >', $post_idx);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('post_num <', $post_num);
        $this->db->group_end();
        $this->db->group_end();

        // 이전글 가져오기
        $return['prev'] = $this->db->where_in("post_status", array("Y","B"))
            ->where('post_notice', "N")
            ->where("brd_key", $brd_key)
            ->where('post_idx !=', $post_idx)
            ->limit(1)
            ->order_by("post_num DESC, post_reply ASC, post_idx ASC")
            ->get("board_post")
            ->row_array();

        if(isset($return['prev']['post_idx']))
        {
            $return['prev']['link'] = base_url("board/{$brd_key}/{$return['prev']['post_idx']}".$param);
        }

        $this->db->group_start();
        $this->db->or_group_start();
        $this->db->where("post_num =", (int)$post_num);
        $this->db->where('post_reply <', $post_reply);
        $this->db->where('post_idx <', $post_idx);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('post_num >', $post_num);
        $this->db->group_end();
        $this->db->group_end();

        // 다음글 가져오기
        $return['next']  =
            $this->db->where_in("post_status", array("Y","B"))
                ->where('post_notice', "N")
                ->where("brd_key", $brd_key)
                ->where('post_idx !=', $post_idx)
                ->limit(1)
                ->order_by("post_num ASC, post_reply DESC, post_idx DESC")
                ->get("board_post")->row_array();

        if(isset($return['next']['post_idx']))
        {
            $return['next']['link'] = base_url("board/{$brd_key}/{$return['next']['post_idx']}".$param);
        }

        return $return;
    }

    /**
     * 게시판과 관련된 링크를 가져온다.
     * @param $brd_key
     * @param string $post_idx
     * @return mixed
     */
    function get_link($brd_key, $post_idx="")
    {
        $queryParam = array();
        if( $this->input->get('category', TRUE) ) $queryParam['category'] = $this->input->get('category', TRUE);
        if( $this->input->get('page', TRUE) )  $queryParam['page'] = $this->input->get('page', TRUE);
        if( $this->input->get('scol', TRUE) )  $queryParam['scol'] = $this->input->get('scol', TRUE);
        if( $this->input->get('stxt', TRUE) )  $queryParam['stxt'] = $this->input->get('stxt', TRUE);

        $param = "";
        if( $queryParam && is_array($queryParam) )
        {
            $param = http_build_query($queryParam);
            $param = "?".  $param;
        }

        $return['base_url'] = base_url("board/{$brd_key}");
        $return['list'] = base_url("board/{$brd_key}". $param);
        $return['write'] = base_url( "board/{$brd_key}/write". $param );
        $return['rss'] = base_url("rss/{$brd_key}");

        if(! empty($post_idx))
        {
            $return['modify'] = base_url( "board/{$brd_key}/write/{$post_idx}".$param );
            $return['delete'] = base_url( "board/{$brd_key}/delete/{$post_idx}".$param );
            $return['reply'] = base_url( "board/{$brd_key}/reply/{$post_idx}". $param );
        }

        return $return;
    }

    /**
     * 게시판 목록을 가져온다.
     */
    function board_list()
    {
        $param['from'] = "board";
        $param['order_by'] = "brd_sort ASC";
        $param['limit'] = FALSE;

        $result = $this->get_list($param);
        return $result;
    }

    /**
     * 게시판 하나의 정보를 가져온다.
     * @param $brd_key
     */
    function get_board($brd_key, $raw_data = FALSE)
    {
        return $raw_data ? $this->_get_board_raw($brd_key) : $this->_get_board_mixed($brd_key);
    }

    /**
     * 가공되지 않은 게시판 정보를 가져온다.
     * @param $brd_key
     */
    private function _get_board_raw($brd_key)
    {
        if(empty($brd_key)) return array();

        if( ! $board = $this->cache->get('board_raw_'.$brd_key) ) {
            $param['from']  = "board";
            $param['idx']   = $brd_key;
            $param['column'] = "brd_key";

            $board = $this->get_one($param);

            if(! IS_TEST) {
                $this->cache->save('board_raw_'.$brd_key, $board, 60*5);
            }
        }

        return $board;
    }

    /**
     * 가공한 게시판의 정보를 가져온다.
     * @param $brd_key
     */
    private function _get_board_mixed($brd_key)
    {
        if( empty($brd_key) ) return array();

        if( ! $board = $this->cache->get('board_'.$brd_key) ) {

            $board = $this->_get_board_raw($brd_key);

            $board['category'] = $this->get_all_category($brd_key);

            if(! IS_TEST)
            {
                $this->cache->save('board_'.$brd_key, $board, 60*5);
            }

        }

        return $board;
    }

    /**
     * 특정 게시판의 캐시를 삭제한다.
     * @param $brd_key
     */
    function delete_cache($brd_key)
    {
        $this->cache->delete('board_raw_'.$brd_key);
        $this->cache->delete('board_'.$brd_key);
    }

    /**
     * 해당게시판의 전체 카테고리 구조 출력
     */
    function get_all_category($brd_key)
    {

        if(empty($brd_key)) return NULL;

        $this->db->where('brd_key', $brd_key);
        $this->db->order_by('bca_parent ASC, bca_sort ASC', TRUE);
        $this->db->from('board_category');
        $result = $this->db->get();
        $list = $result->result_array();

        $return = array();
        foreach($list as &$row)
        {
            if( $row['bca_parent'] == 0 )
            {
                $row['link'] = base_url("board/{$brd_key}?category=".$row['bca_idx']);
                $return[$row['bca_idx']] = $row;
                $return[$row['bca_idx']]['items'] = array();
            }
            else if ( $row['bca_parent'] >= 1 )
            {
                $row['link'] = base_url("board/{$brd_key}?category=".$row['bca_idx']);
                $return[$row['bca_parent']]['items'][] = $row;
            }
        }

        return $return;
    }

    /**
     * 카테고리 한개의 정보를 가져온다.
     * @param $bca_idx
     * @return bool
     */
    function get_category($bca_idx)
    {
        $param['from']  = "board_category";
        $param['idx']   = $bca_idx;
        $param['column'] = "bca_idx";

        return $this->get_one($param);
    }

    /**
     * 해당 게시글의 코멘트가 몇개인지 확인한다.
     * @param $brd_key
     * @param $post_idx
     */
    function get_comment_count($brd_key, $post_idx)
    {
        $count = (int)$this->db->select('COUNT(*) AS cnt')->from('board_comment')->where_in('cmt_status', array('Y','B'))->where('brd_key',$brd_key)->where('post_idx',$post_idx)->get()->row(0)->cnt;
        return $count;
    }

    /**
     * 해당 게시물의 댓글수를 최신화 한다
     * @param $brd_key
     * @param $post_idx
     */
    function update_post_comment_count($brd_key, $post_idx)
    {
        $count = $this->get_comment_count($brd_key, $post_idx);

        $this->db->where('brd_key', $brd_key);
        $this->db->where('post_idx', $post_idx);
        $this->db->set('post_count_comment', (int)$count);
        return $this->db->update('board_post');
    }
}