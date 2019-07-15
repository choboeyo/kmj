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
     * 패러미터 정보를 가져온다.
     * @return string
     */

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