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
		else if($this->uri->segment(3)=='keluar')
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
                $row_qty = '';
                $i=0;
                foreach($query->result() as $row)
                {
                    $shop .= '<td class="header">'.strtoupper($row->shop_initial).'</td>';
                    $row_qty .= '<td><input type="text" name="qty_'.strtolower($row->shop_initial).'[]" id="qty_'.strtolower($row->shop_initial).'_#" style="width: 25px;" onkeyup="countStok(#)"/></td>';
                    $shop_initial[$i++] = strtolower($row->shop_initial);
                }
                $this->data['shop_name'] = $shop;
                $this->data['row_qty'] = $row_qty;
                $this->data['shop_initial'] = $shop_initial;
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
                $data = $query->row();
				$head = '<div style="margin-top: 5px;">
						    <h3 style="text-align: center;">BON MUTASI KELUAR</h3>
						    <table style="width: 700px;">
							<tr><td style="width: 50px;">SUPPLIER</td><td style="width:300px;">: '.strtoupper($sup->sup_name).'</td>
							<td style="width: 120px;text-align:right;">Tanggal Mutasi</td><td style="width:100px;text-align:right">: '.date_to_string($data->date_entry).'</td></tr>							                              
						    </table>
						</div><br />';
				
                //retrieve data toko
				$query = $this->shop->get_shop();
				$jumlah_toko = $query->num_rows();
				$width = $jumlah_toko * 20;				
				$head .= '<table style="width: 600px;" border="1" cellpadding="3">
					    <tr>
						<td style="width: 60px;text-align: center; vertical-align: middle;" rowspan="2">Kode Label</td>
						<td style="width: 100px;text-align: center;" rowspan="2">Nama</td>
						<td style="width: 25px;text-align: center;" rowspan="2">Qty</td>
						<td style="width: '.$width.'px;text-align: center;" colspan="15">Distribusi</td>
						<td style="width: 50px;text-align: center;" rowspan="2">HM</td>
						<td style="width: 50px;text-align: center;" rowspan="2">Harga Jual</td>						
					    </tr>
					    <tr>';
                foreach($query->result() as $row)
                {
                    $head .= '<td style="width: 20px;text-align: center; font-size: 17px;">'.strtoupper($row->shop_initial).'</td>';
                }
                $head.='</tr>';
                    
				//retrieving data item untuk diprint				
                $query = $this->item_mutasi->get_item_mutasi(array('sup_code'=>$this->input->post('sup_code'))); 
               
				$data='';
				if($query->num_rows() > 0)
				{
					foreach($query->result() as $row)
					{
						$data .='<tr>
								<td style="width: 60px;text-align: center;">'.$row->item_code.'</td>
								<td style="width: 100px;text-align: center;">'.$row->item_name.'</td>
								<td style="width: 25px;text-align: center">'.$row->item_qty_stock.'</td>';
						for($i=0;$i<$jumlah_toko;$i++)
						{
							$data .= '<td style="width: 20px;text-align: center; font-size: 17px;"></td>';
						}
                        //check barang medan atau luar kota
                        if(!$this->check_if_medan($this->input->post('sup_code')))
                        {
						    $hp = floor($row->item_hp + 0.15*$row->item_hp);
                        }
                         else
                        {
                            $hp = $row->item_hp;
                        }
						$data .= '	<td style="width: 50px;text-align: right;">'.number_format($hp,'0',',','.').',-</td>
								<td style="width: 50px;text-align: center;"></td>		
							    </tr>';
					}
                    $data .='</table>';
                    $footer = '<br /><br /><table style="text-align:center;">
                            <tr><td>(. . . . . . . . . . . . . .)</td><td>(. . . . . . . . . . . . . .)</td></tr>                            
                        </table>';
                    //update status item yang udh pernah diprint mutasi jadi 1
                    $this->item_mutasi->update_status(array('sup_code'=>$this->input->post('sup_code')));                    
                    $this->cetak_mutasi_pdf($head, $data, $footer);            
				}
                else
                {
                    $this->data['notifikasi'] = 'Tidak ada data mutasi barang masuk pada tanggal tersebut';                    
                }
				//----------------------------------------------------
				
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
                                        <td>'.$row->item_qty_stock.'</td>
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
                //echo $this->db->last_query();exit;
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
                $query = $this->item_mutasi->get_item_mutasi(array('kode_mutasi'=>$kode_mutasi));                
				$data = $query->row();
				$head = '<div style="margin-top: 5px;">
						    <h3 style="text-align: center;">BON MUTASI MASUK</h3>
						    <table style="width: 700px;">
							<tr><td style="width: 50px;">SUPPLIER</td><td style="width:300px;">: '.strtoupper($data->sup_name).'</td>
							<td style="width: 120px;text-align:right;">Tanggal Mutasi</td><td style="width:100px;text-align:right">: '.date_to_string($data->date_entry).'</td></tr>							                              
						    </table>
						</div><br />';
				//retrieve data toko
				$query = $this->shop->get_shop();
				$jumlah_toko = $query->num_rows();
				$width = $jumlah_toko * 20;				
				$head .= '<table style="width: 600px;" border="1" cellpadding="3">
					    <tr>
						<td style="width: 60px;text-align: center; vertical-align: middle;" rowspan="2">Kode Label</td>
						<td style="width: 100px;text-align: center;" rowspan="2">Nama</td>
						<td style="width: 25px;text-align: center;" rowspan="2">Qty</td>
						<td style="width: '.$width.'px;text-align: center;" colspan="15">Distribusi</td>
						<td style="width: 50px;text-align: center;" rowspan="2">HM (Rp)</td>
						<td style="width: 50px;text-align: center;" rowspan="2">Harga Jual</td>						
					    </tr>
					    <tr>';
                foreach($query->result() as $row)
                {
                    $head .= '<td style="width: 20px;text-align: center; font-size: 17px;">'.strtoupper($row->shop_initial).'</td>';
                }
                $head.='</tr>';
                    
				//retrieving data item untuk diprint
				$query = $this->item_mutasi->get_item_mutasi_by_code(array('kode_mutasi'=>$kode_mutasi));             
				$data='';
				if($query->num_rows() > 0)
				{
					foreach($query->result() as $row)
					{
						$data .='<tr>
								<td style="width: 60px;text-align: center;">'.$row->item_code.'</td>
								<td style="width: 100px;text-align: center;">'.$row->item_name.'</td>
								<td style="width: 25px;text-align: center">'.$row->item_qty_stock.'</td>';
						for($i=0;$i<$jumlah_toko;$i++)
						{
							$data .= '<td style="width: 20px;text-align: center; font-size: 17px;"></td>';
						}
						if(!$this->check_if_medan($this->input->post('sup_code')))
                        {
						    $hp = floor($row->item_hp + 0.15*$row->item_hp);
                        }
                        else
                        {
                            $hp = $row->item_hp;
                        }
						$data .= '	<td style="width: 50px;text-align: right;">'.number_format($hp,'0',',','.').',-</td>
								<td style="width: 50px;text-align: center;"></td>		
							    </tr>';
					}
                    $data .='</table>';
                    $footer = '<br /><br /><table style="text-align:center;">
                            <tr><td>(. . . . . . . . . . . . . .)</td><td>(. . . . . . . . . . . . . .)</td></tr>                            
                        </table>';                   
                    $this->cetak_mutasi_pdf($head, $data, $footer);
				}                
				//----------------------------------------------------
				
			}			
			$this->load->view(config_item('template').'gud_rekapmutasi',$this->data);
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
                        //sistem kode label yang ada : 8 digit, dengan ketentuan, 3 digit awal adalah kategori barang, dan sisanya no urut barang
                        //8 digit = xxx-xxxxx
                        //5 digit terakhir diotomasi sistem                    
                        
                        //check apakah barang dengan kategori tersebut sudah pernah ada, klo udh pernah ada trus ambil dan tinggal naikin no urut kode labelnya
                        $this->load->model('item');
                        $query = $this->item->get_item_by_cat($cat_code);
                        if($query->num_rows() > 0)
                        {
                            $data_item = $query->row();
                            $temp = ++$data_item->item_code;
                            if(strlen($temp) < 8)
                            {
                                $start = strlen($temp) - 5;
                                $item_next = substr($temp, $start, 5);
                                $data['item_code'] = $cat_code.$item_next;                                
                            }
                            else 
                            {
                                $data['item_code'] = $temp;
                            }
                        }            
                        else
                        {
                            $data['item_code'] = $cat_code.'00001';
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
        $query = $this->shop->get_shop();
        if($query->num_rows() > 0)
        {
            $shop = $query->result();
            $now = date('Y-m-d');
            $this->load->model('item_distribution');
            $this->load->model('item');
            $success = '';
            for($i=0;$i<count($item_code);$i++)//mulai dari baris ke satu sampai abis
            {
                $shop_initial='';
                for($j=0;$j<count($shop);$j++)//looping semua toko
                {
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
            if(!empty($success))
            {
                $success = 'Proses mutasi keluar untuk '.$success.' telah berhasil dilakukan';
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
        redirect('/gudang/mutasi/keluar','refresh');
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
                //ambil item punya supplier yang disubmit                
                $query = $this->item_distribution->get_item_for_printing($this->input->post('sup_code'));
                if($query->num_rows() > 0)
                {
                    $row_data = '';
                    $i = 0;
                    foreach($query->result() as $row)
                    {
                        $row_data .= '<tr>
                                        <td>'.++$i.'</td>
                                        <td>'.$row->item_code.'</td>
                                        <td>'.$row->item_name.'</td>
                                        <td>'.number_format($row->item_hj,0,',','.').',-</td>
                                        <td>'.$row->qty.' item</td>
                                        <td><span class="button"><input type="button" class="button" value = "Cetak" onclick="cetakLabel(\''.$row->item_code.'\')"/></span></td>
                                    </tr>';
                    }
                    $this->data['row_data'] = $row_data;
                }                    
		    }
            
		    $item_code = $this->uri->segment(4);
		    if(!empty($item_code))
		    {
                //ambil data barang yang akan dibuat label
                $this->load->model('item_distribution');
                $query = $this->item_distribution->get_item_for_exporting($item_code);   
                if($query->num_rows() > 0)
                {
                    $data_txt = 'Cabang:'.chr(9).'Nama Barang :'.chr(9).'Kode Brg/Barcode :'.chr(9).'Input Harga :'.chr(9).'Supplier :'.chr(9).'Tanggal :'.chr(9).'Kode Toko :'.chr(10);
                    foreach($query->result() as $row)
                    {
                        for($i=0;$i < $row->quantity; $i++)
                        {
                            
                            $data_txt .= strtoupper($row->shop_cat).chr(9).strtoupper($row->item_name).chr(9).$row->item_code.chr(9).number_format($row->item_hj,0,',','.').',-'.chr(9).
                                        strtoupper($row->sup_code).chr(9).date("dmy").chr(9).$row->shop_code .chr(10);
                                          
                        }                        
                        if( $row->quantity % 2 == 1)
                        {
                            $data_txt .= '====='.chr(9).'====='.chr(9).'====='.chr(9).'====='.chr(9).'====='.chr(9).'====='.chr(9).'====='.chr(10);                                
                        }
                        
                    }               
                }
                if($this->export_file_txt($data_txt))
                {
                    //update status di item_distribution jadi 1
                    $this->load->model('item_distribution');
                    $this->item_distribution->update_status(array('item_code'=>$item_code));
                    //
                    $this->session->set_userdata('link_download',base_url().'data/Mode_Fashion.doc');
                    $this->session->set_userdata('item_code',$item_code);
                    //simpan data 
                    redirect('gudang/cetak/label','refresh');
                }
                else
                {
                    $this->data['err_msg'] = '<span style="color:red">Gagal mencetak label, silahkan cek hak akses penulisan. Pastikan folder <b>sisgud/data/</b> bisa ditulis</span>';
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
                //buat bon untuk toko yang sedang ditampilkan
                $this->item_distribution->create_bon(array('shop_code'=>$this->input->post('shop_code'),'dist_code'=>$this->data['dist_code']));
			}			
			//cetak bon setelah preview
            if($this->input->post('submit_cetak_bon'))
			{
                //ambil barang untuk dicetak bonnya
                $kode_bon = $this->input->post('dist_code');
                $this->load->model('item_distribution');
                $this->load->model('shop');
                $this->load->model('item');
				$query = $this->item_distribution->get_item_for_pdf(array('dist_code'=>$kode_bon));
                //ambil mutasi
				$mutasi = $query->row();
				$temp = $this->shop->get_shop($mutasi->shop_code);
				$shop = $temp->row();
				$head = '<div style="margin-top: 5px;">
					    <h3 style="text-align: center;">BON MUTASI KELUAR GUDANG</h3>
					    <table style="width: 700px;">
						<tr><td style="width: 80px;">Kode Bon</td><td style="width:260px;">: '.$kode_bon.'</td>
						<td style="width: 70px;text-align:right;">Tanggal Mutasi</td><td style="width:100px;text-align:right">: '.date_to_string($mutasi->dist_out).'</td></tr>
						<tr><td style="width: 80px; ">Toko Tujuan</td><td>: '.strtoupper($shop->shop_name).'</td></tr>                                
					    </table>
					</div><br />';
					
				$head .= '<table style="width: 600px;" border="1" cellpadding="3">
					    <tr>
						<td style="width: 40px;text-align: center;">No Urut</td>
						<td style="width: 60px;text-align: center;">Kode Supplier</td>
						<td style="width: 70px;text-align: center">Kode Barang</td>
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
						<td style="width: 40px;height:;text-align: center;">'.++$i.'</td>
						<td style="width: 60px;text-align: center;">'.$item->sup_code.'</td>
						<td style="width: 70px">'.$item->item_code.'</td>
						<td style="width: 110px;">'.strtoupper($item->item_name).'</td>
                        <td style="width: 50px;text-align: center;">'.$row->item_disc.'</td>                        
						<td style="width: 75px;text-align: right;">'.number_format($item->item_hj,'0',',','.').',-</td>
                        <td style="width: 40px;text-align:right">'.$row->quantity.'</td>
						<td style="width: 75px;text-align: right;">'.number_format($jumlah,'0',',','.').',-</td>
					    </tr>';
					$j++;
					if($j==15)
					{
						$list_item[$index] .= '</table>';
						$j=0;$index++;
					}
                    
				}
				
				$list_item[$index] .= '<tr>						
                                            <td style="width: 405px;text-align:right" colspan="6"> T O T A L</td>
                                             <td style="width: 40px;text-align:right">'.$jumlah_item.'</td>
                                            <td style="width: 75px;text-align: right;">'.number_format($total,'0',',','.').',-</td>
                                            </tr>
                                    </table>';                 
				$footer = '<br /><table style="text-align:center;">
					                    <tr><td>Bagian Gudang</td><td>Bagian Transport</td><td>Bagian Toko</td><td>Bagian Komputer</td></tr>                            
					            </table>';				
				$this->cetak_pdf($head,$list_item,$footer);
			}
            //rekap untuk cetak bon
            if($this->uri->segment(4) == 'rekap')
            {   
                if($this->input->post('submit_rekap_bon'))
                {
                    $this->load->model('item_distribution');
                    $query = $this->item_distribution->get_bon_by_toko($this->input->post('shop_code'));
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
                                            '.form_open('gudang/cetak/bon').'
                                                <input type="hidden" name="dist_code" value="'.$row->dist_code.'"/>
                                                <span class="button"><input class="button" type="submit" name="submit_cetak_bon" value="Cetak"></span>
                                            '.form_close().'
                                            </td>
                                        </tr>';
                        }
                        $this->data['row_data'] = $row_data;
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
    *Cetak mutasi pdf
    */
	function cetak_mutasi_pdf($head, $data, $footer)
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

		//set margins (left,,right)
		$pdf->SetMargins(5, 20, 5);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setPageUnit('mm');
		$size = array(216,165);
		$pdf->setPageFormat($size,'P');
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
		
		//set some language-dependent strings
		$pdf->setLanguageArray($l); 

		// ----------------------------------------------------------------------------------		
		// set font
		$pdf->SetFont('dejavusans', '', 8);
		//print page
		$pdf->AddPage();
		$pdf->writeHTML($head.$data.$footer, true, 0, true, 0);
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
                $data = array(
                        'retur_code'=> time(),
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
        $pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
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
            //echo $html; 
            $pdf->writeHTML($head.$rows.$footer, true, 0, true, 0);
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
            $query = $this->db->query('select * from shop where shop_code in (select shop_code from item_distribution where dist_code = 0 group by shop_code)');
        }
        else if($option == 'pdf')
        {
            $query = $this->db->query('select * from shop where shop_code in (select shop_code from item_distribution where dist_code != 0 group by shop_code)');
        }
        else if($option == 'export')
        {
            $query = $this->db->query('select * from shop where shop_code in (select shop_code from item_distribution where dist_code != 0 and export=0 group by shop_code)');
        }
        //processing query result
            if($query->num_rows())
            {
                $list_toko ='<select name="shop_code">';
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
        $filename = "data/Mode_Fashion.doc";
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
            $keywords = $this->input->post('keywords');
        }
        else
        {
            $keywords = '';
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
                    $this->data['row_data'] .= '<tr>
                                                    <td>'.++$i.'</td>
                                                    <td>'.$row->item_code.'</td>
                                                    <td class="left">'.ucwords($row->item_name).'</td>                                                    
                                                    <td class="left">'.ucwords($row->cat_name).'</td>                                                    
                                                    <td class="left">'.ucwords($row->sup_name).'</td>                                                 
                                                    <td style="text-align:right"> '.$item_hj.'&nbsp;</td>
                                                    <td>'.$row->item_qty_stock.'</td>
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
    *ekspor data ke csv
    */
    function export()
    {
        //preview before export
        if($this->input->post('submit_preview_export'))
        {
            $this->load->model('item_distribution');        
            $this->load->model('shop');  
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
            $query = $this->item_distribution->get_item_export(array('export'=>$this->input->post('export')));
            if($query->num_rows() > 0)
            {                                  
                echo query_to_csv($query,TRUE,$shop->shop_initial.'.csv');                               
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
}
/* End of file gudang.php */
/* Location: ./system/application/controllers/gudang.php */
