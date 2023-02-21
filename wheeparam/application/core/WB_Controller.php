<?php
defined('BASEPATH') OR exit();
/**
 * A base controller for CodeIgniter with view autoloading, layout support,
 * model loading, helper loading, asides/partials and per-controller 404
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-controller
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

/**
 * @property CI_Loader $load
 * @property CI_DB $db
 * @property CI_Output $output
 * @property CI_Cache $cache
 * @property CI_Email $email
 * @property CI_Session $session
 * @property CI_User_agent $agent
 * @property WB_Input $input
 * @property WB_Form_validation $form_validation
 * @property WB_Upload $upload
 * @property Faq_model $faq_model
 * @property Member_model $member_model
 * @property Popup_model $popup_model
 * @property Search_model $search_model
 * @property Statics_model $statics_model
 * @property Site $site
 * @property Boardlib $boardlib
 * @property Paging $paging
 * @property Member $member
 * @property Banner $banner
 */
class WB_Controller extends CI_Controller
{
    public $view = FALSE;
    public $data = array();
    protected $asides = array();
    public $theme = FALSE;
    public $theme_file = "theme";
    public $active 	= NULL;
    public $sub_active = NULL;
    public $skin	= NULL;
    public $skin_type = NULL;

    public function __construct()
    {
        parent::__construct();
    }

    public function _remap($method)
    {
        if (method_exists($this, $method)) call_user_func_array(array($this, $method), array_slice($this->uri->rsegments, 2));
        else
        {
            if (method_exists($this, '_404')) call_user_func_array(array($this, '_404'), array($method));
            else show_404(strtolower(get_class($this)).'/'.$method);
        }

        $this->_load_view();
    }

    protected function _load_view()
    {
        if( empty($this->view) ) return;

        if( $this->skin && $this->skin_type )
        {
            $view = DIR_SKIN . '/' . $this->skin_type . '/' . $this->skin . '/' . $this->view;
        }
        else if ( isset($this->theme) && $this->theme )
        {
            $view = DIR_THEME . '/' . $this->theme . '/' . $this->view;
        }
        else {
            $view = $this->view;
        }

        if (!empty($this->asides) && is_array($this->asides))
        {
            foreach ($this->asides as $name => $file)
            {
                if($this->skin && $this->skin_type) {
                    $file_url = DIR_SKIN . DIRECTORY_SEPARATOR . $this->skin_type . DIRECTORY_SEPARATOR . $this->skin . DIRECTORY_SEPARATOR . $file;
                }
                else {
                    $file_url = (isset($this->theme) && $this->theme) ? DIR_THEME . '/' . $this->theme .'/' . $file : $file;
                }

                $this->data['asides_'.$name] = $this->load->view($file_url, $this->data, TRUE);
            }
        }

        $data['contents'] = $this->load->view($view, $this->data, TRUE);
        if( $this->theme !== FALSE && $this->skin && $this->skin_type )
        {
            if(is_file(VIEWPATH .DIR_SKIN . DIRECTORY_SEPARATOR . $this->skin_type . DIRECTORY_SEPARATOR . $this->skin . DIRECTORY_SEPARATOR . "skin.css")) {
                $data['contents'] .= "<style>";
                $data['contents'] .= file_get_contents(VIEWPATH .DIR_SKIN . DIRECTORY_SEPARATOR . $this->skin_type . DIRECTORY_SEPARATOR . $this->skin . DIRECTORY_SEPARATOR."skin.css");
                $data['contents'] .= "</style>";
            }
            if(is_file(VIEWPATH .DIR_SKIN . DIRECTORY_SEPARATOR . $this->skin_type . DIRECTORY_SEPARATOR . $this->skin . DIRECTORY_SEPARATOR."skin.js")) {
                $data['contents'] .= "<script>";
                $data['contents'] .= file_get_contents(VIEWPATH .DIR_SKIN . DIRECTORY_SEPARATOR . $this->skin_type . DIRECTORY_SEPARATOR . $this->skin . DIRECTORY_SEPARATOR."skin.js");
                $data['contents'] .= "</script>";
            }
        }

        $data = array_merge($this->data, $data);
        $theme = (isset($this->theme) && $this->theme != FALSE) ? $this->theme : NULL;

        $output_data = ($theme) ?  $this->load->view( DIR_THEME . '/' . $theme. '/' . $this->theme_file , $data, TRUE) : $data['contents'];

        $output_data = preg_replace_callback('!\[widget([^\]]*)\]!is', array($this, '_widget_replace'), $output_data);

        $this->output->set_output($output_data);
    }

    protected function _widget_replace( $matches )
    {
        $vars = trim($matches[1]);
        $vars = preg_replace('/\r\n|\r|\n|\t/',' ',$vars);
        $vars = str_replace( array('"','  '), array('',' '), $vars );
        $vars = trim(str_replace( " ", '&', $vars ));

        parse_str($vars, $vars_array);

        $vars_array = array_merge( $vars_array, $this->data );
        $vars_array['CI'] = get_instance();

        // Name이 정의되지 않았다면 리턴
        if( ! isset($vars_array['name']) ) return $this->load->view('tools/widget_error', array("message"=>"위젯 속성중 [name] 값이 정의되지 않았습니다."), TRUE);
        if( ! file_exists( VIEWPATH . DIR_WIDGET . '/' . $vars_array['name']."/widget.php") ) return $this->load->view('tools/widget_error', array("message"=>"{$vars_array['name']} 위젯파일이 존재하지 않습니다."), TRUE);

        // CSS와 JS파일이 있다면 로드
        if( file_exists( VIEWPATH . DIR_WIDGET . '/' . $vars_array['name']."/widget.css") ) $this->site->add_css( '/views/' . DIR_WIDGET . '/' . $vars_array['name'] . "/widget.css");
        if( file_exists( VIEWPATH . DIR_WIDGET . '/' . $vars_array['name']."/widget.js") ) $this->site->add_js( '/views/' . DIR_WIDGET . '/' . $vars_array['name'] . "/widget.js");

        return "<div class=\"widget-{$vars_array['name']}\">". $this->load->view( DIR_WIDGET . '/' . $vars_array['name'] . '/widget', $vars_array, TRUE ) . "</div>";
    }
}