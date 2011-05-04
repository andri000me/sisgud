<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sisgud {
    /**
    *Nampilin tanggal sekarang
    **/
    function get_date()
    {
        $date= date('d').' ';        
        $month = date('m');
        switch($month)
        {
            case '01': $date .= 'Januari';break;
            case '02': $date .= 'Februari';break;
            case '03': $date .= 'Maret';break;
            case '04': $date .= 'April';break;
            case '05': $date .= 'Mei';break;
            case '06': $date .= 'Juni';break;
            case '07': $date .= 'Juli';break;
            case '08': $date .= 'Agustus';break;
            case '09': $date .= 'September';break;
            case '10': $date .= 'Oktober';break;
            case '11': $date .= 'November';break;
            case '12': $date .= 'Desember';break;
        }
        return $date.' '.date('Y');
    }
    /**
    *Nampilin userinfo
    **/
    function get_userinfo()
    {
        $query = $this->db->get_where('operator',array('op_id'=>$this->session->userdata('p_id')));
        $operator = $query->row();
        return ucwords($operator->op_name);
    }
}

/* End of file sisgud.php */
/* Location: ./system/application/libraries/sisgud.php */