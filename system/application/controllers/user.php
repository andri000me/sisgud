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
		if($this->input->post('submit_tambah_user'))
		{
			if($this->validate_form_tambah_user())
			{
				//retrieve data form 
				$data_user = array(
					'p_username'=>$this->input->post('p_username'),
					'p_passwd'=>md5($this->input->post('p_passwd')),
					'p_role'=>$this->input->post('p_role'),
					'p_active'=>'1'
				);				
				$data_op = array(
					'op_name'=>$this->input->post('op_name'),
					'op_address'=>$this->input->post('op_address'),
					'op_phone'=>$this->input->post('op_phone'),
				);				
				//check dulu apakah data username sudah ada 
                $this->load->model('pengguna');
				$query = $this->pengguna->get_pengguna_by_username($data_user['p_username']);
				if($query->num_rows() == 0)
				{
					//insert data user
					if($this->pengguna->insert($data_user))
					{
						//insert data profile
						//ambil op_id dari p_id
						$query = $this->pengguna->get_pengguna_by_username($data_user['p_username']);
						if($query->num_rows() > 0)
						{
							$user = $query->row();
							$data_op['op_id'] = $user->p_id;
							if($this->pengguna->insert_operator($data_op))
                            {
							    $this->data['err_msg'] = '<span style="color:green">Data pengguna telah disimpan</span>';
                            }
						}
					}
				}
				else
				{
					$this->data['err_msg'] = '<span style="color:red">Gagal menyimpan data. Username sudah pernah dipergunakan, pilih username yang lain ! </span>';
				}
			}		
		}
		$this->load->view(config_item('template').'user_tambah',$this->data);
	}
	/**
	* validasi form input tambah user
	*/
	function validate_form_tambah_user()
	{
		$this->load->library('form_validation');
		//setting rules
		$this->form_validation->set_rules('op_name', 'Nama','required');
		$this->form_validation->set_rules('p_username', 'Username','required|alpha_numeric');
		$this->form_validation->set_rules('p_passwd','password','required');
		$this->form_validation->set_rules('confirm','konfirmasi password','required|matches[p_passwd]');		
		//running validation
		if($this->form_validation->run() == FALSE)
		{
		    $this->data['err_msg'] = '<span style="color:red">Ada kesalahan input data. Pastikan bahwa informasi yang anda berikan sudah benar</span>';
		    return FALSE;
		}
		else
		{
		    return TRUE;
		}		
	}
	/**
    * Manajemen pengguna (RUD)
    */
    function manage($param='')
    {
        $this->load->model('pengguna');
        $query = $this->pengguna->get_all();
        if($query->num_rows() > 0)
        {
            $this->data['total_item'] = $query->num_rows();
            //setting up pagination
            $this->load->library('pagination');
            $config['base_url'] = base_url().'user/manage/';
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
                    $this->data['row_data'] .= '<tr>
                                                    <td>'.$row->p_id.'</td>                                                    
                                                    <td>'.$row->p_username.'</td>
                                                    <td>'.ucwords($row->op_name).'</td>
                                                    <td>'.$row->p_role.'</td>
                                                    <td>'.$row->op_address.'</td>
                                                    <td>'.$row->op_phone.'</td>
                                                    <td>
                                                        '.form_open('user/ubah').'
                                                            <input type="hidden" name="p_id" value="'.$row->p_id.'" />
                                                            <span class="button"><input type="submit" name="submit_ubah" class="button" value="Ubah"/></span>
                                                        '.form_close().'
                                                    </td>
                                                </td>';
                }                
                $i++;                
            }
        }
        $this->load->view(config_item('template').'user_manage',$this->data);
    }
    /**
    * Fungsi ubah pengguna
    */
    function ubah()
    {
        //tampilkan data yang akan diubah
        $this->load->model('pengguna');
        if($this->input->post('submit_ubah'))
        {
            $query = $this->pengguna->get_pengguna(array('p_id'=>$this->input->post('p_id')));            
            $this->data['pengguna'] = $query->row();
        }
        //simpan data perubahan
        if($this->input->post('submit_ubah_user'))
        {
            if($this->validate_form_tambah_user())
			{
                //retrieve data form 
				$data_user = array(					
					'p_role'=>$this->input->post('p_role')					
				);				
				$data_op = array(
					'op_name'=>$this->input->post('op_name'),
					'op_address'=>$this->input->post('op_address'),
					'op_phone'=>$this->input->post('op_phone'),
				);
                //ambil userid
                $query = $this->pengguna->get_pengguna_by_username($this->input->post('p_username'));
                $user = $query->row();
                //update
                if($this->pengguna->update_pengguna($data_user,$user->p_id))
                {
                    if($this->pengguna->update_operator($data_op,$user->p_id))
                    {
                        $this->data['err_msg'] = '<span style="color:green">Perubahan data telah disimpan</span>';
                    }
                }
                $query = $this->pengguna->get_pengguna(array('p_id'=>$user->p_id));
                $this->data['pengguna'] = $query->row();
            }
        }
        $this->load->view(config_item('template').'user_ubah',$this->data);
    }
}
//end of user.php