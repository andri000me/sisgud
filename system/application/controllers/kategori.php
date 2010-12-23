<?php
/**
*Name : Kategori
*Author : PuRwa
*version : 1.0 beta
*Description: This class is used to handle management of item category
*/
class Kategori extends Controller {
	
    var $data;
    
	/**
	*Class constructor
	*/
	function Kategori()
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
		$this->data['pages']='kategori';
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
	*Method untuk menambahkan kategori/kelompok barang baru
	*/
	function tambah()
	{
        if(isset($_POST['submit_tambah_kategori']))
        {
            //validasi form tambah kategori
            if($this->validate_form_tambah_kategori())
            {
                //ambil data form
                $this->data['cat_code'] = $this->input->post('cat_code');
                $this->data['cat_name']= $this->input->post('cat_name');
                $this->data['cat_desc']= $this->input->post('cat_desc');
                //insert data ke database
                if($this->insert_data())
                {
                    $this->data['err_msg'] = '<span style="color:green">Data kelompok barang berhasil ditambahkan</span>';                    
                }
                else
                {
                    $this->data['err_msg'] = '<span style="color:red">Gagal menambahkan, kode kelompok barang sudah dipakai</span>';                    
                }
            }            
        }        
        $this->data['page_title'] .= ':. Menambah Kelompok Barang';
        $this->load->view(config_item('template').'kb_tambah',$this->data);        
	}
    /**
    *Insert data ke dalam database
    **/
    function insert_data()
    {
        $data = array(
                'cat_code'=>$this->data['cat_code'],
                'cat_name'=>$this->data['cat_name'],
                'cat_desc'=>$this->data['cat_desc'],
                'op_code'=>$this->session->userdata('p_id'),
                'entry_date'=>date("Y-m-d")
            );
        $this->load->model('category');
        $check = $this->category->get_category($data['cat_code']);
        if($check->num_rows == 0)
        {
            if($this->category->add_category($data))
            {
                //make note for every action done by operator, writing it to database
                $data = array(
                        'trans_name'=>'Menambah kelompok barang',
                        'log_time'=> time(),
                        'p_id'=>$this->session->userdata('p_id')
                    );       
                return TRUE;             
            }            
        }
        else
        {
            return FALSE;
        }
    }
    /**
    *Validasi form tambah kategori
    **/
    function validate_form_tambah_kategori()
    {
        $this->load->library('form_validation');
        //setting rules
        $this->form_validation->set_rules('cat_code', 'kode kelompok barang','required|exact_length[3]|numeric');
        $this->form_validation->set_rules('cat_name', 'nama kelompok barang','required');
        //running validation
        if($this->form_validation->run() == FALSE)
        {
            $this->data['err_msg'] = '<span style="color:red">Terjadi kesalahan. Pastikan bahwa informasi yang diminta sudah ditulis dengan benar.</span>';
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    /**
	*Method untuk mencari/melihat kategori/kelompok barang yang sudah ada di dalam database
	*/
	function cari($param='')
	{
		//ambil keywords yang diktikkan
		if($this->input->post('submit_cari_kategori'))
		{
		    $keywords = $this->input->post('keywords');
        }
        else
        {
            $keywords = '';
        }
        //ambil data kategori berdasarkan keywords
        $this->load->model('category');
        $query = $this->category->search($keywords);
        if($query->num_rows() > 0)
        {
            $this->data['total_item'] = $query->num_rows();
            //setting up pagination
            $this->load->library('pagination');
            $config['base_url'] = base_url().'kategori/cari/';
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
                if($i>=$page_min && $i<$page_max)
                {
                    $this->data['row_data'] .= '<tr>
                                                    <td>'.++$i.'</td>
                                                    <td>'.$row->cat_code.'</td>
                                                    <td>'.$row->cat_name.'</td>
                                                    <td>'.date_to_string($row->entry_date).'</td>
                                                    <td>
                                                        '.form_open('kategori/ubah').'
                                                            <input type="hidden" name="cat_code" value="'.$row->cat_code.'" />
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
            $this->data['err_msg'] = '<span style="color:red">Data tidak ditemukan, coba kata kunci yang lain</span>';
		}		
        $this->data['page_title'] .= ':. Mencari Kelompok Barang';	
        $this->load->view(config_item('template').'kb_cari',$this->data);
	}
	/**
	* Method untuk melakukan fungsionalitas ubah kelompok barang / edit kelompok barang
	*/
	function ubah()
	{
		if($this->input->post('submit_ubah'))
        {
            $cat_code = $this->input->post('cat_code');
            //ambil data category
            $this->load->model('category');
            $query = $this->category->get_category($cat_code);
            if($query->num_rows() > 0)
            {
                $this->data['kategori'] = $query->row();
            }
        }
        if($this->input->post('submit_ubah_kategori'))
        {
            $this->load->model('category');
            //simpan datanya
            $cat_code = $this->input->post('cat_code');
            $data = array(
                'cat_code'=>$cat_code,
                'cat_name'=>$this->input->post('cat_name'),
                'cat_desc'=>$this->input->post('cat_desc')
            );
            if($this->category->update_category($data))
            {
                $this->data['err_msg'] = '<span style="color:green">Perubahan data telah disimpan</span>';
            }
            else
            {
                $this->data['err_msg'] = '<span style="color:green">Gagal menyimpan, silahkan coba lagi.</span>';
            }
            //tampilin lagi                
            $query = $this->category->get_category($cat_code);
            if($query->num_rows() > 0)
            {
                $this->data['kategori'] = $query->row();
            }
        }
		//render to browser
		$this->load->view(config_item('template').'kb_ubah',$this->data);
	}
}
/* End of file kategori.php */
/* Location: ./system/application/controllers/kategori.php */
