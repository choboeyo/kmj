<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Sitemap
 * ------------------------------------------------------------------------
 * 사이트맵
 */
class Sitemap extends WB_Controller {

    /**
     * 사이트맵에 등록할 일반 페이지들
     */
    function pages()
    {
        $data['list'] = array();

        // 메인페이지
        $data['list'][] = array(
            "loc"  => base_url("/"),
            "lastmod" => date('Y-m-d'),
            "priority" => "0.8",
            "changefreq" => "daily"
        );

        $list = $this->db->get('sitemap')->result_array();

        if( count($list) > 0 )
        {
            foreach($list as $row)
            {
                $data['list'][] = array(
                    "loc"  => base_url($row['sit_loc']),
                    "lastmod" => date('Y-m-d'),
                    "priority" => "{$row['sit_priority']}",
                    "changefreq" => $row['sit_changefreq']
                );
            }
        }

        $this->layout = FALSE;
        $result = $this->load->view('tools/sitemap_pages', $data, TRUE);
        echo $result;
    }

    /**
     * 통합 사이트맵
     */
    function index()
    {
        $list = $this->db->where('brd_lv_read','0')->get('board')->result_array();

        ob_start();
        echo "<?xml version=\"1.0\" encoding=\"" . config_item('charset') . "\"?".">\n";
        echo "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        echo "<sitemap>".PHP_EOL;
        echo "<loc>".base_url('sitemap_1.xml')."</loc>".PHP_EOL;
        echo "</sitemap>";


        foreach ($list as $row) {
            echo "<sitemap>".PHP_EOL;
            echo "<loc>" . base_url("sitemap_".$row['brd_key'].".xml") . "</loc>\n";
            echo "</sitemap>\n";
        }

        echo "</sitemapindex>\n";

        $xml = ob_get_clean();

        echo $xml;

    }

    /**
     * 게시판별 사이트맵
     * @param $brd_key
     */
    function board($brd_key)
    {
        if(empty($brd_key))
        {
            die('잘못된 접근입니다.');
        }

        $board = $this->boardlib->get($brd_key, TRUE);

        if( !$board OR  $board['brd_lv_read'] > 0 )
        {
            die('이 게시판은 사이트맵을 사용하지 않습니다.');
        }

        $list = $this->db->where('post_secret', 'N')->where('post_status','Y')->where('brd_key', $brd_key)->from('board_post')->get()->result_array();

        $data['list'] = array();

        // 게시판 자체 사이트맵 추가
        // 게시판글 최종수정일 가져오기
        $max_date = $this->db->select_max('upd_datetime', 'max')->where('post_status','Y')->where('brd_key',$brd_key)->from('board_post')->get()->row(0)->max;
        $data['list'][] = array(
            "loc"  => base_url("/board/{$brd_key}"),
            "lastmod" => date('Y-m-d', strtotime($max_date)),
            "priority" => "0.7",
            "changefreq" => "daily"
        );

        foreach($list as $row)
        {
            $data['list'][] = array(
                "loc"  => base_url("/board/{$brd_key}/{$row['post_idx']}"),
                "lastmod" => date('Y-m-d', strtotime($row['upd_datetime'])),
                "priority" => "0.7",
                "changefreq" => "daily"
            );
        }

        $this->layout = FALSE;
        $result = $this->load->view('tools/sitemap_pages', $data, TRUE);
        echo $result;
    }

    function __construct()
    {
        parent::__construct();

        header('content-type: text/xml');
        header('cache-control: no-cache, must-revalidate');
        header('pragma: no-cache');
    }
}