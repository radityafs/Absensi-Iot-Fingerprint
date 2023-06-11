<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function cek_daftar($nomer)
    {
        $this->db->select('nama');
        $this->db->where('nomor_orang_tua', $nomer);
        $query = $this->db->get('user_data')->row_array();
        if (empty($query)) {
            return array('STATUS' => 'OK', 'KET' => 'ALLOWED');
        } else {
            return array('STATUS' => 'OK', 'KET' => 'NOT_ALLOWED', 'SISWA' => $query['nama']);
        }
    }

    public function daftar($nomer, $nisn, $nama)
    {
        $this->db->set('nomor_orang_tua', $nomer);
        $this->db->where(array('NISN' => $nisn, 'nama' => $nama));
        $this->db->update('user_data');
    }

    public function cek_data($nama, $nisn)
    {
        $this->db->where(array('NISN' => $nisn, 'nama' => $nama));
        $query = $this->db->get('user_data')->num_rows();
        if ($query == 0) {
            return 'NOT_FOUND';
        } else {
            return 'FOUND';
        }
    }

}
