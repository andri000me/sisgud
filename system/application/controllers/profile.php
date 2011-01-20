<?php
/**
*Controller user
*Handling management user : CRUD
*/
class Profile extends Controller {
	//User constructor
	function Profile() 
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
		redirect('profile/view');
	}
    /**
    * Tampilkan profile pengguna
    */
    function view()
    {
        $this->load->model('pengguna');
        $query = $this->pengguna->get_pengguna(array('p_id'=>$this->session->userdata('p_id')));
        if($query->num_rows() > 0)
        {
            $this->data['pengguna'] = $query->row();           
        }
        $this->load->view(config_item('template').'profile_view',$this->data);
    }
    /**
    * fungsi untuk ubah pengguna dan ubah password
    */
    function ubah()
    {
        $this->load->model('pengguna');
        //saving changes
        if($this->input->post('submit_ubah_profile'))
        {
            if($this->validate_form_ubah())
            {
                $pengguna = array(
                    'p_username'=> $this->input->post('p_username'),
                    'p_passwd'=> md5($this->input->post('new_passwd')),
                    'p_role'=> $this->input->post('p_role')
                );
                $operator = array(
                    'op_name'=> $this->input->post('op_name'),
                    'op_phone'=> $this->input->post('op_phone'),
                    'op_address'=> $this->input->post('op_address')
                );
                //cek kalau isi password berarti ubah password
                if($this->input->post('p_passwd') && $this->input->post('new_passwd') && $this->input->post('new_passwd') == $this->input->post('new_passwd_confirm'))
                {
                    //cek apakah username dan password lamanya benar
                    $query = $this->pengguna->validate_pengguna($pengguna['p_username'],$this->input->post('p_passwd'));                    
                    if($query->num_rows() == 1)
                    {
                        if($this->pengguna->update_pengguna($pengguna,$this->session->userdata('p_id')))
                        {
                            $msg = 'password';   
                        }
                    }
                    else
                    {
                        $msg = 'error1';                        
                    }
                }
                else
                {
                    if($this->input->post('p_passwd') && $this->input->post('new_passwd') && $this->input->post('new_passwd_confirm'))
                    {
                        $msg = 'error2';         
                    }
                }
                //ubah data operator
                if($this->pengguna->update_operator($operator,$this->session->userdata('p_id')))
                {
                    if(isset($msg) && $msg == 'password')
                    {
                        $this->data['err_msg'] = '<span style="color:green">Password berhasil diperbaharui</span><br />';
                    }
                    else if(isset($msg) && $msg == 'error1')
                    {
                        $this->data['err_msg'] = '<span style="color:red">Password lama tidak cocok</span><br />';
                    }
                    else if(isset($msg) && $msg == 'error2')
                    {
                        $this->data['err_msg'] = '<span style="color:red">Password baru dan konfirmasi yang diisikan tidak cocok</span><br />';
                    }
                    else 
                    {
                        $this->data['err_msg'] = '';
                    }
                    $this->data['err_msg'] .= '<span style="color:green">Profile yang diperbaharui telah disimpan</span>';                    
                }
            }
            else
            {
                $this->data['err_msg'] = '<span style="color:red">Terjadi kesalahan, pastikan informasi yang diminta sudah diisikan dengan benar</span>';
            }
        }
        //tampilkan data yang akan diedit
        $query = $this->pengguna->get_pengguna(array('p_id'=>$this->session->userdata('p_id')));
        if($query->num_rows() > 0)
        {
            $this->data['pengguna'] = $query->row();           
        }
        $this->load->view(config_item('template').'profile_ubah',$this->data);
    }
    /**
	* validasi form input tambah user
	*/
	function validate_form_ubah()
	{
		$this->load->library('form_validation');
		//setting rules
		$this->form_validation->set_rules('op_name', 'Nama','required');
		$this->form_validation->set_rules('p_username', 'Username','required|alpha_numeric');				
		$this->form_validation->set_rules('op_phone', 'Phone','required|numeric');				
		$this->form_validation->set_rules('op_address', 'Phone','required');				
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
}

//End of profile.php
//location: system/application/controller