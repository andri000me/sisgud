<?php
class Home extends Controller {
	var $data;
	function Home()
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
		$this->data['link3'] = array(
			'href' => 'css/login.css',
			'rel' => 'stylesheet',
			'type' => 'text/css',
			'media' => 'screen'
		);
		$this->data['link_tag'] = link_tag($link1).link_tag($link2);
		
		$this->data['page_title'] = 'Sistem Inventori Gudang';
		$this->data['pages']='gudang';
        if($this->session->userdata('logged_in')==TRUE)
        {
            $this->load->helper('sisgud');
            $this->data['now_date'] = get_date();
            $this->data['userinfo'] = get_userinfo($this);
        }
	}
    /*default function to be caled*/
	function index()
	{
        if($this->session->userdata('logged_in'))
        {
	        $this->load->view('index',$this->data);	
        }
        else
        {
            $this->data['link_tag'] = link_tag($this->data['link3']);
            $this->load->view('login',$this->data);
        }
	}
    /*login function*/
    function login()
    {
        if(isset($_POST['submit_login']))
        {
            //cek form login udah diisin atw belum
            if($this->validate_login_form())
            {
                $username = $this->input->post('username');
                $passwd = md5($this->input->post('passwd'));
                //chek terdaftar atau tidak user di database
                $this->db->where(array('p_username'=>$username, 'p_passwd'=>$passwd));
                $query = $this->db->get('pengguna');
                if($query->num_rows())
                {
                    $user = $query->row();
                    //menambahkan data-data ke dalam session
                    $data = array(
                            'p_id' => $user->p_id,
                            'p_role' => $user->p_role,
                            'p_username' => $user->p_username,
                            'logged_in' => TRUE
                            );
                    $this->session->set_userdata($data);
                    redirect('home','refresh');
                }
                else
                {
                    $this->data['err_login'] = 'Kesalahan username atau password';
                    $this->data['link_tag'] = link_tag($this->data['link3']);
                    $this->load->view('login',$this->data);
                }
            }
            else
            {
                $this->data['link_tag'] = link_tag($this->data['link3']);
                $this->load->view('login',$this->data);
            }
            //cek username sm password di dtabase
        }
        
    }
    /*fungsi untuk memastikan bahwa form login telah diisi dengan benar*/
    function validate_login_form()
    {
        $this->load->library('form_validation');
        //setting rule
        $this->form_validation->set_rules('username','username','required');
        $this->form_validation->set_rules('passwd','password','required');
        if($this->form_validation->run()==FALSE)
        {
            $this->data['err_login'] = 'Username atau password tidak boleh kosong';
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    /*logout dari system*/
    function logout()
    {
        $this->session->unset_userdata('logged_in');
        redirect('home','refresh');
    }
}
/* End of file home.php */
/* Location: ./system/application/controllers/home.php */