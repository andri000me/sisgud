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
                //insert data ke database
                if($this->insert_data())
                {
                    $this->data['notify'] = 'Data kelompok barang berhasil ditambahkan';
                    $this->load->view('kb_tambah',$this->data);
                }
                else
                {
                    $this->data['notify'] = 'Gagal menambahkan, terjadi kesalahan data';
                    $this->load->view('kb_tambah',$this->data);
                }
            } 
            else
            {
                $this->load->view('kb_tambah',$this->data);
            }
        }
        else 
        {
        	$this->data['page_title'] .= ':. Menambah Kelompok Barang';
            $this->load->view('kb_tambah',$this->data);
        }
	}
    /**
    *Insert data ke dalam database
    **/
    function insert_data()
    {
        $data = array(
                'cat_code'=>$this->data['cat_code'],
                'cat_name'=>$this->data['cat_name'],
                'op_code'=>$this->session->userdata('p_id'),
                'entry_date'=>date("Y-m-d")
            );
        $check = $this->db->get_where('category',array('cat_code'=>$data['cat_code']));
	if($check->num_rows == 0)
	{
		if($this->db->insert('category',$data))
		{
		    //make note for every action done by operator, writing it to database
		    $data = array(
		            'trans_name'=>'Menambah kelompok barang',
		            'log_time'=> time(),
		            'p_id'=>$this->session->userdata('p_id')
		        );
		        
		    if($this->db->insert('log_transaksi',$data))
		    {
		        return TRUE;
		    }          
		    
		}
		else
		{
		    return FALSE;
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
        $this->form_validation->set_rules('cat_code', 'kode kelompok barang','required|exact_length[2]|numeric');
        $this->form_validation->set_rules('cat_name', 'nama kelompok barang','required');
        //running validation
        if($this->form_validation->run() == FALSE)
        {
            $this->data['err_vald'] = 'Ada kesalahan input'.validation_errors();
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    /**
    *method untuk membuat list kategori barang
    **/
    function list_category($page="")
    {
        $this->data['list_cat'] = '<table id="search" width = "85%" cellspacing = "7">
                                    <tr id ="head">
                                        <td width ="15%"> Kode </td>
                                        <td width ="40%"> Nama Kelompok Barang </td>
                                        <td width ="15%"> Operator </td>
                                        <td width ="15%"> Tanggal </td>
                                    </tr>';
        $query = $this->db->get('category');
	//make pagination
	$this->load->library('pagination');
	$config['base_url'] = base_url().'index.php/kategori/cari';
	$config['total_rows'] = $query->num_rows();
	$config['per_page'] = '20'; 
	$this->pagination->initialize($config);	
	if(!empty($page))
	{
		$upper = $page + $config['per_page'] ;
		$lower = $page + 1;
	}
	else
	{
		$upper = $config['per_page'];
		$lower = 1;
	}
	$i=1;
	foreach($query->result() as $row)
        {
            $temp = $this->db->get_where('pengguna',array('p_id'=>$row->op_code));
            $operator = $temp->row();
		if($i >= $lower && $i <=$upper)
		{
			$this->data['list_cat'] .= '<tr>
                                            <td>'.$row->cat_code.'</td>
                                            <td>'.ucwords($row->cat_name).'</td>
                                            <td>'.$operator->p_username.'</td>
                                            <td>'.$row->entry_date.'</td>
                                        </tr>';   
		}
		$i++;
        }
        $this->data['list_cat'] .= '<tr><td>Page: '.$this->pagination->create_links().'</td></tr></table>';
    }
	/**
	*Method untuk mencari/melihat kategori/kelompok barang yang sudah ada di dalam database
	*/
	function cari($page="")
	{
		
		if(isset($_POST['submit_cari_kategori']))
		{
		    $keywords = $this->input->post('keywords');
		    $key = $this->input->post('key');
		    if(!empty($keywords))
		    {
			$this->db->like('cat_name',$keywords);
			$this->db->or_like('cat_code',$keywords);
		    }
			$query = $this->db->get('category');						
		    $i = 0;
		    $this->data['list_cat'] = '<table id="search" width = "85%" cellspacing = "7">
					    <tr id ="head">
						<td width ="5%"> Kode </td>
						<td width ="40%"> Nama Supplier </td>
						<td width ="15%"> Operator </td>
						<td width ="15%"> Tanggal </td>
						<td width = "10%"> Action </td>
					    </tr>';
		    foreach($query->result() as $row)
		    {
			$temp = $this->db->get_where('pengguna',array('p_id'=>$row->op_code));
			$operator = $temp->row();
			$this->data['list_cat'] .= '<tr>
							<td>'.$row->cat_code.'</td>
							<td>'.ucwords($row->cat_name).'</td>
							<td>'.$operator->p_username.'</td>
							<td>'.$row->entry_date.'</td>
							<td><a href="'.base_url().'index.php/kategori/ubah/'.$row->cat_code.'">Ubah</a></td>
						    </tr>';
			$i++;
		    }
		    $this->data['list_cat'] .= '</table>';
		    if($i == 0)
		    {
			$this->data['list_cat'] = '<p>Maaf pencarian dengan kata kunci <b>'.$keywords.'</b> tidak ditemukan</p>';
		    }
		}
		else 
		{
			$this->data['page_title'] .= ':. Mencari Kelompok Barang';
			$this->list_category($page);		    
		}
		$this->load->view('kb_cari',$this->data);
	}
	/**
	* Method untuk melakukan fungsionalitas ubah kelompok barang / edit kelompok barang
	*/
	function ubah($cat_code="")
	{
		if(!empty($cat_code))
		{
			if(isset($_POST['submit_ubah_kategori']))
			{
				if($this->validate_form_tambah_kategori())
				{
					$data = array(
							'cat_code'=>$this->input->post('cat_code'),
							'cat_name'=>$this->input->post('cat_name')
							);							
					$query = $this->db->get_where('category',array('cat_code'=>$data['cat_code']));
					if($cat_code == $data['cat_code'] || $query->num_rows == 0)
					{
						$this->db->where('cat_code',$cat_code);
						if($this->db->update('category',$data))
						{
							$this->data['notify'] = 'Data kelompok barang telah disimpan';
						}
						else
						{
							$this->data['notify'] =  'Gagal menyimpan data';
						}
					}
					else
					{
						$this->data['notify'] = 'Data kelompok barang dengan kode <b>'.$data['cat_code'].'</b> sudah pernah dimasukkan ke dalam database';
					}
				}				
			}
			else
			{
				$query = $this->db->get_where('category',array('cat_code'=>$cat_code));
				if($query->num_rows() > 0)
				{
					$data = $query->row();
					$this->data['cat_code'] = $data->cat_code;
					$this->data['cat_name'] = $data->cat_name;
				}
				else
				{
					$this->data['notify'] = 'Kelompok barang tidak ditemukan';
					$this->data['readonly'] = 'yes';
				}
			}
		}
		else
		{
			$this->data['notify'] = 'Gunakan fungsi ubah kelompok barang dari menu cari kelompok';
			$this->data['readonly'] = 'yes';
		}
		//render to browser
		$this->load->view('kb_ubah',$this->data);
	}
}
/* End of file kategori.php */
/* Location: ./system/application/controllers/kategori.php */
