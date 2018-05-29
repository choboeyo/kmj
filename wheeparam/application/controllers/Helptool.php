<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Helptool extends WB_Controller {

    public function index()
    {
        show_404();
    }

    public function lang()
    {
        header('Content-Type: application/javascript; charset=UTF-8');
        header('cache-control: no-cache, must-revalidate');
        header('pragma: no-cache');

        echo "var LANG = {};".PHP_EOL;
        $list = $this->db->get('localize')->result_array();
        foreach($list as $row) {
            $key = str_replace( array("게시판","공통","회원","팝업"), array('board','common','member','popup'), $row['loc_key'] );
            echo "LANG." . str_replace("/", "_", $key)." = '".str_replace("\r\n","", nl2br($row['loc_value_'.LANG]))."';".PHP_EOL;
        }
        exit;
    }

    /**
     * 네이버 신디케이션
     * @param $brd_key
     * @param $post_idx
     */
    public function naversyndi($brd_key, $post_idx)
    {
        $this->load->model('board_model');

        if ( empty($this->site->config('naver_syndication_key'))) {
            die('신디케이션 키가 입력되지 않았습니다');
        }

        $post_idx = (int) $post_idx;

        if (empty($post_idx) OR $post_idx < 1) {
            show_404();
        }

        $board = $this->board_model->get_board($brd_key, FALSE);
        if( ! element('brd_key', $board) )
        {
            show_404();
        }
        if ( $board['brd_use_naver_syndi'] != 'Y') {
            die('이 게시판은 신디케이션 기능을 사용하지 않습니다');
        }

        $post = $this->board_model->get_post($brd_key, $post_idx, FALSE);

        if ( ! element('post_idx', $post)) {
            show_404();
        }

        if( $post['post_status'] != 'Y' ) {
            show_404();
        }

        // 비회원 글읽기가 불가능한경우 리턴
        if( $board['brd_lv_read'] != 0 )
        {
            die('이 게시판은 신디케이션 기능을 사용하지 않습니다');
        }
        if ($post['post_secret'] == 'Y') {
            die('비밀글은 신디케이션을 지원하지 않습니다');
        }

        $base_url = rtrim(base_url("board/{$brd_key}"), '/');
        $post_content = str_replace(PHP_EOL,"", ($post['post_content']));

        //$content = str_replace(array('&amp;', '&nbsp;'), array('&', ' '), $post_content);
        $content = $post_content;
        $summary = str_replace(PHP_EOL,"", str_replace(array('&amp;', '&nbsp;'), array('&', ' '), html_escape(strip_tags(element('post_content', $post)))));

        header('content-type: text/xml');
        header('cache-control: no-cache, must-revalidate');
        header('pragma: no-cache');

        $xml = "";
        $xml .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $xml .= "<feed xmlns=\"http://webmastertool.naver.com\">\n";
        $xml .= "<id>".PHP_EOL . $base_url .PHP_EOL. "</id>\n";
        $xml .= "<title>{$post['post_title']}</title>\n";
        $xml .= "<author>\n";
        $xml .= "<name>webmaster</name>\n";
        $xml .= "</author>\n";
        $xml .= "<updated>" . date('Y-m-d\TH:i:s\+09:00') . "</updated>\n";
        $xml .= '<link rel="site" href="'.$base_url.'" title="'.html_escape($this->site->config('site_title')).'" />'.PHP_EOL;
        $xml .= "<entry>\n";
        $xml .= "<id>" . PHP_EOL . base_url('board/'.$brd_key.'/'.$post_idx) . PHP_EOL . "</id>\n";
        $xml .= "<title><![CDATA[" . html_escape(element('post_title', $post)) . "]]></title>\n";
        $xml .= "<author>\n";
        $xml .= "<name>" . html_escape(element('mem_nickname', $post)) . "</name>\n";
        $xml .= "</author>\n";
        $xml .= "<updated>" .date('Y-m-d\TH:i:s\+09:00', strtotime(element('post_modtime', $post))) . "</updated>\n";
        $xml .= "<published>" . date('Y-m-d\TH:i:s\+09:00', strtotime(element('post_regtime', $post))) . "</published>\n";
        $xml .= "<link rel=\"via\" href=\"" . base_url('board/'.$brd_key) . "\" title=\"" . html_escape(element('brd_title', $board)) . "\" />\n";
        $xml .= "<link rel=\"mobile\" href=\"" . base_url('board/'.$brd_key.'/'.$post_idx) . "\" />\n";
        $xml .= "<content type=\"html\"><![CDATA[{$content}]]></content>\n";
        $xml .= "<summary type=\"text\"><![CDATA[{$summary}]]></summary>\n";
        $xml .= "<category term=\"" . element('brd_key', $board) . "\" label=\"" . html_escape(element('brd_title', $board)) . "\" />\n";
        $xml .= "</entry>\n";
        $xml .= "</feed>";

        echo $xml;
        $this->layout = FALSE;
    }
}
