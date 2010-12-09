<?php
/**
*Controller user
*Handling management user : CRUD
*/
class User extends Controller {
	//User constructor
	function User() 
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
		$this->data['pages']='user';
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
	*Fungsi untuk tambah supplier
	*/
	function tambah() 
	{
		if(isset($_POST['submit_tambah_user']))
		{
			if($this->validate_form_tambah_user())
			{
				//retrieve data form 
				$data_user = array(
					'p_username'=>$this->input->post('username'),
					'p_passwd'=>md5($this->input->post('password')),					
					'p_active'=>'1'
				);
				if($this->session->userdata('p_role')=='admin')
				{
					$data_user['p_role'] = $this->input->post('role');
				}
				else 
				{
					$data_user['p_role'] = 'user';
				}
				$data_op = array(
					'op_name'=>$this->input->post('nama'),
					'op_address'=>$this->input->post('alamat'),
					'op_phone'=>$this->input->post('telepon'),
				);				
				//check dulu apakah data username sudah ada 
				$query = $this->db->get_where('pengguna',array('p_username'=>$data_user['p_username']));
				if($query->num_rows() == 0)
				{
					//insert data user
					if($this->db->insert('pengguna',$data_user))
					{
						//insert data profile
						//ambil op_id dari p_id
						$query = $this->db->get_where('pengguna',array('p_username'=>$data_user['p_username']));
						if($query->num_rows())
						{
							$user = $query->row();
							$data_op['op_id'] = $user->p_id;
							$this->db->insert('operator',$data_op);
							$this->data['notify'] = 'Pengguna berhasil ditambahkan';
						}
					}
				}
				else
				{
					$this->data['notify'] = 'Username yang dimasukan sudah dipakai';
				}
			}		
		}
		$this->load->view('user_tambah',$this->data);
	}
	/**
	* validasi form input tambah user
	*/
	function validate_form_tambah_user()
	{
		$this->load->library('form_validation');
		//setting rules
		$this->form_validation->set_rules('nama', 'Nama','required');
		$this->form_validation->set_rules('username', 'Username','required|alpha_numeric');
		$this->form_validation->set_rules('password','password','required');
		$this->form_validation->set_rules('konfirmasi_password','konfirmasi password','required|matches[password]');		
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
	*membuat list dftar user untuk keperluan rud
	*/
	function lihat($page="")
	{
		$query = $this->db->query('select * from pengguna, operator where pengguna.p_id = operator.op_id');
		 $this->data['list_user'] = '<table id="search" width = "100%" cellspacing = "7">
					    <tr id ="head">
						<td width ="10%"> User ID </td>
						<td width ="35%"> Username </td>
						<td width ="15%"> Nama </td>
						<td width ="15%"> Jabatan </td>
						<td width ="10%"> Action </td>
					    </tr>';
		foreach($query->result() as $row)
		{
			$this->data['list_user'] .= '<tr><td>'.$row->p_id.'</td>
								<td>'.$row->p_username.'</td>
								<td>'.$row->op_name.'</td>
								<td>'.ucwords($row->p_role).'</td>
								<td></td>';
		}
		$this->data['list_user'] .= '</table>';
		$this->load->view('user_cari',$this->data);
	}
}
//end of user.php