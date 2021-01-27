<?php defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $_table = "user";

    public $EMAIL;
    public $NAMA;
    public $USERNAME;
    public $PASSWORD;

    public function rules()
    {
        return [
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|trim|valid_email|is_unique[user.EMAIL]',
                'errors' => array(
                'is_unique' => 'This email has already registered!')
            ],

            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|trim|is_unique[user.USERNAME]|min_length[6]|max_length[24]',
                'errors' => array(
                        'is_unique' => 'This username has already registered!'
                )
            ],

            [
                'field' => 'password1',
                'label' => 'Password',
                'rules' => 'required|trim|min_length[6]|matches[password2]',
                'errors' => array(
                    'matches' => 'Password tidak sesuai',
                    'min_length' => 'Password terdiri dari minimal 6 karakter'
                )
            ],
            [
                'field' => 'password2',
                'label' => 'Password',
                'rules' => 'required|trim|matches[password1]'
            ],
            [
                'field' => 'nama',
                'label' => 'Nama',
                'rules' => 'required|trim'
            ],
            [
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'required|trim'
            ]
        ];
    }

    public function index()
    {
        $data["user"] = $this->User_model->getAll();
        $this->load->view("login", $data);
    }
    public function getPagination($that = null, $limit, $start)
    {
        $response = array();
        if (!empty($that) || $that == '0') {
            $this->db->select('*');
            $this->db->like('user.USERNAME', $that, 'both');
            $this->db->order_by('USERNAME', 'asc');
            return $this->db->get('user', $limit, $start)->result();
        }
        // Select record
        $this->db->select('*');
        $this->db->order_by('USERNAME', 'asc');
        return $this->db->get('user', $limit, $start)->result();
    }

    public function getByUsername($usernm)
    {
        return $this->db->get_where($this->_table, ["USERNAME" => $usernm])->row();
    }

    public function getByEmail($email)
    {
        return $this->db->get_where($this->_table, ["EMAIL" => $email])->row_array();
    }

    public function login($usernm, $pswrd)
    {
    }
    public function getAll()
    {
        return $this->db->get($this->_table)->result();
    }

    public function delete($username)
    {
        $this->db->delete('user', array('USERNAME' => $username));
    }
    public function countquery($that = null)
    {
        if (!empty($that) || $that == '0') {
            $this->db->select('count(USERNAME) as n_row');
            $this->db->like('user.USERNAME', $that, 'both');
            return $this->db->get('user')->result();
        }

        $this->db->select('count(USERNAME) as n_row');
        return $this->db->get('user')->result();
    }
    public function deletetoken($token)
    {
        $this->db->delete('user_token', array('token' => $token));
    }
}
