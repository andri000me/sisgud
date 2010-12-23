<?php
/**
*Name : Supplier
*Author : PuRwa
*version : 1.0 beta
*Description: This class is used to handle management of supplier
*/
class Suppliers extends Controller {
	var $data;
	/**
	*Class constructor
	*/
	function Suppliers()
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
	*Method untuk menambahkan supplier baru
	*/
	function tambah()
	{
        if($this->input->post('submit_tambah_supplier'))
        {
            //validasi form tambah supplier
            if($this->validate_form_tambah_supplier())
            {
                //ambil data form
                $this->data['sup_code'] = $this->input->post('sup_code');
                $this->data['sup_name'] = $this->input->post('sup_name');
                $this->data['sup_type'] = $this->input->post('sup_type');
                $this->data['sup_address'] = $this->input->post('sup_address');
                $this->data['sup_phone'] = $this->input->post('sup_phone');
                //insert data ke database
                if($this->insert_data())
                {
                    $this->data['err_msg'] = '<span style="color:green">Data supplier berhasil ditambahkan</span>';                    
                }
                else
                {
                    $this->data['err_msg'] = '<span style="color:red">Gagal menyimpan. Kode supplier sudah pernah digunakan</span>';                    
                }
            }            
        }        
		$this->data['page_title'] .= ':. Menambah Supplier';        
		$this->load->view(config_item('template').'sup_tambah',$this->data);
	    
    }
    /**
    *Fungsi untuk insert data supplier ke dalam database
    **/
    function insert_data()
    {
        $data = array(
                'sup_code'=>$this->data['sup_code'],
                'sup_name'=>strtolower($this->data['sup_name']),
                'sup_address'=>$this->data['sup_address'],
                'sup_phone'=>$this->data['sup_phone'],
                'sup_type'=>$this->data['sup_type'],
                'entry_date'=>date("Y-m-d"),
                'op_code'=>$this->session->userdata('p_id')
                );
        //check data apakah sudah pernah ditambahkan
        $this->load->model('supplier');
        $check = $this->supplier->get_supplier($data['sup_code']);
        if($check->num_rows() == 0) 
        {
            if($this->supplier->insert($data))
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
    *Validasi form tambah supplier
    **/
    function validate_form_tambah_supplier()
    {
        $this->load->library('form_validation');
        //setting rules
        $this->form_validation->set_rules('sup_code', 'Kode Supplier','required|exact_length[3]|alpha_numeric');
        $this->form_validation->set_rules('sup_name', 'Nama Supplier','required');
        $this->form_validation->set_rules('sup_address','Alamat','required');
        $this->form_validation->set_rules('sup_phone','Telepon','required|numeric');
        //running validation
        if($this->form_validation->run() == FALSE)
        {
            $this->data['err_msg'] = '<span style="color:red">Ada kesalahan input. Pastikan bahwa anda mengisikan informasi yang diminta dengan benar</span>';
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
	/**
	*Method untuk mencari atau menampilkan data supplier yang sudah ada di dalam database.
	*/
	function cari($param='')
	{
		
		if($this->input->post('submit_cari_supplier'))
		{
		    $keywords = $this->input->post('keywords');	
        }
        else
        {
            $keywords = '';
        }
        //searching
        $this->load->model('supplier');
        $query = $this->supplier->search($keywords);
        if($query->num_rows() > 0)
        {
            $this->data['total_item'] = $query->num_rows();
            //setting up pagination
            $this->load->library('pagination');
            $config['base_url'] = base_url().'suppliers/cari/';
            $config['total_rows'] = $this->data['total_item'];
            $config['per_page'] = 50;
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
                    $this->data['row_data'] .= '<tr>
                                                    <td>'.++$i.'</td>
                                                    <td>'.$row->sup_code.'</td>
                                                    <td>'.ucwords($row->sup_name).'</td>
                                                    <td>'.$row->sup_address.'</td>
                                                    <td>'.$row->sup_phone.'</td>
                                                    <td>'.ucwords($row->sup_type).'</td>
                                                    <td>
                                                        '.form_open('suppliers/ubah').'
                                                            <input type="hidden" name="sup_code" value="'.$row->sup_code.'" />
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
        else
        {
            $this->data['err_msg'] = '<span style="color:red">Data tidak ditemukan, coba kata kunci yang lain</p>';
        }		
        //display search result
        $this->data['page_title'] .= ':. Melihat Supplier';
		$this->load->view(config_item('template').'sup_cari',$this->data);
	}
	/**
	* Method untuk melakukan fungsionalitas ubah kelompok barang / edit kelompok barang
	*/
	function ubah()
	{
        //tambilin supplier yang akan diubah
        $this->load->model('supplier');
		if($this->input->post('submit_ubah'))
        {
            $query = $this->supplier->get_supplier($this->input->post('sup_code'));
            if($query->num_rows() > 0)
            {
                $this->data['supplier'] = $query->row();                
            }            	
		}
        //simpan perubahan
        if($this->input->post('submit_ubah_supplier'))
        {
            if($this->validate_form_tambah_supplier())
            {
                $data = array(
                    'sup_code'=>$this->input->post('sup_code'),
                    'sup_name'=>strtolower($this->input->post('sup_name')),
                    'sup_address'=>$this->input->post('sup_address'),
                    'sup_phone'=>$this->input->post('sup_phone'),
                    'sup_type'=>$this->input->post('sup_type')
                );
                //simpan perubahan
                if($this->supplier->update($data))
                {
                    $this->data['err_msg'] = '<span style="color:green">Perubahan telah disimpan</span>';
                }
                else
                {
                    $this->data['err_msg'] = '<span style="color:red">Gagal menyimpan data, silahkan coba lagi</span>';
                }
                //tampilih data setelah disimpan
                $query = $this->supplier->get_supplier($data['sup_code']);
                if($query->num_rows() > 0)
                {
                    $this->data['supplier'] = $query->row();                
                } 
            }            
        }        
		//render to browser
		$this->load->view(config_item('template').'sup_ubah',$this->data);
	}
}
/* End of file gudang.php */
/* Location: ./system/application/controllers/gudang.php */
	
