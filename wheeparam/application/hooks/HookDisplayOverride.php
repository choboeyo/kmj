<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * HookPostControllerConstructor.php
 *
 * 컨트롤러가 인스턴스화 된 직후 가동되는 후킹 클래스.
 *
 */

class HookDisplayOverride {

    function init()
    {
        $this->CI =& get_instance();
        $output = !( $this->CI->uri->segment(1) == 'rss' OR strpos($this->CI->uri->segment(1), "sitemap") !== FALSE ) ? $this->set_layout( $this->CI->output->get_output() ) : $this->CI->output->get_output();
        $this->CI->output->_display($output);
    }

    function set_layout($output)
    {
        if( PAGE_AJAX OR PAGE_INSTALL OR $this->CI->theme === FALSE ) return $output;

        // Script Tag를 모두 가져와서 body 밑으로
        preg_match_all("/<script\\b[^>]*>([\\s\\S]*?)<\\/script>/", $output, $matches);
        $output = preg_replace("/<script\\b[^>]*>([\\s\\S]*?)<\\/script>/","", $output);

        $head = '<!DOCTYPE html>'.PHP_EOL;
        $head .= '<html lang="ko">'.PHP_EOL;
        $head .= '<head>'.PHP_EOL;
        $head .= $this->CI->site->display_meta();
        $head .= $this->CI->site->display_css();
        $head .= '</head>'.PHP_EOL;
        $head .= '<body>';

        $foot = "";
        $foot .= $this->CI->site->display_js() . PHP_EOL;
        foreach($matches[0] as $match) $foot .= $match;

        // 구글애널리틱스 코드가 있다면?
        if( $this->CI->uri->segment(1) != 'admin' && ! $this->CI->input->is_ajax_request() && ! $this->CI->agent->is_robot() && ! $this->CI->member->is_super() )
        {
            $foot .=  (! empty($this->CI->site->config('analytics_google')) ) ? $this->CI->site->config('analytics_google').PHP_EOL : '';
            $foot .= (! empty($this->CI->site->config('analytics_naver')) ) ? $this->CI->site->config('analytics_naver').PHP_EOL : '';
            $foot .= (! empty($this->CI->site->config('analytics_etc')) ) ? $this->CI->site->config('analytics_etc').PHP_EOL : '';
        }

        // 사이트 채널 연동
        $channel = array();
        $channel_type_array = array('facebook','instagram','itunes','naver_blog','naver_cafe','naver_pholar','naver_post','naver_storefarm','playstore');
        $channel_type = $this->CI->site->config('channel_type') == 'Organization' ? 'Organization' : 'Person';
        foreach($channel_type_array as $c)
        {
            if( $this->CI->site->config('channel_'.$c) ) $channel[] = $this->CI->site->config('channel_'.$c);
        }
        if( count($channel) > 0 )
        {
            $channel_arr = array(
                "@context" => "http://schema.org",
                "@type" => $channel_type,
                "name" => $this->CI->site->config('site_title'),
                "url" => base_url(),
                "sameAS" => $channel
            );

            $foot .= '<script type="application/ld+json">'.PHP_EOL;
            $foot .= json_encode($channel_arr, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;
            $foot .= '</script>';
        }

        $foot .= "<script src='".base_url('helptool/lang')."'></script>";

        $foot .= '</body>'.PHP_EOL;
        $foot .= '</html>';

        $output = $head.PHP_EOL.$output.PHP_EOL.$foot;

        return $output;
    }
}