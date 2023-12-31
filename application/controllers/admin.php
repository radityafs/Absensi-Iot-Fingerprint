<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        check_logged();
    }

    public function index()
    {

        $data['kehadiran'] = count($this->db->get_where('data_absensi', ['status' => 1, 'tanggal' => date('d/m/Y')])->result_array()) + count($this->db->get_where('data_absensi', ['status' => 2, 'tanggal' => date('d/m/Y')])->result_array());
        $data['terlambat'] = count($this->db->get_where('data_absensi', ['status' => 2, 'tanggal' => date('d/m/Y')])->result_array());
        $data['query'] = $this->db->get_where('data_absensi', ['tanggal' => date('d/m/Y')])->result_array();
        $data['title'] = 'Dashboard Administrator';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer', $data);

    }
}
