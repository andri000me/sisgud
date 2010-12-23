<?php
/**
*Name : Toko
*Author : PuRwa
*version : 1.0 beta
*Description: This class is used to handle management of Store/Toko
*/
class Toko extends Controller {
	var $data;
	/**
	*Class constructor
	*/
	function Toko()
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
		$this->data['pages']='toko';
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
	*Method untuk menambahkan toko baru
	*/
	function tambah()
	{        
		if($this->input->post('submit_tambah_toko'))
        {
            if($this->validate_form_tambah_toko())
            {
                $this->data['shop_code'] = $this->input->post('shop_code');
                $this->data['shop_name'] = $this->input->post('shop_name');
                $this->data['shop_initial'] = $this->input->post('shop_initial');
                $this->data['shop_address'] = $this->input->post('shop_address');
                $this->data['shop_phone'] = $this->input->post('shop_phone');
                $this->data['shop_supervisor'] = $this->input->post('shop_supervisor');
                //insert data ke database
                if($this->insert_data())
                {
                    //upload shop img
                    $config['upload_path'] = 'css/images/toko/';
                    $config['allowed_types'] = 'jpg';
                    $config['overwrite'] = TRUE;
                    $config['file_name'] = strtolower($this->data['shop_code']); 
                    $this->load->library('upload', $config);
                    //do upload
                    $this->upload->do_upload('shop_img');
                    //pesan sukses
                    $this->data['err_msg']='<span style="color:green">Data toko telah disimpan</span>';
                }
                else
                {
                    $this->data['err_msg']='<span style="color:red">Gagal disimpan. Kode toko sudah pernah digunakan.</span>';
                }
            }
        }
        $this->data['page_title'] .= ':. Menambah Toko';
		$this->load->view(config_item('template').'tok_tambah',$this->data);
	}
    /**
    *Method untuk insert data
    **/
    function insert_data()
    {
        $data = array(
                    'shop_code'=>$this->data['shop_code'],
                    'shop_name'=>$this->data['shop_name'],
                    'shop_address'=>$this->data['shop_address'],
                    'shop_initial'=>$this->data['shop_initial'],
                    'shop_phone'=>$this->data['shop_phone'],
                    'shop_supervisor'=>$this->data['shop_supervisor']
                );
        //check dulu
        $this->load->model('shop');
        $check = $this->shop->get_shop($data['shop_code']);
        if($check->num_rows() == 0)
        {
            if($this->shop->insert($data))
            {
                return TRUE;
            }
        }
        else
        {
            return FALSE;
        }
    }
    /**
    *Method untuk validasi form tambah toko
    **/
    function validate_form_tambah_toko()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('shop_code','kode toko','required');
        $this->form_validation->set_rules('shop_name','nama toko','required');
        $this->form_validation->set_rules('shop_initial','inisial toko','required');
        $this->form_validation->set_rules('shop_address','alamat toko','required');
        $this->form_validation->set_rules('shop_phone','nomor telepon','required');
        $this->form_validation->set_rules('shop_supervisor','supervisor','required');
        if($this->form_validation->run()==FALSE)
        {
            $this->data['err_msg'] = '<span style="color:red">Terjadi kesalahan input data. Pastikan bahwa informasi yang diminta telah dimasukkan dengan benar</span>';
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
	/**
	*Method untuk melihat stok toko
	*/
	function stok($param='')
	{
        //setting parameter for searching
        if($this->input->post('submit_cari_stok'))
        {
            $shop_code = $this->input->post('shop_code');
            $keywords = $this->input->post('keywords');            
           
            $this->session->set_userdata('shop_code',$shop_code);
            $this->session->set_userdata('keywords',$keywords);
            
        }
        else 
        {
            //klo udh pernah searching ambil dari session
            if($this->session->userdata('shop_code'))
            {
                $shop_code = $this->session->userdata('shop_code');
                $keywords = $this->session->userdata('keywords');
            }
        }
        //klo belum pernah searching ambil langsung dari database
        if(isset($keywords) && isset($shop_code))
        {
            $this->load->model('shop');
            $query = $this->shop->search_stok($keywords, $shop_code);
            //proses untuk ditampilkan
            if(isset($query))
            {
                if($query->num_rows() > 0)
                {
                    $this->data['total_item'] = $query->num_rows();
                    //setting up pagination
                    $this->load->library('pagination');
                    $config['base_url'] = base_url().'toko/stok/';
                    $config['total_rows'] = $this->data['total_item'];
                    $config['per_page'] = 10;
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
                    $this->data['total_jumlah'] = 0;
                    $this->data['total_retur'] = 0;
                    foreach($query->result() as $row)
                    {
                        if($i>=$page_min && $i<$page_max)
                        {
                            if(empty($row->stok))
                            {
                                $row->stok = 0;
                            }
                            if(empty($row->retur))
                            {
                                $row->retur = 0;
                            }
                            $this->data['row_data'] .= '<tr>
                                                            <td>'.++$i.'</td>
                                                            <td>'.$row->item_code.'</td>
                                                            <td>'.$row->item_name.'</td>
                                                            <td>'.$row->sup_code.'</td>
                                                            <td class="right">'.number_format($row->item_hj,0,',','.').',-</td>
                                                            <td>'.$row->stok.'</td>
                                                            <td>'.$row->retur.'</td>
                                                        </tr>';                            
                        }
                        else
                        {
                            $i++;
                        }
                        $this->data['total_jumlah'] += $row->stok;
                        $this->data['total_retur'] += $row->retur;
                    }
                }
                else
                {
                    $this->data['err_msg'] = '<span style="color:red">Data tidak ditemukan, silahkan pilih toko yang lain</span>';
                }
            }            
            //ambil data toko
            $query = $this->shop->get_shop($shop_code);
            if($query->num_rows() > 0)
            {
                $this->data['shop'] = $query->row();
            }
        }
		$this->list_toko();
		$this->data['page_title'] .= ':. Melihat Stok Toko';
		$this->load->view(config_item('template').'tok_stok',$this->data);
	}
    /**
    *Membuat list toko
    **/
    function list_toko()
    {
        $this->load->model('shop');
        $query = $this->shop->get_shop();            
        if($query->num_rows())
        {
            $this->data['list_toko'] ='<select name="shop_code">';
            foreach($query->result() as $row)
            {
                $this->data['list_toko'] .= '<option value="'.$row->shop_code.'">'.ucwords($row->shop_name).'</option>';
            }
            $this->data['list_toko'] .= '</select>';
            
        }
    }
    /**
    *Lihat detail toko
    */
    function detail()
    {
        if($this->input->post('submit_detail_toko'))
		{
            $this->load->model('shop');
			$query = $this->shop->detail($this->input->post('shop_code'));
            if($query->num_rows > 0)
            {
			    $this->data['toko'] = $query->row();
                $file_name = 'css/images/toko/'.strtolower($this->data['toko']->shop_code).'.jpg';
                if(file_exists($file_name))
                {
                    $this->data['shop_pict'] = base_url().$file_name;                    
                }
                else
                {
                    $this->data['shop_pict'] = base_url().'css/images/toko/default.png';
                }
                if(empty($this->data['toko']->total))
                {
                    $this->data['toko']->total = 0;
                }
            }
		}
        $this->data['page_title'] .= ':. Melihat Detail Toko';		
		$this->load->view(config_item('template').'tok_detail',$this->data);
    }
	/**
	*Method untuk cari toko
	*/
	function cari($param='')
	{
        $this->load->model('shop');		
        
        //ambil dta toko
        if($this->input->post('submit_cari_toko'))
        {
            $keywords = $this->input->post('keywords');
        }
        else
        {
            $keywords='';
        }
        $query = $this->shop->cari($keywords);
        if($query->num_rows() > 0)
        {            
            $this->data['total_item'] = $query->num_rows();
            //setting up pagination
            $this->load->library('pagination');
            $config['base_url'] = base_url().'toko/cari/';
            $config['total_rows'] = $this->data['total_item'];
            $config['per_page'] = 10;
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
                if($i>=$page_min && $i<$page_max)
                {
                    //ambil data retur
                    $temp = $this->shop->cari_retur($row->shop_code);
                    if($temp->num_rows() > 0)
                    {
                        $tmp = $temp->row();
                        $retur = $tmp->retur;
                    }
                    else
                    {
                        $retur = 0;
                    }
                    //stok = total - retur
                    if(empty($row->total))
                    {
                        $row->total = 0;
                    }
                    $stok = $row->total - $retur;
                    $this->data['row_data'] .= '<tr>
                                                    <td>'.++$i.'</td>
                                                    <td>'.$row->shop_code.'</td>
                                                    <td>'.$row->shop_name.'</td>
                                                    <td>'.$row->total.'</td>
                                                    <td>'.$retur.'</td>
                                                    <td>'.$stok.'</td>
                                                    <td>
                                                    '.form_open('toko/detail').'
                                                        <input type="hidden" name="shop_code" value="'.$row->shop_code.'" />
                                                        <span class="button"><input type="submit" name="submit_detail_toko" class="button" value="Lihat"/></span>
                                                    '.form_close().'
                                                    '.form_open('toko/ubah').'
                                                        <input type="hidden" name="shop_code" value="'.$row->shop_code.'" />
                                                        <span class="button"><input type="submit" name="submit_ubah" class="button" value="Ubah"/></span>
                                                    '.form_close().'
                                                    </td>
                                                </tr>';
                }
                else
                {
                    $i++;
                }
            }
        }
		$this->data['page_title'] .= ':. Melihat Detail Toko';		
		$this->load->view(config_item('template').'tok_cari',$this->data);
	}
	/**
	*Method untuk mengubah data toko, 
	* Data yang diubah adalah supervisor dan nomor telepon
	*/
	function ubah()
	{
		//tampilin data toko yang akan diubah
        $this->load->model('shop');
        if($this->input->post('submit_ubah'))
        {            
            $query = $this->shop->get_shop($this->input->post('shop_code'));            
            if($query->num_rows() > 0)
            {
                $this->data['shop'] = $query->row();
            }
        }        
		if($this->input->post('submit_ubah_toko'))
		{
			if($this->validate_form_tambah_toko())
			{
				$data = array(
                    'shop_code'=>$this->input->post('shop_code'),
                    'shop_name'=>$this->input->post('shop_name'),
                    'shop_initial'=>$this->input->post('shop_initial'),
					'shop_address'=>$this->input->post('shop_address'),
					'shop_phone'=>$this->input->post('shop_phone'),
					'shop_supervisor'=>$this->input->post('shop_supervisor')
				);				
				if($this->shop->update($data))
				{
                    //upload image update klo emang ada                    
                    $config['upload_path'] = 'css/images/toko/';
                    $config['allowed_types'] = 'jpg';
                    $config['overwrite'] = TRUE;
                    $config['file_name'] = strtolower($data['shop_code']); 
                    $this->load->library('upload', $config);
                    //do upload
                    $this->upload->do_upload('shop_img');
                    
					$this->data['err_msg'] = '<span style="color:green">Perubahan telah disimpan</span>';
				}
				else
				{
					$this->data['err_msg'] = '<span style="color:red">Data toko gagal disimpan, terjadi error</span>';
				}
			}
            $query = $this->shop->get_shop($data['shop_code']);            
            if($query->num_rows() > 0)
            {
                $this->data['shop'] = $query->row();
            }
		}	
			
		$this->load->view(config_item('template').'tok_ubah',$this->data);
	}
}
/* End of file toko.php */
/* Location: ./system/application/controllers/toko.php */