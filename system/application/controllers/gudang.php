<?php
/**
*Name : Gudang
*Author : PuRwa
*version : 1.0 beta
*Description: This class is used to handle transcation which take place on storehouse
*/
class Gudang extends Controller {
	var $data;
	/**
	*Class constructor
	*/
	function Gudang()
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
		$link3 = array(
			'href' => 'css/jquery.autocomplete.css',
			'rel' => 'stylesheet',
			'type' => 'text/css',
			'media' => 'screen'
		);
        $link4 = array(
			'href' => 'css/ui-lightness/jquery-ui-1.8.1.custom.css',
			'rel' => 'stylesheet',
			'type' => 'text/css',
			'media' => 'screen'
		);
		$this->data['link_tag'] = link_tag($link1).link_tag($link2).link_tag($link3).link_tag($link4);
		$this->data['page_title'] = 'Sistem Inventori Gudang';
		$this->data['pages']='gudang';
        $this->data['lib_js'] = '	<script src="'.base_url().'lib/jquery-1.4.4.min.js"></script>
                        <script src="'.base_url().'lib/jquery-ui-1.8.7.custom.min.js"></script>
						<script src="'.base_url().'lib/jquery.autocomplete.js"></script>
						<script src="'.base_url().'lib/jquery.tinywatermark-2.1.0.min.js"></script>						
						<script src="'.base_url().'lib/config.js"></script>						
						<script src="'.base_url().'lib/functions.js"></script>						
					';
        if($this->session->userdata('logged_in') != TRUE)
        {
             redirect('/home/', 'refresh');
        }
        $this->load->helper('sisgud');
        $this->data['now_date'] = get_date();
        $this->data['userinfo'] = get_userinfo($this);
        
        //set time zone
        ini_set('date_default_timezone', config_item('timezone'));
	}
	/**
	*Default function index. First call by class
	*/
	function index()
	{
		
		$this->load->view('index',$this->data);
	}   	
	/**
	*Method for make a record of item, in or out
	*/
	function mutasi()
	{
		
		if($this->uri->segment(3)=='masuk')
		{
			if($this->input->post('submit_mutasi_masuk')) 
			{
				//ambil data-data dari form
				$this->data['sup_code'] = $this->input->post('sup_code');
				$this->data['op_code'] = $this->session->userdata('p_id');           
				$this->data['cat_code'] = $this->input->post('cat_code');
				$this->data['item_name'] = $this->input->post('item_name');
				$this->data['item_hp'] = $this->input->post('item_hp');
				$this->data['item_qty'] = $this->input->post('item_qty');
                $this->data['date_bon'] = $this->input->post('date_bon');
				//check whether sup_code exist or not
                $this->load->model('supplier');
				$query = $this->supplier->get_supplier($this->data['sup_code']);
				if($query->num_rows() > 0)
				{
                    $this->insert_mutasi_masuk();
                }
				else
				{
					$this->data['form_notify_supplier'] = '<span style="color:red">Belum memasukkan kode supplier</span>';
				}
			}            
			$this->data['page_title'] .= ' :. Mutasi Masuk';           		               
			$this->load->view(config_item('template').'gud_mutasimasuk',$this->data);
		}
		else if($this->uri->segment(3)=='keluar' || $this->uri->segment(3)=='obral' || $this->uri->segment(3)=='rusak')
		{
			//loading model
            $this->data['opsi'] = $this->uri->segment(3);
            $this->load->model('shop');            
            //get all the  shop
            if($this->uri->segment(3)=='keluar')
                $query = $this->shop->get_shop();
            else if($this->uri->segment(3)=='obral')
                $query = $this->shop->get_shop_by_cat('OBRAL');
            else if($this->uri->segment(3)=='rusak')
                $query = $this->shop->get_shop_by_cat('RUSAK');
            if($query->num_rows() > 0)
            {
                //setting the initial shop,
                $this->data['shop_count'] = $query->num_rows();
                $shop = '';
                $row_qty = '';
                $i=0;
                foreach($query->result() as $row)
                {
                    $shop .= '<td class="header">'.strtoupper($row->shop_initial).'</td>';
                    $row_qty .= '<td><input type="text" class="'.strtolower($row->shop_initial).'_marked" name="qty_'.strtolower($row->shop_initial).'[]" id="qty_'.strtolower($row->shop_initial).'_#" style="width: 25px;" onkeyup="countStok(#)" onkeypress="checkForEnter(1,event,this)"/></td>';
                    $shop_mark[$i] = strtoupper($row->shop_initial);
                    $shop_initial[$i++] = strtolower($row->shop_initial);                    
                }
                $this->data['shop_name'] = $shop;
                $this->data['row_qty'] = $row_qty;
                $this->data['shop_initial'] = $shop_initial;
                $this->data['shop_mark']=$shop_mark;
            }
            //processing data untuk mutasi keluar
            if($this->input->post('submit_mutasi_keluar')) 
            {
                $item_code = $this->input->post('item_code');
                $item_hj = $this->input->post('item_hj');
                $qty_stok = $this->input->post('qty_stok');
                $item_disc = $this->input->post('item_disc');
                foreach($shop_initial as $row)
                {
                    $qty[$row] = $this->input->post('qty_'.$row);
                }                             
                //insert mutasi keluar
                $this->insert_mutasi_keluar($item_code,$item_hj,$qty,$qty_stok,$item_disc);                
            }
			$this->load->view(config_item('template').'gud_mutasikeluar',$this->data);
		}
		//print mutasi
		else if($this->uri->segment(3)=='print')
		{
			//*print mutasi masuk dengan format
			/*| Kode Label | Nama | Qty Awal | initial toko | HM +15% | HJ
			*
			*
			*/
            $this->load->model('supplier');
            $this->load->model('shop');
            $this->load->model('item_mutasi');
            $role = $this->session->userdata('p_role');
			if($this->input->post('submit_print_mutasi'))
			{
				$query = $this->supplier->get_supplier($this->input->post('sup_code'));
				$sup = $query->row();
                $query = $this->item_mutasi->get_item_mutasi(array('sup_code'=>$this->input->post('sup_code')));                
                
                if($query->num_rows() > 0)
                {
                    $data = $query->row();
                    $head = '<div style="margin-top: 5px;">
                                <h3 style="text-align: center;">BON ORDER BARANG</h3>
                                <table style="width: 700px;">
                                <tr><td style="width: 100px;">KODE MUTASI</td><td style="width:250px;">: '.strtoupper($data->kode_mutasi).'</td></tr>	
                                <tr><td style="width: 100px;">SUPPLIER</td><td style="width:250px;">: '.strtoupper($sup->sup_name).'</td></tr>							                              
                                <tr><td style="width: 100px;">TANGGAL BON</td><td style="width:250px;">: '.date_to_string($data->date_bon).'</td></tr>							                              
                                <tr><td style="width: 100px;">TANGGAL MUTASI</td><td style="width:250px;">: '.date_to_string($data->date_entry).'</td></tr>							                              
                                </table>
                            </div>';
                    
                    //retrieve data toko
                    $query = $this->shop->get_shop();
                    $jumlah_toko = $query->num_rows();
                    $this->data['shop_count'] = $query->num_rows();
                    $width = $jumlah_toko * 25;				
                    $head .= '<br /><table style="width: 600px;" border="1" cellpadding="3">
                            <tr>
                            <td style="width: 20px;text-align: center;" rowspan="2">No</td>
                            <td style="width: 60px;text-align: center; vertical-align: middle;" rowspan="2">Kode Label</td>
                            <td style="width: 100px;text-align: center;" rowspan="2">Nama</td>
                            <td style="width: 25px;text-align: center;" rowspan="2">Qty</td>
                            <td style="width: '.$width.'px;text-align: center;" colspan="15">Distribusi</td>
                            <td style="width: 50px;text-align: center;" rowspan="2">HM</td>
                            <td style="width: 60px;text-align: center;" rowspan="2">Harga Jual</td>						
                            </tr>
                            <tr>';
                    $row_shop = '';
                    
                    foreach($query->result() as $row)
                    {
                        $head .= '<td style="width: 25px;text-align: center; font-size: 17px;">'.strtoupper($row->shop_initial).'</td>';
                        $row_shop .= '<td style="width: 25px;text-align: center; font-size: 17px; color: #bbb;">'.strtoupper($row->shop_initial).'</td>';
                    }
                    $head.='</tr>';
                        
                    //retrieving data item untuk diprint				
                    $query = $this->item_mutasi->get_item_mutasi(array('sup_code'=>$this->input->post('sup_code')));                
                    if($query->num_rows() > 0)
                    {
                        $j=0;
                        $data='';
                        $total=0;
                        foreach($query->result() as $row)
                        {
                            $data .='<tr>
                                    <td style="width: 20px;text-align: center;">'.++$j.'</td>
                                    <td style="width: 60px;text-align: center;">'.$row->item_code.'</td>
                                    <td style="width: 100px;text-align: center;">'.$row->item_name.'</td>
                                    <td style="width: 25px;text-align: center">'.$row->qty.'</td>
                                    '.$row_shop;	
                            //check barang medan atau luar kota
                            $total += ($row->item_hp*$row->qty);
                            if(!$this->check_if_medan($row->sup_code))
                            {
                                $hp = floor($row->item_hp + 0.15*$row->item_hp);
                                $this->data['sup_region']='LMD';
                            }
                             else
                            {
                                $hp = $row->item_hp;
                                $this->data['sup_region']='MDN';
                            }
                            $data .= '	<td style="width: 50px;text-align: right;">'.number_format($hp,'0',',','.').',-</td>
                                    <td style="width: 60px;text-align: center;"></td>		
                                    </tr>';                        
                            
                            if($jumlah_toko <= 10 && $j%15 == 0)
                            {
                                $list_data[] = $data;
                                $data = '';
                            }
                            else if($jumlah_toko > 10 && $j%25 == 0)
                            {
                                $list_data[] = $data;
                                $data = '';
                            }
                        }
                        //klo data tak kosong, tambahkan
                        if(!empty($data))
                        {
                            $list_data[] =$data;
                        }
                        $footer = '<tr>
                        				<td rowspan="4" style="text-align:right;width:205px;">T O T A L</td>
                        				<td rowspan="'.($jumlah_toko+1).'" style="text-align:right;width:'.($jumlah_toko*25+50).'px">'.number_format($total,'0',',','.').',-</td>
                        				<td style="width:60px;"></td>
                        			</tr>
                        			</table><br /><table style="text-align:center;">
                                <tr><td>(Bagian Mutasi Masuk)</td><td>(Bagian Distribusi)</td></tr>                            
                            </table>';
                        //update status item yang udh pernah diprint mutasi jadi 1
                        $this->item_mutasi->update_status(array('sup_code'=>$this->input->post('sup_code')));                    
                        $this->cetak_mutasi_pdf($head, $list_data, $footer);            
                    }
                    else
                    {
                        $this->data['notifikasi'] = 'Tidak ada data mutasi barang masuk pada tanggal tersebut';                    
                    }
                    //----------------------------------------------------
                }
			}
            //print preview untuk mutasi
            if($this->input->post('submit_preview_mutasi'))
            {
                //loading model
                $this->load->model('shop');            
                //get all the  shop
                $query = $this->shop->get_shop();
                if($query->num_rows() > 0)
                {
                    //setting the initial shop,
                    $this->data['shop_count'] = $query->num_rows();
                    $shop = '';                    
                    $row_shop='';
                    $i=0;
                    foreach($query->result() as $row)
                    {
                        $shop .= '<td class="header">'.strtoupper($row->shop_initial).'</td>';
                        $row_shop .= '<td></td>';
                    }
                    $this->data['shop_name'] = $shop;                                              
                }
                
                //ambil suplier yang bertanggung jawab
                $query = $this->supplier->get_supplier($this->input->post('sup_code'));
				$this->data['sup'] = $query->row();
                
                //ambil data item mutasi
                $query = $this->item_mutasi->get_item_mutasi(array('sup_code'=>$this->input->post('sup_code')));
               
                if($query->num_rows() > 0)
                {
                    $row_data = '';
                    $i=0;
                    foreach($query->result() as $row)
                    {
                        $row_data .= '<tr>
                                        <td>'.++$i.'</td>
                                        <td>'.$row->item_code.'</td>
                                        <td>'.$row->item_name.'</td>
                                        <td>'.$row->qty.'</td>
                                        '.$row_shop.'
                                        <td></td>
                                    </tr>';
                    }
                    $this->data['tgl_bon'] = $row->date_bon;
                    $this->data['row_data'] = $row_data;                   
                }
            }
			//ambil supplier yang ada di table item_mutasi dan status print mutasinya masih kosong
			$query = $this->supplier->get_supplier_have_mutasi($this->session->userdata('p_role'));
			if($query->num_rows() > 0)
			{
				$this->data['list_sup'] = '<select name="sup_code">';
				foreach($query->result() as $row)
				{
					$this->data['list_sup'] .= '<option value="'.$row->sup_code.'">'.ucwords($row->sup_name).'</option>';
				}
				
				$this->data['list_sup'] .='</select>';
			}
			$this->load->view(config_item('template').'gud_printmutasi',$this->data);
		}
        else if($this->uri->segment(3)=='rekap')
		{
			
            if($this->input->post('submit_rekap_mutasi'))
            {                
                //ambil data dari form
                $this->data['tgl_mutasi'] = $this->input->post('tgl_mutasi');
                $this->data['tgl_bon'] = $this->input->post('tgl_bon');
                $this->data['sup_code'] = $this->input->post('sup_code');
                $opsi = $this->input->post('opsi');
                
                //ambilin data
                $this->load->model('item_mutasi');
                if($opsi == 1 && $this->input->post('tgl_mutasi'))
                {
                    $query = $this->item_mutasi->get_item_mutasi_by_date(array('tgl_mutasi'=>$this->data['tgl_mutasi']));
                    $this->data['title'] = 'TANGGAL MUTASI : '.date_to_string($this->input->post('tgl_mutasi'));
                }
                else if($opsi == 2 && $this->input->post('tgl_bon'))
                {
                    $query = $this->item_mutasi->get_item_mutasi_by_bon($this->data['tgl_bon']);
                    $this->data['title'] = 'TANGGAL BON : '.date_to_string($this->input->post('tgl_bon'));
                }
                else if($opsi == 3 && $this->input->post('sup_code'))
                {
                    $query = $this->item_mutasi->get_item_mutasi_by_supplier($this->data['sup_code']);
                    if($query->num_rows > 0)
                        $this->data['title'] = 'SUPPLIER : '.$query->row()->sup_name;
                }
                
                if(isset($query) && $query->num_rows() > 0)
                {
                    $i=0;
                    $row_data='';
                    foreach($query->result() as $row)
                    {                        
                        $row_data .='<tr>
                                        <td>'.++$i.'</td>
                                        <td>'.$row->kode_mutasi.'</td>
                                        <td>'.ucwords($row->sup_name).' ('.$row->sup_code.')</td>
                                        <td>'.$row->jml_barang.' macam</td>
                                        <td>'.date_to_string($row->date_entry).'</td>
                                        <td><span class="button"><input type="button" class="button" value="Cetak" onclick="cetakMutasi('.$row->kode_mutasi.')"/></span></td>
                                    </tr>';
                    }
                    $this->data['row_data'] = $row_data;
                }
                else
                {
                    $this->data['err_msg'] = '<span style="color:red">Data tidak ditemukan, silahkan ulangi lagi.</span>';
                }                
            }
            $kode_mutasi = $this->uri->segment(4);
			if(!empty($kode_mutasi))
			{
                $this->load->model('item_mutasi');
                $this->load->model('supplier');
                $this->load->model('shop');
                //rekap mutasi keluar untuk supervisor, sudah ada data distribusi toko, hm dan hj
                if($this->session->userdata('p_role') == 'supervisor')
                {
                    $query = $this->item_mutasi->get_item_mutasi(array('kode_mutasi'=>$kode_mutasi));
                    $data = $query->row();
                    $head = '<div style="margin-top: 5px;">
                                <h3 style="text-align: center;">REKAP DISTRIBUSI BARANG</h3>
                                <table style="width: 700px;">
								<tr><td style="width: 100px;">KODE MUTASI</td><td style="width:250px;">: '.strtoupper($data->kode_mutasi).'</td></tr>		
								<tr><td style="width: 100px;">SUPPLIER</td><td style="width:250px;">: '.strtoupper($data->sup_name).'</td></tr>						                              
                                <tr><td style="width: 100px;">TANGGAL BON</td><td style="width:250px;">: '.date_to_string($data->date_bon).'</td></tr>							                              
                                <tr><td style="width: 100px;">TANGGAL MUTASI</td><td style="width:250px;">: '.date_to_string($data->date_entry).'</td></tr>	
                                </table>
                            </div><br />';
                    //retrieve data toko
                    $query = $this->shop->get_shop();
                    $jumlah_toko = $query->num_rows();
                    $this->data['shop_count'] = $query->num_rows();
                    $width = $jumlah_toko * 25;				
                    $head .= '<table style="width: 600px;" border="1" cellpadding="3">
                            <tr>
                            <td style="width: 20px;text-align: center;" rowspan="2">No</td>
                            <td style="width: 60px;text-align: center; vertical-align: middle;" rowspan="2">Kode Label</td>
                            <td style="width: 100px;text-align: center;" rowspan="2">Nama</td>
                            <td style="width: 25px;text-align: center;" rowspan="2">Qty</td>
                            <td style="width: '.$width.'px;text-align: center;" colspan="'.$jumlah_toko.'">Distribusi</td>
                            <td style="width: 25px;text-align: center;" rowspan="2">Stok</td>
                            <td style="width: 50px;text-align: center;" rowspan="2">HM (Rp)</td>
                            <td style="width: 60px;text-align: center;" rowspan="2">Harga Jual (Rp)</td>	
                            </tr>
                            <tr>';
                    $row_shop = '';
                    $shop = array();
                    foreach($query->result() as $row)
                    {
                        $shop[] = $row->shop_code;
                        $head .= '<td style="width: 25px;text-align: center; font-size: 17px;">'.strtoupper($row->shop_initial).'</td>';
                        //$row_shop .= '<td style="width: 25px;text-align: center; font-size: 17px;"></td>';
                    }
                    $head.='</tr>';
                    //retrieving data item untuk diprint
                    $query = $this->item_mutasi->get_item_mutasi_by_code(array('kode_mutasi'=>$kode_mutasi));                    
                    $data='';
                    if($query->num_rows() > 0)
                    {
                        $row_data = '';
                        $j = 0;
                        $this->load->model('item_distribution');
                        foreach($query->result() as $row)
                        {
                            $qry = $this->item_distribution->get_item_distribution($row->item_code);
                            $row_shop = array();
                            foreach($qry->result() as $item_dist)
                            {
                                $row_shop = $this->build_row_shop($row_shop,$shop,$this->data['shop_count'],$item_dist);
                            }
                            $temp_row ='';
                            for($i=0;$i<$this->data['shop_count'];$i++)
                            {
                                if(isset($row_shop[$i]))
                                {
                                    $temp_row .= $row_shop[$i];
                                }
                                else
                                {
                                    $temp_row .=  '<td style="width: 25px;text-align: center; font-size: 20px;">0</td>';
                                }
                            }                            
                            $data .='<tr>
                                    <td style="width: 20px;text-align: center;">'.++$j.'</td>
                                    <td style="width: 60px;text-align: center;">'.$row->item_code.'</td>
                                    <td style="width: 100px;text-align: center;">'.$row->item_name.'</td>
                                    <td style="width: 25px;text-align: center">'.$row->qty.'</td>
                                    '.$temp_row.'
                                    <td style="width: 25px;text-align: center">'.$row->item_qty_stock.'</td>';
                            
                            if(!$this->check_if_medan($row->sup_code))
                            {
                                $hp = floor($row->item_hp + 0.15*$row->item_hp);
                                $this->data['sup_region'] = 'LMD';
                            }
                            else
                            {
                                $hp = $row->item_hp;
                                $this->data['sup_region'] = 'MDN';
                            }
                            $data .= '	<td style="width: 50px;text-align: right;">'.number_format($hp,'0',',','.').',-</td>
                                    <td style="width: 60px;text-align: center;">'.number_format($row->item_hj,'0',',','.').',-</td>		
                                    </tr>';
                            if($jumlah_toko <=10 && $j%15==0)
                            {
                                $list_data[] = $data;
                                $data = '';
                            }
                            else if($jumlah_toko > 10 && $j%25 == 0)
                            {
                                $list_data[] = $data;
                                $data = '';
                            }
                        }
                        //klo data tak kosong, tambahkan
                        if(!empty($data))
                        {
                            $list_data[] =$data;
                        }
                        $footer = '</table><br /><table style="text-align:center;">
                                <tr><td>(Bagian Mutasi Masuk)</td><td>(Bagian Distribusi)</td></tr>                            
                            </table>';
                        //ini diubah jumlah tokonya untuk triger ukuran kertas
                        $this->data['shop_count'] += 1;
                        $this->cetak_mutasi_pdf($head, $list_data, $footer);
                        //echo $head.$list_data[0].$footer;
                        //exit;
                    }
                }
                //rekap mutasi keluar biasa...
                else 
                {
                    $query = $this->item_mutasi->get_item_mutasi(array('kode_mutasi'=>$kode_mutasi));
                    $data = $query->row();
                    $head = '<div style="margin-top: 5px;">
                                <h3 style="text-align: center;">BON ORDER BARANG</h3>
                                <table style="width: 700px;">
								<tr><td style="width: 100px;">KODE MUTASI</td><td style="width:250px;">: '.strtoupper($data->kode_mutasi).'</td></tr>	
								<tr><td style="width: 100px;">SUPPLIER</td><td style="width:250px;">: '.strtoupper($data->sup_name).'</td></tr>						                              
                                <tr><td style="width: 100px;">TANGGAL BON</td><td style="width:250px;">: '.date_to_string($data->date_bon).'</td></tr>							                              
                                <tr><td style="width: 100px;">TANGGAL MUTASI</td><td style="width:250px;">: '.date_to_string($data->date_entry).'</td></tr>							                              
                                </table>
                            </div><br />';
                    //retrieve data toko
                    $query = $this->shop->get_shop();
                    $jumlah_toko = $query->num_rows();
                    $this->data['shop_count'] = $query->num_rows();
                    $width = $jumlah_toko * 25;				
                    $head .= '<table style="width: 600px;" border="1" cellpadding="3">
                            <tr>
                            <td style="width: 20px;text-align: center;" rowspan="2">No</td>
                            <td style="width: 60px;text-align: center; vertical-align: middle;" rowspan="2">Kode Label</td>
                            <td style="width: 100px;text-align: center;" rowspan="2">Nama</td>
                            <td style="width: 25px;text-align: center;" rowspan="2">Qty</td>
                            <td style="width: '.$width.'px;text-align: center;" colspan="'.$jumlah_toko.'">Distribusi</td>
                            <td style="width: 50px;text-align: center;" rowspan="2">HM (Rp)</td>
                            <td style="width: 60px;text-align: center;" rowspan="2">Harga Jual</td>						
                            </tr>
                            <tr>';
                    $row_shop = '';
                    foreach($query->result() as $row)
                    {
                        $head .= '<td style="width: 25px;text-align: center; font-size: 17px;">'.strtoupper($row->shop_initial).'</td>';
                        $row_shop .= '<td style="width: 25px;text-align: center; font-size: 17px; color: #bbb;">'.strtoupper($row->shop_initial).'</td>';
                    }
                    $head.='</tr>';
                        
                    //retrieving data item untuk diprint
                    $query = $this->item_mutasi->get_item_mutasi_by_code(array('kode_mutasi'=>$kode_mutasi));             
                    $data='';
                    if($query->num_rows() > 0)
                    {
                        $row_data = '';
                        $j = 0;
                        $total=0;
                        foreach($query->result() as $row)
                        {
                            $data .='<tr>
                                    <td style="width: 20px;text-align: center;">'.++$j.'</td>
                                    <td style="width: 60px;text-align: center;">'.$row->item_code.'</td>
                                    <td style="width: 100px;text-align: center;">'.$row->item_name.'</td>
                                    <td style="width: 25px;text-align: center">'.$row->qty.'</td>
                                    '.$row_shop;
                            
                            $total += ($row->item_hp*$row->qty);
                            if(!$this->check_if_medan($row->sup_code))
                            {
                                $hp = floor($row->item_hp + 0.15*$row->item_hp);
                                $this->data['sup_region']='LMD';
                            }
                            else
                            {
                                $hp = $row->item_hp;
                                $this->data['sup_region']='MDN';
                            }
                            $data .= '	<td style="width: 50px;text-align: right;">'.number_format($hp,'0',',','.').',-</td>
                                    <td style="width: 60px;text-align: center;"></td>		
                                    </tr>';
                            if($jumlah_toko <=10 && $j%15==0)
                            {
                                $list_data[] = $data;
                                $data = '';
                            }
                            else if($jumlah_toko > 10 && $j%25 == 0)
                            {
                                $list_data[] = $data;
                                $data = '';
                            }
                        }
                        //klo data tak kosong, tambahkan
                        if(!empty($data))
                        {
                            $list_data[] =$data;
                        }
                        $footer = '<tr>
                        				<td rowspan="4" style="text-align:right;width:205px;">T O T A L</td>
                        				<td rowspan="'.($jumlah_toko+1).'" style="text-align:right;width:'.($jumlah_toko*25+50).'px">'.number_format($total,'0',',','.').',-</td>
                        				<td style="width:60px;"></td>
                        			</tr>
                        			</table><br /><table style="text-align:center;">
                                <tr><td>(Bagian Mutasi Masuk)</td><td>(Bagian Distribusi)</td></tr>                            
                            </table>';                  
                        $this->cetak_mutasi_pdf($head, $list_data, $footer);
                    }                    
				}                
				//----------------------------------------------------
				
			}			
			$this->load->view(config_item('template').'gud_rekapmutasi',$this->data);
		}
        
	}
    /**
    * Susun item, masukkin ke row shop untuk PDF
    */
    function build_row_shop($row_shop,$shop,$shop_count,$item)
    {
        for($i=0;$i<$shop_count;$i++)
        {
            //cocokkin urutannya
            if($shop[$i]==$item->shop_code)
            {
                $row_shop[$i] = '<td style="width: 25px;text-align: center; font-size: 20px;">'.$item->total.'</td>';
            }
        }
        return $row_shop;
    }
    /**
    * Susun item, masukkin ke row shop untuk view
    */
    function build_row_data_shop($row_shop,$shop,$shop_count,$item)
    {
    	for($i=0;$i<$shop_count;$i++)
    	{
    		//cocokkin urutannya
    		if($shop[$i]==$item->shop_code)
    		{
    			$row_shop[$i] = '<td>'.$item->total.'</td>';
            }
        }
    	return $row_shop;
    }
    /**
    *Fungsi untuk cetak barang yang sisa sisa, mutasi sisa
    */
    function sisa($param='')
    {
        
        $this->load->model('item');
        $this->load->model('sisa_mutasi');
        //cetak ke pdf
        if($this->input->post('submit_cetak_sisa') || $this->uri->segment(3) == 'cetak')
        {
            $this->load->model('shop');
            $this->load->model('supplier');
            //ambil data untuk cetak sisa mutasi ke pdf
            $kode_mutasi = $this->uri->segment(4);
            if($this->uri->segment(4) && is_numeric($kode_mutasi))
            {                
                $query = $this->sisa_mutasi->get_sisa_mutasi_by_kode($kode_mutasi);                 
            }
            else
            {
                $kode_mutasi = time();
                $query = $this->sisa_mutasi->get_sisa_mutasi(); 
            }
            if($query->num_rows() > 0)
            {                
                $data = $query->row();
                $head = '<div style="margin-top: 5px;">
                            <h3 style="text-align: center;">BON ORDER BARANG (SISA)</h3>
                            <table style="width: 700px;">
                            <tr><td style="width: 50px;">SUPPLIER</td><td style="width:300px;">: CAMPUR-CAMPUR</td></tr>							                              
                            <tr><td style="width: 50px;">TANGGAL</td><td style="width:300px;">: '.date_to_string($data->date_entry).'</td></tr>							                              
                            </table>
                        </div><br />';
                
                //retrieve data toko
                $qry = $this->shop->get_shop();
                $jumlah_toko = $qry->num_rows();
                $this->data['shop_count'] = $qry->num_rows();
                $width = $jumlah_toko * 25;				
                $head .= '<table style="width: 600px;" border="1" cellpadding="3">
                        <tr>
                        <td style="width: 20px;text-align: center;" rowspan="2">No</td>
                        <td style="width: 60px;text-align: center; vertical-align: middle;" rowspan="2">Kode Label</td>
                        <td style="width: 100px;text-align: center;" rowspan="2">Nama</td>
                        <td style="width: 25px;text-align: center;" rowspan="2">Qty</td>
                        <td style="width: '.$width.'px;text-align: center;" colspan="'.$jumlah_toko.'">Distribusi</td>
                        <td style="width: 50px;text-align: center;" rowspan="2">HM</td>
                        <td style="width: 60px;text-align: center;" rowspan="2">Harga Jual</td>						
                        </tr>
                        <tr>';
                $row_shop = '';
                foreach($qry->result() as $row)
                {
                    $head .= '<td style="width: 25px;text-align: center; font-size: 17px;">'.strtoupper($row->shop_initial).'</td>';
                    $row_shop .= '<td style="width: 25x;text-align: center; font-size: 17px;"></td>';
                }
                $head.='</tr>';
                    
                //retrieving data item untuk diprint				
                //$query = $this->sisa_mutasi->get_sisa_mutasi();                
                $data='';               
                $j = 0;
                foreach($query->result() as $row)
                {
                    $temp = $this->item->get_item(array('item_code'=>$row->item_code));
                    $item = $temp->row();                        
                    $data .='<tr>
                            <td style="width: 20px;text-align: center;">'.++$j.'</td>
                            <td style="width: 60px;text-align: center;">'.$row->item_code.'</td>
                            <td style="width: 100px;text-align: center;">'.$item->item_name.'</td>
                            <td style="width: 25px;text-align: center">'.$row->qty.'</td>
                            '.$row_shop;
                    
                    //check barang medan atau luar kota
                    if(!$this->check_if_medan($row->sup_code))
                    {
                        $hp = floor($item->item_hp + 0.15*$item->item_hp);
                    }
                     else
                    {
                        $hp = $item->item_hp;
                    }
                    $data .= '	<td style="width: 50px;text-align: right;">'.number_format($hp,'0',',','.').',-</td>
                            <td style="width: 60px;text-align: right;">'.number_format($item->item_hj,'0',',','.').',-</td>		
                            </tr>';
                    $this->sisa_mutasi->update_status(array('kode_mutasi'=>$kode_mutasi,'item_code'=>$row->item_code));
                    //atur paging
                    if($jumlah_toko <= 10 && $j%15 == 0)
                    {
                        $list_data[] = $data;
                        $data = '';
                    }
                    else if($jumlah_toko > 10 && $j%25 == 0)
                    {
                        $list_data[] = $data;
                        $data = '';
                    }
                }
                //klo data tak kosong, tambahkan
                if(!empty($data))
                {
                    $list_data[] = $data;
                }
                $footer = '</table><br /><table style="text-align:center;">
                        <tr><td>(Bagian Mutasi Masuk)</td><td>(Bagian Distribusi)</td></tr>                            
                    </table>';
                if(count($list_data) > 0)
                {
                    $this->cetak_mutasi_pdf($head, $list_data, $footer);                     
                }                
            }
        }
        
        //preview sebelum cetak mutasi sisa
        if($this->input->post('submit_preview_sisa'))
        {            
            $this->load->model('shop');            
            $this->load->model('supplier');            
            //get all the  shop
            $query = $this->shop->get_shop();
            if($query->num_rows() > 0)
            {
                //setting the initial shop,
                $this->data['shop_count'] = $query->num_rows();
                $shop = '';                    
                $row_shop='';
                $i=0;
                foreach($query->result() as $row)
                {
                    $shop .= '<td class="header">'.strtoupper($row->shop_initial).'</td>';
                    $row_shop .= '<td></td>';
                }
                $this->data['shop_name'] = $shop;                                              
            }
            //get data for preview
            $query = $this->sisa_mutasi->get_sisa_mutasi();
            if($query->num_rows() > 0)
            {
                $this->data['row_data'] = '';
                $i = 0;
                foreach($query->result() as $row)
                {
                    $temp = $this->supplier->get_supplier($row->sup_code);
                    $sup = $temp->row();
                    $temp = $this->item->get_item(array('item_code'=>$row->item_code));
                    $item = $temp->row();
                    $this->data['row_data'] .= '<tr>
                                                    <td>'.++$i.'</td>
                                                    <td>'.$row->item_code.'</td>
                                                    <td>'.ucwords($item->item_name).'</td>
                                                    <td>'.ucwords($sup->sup_name).'('.$sup->sup_code.')</td>
                                                    <td>'.$row->qty.'</td>
                                                    '.$row_shop.'
                                                    <td></td>
                                                </tr>';
                }
                $this->data['tgl_bon'] = date_to_string($row->date_entry);
            }
            
            $this->load->view(config_item('template').'gud_printsisa',$this->data);
            
        }
        
        //insert atw delete dari table
        if($this->input->post('opsi'))
        {
            $this->load->model('sisa_mutasi');
            $data = array(
                'kode_mutasi'=> 0,
                'item_code'=>$this->input->post('item_code'),
                'sup_code'=>$this->input->post('sup_code'),
                'qty'=>$this->input->post('qty'),
                'date_entry'=>date('Y-m-d'),
                'status_print_mutasi'=>0
            );
            $opsi = $this->input->post('opsi');
            //opsi 1 insert
            if($opsi == 1)
            {
                if($this->sisa_mutasi->insert_sisa_mutasi($data))
                {
                    echo 1;
                }
                else
                {
                    echo 0;
                }
            }
            //opsi 2 delete
            else if($opsi==2)
            {
                 if($this->sisa_mutasi->remove($data))
                {
                    echo 1;
                }
                else
                {
                    echo 0;
                }
            }
               
        }
        
        //search dari form
        if($this->input->post('submit_cari_sisa'))
        {
            $keywords = $this->input->post('keywords');
            $this->session->set_userdata('keywords');
            $query = $this->item->get_sisa($keywords);            
        }
        else
        {
            
            //search dari session, untuk lihat page
            $keywords = $this->session->userdata('keywords');
            if(isset($keywords))
            {
                $query = $this->item->get_sisa($keywords);
            }            
        }        
        if(!isset($_POST['submit_preview_sisa']))        
        {
            //urusan rekap mutasi sisa
            if($param=='rekap')
            {                
                if($this->input->post('submit_rekap_mutasi_sisa'))
                {                    
                    //ambil data dari form
                    $this->data['tgl_mutasi'] = $this->input->post('tgl_mutasi');
                    //$this->data['tgl_bon'] = $this->input->post('tgl_bon');
                    $this->data['sup_code'] = $this->input->post('sup_code');
                    $opsi = $this->input->post('opsi');
                    
                    //ambilin data
                    $this->load->model('item_mutasi');
                    if($this->input->post('tgl_mutasi'))
                    {
                        $query = $this->sisa_mutasi->get_sisa_mutasi_by_date(array('tgl_mutasi'=>$this->data['tgl_mutasi']));
                        $this->data['title'] = 'TANGGAL MUTASI : '.date_to_string($this->input->post('tgl_mutasi'));
                    }                    
                    if(isset($query) && $query->num_rows() > 0)
                    {
                        $i=0;
                        $row_data='';
                        foreach($query->result() as $row)
                        {                            
                            $row_data .='<tr>
                                            <td>'.++$i.'</td>
                                            <td>'.$row->kode_mutasi.'</td>
                                            <td>'.ucwords($row->sup_name).' ('.$row->sup_code.')</td>
                                            <td>'.$row->jml_barang.' macam</td>
                                            <td>'.date_to_string($row->date_entry).'</td>
                                            <td><a href="'.base_url().'gudang/sisa/cetak/'.$row->kode_mutasi.'" target="_new"><span class="button"><input type="button" class="button" value="Cetak" /></span></a></td>
                                        </tr>';
                        }                        
                        $this->data['row_data'] = $row_data;
                    }
                    else
                    {
                        $this->data['err_msg'] = '<span style="color:red">Data tidak ditemukan, silahkan ulangi lagi.</span>';
                    } 
                }    
                $this->load->view(config_item('template').'gud_rekapsisa',$this->data);                    
            }
            //list data barang untuk dicetak mutasi sisa
            else
            {
                //tampilin data untuk ke browser
                if(isset($query) && $query->num_rows() > 0)
                {
                    $this->data['total_item'] = $query->num_rows(); 
                    //setting up pagination
                    $this->load->library('pagination');
                    $config['base_url'] = base_url().'gudang/sisa/';
                    $config['total_rows'] = $this->data['total_item'];
                    $config['per_page'] = 20;
                    $this->pagination->initialize($config);
                    $this->data['page'] = $this->pagination->create_links();
                    //applying pagination on displaying result            
                    if(isset($param) && intval($param) > 0)
                    {
                        $page_min = $param;
                        $page_max = $page_min + $config['per_page'];
                    }
                    else
                    {
                        $page_min = 0;
                        $page_max = $config['per_page'];
                    }
                    $i = 0;
                    $this->data['row_data'] = '';            
                    foreach($query->result() as $row)
                    {                         
                        if($i>=$page_min && $i <$page_max)
                        {
                            $checkbox = '<input type="checkbox" name="opsi_'.++$i.'" value="'.$row->item_code.'" onchange="addRemoveMutasi('.($i%$config['per_page']).')"/>';
                            if($row->kode_mutasi == '0')
                            {
                                $checkbox = '<input type="checkbox" name="opsi_'.$i.'" value="'.$row->item_code.'" onchange="addRemoveMutasi('.($i%$config['per_page']).')" checked="checked"/>';
                            }
                            $this->data['row_data'] .= '<tr>
                                                            <td>'.$i.'</td>
                                                            <td>'.$row->item_code.'</td>
                                                            <td>'.$row->item_name.'</td>
                                                            <td>'.$row->cat_code.'</td>
                                                            <td>'.$row->sup_code.'</td>
                                                            <td>'.number_format($row->item_hp,0,',','.').'</td>
                                                            <td>'.$row->item_qty_stock.'</td>
                                                            <td>'.$checkbox.'</td>
                                                        </tr>';
                        }
                        else
                        {
                            $i++;
                        }
                    }            
                }
                $this->load->view(config_item('template').'gud_sisa',$this->data);
            }
        }
    }
    function check_if_medan($sup_code)
    {
        $query = $this->supplier->get_supplier($sup_code);
        if($query->num_rows() > 0)
        {
            $check = $query->row();
            if($check->sup_type == 'medan')
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
    }
    /**
    *Menyimpan data mutasi masuk ke dalam database
    **/
    function insert_mutasi_masuk()
    {
        $j = 0;
        $this->data['last_query']='';
        $this->data['form_notify']='';
        $time = time();
        $tgl_mutasi = date('Y-m-d');
        $thn = date('y');
        $success = '';
        $failed='';
        $qty_nol = '';
        for($i = 0; $i < count($this->data['cat_code']); $i++)
        {            
            if(!empty($this->data['cat_code'][$i]))
            {
                
                //tentuin kode kelompok barangya dulu, kelompok barang adalah 3 digit awal                
                $cat_code = $this->data['cat_code'][$i];
                if($this->data['item_qty'][$i] > 0)
                {
                    $item_hm = $this->data['item_hp'][$i]*$this->data['item_qty'][$i];
                    $data = array(				    
                        'item_name'=> $this->data['item_name'][$i],
                        'item_hm'=> $item_hm,
                        'item_hp'=> $this->data['item_hp'][$i],
                        'item_qty_total'=> $this->data['item_qty'][$i],
                        'item_qty_stock'=> $this->data['item_qty'][$i],
                        'sup_code'=> $this->data['sup_code'],
                        'cat_code'=> $cat_code,
                        'op_code'=> $this->session->userdata('p_id')				    
                    );
                    //cek dulu apakah kode kelompok barangnya ada di database
                    $this->load->model('category');
                    $query = $this->category->get_category($cat_code);
                    if($query->num_rows() > 0)
                    {
                        //semua item yang dimutasikan dianggap sebagai item baru, tidak pernah ada update item
                        //sistem kode label yang ada : 10 digit, dengan ketentuan, 3 digit awal adalah kategori barang, 2 digit tahun dan sisanya no urut barang
                        //10 digit = xxx-xx-xxxxx
                        //5 digit terakhir diotomasi sistem                    
                        
                        //check apakah barang dengan kategori tersebut sudah pernah ada, klo udh pernah ada trus ambil dan tinggal naikin no urut kode labelnya
                        $this->load->model('item');
                        $query = $this->item->get_item_by_catnyear($cat_code.$thn);
                        if($query->num_rows() > 0)
                        {
                            $data_item = $query->row();
                            $temp = ++$data_item->item_code; //klo ada di database terus langsung di
                            $start = strlen($temp) - 5;
                            $item_next = substr($temp, $start, 5);
                            $data['item_code'] = $cat_code.$thn.$item_next;                        
                        }            
                        else //klo bblum ada mulai dari 00001
                        {
                            $data['item_code'] = $cat_code.$thn.'00001';
                        }
                        //save item to database
                        $this->load->model('item');
                        $this->load->model('item_mutasi');
                        $this->load->model('log_transaksi');
                        
                        if($this->item->add_item($data))
                        {
                            if(config_item('log_enable')) //if log was enabled, then do logging
                            {
                                $log = array(
                                    'trans_name'=>'item::add_item',
                                    'log_time'=>time(),
                                    'p_id'=>$this->session->userdata('p_id'),
                                    'keterangan'=>'Tambah item baru ke database'
                                );
                                $this->log_transaksi->insert($log);
                            }
                            
                            $item_mutasi = array(
                                'kode_mutasi'=>$time,
                                'item_code'=>$data['item_code'],
                                'sup_code'=>$data['sup_code'],
                                'qty'=>$data['item_qty_total'],
                                'date_entry'=>$tgl_mutasi,
                                'date_bon'=>$this->data['date_bon'],
                                'status_print_mutasi'=>0
                            );                            
                            if($this->item_mutasi->insert_item_mutasi($item_mutasi))
                            {
                                if(config_item('log_enable')) //if log was enabled, then do logging
                                {
                                    $log = array(
                                        'trans_name'=>'item_mutasi::insert_item_mutasi',
                                        'log_time'=>time(),
                                        'p_id'=>$this->session->userdata('p_id'),
                                        'keterangan'=>'Tambah item_mutasi baru ke database'
                                    );
                                    $this->log_transaksi->insert($log);
                                }
                               $success .= $data['item_code'].', '; 
                            }
                        }
                    }
                    else
                    {					
                        $failed .= $cat_code.', ';
                        $j--;
                    }				
                }                
            }    
        }
        //setting error message / succes message
        $form_notify = '';
        if(!empty($success))
        {
            $form_notify .= '<span style="color:green">Kode Barang: <b>'.$success.'</b> telah disimpan</span><br />';
        }
        if(!empty($failed))
        {
            $form_notify .= '<span style="color:red">Kategori Barang: <b>'.$failed.'</b> tidak valid</span>';
        }
        $this->session->set_userdata('form_notify',$form_notify);
        redirect('/gudang/mutasi/masuk','refresh');
    }
    
	/**
    *Menyimpan data mutasi keluar ke dalam database (table item_distribution)
    **/
    function insert_mutasi_keluar($item_code,$item_hj,$qty,$qty_stok,$item_disc)
    {
        //ambil data semua toko        
        $query = $this->shop->get_all_shop();        
        if($query->num_rows() > 0)
        {
            $shop = $query->result();
            $now = date('Y-m-d');
            $this->load->model('item_distribution');
            $this->load->model('item');
            $success = '';
            for($i=0;$i<count($item_code);$i++)//mulai dari baris ke satu sampai abis
            {
                $tmp_cat = substr($item_code[$i],0,3);
                if($tmp_cat == config_item('hadiah') || $item_hj[$i] > 0)
                {
                    $shop_initial='';
                    for($j=0;$j<count($shop);$j++)//looping semua toko
                    {
                        if(isset($qty[strtolower($shop[$j]->shop_initial)][$i]))
                            $quantity = $qty[strtolower($shop[$j]->shop_initial)][$i];
                        if(!empty($quantity) && $quantity > 0)
                        {
                            $data = array(
                                'dist_code'=> 0,
                                'item_code'=> $item_code[$i],
                                'shop_code'=> $shop[$j]->shop_code,
                                'dist_out'=> $now,
                                'quantity'=> $quantity,
                                'item_disc'=> $item_disc[$i],
                                'status'=> 0
                            );
                            //insert data ke table item_distribution 
                            if($this->item_distribution->insert_item_distribution($data))
                            {
                                $shop_initial.= $shop[$j]->shop_initial.', ';
                            }
                            $quantity = 0;
                        }
                        
                    }
                    //update qty_stok di tabel item
                    $param = array('item_code'=>$item_code[$i],'item_qty_stok'=>$qty_stok[$i],'item_hj'=>$item_hj[$i]);
                    $this->item->update_item($param);
                    
                    if(!empty($shop_initial))//setting msg
                    {
                        $success .= 'kode <b>'.$item_code[$i].'</b> ke toko : <b>('.$shop_initial.')</b>, ';
                    }
                }
            }            
            if(!empty($success))
            {
                $success = 'Proses mutasi '.$this->data['opsi'].' untuk '.$success.' telah berhasil dilakukan';
            }
            else
            {
                $failed = 'Belum mengisi qty barang untuk masing-masing toko';
            }
        }
        //setting error message / succes message
        $form_notify = '';
        if(!empty($success))
        {
            $form_notify .= '<span style="color:green">'.$success.'</span>';
        }
        if(!empty($failed))
        {
            $form_notify .= '<span style="color:red">'.$failed.'</span>';
        }
        $this->session->set_userdata('form_notify',$form_notify);
        redirect('/gudang/mutasi/'.$this->data['opsi'],'refresh');
    }
    /**
	*Method for printing label an bon
	*/
	function cetak()
	{
		if($this->uri->segment(3)=='label')
		{
			//membuat print label per supplier
		    if($this->input->post('submit_cetak_label'))
		    {
                //ambil nama supplier buat ditampilin
                $this->load->model('supplier');
                $this->load->model('item_distribution');
                $query = $this->supplier->get_supplier($this->input->post('sup_code'));
                $this->data['sup_name'] = $query->row()->sup_name;
                $this->data['sup_code'] = $this->input->post('sup_code');
                //ambil item punya supplier yang disubmit                
                $query = $this->item_distribution->get_item_for_printing($this->input->post('sup_code'));
                if($query->num_rows() > 0)
                {
                    $row_data = '';
                    $i = 0;
                    foreach($query->result() as $row)
                    {                        
                        $checkbox = '<input type="checkbox" name="opsi_'.++$i.'" value="'.$row->item_code.'" onchange="addRemoveLabel('.$i.')"/>';
                        if($row->status == 2)
                        {
                            $checkbox = '<input type="checkbox" name="opsi_'.$i.'" value="'.$row->item_code.'" onchange="addRemoveLabel('.$i.')" checked="checked"/>';
                        }
                        $row_data .= '<tr>
                                        <td>'.$i.'</td>
                                        <td>'.$row->item_code.'</td>
                                        <td>'.$row->item_name.'</td>
                                        <td>'.number_format($row->item_hj,0,',','.').',-</td>
                                        <td>'.$row->qty.' item</td>
                                        <td>'.$checkbox.'</td>
                                    </tr>';
                    }
                    $this->data['row_data'] = $row_data;
                }                    
		    }
            //retrieve item_code di table item_distribusi yang statusnya 2, trus di ambil untuk diexport
		    $item_code = $this->uri->segment(4);
		    if($this->input->post('submit_cetak_txt'))
		    {
                //ambil data item
                $this->load->model('item_distribution');
                $query = $this->item_distribution->get_item_accumulated($this->input->post('sup_code'));
                $items = $query->result();
                
                if($query->num_rows() > 0)
                {
                    $data_txt = 'Cabang:'.chr(9).'Nama Barang :'.chr(9).'Kode Brg/Barcode :'.chr(9).'Input Harga :'.chr(9).'Harga Modal :'.chr(9).'Supplier :'.chr(9).'Tanggal :'.chr(9).'Kode Toko :'.chr(10);
                    foreach($items as $row)
                    {
                        //ambil data barang yang akan dibuat label                
                        $query = $this->item_distribution->get_item_for_exporting($row->item_code);                        
                        if($query->num_rows() > 0)
                        {                            
                            foreach($query->result() as $row)
                            {
                                for($i=0;$i < $row->quantity; $i++)
                                {
                                    if(config_item('label') == 1)
                                    {
                                        if($row->shop_cat == 'MODE')
                                            $shop_cat = '';
                                        else if($row->shop_cat == 'MODIEST')
                                            $shop_cat = '.';
                                        else 
                                            $shop_cat = '';
                                    }
                                    else if(config_item('label') == 2)
                                    {
                                        $shop_cat = $row->shop_cat;
                                    }
                                    //pengkodean harga modal
				    if($row->item_hp == 0) 
				    {
					$tmp = array(0,0,0);
				    }
				    else
				    {
					$tmp = substr($row->item_hp,0,3);
				    }                                    
                                    $kode = config_item('kode_hm');                                    
                                    $kode_hm = $kode[$tmp[0]].$kode[$tmp[1]].$kode[$tmp[2]];                                   
                                    $data_txt .= strtoupper($shop_cat).chr(9).strtoupper($row->item_name).chr(9).$row->item_code.chr(9).number_format($row->item_hj,0,',','.').',-'.chr(9).$kode_hm.chr(9).
                                                strtoupper($row->sup_code).chr(9).date("dmy").chr(9).$row->shop_code .chr(10);
                                                  
                                }                        
                                if( $row->quantity % 2 == 1)
                                {
                                    $data_txt .= '====='.chr(9).'====='.chr(9).'====='.chr(9).'====='.chr(9).'====='.chr(9).'====='.chr(9).'====='.chr(10);                                
                                }
                                
                            }               
                        }
                    }
                }                
                if(isset($data_txt))
                {                    
                    if($this->export_file_txt($data_txt))
                    {
                        //update status di item_distribution jadi 1
                        $this->load->model('item_distribution');
                        foreach($items as $row)
                        {
                            $this->item_distribution->update_status(array('item_code'=>$row->item_code));
                            $item_code .= $row->item_code.', ';
                        }                        
                        $this->session->set_userdata('link_download',base_url().'data/label-'.$this->session->userdata('p_id').'.doc');
                        $this->session->set_userdata('item_code',$item_code);
                        //simpan data 
                        //redirect('gudang/cetak/label','refresh');
                    }
                    else
                    {
                        $this->data['err_msg'] = '<span style="color:red">Gagal mencetak label, silahkan cek hak akses penulisan. Pastikan folder <b>sisgud/data/</b> bisa ditulis</span>';
                    }
                }
                else
                {
                    $this->data['err_msg'] = '<span style="color:red">Anda belum memilih barang yang akan dicetak label</span>';
                }
		    }     
			$this->list_supplier();
			$this->data['page_title'] .= ' :. Mencetak Label';
			$this->load->view(config_item('template').'gud_cetaklabel',$this->data);
		}		
		else if($this->uri->segment(3)=='bon')
		{            
			//print preview untuk bon toko tertentu
			if($this->input->post('submit_preview_bon'))
			{
				$this->load->model('item_distribution');
				$this->load->model('shop');
                //ambil dan tentukan tanggal serta kode bon, toko tujuan
                $query = $this->shop->get_shop($this->input->post('shop_code'));
                $this->data['shop'] = $query->row();
                
                $bon = $this->generate_bon($this->input->post('shop_code'));
                $this->data['tgl_bon'] = $bon['dist_out'];
                $this->data['dist_code'] = $bon['dist_code'];
                if(count($bon['dist_code']) > 1)
                {
                	
                }
                $rows = array();
                $k=0;
                foreach($bon['dist_out'] as $dist_out)
                {
                	$query = $this->item_distribution->get_item_for_bon($this->input->post('shop_code'),$dist_out);
                	if($query->num_rows() > 0)
                	{
                		$i=0;
                		$row_data='';
                		$total_rupiah = 0;
                		$total_qty = 0;
                		foreach($query->result() as $row)
                		{
                			$jumlah = $row->item_hj *(1 - $row->item_disc/100) * $row->quantity;
                			$row_data .= '<tr>
                	                                        <td>'.++$i.'</td>
                	                                        <td>'.$row->sup_code.'</td>
                	                                        <td>'.$row->item_code.'</td>
                	                                        <td class="left">'.$row->item_name.'</td>
                	                                        <td class="left">'.$row->item_disc.'</td>
                	                                        <td class="right">'.number_format($row->item_hj,0,',','.').',-</td>
                	                                        <td>'.$row->quantity.' item</td>
                	                                        <td class="right">'.number_format($jumlah,0,',','.').',-</td>
                	                                    </tr>';
                			$total_rupiah += $jumlah;
                			$total_qty += $row->quantity;
                		}
                		$row_data .= '<tr><td colspan="6">T O T A L</td><td>'.$total_qty.' item</td><td class="right">'.number_format($total_rupiah,0,',','.').',-</td></tr>';
                		$rows[$k++] = $row_data;
                	}
                	else
                	{
                		$this->data['err_msg'] = '<span style="color:red">Tidak dapat mencetak bon. Anda belum mencetak label atau bon sudah pernah dicetak</span>';
                	}
                }
                $this->data['row'] = $rows;
                
                //buat bon untuk toko yang sedang ditampilkan
                //$this->item_distribution->create_bon(array('shop_code'=>$this->input->post('shop_code'),'dist_code'=>$this->data['dist_code']));                
			}			
			//cetak bon setelah preview
            $kode_bon = $this->uri->segment(4);
            $shop_code = $this->uri->segment(5);
            $tgl_bon=$this->uri->segment(6);
            if($this->input->post('submit_cetak_bon') || (!empty($kode_bon) && !empty($shop_code)))
			{    
				$this->load->model('item_distribution');
				$this->item_distribution->create_bon(array('shop_code'=>$shop_code,'dist_code'=>$kode_bon,'dist_out'=>$tgl_bon));
                //ambil barang untuk dicetak bonnya
                if(empty($kode_bon))
                {
                    $kode_bon = $this->input->post('dist_code');
                }                
                
                $this->load->model('shop');
                $this->load->model('item');
				$query = $this->item_distribution->get_item_for_pdf(array('dist_code'=>$kode_bon,'shop_code'=>$shop_code));                 
                if($query->num_rows() > 0)
                {
                    $item_jml = $query->num_rows();
                    //ambil mutasi
                    $mutasi = $query->row();
                    $temp = $this->shop->get_shop($mutasi->shop_code);
                    $shop = $temp->row();
                    $this->data['shop_code'] = $shop->shop_code;
                    $head = '<div style="margin-top: 5px;">
                            <h3 style="text-align: center;">BON MUTASI KELUAR GUDANG</h3>
                            <table style="width: 700px;">
                            <tr><td style="width: 80px;">Kode Bon</td><td style="width:260px;">: '.$kode_bon.'</td>
                            <td style="width: 70px;text-align:right;">Tanggal</td><td style="width:100px;text-align:right">: '.date_to_string($mutasi->dist_out).'</td></tr>
                            <tr><td style="width: 80px; ">Toko Tujuan</td><td>: '.strtoupper($shop->shop_name).'</td></tr>                                
                            </table>
                        </div><br />';
                        
                    $head .= '<table style="width: 600px;" border="1" cellpadding="3">
                            <tr>
                            <td style="width: 20px;text-align: center;">No</td>						
                            <td style="width: 70px;text-align: center">Kode Barang</td>
                            <td style="width: 70px;text-align: center;">Kelompok Brg</td>
                            <td style="width: 50px;text-align: center;">Supplier</td>
                            <td style="width: 110px;text-align: center;">Nama Barang</td>
                            <td style="width: 50px;text-align: center;">Disc %</td> 
                            <td style="width: 75px;text-align: center;">Harga Jual (Rp.)</td>
                            <td style="width: 40px;text-align: center;">Qty Brg</td>
                            <td style="width: 75px;text-align: center;">Jumlah (Rp.) </td>
                            </tr>';
                    $i = 0;
                    $jumlah_item = 0;
                    $total = 0;
                    $j=0;$index=0;
                    
                    foreach($query->result() as $row)
                    {
                        $temp = $this->item->get_item(array('item_code'=>$row->item_code));
                        $item = $temp->row();
                        $jumlah = $item ->item_hj *(1 - $row->item_disc/100) * $row->quantity;
                        $jumlah_item += $row->quantity;
                        $total += $jumlah;
                        if(!isset($list_item[$index]))
                        {
                            $list_item[$index] = '';
                        }
                        $list_item[$index].= '<tr>
                            <td style="width: 20px;height:;text-align: center;">'.++$i.'</td>						
                            <td style="width: 70px;text-align:center">'.$item->item_code.'</td>
                            <td style="width: 70px;height:;text-align: center;">'.$item->cat_code.'</td>
                            <td style="width: 50px;text-align: center;">'.$item->sup_code.'</td>
                            <td style="width: 110px;">'.strtoupper($item->item_name).'</td>
                            <td style="width: 50px;text-align: center;">'.$row->item_disc.'</td>                        
                            <td style="width: 75px;text-align: right;">'.number_format($item->item_hj,'0',',','.').',-</td>
                            <td style="width: 40px;text-align:right">'.$row->quantity.'</td>
                            <td style="width: 75px;text-align: right;">'.number_format($jumlah,'0',',','.').',-</td>
                            </tr>';
                        $j++;
                        if($j==15)
                        {
                            //yang ditutup table disini khusus yang
                            if($item_jml%15 > 0)
                            {                            
                                $list_item[$index] .= '</table>';
                            }
                            else
                            {                           
                                if($index < ($item_jml/15)-1)
                                    $list_item[$index] .= '</table>';
                            }
                            $j=0;$index++;                        
                        }                 
                    }    
                    //Jika jenis item pas kelipatan 15, maka index diturunkan satu dan harus ditutup langsung
                    if($item_jml%15 == 0)
                    {               
                        $list_item[--$index] .= '<tr>						
                                                    <td style="width: 445px;text-align:right" colspan="7"> T O T A L</td>
                                                     <td style="width: 40px;text-align:right">'.$jumlah_item.'</td>
                                                    <td style="width: 75px;text-align: right;">'.number_format($total,'0',',','.').',-</td>
                                                    </tr>
                                            </table>';                 
                    }
                    //yang bukan kelipatan  15, ya normal                
                    else
                    {
                        $list_item[$index] .= '<tr>						
                                                    <td style="width: 445px;text-align:right" colspan="7"> T O T A L</td>
                                                     <td style="width: 40px;text-align:right">'.$jumlah_item.'</td>
                                                    <td style="width: 75px;text-align: right;">'.number_format($total,'0',',','.').',-</td>
                                                    </tr>
                                            </table>';
                    }
                    $footer = '<br /><table style="text-align:center;">
                                            <tr><td>Bagian Gudang</td><td>Bagian Transport</td><td>Bagian Toko</td><td>Bagian Komputer</td></tr>                            
                                    </table>';
                    //echo htmlentities($list_item[1]);exit;
                    $this->cetak_pdf($head,$list_item,$footer);
                }
                else
                {
                    $this->data['err_msg'] = '<p><span style="color:red">Data tidak ditemukan</span></p>';
                }
			}            
            //rekap untuk cetak bon
            if($this->uri->segment(4) == 'rekap')
            {       
                if($this->input->post('submit_rekap_bon'))
                {
                    $this->load->model('item_distribution');
                    //ambil bon toko sesuai tanggal yang diquery kan
                    $tgl = $this->input->post('date_bon');
                    $query = $this->item_distribution->get_bon_by_toko($this->input->post('shop_code'),$tgl);
                    if($query->num_rows() > 0)
                    {
                        $i=0;
                        $row_data='';
                        foreach($query->result() as $row)
                        {
                            $row_data .= '<tr>
                                            <td>'.++$i.'</td>
                                            <td>'.$row->dist_code.'</td>
                                            <td>'.date_to_string($row->dist_out).'</td>
                                            <td>'.$row->jenis_brg.' macam</td>
                                            <td>'.$row->jumlah_brg.' item</td>
                                            <td>
                                            <!--'.form_open('gudang/cetak/bon').'-->
                                                <input type="hidden" name="dist_code" value="'.$row->dist_code.'"/>
                                                <a href="'.base_url().'gudang/cetak/bon/'.$row->dist_code.'/'.$this->input->post('shop_code').'" target="new"/><span class="button"><input class="button" type="submit" name="submit_cetak_bon" value="Cetak"></span></a>
                                            <!--'.form_close().'-->
                                            '.form_open('gudang/export').'
                                                <input type="hidden" value="'.$row->dist_code.'" name="dist_code" />
                                                <input type="hidden" value="'.$this->input->post('shop_code').'" name="shop_code" />
                                                <span class="button"><input class="button" type="submit" value="Export" name="submit_export"/></span>
                                            '.form_close().'
                                            </td>
                                        </tr>';
                        }
                        $this->data['row_data'] = $row_data;
                    }
                    else
                    {                       
                        $this->data['err_msg'] = '<p><span style="color:red">Data tidak ditemukan.</span></p>';
                    }
                    //ambil data toko
                    $this->load->model('shop');
                    $query = $this->shop->get_shop($this->input->post('shop_code'));
                    $this->data['shop'] = $query->row();
                }
                $this->data['list_toko_pdf'] = $this->list_toko('pdf');
                
                $this->load->view(config_item('template').'gud_rekapbon',$this->data);
            }
            else
            {
			    $this->data['list_toko_bon'] = $this->list_toko('bon');               
			    $this->data['page_title'] .= ' :. Mencetak Label';            
			    $this->load->view(config_item('template').'gud_cetakbon',$this->data);
            }
		}
	}
    /**
    *Fungsi untuk generate kode bon toko
    */
    function generate_bon($shop_code)
    {
        //retrieve shop data
        $this->load->model('shop');
        $this->load->model('item_distribution');
        $query = $this->shop->get_shop($shop_code);
        if($query->num_rows() > 0)
        {
            $shop = $query->row();

            //how many bon do we have to create
            $qry = $this->item_distribution->count_bon($shop_code);   
            //var_dump($qry->result());exit;         
            
            //retrieve last dist code for shop
            $query = $this->item_distribution->get_last_dist_code($shop_code);
            //klo ada tinggal nerusin aja
            if($query->num_rows() >0)
            {
                $last_code = $query->row()->dist_code;
                //kode lama numeric time, jadi mesti buat kode baru
                if(is_numeric($last_code))
                {
                    $dist_code[0] = strtoupper($shop->shop_initial).date('y').'-0001';
                }
                else
                {
                    $arr = explode('-',$last_code);
                    $num = ++$arr[1];
                    $dist_code[0] = strtoupper($shop->shop_initial).date('y').'-'.str_pad($num, 4, '0',STR_PAD_LEFT);             
                }
            }
            //klo belum ada bikin baru
            else
            {
                $dist_code[0] = strtoupper($shop->shop_initial).date('y').'-0001';
            } 
                     
            if($qry->num_rows()>0)
            {
            	$i=0;
            	foreach($qry->result() as $row)
            	{
            		if($i>0)
            		{
            			$dist_code[$i] = strtoupper($shop->shop_initial).date('y').'-'.str_pad(++$num, 4, '0',STR_PAD_LEFT);
            		}
            		$dist_out[$i] = $row->dist_out;
            		$i++;
            	}
            }
            return array(
            	'dist_out'=>$dist_out,
            	'dist_code'=>$dist_code
            );
        }        
    }
    /**
    * Fungsi untuk akumulasi cetak label
    */
    function acc_print_label()
    {
        if($this->input->post('item_code'))
        {
           
            $this->load->model('item_distribution');
            if($this->item_distribution->update_for_acc($this->input->post('item_code'),$this->input->post('status')))
            {
                echo 1;
            }
            else
            {
                echo 0;
            }            
        }        
    }
    /**
    *Cetak mutasi pdf
    */
	function cetak_mutasi_pdf($head, $list_data, $footer)
	{
		require_once('lib/tcpdf/config/lang/eng.php');
		require_once('lib/tcpdf/tcpdf.php');
        $jumlah_toko = $this->data['shop_count'];
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Nicola Asuni');
		$pdf->SetTitle('TCPDF Example 006');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins (left,,right)
		$pdf->SetMargins(9, 20, 9);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 10);
		$pdf->setPageUnit('mm');
        if($jumlah_toko <= 10)
        {
		    $size = array(216,165);
        }
        else
        {
            $width = 216 + (($jumlah_toko - 10)*10);
            
            $size = array($width,216);
        }
		$pdf->setPageFormat($size,'P');
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
		
		//set some language-dependent strings
		$pdf->setLanguageArray($l); 

		// ----------------------------------------------------------------------------------		
		// set font
		$pdf->SetFont('dejavusans', '', 8);
		//print page
        
        foreach($list_data as $data)
        {
            $pdf->AddPage();            
            $pdf->writeHTML($head.$data.$footer, true, 0, true, 0);
            if($jumlah_toko<=10)
            	$pdf->writeHTMLCell(30,15,188,5,'<span style="font-size:30pt">'.$this->data['sup_region'].'</span>','right');
            else
            	$pdf->writeHTMLCell(30,15,210,5,
            		'<span style="font-size:30pt;">'.$this->data['sup_region'].'</span>',            		
            		'right');
            
        }
        
		//-------------------------------------------------------------------------------------
		//Close and output PDF document
		$pdf->Output('print_mutasi.pdf', 'I');   
		
	}
    /**
    *Fungsi retur barang
    */
	function retur($param='')
	{
        //retur barang
        if($param == 'tambah')
        {
            if($this->input->post('submit_simpan_retur'))
            {
                if($this->input->post('shop_code') && $this->input->post('retur_code') && $this->input->post('tgl_retur'))
                {
                    $data = array(
                            'retur_code'=> $this->input->post('retur_code'),
                            'retur_date'=> date('Y-m-d'),
                            'shop_code' => $this->input->post('shop_code'),
                            'op_code'=> $this->session->userdata('p_id')
                        );				
                    //retrieving data from form
                    $item_code = $this->input->post('item_code');						
                    $qty_retur = $this->input->post('qty_retur');
                    $shop_code = $this->input->post('shop_code');
                    $tgl_retur = $this->input->post('tgl_retur');
                    $retur_code = time();
                    //processing retur
                    $this->load->model('item_retur');
                    $this->load->model('item');
                    $success = '';
                    $failed = '';
                    for($i=0;$i<count($item_code);$i++)
                    {
                        if(!empty($item_code[$i]) && $qty_retur[$i] > 0)
                        {
                            $data = array(
                                'retur_code'=>$retur_code,
                                'retur_date'=>$tgl_retur,
                                'item_code'=>$item_code[$i],
                                'quantity'=>$qty_retur[$i],
                                'shop_code'=>$shop_code,
                                'op_code'=>$this->session->userdata('p_id')
                            );
                            if($this->item_retur->insert_item_retur($data))
                            {
                                //tambahin stok yang ada di gudang
                                if($this->item->update_after_retur($data))
                                {
                                    $success .= $item_code[$i].', ';
                                }
                            }
                        }
                        else
                        {
                            if(!empty($item_code[$i]))
                            {
                                $failed .= $item_code[$i].', ';
                            }
                        }
                    }
                    $msg = '';
                    if(!empty($success))
                    {
                        $msg .= '<span style="color:green">Kode barang <b>'.$success.'</b> berhasil diretur</span><br />';
                    }
                    if(!empty($failed))
                    {
                        $msg .= '<span style="color:red">Kode barang <b>'.$success.'</b> gagal diretur</span><br />';
                    }
                    $this->session->set_userdata('form_notify',$msg);
                    redirect('/gudang/retur/tambah/','refresh');
                }
                else 
                {
                    $msg = '<span style="color:red">Terjadi kesalahan, periksa kembali tanggal retur, kode retur, dan kode toko</span>';
                    $this->session->set_userdata('form_notify',$msg);
                }
            }
            
            $this->load->view(config_item('template').'gud_retur',$this->data);
        }
        else if($param == 'rekap')
        {
            if($this->input->post('submit_rekap_retur'))
            {
                if($this->validate_form_retur())
                {
                    $this->load->model('item_retur');
                    $this->load->model('shop');
                    //ambil data supplier
                    $this->load->model('supplier');
                    $query = $this->supplier->get_supplier($this->input->post('sup_code'));
                    if($query->num_rows > 0)
                    {
                        $this->data['supplier'] = $query->row();                    
                    }
                    //taro sup_code di session, untuk lihat retur yang page nya banyak
                    $this->session->set_userdata('sup_code',$this->input->post('sup_code'));
                    $this->session->set_userdata('tgl_awal',$this->input->post('tgl_awal'));
                    $this->session->set_userdata('tgl_akhir',$this->input->post('tgl_akhir'));
                    //rekap retur per supplier
                    $data = array(
                        'sup_code'=>$this->input->post('sup_code'),
                        'tgl_awal'=>$this->input->post('tgl_awal'),
                        'tgl_akhir'=>$this->input->post('tgl_akhir')
                    );
                    $query = $this->item_retur->rekap($data);
                    if($query->num_rows() > 0)
                    {
                        $this->data['total_item'] = $query->num_rows();
                        //setting up pagination
                        $this->load->library('pagination');
                        $config['base_url'] = base_url().'gudang/retur/';
                        $config['total_rows'] = $this->data['total_item'];
                        $config['per_page'] = 20;
                        $this->pagination->initialize($config);
                        $this->data['page'] = $this->pagination->create_links();
                        //populate data
                        $row_data = '';
                        $i=0;                    
                        $this->data['total_retur'] = 0;
                        foreach($query->result() as $row)
                        {
                            if($i<$config['per_page'])
                            {
                                //ambil data toko
                                $temp = $this->shop->get_shop($row->shop_code);
                                $shop = $temp->row();
                                $row_data .= '<tr>
                                                <td>'.++$i.'</td>
                                                <td>'.$row->item_code.'</td>
                                                <td>'.ucwords($row->item_name).'</td>
                                                <td>'.date_to_string($row->retur_date).'</td>
                                                <td>'.$shop->shop_name.'</td>
                                                <td>'.$row->quantity.' item</td>
                                            </tr>';
                            }
                            $this->data['total_retur'] += $row->quantity;
                        }
                        $this->data['row_data'] = $row_data;                    
                    }
                    else
                    {
                        $this->data['err_msg'] = '<span style="color:red">Supplier <b>'.ucwords($this->data['supplier']->sup_name).'</b> tidak memiliki retur</span>';
                    }
                }
            }
            $this->load->view(config_item('template').'gud_rekapretur',$this->data);
        }
        else if(intval($param) > 0 || $param == '')
        {
            if($this->session->userdata('sup_code'))
            {
                $sup_code = $this->session->userdata('sup_code');
                $tgl_awal = $this->session->userdata('tgl_awal');
                $tgl_akhir = $this->session->userdata('tgl_akhir');
                //ambil data supplier
                //ambil data supplier
                $this->load->model('supplier');
                $query = $this->supplier->get_supplier($sup_code);
                if($query->num_rows > 0)
                {
                    $this->data['supplier'] = $query->row();                    
                }
                //loading data retur per page
                $this->load->model('item_retur');
                $this->load->model('shop');
                $query = $this->item_retur->rekap(array('sup_code'=>$sup_code,'tgl_awal'=>$tgl_awal,'tgl_akhir'=>$tgl_akhir));
                if($query->num_rows() > 0)
                {                    
                    $this->data['total_item'] = $query->num_rows();
                    //setting up pagination
                    $this->load->library('pagination');
                    $config['base_url'] = base_url().'gudang/retur/';
                    $config['total_rows'] = $this->data['total_item'];
                    $config['per_page'] = 20;
                    $this->pagination->initialize($config);
                    $this->data['page'] = $this->pagination->create_links();
                    //applying pagination on displaying result            
                    if(isset($param) && intval($param) > 0)
                    {
                        $page_min = $param;
                        $page_max = $page_min + $config['per_page'];
                    }
                    else
                    {
                        $page_min = 0;
                        $page_max = $config['per_page'];
                    }
                    $row_data = '';
                    $i = 0;                    
                    $this->data['total_retur'] = 0;
                    foreach($query->result() as $row)
                    {
                        if($i>=$page_min && $i <$page_max)
                        {
                            //ambil data toko
                            $temp = $this->shop->get_shop($row->shop_code);
                            $shop = $temp->row();
                            $row_data .= '<tr>
                                            <td>'.++$i.'</td>
                                            <td>'.$row->item_code.'</td>
                                            <td>'.ucwords($row->item_name).'</td>
                                            <td>'.date_to_string($row->retur_date).'</td>
                                            <td>'.$shop->shop_name.'</td>
                                            <td>'.$row->quantity.' item</td>
                                        </tr>';
                        }
                        else
                        {
                            $i++;
                        }
                        $this->data['total_retur'] += $row->quantity;
                    }
                    $this->data['row_data'] = $row_data;                    
                }
                else
                {
                    $this->data['err_msg'] = '<span style="color:red">Supplier <b>'.ucwords($this->data['supplier']->sup_name).'</b> tidak memiliki retur</span>';
                }
            }
            else
            {
                redirect('gudang/retur/rekap');
            }
            $this->load->view(config_item('template').'gud_rekapretur',$this->data);
        }
        /*
        else if(intval($param) > 0)
        {
            $this->load->model('item_retur');
            $query = $this->item_retur->get_retur(array('retur_code'=>$param));
            if($query->num_rows() > 0)
            {
                $view_retur = '';
                $i=0;
                $total_rupiah =0;
                $total_qty = 0;
                foreach($query->result() as $row)
                {
                    $total = $row->quantity*$row->item_hj;
                    $view_retur .= '<tr>
                                        <td>'.++$i.'</td>
                                        <td>'.$row->item_code.'</td>
                                        <td>'.$row->item_name.'</td>                                        
                                        <td>'.$row->sup_code.'</td>
                                        <td class="right">'.number_format($row->item_hj,0,',','.').',-</td>
                                        <td>'.$row->quantity.'</td>
                                        <td class="right">'.number_format($total,0,',','.').',-</td>
                                    </tr>';
                    $total_rupiah += $total;
                    $total_qty += $row->quantity;
                }
                $view_retur .= '<tr>
                                    <td class="right" colspan="5" >T O T A L</td>
                                    <td>'.$total_qty.' item </td>
                                    <td class="right">'.number_format($total_rupiah,0,',','.').',-</td>
                                </tr>';
                $this->data['view_retur'] = $view_retur;
                $this->data['shop_name'] = $row->shop_name;
                $this->data['retur_code'] = $param;
                $this->data['tgl_retur'] = $row->retur_date;
            }
            $this->load->view(config_item('template').'gud_rekapretur',$this->data);
        }*/
	}
    /**
    * validasi form retur
    */
    function validate_form_retur()
    {
        $this->load->library('form_validation');
        //setting up the rule
        $this->form_validation->set_rules('tgl_awal','tanggal awal','required');
        $this->form_validation->set_rules('tgl_akhir','tanggal akhir','required');
        $this->form_validation->set_rules('sup_code','kode supplier','required');
        if($this->form_validation->run() == FALSE)
        {
            $this->data['err_msg'] = '<span style="color:red">Terjadi kesalahan! Pastikan bahwa informasi yang diminta telah dimasukkan dengan benar</span>';
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
   /*
    **Funngsi cetak pdf
    */
    function cetak_pdf($head,$list_item,$footer)
    {
        require_once('lib/tcpdf/config/lang/eng.php');
        require_once('lib/tcpdf/tcpdf.php');

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 006');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //set margins
        $pdf->SetMargins(9, 20, 9);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->setPageUnit('mm');
        $size = array(216,165);
        $pdf->setPageFormat($size,'P');
        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
        
        //set some language-dependent strings
        $pdf->setLanguageArray($l); 

        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('dejavusans', '', 8);
        
        foreach($list_item as $rows)
        {
            // add a page
            $pdf->AddPage();
            //echo $html; exit;
            $html = $head.$rows.$footer;             
            $pdf->writeHTML($head.$rows.$footer, true, 0, true, 0);
            $pdf->writeHTMLCell(15,15,188,5,'<span style="font-size:30pt">'.$this->data['shop_code'].'</span>','right');
        }
        
        // ---------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('tes.pdf', 'I');     
            
    }
    /**
    *List supplier untuk hari ini yang ada barang
    **/
    function list_supplier()
    {
        $this->load->model('item_distribution');
        $this->load->model('supplier');
        $query = $this->item_distribution->get_supplier_for_printing();
        if($query->num_rows() > 0)
        {            
            $this->data['list_supp'] ='<select name="sup_code">';
            foreach($query->result() as $row)
            {
                $temp = $this->supplier->get_supplier($row->sup_code);
                if($temp->num_rows() > 0)
                {
                    $supplier = $temp->row();
                    $this->data['list_supp'] .= '<option value="'.$supplier->sup_code.'">'.ucwords($supplier->sup_name).'</option>';
                }
            }
            $this->data['list_supp'] .= '</select>';
        }
    }
    /**
    *Membuat list toko
    **/
    function list_toko($option="")
    {
        $list_toko = '<select><option>Tidak Ada</option></select>';
        if(empty($option))
        {
            $query = $this->db->get('shop');            
        }
        else if($option == 'bon')
        {
            $query = $this->db->query('select * from (select shop_code from item_distribution where dist_code = "0" group by shop_code) as dist left join shop on shop.shop_code=dist.shop_code where shop_cat != "OBRAL" and shop_cat != "RUSAK"');
        }
        else if($option == 'obral')
        {
            $query = $this->db->query('select * from (select shop_code from item_distribution where dist_code = "0" group by shop_code) as dist left join shop on shop.shop_code=dist.shop_code where shop_cat = "OBRAL"');
        }
        else if($option == 'obral_pdf')
        {
            $query = $this->db->query('select * from (select shop_code from item_distribution where dist_code != "0" group by shop_code) as dist left join shop on shop.shop_code=dist.shop_code where shop_cat = "OBRAL"');
        }
        else if($option == 'rusak')
        {
            $query = $this->db->query('select * from (select shop_code from item_distribution where dist_code = "0" group by shop_code) as dist left join shop on shop.shop_code=dist.shop_code where shop_cat = "RUSAK"');
        }
        else if($option == 'rusak_pdf')
        {
            $query = $this->db->query('select * from (select shop_code from item_distribution where dist_code != "0" group by shop_code) as dist left join shop on shop.shop_code=dist.shop_code where shop_cat = "RUSAK"');
        }
        else if($option == 'pdf')
        {
            $query = $this->db->query('select * from (select shop_code from item_distribution where dist_code != "0" group by shop_code) as dist left join shop on shop.shop_code=dist.shop_code');
        }
        else if($option == 'export')
        {
            $query = $this->db->query('select shop.* from (select shop_code from item_distribution where dist_code != "0" and export=0 group by shop_code) as dist left join shop on shop.shop_code=dist.shop_code where shop_cat != "OBRAL" and shop_cat != "RUSAK"');
        }
        else if($option == 'label')
        {
        	$query = $this->db->query('select * from shop where shop_cat != "OBRAL" and shop_cat != "RUSAK"');
        }
        //processing query result
        if($query->num_rows())
        {
            $list_toko ='<select name="shop_code" style="padding:0;margin:0;height:22px;">';
            foreach($query->result() as $row)
            {
                $list_toko .= '<option value="'.$row->shop_code.'">'.ucwords($row->shop_name).'</option>';
            }
            $list_toko .= '</select>';
        }
        return $list_toko;
    }
    /**
    *Fungsi untuk mengekspor file ke .txt
    **/
    function export_file_txt($data_txt)
    {
        //print per toko
        $filename = "data/label-".$this->session->userdata('p_id').".doc";        
        //initialize the file
        if(!file_exists($filename))
        {
            $file = fopen($filename,"w");  
            $cek = fwrite($file,'-');
            fclose($file);
        }        
        if(is_writable($filename))
        {
            $file = fopen($filename,"w");  
            $cek = fwrite($file,$data_txt);
            fclose($file);
            return TRUE;
        }
        else
        {
            return FALSE;
        }
        //exit;
    }
	/**
	*Fungsi untuk melihat stok gudang
	*/
	function stok($param='')
	{
        //jika search berdasarkan keyword
		if($this->input->post('submit_search_stock'))
        {
	    $this->session->set_userdata('keywords',$this->input->post('keywords'));
            $keywords = $this->input->post('keywords');
        }
        else
        {
	    if($this->session->userdata('keywords'))
	    {
			$keywords = $this->session->userdata('keywords');
	    }
	    else 
	    {
            	$keywords = '';
	    }
        }        
        //tampilkan datanya
        $this->load->model('item');
        $query = $this->item->search_item(array('keywords'=>$keywords));        
        if($query->num_rows > 0)
        {            
            $this->data['total_item'] = $query->num_rows(); 
            //setting up pagination
            $this->load->library('pagination');
            $config['base_url'] = base_url().'gudang/stok/';
            $config['total_rows'] = $this->data['total_item'];
            $config['per_page'] = 20;
            $this->pagination->initialize($config);
            $this->data['page'] = $this->pagination->create_links();
            //applying pagination on displaying result            
            if(isset($param) && intval($param) > 0)
            {
                $page_min = $param;
                $page_max = $page_min + $config['per_page'];
            }
            else
            {
                $page_min = 0;
                $page_max = $config['per_page'];
            }
            $i = 0;
            $this->data['row_data'] = '';            
            foreach($query->result() as $row)
            {                
                if($i>=$page_min && $i <$page_max)
                {
                    if($row->item_hj == 0 )
                    {
                        $item_hj = '<span style="color:red">not available</span>';
                    }
                    else
                    {
                        $item_hj = number_format($row->item_hj,0,',','.').',-';
                    }
                    //hanya supervisor yang boleh lihat harga modal
                    $hm = '';
                    if($this->session->userdata('p_role') == 'supervisor' || $this->session->userdata('p_role') == 'operator_retur')
                    {
                        $hm = '<td style="text-align:right"> '.number_format($row->item_hp,0,',','.').',-'.'&nbsp;</td>';
                    }
                    $hj = '<td style="text-align:right"> '.$item_hj.'&nbsp;</td>';
                    if($this->session->userdata('p_role') == 'operator_retur')
                    {
                        $hj = '';
                    }
                    //yang boleh hapus hanya supervisor
                    $button_del = '';
                    if($this->session->userdata('p_role') == 'supervisor')                    
                        $button_del = '<span class="button"><input type="button" name="submit_hapus" value="Hapus" class="button" onclick="removeItem(\''.$row->item_code.'\')"/></span>';
                    //yang boleh ubah hanya supervisor dan operator
                    $button_ubah='';
                    if($this->session->userdata('p_role') == 'supervisor' || $this->session->userdata('p_role') == 'operator')
                    {
                        $button_ubah = '<td>'.form_open('gudang/ubah').'
                                        <input type="hidden" name="item_code" value="'.$row->item_code.'"/>
                                        <span class="button"><input type="submit" name="submit_ubah" value="Ubah" class="button"/></span>
                                        '.$button_del.'
                                        '.form_close().'</td>';
                    }
                    $jumlah = $row->item_hj*$row->item_qty_stock;
                    $this->data['row_data'] .= '<tr>
                                                    <td>'.++$i.'</td>
                                                    <td>'.$row->item_code.'</td>
                                                    <td class="left">'.ucwords($row->item_name).'</td>                                                    
                                                    <td class="left">'.ucwords($row->cat_code).'</td>                                                    
                                                    <td class="left">'.ucwords($row->sup_name).'</td>                                                 
                                                    '.$hm.'
                                                    '.$hj.'
                                                    <td>'.$row->item_qty_stock.'</td>   
                                                    <td>'.number_format($jumlah,0,',','.').'</td>                                                 
                                                    '.$button_ubah.'                                                    
                                                </tr>';
                }
                else
                {
                    $i++;
                }
            }            
        }
        else
        {
            $this->data['err_msg'] = '<span style="color:red">Data tidak ditemukan, coba kata kunci yang lain.</span>';
        }
        $this->data['page_title'] .= ' :. Stok Gudang';
		$this->load->view(config_item('template').'gud_stok',$this->data);
	}
    /**
    * Fungsi untuk hapus barang yang dianggap salah
    */
    function hapus($param='')
    {
        if(!empty($param))
        {
            $this->load->model('item');
            if($this->item->hapus($param))
            {
                $this->session->set_userdata('msg','<span style="color:green">Kode barang <b>'.$param.'</b> telah dihapus</span>');
            }
            else
            {
                $this->session->set_userdata('msg','<span style="color:red">Data tidak ditemukan</span>');     
            }
        }
        else
        {
            $this->session->set_userdata('msg','<span style="color:red">Data tidak ditemukan</span>');           
        }
        redirect('gudang/stok');
    }
    /**
    *ubah data barang, kalau2 ada salah input
    */
    function ubah()
    {
        //simpan perubahan
        $this->load->model('item');
        if($this->input->post('submit_ubah_barang'))
        {
            $data = array(
                'item_code'=> $this->input->post('item_code'),
                'item_name'=> $this->input->post('item_name'),
                'sup_code'=> $this->input->post('sup_code'),
                'item_hp'=> $this->input->post('item_hp'),
                'item_hj'=> $this->input->post('item_hj'),
                'quantity'=> $this->input->post('quantity')
            );
            //kalau operator yang diedit adalah hp, qty, sup_code, name
            if($this->session->userdata('p_role') == 'operator')
            {
                if($this->item->edit_item($data,1))
                {
                    $this->data['err_msg'] = '<span style="color:green">Data telah disimpan</span>';
                }
                else
                {
                    $this->data['err_msg'] = '<span style="color:red">Terjadi kesalahan data, pastikan informasi yang diberikan sudah benar</span>';
                }
            }
            //kalau operator yang diedit adalah hj, disc
            else if($this->session->userdata('p_role') == 'supervisor')
            {
                if($this->item->edit_item($data,2))
                {
                    $this->data['err_msg'] = '<span style="color:green">Data telah disimpan</span>';
                }
                else
                {
                    $this->data['err_msg'] = '<span style="color:red">Terjadi kesalahan data, pastikan informasi yang diberikan sudah benar</span>';
                }
            }            
            //tampilin barang setelah diedit
            $query = $this->item->get_item(array('item_code'=>$this->input->post('item_code')));
            if($query->num_rows() > 0)
            {
                $this->data['item'] = $query->row();                
            }
        }
        //tampilin untuk diubah
        if($this->input->post('submit_ubah'))
        {            
            $query = $this->item->get_item(array('item_code'=>$this->input->post('item_code')));
            if($query->num_rows() > 0)
            {
                $this->data['item'] = $query->row();                
            }
        }
        $this->load->view(config_item('template').'gud_ubah',$this->data);
    }
    /**
    * Fungsi untuk untuk laporan barang rusak
    */
    function rusak()
    {
        //preview barang rusak yang akan dicetak
        if($this->input->post('submit_preview_rusak'))
        {
            $this->load->model('item_distribution');
            $this->load->model('shop');
            //ambil dan tentukan tanggal serta kode bon, toko tujuan
            $query = $this->shop->get_shop($this->input->post('shop_code'));
            $this->data['shop'] = $query->row();
            $this->data['tgl_bon'] = date('Y-m-d');
            $this->data['dist_code'] = time();
            $query = $this->item_distribution->get_item_for_bon($this->input->post('shop_code'));                
            if($query->num_rows() > 0)
            {
                $i=0;
                $row_data='';
                $total_rupiah = 0;
                $total_qty = 0;
                foreach($query->result() as $row)
                {
                    $jumlah = $row->item_hj *(1 - $row->item_disc/100) * $row->quantity;
                    $row_data .= '<tr>
                                    <td>'.++$i.'</td>
                                    <td>'.$row->sup_code.'</td>
                                    <td>'.$row->item_code.'</td>
                                    <td class="left">'.$row->item_name.'</td>                                    
                                    <td class="right">'.number_format($row->item_hj,0,',','.').',-</td>
                                    <td>'.$row->quantity.' item</td>
                                    <td class="right">'.number_format($jumlah,0,',','.').',-</td>
                                    <td></td><td></td><td></td>
                                </tr>';
                    $total_rupiah += $jumlah;
                    $total_qty += $row->quantity;                        
                }
                $row_data .= '<tr><td colspan="5">T O T A L</td><td>'.$total_qty.' item</td><td class="right">'.number_format($total_rupiah,0,',','.').',-</td><td></td><td></td><td></td></tr>';
                $this->data['row_data'] = $row_data;
            }
            else
            {
                $this->data['err_msg'] = '<span style="color:red">Tidak dapat mencetak bon. Anda belum mencetak label atau bon sudah pernah dicetak</span>';
            }
            //buat bon barang obral untuk outlet barang obral yang sedang ditampilkan
            $this->item_distribution->create_bon(array('shop_code'=>$this->input->post('shop_code'),'dist_code'=>$this->data['dist_code']));
        }
        //cetak barang rusak ke pdf
        if($this->input->post('submit_cetak_rusak') || ($this->uri->segment(3) && $this->uri->segment(4)))
        {
            //ambil barang untuk dicetak bonnya
            $kode_bon = $this->uri->segment(3);
            $shop_code = $this->uri->segment(4);
            if(empty($kode_bon))
            {
                $kode_bon = $this->input->post('dist_code');
            }                
            $this->load->model('item_distribution');
            $this->load->model('shop');
            $this->load->model('item');
            $query = $this->item_distribution->get_item_for_pdf(array('dist_code'=>$kode_bon,'shop_code'=>$shop_code));
            if($query->num_rows() > 0)
            {
                $item_jml = $query->num_rows();
                //ambil mutasi
                $mutasi = $query->row();
                $temp = $this->shop->get_shop($mutasi->shop_code);
                $shop = $temp->row();
                $this->data['shop_code'] = $shop->shop_code;
                $head = '<div style="margin-top: 5px;">
                        <h3 style="text-align: center;">BON BARANG RUSAK</h3>
                        <table style="width: 700px;">
                        <tr><td style="width: 80px;">Kode Bon</td><td style="width:260px;">: '.$kode_bon.'</td>
                        <td style="width: 70px;text-align:right;">Tanggal</td><td style="width:100px;text-align:right">: '.date_to_string($mutasi->dist_out).'</td></tr>
                        <tr><td style="width: 80px; ">Toko Tujuan</td><td>: '.strtoupper($shop->shop_name).'</td></tr>                                
                        </table>
                    </div><br />';
                    
                $head .= '<table style="width: 560px;" border="1" cellpadding="3">
                        <tr>
                        <td style="width: 20px;text-align: center;">No</td>	
                        <td style="width: 50px;text-align: center;">Supplier</td>
                        <td style="width: 70px;text-align: center">Kode Barang</td> 
                        <td style="width: 110px;text-align: center;">Nama Barang</td>                        
                        <td style="width: 75px;text-align: center;">Harga Jual (Rp.)</td>
                        <td style="width: 40px;text-align: center;">Qty Brg</td>
                        <td style="width: 75px;text-align: center;">Jumlah (Rp.) </td>
                        <td style="width: 40px;text-align: center;">Ganti Barang</td>
                        <td style="width: 40px;text-align: center;">Ganti Uang</td>
                        <td style="width: 40px;text-align: center;">Potong Bon</td>
                        </tr>';
                $i = 0;
                $jumlah_item = 0;
                $total = 0;
                $j=0;$index=0;
                
                foreach($query->result() as $row)
                {
                    $temp = $this->item->get_item(array('item_code'=>$row->item_code));
                    $item = $temp->row();
                    $jumlah = $item ->item_hj *(1 - $row->item_disc/100) * $row->quantity;
                    $jumlah_item += $row->quantity;
                    $total += $jumlah;
                    if(!isset($list_item[$index]))
                    {
                        $list_item[$index] = '';
                    }
                    $list_item[$index].= '<tr>
                        <td style="width: 20px;height:;text-align: center;">'.++$i.'</td>	
                        <td style="width: 50px;text-align: center;">'.$item->sup_code.'</td>
                        <td style="width: 70px;text-align:center">'.$item->item_code.'</td>                          
                        <td style="width: 110px;">'.strtoupper($item->item_name).'</td>                                                
                        <td style="width: 75px;text-align: right;">'.number_format($item->item_hj,'0',',','.').',-</td>
                        <td style="width: 40px;text-align:right">'.$row->quantity.'</td>
                        <td style="width: 75px;text-align: right;">'.number_format($jumlah,'0',',','.').',-</td>
                        <td style="width: 40px;text-align: right;">&nbsp;</td>
                        <td style="width: 40px;text-align: right;">&nbsp;</td>
                        <td style="width: 40px;text-align: right;">&nbsp;</td>
                        </tr>';
                    $j++;
                    if($j==15)
                    {
                        //yang ditutup table disini khusus yang
                        if($item_jml%15 > 0)
                        {                            
                            $list_item[$index] .= '</table>';
                        }
                        else
                        {                           
                            if($index < ($item_jml/15)-1)
                                $list_item[$index] .= '</table>';
                        }
                        $j=0;$index++;                        
                    }                 
                }    
                //Jika jenis item pas kelipatan 15, maka index diturunkan satu dan harus ditutup langsung
                if($item_jml%15 == 0)
                {               
                    $list_item[--$index] .= '<tr>						
                                                <td style="width: 325px;text-align:right" colspan="6"> T O T A L</td>
                                                <td style="width: 40px;text-align:right">'.$jumlah_item.'</td>
                                                <td style="width: 75px;text-align: right;">'.number_format($total,'0',',','.').',-</td>
                                                <td style="width: 40px;text-align: right;">&nbsp;</td>
                                                <td style="width: 40px;text-align: right;">&nbsp;</td>
                                                <td style="width: 40px;text-align: right;">&nbsp;</td>
                                                </tr>
                                        </table>';                 
                }
                //yang bukan kelipatan  15, ya normal                
                else
                {
                    $list_item[$index] .= '<tr>						
                                                <td style="width: 325px;text-align:right" colspan="7"> T O T A L</td>
                                                <td style="width: 40px;text-align:right">'.$jumlah_item.'</td>
                                                <td style="width: 75px;text-align: right;">'.number_format($total,'0',',','.').',-</td>
                                                <td style="width: 40px;text-align: right;">&nbsp;</td>
                                                <td style="width: 40px;text-align: right;">&nbsp;</td>
                                                <td style="width: 40px;text-align: right;">&nbsp;</td>
                                                </tr>
                                        </table>';
                }
                $footer = '<br /><table style="text-align:center;">
                                        <tr><td>Bagian Gudang</td><td>Bagian Transport</td><td>Bagian Toko</td><td>Bagian Komputer</td></tr>                            
                                </table>';
                //echo htmlentities($list_item[1]);exit;
                $this->cetak_pdf($head,$list_item,$footer);
            }
            else
            {
                $this->data['err_msg'] = '<p><span style="color:red">Data tidak ditemukan</span></p>';
            } 
        }
        //output to browser        
        if($this->uri->segment(3) == 'rekap')
        {
            if($this->input->post('submit_rekap_rusak'))
            {
                $this->load->model('item_distribution');
                //ambil bon toko sesuai tanggal yang diquery kan
                $tgl = $this->input->post('date_bon');
                $query = $this->item_distribution->get_bon_by_toko($this->input->post('shop_code'),$tgl);
                if($query->num_rows() > 0)
                {
                    $i=0;
                    $row_data='';
                    foreach($query->result() as $row)
                    {
                        $row_data .= '<tr>
                                        <td>'.++$i.'</td>
                                        <td>'.$row->dist_code.'</td>
                                        <td>'.date_to_string($row->dist_out).'</td>
                                        <td>'.$row->jenis_brg.' macam</td>
                                        <td>'.$row->jumlah_brg.' item</td>
                                        <td>
                                        <!--'.form_open('gudang/cetak/bon').'-->
                                            <input type="hidden" name="dist_code" value="'.$row->dist_code.'"/>
                                            <a href="'.base_url().'gudang/rusak/'.$row->dist_code.'/'.$this->input->post('shop_code').'" target="new"/><span class="button"><input class="button" type="submit" name="submit_cetak_bon" value="Cetak"></span></a>
                                        <!--'.form_close().'-->
                                        </td>
                                    </tr>';
                    }
                    $this->data['row_data'] = $row_data;
                }
                else
                {
                    $this->data['err_msg'] = '<p><span style="color:red">Data tidak ditemukan.</span></p>';
                }
                //ambil data toko
                $this->load->model('shop');
                $query = $this->shop->get_shop($this->input->post('shop_code'));
                $this->data['shop'] = $query->row();
            }
            $this->data['list_toko_rusak_pdf'] = $this->list_toko('rusak_pdf');
            $this->load->view(config_item('template').'gud_rekaprusak',$this->data);
        }
        else 
        {
            $this->data['list_toko_rusak'] = $this->list_toko('rusak');
            $this->load->view(config_item('template').'gud_rusak',$this->data);
        }
    }
    /**
    * Fungsi untuk untuk laporan barang obral
    */
    function obral()
    {
        //preview sebelum cetak bon obral
        if($this->input->post('submit_preview_obral'))
        {
            $this->load->model('item_distribution');
            $this->load->model('shop');
            //ambil dan tentukan tanggal serta kode bon, toko tujuan
            $query = $this->shop->get_shop($this->input->post('shop_code'));
            $this->data['shop'] = $query->row();
            $this->data['tgl_bon'] = date('Y-m-d');
            $this->data['dist_code'] = time();
            $query = $this->item_distribution->get_item_for_bon($this->input->post('shop_code'));                
            if($query->num_rows() > 0)
            {
                $i=0;
                $row_data='';
                $total_rupiah = 0;
                $total_qty = 0;
                foreach($query->result() as $row)
                {
                    $jumlah = $row->item_hj *(1 - $row->item_disc/100) * $row->quantity;
                    $row_data .= '<tr>
                                    <td>'.++$i.'</td>
                                    <td>'.$row->sup_code.'</td>
                                    <td>'.$row->item_code.'</td>
                                    <td class="left">'.$row->item_name.'</td>
                                    <td class="left">'.$row->item_disc.'</td>
                                    <td class="right">'.number_format($row->item_hj,0,',','.').',-</td>
                                    <td>'.$row->quantity.' item</td>
                                    <td class="right">'.number_format($jumlah,0,',','.').',-</td>
                                </tr>';
                    $total_rupiah += $jumlah;
                    $total_qty += $row->quantity;                        
                }
                $row_data .= '<tr><td colspan="6">T O T A L</td><td>'.$total_qty.' item</td><td class="right">'.number_format($total_rupiah,0,',','.').',-</td></tr>';
                $this->data['row_data'] = $row_data;
            }
            else
            {
                $this->data['err_msg'] = '<span style="color:red">Tidak dapat mencetak bon. Anda belum mencetak label atau bon sudah pernah dicetak</span>';
            }
            //buat bon barang obral untuk outlet barang obral yang sedang ditampilkan
            $this->item_distribution->create_bon(array('shop_code'=>$this->input->post('shop_code'),'dist_code'=>$this->data['dist_code']));
        }
        //cetak bon obral ke pdf
        if($this->input->post('submit_cetak_obral') || ($this->uri->segment(3) && $this->uri->segment(4)))
        {
            //ambil barang untuk dicetak bonnya
            $kode_bon = $this->uri->segment(3);
            $shop_code = $this->uri->segment(4);
            if(empty($kode_bon))
            {
                $kode_bon = $this->input->post('dist_code');
            }                
            $this->load->model('item_distribution');
            $this->load->model('shop');
            $this->load->model('item');
            $query = $this->item_distribution->get_item_for_pdf(array('dist_code'=>$kode_bon,'shop_code'=>$shop_code));
            if($query->num_rows() > 0)
            {
                $item_jml = $query->num_rows();
                //ambil mutasi
                $mutasi = $query->row();
                $temp = $this->shop->get_shop($mutasi->shop_code);
                $shop = $temp->row();
                $this->data['shop_code'] = $shop->shop_code;
                $head = '<div style="margin-top: 5px;">
                        <h3 style="text-align: center;">BON BARANG OBRAL</h3>
                        <table style="width: 700px;">
                        <tr><td style="width: 80px;">Kode Bon</td><td style="width:260px;">: '.$kode_bon.'</td>
                        <td style="width: 70px;text-align:right;">Tanggal</td><td style="width:100px;text-align:right">: '.date_to_string($mutasi->dist_out).'</td></tr>
                        <tr><td style="width: 80px; ">Toko Tujuan</td><td>: '.strtoupper($shop->shop_name).'</td></tr>                                
                        </table>
                    </div><br />';
                    
                $head .= '<table style="width: 600px;" border="1" cellpadding="3">
                        <tr>
                        <td style="width: 20px;text-align: center;">No</td>						
                        <td style="width: 70px;text-align: center">Kode Barang</td>
                        <td style="width: 70px;text-align: center;">Kelompok Brg</td>
                        <td style="width: 50px;text-align: center;">Supplier</td>
                        <td style="width: 110px;text-align: center;">Nama Barang</td>
                        <td style="width: 50px;text-align: center;">Disc %</td> 
                        <td style="width: 75px;text-align: center;">Harga Jual (Rp.)</td>
                        <td style="width: 40px;text-align: center;">Qty Brg</td>
                        <td style="width: 75px;text-align: center;">Jumlah (Rp.) </td>
                        </tr>';
                $i = 0;
                $jumlah_item = 0;
                $total = 0;
                $j=0;$index=0;
                
                foreach($query->result() as $row)
                {
                    $temp = $this->item->get_item(array('item_code'=>$row->item_code));
                    $item = $temp->row();
                    $jumlah = $item ->item_hj *(1 - $row->item_disc/100) * $row->quantity;
                    $jumlah_item += $row->quantity;
                    $total += $jumlah;
                    if(!isset($list_item[$index]))
                    {
                        $list_item[$index] = '';
                    }
                    $list_item[$index].= '<tr>
                        <td style="width: 20px;height:;text-align: center;">'.++$i.'</td>						
                        <td style="width: 70px;text-align:center">'.$item->item_code.'</td>
                        <td style="width: 70px;height:;text-align: center;">'.$item->cat_code.'</td>
                        <td style="width: 50px;text-align: center;">'.$item->sup_code.'</td>
                        <td style="width: 110px;">'.strtoupper($item->item_name).'</td>
                        <td style="width: 50px;text-align: center;">'.$row->item_disc.'</td>                        
                        <td style="width: 75px;text-align: right;">'.number_format($item->item_hj,'0',',','.').',-</td>
                        <td style="width: 40px;text-align:right">'.$row->quantity.'</td>
                        <td style="width: 75px;text-align: right;">'.number_format($jumlah,'0',',','.').',-</td>
                        </tr>';
                    $j++;
                    if($j==15)
                    {
                        //yang ditutup table disini khusus yang
                        if($item_jml%15 > 0)
                        {                            
                            $list_item[$index] .= '</table>';
                        }
                        else
                        {                           
                            if($index < ($item_jml/15)-1)
                                $list_item[$index] .= '</table>';
                        }
                        $j=0;$index++;                        
                    }                 
                }    
                //Jika jenis item pas kelipatan 15, maka index diturunkan satu dan harus ditutup langsung
                if($item_jml%15 == 0)
                {               
                    $list_item[--$index] .= '<tr>						
                                                <td style="width: 445px;text-align:right" colspan="7"> T O T A L</td>
                                                 <td style="width: 40px;text-align:right">'.$jumlah_item.'</td>
                                                <td style="width: 75px;text-align: right;">'.number_format($total,'0',',','.').',-</td>
                                                </tr>
                                        </table>';                 
                }
                //yang bukan kelipatan  15, ya normal                
                else
                {
                    $list_item[$index] .= '<tr>						
                                                <td style="width: 445px;text-align:right" colspan="7"> T O T A L</td>
                                                 <td style="width: 40px;text-align:right">'.$jumlah_item.'</td>
                                                <td style="width: 75px;text-align: right;">'.number_format($total,'0',',','.').',-</td>
                                                </tr>
                                        </table>';
                }
                $footer = '<br /><table style="text-align:center;">
                                        <tr><td>Bagian Gudang</td><td>Bagian Transport</td><td>Bagian Toko</td><td>Bagian Komputer</td></tr>                            
                                </table>';
                //echo htmlentities($list_item[1]);exit;
                $this->cetak_pdf($head,$list_item,$footer);
            }
            else
            {
                $this->data['err_msg'] = '<p><span style="color:red">Data tidak ditemukan</span></p>';
            }
        }
        //output to browser        
        if($this->uri->segment(3) == 'rekap')
        {
            if($this->input->post('submit_rekap_obral'))
            {
                $this->load->model('item_distribution');
                //ambil bon toko sesuai tanggal yang diquery kan
                $tgl = $this->input->post('date_bon');
                $query = $this->item_distribution->get_bon_by_toko($this->input->post('shop_code'),$tgl);
                if($query->num_rows() > 0)
                {
                    $i=0;
                    $row_data='';
                    foreach($query->result() as $row)
                    {
                        $row_data .= '<tr>
                                        <td>'.++$i.'</td>
                                        <td>'.$row->dist_code.'</td>
                                        <td>'.date_to_string($row->dist_out).'</td>
                                        <td>'.$row->jenis_brg.' macam</td>
                                        <td>'.$row->jumlah_brg.' item</td>
                                        <td>
                                        <!--'.form_open('gudang/cetak/bon').'-->
                                            <input type="hidden" name="dist_code" value="'.$row->dist_code.'"/>
                                            <a href="'.base_url().'gudang/obral/'.$row->dist_code.'/'.$this->input->post('shop_code').'" target="new"/><span class="button"><input class="button" type="submit" name="submit_cetak_bon" value="Cetak"></span></a>
                                        <!--'.form_close().'-->
                                        </td>
                                    </tr>';
                    }
                    $this->data['row_data'] = $row_data;
                }
                else
                {
                    $this->data['err_msg'] = '<p><span style="color:red">Data tidak ditemukan.</span></p>';
                }
                //ambil data toko
                $this->load->model('shop');
                $query = $this->shop->get_shop($this->input->post('shop_code'));
                $this->data['shop'] = $query->row();
            }
            $this->data['list_toko_obral_pdf'] = $this->list_toko('obral_pdf');
            $this->load->view(config_item('template').'gud_rekapobral',$this->data);
        }
        else
        {
            $this->data['list_toko_obral'] = $this->list_toko('obral');
            $this->load->view(config_item('template').'gud_obral',$this->data);
        }
    }
    /**
    *ekspor data ke csv
    */
    function export()
    {
        //preview before export
        if($this->input->post('submit_preview_export'))
        {
            $this->load->model('item_distribution');        
            $this->load->model('shop');  
            //check, apakah data yang dieksport ada dalam 1 bon atau lebih
            $qry = $this->item_distribution->count_bon_export($this->input->post('shop_code'));
            $this->data['num_of_bon'] = $qry->num_rows();
            if($qry->num_rows() > 1 ) //jika lebih dari satu bon
            {
                $i = 0;
                $this->data['row_data'] = '';
                foreach($qry->result() as $row)
                {
                    $this->data['row_data'] .= '<tr>
                                                    <td>'.++$i.'</td>
                                                    <td>'.$row->dist_code.'</td>
                                                    <td>'.$row->jml_item.'</td>
                                                    <td>'.$row->total_item.'</td>
                                                    <td>
                                                        '.form_open('gudang/export').'
                                                        <input type="hidden" name="dist_code" value="'.$row->dist_code.'" />
                                                        <input type="hidden" name="shop_code" value="'.$row->shop_code.'" />
                                                        <span class="button"><input class="button" type="submit" name="submit_export" value="Export" /></span>
                                                        '.form_close().'
                                                    </td>
                                                    </tr>';
                }
                //update status exportnya, isi time aja
                $this->data['export'] = time();                
                $this->item_distribution->update_status_export(array('shop_code'=>$this->input->post('shop_code'),'export'=>$this->data['export']));
            }
            //jika tidak lebih dari satu bon, diproses biasa aja
            else
            {            
                //tampilkan data yang mau diexport
                $query = $this->item_distribution->get_item_for_shop($this->input->post('shop_code'));
                if($query->num_rows() > 0)
                {
                    $this->data['total_item'] = $query->num_rows();
                    $this->data['total'] = 0;
                    $this->data['row_data'] = '';
                    $i = 0;
                    foreach($query->result() as $row)
                    {
                        $this->data['row_data'] .= '<tr>
                                                        <td>'.++$i.'</td>
                                                        <td>'.$row->item_code.'</td>
                                                        <td>'.$row->item_name.'</td>
                                                        <td>'.number_format($row->item_hj,0,',',',').',-</td>
                                                        <td>'.$row->item_disc.'</td>
                                                        <td>'.$row->quantity.'</td>
                                                    </tr>';
                        $this->data['total'] += $row->quantity;
                    } 
                    //update status exportnya, isi time aja
                    $this->data['export'] = time();                
                    $this->item_distribution->update_status_export(array('shop_code'=>$this->input->post('shop_code'),'export'=>$this->data['export']));
                }
                else
                {
                    $this->data['err_msg'] = '<span style="color:red">Tidak ada data export untuk toko ini</span>'; 
                }
                //tampilkan data toko            
                $temp = $this->shop->get_shop($this->input->post('shop_code'));
                $this->data['shop'] = $temp->row(); 
            }
        }
        //export to csv
        if($this->input->post('submit_export'))
        {
            $this->load->model('item_distribution');        
            $this->load->model('shop');        
            $this->load->helper('csv');
            //ambil data keterangan toko
            $temp = $this->shop->get_shop($this->input->post('shop_code'));
            $shop = $temp->row();
            //ambil data untuk dieksport
            if($this->input->post('export')) 
            {
                $query = $this->item_distribution->get_item_export(array('export'=>$this->input->post('export')));
                $filename = $shop->shop_initial;
            }
            else if($this->input->post('dist_code'))
            {                
                //echo 'bolo';exit;
            	$query = $this->item_distribution->get_item_export(array('dist_code'=>$this->input->post('dist_code'),'shop_code'=>$this->input->post('shop_code')));
                $filename=$this->input->post('dist_code');                
            }
            if($query->num_rows() > 0)
            {                                  
                echo query_to_csv($query,TRUE,$filename.'.csv');                               
            }               
        }
        else
        {
            $this->data['page_title'] .= ' :. Export Data';
            $this->data['list_toko'] = $this->list_toko('export');
		    $this->load->view(config_item('template').'gud_export',$this->data);
        }
    }
    /**
    *import data dari csv
    */
    function import()
    {
        $this->load->library('csvreader');
        $file_name = 'data/tes.csv';
        $temp = $this->csvreader->parse_file($file_name);
        echo '<pre>';        
        print_r($temp);       
        echo '</pre>';
    }
    
    /**
     * Fungsi untuk mengubah status label kembali ke nol untuk cetak ulang
     * Dikelompokkan berdasarkan supplier
     */
    function label()
    {
    	$dist_out = $this->session->userdata('dist_out');
    	if($this->input->post('submit_cari_sup'))
    	{
    		$this->session->set_userdata('dist_out', $this->input->post('dist_out'));
    		$dist_out = $this->input->post('dist_out');
    	}
    	    	
    	if($this->input->post('submit_ubah_status'))
    	{
    		$shop_code = $this->input->post('shop_code');
    		$sup_code = $this->input->post('sup_code');
    		$dist_out = $this->input->post('dist_out');
    		$this->load->model('item_distribution');
    		foreach($sup_code as $row)
    		{
    			$this->item_distribution->reset_status_label($row,$dist_out,$shop_code);
    		}
    		echo 1;  
    		exit(0); 		
    	}
    	
    	$this->load->model('item_distribution');
    	if(!empty($dist_out))
    	{
    		//$dist_out = $this->input->post('dist_out');
    		$query = $this->item_distribution->get_cetak_ulang_label($dist_out);
    		if($query->num_rows())
    		{
    			$i=0;
    			$row_data = '';
    			foreach($query->result() as $row)
    			{
    				$row_data .= '<tr>
    	    									<td>'.++$i.'</td>
    	    									<td>'.$row->sup_code.'</td>
    	    									<td>'.strtoupper($row->sup_name).'</td>
    	    									<td>'.$row->jenis.' jenis</td>
    	    									<td>'.$row->jumlah.' item</td>
    	    									<td>
    	    										<input type="checkbox" name="sup_code" value="'.$row->sup_code.'" class="checkbox_status"/>
    	    									</td>
    	    								</tr>';
    			}
    			$this->data['row_data'] = $row_data;
    			$this->data['dist_out'] = $dist_out;
    		}
    		else
    		{
    			$this->data['err_msg'] = '<span style="color:red">Data tidak ditemukan</span>';
    		}
    	}
    	else
    	{
    		$this->data['err_msg'] = '<span style="color:red">Tanggal tidak boleh dikosongkan</span>';
    	}
    	$this->data['list_toko'] = $this->list_toko('label');
    	$this->load->view(config_item('template').'gud_label', $this->data);
    }
    
    function rekapmasuk()
    {
    	if($this->input->post('submit_cari_mutasi'))
    	{
    		if($this->input->post('date_entry'))
    		{
    			$date_entry = $this->input->post('date_entry');
    		}
    		else
    		{
    			$this->data['err_msg'] = '<span style="color:red">Tanggal tidak boleh dikosongkan</span>';
    		}
    	}
    	$this->load->view(config_item('template').'gud_rekapmasuk', $this->data);
    }
    
    function distribusi()
    {
    	if($this->input->post('submit_rekap_distribusi'))
    	{
    		//siapin data shop untuk header
    		$this->load->model('shop');
    		$query = $this->shop->get_shop();
    		$jumlah_toko = $query->num_rows();
    		$this->data['jumlah_toko'] = $jumlah_toko;
    		$shop = array();
    		$header = '<tr>';
            foreach($query->result() as $row)
            {
            	$shop[] = $row->shop_code;
            	$header .= '<td class="header">'.strtoupper($row->shop_initial).'</td>';
            }
            $this->data['header'] = $header.'</tr>';
    		//filter data sesuai parameter
    		$this->data['opsi'] = $this->input->post('opsi');
    		$this->data['tgl_awal'] = $this->input->post('tgl_awal');
    		$this->data['tgl_akhir'] = $this->input->post('tgl_akhir');
    		$this->data['title'] = 'PERIODE : '.date_to_string($this->data['tgl_awal']).' s.d. '.date_to_string($this->data['tgl_akhir']);
    		$this->load->model('item_distribution');
    		$param = array('tgl_awal'=>$this->data['tgl_awal'],'tgl_akhir'=>$this->data['tgl_akhir']);
    		if($this->data['opsi'] == 1)
    			$param['item_code'] = $this->input->post('item_code');
    		else if($this->data['opsi'] == 2)
    			$param['cat_code'] = $this->input->post('cate_code');
    		else if($this->data['opsi'] == 3)
    			$param['sup_code'] = $this->input->post('sup_code');
    		$query = $this->item_distribution->recap_distribution($param);
    		//var_dump($query);exit;
    		if($query->num_rows())
    		{
    			//olah data untuk ditampilkan dalam tabel distribusi barang, per baris mewakili satu kode label
    			$row_data='';
    			$j=0;
    			foreach($query->result() as $item)
    			{
    				$qry = $this->item_distribution->get_item_distribution($item->item_code);
    				$row_shop = array();
    				foreach($qry->result() as $item_dist)
    				{
    					$row_shop = $this->build_row_data_shop($row_shop,$shop,$jumlah_toko,$item_dist);
    				}
    				$row_shop_data = '';
    				for($i=0;$i<$jumlah_toko;$i++)
    				{
	    				if(isset($row_shop[$i]))
	    				{
	    					$row_shop_data .= $row_shop[$i];
	    				}
	    				else
	    				{
	    					$row_shop_data .=  '<td></td>';
	    				}
    				}
    				$this->load->model('supplier');
    				if(!$this->check_if_medan($item->sup_code))
    				{
    					$hp = floor($item->item_hp + 0.15*$item->item_hp);
    					//$this->data['sup_region'] = 'LMD';
    				}
    				else
    				{
    					$hp = $item->item_hp;
    					//$this->data['sup_region'] = 'MDN';
    				}
    				$row_data .= '<tr>
    								<td>'.++$j.'</td>
    								<td>'.$item->item_code.'</td>
    								<td>'.$item->item_qty_total.'</td>
    								'.$row_shop_data.'
    								<td>'.$item->item_qty_stock.'</td>
    								<td>'.number_format($hp,0,',','.').'</td>
    								<td>'.number_format($item->item_hj,0,',','.').'</td>
    							';	
    			}
    			$this->data['row_data'] = $row_data;
    		}
    	}
    	$this->load->view(config_item('template').'gud_rekapdistribusi', $this->data);
    }
}
/* End of file gudang.php */
/* Location: ./system/application/controllers/gudang.php */
