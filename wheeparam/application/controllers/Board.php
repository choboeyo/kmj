<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board extends WB_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('board_model');
    }

    /**
     * INDEX 페이지는 접근금지
     */
    function index()
    {
        alert(langs( 'board/msg/invalid_access' ));
    }

    /**
     * 게시판 보기 페이지
     * @param $brd_key
     * @param $post_idx
     */
    public function view($brd_key, $post_idx)
    {
        $this->board_common($brd_key, 'read');
        $this->data['view'] = $this->board_model->get_post($brd_key, $post_idx, FALSE);

        if(! in_array( $this->data['view']['post_status'], array("Y","B")))
        {
            alert(langs('게시판/msg/invalid_post'));
            exit;
        }
        
        // 비밀글일 경우 처리
        if( $this->data['view']['post_secret'] == 'Y' )
        {
            $is_auth = FALSE;

            if( !empty($this->data['view']['mem_userid']) && $this->data['view']['mem_userid'] == $this->member->info('userid') )
            {
                $is_auth = TRUE;
            }

            if( $this->data['board']['auth']['admin'] ) {
                $is_auth = TRUE;
            }

            // 해당 글이 답글일 경우
            if( strlen($this->data['view']['post_reply']) > 0 && $this->member->is_login())
            {
                // 원글중에 작성자가 있는경우 글을 볼 권한이 있다!
                $tmp = $this->db->where('post_num', $this->data['view']['post_num'])->where('brd_key', $brd_key)->get('board_post')->result_array();
                foreach($tmp as $t)
                {
                    if( $t['mem_userid'] && $t['mem_userid'] == $this->member->info('userid') )
                    {
                        $is_auth = TRUE;
                        break;
                    }
                }
            }

            if(! $is_auth)
            {
                if( ! $this->session->userdata('post_password_'.$post_idx) )
                {
                    redirect(base_url("board/{$brd_key}/password/{$post_idx}?w=s&reurl=".current_full_url()));
                }
            }
        }

        // 게시판 조회수 상승
        if( ! $this->session->userdata('post_hit_'.$post_idx) OR (int)$this->session->userdata('post_hit_'.$post_idx) + 60*60*24 < time() )
        {
            $this->db->where('post_idx', $post_idx)->set('post_hit', 'post_hit+1', FALSE)->update('board_post');
            $this->data['view']['post_hit'] += 1;
            $this->session->set_userdata('post_hit_'.$post_idx, time());
        }

        // 포인트 관련 프로세스
        $this->point_process('brd_point_read', 'POST_READ', '게시글 읽기', $post_idx, ($this->data['view']['mem_userid'] == $this->member->info('userid')) );

        // 링크 추가
        $this->data['board']['link']['reply'] = base_url("board/{$brd_key}/write/?post_parent={$post_idx}");
        $this->data['board']['link']['modify'] = base_url("board/{$brd_key}/write/{$post_idx}");
        $this->data['board']['link']['delete'] = base_url("board/{$brd_key}/delete/{$post_idx}");

        // 메타태그 설정
        $this->site->meta_title         = $this->data['view']['post_title'] . ' - ' . $this->data['board']['brd_title']; // 이 페이지의 타이틀
        $this->site->meta_description 	= cut_str(get_summary($this->data['view']['post_content'],FALSE),80);   // 이 페이지의 요약 설명
        $this->site->meta_keywords 		= $this->data['view']['post_keywords'];   // 이 페이지에서 추가할 키워드 메타 태그
        $this->site->meta_image			= $this->data['view']['post_thumbnail'];   // 이 페이지에서 표시할 대표이미지

        // 댓글 입력폼

        $write_skin_path = DIR_SKIN . "/board/comment/" . $this->data['board']['brd_skin_c'] . "/c_write";
        $comment_hidden = array("reurl"=>current_full_url(),"cmt_idx"=>"","cmt_parent"=>"");
        $comment_action_url = base_url( "board/{$brd_key}/comment/{$post_idx}", SSL_VERFIY ? 'https':'http' );
        $tmp['comment_view'] = array();
        $tmp['comment_form_open'] = form_open($comment_action_url,array("id"=>"form-board-comment","data-form"=>"board-comment"), $comment_hidden);
        $tmp['comment_form_close'] = form_close();
        $this->data['comment_write'] =  $this->data['board']['brd_use_comment'] == 'Y' && $this->data['board']['auth']['comment'] ? $this->load->view($write_skin_path, $tmp, TRUE) : NULL;

        // 댓글 목록
        $list_skin_path = DIR_SKIN . "/board/comment/" . $this->data['board']['brd_skin_c'] . "/c_list";
        if( $this->data['board']['brd_use_comment'] == 'Y' )
        {
            $mem_userid = ($this->member->is_login()) ? $this->member->info('userid') : '';
            $tmp2['comment_list'] = $this->board_model->comment_list($brd_key, $post_idx, $this->data['board']['auth']['admin'], $mem_userid);
            // 각 댓글마다 대댓글 폼을 만든다.
            foreach($tmp2['comment_list']['list'] as &$row)
            {
                unset($tmp);
                $row['comment_form'] = "";
                if(strlen($row['cmt_reply']) < 5)
                {
                    $comment_hidden = array("reurl"=>current_full_url(),"cmt_idx"=>"","cmt_parent"=>$row['cmt_idx']);
                    $comment_action_url = base_url( "board/{$brd_key}/comment/{$post_idx}", SSL_VERFIY ? 'https':'http' );
                    $tmp['comment_view'] = array();
                    $tmp['comment_form_open'] = form_open($comment_action_url,array("data-form"=>"board-comment"), $comment_hidden);
                    $tmp['comment_form_close'] = form_close();
                    $row['comment_form'] =  $this->data['board']['brd_use_comment'] == 'Y' && $this->data['board']['auth']['comment'] ? $this->load->view($write_skin_path, $tmp, TRUE) : NULL;
                }
            }
        }
        $tmp2['board'] = $this->data['board'];
        $this->data['comment_list'] = $this->data['board']['brd_use_comment'] == 'Y' ? $this->load->view($list_skin_path, $tmp2, TRUE) : NULL;

        $this->view = "view";
        $this->skin_type = "board/view";
        $this->skin = $this->data['board']['brd_skin_v'];
    }

    /**
     * 게시판 목록
     * @param $brd_key
     */
    public function lists($brd_key)
    {
        $this->board_common($brd_key, 'list');

        // 메타태그 설정
        $this->site->meta_title         = $this->data['board']['brd_title'] . ' - ' . $this->data['page'] . '페이지'; // 이 페이지의 타이틀
        $this->site->meta_description 	= $this->data['board']['brd_description'];   // 이 페이지의 요약 설명
        $this->site->meta_keywords 		= $this->data['board']['brd_keywords'];   // 이 페이지에서 추가할 키워드 메타 태그
        $this->site->meta_image			= "";   // 이 페이지에서 표시할 대표이미지

        // 레이아웃 & 뷰파일 설정
        $this->view     = "list";

        $this->skin_type = "board/list";
        $this->skin     = $this->data['board']['brd_skin_l'];
    }

    /**
     * 코멘트 등록/수정 처리
     * @param $brd_key
     * @param $post_idx
     * @param string $cmt_idx
     */
    public function comment($brd_key, $post_idx)
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('cmt_content', langs('게시판/comment/form_content'), 'trim|required');

        if( empty($brd_key) OR empty($post_idx) )
        {
            alert(langs('게시판/msg/invalid_access'));
            exit;
        }

        $this->board_common($brd_key,'comment');

        $data['brd_key'] = $brd_key;
        $data['post_idx'] = $post_idx;
        $data['cmt_idx'] = $this->input->post('cmt_idx', TRUE);
        $data['cmt_parent'] = $this->input->post('cmt_parent', TRUE, 0);
        $data['cmt_content'] = $this->input->post('cmt_content', FALSE);
        $data['mem_userid']  = ( $this->member->is_login() ) ? $this->member->info('userid') : '';
        $data['mem_password'] = ( $this->member->is_login() ) ?  $this->member->info('password') : get_password_hash( $this->input->post('mem_password', FALSE) );
        $data['mem_nickname'] = ( $this->member->is_login() ) ? $this->member->info('nickname') : $this->input->post('mem_nickname');
        $data['cmt_modtime'] = date('Y-m-d H:i:s');
        $data['cmt_ip'] = ip2long( $this->input->ip_address() );
        $data['cmt_status'] = 'Y';
        $data['cmt_mobile'] = $this->site->viewmode == DEVICE_MOBILE ? 'Y' : 'N';

        $reurl = $this->input->post('reurl', TRUE, base_url("board/{$brd_key}/{$post_idx}") );

        // 값 유효성 체크
        if( empty($data['cmt_content']) )
        {
            alert(langs('게시판/comment/content_required'));
            exit;
        }

        if( empty($data['mem_nickname']) )
        {
            alert(langs('게시판/comment/nickname_required'));
            exit;
        }

        if( empty($data['mem_password']) )
        {
            alert(langs('게시판/comment/password_required'));
            exit;
        }

        // 신규 등록일경우
        if( empty($data['cmt_idx']) )
        {
            $data['cmt_regtime'] = date('Y-m-d H:i:s');

            if(! empty($data['cmt_parent']))
            {
                $parent = $this->db->where('cmt_idx', $data['cmt_parent'])->where_in('cmt_status', array('Y','B'))->where('post_idx', $data['post_idx'])->get('board_comment')->row_array();

                if(! $parent OR !isset($parent['cmt_idx']) OR ! $parent['cmt_idx']) {
                    alert('답변할 댓글이 없습니다.\\n답변하는 동안 댓글이 삭제되었을 수 있습니다.');
                    exit;
                }

                if($parent['post_idx'] != $data['post_idx']) {
                    alert('댓글을 등록할 수 없습니다.\\n잘못된 방법으로 등록을 요청하였습니다.');
                    exit;
                }

                if(strlen($parent['cmt_reply']) >= 5) {
                    alert('더이상 답변을 달수 없습니다.\\n\\n답변은 5단계 까지만 가능합니다.');
                    exit;
                }

                $reply_len = strlen($parent['cmt_reply']) + 1;

                $begin_reply_char = 'A';
                $end_reply_char = 'Z';
                $reply_number = +1;

                $this->db->select("MAX(SUBSTRING(cmt_reply, {$reply_len}, 1)) AS reply")->from('board_comment')->where('cmt_num', $parent['cmt_num'])->where('post_idx', $data['post_idx'])->where("SUBSTRING(cmt_reply, {$reply_len}, 1) <>", '');
                if($parent['cmt_reply']) $this->db->like('cmt_reply', $parent['cmt_reply'],'after');
                $row = $this->db->get()->row_array();

                $reply_char ="";

                if(!$row['reply']) $reply_char = $begin_reply_char;
                else if ($row['reply'] == $end_reply_char) {
                    alert('더이상 답변을 달수 없습니다.\\n\\n답변은 26개까지만 가능합니다.');
                    exit;
                }
                else $reply_char = chr(ord($row['reply']) + $reply_number);

                $data['cmt_reply'] = $parent['cmt_reply'] . $reply_char;
                $data['cmt_num'] = $parent['cmt_num'];
            }
            else
            {
                $tmp = (int)$this->db->select_max('cmt_num','max')->from('board_comment')->where('post_idx',$data['post_idx'])->get()->row(0)->max;

                $data['cmt_reply'] = "";
                $data['cmt_num'] = $tmp+1;
            }

            if( $this->db->insert('board_comment', $data) )
            {
                // 대댓글을 위한 정보입력
                $cmt_idx = $this->db->insert_id();

                // 포인트 입력처리
                $this->point_process('brd_point_comment', "CMT_WRITE", "댓글 등록", $cmt_idx, FALSE);

                $this->board_model->update_post_comment_count($brd_key, $post_idx);
                alert('댓글 작성이 완료되었습니다.', $reurl);
            }
        }
        // 수정권한이라면
        else
        {
            // 기존 댓글 정보를 가져온다
            $comment = $this->db->where("cmt_idx", $data['cmt_idx'])->where('brd_key', $brd_key)->where('post_idx', $post_idx)->get('board_comment')->row_array();
            if( ! $comment || ! isset($comment['cmt_idx']) || $comment['cmt_idx'] != $data['cmt_idx'] )
            {
                alert(langs('게시판/msg/invalid_comment'));
                exit;
            }

            if(! $this->data['board']['auth']['admin'] )
            {
                // 기존 댓글과 수정권한이 있는지 확인한다.
                if( $comment['mem_userid'] )
                {
                    if( $this->member->is_login() )
                    {
                        if( $this->member->info('userid') != $comment['mem_userid'] )
                        {
                            alert(langs('게시판/msg/comment_modify_unauthorize'));
                            exit;
                        }
                    }
                    else
                    {
                        alert_login();
                        exit;
                    }
                }
                else
                {
                    if( $data['mem_password'] != $comment['mem_password'] )
                    {
                        alert(langs('게시판/msg/invalid_password'));
                        exit;
                    }
                }
            }

            // 수정일 경우는 바뀌어선 안되는 정보들은 unset
            unset($data['brd_key'], $data['post_idx']);

            $this->db->where('brd_key', $brd_key);
            $this->db->where('post_idx', $post_idx);
            $this->db->where('cmt_idx', $data['cmt_idx']);
            if( $this->db->update('board_comment', $data) )
            {
                $this->board_model->update_post_comment_count($brd_key, $post_idx);
                alert_close(langs('게시판/msg/comment_modify_success'), TRUE);
                exit;
            }
        }


        alert(langs('게시판/msg/comment_failed'));
        exit;
    }

    /**
     * 코멘트 수정/삭제 권한 확인
     * @param $comment
     * @return bool
     */
    public function _check_comment_modify($comment)
    {
        if( $this->data['board']['auth']['admin'] ) return TRUE;

        // 댓글 수정/삭제 권한 확인
        if( $comment['mem_userid'] && ! $this->member->is_login() )
        {
            return langs('게시판/msg/comment_modify_unauthorize');
        }
        else if ( $comment['mem_userid'] && $this->member->is_login() && $this->member->info('userid') != $comment['mem_userid'])
        {
            return langs('게시판/msg/comment_modify_unauthorize');
        }

        return TRUE;
    }

    /**
     * 코멘트 수정 폼
     * @param string $cmt_idx
     */
    public function comment_modify($cmt_idx="")
    {
        if( ! $comment = $this->db->where('cmt_idx', $cmt_idx)->where('cmt_status', 'Y')->get('board_comment')->row_array() )
        {
            alert_close(langs('게시판/msg/invalid_comment'));
            exit;
        }

        $this->board_common($comment['brd_key']);

        if( ($result = $this->_check_comment_modify($comment)) !== TRUE )
        {
            alert_close($result);
            exit;
        }

        $this->site->meta_title = "댓글 수정";

        $hidden=array("mem_nickname"=>$comment['mem_nickname'],"cmt_idx"=>$comment['cmt_idx'],"cmt_parent"=>$comment['cmt_parent']);
        $action_url = base_url('board/'.$comment['brd_key'].'/comment/'.$comment['post_idx'].'/'.$cmt_idx, SSL_VERFIY ? 'https':'http');
        $this->data['comment_form_open'] = form_open($action_url, array("id"=>"form-board-comment","data-form"=>"board-comment"), $hidden);
        $this->data['comment_form_close'] = form_close();
        $this->data['comment_view'] = $comment;
        $this->data['is_reply'] = FALSE;

        $this->theme_file = "popup";
        $this->skin_type = "board/comment";
        $this->skin = $this->data['board']['brd_skin_c'];
        $this->view = "c_write";
    }

    /**
     * 댓글 삭제
     * @param $brd_key
     * @param $post_idx
     * @param $cmt_idx
     */
    public function comment_delete($brd_key, $post_idx, $cmt_idx)
    {
        $this->board_common($brd_key);

        if( ! $comment = $this->db->where('cmt_idx', $cmt_idx)->where('cmt_status', 'Y')->get('board_comment')->row_array() )
        {
            alert(langs('게시판/msg/invalid_comment'));
            exit;
        }

        if( ! $this->data['board']['auth']['admin'] )
        {
            if( ($result = $this->_check_comment_modify($comment)) !== TRUE )
            {
                alert($result);
                exit;
            }

            if( empty($comment['mem_userid']) )
            {
                alert(langs('게시판/msg/cannot_delete_guest_comment'));
                exit;
            }
        }

        // 원본 가져오기
        $original = $this->db->where('cmt_idx', $cmt_idx)->get('board_comment')->row_array();
        if(!$original OR !isset($original['cmt_idx']))
            alert('삭제할 원본 댓글이 없습니다.\\ 이미 삭제되엇거나 존재하지 않는 댓글입니다.');


        // 이 댓글의 하위 댓글이 있는지 확인
        $len = strlen($original['cmt_reply']);
        if ($len < 0) $len = 0;
        $comment_reply = substr($original['cmt_reply'], 0, $len);

        $cnt =
            $this->db
                ->select('COUNT(*) AS cnt')
                ->from('board_comment')
                ->like('cmt_reply', $comment_reply,'after')
                ->where('cmt_idx <>', $cmt_idx)
                ->where('cmt_num', $original['cmt_num'])
                ->where('post_idx', $original['post_idx'])
                ->where('cmt_status', 'Y')
                ->where('cmt_parent', $cmt_idx)
                ->get()->row(0)->cnt;


        if($cnt > 0)
            alert('삭제하려는 댓글에 답변이 달려있어 삭제할 수 없습니다.');

        if( $this->db->where('brd_key', $brd_key)->where('post_idx', $post_idx)->where('cmt_idx', $cmt_idx)->set('cmt_status', 'N')->update('board_comment') )
        {
            $this->board_model->update_post_comment_count($brd_key, $post_idx);

            // 댓글등록으로 증가한 포인트가 있다면 다시 감소
            $this->point_cancel("CMT_WRITE",$cmt_idx, "댓글삭제");

            alert(langs('게시판/msg/comment_delete_success'));
            exit;
        }
    }


    /**********************************************************
     *
     * 게시판 암호 확인 페이지
     * @param string $brd_key
     * @param string $post_idx
     *
     *********************************************************/
    function password($brd_key="",$post_idx="")
    {
        $this->board_common($brd_key);
        // 폼검증 라이브러리 로드
        $this->load->library("form_validation");
        // 폼검증 규칙 설정
        $this->form_validation->set_rules("password", langs('게시판/form/password'), "trim|required|min_length[4]|max_length[16]");
        if( $this->form_validation->run() == FALSE )
        {
            $hidden = array("reurl"=>$this->input->get('reurl', TRUE));
            $action_url = base_url("board/{$brd_key}/password/{$post_idx}", SSL_VERFIY ? 'https':'http');
            $this->data['form_open'] = form_open($action_url,array("id"=>"form-post-password","data-form"=>"post-password-form"), $hidden);
            $this->data['form_close']= form_close();

            $this->view = "password";
            $this->skin_type = "board/view";
            $this->skin = $this->data['board']['brd_skin_v'];
        }
        else
        {
            $reurl = $this->input->post("reurl", TRUE, base_url("board/{$brd_key}/{$post_idx}") );
            $password = $this->input->post("password", TRUE);
            $post = $this->board_model->get_post($brd_key, $post_idx);
            if( get_password_hash($password) == $post['mem_password'] )
            {
                $this->session->set_userdata('post_password_'.$post_idx, TRUE);
                redirect($reurl);
                exit;
            }
            else
            {
                alert( langs('게시판/msg/invalid_password') );
                exit;
            }
        }
    }

    /**
     * 수정이나 삭제 권한이 있나 확인한다.
     * @param $brd_key
     * @param $post_idx
     */
    public function _modify_auth($brd_key,  $post_idx="")
    {
        if(empty($post_idx)) return;

        $post = $this->board_model->get_post($brd_key, $post_idx, FALSE);

        // 관리자가 아니라면
        if( ! $this->data['board']['auth']['admin'] )
        {
            // 회원이 작성한 글이라면
            if( ! empty($post['mem_userid'])  )
            {
                if( ! $this->member->is_login() )
                {
                    alert_login( langs('게시판/msg/modify_require_login') );
                    exit;
                }
                else if ( $post['mem_userid'] != $this->member->info('userid') )
                {
                    alert(langs('게시판/msg/modify_unauthorize'));
                    exit;
                }
            }
            else
            {
                if( ! $this->session->userdata('post_password_'.$post_idx) )
                {
                    redirect(base_url("board/{$brd_key}/password/{$post_idx}?reurl=".current_full_url()));
                }
            }
        }
    }

    /**
     * 글쓰기 페이지
     * @param $brd_key
     * @param string $post_idx
     */
    public function write($brd_key, $post_idx="")
    {
        $this->load->library('form_validation');
        $this->board_common($brd_key, 'write');

        // 수정이라면 권한을 확인한다.
        $this->_modify_auth($brd_key, $post_idx);

        $this->form_validation->set_rules('post_title', langs('게시판/form/post_title') ,'required|trim');
        $this->form_validation->set_rules('post_content', langs('게시판/form/post_content'),'required|trim');
        if( ! $this->member->is_login() )
        {
            $this->form_validation->set_rules('mem_nickname', langs('게시판/form/mem_nickname') ,'required|trim');
            $this->form_validation->set_rules('mem_password', langs('게시판/form/password') ,'required|trim|min_length[4]|max_length[16]');
        }


        if( $this->form_validation->run()  != FALSE)
        {
            $this->load->library('upload');

            if( ! $this->member->is_login() )
            {
                // 비회원이고 리캡쳐 설정이 되있을 경우 경우 구글 리캡챠확인
                if( $this->site->config('google_recaptcha_site_key') && $this->site->config('google_recaptcha_secret_key') )
                {
                    $this->load->library('google_recaptcha');
                    $response = $this->input->post('g-recaptcha-response', TRUE);

                    if( empty($response) OR ! $this->google_recaptcha->check_response( $response ) )
                    {
                        alert('자동등록 방지 인증에 실패하였습니다.');
                        exit;
                    }
                }
                // 비회원일이고 수정일 경우 입력한 패스워드와 기존 패스워드 확인
                if( $post_idx )
                {
                    $post = $this->board_model->get_post($brd_key, $post_idx, FALSE);

                    if( get_password_hash( $this->input->post('mem_password', TRUE) ) != $post['mem_password'] )
                    {
                        alert('잘못된 비밀번호 입니다.');
                        exit;
                    }
                }
            }

            // 받아온 값을 정리한다.
            $data['post_title'] = $this->input->post('post_title', TRUE);
            $data['bca_idx'] = (int) $this->input->post('bca_idx', TRUE);
            $data['post_parent'] = $this->input->post('post_parent', TRUE, 0);
            $data['post_secret'] = $this->input->post('post_secret', TRUE, 'N') == 'Y' ? "Y":'N';
            $data['post_content'] = $this->input->post('post_content', FALSE);
            $data['brd_key'] = $brd_key;
            $data['post_modtime'] = date('Y-m-d H:i:s');
            $data['post_html'] = $this->data['use_wysiwyg'] ? 'Y' : 'N';
            $data['post_notice'] = $this->input->post('post_notice', TRUE) == 'Y' ? 'Y' : 'N';
            $data['post_ip'] = ip2long( $this->input->ip_address() );
            $data['post_mobile'] = $this->site->viewmode == DEVICE_MOBILE ? 'Y' : 'N';
            $data['post_keywords'] = $this->input->post('post_keywords', TRUE);
            for($i=1; $i<=9; $i++)
            {
                $data['post_ext'.$i] = $this->input->post('post_ext'.$i, TRUE,'');
            }

            $parent = array();
            if(! empty( $data['post_parent'] ) )
            {
                $parent = $this->board_model->get_post($brd_key, $data['post_parent'], FALSE);
            }

            // 관리자가 아니라면 사용할수 없는 옵션 끄기
            if(! $this->data['board']['auth']['admin'] )
            {
                $data['post_notice'] = 'N';
            }

            if($this->input->post('post_annonymous', TRUE) == 'Y' OR $this->data['board']['brd_use_anonymous'] == 'A')
            {
                $data['mem_nickname'] = "익명";
            }

            // 로그인 상태에 따라 값을 수정
            if( $this->member->is_login() )
            {
                $data['mem_userid'] = $this->member->info('userid');
                $data['mem_nickname'] = $this->member->info('nickname');
                $data['mem_password'] = $this->member->info('password');
            }
            else
            {
                $data['mem_userid'] = '';
                $data['mem_nickname'] = $this->input->post('mem_nickname', TRUE);
                $data['mem_password'] = get_password_hash( $this->input->post('mem_password', TRUE) );
            }

            // 게시판 설정을 이용해서 값 정리
            if( $this->data['board']['brd_use_secret'] == 'N' ) $data['post_secret'] = 'N';
            else if ( $this->data['board']['brd_use_secret'] == 'A' ) $data['post_secret'] = 'Y';
            // 답글인경우 원글이 비밀글이면 답글도 비밀글
            else if ( ! empty($data['post_parent']) && $parent['post_secret'] == 'Y' ) $data['post_secret'] = 'Y';

            // 파일 업로드가 있다면
            if( $this->data['use_attach'] )
            {

                if( isset($_FILES) && isset($_FILES['userfile']) && count($_FILES['userfile']) > 0 )
                {
                    $dir_path = DIR_UPLOAD . "/board/{$brd_key}/".date('Y')."/".date('m');
                    make_dir($dir_path,FALSE);

                    $upload_config['upload_path'] = "./".$dir_path;
                    $upload_config['file_ext_tolower'] = TRUE;
                    $upload_config['allowed_types'] = FILE_UPLOAD_ALLOW;
                    $upload_config['encrypt_name'] = TRUE;

                    $this->load->library("upload", $upload_config);
                    $this->data['upload_array'] = array();

                    // FOR문으로 업로드하기 위해 돌리기
                    $files = NULL;
                    foreach ($_FILES['userfile'] as $key => $value) {
                        foreach ($value as $noKey => $noValue) {
                            $files[$noKey][$key] = $noValue;
                        }
                    }
                    unset($_FILES);

                    // FOR 문 돌면서 정리
                    foreach ($files as $file) {
                        $_FILES['userfile'] = $file;
                        $this->upload->initialize($upload_config);
                        if( ! isset($_FILES['userfile']['tmp_name']) OR ! $_FILES['userfile']['tmp_name']) continue;
                        if (! $this->upload->do_upload('userfile') )
                        {
                            alert('파일 업로드에 실패하였습니다.\\n'.$this->upload->display_errors(' ',' '));
                            exit;
                        }
                        else
                        {
                            $filedata = $this->upload->data();
                            $this->data['upload_array'][] = array(
                                "brd_key" => $brd_key,
                                "att_origin" => $filedata['orig_name'],
                                "att_filename" => $dir_path . "/" . $filedata['file_name'],
                                "att_caption" => $filedata['orig_name'],
                                "att_downloads" => 0,
                                "att_filesize" => $filedata['file_size'] * 1024,
                                "att_image_width" => $filedata['image_width'] ? $filedata['image_width'] : 0,
                                "att_image_height" => $filedata['image_height'] ? $filedata['image_height'] : 0,
                                "att_ext" => $filedata['file_ext'],
                                "att_is_image" => ($filedata['is_image'] == 1) ? 'Y' : 'N',
                                "att_regtime" => date('Y-m-d H:i:s')
                            );
                        }
                    }
                }
            }

            // 첨부파일 삭제가 있다면 삭제한다.
            $del_file = $this->input->post("del_file", TRUE);
            if( $del_file && count($del_file) > 0 )
            {
                foreach($del_file as $att_idx) {
                    $this->board_model->attach_remove($att_idx);
                }
            }

            // 외부이미지를복사 꺼놓음..
            //$data['post_content'] = $this->board_model->copy_external_image($data['post_content'], $this->agent->agent_string());

            // 게시판설정에 관리자승인이 되어있다면 글입력시 자동 미승인상태로
            if( $this->data['board']['brd_use_assign'] == 'Y' && ! PAGE_ADMIN )
            {
                $data['post_assign'] = 'N';
            }

            // 수정이냐 신규냐에 따라 값 설정
            if( empty($post_idx) )
            {
                $data['post_regtime'] = date('Y-m-d H:i:s');
                $data['post_status'] = 'Y';
                $data['post_count_comment'] = 0;
                $data['post_hit'] = 0;

                // 답글인경우
                if(! empty($data['post_parent']))
                {
                    if( strlen($parent['post_reply']) >= 10 )
                    {
                        alert('더 이상 답변하실 수 없습니다.\\n답변은 10단계 까지만 가능합니다.');
                        exit;
                    }

                    $reply_len = strlen($parent['post_reply']) + 1;

                    $begin_reply_char = 'A';
                    $end_reply_char = 'Z';
                    $reply_number = +1;

                    $reply_char = "";

                    $this->db->select("MAX(SUBSTRING(post_reply, {$reply_len}, 1)) AS reply")->from('board_post')->where('post_num', $parent['post_num'])->where('brd_key', $brd_key)->where("SUBSTRING(post_reply, {$reply_len}, 1) <>", '');
                    if($parent['post_reply']) $this->db->like('post_reply', $parent['post_reply'],'after');
                    $row = $this->db->get()->row_array();

                    if(! $row['reply']) {
                        $reply_char = $begin_reply_char;
                    }
                    else if ($row['reply'] == $end_reply_char) {
                        alert("더 이상 답변하실 수 없습니다.\\n답변은 26개 까지만 가능합니다.");
                        exit;
                    }
                    else {
                        $reply_char = chr(ord($row['reply']) + $reply_number);
                    }

                    $data['post_reply'] = $parent['post_reply'] . $reply_char;

                    // 답변의 원글이 비밀글이라면, 비밀번호는 원글과 동일하게 넣는다.
                    if( $parent['post_secret'] == 'Y' ) {
                        $data['mem_password'] = $parent['mem_password'];
                    }

                    $data['post_num'] = $parent['post_num'];
                }
                else {
                    $tmp  = (int)$this->db->select_max('post_num','max')->from('board_post')->where('brd_key',$brd_key)->get()->row(0)->max;
                    $data['post_reply'] = "";
                    $data['post_num'] = $tmp+1;
                }

                if(! $this->db->insert('board_post', $data) )
                {
                    alert(langs('게시판/msg/write_failed'));
                    exit;
                }

                $post_idx = $this->db->insert_id();
            }
            else {
                $this->db->where('brd_key', $brd_key);
                $this->db->where('post_idx', $post_idx);

                if(! $this->db->update('board_post', $data))
                {
                    alert(langs('게시판/msg/write_failed'));
                    exit;
                }

            }

            // 업로드된 데이타가 있을경우에 DB에 기록
            if(isset($this->data['upload_array']) && count($this->data['upload_array']) >0 )
            {
                foreach($this->data['upload_array'] as &$arr) {
                    $arr['post_idx'] = $post_idx;
                }
                $this->db->insert_batch("board_attach", $this->data['upload_array']);
            }

            // 자신의 글은 바로 볼수 있도록
            if( ! $this->member->is_login() )
            {
                $this->session->set_userdata('post_password_'.$post_idx, TRUE);
            }

            if($this->member->is_login())
            {
                $this->point_process('brd_point_write', "POST_WRITE", "게시글 등록", $post_idx, FALSE);
            }

            $insert_data['post_idx'] = $post_idx;
            $insert_data['post_status'] = 'Y';

            alert(langs('게시판/msg/write_success'), base_url("board/{$brd_key}/{$post_idx}"));
            exit;


        }
        else {
            // 수정일경우를 대비해서 글 고유 pk 넘김
            $this->data['post_idx'] = (int)$post_idx;
            $this->data['post_parent'] = $this->input->get('post_parent', TRUE);
            $this->data['view'] = empty($post_idx) ? array() : $this->board_model->get_post($brd_key, $post_idx, FALSE);
            $this->data['parent'] = empty($this->data['post_parent']) ? array() : $this->board_model->get_post($brd_key, $this->data['post_parent'], FALSE);

            if( $this->data['post_idx'] && (! $this->data['view'] OR ! isset($this->data['view']['post_idx']) OR !$this->data['view']['post_idx'] ) )
            {
                alert(langs('게시판/msg/invalid_access'));
                exit;
            }

            $hidden = array();
            // 답글작성일경우 부모 번호 넘겨주기
            if($this->data['post_parent']) {
                $hidden['post_parent'] = $this->data['post_parent'];

                $this->data['view']['post_title'] = "RE : ". $this->data['parent']['post_title'];
            }

            $write_url = base_url("board/{$brd_key}/write" . ($post_idx ? '/'.$post_idx : ''), SSL_VERFIY ? "https":'http');

            $this->data['form_open'] = form_open_multipart($write_url, array("data-form"=>"post", "id"=>"form-post","autocomplete"=>"off"), $hidden);
            $this->data['form_close'] = form_close();

            // 메타태그 설정
            $this->site->meta_title         = $this->data['board']['brd_title'] . ' - '. ((empty($post_idx)) ? '새 글 작성' : '글 수정'); // 이 페이지의 타이틀
            $this->site->meta_description 	= $this->data['board']['brd_description'];   // 이 페이지의 요약 설명
            $this->site->meta_keywords 		= $this->data['board']['brd_keywords'];   // 이 페이지에서 추가할 키워드 메타 태그
            $this->site->meta_image			= "";   // 이 페이지에서 표시할 대표이미지

            // 레이아웃 & 뷰파일 설정
            $this->view     = "write";

            $this->skin_type = "board/write";
            $this->skin     = $this->data['board']['brd_skin_w'];
        }
    }

    /**
     * 첨부파일 다운로드 하기
     * @param $brd_key
     * @param $post_idx
     * @param $bmt_idx
     */
    public function download($brd_key, $post_idx, $att_idx)
    {
        if(empty($brd_key) OR empty($post_idx) OR empty($att_idx))
        {
            alert(langs( 'board/msg/invalid_access' ));
        }

        $this->board_common($brd_key, 'download');

        if(! $att = $this->db->where('att_idx', $att_idx)->where('brd_key', $brd_key)->where('post_idx', $post_idx)->get('board_attach')->row_array())
        {
            alert(langs( 'board/msg/invalid_attach_file' ));
            exit;
        }

        $post = $this->board_model->get_post($brd_key, $post_idx, TRUE);

        $this->point_process('brd_point_download', "POST_ATTACH_DOWNLOAD", "첨부파일 다운로드", $post_idx, ($post['mem_userid'] == $this->member->info('userid')) );

        $this->load->helper('download');
        $data = file_get_contents(FCPATH.$att['att_filename']);
        $name = urlencode($att['att_origin']);
        force_download($name, $data);
    }

    /**
     * 게시판에 관련된 포인트를 처리합니다.
     * @param $type
     * @param $mpo_type
     * @param $msg
     * @param $target_idx
     */
    public function point_process($type, $mpo_type, $msg, $target_idx, $check_writer=FALSE)
    {
        // 첨부파일 다운시 필요한 포인트가 있다면 확인
        if( $this->data['board'][$type] != 0 )
        {
            // 회원일 경우만 실행한다.
            if( $this->member->is_login() )
            {
                // 본인의 것은 실행하지 않는다.
                if( ! $check_writer )
                {
                    // 이미 처리된 포인트 내역이 있는지 확인한다. (포인트는 한번만 차감/등록한다.)
                    $res = (int) $this->db->select('COUNT(*) as cnt')->where('target_type', $mpo_type)->where('target_idx', $target_idx)->where('mem_idx', $this->member->is_login())->get('member_point')->row(0)->cnt;
                    if( $res <= 0)
                    {
                        // 포인트 차감일 경우, 해당 포인트가 있는지 확인한다
                        if( (int)$this->data['board'][$type] < 0)
                        {
                            if( (int)$this->member->info('point') < abs((int)$this->data['board'][$type]) )
                            {
                                alert(langs('회원/point/not_enough') . "({$this->data['board'][$type]})");
                                exit;
                            }
                        }
                        
                        // 포인트 실제 처리
                        $this->member->add_point($this->member->is_login(),$this->data['board'][$type."_flag"], $this->data['board'][$type], FALSE, $mpo_type, $msg, $target_idx);
                    }
                }
            }
            // 비회원일 경우 아예 실행이 불가능하다.
            else
            {
                alert(langs('공통/msg/login_required'));
                exit;
            }
        }
    }

    /**
     * 게시판에 관련된 포인트를 삭제등의 행동시 취소합니다.
     * @param $target_type
     * @param $target_idx
     * @param $msg
     */
    public function point_cancel($target_type, $target_idx, $msg)
    {
        // 댓글등록으로 증가한 포인트가 있다면 다시 감소
        // 포인트 입력처리
        if( $this->member->is_login() )
        {
            $ret = $this->db->where('target_type', $target_type)->where('mem_idx', $this->member->is_login() )->where('target_idx', $target_idx)->where('mpo_value !=','0')->get('member_point')->row_array();
            if( $ret && isset($ret['mpo_value']) && $ret['mpo_value'] != 0 )
            {
                $this->member->add_point($this->member->is_login(),$ret['mpo_flag']*-1, $ret['mpo_value'], FALSE, $target_type, $msg, $target_idx);
            }
        }
    }

    /**
     * 게시글 삭제
     * @param $brd_key
     * @param $post_idx
     */
    public function delete($brd_key, $post_idx)
    {
        $this->board_common($brd_key);
        $this->_modify_auth($brd_key, $post_idx);

        $post = $this->board_model->get_post($brd_key, $post_idx, FALSE);

        $len = strlen($post['post_reply']);
        if( $len < 0 ) $len = 0;
        $reply = substr($post['post_reply'], 0, $len);

        // 게시글에 답글이 달려있는경우 삭제할 수 없다
        $count = (int) $this->db->select('COUNT(*) AS cnt')
            ->where('post_idx <>', $post['post_idx'])
            ->where('post_num', $post['post_num'])
            ->where('brd_key', $post['brd_key'])
            ->like('post_reply', $reply, 'after')
            ->where_in('post_status',array('Y','B'))
            ->get('board_post')
            ->row(0)
            ->cnt;

        if( $count > 1 )
        {
            alert(langs('게시판/msg/cant_delete_because_child'));
            exit;
        }

        if( $this->db->where('post_idx', $post_idx)->set('post_status', 'N')->update('board_post') )
        {
            $this->point_cancel("POST_WRITE", $post_idx, "게시글 삭제");

            alert( langs('게시판/msg/delete_success'), base_url("board/{$brd_key}") );
            exit;
        }
        else
        {
            alert( langs('게시판/msg/delete_failed') );
            exit;
        }
    }

    /**
     * 권한 확인
     * @return array
     */
    private function check_auth()
    {
        $return = array();

        $return['admin'] = ( ( $this->member->is_super() ) OR ( $this->board_model->is_admin($this->data['board']['brd_key'], $this->member->is_login())) );
        $return['read'] = (  $return['admin']  OR ($this->member->level() >= $this->data['board']['brd_lv_read']) );
        $return['list'] = (  $return['admin']  OR ($this->member->level() >= $this->data['board']['brd_lv_list']) );
        $return['write'] = (  $return['admin']  OR ($this->member->level() >= $this->data['board']['brd_lv_write']) );
        $return['upload'] = ( $return['admin'] OR ($this->member->level() >= $this->data['board']['brd_lv_upload']) );
        $return['download'] = ( $return['admin'] OR ($this->member->level() >= $this->data['board']['brd_lv_download']) );
        $return['comment'] = ( $return['admin'] OR ($this->member->level() >= $this->data['board']['brd_lv_comment']) );
        $return['reply'] = ( $return['admin'] OR ($this->member->level() >= $this->data['board']['brd_lv_reply']) );

        return $return;
    }

    /**
     * 게시판마다 공통으로 불러오기
     * @param $brd_key
     * @param string $check_type
     */
    private function board_common($brd_key, $check_type="")
    {
        $this->param = array();

        // 넘어온 값을 정리
        $this->data['board'] = $this->board_model->get_board($brd_key, FALSE);

        if(empty($this->data['board']) OR ! isset($this->data['board']['brd_key']) )
        {
            alert('존재하지 않는 게시판 또는 삭제된 게시판입니다.');
            exit;
        }

        $this->data['board']['auth'] = $this->check_auth();
        $this->data['board']['link'] = $this->board_model->get_link($brd_key);

        // front-end 에서 알아보기쉽게 값을 정리
        $this->data['use_wysiwyg'] = ($this->data['board']['brd_use_wysiwyg'] == 'Y');
        $this->data['use_secret'] = ($this->member->is_login() && $this->data['board']['brd_use_secret'] == 'Y');
        $this->data['use_notice'] = ($this->data['board']['auth']['admin']);
        $this->data['use_category'] = (($this->data['board']['brd_use_category'] == 'Y') && (count($this->data['board']['category']) > 0));
        $this->data['use_attach'] = ($this->data['board']['brd_use_attach'] == 'Y' && $this->data['board']['auth']['upload']);

        // 접속한 기기에 따라 설정을 바꾼다.
        $this->data['board']['brd_skin_l'] = ($this->site->viewmode == DEVICE_MOBILE) ? $this->data['board']['brd_skin_l_m'] : $this->data['board']['brd_skin_l'];
        $this->data['board']['brd_skin_w'] = ($this->site->viewmode == DEVICE_MOBILE) ? $this->data['board']['brd_skin_w_m'] : $this->data['board']['brd_skin_w'];
        $this->data['board']['brd_skin_c'] = ($this->site->viewmode == DEVICE_MOBILE) ? $this->data['board']['brd_skin_c_m'] : $this->data['board']['brd_skin_c'];
        $this->data['board']['brd_skin_v'] = ($this->site->viewmode == DEVICE_MOBILE) ? $this->data['board']['brd_skin_v_m'] : $this->data['board']['brd_skin_v'];
        $this->data['board']['brd_title'] = ($this->site->viewmode == DEVICE_MOBILE) ? ($this->data['board']['brd_title_m']?$this->data['board']['brd_title_m']:$this->data['board']['brd_title']) : $this->data['board']['brd_title'];
        $this->data['board']['brd_page_rows'] = ($this->site->viewmode == DEVICE_MOBILE) ? $this->data['board']['brd_page_rows_m'] : $this->data['board']['brd_page_rows'];
        $this->data['board']['brd_fixed_num'] = ($this->site->viewmode == DEVICE_MOBILE) ? $this->data['board']['brd_fixed_num_m'] : $this->data['board']['brd_fixed_num'];

        unset($this->data['board']['brd_skin_m'], $this->data['board']['brd_title_m'], $this->data['board']['brd_page_rows_m'], $this->data['board']['brd_fixed_num_m']);

        $this->data['category_list'] = ( $this->data['use_category'] && count($this->data['board']['category']) > 0 ) ? $this->data['board']['category'] : NULL;

        // 리스트 불러오기
        $this->param['page'] = $this->data['page'] = (int)$this->input->get('page', TRUE) >= 1 ? $this->input->get('page', TRUE) : 1;
        $this->param['scol'] = $this->data['scol'] = $this->input->get('scol', TRUE);
        $this->param['stxt'] = $this->data['stxt'] = $this->input->get('stxt', TRUE);
        $this->param['category'] = $this->data['category'] = $this->input->get('category', TRUE);

        if( $check_type && ! $this->data['board']['auth'][$check_type] )
        {
            $msg = langs('게시판/msg/list_unauthorize');;
            if( $check_type == 'write' ) $msg = langs('게시판/msg/write_unauthorize');
            else if ($check_type == 'view' || $check_type == 'read') $msg = langs('게시판/msg/read_unauthorize');
            else if ($check_type == 'download') $msg = langs('게시판/msg/download_unauthorize');
            else if ($check_type == 'reply') $msg = langs('게시판/msg/reply_unauthorize');
            else if ($check_type == 'comment') $msg = langs('게시판/msg/comment_unauthorize');

            alert($msg);
            exit;
        }

        $use_list = TRUE;
        if( $check_type == 'download' OR $check_type == 'comment' OR $check_type == 'write' OR $check_type == 'reply' )
        {
            $use_list = FALSE;
        }
        else if( ($check_type == "view" OR $check_type == "read" ) && $this->data['board']['brd_use_view_list'] == 'N' )
        {
            $use_list = FALSE;
        }

        $this->data['list'] = array(
            "list"=>array(),
            "total_count" => 0
        );
        $this->data['pagination'] = "";

        if( $use_list )
        {
            // 게시글 목록 가져오기
            $this->data['list'] = $this->board_model->post_list($this->data['board'], $this->param);

            // 페이지네이션 세팅
            $paging['page'] = $this->param['page'];
            $paging['page_rows'] = $this->data['board']['brd_page_rows'];
            $paging['total_rows'] = $this->data['list']['total_count'];
            $paging['fixed_page_num'] = $this->data['board']['brd_fixed_num'];

            $this->load->library('paging', $paging);
            $this->data['pagination'] = $this->paging->create();
        }

        // 레이아웃 정의
        $this->theme    = $this->site->get_layout();
        $this->skin_type = "board";
        $this->active   = "/board/".$this->data['board']['brd_key'];
    }

}