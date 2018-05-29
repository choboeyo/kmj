<?php
/**
 * Class Statics_model
 * ----------------------------------------------------------
 * 통계 관련 모델
 */
class Statics_model extends WB_Model
{
    function ip_info_update()
    {
        $this->db->where('sta_country_code', '');
        $this->db->where('sta_ip <>', '0');
        $this->db->where('sta_ip <>', '2130706433'); // 127.0.0.1
        $this->db->group_by('sta_ip');
        $result = $this->db->get('statics');
        $list = $result->result_array();

        if( count($list) > 0 )
        {
            foreach($list as $row)
            {
                $ip_info = get_ip_info( long2ip($row['sta_ip']));
                $this->db->where('sta_ip', $row['sta_ip']);
                $this->db->set('sta_country',  $ip_info['country'] );
                $this->db->set('sta_country_code', $ip_info['countryCode']);
                $this->db->set('sta_addr', $ip_info['addr']);
                $this->db->set('sta_org', $ip_info['org']);
                $this->db->update('statics');
            }
        }
    }

    /**
     * 방문통계 목록 구하기
     * @param array $param
     * @return mixed
     */
    function visit_list($param=array())
    {
        $param['select'] = '*, INET_NTOA(sta_ip) AS sta_ip';
        $param['from'] = "statics";
        $param['limit'] = TRUE;
        $param['page_rows'] = element('page_rows', $param, 20);
        $param['page']  = element('page', $param, 1);
        $param['order_by'] = "sta_idx DESC";

        $list = $this->get_list($param);

        // 데이타 가공
        foreach($list['list'] as &$row)
        {
            // Internet Explorer인 경우 버젼도 같이 포함해 줍니다.
            if( strtolower($row['sta_browser']) == 'internet explorer' )
            {
                $row['sta_browser'] .= " " . $row['sta_version'];
            }

            $row['sta_device'] = $row['sta_is_mobile'] == 'Y' ? $row['sta_mobile'] : $row['sta_platform'];
        }

        return $list;
    }

    /**
     * 시간대별 통계
     * @param $startdate
     * @param $enddate
     */
    function statics_times($startdate, $enddate) {
        $return = array();
        $return['sum'] = 0;
        $return['list'] = array();
        $return['total'] = 0;
        $return['hourlist'] = array();
        $return['valuelist'] = array();

        for($i= 0; $i<=23; $i++) {
            $return['list'][ sprintf('%02d',  $i)] = 0;
            $return['hourlist'][] = $i;
        }

        $result =
            $this->db->select("DATE_FORMAT(sta_regtime, '%H') as hour, COUNT(*) AS `count`")
                ->from('statics')
                ->where('sta_regtime >=', $startdate . " 00:00:00")
                ->where('sta_regtime <=', $enddate . " 23:59:59")
                ->group_by("DATE_FORMAT(sta_regtime, '%H')")
                ->order_by("hour ASC")
                ->get();

        $result_array = $result->result_array();
        foreach($result_array as $row)
        {
            $return['list'][$row['hour']] += $row['count'];
            $return['total'] += $row['count'];
        }


        foreach($return['list'] as $value)
        {
            $return['valuelist'][] = $value;
        }
        $return['hourlist'] = json_encode($return['hourlist'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        $return['valuelist'] = json_encode($return['valuelist'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

        return $return;
    }

    /**
     * 기기별 통계
     * @param $startdate
     * @param $enddate
     * @return array
     */
    function statics_device($startdate, $enddate) {
        $return = array(
            "sum" => array(
                "total" => array(
                    "count" => 0
                ),
                "pc" => array(
                    "count" => 0,
                    "percent" => 0
                ),
                "mobile" => array(
                    "count" => 0,
                    "percent" => 0
                )
            ),
            "list" => array(),
            "device_counts" => array(),
            "device_list" => array()
        );

        $startdate_s = new DateTime($startdate); // 20120101 같은 포맷도 잘됨
        $enddate_s = new DateTime($enddate);
        $diff = date_diff($startdate_s, $enddate_s);
        $diff = $diff->days;

        for($i=0; $i<=$diff; $i++)
        {
            $r_date = date('Y-m-d', strtotime("+{$i} days", strtotime($startdate)));
            $return['list'][ $r_date ]['pc'] = 0;
            $return['list'][ $r_date ]['mobile'] = 0;
            $return['list'][ $r_date ]['total'] = 0;
        }

        $result =
            $this->db->select("DATE_FORMAT(sta_regtime, '%Y-%m-%d') as date, sta_is_mobile, COUNT(*) AS `count`")
                ->from('statics')
                ->where('sta_regtime >=', $startdate . " 00:00:00")
                ->where('sta_regtime <=', $enddate . " 23:59:59")
                ->group_by("DATE_FORMAT(sta_regtime, '%Y-%m-%d'), sta_is_mobile")
                ->order_by("date ASC")
                ->get();
        $result_array = $result->result_array();

        foreach($result_array as $row)
        {
            if( $row['sta_is_mobile'] == 'Y' ) {
                $return['sum']['mobile']['count'] += (int)$row['count'];
                $return['list'][ $row['date'] ]['mobile'] = (int)$row['count'];
            }
            else {
                $return['sum']['pc']['count'] += (int)$row['count'];
                $return['list'][ $row['date'] ]['pc'] = (int)$row['count'];
            }

            $return['sum']['total']['count'] += (int)$row['count'];
            $return['list'][ $row['date'] ]['total'] += (int)$row['count'];
        }

        if( $return['sum']['total']['count'] > 0 )
        {
            $return['sum']['pc']['percent'] = round($return['sum']['pc']['count'] /  $return['sum']['total']['count'] * 100, 2);
            $return['sum']['mobile']['percent'] = round($return['sum']['mobile']['count'] /  $return['sum']['total']['count'] * 100, 2);
        }

        // 모바일 기기별
        $result =
            $this->db->from('statics')
                ->select('sta_mobile, COUNT(*) AS count')
                ->where('sta_regtime >=', $startdate . " 00:00:00")
                ->where('sta_regtime <=', $enddate . " 23:59:59")
                ->where('sta_mobile <>', '')
                ->group_by('sta_mobile')
                ->order_by('count DESC')
                ->get();
        $list = $result->result_array();

        foreach($list as $row)
        {
            $return['device_list'][] = $row['sta_mobile'];
            $return['device_counts'][] = (int)$row['count'];
        }

        $return['device_list'] = json_encode($return['device_list'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        $return['device_counts'] = json_encode($return['device_counts'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

        return $return;
    }

    /**
     * 그룹별 통계
     * @param $column
     * @param $startdate
     * @param $enddate
     * @return mixed
     */
    function statics_group($column, $startdate, $enddate) {
        $result =
            $this->db->from('statics')
                ->select($column.', COUNT(*) AS count')
                ->where('sta_regtime >=', $startdate . " 00:00:00")
                ->where('sta_regtime <=', $enddate . " 23:59:59")
                ->where("{$column} <>",'')
                ->group_by($column)
                ->order_by('count DESC')
                ->get();

        $return['list'] = $result->result_array();
        $return[$column] = array();
        $return['counts'] = array();
        $return['total'] = 0;

        foreach($return['list'] as $row)
        {
            $return[$column][] = $row[$column];
            $return['counts'][] = (int)$row['count'];
            $return['total'] += (int)$row['count'];
        }

        $return[$column] = json_encode($return[$column], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $return['counts'] = json_encode($return['counts'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

        return $return;
    }
}