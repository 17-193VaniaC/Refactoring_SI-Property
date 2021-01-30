<?php defined('BASEPATH') or exit('No direct script access allowed');

class Tanahdb_model extends Propertidb_model
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

}