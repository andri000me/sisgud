<?php
/**
*Name : Supplier
*Author : PuRwa
*version : 1.0 beta
*Description: This class is used to handle management of supplier
*/
class Supplier extends Controller {
	var $data;
	/**
	*Class constructor
	*/
	function Supplier()
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
    *Fungsi untuk list semua supplier yang ada
    **/
    function list_supplier($page="")
    {
        $this->data['list_sup'] = '<table id="search" width = "85%" cellspacing = "7">
                                    <tr id ="head">
                                        <td width ="15%"> Kode </td>
                                        <td width ="40%"> Nama Supplier </td>
                                        <td width ="15%"> Operator </td>
                                        <td width ="15%"> Tanggal </td>
                                    </tr>';
        $query = $this->db->get('supplier');
	//make pagination
	$this->load->library('pagination');
	$config['base_url'] = base_url().'index.php/supplier/cari';
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
		if($i>=$lower && $i <= $upper )
		{
			$this->data['list_sup'] .= '<tr>
                                            <td>'.$row->sup_code.'</td>
                                            <td>'.ucwords($row->sup_name).'</td>
                                            <td>'.$operator->p_username.'</td>
                                            <td>'.$row->entry_date.'</td>
                                        </tr>';                
		}
		$i++;
        }
        $this->data['list_sup'] .= '<tr><td colspan="4">'.$this->pagination->create_links().'</td></tr></table>';
    }
	/**
	*Method untuk menambahkan supplier baru
	*/
	function tambah()
	{
        if(isset($_POST['submit_tambah_supplier']))
        {
            //validasi form tambah supplier
            if($this->validate_form_tambah_supplier())
            {
                //ambil data form
                $this->data['sup_code'] = $this->input->post('sup_code');
                $this->data['sup_name'] = $this->input->post('sup_name');
                $this->data['sup_address'] = $this->input->post('sup_address');
                $this->data['sup_phone'] = $this->input->post('sup_phone');
                //insert data ke database
                if($this->insert_data())
                {
                    $this->data['notify'] = 'Data supplier berhasil ditambahkan';
                    $this->load->view('sup_tambah',$this->data);
                }
                else
                {
                    $this->data['notify'] = 'Gagal ditambahkan, terjadi kesalahan data';
                    $this->load->view('sup_tambah',$this->data);
                }
            }
            else
            {
                $this->load->view('sup_tambah',$this->data);
            }
        }
        else
        {
		$this->data['page_title'] .= ':. Menambah Supplier';
        //echo time();
		$this->load->view('sup_tambah',$this->data);
	    }
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
                'entry_date'=>date("Y-m-d"),
                'op_code'=>$this->session->userdata('p_id')
                );
	$check = $this->db->get_where('supplier',array('sup_code'=>$data['sup_code']));
	if($check->num_rows() == 0) 
	{
		if($this->db->insert('supplier',$data))
		{
		    //echo $this->db->last_query();exit;
		    //make note for every action done by operator, writing it to database
		    $data = array(
		            'trans_name'=>'Memasukan data supplier',
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
            $this->data['err_vald'] = 'Ada kesalahan input'.validation_errors();
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
	function cari($page="")
	{
		$this->data['page_title'] .= ':. Melihat Supplier';
		if(isset($_POST['sup_search']))
		{
		    $keywords = $this->input->post('keywords');
		    $key = $this->input->post('key');
		    if(!empty($keywords))
		    {
			$this->db->like('sup_name',$keywords);
			$this->db->or_like('sup_code',$keywords);         
		    }
				
		    $query = $this->db->get('supplier');
		    //echo $this->db->last_query();exit;
		    $i = 0;
		    $this->data['list_sup'] = '<table id="search" width = "85%" cellspacing = "7">
					    <tr id ="head">
						<td width ="10%"> Kode </td>
						<td width ="35%"> Nama Kelompok Barang </td>
						<td width ="15%"> Operator </td>
						<td width ="15%"> Tanggal </td>
						<td width ="10%"> Action </td>
					    </tr>';
		    //$query = $this->db->get('supplier');
		    foreach($query->result() as $row)
		    {
			$temp = $this->db->get_where('pengguna',array('p_id'=>$row->op_code));
			$operator = $temp->row();
			$this->data['list_sup'] .= '<tr>
							<td>'.$row->sup_code.'</td>
							<td>'.ucwords($row->sup_name).'</td>
							<td>'.$operator->p_username.'</td>
							<td>'.$row->entry_date.'</td>
							<td><a href="'.base_url().'index.php/supplier/ubah/'.$row->sup_code.'">Ubah</a></td>
						    </tr>';
			$i++;
		    }
		    $this->data['list_sup'] .= '</table>';
		    //echo $this->data['list_sup']; exit;
		    if($i == 0)
		    {
			$this->data['list_sup'] = '<p>Maaf pencarian dengan kata kunci <b>'.$keywords.'</b> tidak ditemukan</p>';
		    }
		}
		else
		{
		    //membuat list data yang ada
		    $this->list_supplier($page);     
		}
		$this->load->view('sup_cari',$this->data);
	}
	/**
	* Method untuk melakukan fungsionalitas ubah kelompok barang / edit kelompok barang
	*/
	function ubah($sup_code="")
	{
		if(!empty($sup_code))
		{
			if(isset($_POST['submit_ubah_supplier']))
			{
				if($this->validate_form_tambah_supplier())
				{
					$data = array(
						'sup_code'=>$this->input->post('sup_code'),
						'sup_name'=>strtolower($this->input->post('sup_name')),
						'sup_address'=>$this->input->post('sup_address'),
						'sup_phone'=>$this->input->post('sup_phone')
						);
					$query = $this->db->get_where('supplier',array('sup_code'=>$data['sup_code']));
					if($sup_code == $data['sup_code'] || $query->num_rows == 0)
					{
						$this->db->where('sup_code',$sup_code);
						if($this->db->update('supplier',$data))
						{
							$this->data['notify'] = 'Data supplier telah disimpan';
						}
						else
						{
							$this->data['notify'] =  'Gagal menyimpan data';
						}
					}
					else
					{
						$this->data['notify'] = 'Data supplier dengan kode <b>'.$data['sup_code'].'</b> sudah pernah dimasukkan ke dalam database';
					}
				}				
			}
			else
			{
				$query = $this->db->get_where('supplier',array('sup_code'=>$sup_code));
				if($query->num_rows() > 0)
				{
					$data = $query->row();
					$this->data['sup_code'] = $data->sup_code;
					$this->data['sup_name'] = $data->sup_name;
					$this->data['sup_address'] = $data->sup_address;
					$this->data['sup_phone'] = $data->sup_phone;
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
		$this->load->view('sup_ubah',$this->data);
	}
}
/* End of file gudang.php */
/* Location: ./system/application/controllers/gudang.php */
	
