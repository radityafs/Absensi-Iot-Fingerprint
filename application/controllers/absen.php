<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('absen_model', 'absen');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->load->view('errors/index.html');
    }

    public function mode()
    {
        if (isset($_GET['key'])) {
            $mode = $this->absen->get_mode($_GET['key']);
            if ($mode['nama_devices'] != null) {
                echo (json_encode(array('STATUS' => 'OK', 'mode' => $mode, 'KET' => 'SUCCESSFULY')));
            } else {
                echo (json_encode(array('STATUS' => 'BAD', 'mode' => null, 'KET' => 'DEVICE TIDAK TERDAFTAR')));
            }
        } else {
            echo (json_encode(array('STATUS' => 'BAD', 'KET' => 'KEY TIDAK TERDEFINISI')));
        }
    }

    public function insert()
    {
        if (isset($_GET['key']) && isset($_GET['id'])) {
            $device_status = $this->absen->device_status($_GET['key']);
            if ($device_status['KET'] == 'DEVICE AKTIF') {
                $absensi = $this->absen->insert($device_status['nama_devices'], $_GET['id']);
                echo $absensi;
            } else {
                echo (json_encode(array('STATUS' => 'BAD', 'KET' => $device_status)));
            }
        } else {
            echo (json_encode(array('STATUS' => 'BAD', 'KET' => 'KEY TIDAK TERDEFINISI')));
        }
    }
    public function addfinger()
    {
        if (isset($_GET['key']) && isset($_GET['finger-id'])) {
            $add = $this->absen->save_finger($_GET['key'], $_GET['finger-id']);
            echo $add;
        } else {
            echo (json_encode(array('STATUS' => 'BAD', 'KET' => 'KEY TIDAK TERDEFINISI')));
        }
    }
    public function getid()
    {
        if (isset($_GET['key'])) {
            $getid = $this->absen->get_id($_GET['key']);
            echo $getid;
        } else {
            echo (json_encode(array('STATUS' => 'BAD', 'KET' => 'KEY TIDAK TERDEFINISI')));
        }
    }

}
