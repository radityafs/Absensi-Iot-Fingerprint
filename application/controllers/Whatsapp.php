<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('whatsapp_model', 'whatsapp');
    }

    public function index()
    {
        echo json_encode($_POST);
        die();
    }

    public function cek_daftar()
    {
        if (isset($_POST['nomer'])) {
            $cek_daftar = $this->whatsapp->cek_daftar($_POST['nomer']);
            echo json_encode($cek_daftar);
        } else {
            echo json_encode(array('STATUS' => 'BAD', 'KET' => 'VARIABEL UNSET'));
        }
    }

    public function daftar()
    {
        if (isset($_POST['nomer']) && isset($_POST['nama']) && isset($_POST['nisn'])) {
            $cek_daftar = $this->whatsapp->cek_daftar($_POST['nomer']);
            if ($cek_daftar['KET'] == 'ALLOWED') {
                $cek_data = $this->whatsapp->cek_data($_POST['nama'], $_POST['nisn']);
                if ($cek_data == 'FOUND') {
                    $daftar = $this->whatsapp->daftar($_POST['nomer'], $_POST['nisn'], $_POST['nama']);
                    echo json_encode(array('STATUS' => 'OK', 'KET' => 'Berhasil Melakukan Pendaftaran'));
                } else {
                    echo json_encode(array('STATUS' => 'BAD', 'KET' => 'Cek Kembali data Nama & NISN'));
                }
            } else {
                echo json_encode(array('STATUS' => 'BAD', 'KET' => 'NOT_ALLOWED', 'SISWA' => $cek_daftar['SISWA']));
            }
        } else {
            echo json_encode(array('STATUS' => 'BAD', 'KET' => 'VARIABEL UNSET'));
        }
    }

}
