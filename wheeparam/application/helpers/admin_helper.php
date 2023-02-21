<?php
function display_member_menu($mem_idx, $mem_name, $mem_status='Y')
{
    $CI =& get_instance();

    $str = '<div class="btn-group ML10 ">';
    $str .= '<button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown">'.$mem_name.' <i class="fas fa-angle-down"></i></button>';
    $str .= '<ul class="dropdown-menu dropdown-menu-dark">';
    $str .= '<li><a href="javascript:;" onclick="APP.MEMBER.POP_INFO_ADMIN('.$mem_idx.');">회원정보</a></li>';
    $str .= '<li><a href="javascript:;" onclick="APP.MEMBER.POP_MODIFY_ADMIN('.$mem_idx.');">정보수정</a></li>';
    $str .= '<li><a href="javascript:;" onclick="APP.MEMBER.POP_PASSWORD_ADMIN('.$mem_idx.');">비밀번호 변경</a></li>';

    if( $mem_status != 'N')
    {
        $str .= '<li class="divider"></li>';
    }

    if( $mem_status == 'Y' )
    {
        $str .= '<li><a href="#" onclick="APP.MEMBER.STATUS_CHANGE('.$mem_idx.',\'Y\',\'H\');">휴면처리</a></li>';
        $str .= '<li><a href="#" onclick="APP.MEMBER.STATUS_CHANGE('.$mem_idx.',\'Y\',\'D\');">로그인금지</a></li>';
    }
    else if ( $mem_status == 'H')
    {
        $str .= '<li><a href="#" onclick="APP.MEMBER.STATUS_CHANGE('.$mem_idx.',\'H\',\'Y\');">휴면해제</a></li>';
    }
    else if ( $mem_status == 'D' )
    {
        $str .= '<li><a href="#" onclick="APP.MEMBER.STATUS_CHANGE('.$mem_idx.',\'D\',\'Y\');">로그인금지 해제</a></li>';
    }

    if( $mem_status != 'N')
    {
        $str .= '<li><a href="#" onclick="APP.MEMBER.STATUS_CHANGE('.$mem_idx.',\''.$mem_status.'\',\'N\');">회원탈퇴</a></li>';
    }

    $str .= '<li class="divider"></li>';
    $str .= '<li><a href="'.base_url('admin/members/log?sc=idx&st='.$mem_idx).'">로그인 기록</a></li>';

    if( $CI->site->config('point_use') == 'Y' ) {
        $str .= '<li><a href="javascript:;" onclick="APP.MEMBER.POP_POINT_ADMIN('.$mem_idx.');">'.$CI->site->config('point_name').' 관리</a></li>';
        $str .= '<li><a href="javascript:;" onclick="APP.MEMBER.POP_POINT_FORM_ADMIN('.$mem_idx.');">'.$CI->site->config('point_name').' 추가</a></li>';
    }


    $str .= '</ul>';
    $str .= '</div>';
    return $str;
}

function get_skin_list( $skin_type="")
{
    $skin_path = VIEWPATH . DIRECTORY_SEPARATOR . DIR_SKIN . DIRECTORY_SEPARATOR . $skin_type;

    $CI =& get_instance();

    $CI->load->helper('directory');
    $map = directory_map($skin_path, 1);

    $return = array();
    if( is_array($map)) {
        foreach($map as $skins)
        {
            $return[] = str_replace(DIRECTORY_SEPARATOR , "", $skins);
        }
    }
    return $return;
}

function get_item_group() {

}