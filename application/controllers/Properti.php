<?php

    $imgmaxsize = 500000;
    $imgacceptable = array(   
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
        );

defined('BASEPATH') or exit('No direct script access allowed');

class Properti extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('propertidb_model');
        $this->load->model('properti_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $title['title'] = 'Properti';

        $config['base_url'] = base_url('properti/index');
        $config['total_rows'] = $this->db->count_all('properti');
        $config['per_page'] = 20;
        $config["uri_segment"] = 3;
 
        $config['first_link']       = 'First';
        $config['last_link']        = 'Last';
        $config['next_link']        = 'Next';
        $config['prev_link']        = 'Prev';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';


        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        
        if (!empty($this->input->post('Search'))) {
            $id = $this->input->post('searchById');
            $this->session->set_flashdata(array("search_properti"=>$id));  
            $data['search']=$id;
            $n_row = $this->properti_model->countquery($id)[0]->n_row;
            $config['total_rows'] = $n_row;
            $data['page'] = 0;
        } 
        else{
            if($this->session->flashdata('search_properti') != NULL){
                $data['search']= $this->session->flashdata('search_properti');
                $n_row = $this->properti_model->countquery($data['search'])[0]->n_row;
                $config['total_rows'] = $n_row;
            }

            else{
                $data['search']= '';
                $config['total_rows'] = $this->db->count_all('properti');
            }
        }

        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);

        $data['user'] = $this->db->get_where('user', ['USERNAME' => $this->session->userdata('username')])->row_array();
        $data['properti'] = $this->properti_model->getPagination($data['search'], $config["per_page"], $data['page']);

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('templates/header.php', $title);
        $this->load->view('templates/navbar.php', $data);
        $this->load->view("PROPERTI/list_properti", $data);
        $this->load->view('templates/footer.php');
    }

    public function propertidetail($ID_properti = null){
        $title['title'] = 'Detail properti';
        $data["Detail"] = $this->properti_model->getById($ID_properti);
        $data['image'] = $this->properti_model->getImage($ID_properti);
        $data['user'] = $this->db->get_where('user', ['USERNAME' => $this->session->userdata('username')])->row_array();
        
        $this->load->view('templates/header.php', $title);
        $this->load->view('templates/navbar.php', $data);
        $this->load->view("PROPERTI/properti", $data);
        $this->load->view('templates/footer.php');
    }

    public function map(){
        $title['title'] = 'Detail properti';
        $data['properti'] = $this->properti_model->getNoImage();
        $data['user'] = $this->db->get_where('user', ['USERNAME' => $this->session->userdata('username')])->row_array();
        $this->load->view('templates/header.php', $title);
        $this->load->view('templates/navbar.php', $data);
        $this->load->view("PROPERTI/map", $data);
        $this->load->view('templates/footer.php');
    }

    public function isNotImage($field){
        global $imgacceptable;
        if((!in_array($_FILES[$field]['type'], $imgacceptable)) && (!empty($_FILES[$field]["type"]))) {
            return true;
        }                
        return false;
    }

    public function isTooBig($field){
        global $imgmaxsize;
        if(($_FILES[$field]['size'] >= $imgmaxsize)) {
            return true;
        }               
        return false; 
    }

    
    public function add()
    {
        $title['title'] = 'Tambah Properti';
        $data['user'] = $this->db->get_where('user', ['USERNAME' => $this->session->userdata('username')])->row_array();
        
        if ($data['user']['ROLE'] != 'Admin') {
            redirect('Properti');
        } 

        $pm = $this->propertidb_model;
        $validation = $this->form_validation;
        $validation->set_rules($pm->rules());
        $post = $this->input->post();
        if ($validation->run()) { 

                if(!empty($_FILES["pict"]['tmp_name'])){
                    if($this->isTooBig('pict')) {
                        $this->session->set_flashdata('failed', 'Ukuran gambar terlalu besar! (ukuran maksimal 500kb)');
                        redirect("properti/editproperti/".$id_p);
                    }

                    if($this->isNotImage('pict')) {
                        $this->session->set_flashdata('failed', 'Harap upload gambar dengan tipe jpeg/jpg/gif/png.');
                        redirect("properti/editproperti/".$id_p);
                    }
                }
            $pm->save();
            $this->session->set_flashdata('success', '<div class="alert alert-success" role="alert">Berhasil disimpan</div>');
            redirect("properti/add");
        }

        $this->load->view('templates/header.php', $title);
        $this->load->view('templates/navbar.php', $data);
        $this->load->view("Properti/add", $data);
        $this->load->view('templates/footer.php');
    }



    public function editproperti($id_p = null)
    {
        $title['title'] = 'Edit properti';
        $data['user'] = $this->db->get_where('user', ['USERNAME' => $this->session->userdata('username')])->row_array();

        if ($data['user']['ROLE'] == 'Admin') {
            if (empty($id_p)) redirect('properti');

            $pro = $this->properti_model;
            $validation = $this->form_validation;
            $validation->set_rules($pro->rules2());

            if ($validation->run()) {
                if(!empty($_FILES["pict2"]['tmp_name'])){
                    if($this->isTooBig('pict2')) {
                        $this->session->set_flashdata('failed', 'Ukuran gambar terlalu besar! (ukuran maksimal 500kb)');
                        redirect("properti/editproperti/".$id_p);
                    }

                    if($this->isNotImage('pict2')) {
                        $this->session->set_flashdata('failed', 'Harap upload gambar dengan tipe jpeg/jpg/gif/png.');
                        redirect("properti/editproperti/".$id_p);
                    }
                }
                    $this->properti_model->update();
                    $this->session->set_flashdata('success', 'Berhasil disimpan');
                    redirect("properti");
            }

            $data["properti"] = $pro->getById($id_p);

            if (!$data["properti"]) show_404();

            $this->load->view('templates/header.php', $title);
            $this->load->view('templates/navbar.php', $data);
            $this->load->view("properti/edit", $data);
            $this->load->view('templates/footer.php');
        } else {
            redirect('properti');
        }
    }

    public function delete($id_p)
    {
        $pro = $this->propertidb_model;
        $data['user'] = $this->db->get_where('user', ['USERNAME' => $this->session->userdata('username')])->row_array();
        if ($data['user']['ROLE'] == 'Admin') {
            $pro->delete($id_p);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert"> Properti berhasil dihapus.</div>');
        } 
        redirect('properti');
    }
}