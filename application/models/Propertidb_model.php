<?php defined('BASEPATH') or exit('No direct script access allowed');

class Propertidb_model extends CI_Model
{
    private $_table = "properti";
    private $_table2 = "image";
    public $ID_P;
    public $NAMA_P;
    public $ALAMAT;
    public $TIPE;
    public $LUAS;
    public $DESKRIPSI;
    public $HARGA;
    public $LATITUDE;
    public $LONGITUDE;
    public $input_date;

    public function rules()
    {
        return [
            [
                'field' => 'n_properti',
                'label' => 'nama properti',
                'rules' => 'required'
            ],

            [
                'field' => 'almt',
                'label' => 'alamat properti',
                'rules' => 'required'
            ],

            [
                'field' => 'type',
                'label' => 'tipe properti',
                'rules' => 'required'
            ],

            [
                'field' => 'size',
                'label' => 'luas',
                'rules' => 'required'
            ],

            [
                'field' => 'desk',
                'label' => 'deskripsi',
                'rules' => 'required'
            ],

            [
                'field' => 'price',
                'label' => 'price',
                'rules' => 'required'
            ],

            [
                'field' => 'lat',
                'label' => 'latitude',
                'rules' => 'required|greater_than_equal_to[-90]|less_than_equal_to[90]'
            ],

            [
                'field' => 'long',
                'label' => 'longitude',
                'rules' => 'required|greater_than_equal_to[-180]|less_than_equal_to[180]'
            ],
        ];
    }

    public function rules2()
    {
        return [
            [
                'field' => 'n_properti',
                'label' => 'nama properti',
                'rules' => 'required'
            ],

            [
                'field' => 'almt',
                'label' => 'alamat properti',
                'rules' => 'required'
            ],

            [
                'field' => 'type',
                'label' => 'tipe properti',
                'rules' => 'required'
            ],

            [
                'field' => 'size',
                'label' => 'luas',
                'rules' => 'required'
            ],

            [
                'field' => 'desk',
                'label' => 'deskripsi',
                'rules' => 'required'
            ],

            [
                'field' => 'price',
                'label' => 'price',
                'rules' => 'required'
            ],
            [
                'field' => 'lat',
                'label' => 'latitude',
                'rules' => 'required|greater_than_equal_to[-90]|less_than_equal_to[90]'
            ]
            ,[
                'field' => 'long',
                'label' => 'longitude',
                'rules' => 'required|greater_than_equal_to[-180]|less_than_equal_to[180]'
            ],
        ];
    }

  
        public function save($dataimg = NULL)
    {
        $post = $this->input->post();
        $image = addslashes(file_get_contents($_FILES['pict']['tmp_name']));

        $this->IMG = $image;
        $this->ID_P = uniqid();
        $this->NAMA_P = $post["n_properti"];
        $this->HARGA = $post["price"];
        $this->ALAMAT = $post["almt"];
        $this->LATITUDE = $post["lat"];
        $this->LONGITUDE = $post["long"];
        $this->TIPE = $post["type"];
        $this->LUAS = $post["size"];
        $this->DESKRIPSI = $post["desk"];
        $this->input_date = date("Y-m-d h:i:s");
        return $this->db->insert($this->_table, $this);
    }
    public function update()
    {
        $post = $this->input->post();
        $this->ID_P = $post['id_prop'];
        $this->NAMA_P = $post["n_properti"];
        $this->HARGA = $post["price"];
        $this->ALAMAT = $post["almt"];
        $this->DESKRIPSI = $post["desk"];
        $this->TIPE = $post["type"];
        $this->LUAS = $post["size"];
        $this->LATITUDE = $post["lat"];
        $this->LONGITUDE = $post["long"];
        if(file_get_contents($_FILES['pict2']['tmp_name'])){
            $image = addslashes(file_get_contents($_FILES['pict2']['tmp_name']));
            $this->IMG = $image;
        }
        return $this->db->update($this->_table, $this, array('ID_P' => $post['id_prop']));
    }

    public function delete($id_p)
    {

        return $this->db->delete($this->_table, array("ID_P" => $id_p));
    }

}