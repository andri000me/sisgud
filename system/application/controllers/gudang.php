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
        $this->data['lib_js'] = '	<script src="'.base_url().'lib/jquery-1.4.2.min.js"></script>
                        <script src="'.base_url().'lib/jquery-ui-1.8.1.custom.min.js"></script>
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
			if(isset($_POST['submit_mutasi_masuk'])) 
			{
				//ambil data-data dari form
				$this->data['sup_code'] = $this->input->post('sup_code');
				$this->data['op_code'] = $this->session->userdata('p_id');           
				$this->data['cat_code'] = $this->input->post('cat_code');
				$this->data['item_name'] = $this->input->post('item_name');
				$this->data['item_hp'] = $this->input->post('item_hp');
				$this->data['item_qty'] = $this->input->post('item_qty');				
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
			$this->load->view('gud_mutasimasuk',$this->data);
		}
		else if($this->uri->segment(3)=='keluar')
		{
			if(isset($_POST['submit_mutasi_keluar']))
			{
				//retrieve data dari form dulu
				$this->data['item_code'] = $this->input->post('item_code');
				$this->data['item_qty_stock'] = $this->input->post('item_qty_stock');
				$this->data['item_hj'] = $this->input->post('item_hj');
				$query = $this->db->get('shop');
				if($query->num_rows())
				{
				    foreach($query->result() as $row)
				    {
					$this->data['qty_'.strtolower($row->shop_initial)] = $this->input->post('qty_'.strtolower($row->shop_initial));                        
				    }
				}
				if($this->insert_mutasi_keluar())
				{
				    $this->data['form_notify'] = 'Data mutasi keluar berhasil dimasukkan ke dalam database';
				}
				//exit;
			}
			//list toko
			$this->db->order_by('ordered_by','asc');
			$query = $this->db->get('shop');
			$this->data['jmlh_toko'] = $query->num_rows();
			$this->data['shop_initial'] = '<tr id="head">';
			$this->data['shop_row'] = '';
			$list_append = '';            
			$i = 0;
			foreach($query->result() as $row)
			{
				$this->data['shop_initial'] .= '<td style="text-align: left;">'.strtoupper($row->shop_initial).'</td>';
				$this->data['shop_row'] .= '<td><input type="text" id="'.strtolower($row->shop_initial).'_#" name="qty_'.strtolower($row->shop_initial).'[]" size="1" /></td>'; 
				if($i==0)
				{
				    $list_append .= "'".strtolower($row->shop_initial)."'";   
				}
				else 
				{
				    $list_append .= ",'".strtolower($row->shop_initial)."'";
				}
				$i++;  
			}
			$this->data['append'] = "countStock(new Array(".$list_append."),".$i.",#)";
			//buat form sebanyak 15 buah
			$this->data['tr'] = '';
			$dropdown = $this->list_item_dropdown();
			for($i=0;$i<15;$i++)
			{
				str_replace("#",$i,$dropdown);
				
				$this->data['tr'].= '<tr>
							<td><input type="hidden" name="item_num">'.str_replace("#",$i,$dropdown).' </td>
							<td><input type="text" id="item_name_'.$i.'" name="item_name" readonly="yes" size="18"/></td>
							<td><input type="text" id="item_qty_first_'.$i.'" name="item_qty_first" size="3" readonly="yes"/></td>
							'.str_replace("#",$i,$this->data['shop_row']).'
							<td><input type="text" id="item_qty_stock_'.$i.'" name="item_qty_stock[]" onfocus="'.str_replace("#",$i,$this->data['append']).'" size="3" readonly="yes"/></td>
							<!--<td style="text-align:right;width:150px;"><span id="item_hp_'.$i.'" ></span></td>-->                                        
							<td><input type="text" name="item_hj[]" size="10"/></td>                                        
						    </tr>';
				//echo $i;
			}
			//echo $dropdown; exit;
			$this->data['shop_initial'] .= '</tr>';
            $this->data['page_title'] .= ' :. Mutasi Keluar';
			
			$this->load->view('gud_mutasikeluar',$this->data);
		}
		//print mutasi
		else if($this->uri->segment(3)=='print')
		{
			//*print mutasi masuk dengan format
			/*| Kode Label | Nama | Qty Awal | initial toko | HM +15% | HJ
			*
			*
			*/
            $role = $this->session->userdata('p_role');
			if(isset($_POST['submit_print_mutasi']))
			{
				$query = $this->db->get_where('supplier',array('sup_code'=>$this->input->post('sup_code')));
				$data = $query->row();
				$head = '<div style="margin-top: 5px;">
						    <h3 style="text-align: center;">BON MUTASI MASUK</h3>
						    <table style="width: 700px;">
							<tr><td style="width: 50px;">SUPPLIER</td><td style="width:330px;">: '.strtoupper($data->sup_name).'</td>
							<td style="width: 130px;text-align:right;">Tanggal Mutasi</td><td style="width:60px;text-align:right">: '.date("d-m-Y").'</td></tr>							                              
						    </table>
						</div><br />';
				//retrieve data toko
				$query = $this->db->get('shop');
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
				$sup_code = $this->input->post('sup_code');
                $query = $this->db->query('select * from item_mutasi left join item on item_mutasi.item_code=item.item_code where item.sup_code = "'.$sup_code.'"'); 
               
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
						$hm = floor($row->item_hm + 0.15*$row->item_hm);
						$data .= '	<td style="width: 50px;text-align: center;">'.number_format($hm,'0',',','.').',-</td>
								<td style="width: 50px;text-align: center;"></td>		
							    </tr>';
					}
                    $data .='</table>';
                    $footer = '<br /><br /><table style="text-align:center;">
                            <tr><td>(. . . . . . . . . . . . . .)</td><td>(. . . . . . . . . . . . . .)</td></tr>                            
                        </table>';
                    //update status item yang udh pernah diprint mutasi jadi 1
                    $this->db->where(array('sup_code'=>$sup_code,'status_print_mutasi'=>0));
                    $this->db->update('item_mutasi',array('status_print_mutasi'=>1));
                    $this->cetak_mutasi_pdf($head, $data, $footer);
				}
                else
                {
                    $this->data['notifikasi'] = 'Tidak ada data mutasi barang masuk pada tanggal tersebut';                    
                }
				//----------------------------------------------------
				
			}
			//retrieve data supplier yang dientry pada hari ini dan akan diprint
			//and entry_date ="'.$date.'"			
			$query = $this->db->query('select * from supplier where supplier.sup_code in(select item.sup_code from item left join item_mutasi on item.item_code = item_mutasi.item_code group by item.sup_code)');
			if($query->num_rows() > 0)
			{
				$this->data['list_sup'] = '<select name="sup_code">';
				foreach($query->result() as $row)
				{
					$this->data['list_sup'] .= '<option value="'.$row->sup_code.'">'.ucwords($row->sup_name).'</option>';
				}
				
				$this->data['list_sup'] .='</select>';
			}
			$this->load->view('gud_printmutasi',$this->data);
		}
        else if($this->uri->segment(3)=='rekap')
		{
			//*print mutasi masuk dengan format
			/*| Kode Label | Nama | Qty Awal | initial toko | HM +15% | HJ
			*
			*
			*/
            if(isset($_POST['submit_rekap_mutasi']))
            {
                $tgl_mutasi = $this->input->post('Y').'-'.$this->input->post('m').'-'.$this->input->post('d');
                $query = $this->db->query('select item_mutasi.*, count(item_code) as jml_barang from item_mutasi where date_entry = "'.$tgl_mutasi.'" group by kode_mutasi');
                $table = '<h3 style="text-align:right;margin-right: 20px;">'.$tgl_mutasi.'</h3>
                            <table id="search" width = "95%" cellspacing = "7">
                            <tr id ="head">
                                <td width ="10%"> Kode Mutasi</td>
                                <td width ="20%"> Supplier </td>
                                <td width ="10%"> Jenis Barang </td>		
                                <td width ="5%"> Action </td>		
                            </tr>';
                if($query->num_rows() > 0)
                {
                    foreach($query->result() as $row)
                    {
                        $sup = $this->db->get_where('supplier',array('sup_code'=>$row->sup_code));
                        $supplier = $sup->row();
                        $table .='<tr>
                                    <td>'.$row->kode_mutasi.'</td>
                                    <td>'.ucwords($supplier->sup_name).'</td>
                                    <td>'.$row->jml_barang.' macam</td>
                                    <td><a href="'.base_url().'index.php/gudang/mutasi/rekap/'.$row->kode_mutasi.'"><button>Cetak Mutasi</button></a></td>
                                </tr>';
                    }
                    $table.='</table>';
                    $this->data['list_mutasi'] = $table;
                }
                else
                {
                    $this->data['list_mutasi'] = 'Tidak ada data mutasi pada tanggal tersebut';
                }
                
            }
            $kode_mutasi = $this->uri->segment(4);
			if(!empty($kode_mutasi))
			{
                $query = $this->db->get_where('item_mutasi',array('kode_mutasi'=>$kode_mutasi));
                $temp = $query->row();
				$supplier = $this->db->get_where('supplier',array('sup_code'=>$temp->sup_code));
				$data = $supplier->row();
				$head = '<div style="margin-top: 5px;">
						    <h3 style="text-align: center;">BON MUTASI MASUK</h3>
						    <table style="width: 700px;">
							<tr><td style="width: 50px;">SUPPLIER</td><td style="width:330px;">: '.strtoupper($data->sup_name).'</td>
							<td style="width: 130px;text-align:right;">Tanggal Mutasi</td><td style="width:60px;text-align:right">: '.$temp->date_entry.'</td></tr>							                              
						    </table>
						</div><br />';
				//retrieve data toko
				$query = $this->db->get('shop');
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
				$query = $this->db->query('select * from item where item_code in (select item_code from item_mutasi where kode_mutasi="'.$kode_mutasi.'")');             
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
						$hm = floor($row->item_hp + 0.15*$row->item_hp);
						$data .= '	<td style="width: 50px;text-align: center;">'.number_format($hm,'0',',','.').',-</td>
								<td style="width: 50px;text-align: center;"></td>		
							    </tr>';
					}
                    $data .='</table>';
                    $footer = '<br /><br /><table style="text-align:center;">
                            <tr><td>(. . . . . . . . . . . . . .)</td><td>(. . . . . . . . . . . . . .)</td></tr>                            
                        </table>';                   
                    $this->cetak_mutasi_pdf($head, $data, $footer);
				}
                else
                {
                    $this->data['notifikasi'] = 'Tidak ada data mutasi barang masuk pada tanggal tersebut';                    
                }
				//----------------------------------------------------
				
			}			
			$this->load->view('gud_rekapmutasi',$this->data);
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
        if(!empty($faile))
        {
            $form_notify .= '<span style="color:green">Kategori Barang: <b>'.$failed.'</b>, tidak valid</span>';
        }
        $this->session->set_userdata('form_notify',$form_notify);
        redirect('/gudang/mutasi/masuk','refresh');
    }
    
	/**
    *Menyimpan data mutasi keluar ke dalam database
    **/
    function insert_mutasi_keluar()
    {
        //mulai transaksi database
        $dist_code = '0';
        $dist_out = date("Y-m-d");
        $query = $this->db->get('shop');
        $this->db->trans_begin();
        for($i=0; $i<count($this->data['item_code']); $i++)
        {
             
		//echo 'hj : '. $this->data['item_hj'][$i];
		//echo 'stock'. $this->data['item_qty_stock'][$i];//exit;
		if(!empty($this->data['item_code'][$i]) && !empty($this->data['item_hj'][$i]) && ($this->data['item_qty_stock'][$i] >= 0))
		{
			//echo 'tessss';exit;
			//update table item
			$this->db->where('item_code',$this->data['item_code'][$i]);
			$data = array (
				    'item_hj'=>$this->data['item_hj'][$i],
				    'item_qty_stock'=>$this->data['item_qty_stock'][$i]
				);
			$this->db->update('item',$data);//echo $this->db->last_query();exit;
			$temp = $this->db->get_where('item',array('item_code'=>$this->data['item_code'][$i]));
			$sup_code = $temp->row()->sup_code;//echo $temp->row()->sup_code;exit;
			//insert data ke table item_distribution
			$data = array (                            
				    'dist_code'=>$dist_code,
				    'item_code'=>$this->data['item_code'][$i],
				    'dist_out'=>$dist_out,
				    'sup_code'=>$sup_code
				); 
			//echo $data['sup_code'];exit;                        
			if($query->num_rows())
			{
				//echo 'tessss';exit;
				foreach($query->result() as $row)
				{
					$data['shop_code'] = $row->shop_code;
					$data['quantity']= $this->data['qty_'.strtolower($row->shop_initial)][$i];
					if($data['quantity'] > 0)
					{
					    if($this->db->insert('item_distribution',$data))
					    {
						//echo $this->db->last_query();exit;
						//mencatat log transaksi ke dalam database
						//make note for every action done by operator, writing it to database
						$data1 = array(
							'trans_name'=>'Memasukkan data mutasi keluar untuk kode toko : '.$data['shop_code'].' kode item: '.$this->data['item_code'][$i],
							'log_time'=> time(),
							'p_id'=>$this->session->userdata('p_id')
						    );                                    
						$this->db->insert('log_transaksi',$data1); 
						//$data =                                 
					    } 
					    //echo $this->db->last_query();exit;                            
					}
				}                    
			}
		}
        }
        //cek apakah transaksi database sukses
        if($this->db->trans_status()== FALSE)
        {
            $this->db->trans_rollback();
            $this->data['form_notify'] = 'Data mutasi keluar tidak berhasil dimasukkan ke dalam database. Silahkan ulang kembali.';
            return FALSE;
        }
        else
        {
            $this->db->trans_commit();
            //echo 'TRUE';
            return TRUE;
        }
        //exit;
    }
    /**
	*Method for printing label an bon
	*/
	function cetak()
	{
		if($this->uri->segment(3)=='label')
		{
			/*//bikin bon berdasarkan toko
			if(isset($_POST['submit_bon_toko']))
			{
				$query = $this->db->get_where('shop',array('shop_code'=>$this->input->post('shop_code')));
				$shop = $query->row();//echo $shop->shop_name;exit;
				$this->data['shop_name'] = $shop->shop_name .'<input type="hidden" name="bon_shop_code" value="'.$this->input->post('shop_code').'" /><input type="hidden" name="shop_name" value="'.$shop->shop_name.'" />';
				$query = $this->db->get_where('item_distribution',array('shop_code'=>$this->input->post('shop_code'),'status'=>0));//echo $this->db->last_query();exit;
				if($query->num_rows() > 0)
				{
					$this->data['print_button'] = '<input type = "submit" name="submit_export_file" value = "Print ke Notepad" />';
					$this->data['tr']= '';
					foreach($query->result() as $row)
					{
						$temp = $this->db->get_where('item',array('item_code'=>$row->item_code));
						$item = $temp->row();
						$this->data['tr'] .= '<tr>
										<td>'.$row->item_code.'</td>
										<td>'.ucwords($item->item_name).'</td>
										<td>'.strtoupper($item->sup_code).'</td>
										<td style="text-align: right">'.number_format($item->item_hj,2,',','.').'</td>
										<td>'.$row->quantity.'</td>                         
										</tr>';
			    }
			}
			//echo $this->db->last_query();exit;                
		    }*/
		    //membuat print label per supplier
		    if(isset($_POST['submit_label_supplier']))
		    {
			//ambil nama supplier buat ditampilin
			$query = $this->db->get_where('supplier',array('sup_code'=>$this->input->post('sup_code')));
			$this->data['sup_name'] = $query->row()->sup_name;
			//ambil item punya supplier yang disubmit
			$sql = 'select item_code, sum(quantity) as qty from item_distribution where sup_code = "'.$this->input->post('sup_code').'" and status=0 group by item_code';
			$query = $this->db->query($sql);//echo $this->db->last_query();exit;
			if($query->num_rows() > 0)
			{
			    $this->data['tr']= '';
			    foreach($query->result() as $row)
			    {
				$temp = $this->db->get_where('item',array('item_code'=>$row->item_code));
				$item = $temp->row();
				$this->data['tr'] .= '<tr>
					<td>'.$row->item_code.'</td>
					<td>'.ucwords($item->item_name).'</td>
					<td>'.strtoupper($item->sup_code).'</td>
					<td style="text-align: right">'.number_format($item->item_hj,2,',','.').'</td>
					<td>'.$row->qty.'</td>  
					<td><a href="'.base_url().'index.php/gudang/cetak/label/'.$row->item_code.'"><input type="button"" value="Print Notepad"/></a></td>                                
				    </tr>';
			    } 
			}                    
		    }
		    $item_code = $this->uri->segment(4);
		    if(!empty($item_code))
		    {
			$data_txt = 'Nama Barang :'.chr(9).'Kode Brg/Barcode :'.chr(9).'Input Harga :'.chr(9).'Supplier :'.chr(9).'Tanggal :'.chr(9).'Kode Toko :'.chr(10);
			$query = $this->db->get_where('item_distribution',array('item_code'=>$item_code,'status'=>'0'));
			 if($query->num_rows() > 0)
			    {
				$temp = $this->db->get_where('item',array('item_code'=>$item_code));
				$item = $temp->row();
				foreach($query->result() as $row)
				{
					for($i=0;$i < $row->quantity; $i++)
					{
						if($row->shop_code == "00" || $row->shop_code == "27" || $row->shop_code == "18" )
						{
							;
						}
						else 
						{
							$data_txt .= strtoupper($item->item_name).chr(9).$item->item_code.chr(9).number_format($item->item_hj,0,',','.').',-'.chr(9).
							strtoupper($item->sup_code).chr(9).date("dmy").chr(9).$row->shop_code .chr(10);
						}              
					}
					if($row->shop_code != "00" && $row->shop_code != "27" &&  $row->shop_code != "18" )
					{
						if( $row->quantity % 2 == 1)
						{
							$data_txt .= '====='.chr(9).'====='.chr(9).'====='.chr(9).'====='.chr(9).'====='.chr(9).'====='.chr(10);
							//echo 'cek '.$row->shop_code.'<br />';
						}
					}
				}               
			    }
			
						
			$this->export_file_txt($data_txt);	
			
			
				//membuat log transaksi yang terjadi ke dalam database
				$data = array(
					    'trans_name'=>'Menandai table item_distribution untuk item yang udah diprint labelnya. Kode Item : '.$item_code,
					    'log_time'=> time(),
					    'p_id'=>$this->session->userdata('p_id')
					);                                    
			       $this->db->insert('log_transaksi',$data);            
			       redirect('/gudang/cetak/simpan/'.$item_code);
		    }
		     
			$this->list_supplier();
			$this->data['page_title'] .= ' :. Mencetak Label';
			$this->load->view('gud_cetaklabel',$this->data);
		}
		else if( $this->uri->segment(3)=='simpan')
		{
			//update status to 1, have been printed
			$item_code = $this->uri->segment(4);
			$this->db->where(array('item_code'=>$item_code, 'status'=>0));
			$this->db->update('item_distribution',array('status'=>1));
			$this->data['file_name'] = 'data/Mode_Fashion.txt';
			$this->list_supplier();
			$this->data['page_title'] .= ' :. Mencetak Label';
			$this->load->view('gud_cetaklabel',$this->data);
		}
		else if($this->uri->segment(3)=='bon')
		{
			$shop_code = $this->uri->segment(5);
			if(isset($_POST['submit_toko']) || ($this->uri->segment(4)== 'toko' && !empty($shop_code)))
			{
				if(empty($shop_code)) 
				{
					$shop_code = $this->input->post('shop_code');
				}
				$query = $this->db->get_where('shop',array('shop_code'=>$shop_code));
				$shop = $query->row();
				$this->data['shop_name'] = $shop->shop_name .'<input type="hidden" name="bon_shop_code" value="'.$this->input->post('shop_code').'" /><input type="hidden" name="shop_name" value="'.$shop->shop_name.'" />';
				//ambil bon yang ada di toko
				$sql = 'select dist_code, count(item_code) as count_item, dist_out from item_distribution where dist_code != 0 and shop_code = "'.$shop->shop_code.'" group by dist_code  order by dist_code desc';
				$query = $this->db->query($sql);
				//make pagination for printing pdf
				$this->load->library('pagination');
				$config['base_url'] = base_url().'index.php/gudang/cetak/bon/toko/'.$shop_code;
				$config['total_rows'] = $query->num_rows();
				$config['per_page'] = '20';
				$this->pagination->initialize($config);
				$this->data['pagination'] = $this->pagination->create_links();
				//echo $query->num_rows();
				$page = $this->uri->segment(6);
				if(!empty($page))
				{
					$lower = $page + 1;
					$upper = $page + $config['per_page'];
				}
				else
				{
					$upper = $page + $config['per_page'];
					$lower = 1;
				}
				//end of pagination
				if($query->num_rows())
				{
				    $tr = '';
				    $i=1;
				    foreach($query->result() as $row)
				    {
					if($i >= $lower && $i <= $upper)
					{
						$tr .= '<tr>
							    <td>'.$row->dist_code.'</td>
							    <td>'.$row->count_item.' macam barang</td>
							    <td>'.$row->dist_out.'</td>
							    <td><a href="'.base_url().'index.php/gudang/cetak/bon/kode/'.$row->dist_code.'"><input type="button" value="Cetak Bon" /></a></td>
							</tr>';
						
					}
					$i++;
				    }
				    $this->data['tr'] = $tr;
				}                
			}
			//membuat bon per toko
			if(isset($_POST['submit_print_bon']))
			{
				//update table item_distribution, masukkin kode bonnya, dist_code = kode bon
				$this->data['shop_code'] = $this->input->post('shop_code');
				$this->db->where(array('shop_code'=>$this->data['shop_code'], 'dist_code'=>0));
				$this->db->update('item_distribution', array('dist_code'=>time()));
				$query = $this->db->get_where('shop',array('shop_code'=>$this->data['shop_code']));
				$data = $query->row();
				$this->data['form_notify_bon'] = '<b>Bon untuk toko '.ucwords($data->shop_name).' telah dicetak.</b>';
			}
			$kode_bon = $this->uri->segment(5);
			if($this->uri->segment(4) == 'kode' && !empty($kode_bon))
			{
                
				$query = $this->db->get_where('item_distribution', array('dist_code'=>$kode_bon));
				$mutasi = $query->row();
				$temp = $this->db->get_where('shop',array('shop_code'=>$mutasi->shop_code));
				$shop = $temp->row();$this->data['shop'] = $shop;
				$head = '<div style="margin-top: 5px;">
					    <h3 style="text-align: center;">BON MUTASI KELUAR GUDANG</h3>
					    <table style="width: 700px;">
						<tr><td style="width: 80px;">Kode Bon</td><td style="width:300px;">: '.$kode_bon.'</td>
						<td style="width: 70px;text-align:right;">Tanggal Mutasi</td><td style="width:60px;text-align:right">: '.date("d-m-Y").'</td></tr>
						<tr><td style="width: 80px; ">Toko Tujuan</td><td>: '.strtoupper($shop->shop_name).'</td></tr>                                
					    </table>
					</div><br />';
					
				$head .= '<table style="width: 600px;" border="1" cellpadding="3">
					    <tr>
						<td style="width: 40px;text-align: center;">No Urut</td>
						<td style="width: 60px;text-align: center;">Kode Supplier</td>
						<td style="width: 70px;text-align: center">Kode Barang</td>
						<td style="width: 110px;text-align: center;">Nama Barang</td>
						<td style="width: 40px;text-align: center;">Qty Brg</td>
						<td style="width: 75px;text-align: center;">Harga Jual (Rp.)</td>
						<td style="width: 50px;text-align: center;">Disc %</td>
						<td style="width: 75px;text-align: center;">Jumlah (Rp.) </td>
					    </tr>';
				$i = 0;
				$jumlah_item = 0;
				$total = 0;
				$j=0;$index=0;//$list_item = array (5);
				foreach($query->result() as $row)
				{
					$temp = $this->db->get_where('item',array('item_code'=>$row->item_code));
					$item = $temp->row();
					$jumlah = $row->quantity * $item->item_hj;
					$jumlah_item += $row->quantity;
					$total += $jumlah;
					if(!isset($list_item[$index]))
					{
						$list_item[$index] = '';
					}
					$list_item[$index].= '<tr>
						<td style="width: 40px;height:;text-align: center;">'.++$i.'</td>
						<td style="width: 60px;">'.$item->sup_code.'</td>
						<td style="width: 70px">'.$item->item_code.'</td>
						<td style="width: 110px;">'.strtoupper($item->item_name).'</td>                                
						<td style="width: 40px;text-align:right">'.$row->quantity.'</td>
						<td style="width: 75px;text-align: right;">'.number_format($item->item_hj,'0',',','.').',-</td>
						<td style="width: 50px;text-align: center;"> - </td>
						<td style="width: 75px;text-align: right;">'.number_format($jumlah,'0',',','.').',-</td>
					    </tr>';
					$j++;
					if($j==15)
					{
						$list_item[$index] .= '</table>';
						$j=0;$index++;
					}
                    
				}
				//echo $list_item[1];
				//exit;
				$list_item[$index] .= '<tr>
						<td style="width: 40px;text-align: center;"></td>                                
						<td style="width: 60px;"></td>
						<td style="width: 70px;"></td>
						<td style="width: 110px;"></td>                                
						<td style="width: 40px;text-align:right">'.$jumlah_item.'</td>
						<td style="width: 75px;text-align: right;"></td>
						<td style="width: 50px;text-align: center;"></td>
						<td style="width: 75px;text-align: right;">'.number_format($total,'0',',','.').',-</td>
					    </tr></table>';                 
				$footer = '<br /><table style="text-align:center;">
					    <tr><td>Bagian Gudang</td><td>Bagian Transport</td><td>Bagian Toko</td><td>Bagian Komputer</td></tr>                            
					</table>';
				//echo htmlentities($bon);exit;
				//echo $bon;exit;
				$this->cetak_pdf($head,$list_item,$footer);
			}
			$this->data['list_toko_bon'] = $this->list_toko('bon');
			$this->data['list_toko_pdf'] = $this->list_toko('pdf');
			$this->data['page_title'] .= ' :. Mencetak Label';
			$this->load->view('gud_cetakbon',$this->data);
		}
	}
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
	function retur()
	{
		if(isset($_POST['submit_search_toko']))
		{
			//retrieve data form
			$shop_code = $this->input->post('shop_code');
			$keywords = $this->input->post('keywords');
			$key = $this->input->post('key');
			if(!empty($keywords))
			{
				$sql = 'select toko.*,toko.stok_toko,(toko.stok_toko-retur.qty_retur) as sisa 
					from (select item.item_code, item.item_name, item.item_hm,item.item_hj, item.item_qty_stock as stok_gudang, sum(item_distribution.quantity) as stok_toko 
					from item, item_distribution where item.item_code = item_distribution.item_code and item_distribution.shop_code ="'.$shop_code.'" group by item.item_code)
					as toko left join (select item_retur.item_code, sum(item_retur.quantity) as qty_retur from item_retur group by item_retur.item_code) as retur 
					on toko.item_code = retur.item_code where toko.item_code like "%'.$keywords.'%" or toko.item_name like "%'.$keywords.'%"';
			}
			else
			{
				$sql = 'select toko.*,toko.stok_toko,(toko.stok_toko-retur.qty_retur) as sisa
					from (select item.item_code, item.item_name, item.item_hm,item.item_hj, item.item_qty_stock as stok_gudang, sum(item_distribution.quantity) as stok_toko
					from item, item_distribution where item.item_code = item_distribution.item_code and item_distribution.shop_code ="'.$shop_code.'" group by item.item_code) 
					as toko left join (select item_retur.item_code, sum(item_retur.quantity) as qty_retur from item_retur group by item_retur.item_code) as retur on toko.item_code = retur.item_code';
			}
			//echo $sql;exit;
			$query = $this->db->query($sql);
			$this->data['shop'] = '<input type="hidden" value="'.$shop_code.'" name="shop_code" />';
			$tr='';
			if($query->num_rows())
			{
				foreach($query->result() as $row)
				{
					if(!empty($row->sisa))
					{
						$row->stok_toko = $row->sisa;
					}
					$tr .= '<tr>
						<td>'.$row->item_code.' <input type="hidden" value="'.$row->item_code.'" name="item_code[]" /></td>
						<td>'.$row->item_name.'</td>
						<td>'.$row->stok_gudang.'</td>
						<td>'.$row->stok_toko.'<input type="hidden" value="'.$row->stok_toko.'" name="stok_toko[]" /></td>						
						<td style="text-align:right">'.number_format($row->item_hm,0,',','.').',-</td>
						<td style="text-align:right">'.number_format($row->item_hj,0,',','.').',-</td>
						<td style="text-align:center"><input type="text" name="qty_retur[]" size="5" /></td>
					</tr>';
				}
				$this->data['tr'] = $tr;
			}
		}
		if(isset($_POST['submit_simpan_retur']))
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
			$stok_toko = $this->input->post('stok_toko');
			$this->data['notify']='';
			for($i=0; $i < count($qty_retur);$i++)
			{
				$data['item_code'] = $item_code[$i];
				$data['quantity'] = $qty_retur[$i];
				if(!empty($data['quantity']))
				{
					if($data['quantity'] < $stok_toko[$i])
					{
						//simpan data retur
						if($this->db->insert('item_retur',$data))
						{
							//update stok gudang
							$this->db->query('update item set item_qty_stock = item_qty_stock + '.$data['quantity'].' where item_code = "'.$data['item_code'].'"');
							$this->data['notify'] ='Item code : <b>'.$data['item_code'].', </b> telah diretur dan telah ditambahkan ke stok gudang';
						}
					}
					else 
					{
						$this->data['notify'].='Item code : <b>'.$data['item_code'].', </b> quantity retur tidak valid';
					}
				}				
			}			
		}
		$this->data['list_toko'] = $this->list_toko();
		$this->load->view('gud_retur',$this->data);
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
        $sql = 'select * from item_distribution where status = 0 group by sup_code';
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {            
            $this->data['list_supp'] ='<select name="sup_code">';
            foreach($query->result() as $row)
            {
                $temp = $this->db->get_where('supplier',array('sup_code'=>$row->sup_code));//echo $this->db->last_query();echo $temp->num_rows();exit;
                if($temp->num_rows() > 0)
                {
                    $supplier = $temp->row();
                    $this->data['list_supp'] .= '<option value="'.$supplier->sup_code.'">'.ucwords($supplier->sup_name).'</option>';
                }
            }
            $this->data['list_supp'] .= '</select>';//echo $this->data['list_sup'];exit;
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
	$file = fopen("data/Mode_Fashion.txt","w");  
	$cek = fwrite($file,$data_txt);
	fclose($file);
	//exit;
    }
	/**
	*Fungsi untuk melihat stok gudang
	*/
	function stok()
	{
		if(isset($_POST['submit_stock_search']))
        {
            $keywords = $this->input->post('keywords');
            $key = $this->input->post('key');
            if(!empty($keywords))
            {
                switch($key)
                {
                    case 'item_code': $this->db->like('item_code',$keywords);break;
                    case 'sup_code': $this->db->like('sup_code',$keywords);break;
                    case 'item_name': $this->db->like('item_name',$keywords);break;
                }                
            }
        }
        $query = $this->db->get('item');
        $this->data['list_item'] = '';
        if($query->num_rows > 0)
        {
            foreach($query->result() as $row)
            {
                $temp = $this->db->get_where('operator',array('op_id'=>$row->op_code));
                $operator = $temp->row();
                $this->data['list_item'] .= '<tr>
                                                <td>'.$row->item_code.'</td>
                                                <td>'.ucwords($row->item_name).'</td>
                                                <td>'.$row->item_qty_total.'</td>
                                                <td>'.$row->item_qty_stock.'</td>
                                                <td style="text-align:right"> '.number_format($row->item_hm,0,',','.').',- &nbsp;</td>
                                                <td>'.$row->cat_code.'</td>                                                                                                                             
                                                <td>'.$row->sup_code.'</td>                                                                                                                             
                                                <td>'.$operator->op_name.'</td>                                                                                                                             
                                            </tr>';
            }
        }
        $this->data['page_title'] .= ' :. Stok Gudang';
		$this->load->view('gud_stok',$this->data);
	}
    /*Fungsi untuk membuat json_reader json_encode menggunakan extjs*/
    function js_list_item()
    {
        $query = $this->db->get('item');
        foreach($query->result() as $row)
        {
            $sup = array(
                    'sup_code'=>$row->sup_code,
                    'sup_name'=>$row->sup_name
                );
            $sup_list[] = $sup;
        }
        $rows = $query->num_rows();
        $data = json_encode($sup_list);
        echo '({"total":"' . $rows . '","results":' . $data . '})'; 
    }
    //fungsi untuk mengambil item trus disusun jadi dropdown...
    function list_item_dropdown()
    {
        $this->db->where(array('item_qty_stock >'=>0));
        $query = $this->db->get('item');
        $select = '<select name="item_code[]" id="item_#" ><option onclick="clearField(#)"></option>';  
        $hidden ='';        
        foreach($query->result() as $row)
        {
            $select .= '<option value="'.$row->item_code.'" onclick = updateValue(#,"'.$row->item_code.'",'.$row->item_qty_stock.',"'.number_format($row->item_hp,0,',','.').'")>'.$row->item_code.'</option>';
            $hidden .='<input type="hidden" id="'.$row->item_code.'" value="'.$row->item_name.'" />';                       
            
        }
        return $select.'</select>'.$hidden;
    }
    
}
/* End of file gudang.php */
/* Location: ./system/application/controllers/gudang.php */
