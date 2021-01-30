<?php
defined('BASEPATH') or exit('No direct script access allowed');

class user extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model("User_model");
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function index()
    {
        if ($this->session->userdata('username')) {
            redirect('dashboard');
        } else {
            redirect('login');
        }
    }

    public function login()
    {
        $title['title'] = 'Login';
        if ($this->session->userdata('username')) {
            redirect('');
        }
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $title);
            $this->load->view('templates/navbar', $title);
            $this->load->view('user/login');
            $this->load->view('templates/footer');
        } 
        else {  
            $this->_login();
            redirect('');
        }
    }

    private function _login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $user = $this->db->get_where('user', ['USERNAME' => $username])->row_array();  //baca : select * dari tael user where email == $email  
        if ($user) {
            if (password_verify($password, $user['PASSWORD'])) {
                $data = [
                    'username' => $user['USERNAME'],
                    'role' => $user['ROLE']
                ];
                $this->session->set_userdata($data);
            } 
            else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Password salah, silahkan coba lagi</div>');
                redirect('user/login');
            }
        } 
        else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Account is not registered! </div>');
            redirect('user/login');
        }
    }

    public function add()
    {
        $title['title'] = 'Registrasi Akun';
        $dataa['user'] = $this->db->get_where('user', ['USERNAME' => $this->session->userdata('username')])->row_array();

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[user.USERNAME]', [
            'is_unique' => 'This username has already registered!']);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.EMAIL]', [
            'is_unique' => 'This email has already registered!']);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[6]|matches[password2]', [
            'matches' => 'Password tidak sesuai',
            'min_length' => 'Password terdiri dari minimal 6 karakter'
        ]);

        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $title);
            $this->load->view('templates/navbar', $dataa);
            $this->load->view('user/register');
            $this->load->view('templates/footer');
        } 
        else {
            $data = [
                'ROLE' => "Customer",
                'NAMA' => $this->input->post('nama', true),
                'USERNAME' => htmlspecialchars($this->input->post('username', true)),
                'EMAIL' => htmlspecialchars($this->input->post('email', true)),
                'PASSWORD' => password_hash($this->input->post('password1'), PASSWORD_BCRYPT)
            ];
            $this->db->insert('user', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert"> Akun berhasil dibuat. </div>');
            $this->session->set_userdata($data);
            if($this->session->userdata('USERNAME')){
                    redirect('');
            }
            else{
                redirect('properti/');
            }
        }
    }

    public function userlist()
    {
        $title['title'] = 'Daftar Akun Pengguna';
        $dataa['user'] = $this->db->get_where('user', ['USERNAME' => $this->session->userdata('username')])->row_array();
        if ($dataa['user']['ROLE'] != 'Admin') {
            redirect("dashboard");
        }
        // TODO Membuat pagination userlist
    }

    public function delete($username)
    {
        $data['user'] = $this->db->get_where('user', ['USERNAME' => $this->session->userdata('username')])->row_array();
        if ($data['user']['USERNAME'] == $username) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Anda tidak bisa menghapus akun yang anda gunakan saat ini</div>');
            redirect('user/seeAllUser');
        } else if ($data['user']['ROLE'] == 'IT FINANCE') {
            $this->User_model->delete($username);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert"> Data berhasil dihapus.</div>');
            redirect('user/seeAllUser');
        } else {
            redirect('user/seeAllUser');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('role');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert"> You have been logout. </div>');
        redirect('');
    }

    public function forgot()
    {

    }

    public function resetPassword()
    {
      
    }

    public function _sendEmail($token)
    {

    }

    public function changePassword()
    {

    }
}
