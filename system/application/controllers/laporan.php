<?php
/**
*Name : Supplier
*Author : PuRwa
*version : 1.0 beta
*Description: This class is used to handle management of supplier
*/
class Laporan extends Controller {
	var $data;
	/**
	*Class constructor
	*/
	function Laporan()
	{
		parent::Controller();
		$this->load->helper(array('html','form','url'));
		$link1 = array(
			'href' => 'css/default.css',
			'rel' => 'stylesheet',
			'type' => 'text/css',
			'media' => 'screen'
		);
		$link2 = array(
			'href' => 'css/menu.css',
			'rel' => 'stylesheet',
			'type' => 'text/css',
			'media' => 'screen'
		);
		$this->data['link_tag'] = link_tag($link1).link_tag($link2);
		$this->data['page_title'] = 'Sistem Inventori Gudang';
		$this->data['pages']='supplier';
        $this->data['lib_js'] = '	<script src="'.base_url().'lib/jquery-1.4.4.min.js"></script>
                        <script src="'.base_url().'lib/jquery-ui-1.8.7.custom.min.js"></script>
						<script src="'.base_url().'lib/jquery.autocomplete.js"></script>						
						<script src="'.base_url().'lib/config.js"></script>						
						<script src="'.base_url().'lib/functions.js"></script>	';
        if($this->session->userdata('logged_in') != TRUE)
        {
             redirect('/home/', 'refresh');
        }
        $this->load->helper('sisgud');
        $this->data['now_date'] = get_date();
        $this->data['userinfo'] = get_userinfo($this);
	}
	/**
	*Default function index. First call by class
	*/
	function index()
	{
		
		$this->load->view('index',$this->data);
	}    

    /**
     * Laporan barang masuk
     * - per supplier
     * - per kelompok barang
     */
    function masuk()
    {
        if($this->input->post('submit_report_display') || $this->input->post('submit_report_print'))
        {

            $param['from'] = $this->input->post('date_from');
            $param['to'] = $this->input->post('date_to');
            $type = $this->input->post('type');
            $this->load->model('item_mutasi');
            //per supplier
            if($type == 1)
            {
                //echo 'tes';
                $param['sup_code'] = $this->input->post('sup_id');
                $this->load->model('supplier');
                $query = $this->supplier->get_supplier($param['sup_code']);
                $supplier = $query->row();

                $query = $this->item_mutasi->stat_item_mutasi_sup($param);
                $head = '<br /><h3 style="text-align: center; font-size: 14px">LAPORAN DATA BARANG MASUK</h3>
                        <table class="table-form">
                            <tr>
                                <td style="width: 80px;">Supplier</td>
                                <td>: '.ucwords($supplier->sup_name).' ('.$supplier->sup_code.')</td>
                            </tr>
                            <tr>
                                <td style="width: 80px;">Periode</td>
                                <td>: '.$this->date_to_string($param['from']).' s.d. '.$this->date_to_string($param['to']).'</td>
                            </tr>
                        </table><br />
                        <table class="table-data" style="width: 500px;border: 1px solid;text-align: center;margin: 0px auto;" cellpadding="2" cellspacing="0">
                            <tr>
                                <td class="header" style="font-weight: bold; width: 50px; background-color: #dedede; text-align:center; border: 1px solid;">No</td>
                                <td class="header" style="font-weight: bold; width: 110px; background-color: #dedede; text-align:center; border: 1px solid;">Tanggal</td>
                                <td class="header" style="font-weight: bold; width: 110px; background-color: #dedede; text-align:center; border: 1px solid;">Kelompok Barang</td>
                                <td class="header" style="font-weight: bold; width: 110px; background-color: #dedede; text-align:center; border: 1px solid;">Qty Masuk</td>
                                <td class="header" style="font-weight: bold; width: 120px; background-color: #dedede; text-align:center; border: 1px solid;">Rupiah</td>
                            </tr>';
                $footer = '</table>';
                if($query->num_rows() > 0)
                {
                    $temp='';$i=0;$body=array();
                    $total_masuk=0;
                    $total_rupiah=0;
                    foreach($query->result() as $row)
                    {
                        $temp .= '<tr>
                            <td style="width: 50px; border: 1px solid;">'.++$i.'</td>
                            <td style="width: 110px; border: 1px solid;">'.$this->date_to_string($row->date_entry).'</td>
                            <td style="width: 110px; border: 1px solid;">'.$row->cat_code.'</td>
                            <td style="width: 110px; border: 1px solid;text-align:right;">'.number_format($row->masuk).' &nbsp; </td>
                            <td style="width: 120px; border: 1px solid;text-align:right;">'.number_format($row->rupiah).' &nbsp; </td>
                        </tr>';
                        if($i%50 == 0)
                        {
                            $body[] = $temp;
                            $temp = '';
                        }
                        $total_masuk +=$row->masuk;
                        $total_rupiah +=$row->rupiah;
                    }
                    $row_total = '<tr>
                        <td colspan="3" style="width: 270px; border: 1px solid;">TOTAL</td>
                        <td style="width: 110px; border: 1px solid;text-align:right;">'.number_format($total_masuk).' &nbsp; </td>
                        <td style="width: 120px; border: 1px solid;text-align:right;">'.number_format($total_rupiah).' &nbsp; </td>
                    </tr>';
                    $body[]=$temp;
                    $this->data['table'] = $head.$body[0].$row_total.$footer;
                    if($this->input->post('submit_report_print'))
                    {
                        $this->cetak_pdf(6,$head,$body, $row_total,$footer);
                    }
                }
            }
            //per kelompok barang
            else if($type == 2)
            {
                $query = $this->item_mutasi->stat_item_mutasi_cat($param);
                //var_dump($query->result());exit;
                $head = '<br /><h3 style="text-align: center; font-size: 14px">LAPORAN DATA BARANG MASUK</h3>
                        <table class="table-form">
                            <tr>
                                <td style="width: 80px;">Periode</td>
                                <td>: '.$this->date_to_string($param['from']).' s.d. '.$this->date_to_string($param['to']).'</td>
                            </tr>
                        </table><br />
                        <table class="table-data" style="width: 500px;border: 1px solid;text-align: center;margin: 0px auto;" cellpadding="2" cellspacing="0">
                            <tr>
                                <td class="header" style="font-weight: bold; width: 50px; background-color: #dedede; text-align:center; border: 1px solid;">No</td>
                                <td class="header" style="font-weight: bold; width: 110px; background-color: #dedede; text-align:center; border: 1px solid;">Kelompok Barang</td>
                                <td class="header" style="font-weight: bold; width: 110px; background-color: #dedede; text-align:center; border: 1px solid;">Stok Gudang</td>
                                <td class="header" style="font-weight: bold; width: 110px; background-color: #dedede; text-align:center; border: 1px solid;">Qty Masuk</td>
                                <td class="header" style="font-weight: bold; width: 120px; background-color: #dedede; text-align:center; border: 1px solid;">Rupiah Seluruh</td>
                            </tr>';
                $footer = '</table>';
                if($query->num_rows() > 0)
                {
                    $temp='';$i=0;$body=array();
                    $total_stok=0;
                    $total_masuk=0;
                    $total_rupiah=0;
                    foreach($query->result() as $row)
                    {
                        $temp .= '<tr>
                            <td style="width: 50px; border: 1px solid;">'.++$i.'</td>
                            <td style="width: 110px; border: 1px solid;">'.$row->cat_code.'</td>
                            <td style="width: 110px; border: 1px solid;text-align:right;">'.number_format($row->stok_gudang).' &nbsp; </td>
                            <td style="width: 110px; border: 1px solid;text-align:right;">'.number_format($row->masuk).' &nbsp; </td>
                            <td style="width: 120px; border: 1px solid;text-align:right;">'.number_format($row->rupiah).' &nbsp; </td>
                        </tr>';
                        if($i%50 == 0)
                        {
                            $body[] = $temp;
                            $temp = '';
                        }
                        $total_stok += $row->stok_gudang;
                        $total_masuk += $row->masuk;
                        $total_rupiah += $row->rupiah;
                    }
                    $row_total = '<tr>
                        <td colspan="2" style="width: 160px; border: 1px solid;">TOTAL</td>
                        <td style="width: 110px; border: 1px solid;text-align:right;">'.number_format($total_stok).' &nbsp; </td>
                        <td style="width: 110px; border: 1px solid;text-align:right;">'.number_format($total_masuk).' &nbsp; </td>
                        <td style="width: 120px; border: 1px solid;text-align:right;">'.number_format($total_rupiah).' &nbsp; </td>
                    </tr>';
                    $body[] = $temp;
                    $this->data['table'] = $head.$body[0].$row_total.$footer;
                    if($this->input->post('submit_report_print'))
                    {
                        $this->cetak_pdf(6,$head,$body, $row_total, $footer);
                    }
                }
            }
        }
        $this->data['script']="
            $('#report_type').change(function(){
                var type = $('#report_type').val();
                if(type == 1) {
                    $('#supplier').css('display','table-row');
                    $('#category').css('display','none');
                }
                else if(type == 2) {
                    $('#supplier').css('display','none');
                    $('#category').css('display','table-row');
                }
            });
        ";
        $this->load->view(config_item('template').'laporan_masuk',$this->data);
    }

    function distribusi()
    {
        if($this->input->post('submit_report_display') || $this->input->post('submit_report_print'))
        {
            $param['from'] = $this->input->post('date_from');
            $param['to'] = $this->input->post('date_to');
            $type = $this->input->post('type');
            $param['shop_code'] = $this->input->post('shop_code');
            $this->load->model('shop');
            $query = $this->shop->get_shop($param['shop_code']);
            $shop = $query->row();
            //per supplier
            $this->load->model('item_distribution');
            if($type == 1)
            {

                $query = $this->item_distribution->stat_item_dist_sup($param);
                $head = '<br /><h3 style="text-align: center; font-size: 14px">LAPORAN DISTRIBUSI BARANG</h3>
                        <table class="table-form">
                            <tr>
                                <td style="width: 80px;">Toko</td>
                                <td>: '.ucwords($shop->shop_name).' ('.$shop->shop_code.')</td>
                            </tr>
                            <tr>
                                <td style="width: 80px;">Periode</td>
                                <td>: '.$this->date_to_string($param['from']).' s.d. '.$this->date_to_string($param['to']).'</td>
                            </tr>
                        </table><br />
                        <table class="table-data" style="width: 510px;border: 1px solid;text-align: center;margin: 0px auto;" cellpadding="2" cellspacing="0">
                            <tr>
                                <td class="header" style="font-weight: bold; width: 40px; background-color: #dedede; text-align:center; border: 1px solid;">No</td>
                                <td class="header" style="font-weight: bold; width: 130px; background-color: #dedede; text-align:center; border: 1px solid;">Supplier</td>
                                <td class="header" style="font-weight: bold; width: 50px; background-color: #dedede; text-align:center; border: 1px solid;">Qty</td>
                                <td class="header" style="font-weight: bold; width: 110px; background-color: #dedede; text-align:center; border: 1px solid;">Rupiah</td>
                                <td class="header" style="font-weight: bold; width: 190px; background-color: #dedede; text-align:center; border: 1px solid;">Keterangan</td>
                            </tr>';
                $footer = '</table>';
                if($query->num_rows() > 0)
                {
                    $body=array();$temp='';$i=0;
                    $total_qty=0;$total_rupiah=0;
                    $this->load->model('supplier');
                    foreach($query->result() as $row)
                    {
                        $sup = $this->supplier->get_sup_data($row->sup_code);
                        $temp .= '<tr>
                            <td style="width: 40px; border: 1px solid;">'.++$i.'</td>
                            <td style="width: 130px; border: 1px solid;text-align:left;">'.$sup->sup_code.' ('.substr($sup->sup_name, 0, 20).')</td>
                            <td style="width: 50px; border: 1px solid;text-align:right;">'.number_format($row->qty).' &nbsp; </td>
                            <td style="width: 110px; border: 1px solid;text-align:right;">'.number_format($row->rupiah).' &nbsp; </td>
                            <td style="width: 190px; border: 1px solid;text-align:right;"></td>
                        </tr>';
                        if($i%50 == 0)
                        {
                            $body[] = $temp;
                            $temp = '';
                        }
                        $total_qty += $row->qty;
                        $total_rupiah += $row->rupiah;
                    }
                    $body[] = $temp;
                    $row_total = '<tr>
                        <td colspan="2" style="width: 170px; border: 1px solid;">TOTAL</td>
                        <td style="width: 50px; border: 1px solid;text-align:right;">'.number_format($total_qty).' &nbsp; </td>
                        <td style="width: 110px; border: 1px solid;text-align:right;">'.number_format($total_rupiah).' &nbsp; </td>
                        <td style="width: 190px; border: 1px solid;">&nbsp;</td>
                    </tr>';
                    $this->data['table'] = $head.$body[0].$row_total.$footer;
                    if($this->input->post('submit_report_print'))
                    {
                        $this->cetak_pdf(6,$head,$body,$row_total,$footer);
                    }
                }
            }
            //per kelompok barang
            else if($type == 2)
            {
                $query = $this->item_distribution->stat_item_dist_cat($param);
                $head = '<br /><h3 style="text-align: center; font-size: 14px">LAPORAN DISTRIBUSI BARANG</h3>
                        <table class="table-form">
                            <tr>
                                <td style="width: 80px;">Toko</td>
                                <td>: '.ucwords($shop->shop_name).' ('.$shop->shop_code.')</td>
                            </tr>
                            <tr>
                                <td style="width: 80px;">Periode</td>
                                <td>: '.$this->date_to_string($param['from']).' s.d. '.$this->date_to_string($param['to']).'</td>
                            </tr>
                        </table><br />
                        <table class="table-data" style="width: 510px;border: 1px solid;text-align: center;margin: 0px auto;" cellpadding="2" cellspacing="0">
                            <tr>
                                <td class="header" style="font-weight: bold; width: 40px; background-color: #dedede; text-align:center; border: 1px solid;">No</td>
                                <td class="header" style="font-weight: bold; width: 130px; background-color: #dedede; text-align:center; border: 1px solid;">Kelompok Barang</td>
                                <td class="header" style="font-weight: bold; width: 50px; background-color: #dedede; text-align:center; border: 1px solid;">Qty</td>
                                <td class="header" style="font-weight: bold; width: 110px; background-color: #dedede; text-align:center; border: 1px solid;">Rupiah</td>
                                <td class="header" style="font-weight: bold; width: 190px; background-color: #dedede; text-align:center; border: 1px solid;">Keterangan</td>
                            </tr>';
                $footer = '</table>';
                if($query->num_rows() > 0)
                {
                    $body=array();$temp='';$i=0;
                    $total_qty=0;$total_rupiah=0;
                    //$this->load->model('supplier');
                    foreach($query->result() as $row)
                    {
                        //$sup = $this->supplier->get_sup_data($row->sup_code);
                        $temp .= '<tr>
                            <td style="width: 40px; border: 1px solid;">'.++$i.'</td>
                            <td style="width: 130px; border: 1px solid;text-align:left;">'.$row->cat_code.'</td>
                            <td style="width: 50px; border: 1px solid;text-align:right;">'.number_format($row->qty).' &nbsp; </td>
                            <td style="width: 110px; border: 1px solid;text-align:right;">'.number_format($row->rupiah).' &nbsp; </td>
                            <td style="width: 190px; border: 1px solid;text-align:right;"></td>
                        </tr>';
                        if($i%50 == 0)
                        {
                            $body[] = $temp;
                            $temp = '';
                        }
                        $total_qty += $row->qty;
                        $total_rupiah += $row->rupiah;
                    }
                    $body[] = $temp;
                    $row_total = '<tr>
                        <td colspan="2" style="width: 170px; border: 1px solid;">TOTAL</td>
                        <td style="width: 50px; border: 1px solid;text-align:right;">'.number_format($total_qty).' &nbsp; </td>
                        <td style="width: 110px; border: 1px solid;text-align:right;">'.number_format($total_rupiah).' &nbsp; </td>
                        <td style="width: 190px; border: 1px solid;">&nbsp;</td>
                    </tr>';
                    $this->data['table'] = $head.$body[0].$row_total.$footer;
                    if($this->input->post('submit_report_print'))
                    {
                        $this->cetak_pdf(6,$head,$body,$row_total,$footer);
                    }
                }
            }
        }
        $this->data['list_shop'] = $this->list_shop();
        $this->load->view(config_item('template').'laporan_distribusi',$this->data);
    }

    function list_shop()
    {
        $query = $this->db->query('select * from shop where shop_cat != "OBRAL" and shop_cat != "RUSAK"');
        $list_toko ='<select name="shop_code" style="padding:0;margin:0;height:22px;"><option>--Pilih--</option>';
        //processing query result
        if($query->num_rows())
        {
            foreach($query->result() as $row)
            {
                $list_toko .= '<option value="'.$row->shop_code.'">'.ucwords($row->shop_name).'</option>';
            }
            $list_toko .= '</select>';
        }
        return $list_toko;
    }

    function date_to_string($tgl)
    {
        $month = array('','Januari','Februari','Maret','April','Mei','Juni','Juli',
        'Agustus','September','Oktober','November','Desember');
        $tmp = explode('-',$tgl);

        return $tmp[2].' '.$month[intval($tmp[1])].' '.$tmp[0];
    }

    /*
   **Funngsi cetak pdf
   */
    function cetak_pdf($opsi,$head,$row,$row_total,$foot)
    {
        require_once('lib/tcpdf/config/lang/eng.php');
        require_once('lib/tcpdf/tcpdf.php');

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('PuRwa ReN');
        $pdf->SetTitle('Laporan Sisgud');
        $pdf->SetSubject('Laporan');
        $pdf->SetKeywords('Penjualan, Barang, Harga');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(10);

        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set font
        //$pdf->SetFont('dejavusans', '', 8);
        //$size = array(216,165);
        if($opsi == 1) //cetak laporan untuk penjualan harian
        {
            $pdf->SetFont('dejavusans', '', 8);
            $pdf->setPageFormat('A4','L');
        }
        else if($opsi == 2) //cetak laporan akumulasi
        {
            $pdf->SetFont('dejavusans', '', 8);
            $pdf->setPageFormat('A4','P');
        }
        else if($opsi == 3)
        {
            $pdf->setPageUnit('mm');
            $size = array(216,165);
            $pdf->SetFont('dejavusans', '', 8);
            $pdf->setPageFormat($size, 'P');
        }
        else if($opsi == 4)
        {
            //set font
            $pdf->SetFont('dejavusans', '', 10);
            $pdf->setPageUnit('mm');
            $size = array(216,330);
            $pdf->setPageFormat($size,'L');

        }
        else if($opsi == 5)
        {
            $pdf->SetFont('dejavusans', '', 8);
            $pdf->setPageFormat('A4','P');
        }
        else if($opsi == 6) //cetak laporan rekap
        {
            $pdf->SetFont('dejavusans', '', 8);
            $size = array(216,330);
            $pdf->setPageFormat($size,'P');
        }
        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        //set some language-dependent strings
        $pdf->setLanguageArray($l);

        // ---------------------------------------------------------

        $i = 0;
        $j = 0;
        if($opsi == 1)
        {
            foreach($row as $data)
            {
                // add a page
                $pdf->AddPage();
                if($i == (count($row) - 1))
                {
                    $foot = $row_total.$foot;
                }
                $pdf->writeHTML($head.$data.$foot, true, 0, true, 0);
                $i++;
            }
        }
        if($opsi == 2)
        {
            //acc per kode label
            foreach($row[0] as $data)
            {
                // add a page
                $pdf->AddPage();
                $foot1 = $foot;
                if($i == (count($row[0]) - 1))
                {
                    $foot1 = $row_total[0].$foot;
                }
                $pdf->writeHTML($head[0].$data.$foot1, true, 0, true, 0);
                $i++;
            }
            //acc per kel barang
            foreach($row[1] as $data)
            {
                $pdf->AddPage();
                $foot2 = $foot;
                if($j == (count($row[1]) - 1))
                {
                    $foot2 = $row_total[1].$foot;
                }
                $pdf->writeHTML($head[1].$data.$foot2, true, 0, true, 0);
                $j++;
            }
        }
        if($opsi == 3 || $opsi == 5)
        {

            foreach($row as $data)
            {
                if($i == (count($row) - 1))
                {
                    $foot = $row_total.$foot;
                }
                $pdf->AddPage();
                $pdf->writeHTML($head.$data.$foot, true, 0, true, 0);
                $i++;
            }
        }
        if($opsi == 4)
        {
            $i = 0;
            foreach($row as $data)
            {
                $pdf->AddPage();
                if($i == count($row)-1)
                {
                    $pdf->writeHTML($head.$data.$row_total.$foot, true, 0, true, 0);
                }
                else
                {
                    $pdf->writeHTML($head.$data.'</table>', true, 0, true, 0);
                }
                $i++;
            }
        }
        if($opsi == 6)
        {
            //acc per kode label
            foreach($row as $data)
            {
                // add a page
                $pdf->AddPage();
                $foot1 = $foot;
                if($i == (count($row) - 1))
                {
                    $foot1 = $row_total.$foot;
                }
                $pdf->writeHTML($head.$data.$foot1, true, 0, true, 0);
                $i++;
                //break;
            }
        }
        // ---------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('Laporan.pdf', 'I');

    }

}
/* End of file gudang.php */
/* Location: ./system/application/controllers/gudang.php */
	
