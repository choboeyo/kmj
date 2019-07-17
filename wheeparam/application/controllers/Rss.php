<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Rss
 * ------------------------------
 * RSS 보기 페이지
 */
class Rss extends WB_Controller {

    function index($brd_key="")
    {
        $this->load->library('boardlib');

        $brd_array = array();
        // 통합 RSS 인 경우
        if( empty($brd_key) )
        {
            $board_list = $this->db->where('brd_lv_read', '0')->get('board')->result_array();
            foreach($board_list as $b)
            {
                $brd_array[] = $b['brd_key'];
            }
        }
        else
        {
            $board = $this->boardlib->get($brd_key, TRUE);
            if( $board['brd_use_rss'] != 'Y' OR $board['brd_lv_read'] > 0 )
            {
                die('해당 게시판은 RSS 사용 설정이 되어있지 않습니다.');
            }
            $brd_array[] = $brd_key;
        }

        if( count($brd_array) <= 0)
        {
            die('RSS를 사용할수 있는 게시판이 없습니다.');
        }

        $post_list = $this->db
                ->select('board_post.*,board.brd_title')
                ->join('board','board.brd_key=board_post.brd_key','inner')
                ->where_in('board_post.brd_key', $brd_array)
                ->where('post_status' ,'Y')
                ->where('post_secret', 'N')
                ->order_by('post_num DESC, post_reply ASC, post_idx DESC')
                ->limit(50)
                ->get('board_post')
                ->result_array();

        header('content-type: text/xml');
        header('cache-control: no-cache, must-revalidate');
        header('pragma: no-cache');

        $title  = (empty($brd_key)) ? $this->site->config('site_title').' 통합 RSS': $board['brd_title'] . ' RSS';
        $url    = (empty($brd_key)) ? base_url() : base_url('board/'.$brd_key);
        $copyright = $this->site->config('site_title');
        $description = (empty($brd_key)) ? $this->site->config('site_meta_description') : $board['brd_description'];

        ob_start();
        echo "<?xml version=\"1.0\" encoding=\"" . config_item('charset') . "\"?".">\n";
        echo "<rss version=\"2.0\">\n";
        echo "<channel>\n";
        echo "<title>" . html_escape(element('title', $title)) . "</title>\n";
        echo "<link>" . $url . "</link>\n";
        if ($copyright) {
            echo "<copyright><![CDATA[ " . html_escape($copyright) . "]]></copyright>";
        }
        if ($description) {
            echo "<copyright><![CDATA[ " . html_escape($description) . "]]></copyright>";
        }

        foreach ($post_list as $row) {
            echo "<item>\n";
            echo "<title><![CDATA[" . element('post_title', $row) . "]]></title>\n";
            echo "<link>" . base_url( "board/{$row['brd_key']}/{$row['post_idx']}") . "</link>\n";
            echo "<author>" . html_escape(element('post_nickname', $row)) . "</author>\n";
            echo "<pubDate>" . date('Y-m-d', strtotime($row['reg_datetime'])) . "</pubDate>\n";
            echo "<description><![CDATA[" . display_html_content($row['post_content']) . "]]></description>\n";
            echo "<category>" . html_escape($row['brd_title']) . "</category>\n";
            echo "</item>\n";
        }
        echo "</channel>\n";
        echo "</rss>\n";

        $xml = ob_get_clean();

        echo $xml;

    }
}